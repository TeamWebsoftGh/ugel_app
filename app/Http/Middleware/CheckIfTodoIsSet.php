<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfTodoIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (user()->hasRole('developer|sys-admin') || !settings("enforce_todo_list", false))
        {
            return $next($request);
        }
        $start_date = Carbon::now()->format('Y-m-d');
        $project = Project::with('assignedEmployees')->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', employee()->id)->where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $start_date)->first();

        // if employee
        if ($project == null && url()->current() != route("todolist.index") && url()->current() != route("projects.store"))
        {
            request()->session()->flash('message', "Set your goals for the day to continue using the application.");
            return redirect()->route("todolist.index");
        }
        else
        {
            return $next($request);
        }
    }
}
