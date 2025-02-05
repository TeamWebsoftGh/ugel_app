<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\NewClientEvent;
use App\Mail\Admin\AccountCreatedMail;
use App\Models\Auth\Role;
use App\Models\Employees\Employee;
use App\Models\Organization\Designation;
use App\Repositories\Interfaces\IClientRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IEmployeeDetailService;
use App\Services\Interfaces\IClientService;
use App\Traits\UploadableTrait;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeDetailService extends ServiceBase implements IEmployeeDetailService
{
    use UploadableTrait;

    private IClientRepository $employeeRepo;

    /**
     * ClientService constructor.
     *
     * @param IClientRepository $employeeRepository
     */
    public function __construct(IClientRepository $employeeRepository)
    {
        parent::__construct();
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * List all the Guests
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listEmployees(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->employeeRepo->listEmployees($filter, $order, $sort);
    }

    /**
     * Create the Guests
     *
     * @param array $params
     * @return Response
     */
    public function createEmployee(array $params): Response
    {
        //Declaration
        $company = company();
        $params['company_id'] = company_id();
        $password = Str::password(12);
        $params['probation_start_date'] = $params['joining_date'];
        $params['gender'] = strtolower($params['gender']);
        $params['email'] = strtolower(trim($params['email']));
        $employee= null;

        //Process Request
        try {
            $des = Designation::find($params['designation_id']);
            $count = Employee::where('designation_id', $params['designation_id'])->count();

            if(isset($des) && $des->enforce_max_staff_count && $count >= $des->max_staff_count)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_ERROR;

                return $this->response;
            }

            try {
                $startDate = Carbon::createFromFormat(env('Date_Format'), $params['joining_date']);
                $params['probation_end_date'] = Carbon::parse($startDate->addMonths($company->probation_period??6))->format(env('Date_Format'));
            }catch (\Exception $ex)
            {
                log_error(format_exception($ex), new Employee(), "create-employee");
            }
            $params['staff_id'] = empty($params['staff_id'])?generate_staff_id():$params['staff_id'];

            $user = [];
            $user['username'] = $params['staff_id'];
            $user['name'] = $params['first_name'] .' '. $params['last_name'];
            $user['email'] = strtolower(trim($params['email']));
            $user['password'] = $password;
            $user['contact_no'] = $params['contact_no'];
            $user['is_active'] = 1;
            $user['company_id'] = $company->id;

            if (isset($params['profile_photo']) && $params['profile_photo'] instanceof UploadedFile)
            {
                $photo = $params['profile_photo'];
                $new_user = Str::slug($user['username']);
                $user['profile_photo'] = $this->uploadPublic($photo, $new_user, "profile-photos");
                $params['profile_photo'] = $user['profile_photo'] ;
            }
            DB::beginTransaction();
            $created_user = $this->userRepo->createUser($user);
            $created_user->syncRoles(array(optional(Role::firstWhere('name', 'employee'))->id));
            $created_user->companies()->sync([company_id()]);

            $params['id'] = $created_user->id;
            $params['user_id'] = $created_user->id;

            $employee = $this->employeeRepo->createEmployee($params);

            DB::commit();
            $created_user->password = $password;
            event(new NewClientEvent($created_user));


        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), new Employee(), 'create-employee-failed');
        }

        //Check if Employee was created successfully
        if (!$employee)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-employee-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $employee, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $employee;

        return $this->response;
    }

    /**
     * Find the Employee by id
     *
     * @param int $id
     *
     * @return Employee
     */
    public function findEmployeeById(int $id): Employee
    {
        return $this->employeeRepo->findEmployeeById($id);
    }

    /**
     * Update Guest
     *
     * @param array $data
     * @param Employee $employee
     * @return Response
     */
    public function updateEmployee(array $data, Employee $employee)
    {
        //Declaration
        $usr = [];
        $email = $employee->email;

        //Process Request
        try {
            if (isset($data['staff_type'])){
                $data['is_local'] = $data['staff_type'];
            }
            if (isset($data['email']) ){
                $data['email'] = strtolower(trim($data['email']));
                $usr['email'] = strtolower(trim($data['email']));
            }

            if (isset($data['phone_number'])){
                $data['phone_number'] = strtolower(trim($data['phone_number']));
                $usr['phone_number'] = $data['phone_number'];
            }

            DB::beginTransaction();
            $this->employeeRepo->updateEmployee($data, $employee);

            if(isset($data['email']) || isset($data['phone_number']))
            {
                $usr['name'] = $employee->refresh()->full_name;
                $employee->user()->update($usr);
                update_finance_user($email, $usr);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), $employee, 'update-employee-failed');
        }

        //Audit Trail
        $logAction = 'update-employee-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $employee, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Employee $employee
     * @return Response
     */
    public function deleteEmployee(Employee $employee)
    {
        //Declaration
        $result = new Response();

        //$Guest->bookings()->sync([]);
        $this->employeeRepo->delete($employee->id);

        //Audit Trail
        $logAction = 'delete-Guest-successful';
        $auditMessage = 'You have successfully deleted Guest with name '.$employee->fullname;

        log_activity($auditMessage, $employee, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }
}
