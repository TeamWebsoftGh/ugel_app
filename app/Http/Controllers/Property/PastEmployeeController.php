<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Models\Employees\Employee;
use App\QualificationLanguage;
use App\QualificationSkill;
use App\Services\Interfaces\IEmployeeService;
use App\Services\Interfaces\IUserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PastEmployeeController extends Controller
{
    protected IUserService $userService;
    protected IEmployeeService $employeeService;
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(IUserService $userService, IEmployeeService $employeeService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $employees = $this->employeeService->listPastEmployees($data);
        if (request()->ajax())
        {
            return datatables()->of($employees)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->setRowAttr([
                    'data-target' => function($user) {
                        return '#employee-content';
                    },
                ])
                ->addColumn('name', function ($row)
                {
                    $url = asset($row->user->user_image);
                    $profile_photo = '<img src="'. $url .'" class="avatar-sm rounded-circle me-2"/>';

                    $name  = "<span class='text-body fw-medium'>".$row->full_name. "</span>";
                    $gender= "<span>Gender: &nbsp;".($row->gender ?? ''). "</span>";
                    $payslip_type = "<span>Staff Id: &nbsp;".($row->staff_id ?? ''). "</span>";

                    return "<div class='d-flex'>
									<div class='mr-2'>".$profile_photo."</div>
									<div>"
                        .$name.'</br>'.$gender.'</br>'.$payslip_type;
                    "</div>
								</div>";
                })
                ->addColumn('company', function ($row)
                {
                    $department  = "<span>Department : ".($row->department->department_name ?? ''). "</span>";
                    $designation = "<span>Designation : ".($row->designation->designation_name ?? ''). "</span>";
                    $location = "<span>Branch : ".($row->branch->branch_name ?? ''). "</span>";

                    return $department.'</br>'.$designation.'<br/>'.$location;
                })
                ->addColumn('contacts', function ($row)
                {
                    $email = "<i class='las la-envelope text-muted' title='Email'></i>&nbsp;".$row->email;
                    $contact_no = "<i class='text-muted las la-phone' title='Phone'></i>&nbsp;".$row->contact_no;
                    $skype_id = "<i class='text-muted las la-skype' title='Skype'></i>&nbsp;".$row->skype_id;

                    return $email.'</br>'.$contact_no.'</br>'.$skype_id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '';
                    if (user()->can('read-employees'))
                    {
                        $button .= '<a href="'.route("employees.show", $data->id).'"  class="edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View Details"><i class="las la-eye"></i></button></a>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('update-employees'))
                    {
                        $button .= '<a class="download btn-sm" target="_blank" style="background:#FF7588; color:#fff" title="PDF" href="' . route('employees.pdf', $data->id ) . '"><i class="las la-file-pdf" aria-hidden="true"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['name','company','contacts','action',])
                ->make(true);
        }

        return view('property.past-employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function show(int $id)
    {
        $employee = $this->employeeService->listPastEmployees()->firstOrFail($id);
        return view('property.past-employees.show', compact("employee"));
    }

    public function import()
    {
        if (auth()->user()->can('import-employee'))
        {
            return view('past-employees.import');
        }

        return abort(404, __('You are not authorized'));
    }

    public function importPost()
    {
        try
        {
            Excel::queueImport(new PastEmployeesImport(), request()->file('file'));
        } catch (ValidationException $e)
        {
            $failures = $e->failures();

            return view('past-employees.importError', compact('failures'));
        }

        $this->setSuccessMessage(__('Imported Successfully'));

        return back();

    }
}
