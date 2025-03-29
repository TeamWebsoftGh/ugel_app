<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    includeRouteFiles(__DIR__.'/portal/');

    Route::namespace('App\Http\Controllers\Account')->group(function () {
        // Route::get('account', 'AccountController@index')->name('account.index');
        Route::get('account/edit', 'AccountController@edit')->name('account.edit');
        Route::put('account/edit', 'AccountController@update')->name('account.update');
        Route::get('account/change-password', 'ChangePasswordController@edit')->name('account.change-password');
        Route::put('account/change-password', 'ChangePasswordController@update')->name('account.change-password');
        Route::post('account/change-password', 'ChangePasswordController@update')->name('account.change-password');
    });

    Route::get('markAsRead', 'RouteClosureHandlerController@markAsReadNotification')->name('markAsRead');
    Route::get('/all/notifications', 'RouteClosureHandlerController@allNotifications')->name('seeAllNoti');
    Route::get('clearAll', 'RouteClosureHandlerController@clearAll')->name('clearAll');

    Route::namespace('App\Http\Controllers\Ajax')->group(function () {
        Route::post('dynamic_dependent/fetch-company', 'DynamicDependent@fetchCompany')->name('dynamic_company');
        Route::post('dynamic_dependent/fetch_subsidiary', 'DynamicDependent@fetchSubsidiaries')->name('dynamic_subsidiary');
        Route::post('dynamic_dependent/fetch_department', 'DynamicDependent@fetchDepartment')->name('dynamic_department');
        Route::post('dynamic_dependent/fetch_teams', 'DynamicDependentController@fetchTeams')->name('dynamic_team');
        Route::post('dynamic_dependent/fetch_employee', 'DynamicDependent@fetchEmployee')->name('dynamic_employee');
        Route::post('dynamic_dependent/fetch_employee_details', 'DynamicDependent@fetchEmployeeDetails')->name('dynamic_employee_details');
        Route::post('dynamic_dependent/fetch_employee_department', 'DynamicDependent@fetchEmployeeDepartment')->name('dynamic_employee_department');
        Route::post('dynamic_dependent/fetch_designation_department', 'DynamicDependent@fetchDesignationDepartment')->name('dynamic_designation_department');
        Route::post('dynamic_dependent/fetch_office_shifts', 'DynamicDependent@fetchOfficeShifts')->name('dynamic_office_shifts');
        Route::post('dynamic_dependent/company_employee/{ticket}', 'DynamicDependent@companyEmployee')->name('company_employee');
        Route::post('dynamic_dependent/fetch_candidate', 'DynamicDependent@fetchCandidate')->name('dynamic_candidate');
        Route::post('dynamic_dependent/fetch_branch', 'DynamicDependent@fetchBranch')->name('dynamic_branch');
        Route::post('dynamic_dependent/fetch_unit', 'DynamicDependent@fetchUnits')->name('dynamic_unit');

    });

});

require __DIR__.'/auth.php';
