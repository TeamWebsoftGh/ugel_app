<?php

namespace App\Http\Controllers;

use App\Models\Audit\LogActivity;
use App\Models\Auth\User;
use App\Models\Common\DocumentType;
use App\Models\Common\TravelType;
use App\Models\CustomerService\SupportTicket;
use App\Models\Timesheet\Attendance;
use App\Models\Employees\Employee;
use App\Models\Property\Award;
use App\Models\Memo\Announcement;
use App\Models\Organization\EmployeeType;
use App\Models\Task\Task;
use App\Models\Timesheet\Leave;
use App\Services\Interfaces\IUserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class DashboardController extends Controller
{
    private IUserService $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUserService $user)
    {
        $this->userService = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $start_date = Carbon::now()->format('Y-m-d');
        $activities = LogActivity::getLatest();
        $employees = User::where('is_active', '=', 1)->orderByDesc('id')->get();

        $p_employees_count = $employees->where('confirmed_date', '==', null)->count();

        $birthdays = User::where('date_of_birth', '<=', $start_date)
            ->orderByRaw('DATE_FORMAT(date_of_birth, "%m-%d")')
            ->get();

        $departments = $employees->groupBy('company_id');

        $dept_count_array = [];
        $dept_name_array = [];
        $dept_bgcolor_array = [];
        $dept_hover_bgcolor_array = [];

        mt_srand(127);
        if ($departments)
        {
            foreach ($departments as $key => $dept)
            {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
                $dept_bgcolor_array[] = 'rgba(' . $r . ',' . $g . ',' . $b . ', 0.7)';
                $dept_hover_bgcolor_array[] = 'rgb(' . $r . ',' . $g . ',' . $b . ')';

                $dept_count_array[] = $dept->count();
                if ($key == null)
                {
                    $dept_name_array[] = __('No Company');
                } else
                {
                    $dept_name_array[] = $dept->first()->company->company_name;
                }
            }
        }

        $designations = $employees->groupBy('branch_id');

        $desig_count_array = [];
        $desig_name_array = [];
        $desig_bgcolor_array = [];
        $desig_hover_bgcolor_array = [];
        mt_srand(200);
        if ($designations)
        {
            foreach ($designations as $key => $desig)
            {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
                $desig_bgcolor_array[] = 'rgba(' . $r . ',' . $g . ',' . $b . ', 0.7)';
                $desig_hover_bgcolor_array[] = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
                $desig_count_array[] = $desig->count();
                if ($key == null)
                {
                    $desig_name_array[] = __('No Designation');
                } else
                {
                    $desig_name_array[] = $desig->first()->location->location_name;
                }
            }
        }

        $attendance_count = 0;

        $leave_count = 0;

        $projects = Task::select('id', 'title')->get();

        $projects_group = $projects->groupBy('project_status');

        $project_count_array = [];
        $project_name_array = [];

        foreach ($projects_group as $key => $item)
        {
            $project_count_array[] = $item->count();
            $project_name_array[] = $key;
        }

        $completed_projects = $projects->where('project_status', 'completed')->count();

        $announcements = Announcement::where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))->select('id', 'title', 'summary')->get();

        $ticket_count = 1;//SupportTicket::where('ticket_status', 'open')->count();

        return view('dashboard.admin_dashboard', compact('employees', 'attendance_count',
            'leave_count', 'dept_count_array', 'dept_name_array', 'dept_bgcolor_array', 'dept_hover_bgcolor_array',
            'desig_count_array', 'desig_name_array', 'desig_bgcolor_array', 'desig_hover_bgcolor_array',
            'p_employees_count', 'birthdays', 'projects','activities', 'project_count_array', 'project_name_array',
            'completed_projects', 'announcements', 'ticket_count'));
    }

    public function profile()
    {
        $user = auth()->user();

        $employee = Employee::find($user->id);

        if (!$employee)
        {
            if ($user->role_users_id == 3)
            {
                return view('profile.client_profile', compact('user'));
            }

            return view('profile.user_profile', compact('user'));
        }
        else
        {
            $statuses = EmployeeType::select('id', 'emp_type_name')->get();

            $countries = DB::table('countries')->select('id', 'name')->get();
            $document_types = DocumentType::select('id', 'document_type')->get();

            $education_levels = QualificationEducationLevel::select('id', 'name')->get();
            $language_skills = QualificationLanguage::select('id', 'name')->get();
            $general_skills = QualificationSkill::select('id', 'name')->get();

            return view('profile.employee_profile', compact('user', 'employee', 'statuses',
                'countries', 'document_types', 'education_levels', 'language_skills', 'general_skills'));
        }
    }

    public function profile_update(Request $request, $id)
    {
        $user = $this->userService->findUserById($id);
        $data = $request->except("_token");

        $validator = Validator::make($request->all(),
            [
                'username' => 'required|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'contact_no' => 'required|unique:users,contact_no,' . $id,
                'profile_photo' => 'nullable|image|max:10240|mimes:jpeg,png,jpg,gif',
            ]
        );

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $photo = $request->profile_photo;

        if (isset($photo))
        {
            $new_user = $request->username;
            if ($photo->isValid())
            {
                $file_name = preg_replace('/\s+/', '', $new_user) . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('profile_photos', $file_name);
                $data['profile_photo'] = $file_name;
            }
        }

        $username = strtolower(trim($request->username));
        $contact_no = $request->contact_no;
        $email = strtolower(trim($request->email));

        $data['username'] = $username;
        $data['email'] = $email;

        $result = $this->userService->updateUser($data, $user);

        if ($user->role_users_id == 3)
        {
            Client::whereId($user->id)->update(['username' => $username, 'contact_no' => $contact_no,
                'email' => $email]);
            $this->setSuccessMessage(__('User Info Updated'));

            return redirect()->route('clientProfile');
        }

        log_activity('User Info Updated', $user, 'profile-updated');

        $this->setSuccessMessage(__('User Info Updated'));

        return redirect()->route('profile');
    }

    public function employeeProfileUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->only('first_name', 'last_name', 'private_email', 'contact_no', 'gender'
        ),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'contact_no' => 'required|numeric|unique:users,contact_no,' . $id,
                'gender' => 'required',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [];
        //$data['first_name'] = $request->first_name;
        //$data['last_name'] = $request->last_name;
        $data['gender'] = $request->gender;
        $data ['marital_status'] = $request->marital_status;

        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['country'] = $request->country;
        $data['zip_code'] = $request->zip_code;

        $data['private_email'] = strtolower(trim($request->email));
        $data['contact_no'] = $request->contact_no;

        $uData = [];

        $uData['email'] = strtolower(trim($request->email));
        $uData['contact_no'] = $request->contact_no;


        DB::beginTransaction();
        try
        {
            $user = $this->userService->findUserById($id);
            $result = $this->userService->updateUser($uData, $user);

            $employee = Employee::find($id);
            $employee->update($data);

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        } catch (Throwable $e)
        {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
        log_activity('User Info Updated', $employee, 'employee-profile-updated');

        return response()->json(['success' => __('Data Added successfully.')]);
    }

    public function employeeDashboard(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::with('department:id,department_name', 'officeShift')->findOrFail($user->id);

        $current_day_in = strtolower(Carbon::now()->format('l')) . '_in';
        $current_day_out = strtolower(Carbon::now()->format('l')) . '_out';

        $shift_in = $employee->officeShift->$current_day_in;
        $shift_out = $employee->officeShift->$current_day_out;
        $shift_name = $employee->officeShift->shift_name;

        $announcements = Announcement::where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))->where('is_notify', 1)->select('id', 'title', 'summary')->latest()->take(3)->get();

        $employee_award_count = Award::where('employee_id', $user->id)->count();

        $holidays = [];//Holiday::where('is_publish', 1)
