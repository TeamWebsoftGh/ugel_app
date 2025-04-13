<?php

namespace App\Providers;

use App\Http\View\Composers\PropertyComposer;
use App\Http\View\Composers\LayoutComposer;
use App\Models\Common\FinancialYear;
use App\Models\Timesheet\LeaveType;
use App\Services\Helpers\PropertyHelper;
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
            ['property.*', 'settings.*', 'client.*', 'report.*', 'legal.*', 'billing.*','communication.*','resource.*',
                'organization.*', 'workflow.*','offers.*', 'workflow-requests.*', 'customer-service.*', 'tasks.*',
                'user-access.teams.*'],
            PropertyComposer::class
        );


        view()->composer([
            'report.*',
        ], function ($view) {
            $view->with('properties', PropertyHelper::getAllProperties());
            $view->with('report_year', date('Y'));
        });
    }

}
