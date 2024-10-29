<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Models\Common\AwardType;
use App\Models\Employees\Employee;
use App\Models\Property\Award;

class EmployeeAwardController extends Controller {

	//
	public function index($employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('read-employees') || $logged_user->id == $employee)
		{
			if (request()->ajax())
			{
				return datatables()->of(Award::where('employee_id', $employee)->get())
					->setRowId(function ($award)
					{
						return $award->id;
					})
					->addColumn('awardType', function ($row)
					{
						return empty($row->award_type->award_name) ? '' : $row->award_type->award_name;
					})
                    ->addColumn('action', function ($data)
                    {
                        $button = '<button type="button" name="show" data-id="' . $data->id . '" class="show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                        $button .= '&nbsp;';
                        if (user()->can('update-employees'))
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


    public function edit(Employee $employee, $id)
    {
        if(request()->ajax())
        {
            $data['award_types'] = AwardType::enabled()->select('id', 'name')->get();
            $data['employee'] = $employee;
            $data['data'] = Award::where(['employee_id' => $employee, 'id' => $id])->firstOrFail();
            return view("employee.partials.awards", $data);
        }
        return redirect()->route('employees.show', $employee->id);
    }
}
