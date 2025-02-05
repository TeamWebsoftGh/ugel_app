<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Organization\EmployeeCategory;
use App\Models\Organization\OfficeShift;
use App\Repositories\Interfaces\ICompanyRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ICompanyService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class CompanyService extends ServiceBase implements ICompanyService
{
    use UploadableTrait;

    private ICompanyRepository $companyRepo;

    /**
     * CompanyService constructor.
     *
     * @param ICompanyRepository $companyRepo
     */
    public function __construct(ICompanyRepository $companyRepo){
        parent::__construct();
        $this->companyRepo = $companyRepo;
    }

    /**
     * List all the Companies
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listCompanies(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->companyRepo->listCompanies($order, $sort, $columns);
    }

    /**
     * Create Company
     *
     * @param array $params
     *
     * @return Response
     */
    public function createCompany(array $params)
    {
        //Declaration
        $company = null;

        //Process Request
        try {
            if (isset($params['logo']) && $params['logo'] instanceof UploadedFile) {
                $params['logo'] = $this->uploadPublic($params['logo'], 'logo', 'uploads/company_logo');
            }
            $company = $this->companyRepo->createCompany($params);

            OfficeShift::create([
                'shift_name' => 'Morning Shift',
                'company_id' => $company->id,
                'default_shift' => 1,
                'monday_in' => '08:00AM',
                'monday_out' => '05:00PM',
                'tuesday_in' => '08:00AM',
                'tuesday_out' => '05:00PM',
                'wednesday_in' => '08:00AM',
                'wednesday_out' => '05:00PM',
                'thursday_in' => '08:00AM',
                'thursday_out' => '05:00PM',
                'friday_in' => '08:00AM',
                'friday_out' => '05:00PM',
            ]);

            Branch::create([
                'location_name' => 'Accra',
                'short_code' => 'HQ',
                'address1' => 'Permanent',
                'city' => 'Accra',
                'state' => 'Greater Accra Region',
                'zip' => '00233',
                'country' => 1,
                'company_id' => $company->id,
                'is_active' => 1,
            ]);

            Department::create([
                'department_name' => 'Human Resource',
                'company_id' => $company->id,
                'is_builtin' => 1,
            ]);

            Department::create([
                'department_name' => 'Finance',
                'company_id' => $company->id,
                'is_builtin' => 1,
            ]);

            EmployeeCategory::create([
                'name' => 'Management',
                'level' => '60',
                'probation_duration' => 3,
                'tax_category' => 'Management',
                'company_id' => $company->id,
                'is_builtin' => 1,
            ]);

            EmployeeCategory::create([
                'name' => 'Staff',
                'level' => '20',
                'probation_duration' => 3,
                'tax_category' => 'Others',
                'company_id' => $company->id,
            ]);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Company(), 'create-company-failed');
        }

        //Check if company was created successfully
        if (!$company)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-company-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $company, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $company;

        return $this->response;
    }


    /**
     * Find the Company by id
     *
     * @param int $id
     *
     * @return Company
     */
    public function findCompanyById(int $id)
    {
        return $this->companyRepo->findCompanyById($id);
    }


    /**
     * Update Company
     *
     * @param array $params
     *
     * @param Company $company
     * @return Response
     */
    public function updateCompany(array $params, Company $company)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            if (isset($params['logo']) && $params['logo'] instanceof UploadedFile) {
                $params['company_logo'] = $this->uploadPublic($params['logo'], 'logo', 'uploads/company_logo');
            }
            $result = $this->companyRepo->updateCompany($params, $company->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $company, 'update-company-failed');
        }

        //Check if Company was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-company-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $company, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Company $company
     * @return Response
     */
    public function deleteCompany(Company $company)
    {
        //Declaration

        if($company->id == auth()->user()->company_id)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = "You cannot delete this company.";

            return $this->response;
        }
        if ($this->companyRepo->deleteCompany($company->id))
        {
            //Audit Trail
            $logAction = 'delete-company-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $company, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
    }
}
