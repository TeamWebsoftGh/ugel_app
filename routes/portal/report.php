<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Timesheet Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'reports', 'as' => 'report.', 'namespace' => 'App\Http\Controllers\Report'], function ()
{
    Route::prefix('billing')->group(function ()
    {
        Route::get('payment', 'BillingReportController@payments')->name('payments');
        Route::post('payment', 'BillingReportController@exportPayments')->name('payments');
    });

    Route::prefix('property')->group(function ()
    {
        Route::get('', 'PropertyReportController@properties')->name('properties');
        Route::post('', 'PropertyReportController@exportProperties')->name('properties');
    });

});

//Audit Trail
Route::group(['as' => 'audit.', 'prefix' => 'audit', 'namespace' => 'App\Http\Controllers\Report'], function () {
    Route::get('payroll-activity', 'AuditController@payrollActivity')->name('payroll_activity');
    Route::get('user-activity', 'AuditController@userActivity')->name('user_activity');
    Route::get('login-activity', 'AuditController@loginActivity')->name('login_activity');
    Route::get('error-logs', 'AuditController@errorLogs')->name('error_logs');
});
