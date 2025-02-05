<?php


namespace App\Http\View\Composers;

use App\Models\Employees\Attendance;
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

        $data['shift_in'] = $data['employee']->officeShift->$current_day_in;
        $data['shift_out'] = $data['employee']->officeShift->$current_day_out;
        $data['shift_name'] = $data['employee']->officeShift->shift_name;

        //checking if employee has attendance on current day
        $data['employee_attendance'] = Attendance::where('attendance_date', now()->format('Y-m-d'))
                ->where('employee_id', $data['employee']->id)->orderBy('id', 'desc')->first() ?? null;

        //IP Check
       // $data['ipCheck'] = IpSetting::where('ip_address',request()->ip())->exists();

		$view->with($data);
	}

}
