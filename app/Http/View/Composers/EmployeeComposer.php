<?php


namespace App\Http\View\Composers;

use App\Helpers\StatusHelper;
use App\Models\Organization\Company;
use App\Models\Settings\Country;
use App\Services\Helpers\EmployeeHelper;
use Exception;
use Illuminate\View\View;


class EmployeeComposer
{
	public function __construct()
	{
	}

	public function compose(View $view)
	{
		$view->with([
            'service_types' => EmployeeHelper::getAllServiceTypes(),
            'users' => EmployeeHelper::getAll(),
            'companies' => Company::select('id', 'company_name')->get(),
            'task_statuses' => StatusHelper::getAllTaskStatuses(),
            'countries' => Country::all()
        ]);
	}
}
