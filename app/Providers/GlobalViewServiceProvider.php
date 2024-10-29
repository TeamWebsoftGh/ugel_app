<?php

namespace App\Providers;

use App\Http\View\Composers\EmployeeComposer;
use App\Http\View\Composers\LayoutComposer;
use App\Models\Common\FinancialYear;
use App\Models\Timesheet\LeaveType;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class GlobalViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['layout.main', 'layout.admin.main', 'projects.invoices.show', 'dashboard', 'layout.client', 'frontend.Layout.navigation', 'layout.main'],
            LayoutComposer::class
        );

        View::composer(
            ['property.*', 'settings.*', 'payroll.*', 'report.*', 'employee.*', 'timesheet.*','memo.*','resource.*',
                'organization.*', 'workflow.*','offers.*', 'workflow-requests.*', 'customer-service.*', 'tasks.*',
                'configuration.leave-types.*'],
            EmployeeComposer::class
        );

        view()->composer([
            'timesheet.partials*',
            ], function ($view) {
            $view->with('leave_years', FinancialYear::pluck('year'));
            $view->with('leave_types', $this->getLeaveTypes());
            $view->with('leave_type', $this->getLeaveTypes()->first());
            $view->with('leave_year', date('Y'));
        });
    }

    /**
     * Get all the categories
     *
     */
    private function getLeaveTypes()
    {
        return LeaveType::select('id', 'leave_type_name', 'allocated_days')->get();
    }
}
