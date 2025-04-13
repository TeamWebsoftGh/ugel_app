<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Timesheet Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'report', 'as' => 'report.', 'namespace' => 'App\Http\Controllers\Report'], function ()
{
    Route::prefix('billing')->group(function ()
    {
        Route::get('payments', 'BillingReportController@payments')->name('payments');
        Route::post('payments', 'BillingReportController@exportPayments')->name('payments');
    });

    Route::prefix('properties')->group(function ()
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
