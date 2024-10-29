<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employees\Employee;
use App\Services\Interfaces\IEmployeeService;
use App\Services\Interfaces\IUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class EmployeeController extends Controller
{

    protected IUserService $userService;
    protected IEmployeeService $employeeService;
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(IUserService $userService, IEmployeeService $employeeService)
    {
        $this->middleware(['permission:read-employees'], ['only' => ['show', 'employeePDF', 'setSalary']]);
        $this->middleware(['permission:update-employees'], ['only' => ['store', 'employeePDF', 'setSalary']]);
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
        $employees = $this->employeeService->listEmployees($data);
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
					$contact_no = "<i class='text-muted las la-phone' title='Phone'></i>&nbsp;".$row->phone_number;
                    $skype_id = "<i class='text-muted las la-skype' title='Skype'></i>&nbsp;".$row->skype_id;

                    return $email.'</br>'.$contact_no.'</br>'.$skype_id;
				})
				->addColumn('action', function ($data)
				{
					$button = '';
					if (user()->can('read-employees'))
					{
						$button .= '<a href="'.route("property.employees.show", $data->id).'"  class="edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View Details"><i class="las la-eye"></i></button></a>';
						$button .= '&nbsp;';
					}
					if (user()->can('update-employees'))
					{
						$button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                        $button .= '&nbsp;';
                        $button .= '<a class="download btn-sm" target="_blank" style="background:#FF7588; color:#fff" title="PDF" href="' . route('property.employees.pdf', $data->id ) . '"><i class="las la-file-pdf" aria-hidden="true"></i></a>';
					}

					return $button;
				})
				->rawColumns(['name','company','contacts','action',])
				->make(true);
		}

		return view('employee.index');
	}


    /**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        if(user()->id == 1){

            $gh5 = PerformanceContractSupervisor::all();
            foreach ($gh5 as $r)
            {
                $e = Employee::firstWhere('old_staff_id', $r->staff_id);
                if ($e){
                    $r->staff_id = $e->staff_id;
                    $r->save();
                }
            }
        }
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
     */
	public function store(EmployeeRequest $request)
	{
        $data = $request->except('_method');

        if ($request->has("id") && $request->input("id") != null)
        {
            $employee = $this->employeeService->findEmployeeById($request->input("id"));
            $results = $this->employeeService->updateEmployee($data, $employee);
        }else{
            $results = $this->employeeService->createEmployee($data);
        }

        if ($request->ajax())
        {
            return $this->responseJson($results);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('employees.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param Employee $employee
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function show(Employee $employee)
	{
        return view('employee.show', compact('employee'));
	}


	public function destroy($id)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee'))
		{
			DB::beginTransaction();
			try
			{
				Employee::whereId($id)->delete();
				$this->unlink($id);
				User::whereId($id)->delete();

				DB::commit();
			} catch (Exception $e)
			{
				DB::rollback();

                log_error(format_exception($e), new Employee(), 'delete-employee-failed');
				return response()->json(['error' => ResponseMessage::DEFAULT_ERR_DELETE]);
			} catch (Throwable $e)
			{
				DB::rollback();

                log_error(format_exception($e), new Employee(), 'delete-employee-failed');
                return response()->json(['error' => ResponseMessage::DEFAULT_ERR_DELETE]);
			}

			return response()->json(['success' => __(ResponseMessage::DEFAULT_SUCCESS_DELETE)]);
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function unlink($employee)
	{
		$user = User::findOrFail($employee);
		$file_path = $user->profile_photo;

		if ($file_path)
		{
			$file_path = public_path('uploads/profile_photos/' . $file_path);
			if (file_exists($file_path))
			{
				unlink($file_path);
			}
		}
	}

	public function delete_by_selection(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee'))
		{
			$employee_id = $request['employeeIdArray'];

			$user = User::whereIn('id', $employee_id);

			if ($user->delete())
			{
				return response()->json(['success' => __('Data is successfully deleted')]);
			}
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function infoUpdate(Request $request, $employee)
	{
		$logged_user = auth()->user();
        $ed = Employee::findorFail($employee);

        if ($logged_user->can('modify-details-employee'))
		{
			if (request()->ajax())
			{
			    if($request->has('first_name'))
			    {
                    $validator = Validator::make($request->only('first_name', 'last_name', 'email', 'is_tax_payer', 'date_of_birth', 'gender',
                        'username', 'company_id', 'department_id', 'designation_id', 'office_shift_id', 'location_id', 'employee_type_id',
                        'marital_status', 'is_ssf_contributor'
                    ),
                        [
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'marital_status' => 'required',
                            'gender' => 'required',
                            'date_of_birth' => 'required',
                            'username' => 'sometimes|unique:users,username,' . $employee,
                            'employee_type_id' => 'required',
                            'office_shift_id' => 'required',
                        ]
                    );
                }
			    else if($request->has('email')) {
                    $validator = Validator::make($request->only('contact_no','email', 'private_email', 'address', 'city', 'state', 'country', 'zip_code'
                    ),
                        [
                            'contact_no' => 'required|phone',
                            'email' => 'sometimes|unique:employees,email,' . $employee,
                            'country' => 'required',
                            'city' => 'required',
                        ]
                    );
                }else{
                    $validator = Validator::make($request->only('joining_date','probation_start_date', 'staff_type', 'department_id', 'location_id', 'probation_end_date', 'designation_id', 'confirmed_date', 'zip_code'
                    ),
                        [
                            'joining_date' => 'required',
                            'staff_type' => 'required',
                            'designation_id' => 'required',
                            'location_id' => 'required',
                            'probation_start_date' => 'required',
                            'probation_end_date' => 'required|after:probation_start_date',
                            'department_id' => 'required',
                            'confirmed_date' => 'nullable|before_or_equal:today|after_or_equal:probation_end_date',
                        ]
                    );
                }

				if ($validator->fails())
				{
					return response()->json(['errors' => $validator->errors()->all()]);
				}

				$data = $request->except('end_date', 'exit_date', 'date_of_birth');

//                //Leave Calculation todo
//                $employee_leave_info = Employee::find($employee);
//                if ($employee_leave_info->total_leave==0) {
//                    $data['total_leave'] = $request->total_leave;
//                    $data['remaining_leave'] = $request->total_leave;
//                }
//                elseif ($request->total_leave > $employee_leave_info->total_leave) {
//                    $data['total_leave'] = $request->total_leave;
//                    $data['remaining_leave'] = $request->remaining_leave + ($request->total_leave - $employee_leave_info->total_leave);
//                }
//                elseif ($request->total_leave < $employee_leave_info->total_leave) {
//                    $data['total_leave'] = $request->total_leave;
//                    $data['remaining_leave'] = $request->remaining_leave - ($employee_leave_info->total_leave - $request->total_leave);
//                }else {
//                    $data['total_leave'] = $request->total_leave;
//                    $data['remaining_leave'] = $employee_leave_info->remaining_leave;
//                }

				$user = [];
//				$user['username'] = strtolower(trim($request->username));
//				$user['password'] = bcrypt($request->password);
//				$user ['role_users_id'] = $request->role_users_id;
				$user['contact_no'] = $request->contact_no;
				$user['is_active'] = 1;

                if (isset($data['email'])){
                    $data['email'] = strtolower(trim($request->email));
                    $user['email'] = strtolower(trim($request->email));
                }
                if (isset($data['staff_type'])){
                    $data['is_local'] = $data['staff_type'];
                }
				DB::beginTransaction();
				try
				{
				    if(isset($data['date_of_birth']) && !isset($data['end_date']))
				    {
				        $data['end_date'] = $data['date_of_birth'];
                    }
					User::whereId($employee)->update($user);
                    $ed->update($data);

					DB::commit();
				} catch (Exception $e)
				{
					DB::rollback();
                    log_error(format_exception($e), $ed, 'update-employee-failed');
					return response()->json(['error' => ResponseMessage::DEFAULT_ERR_UPDATE]);
				} catch (Throwable $e)
				{
					DB::rollback();
					return response()->json(['error' => $e->getMessage()]);
				}

				return response()->json(['success' => __('Data Updated successfully.')]);
			}
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function socialProfileShow(Employee $employee)
	{
		return view('employee.social_profile.index', compact('employee'));
	}

	public function storeSocialInfo(Request $request, $employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee') || $logged_user->id == $employee)
		{
			$data = [];
			$data['fb_id'] = $request->fb_id;
			$data['twitter_id'] = $request->twitter_id;
			$data['linkedIn_id'] = $request->linkedIn_id;
			$data['blogger_id'] = $request->blogger_id;
            $data['whatsapp_id'] = $request->whatsapp_id;
			$data['skype_id'] = $request->skype_id;

			Employee::whereId($employee)->update($data);

			return response()->json(['success' => __('Data is successfully updated')]);

		}

		return response()->json(['success' => __('You are not authorized')]);

	}

	public function indexProfilePicture(Employee $employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee'))
		{
			return view('employee.profile_picture.index', compact('employee'));
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function storeProfilePicture(Request $request, $employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee') || $logged_user->id == $employee)
		{
			$data = [];
			$photo = $request->profile_photo;
			$file_name = null;

			if (isset($photo))
			{
				$new_user = $request->employee_username;
                if ($photo instanceof UploadedFile)
                {
                    $file_name = preg_replace('/\s+/', '', $new_user) . '_' . time() . '.' . $photo->getClientOriginalExtension();
                    $photo->storeAs('profile_photos', $file_name);
                    $data['profile_photo'] = $file_name;
                }
			}

			$this->unlink($employee);

			User::whereId($employee)->update($data);

			return response()->json(['success' => 'Data is successfully updated', 'profile_picture' => $file_name]);

		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function setSalary(Employee $employee)
	{
		$logged_user = auth()->user();
		if ($logged_user->can('modify-details-employee'))
		{
			return view('employee.salary.index', compact('employee'));
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function storeSalary(Request $request, $employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('modify-details-employee'))
		{

			$validator = Validator::make($request->only('payslip_type', 'basic_salary'
			),
				[
					'basic_salary' => 'required|numeric',
					'payslip_type' => 'required',
				]
			);


			if ($validator->fails())
			{
				return response()->json(['errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			try
			{
				Employee::updateOrCreate(['id' => $employee], [
					'payslip_type' => $request->payslip_type,
					'basic_salary' => $request->basic_salary]);
				DB::commit();
			} catch (Exception $e)
			{
				DB::rollback();

				return response()->json(['error' => $e->getMessage()]);
			} catch (Throwable $e)
			{
				DB::rollback();

				return response()->json(['error' => $e->getMessage()]);
			}

			return response()->json(['success' => __('Data Added successfully.')]);


		}

		return response()->json(['error' => __('You are not authorized')]);
	}

	public function import()
	{
		if (auth()->user()->can('import-employee'))
		{
            $types = Constants::HRM_IMPORT_TYPES;
			return view('employee.import', compact("types"));
		}

		return abort(404, __('You are not authorized'));
	}

	public function importPost(Request $request)
	{
		try
		{
            if ($request->type == "employees")
            {
                Excel::queueImport(new UsersImport(), request()->file('file'));
            }else if($request->type == "contact-persons"){
                Excel::queueImport(new ContactPersonImport(), request()->file('file'));
            }else{
                $this->setErrorMessage(__("Invalid Type selected."));
                return back();
            }
		} catch (ValidationException $e)
		{
			$failures = $e->failures();

			return view('employee.importError', compact('failures'));
		}

		$this->setSuccessMessage(__('Imported Successfully'));

		return back();
	}

    public function customImport()
    {
        if (auth()->user()->can('import-employee'))
        {
            return view('employee.custom-import');
        }

        return abort(404, __('You are not authorized'));
    }

    public function customImportPost()
    {
        try
        {
            Excel::queueImport(new BulkUserUpdate(), request()->file('file'));
        } catch (ValidationException $e)
        {
            $failures = $e->failures();

            return view('employee.importError', compact('failures'));
        }

        $this->setSuccessMessage(__('Updated Successfully'));

        return back();

    }

    public function employeePDF($id)
    {
        $employee = Employee::with('user:id,profile_photo,username','company:id,company_name','department:id,department_name', 'designation:id,designation_name','officeShift:id,shift_name','role:id,name')
            ->where('id',$id)
            ->first()
            ->toArray();

        $content = view('employee.pdf', $employee);

        return $this->userService->print($content, $employee['staff_id']);
    }
}
