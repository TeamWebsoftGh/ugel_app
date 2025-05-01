<?php


namespace App\Http\View\Composers;

use Carbon\Carbon;
use Exception;
use Illuminate\View\View;


class LayoutComposer {

	private $translation;

	public function __construct()
	{
		//$this->translation = $translation;
	}

	public function compose(View $view)
	{
        //$data['languages'] = $this->translation->allLanguages();

        $data['employee'] = employee();
        $current_day_in = strtolower(Carbon::now()->format('l')) . '_in';
        $current_day_out = strtolower(Carbon::now()->format('l')) . '_out';


        //IP Check
       // $data['ipCheck'] = IpSetting::where('ip_address',request()->ip())->exists();

		$view->with($data);
	}

}
