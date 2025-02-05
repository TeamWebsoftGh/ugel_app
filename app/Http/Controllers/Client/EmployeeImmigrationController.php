<?php

namespace App\Http\Controllers\Client;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeImmigration;
use App\Models\Employees\EmployeeWorkExperience;
use App\Models\Settings\Country;
use App\Services\Helpers\Response;
use App\Traits\JsonResponseTrait;
use App\Traits\UploadableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeImmigrationController extends Controller
{
    use JsonResponseTrait, UploadableTrait;

	public function index(Employee $employee)
	{
        if(user()->can('read-employees')||employee()->id==$employee->id)
        {
            $list = $employee->immigrations()->orderByDesc('updated_at')->get();
			if (request()->ajax())
			{
				return datatables()->of($list)
					->setRowId(function ($immigration)
					{
						return $immigration->id;
					})
					->addColumn('document', function ($row)
					{
						if ($row->document_file)
						{
							return $row->document_number . '<br><h6><a href="' . route('immigrations_document.download', $row->id) . '">' . __('Download File') . '</a></h6>';
						} else
						{
							return $row->document_number;
						}
					})
					->addColumn('country', function ($row)
					{
						return $row->country->name;
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
					->rawColumns(['action', 'document'])
					->make(true);
			}
		} else
		{
			abort(403);
		}
	}

    public function create(Employee $employee)
    {
        if(request()->ajax())
        {
            $data['employee'] = $employee;
            $data['countries'] = Country::all();
            $data['data'] = new EmployeeImmigration();
            return view("employee.partials.immigration", $data);
        }
        return redirect()->route('employees.show', $employee->id);
    }

	public function store(Request $request, Employee $employee)
	{
        $results = new Response();

        $validatedData = $request->validate([
            'document_number' => 'required|unique:employee_immigrations',
            'issue_date' => 'required|date|before_or_equal:today',
            'expiry_date' => 'required|date|after_or_equal:issue_date',
            'eligible_review_date' => 'required|date',
            'country_id' => 'required',
            'document_file' => 'nullable|file|max:10240|mimes:jpeg,png,jpg,gif,ppt,pptx,doc,docx,pdf',
        ]);

        try {
            if(user()->can('update-employees')||employee()->id==$employee->id)
            {
                $data = $request->except('_token', '_method', 'id', 'employee_id');

                if ($request->has("id") && $request->input("id") != null)
                {
                    $immigration = EmployeeImmigration::firstWhere(['employee_id' => $employee->id, 'id' => $request->input("id")]);
                    $immigration?->update($data);

                    if (isset($data['document_file'])) {
                        $files = collect([$data['document_file']]);
                        $this->saveDocuments($files, $immigration, $immigration->document_number, $employee->id);
                    }

                    log_activity('Employee Work Permit updated', $immigration, 'employee-work-permit-updated');
                    $results->status = ResponseType::SUCCESS;
                    $results->message = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

                }else{
                    $data['company_id'] =  company_id();
                    $data['employee_id'] =  $employee->id;
                    if(user()->can('update-employees')){
                        $data['status'] =  "approved";
                    }
                    $immigration = EmployeeImmigration::create($data);

                    if (isset($data['document_file'])) {
                        $files = collect([$data['document_file']]);
                        $this->saveDocuments($files, $immigration, $immigration->document_number, $employee->id);
                    }

                    log_activity('Employee Work Permit Added', $immigration, 'create-employee-work-permit-successful');

                    $results->status = ResponseType::SUCCESS;
                    $results->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
                }

                return $this->responseJson($results);
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $employee, "create-update-work-permit-failed");
            $results->status = ResponseType::ERROR;
            $results->message = ResponseMessage::DEFAULT_ERROR;

            return $this->responseJson($results);
        }

		return response()->json(['success' => __('You are not authorized')]);

	}

	public function edit(Employee $employee, $id)
	{
        if(request()->ajax())
        {
            $data['employee'] = $employee;
            $data['countries'] = Country::all();
            $data['data'] = $employee->immigrations()->findOrFail($id);
            return view("employee.partials.immigration", $data);
        }
        return redirect()->route('employees.show', $employee->id);
	}


	public function unlink($id)
	{

		$immigration = EmployeeImmigration::findOrFail($id);
		$file_path = $immigration->document_file;

		if ($file_path)
		{
			$file_path = public_path('uploads/immigration_documents/' . $file_path);
			if (file_exists($file_path))
			{
				unlink($file_path);
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Employee $employee, $id)
    {
        $results = new Response();
        try {
            if (user()->can('update-employees')||user()->id==$id)
            {
                $contact = $employee->immigrations()->findOrFail($id);
                $contact->delete();
                log_activity('Employee Work Permit deleted', $contact, 'employee-work-permit-deleted');
                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $employee, "delete-work-permit-failed");
            $results->status = ResponseType::ERROR;
            $results->message = ResponseMessage::DEFAULT_ERR_DELETE;
        }

        return $this->responseJson($results);
    }

	public function download($id)
	{
		$file = EmployeeImmigration::findOrFail($id);

		$file_path = $file->document_file;

		$download_path = public_path("uploads/" . $file_path);

		if (file_exists($download_path))
		{
			$response = response()->download($download_path);

			return $response;
		} else
		{
			return abort('404', __('File not Found'));
		}
	}


}
