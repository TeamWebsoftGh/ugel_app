<?php

namespace App\Http\Controllers\Client;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeWorkExperience;
use App\Services\Helpers\Response;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeWorkExperienceController extends Controller
{
    use JsonResponseTrait;

	public function index(Employee $employee)
	{
        if(user()->can('read-employees')||employee()->id==$employee->id)
        {
            $list = $employee->work_experiences()->orderByDesc('updated_at')->get();
			if (request()->ajax())
			{
				return datatables()->of($list)
					->setRowId(function ($work_experience)
					{
						return $work_experience->id;
					})
                    ->addColumn('action', function ($data)
                    {
                        $button = '';
                        if (user()->can('update-employees')||employee()->id==$data->employee_id)
                        {
                            $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                            $button .= '&nbsp;';
                        }
                        if (user()->can('update-employees'))
                        {
                            $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                        }

                        return $button;
                    })
					->rawColumns(['action'])
					->make(true);
			}
		}
	}

    public function create(Employee $employee)
    {
        if(request()->ajax())
        {
            $data['employee'] = $employee;
            $data['data'] = new EmployeeWorkExperience();
            return view("employee.partials.work-experience", $data);
        }
        return redirect()->route('employees.show', $employee->id);
    }

	public function store(Request $request,$employee)
	{
        $results = new Response();
		$logged_user = auth()->user();
        $validatedData = $request->validate([
            'company_name' => 'required',
            'job_title' => 'required',
            'start_date' =>'required|date|before_or_equal:today',
            'end_date' =>'required|date|after_or_equal:start_date',
        ]);
        if(user()->can('update-employees')||employee()->id==$employee->id)
		{
            $data = $request->except('_token', '_method', 'id', 'employee_id');

            if ($request->has("id") && $request->input("id") != null)
            {
                $contact = EmployeeWorkExperience::firstWhere(['employee_id' => $employee, 'id' => $request->input("id")]);
                $contact?->update($data);

                log_activity('Employee Work Experience updated', $contact, 'employee-work-experience-updated');
                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

            }else{
                $data['company_id'] =  company_id();
                $data['employee_id'] =  $employee;
                if($logged_user->can('update-employees')){
                    $data['status'] =  "approved";
                }
                $contact = EmployeeWorkExperience::create($data);

                log_activity('Employee Work Experience Added', $contact, 'employee-work-experience-added');

                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
            }

            return $this->responseJson($results);
        }

        abort(403);
	}

    public function edit(Employee $employee, $id)
	{
        if(request()->ajax())
        {
            $data['employee'] = $employee;
            $data['data'] = $employee->work_experiences()->find($id);
            return view("employee.partials.work-experience", $data);
        }
        return redirect()->route('employees.show', $employee->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return JsonResponse
     */
	public function destroy(Employee $employee, $id)
	{
        $results = new Response();
        try {
            if (user()->can('update-employees')||user()->id==$id)
            {
                $contact = $employee->work_experiences()->find($id);
                $contact->delete();
                log_activity('Employee Work Experience deleted', $contact, 'employee-work-experience-deleted');
                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $employee, "delete-work-experience-failed");
            $results->status = ResponseType::ERROR;
            $results->message = ResponseMessage::DEFAULT_ERR_DELETE;
        }

        return $this->responseJson($results);
	}

}
