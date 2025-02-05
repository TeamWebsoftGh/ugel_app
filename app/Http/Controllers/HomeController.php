<?php

namespace App\Http\Controllers;

use App\Models\Audit\LogActivity;
use App\Models\Auth\User;
use App\Models\Memo\Announcement;
use App\Models\Property\Property;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    private IUserService $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUserService $user)
    {
        $this->userService = $user;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $start_date = \Carbon\Carbon::now()->format('Y-m-d');
        $activities = LogActivity::getLatest();
        $employees = User::where('is_active', '=', 1)->orderByDesc('id')->get();

        $p_employees_count = $employees->where('confirmed_date', '==', null)->count();

        $birthdays = User::where('date_of_birth', '<=', $start_date)
            ->orderByRaw('DATE_FORMAT(date_of_birth, "%m-%d")')
            ->get();

        $departments = $employees->groupBy('company_id');

        $dept_count_array = [];
        $dept_name_array = [];
        $dept_bgcolor_array = [];
        $dept_hover_bgcolor_array = [];

        mt_srand(127);
        if ($departments)
        {
            foreach ($departments as $key => $dept)
            {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
                $dept_bgcolor_array[] = 'rgba(' . $r . ',' . $g . ',' . $b . ', 0.7)';
                $dept_hover_bgcolor_array[] = 'rgb(' . $r . ',' . $g . ',' . $b . ')';

                $dept_count_array[] = $dept->count();
                if ($key == null)
                {
                    $dept_name_array[] = __('No Company');
                } else
                {
                    $dept_name_array[] = $dept->first()?->company?->company_name;
                }
            }
        }

        $designations = $employees->groupBy('branch_id');

        $desig_count_array = [];
        $desig_name_array = [];
        $desig_bgcolor_array = [];
        $desig_hover_bgcolor_array = [];
        mt_srand(200);
        if ($designations)
        {
            foreach ($designations as $key => $desig)
            {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
                $desig_bgcolor_array[] = 'rgba(' . $r . ',' . $g . ',' . $b . ', 0.7)';
                $desig_hover_bgcolor_array[] = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
                $desig_count_array[] = $desig->count();
                if ($key == null)
                {
                    $desig_name_array[] = __('No Designation');
                } else
                {
                    $desig_name_array[] = $desig->first()->location->location_name;
                }
            }
        }

        $attendance_count = 0;

        $leave_count = 0;

        $projects = Property::select('id', 'property_name')->get();

        $projects_group = $projects->groupBy('project_status');

        $project_count_array = [];
        $project_name_array = [];

        foreach ($projects_group as $key => $item)
        {
            $project_count_array[] = $item->count();
            $project_name_array[] = $key;
        }

        $completed_projects = $projects->where('project_status', 'completed')->count();

        $announcements = Announcement::where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))->select('id', 'title', 'short_message')->get();

        $ticket_count = 1;//SupportTicket::where('ticket_status', 'open')->count();

        return view('home', compact('employees', 'attendance_count',
            'leave_count', 'dept_count_array', 'dept_name_array', 'dept_bgcolor_array', 'dept_hover_bgcolor_array',
            'desig_count_array', 'desig_name_array', 'desig_bgcolor_array', 'desig_hover_bgcolor_array',
            'p_employees_count', 'birthdays', 'projects','activities', 'project_count_array', 'project_name_array',
            'completed_projects', 'announcements', 'ticket_count'));
    }

}
