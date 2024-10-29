<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Hr Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'payroll', 'namespace' => 'Payroll', 'as' => 'payroll.'], function ()
{
    //Pay period
    Route::get('pay-periods/detail/{id}', 'PayPeriodController@show')->name('pay-periods.detail');
    Route::post('pay-periods/change-status/{id}', 'PayPeriodController@update')->name('pay-periods.update');
    Route::resource('pay-periods', 'PayPeriodController')->except(['create', 'show', 'store', 'update']);

    //Run Pay
    Route::get('/pay-run-history', 'PaySummaryController@processing')->name('pay-run-history');
    Route::get('/pay-run-history/detail/{id}', 'PaySummaryController@show')->name('pay-processing.detail');
    Route::get('/pay-summary', 'PaySummaryController@index')->name('pay-summary');
    Route::get('/pay-processing', 'PaySummaryController@processing')->name('pay-processing');
    Route::get('/history', 'PayHistoryController@index')->name('history');
    Route::get('/run-pay', 'ProcessPayController@processPayForm')->name('run-pay-form');
    Route::post('/run-pay', 'ProcessPayController@processPay')->name('run-pay');
    Route::post('/reverse-pay', 'ProcessPayController@reversePay')->name('reverse-pay');
    Route::post('/post-pay', 'ProcessPayController@postPay')->name('post-pay');
    Route::post('/rerun-pay', 'ProcessPayController@rerunPay')->name('rerun-pay');
    Route::get('/generatePayslip/{id}/{q?}', 'ProcessPayController@generatePayslip')->name('generate-payslip');

    //Earnings & Deductions
    Route::get('/basic-salaries/export', 'BasicSalaryController@export')->name('basic-salaries.export');
    Route::get('/basic-salaries/import', 'BasicSalaryController@import')->name('basic-salaries.import');
    Route::post('/basic-salaries/importPost', 'BasicSalaryController@importPost')->name('basic-salaries.importPost');
    Route::get('/basic-salaries', 'BasicSalaryController@index')->name('basic-salaries.index');

    Route::get('/earnings-deductions/import', 'AllowanceDeductionsController@import')->name('earnings-deductions.import');
    Route::post('/earnings-deductions/importPost', 'AllowanceDeductionsController@importPost')->name('earnings-deductions.importPost');

});
