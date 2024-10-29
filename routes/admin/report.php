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
    Route::prefix('property')->group(function ()
    {
        Route::get('attendance', 'HrmReportController@attendance')->name('attendance');
        Route::get('training', 'HrmReportController@training')->name('training');
        Route::get('employees', 'HrmReportController@employees')->name('employees');
    });
    Route::prefix('payroll')->group(function ()
    {
        Route::get('paye-monthly-return', 'PayrollReportController@graMonthlyReturnForm')->name('paye-monthly-return');
        Route::post('paye-monthly-return', 'PayrollReportController@graMonthlyReturn')->name('paye-monthly-return');
        Route::get('income-tax', 'PayrollReportController@incomeTaxForm')->name('income-tax');
        Route::post('income-tax', 'PayrollReportController@incomeTax')->name('income-tax');
        Route::get('ssnit', 'PayrollReportController@ssnitForm')->name('ssnit');
        Route::post('ssnit', 'PayrollReportController@ssnit')->name('ssnit');
        Route::get('net-income', 'PayrollReportController@netIncomeForm')->name('net-income');
        Route::post('net-income', 'PayrollReportController@netIncome')->name('net-income');
        Route::get('pay-summary', 'PayrollReportController@paySummaryForm')->name('pay-summary');
        Route::post('pay-summary', 'PayrollReportController@paySummary')->name('pay-summary');
        Route::get('payslip', 'ReportController@payslip')->name('payslip');
        Route::get('pension', 'ReportController@pension')->name('pension');
    });
    Route::get('project', 'ReportController@project')->name('project');
    Route::get('task', 'ReportController@task')->name('task');
    Route::get('account', 'ReportController@account')->name('account');
    Route::get('expense', 'ReportController@expense')->name('expense');
    Route::get('deposit', 'ReportController@deposit')->name('deposit');
    Route::get('transaction', 'ReportController@transaction')->name('transaction');
});

//Audit Trail
Route::group(['as' => 'audit.', 'prefix' => 'audit', 'namespace' => 'Report'], function () {
    Route::get('payroll-activity', 'AuditController@payrollActivity')->name('payroll_activity');
    Route::get('user-activity', 'AuditController@userActivity')->name('user_activity');
    Route::get('login-activity', 'AuditController@loginActivity')->name('login_activity');
    Route::get('error-logs', 'AuditController@errorLogs')->name('error_logs');
});
