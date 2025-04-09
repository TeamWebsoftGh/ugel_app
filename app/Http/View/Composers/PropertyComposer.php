<?php


namespace App\Http\View\Composers;

use App\Helpers\StatusHelper;
use App\Models\Auth\Team;
use App\Models\Organization\Company;
use App\Models\Settings\Country;
use App\Services\Helpers\PropertyHelper;
use Exception;
use Illuminate\View\View;


class PropertyComposer
{
	public function __construct()
	{
	}

	public function compose(View $view)
	{
		$view->with([
            'property_types' => PropertyHelper::getAllPropertyTypes(),
            'property_categories' => PropertyHelper::getAllPropertyCategories(),
            'users' => PropertyHelper::getAll(),
            'teams' => Team::where('is_active', 1)->get(),
            'customers' => PropertyHelper::getAllCustomers(),
            'client_types' => PropertyHelper::getAllClientTypes(),
            'companies' => Company::select('id', 'company_name')->get(),
            'task_statuses' => StatusHelper::getAllTaskStatuses(),
            'countries' => PropertyHelper::getAllCountries()
        ]);
	}
}
