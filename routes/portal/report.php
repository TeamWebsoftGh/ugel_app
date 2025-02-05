<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Timesheet Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'report', 'as' => 'report.', 'namespace' => 'Report'], function ()
{
    Route::prefix('hrm')->group(function ()
    {
        Route::get('attendance', 'HrmReportController@attendance')->name('attendance');
        Route::get('training', 'HrmReportController@training')->name('training');
        Route::get('employees', 'HrmReportController@employees')->name('employees');
    });

    Route::get('project', 'ReportController@project')->name('project');
    Route::get('task', 'ReportController@task')->name('task');
    Route::get('account', 'ReportController@account')->name('account');
    Route::get('expense', 'ReportController@expense')->name('expense');
    Route::get('deposit', 'ReportController@deposit')->name('deposit');
    Route::get('transaction', 'ReportController@transaction')->name('transaction');
});

//Audit Trail
Route::group(['as' => 'audit.', 'prefix' => 'audit', 'namespace' => 'App\Http\Controllers\Report'], function () {
    Route::get('payroll-activity', 'AuditController@payrollActivity')->name('payroll_activity');
    Route::get('user-activity', 'AuditController@userActivity')->name('user_activity');
    Route::get('login-activity', 'AuditController@loginActivity')->name('login_activity');
    Route::get('error-logs', 'AuditController@errorLogs')->name('error_logs');
});
