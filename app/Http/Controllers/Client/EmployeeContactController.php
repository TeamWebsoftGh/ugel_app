<?php

namespace App\Http\Controllers\Client;

use App\Abstracts\Http\Controller;
use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeContact;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeContactController extends Controller
{
    protected IClientService $employeeService;
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(IClientService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @param Employee $employee
	 * @return JsonResponse
     * @throws \Exception
	 */
	public function index(Employee $employee)
	{
		if(user()->can('read-employees')||employee()->id==$employee->id)
        {
            $list = $employee->emergencyContacts()->orderByDesc('updated_at')->get();
            if (request()->ajax())
            {
                return datatables()->of($list)
                    ->setRowId(function ($row)
                    {
                        return $row->id;
                    })
                    ->addColumn('contact_type', function ($row)
                    {
                        return empty($row->type) ? '' : Constants::CONTACT_TYPES[$row->type];
                    })
                    ->addColumn('contact_relation', function ($row)
                    {
                        return empty($row->relation) ? '' : Constants::RELATIONSHIP_TYPES[$row->relation];
                    })
                    ->addColumn('email', function ($row)
                    {
                        return empty($row->personal_email) ? $row->work_email: $row->personal_email;
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
            $data['contact_types'] = Constants::CONTACT_TYPES;
            $data['relation_types'] = Constants::RELATIONSHIP_TYPES;
            $data['employee'] = $employee;
            $data['data'] = new EmployeeContact();
            return view("employee.partials.contact-person", $data);
        }
        return redirect()->route('employees.show', $employee->id);
    }

	public function edit(Employee $employee, $id)
	{
        if(request()->ajax())
        {
            $data['contact_types'] = Constants::CONTACT_TYPES;
            $data['relation_types'] = Constants::RELATIONSHIP_TYPES;
            $data['employee'] = $employee;
            $data['data'] = EmployeeContact::firstWhere(['employee_id' => $employee->id, 'id' => $id]);
            return view("employee.partials.contact-person", $data);
        }

        return redirect()->route('employees.show', $employee->id);
	}

    /**
     * @param Request $request
     * @param $employee
     * @return JsonResponse
     */
    public function store(Request $request, $employee)
    {
        $results = new Response();
        $logged_user = user();
        $validatedData = $request->validate([
            'personal_email' => 'nullable|email',
            'relation' => 'required',
            'type' => 'required',
            'work_email' => 'email|nullable',
            'contact_name' => 'required',
            'personal_phone' => 'required|phone',
            'home_phone' => 'nullable|phone',
            'work_phone' => 'nullable|phone',
        ]);
        if ($logged_user->can('create-employees')||$logged_user->id==$employee)
        {
            $data = $request->except('_token', '_method', 'id', 'employee_id');

            if ($request->has("id") && $request->input("id") != null)
            {
                $contact = EmployeeContact::firstWhere(['employee_id' => $employee, 'id' => $request->input("id")]);
                $contact?->update($data);

                log_activity('Contact person updated', $contact, 'employee-contact-persons-updated');
                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

            }else{
                $data['company_id'] =  company_id();
                $data['employee_id'] =  $employee;
                $contact = EmployeeContact::create($data);

                log_activity('Contact person Added', $contact, 'employee-contact-persons-added');

                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
            }

            return $this->responseJson($results);
        }

        abort(403);
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
                $contact = $employee->emergencyContacts()->find($id);
                $contact->delete();
                log_activity('Contact person deleted', $contact, 'employee-contact-persons-deleted');
                $results->status = ResponseType::SUCCESS;
                $results->message = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $employee, "delete-contact-person-failed");
            $results->status = ResponseType::ERROR;
            $results->message = ResponseMessage::DEFAULT_ERR_DELETE;
        }

        return $this->responseJson($results);
    }

}