//            ->where('end_date', '>=', now()->format('Y-m-d'))
//            ->where('company_id', $employee->company_id)
//            ->select('id', 'event_name', 'start_date', 'end_date')->latest()->take(3)->get();

        $leave_types = [];//LeaveTypeHelper::getLeaveTypes($employee);
        $travel_types = TravelType::select('id', 'arrangement_type')->get();

        $activities = LogActivity::getLatest()->where('user_id', user_id());

        $assigned_projects =[];// EmployeeProject::whereHas('project', function ($query)  {
//            return $query->where('project_status', '!=', 'completed')->select('id', 'title', 'project_status');
//        })->where('employee_id', $employee->id)->get();

        $assigned_projects_count = 0;//$assigned_projects->count();

        $assigned_tasks = SupportTicket::all();

        $assigned_tasks_count = $assigned_tasks->count();

        $assigned_tickets = SupportTicket::all();


        $assigned_tickets_count = $assigned_tickets->count();


        //checking if employee has attendance on current day
        $employee_attendance = Attendance::where('attendance_date', now()->format('Y-m-d'))
                ->where('employee_id', $employee->id)->orderBy('id', 'desc')->first() ?? null;

        //IP Check
        $ipCheck = true;


        return view('dashboard.employee_dashboard', compact('user', 'employee', 'employee_attendance',
            'shift_in', 'shift_out', 'shift_name', 'announcements','activities',
            'employee_award_count', 'holidays', 'leave_types', 'travel_types',
            'assigned_projects', 'assigned_projects_count',
            'assigned_tasks', 'assigned_tasks_count', 'assigned_tickets', 'assigned_tickets_count','ipCheck'));
    }


    public function clientDashboard()
    {
        $user = auth()->user();

        $client = Client::with('invoices', 'projects')->findOrFail($user->id);

        $paid_invoices = $client->invoices->where('status', 1);

        $paid_invoice_count = $paid_invoices->count();

        $unpaid_invoices = $client->invoices->where('status', 0);

        $unpaid_invoice_count = $unpaid_invoices->count();

        $completed_project_count = $client->projects->where('project_status', 'completed')->count();

        $in_progress_project_count = $client->projects->where('project_status', 'in_progress')->count();

        $invoice_paid_amount_raw = $paid_invoices->sum('grand_total');

        $invoice_unpaid_amount_raw = $unpaid_invoices->sum('grand_total');

        if (config('variable.currency_format') == 'suffix')
        {
            $invoice_paid_amount = $invoice_paid_amount_raw . config('variable.currency');
            $invoice_unpaid_amount = $invoice_unpaid_amount_raw . config('variable.currency');

        } else
        {
            $invoice_paid_amount = config('variable.currency') . $invoice_paid_amount_raw;
            $invoice_unpaid_amount = config('variable.currency') . $invoice_unpaid_amount_raw;
        }


        return view('dashboard.client_dashboard', compact('user', 'client',
            'paid_invoice_count', 'unpaid_invoice_count', 'completed_project_count', 'in_progress_project_count',
            'invoice_paid_amount', 'invoice_unpaid_amount'));
    }

    public function clientProfile()
    {
        $user = auth()->user();
        if ($user->role_users_id == 3)
        {
            return view('profile.client_profile', compact('user'));
        }

        return redirect('profile');
    }
}


