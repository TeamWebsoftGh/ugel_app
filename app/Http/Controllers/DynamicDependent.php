<?php

namespace App\Http\Controllers;

use App\Helpers\LeaveTypeHelper;
use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Organization\Designation;
use App\Services\Helpers\EmployeeHelper;
use App\Traits\LeaveTrait;
use Illuminate\Http\Request;

class DynamicDependent extends Controller
{
    use LeaveTrait;

	public function fetchDepartment(Request $request)
	{
		$value = $request->get('value');
		$data = EmployeeHelper::getAllDepartments();
		$output = '';
		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->department_name . '</option>';
		}

		return $output;
	}

    public function fetchBranch(Request $request)
    {
        $value = $request->get('value');
        $data = EmployeeHelper::getAllLocations();
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->branch_name . '</option>';
        }

        return $output;
    }

    public function fetchSubsidiaries(Request $request)
    {
        $data = EmployeeHelper::getAllSubsidiaries();
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->name . '</option>';
        }

        return $output;
    }

	public function fetchUnits(Request $request)
	{
		$data = EmployeeHelper::getAllUnits($request->department);
        $output = '';
		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->unit_name . '</option>';
		}

		return $output;
	}

	public function fetchEmployee(Request $request)
	{
		$value = $request->get('value');
		$data = EmployeeHelper::getAll();
		$output = '';
		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->fullname . ' - ' . $row->staff_id . '</option>';
		}

		return $output;
	}

    public function fetchEmployeeDetails(Request $request)
    {
        $value = $request->get('value');
        $data = Employee::find($value);

        return $data;
    }

	public function fetchEmployeeDepartment(Request $request)
	{
		$value = $request->get('value');
		$data = Employee::wheredepartment_id($value)->groupBy('first_name')->get();
		$output = '';
		foreach ($data as $row)
		{
            $output .= '<option value=' . $row->id . '>' . $row->fullname . ' - ' . $row->staff_id . '</option>';
		}

		return $output;
	}

	public function fetchDesignationDepartment(Request $request)
	{
		$value = $request->get('value');
		$data = Designation::wheredepartment_id($value)->get();
		$output = '';

		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->designation_name . '</option>';
		}

		return $output;
	}


	public function companyEmployee(SupportTicket $ticket){
		$value = $ticket->company_id;
		$data = Employee::whereCompany_id($value)->groupBy('first_name')->get();
		$output = '';
		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->first_name . ' ' . $row->last_name . '</option>';
		}

		return $output;
	}

    public function fetchLeaveTypes(Request $request)
    {
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $employee = Employee::find($value);
        $data = $this->getLeaveTypes($employee);
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->$dependent . '</option>';
        }

        return $output;
    }

	public function fetchCandidate(Request $request)
	{
		$value = $request->get('value');

		$data = JobCandidate::whereJob_id($value)->groupBy('full_name')->get();
		$output = '';
		foreach ($data as $row)
		{
			$output .= '<option value=' . $row->id . '>' . $row->full_name . '</option>';
		}

		return $output;
	}

}
