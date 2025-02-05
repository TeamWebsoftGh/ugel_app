<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Property\DesignationChange;

class EmployeeDesignationChangeController extends Controller {

	public function index($employee)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('read-employees') || $logged_user->id == $employee)
		{
			if (request()->ajax())
			{
				return datatables()->of(DesignationChange::where('employee_id', $employee)->get())
					->setRowId(function ($promotion)
					{
						return $promotion->id;
					})
                    ->addColumn('old_designation_name', function ($row)
                    {
                        return $row->old_designation->designation_name;
                    })
                    ->addColumn('new_designation_name', function ($row)
                    {
                        return $row->new_designation->designation_name;
                    })
					->addColumn('action', function ($data) use ($employee,$logged_user)
					{
						$button = '';
						if (auth()->user()->can('view-details-employee') || $logged_user->id == $employee)
						{
							$button = '<button type="button" name="show_promotion" id="' . $data->id . '" class="show_promotion btn btn-success btn-sm"><i class="dripicons-preview"></i></button>';
						}

						return $button;
					})
					->rawColumns(['action'])
					->make(true);
			}
		}
	}

	public function show($id)
	{
		if (request()->ajax())
		{
			$data = DesignationChange::findOrFail($id);
			$company_name = $data->company->company_name ?? '';
			$employee_name = $data->employee->full_name;

			return response()->json(['data' => $data, 'employee_name' => $employee_name, 'company_name' => $company_name]);
		}
	}
}
