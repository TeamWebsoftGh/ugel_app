<?php

use App\Http\Controllers\Ajax\DynamicDependentController;
use App\Http\Controllers\Ajax\DynamicPropertyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'ajax', 'namespace' => 'App\Http\Controllers\Ajax'], function (){
    Route::get('/get-customer-details/{id}', [DynamicPropertyController::class, 'getDetails'])->name('customers.details');
    Route::get('/get-maintenance-categories/{id}', [DynamicPropertyController::class, 'getMaintenanceCategories'])->name('customers.details');
    Route::get('/workflow/return-to-options/{workflowTypeId}', [DynamicDependentController::class, 'getReturnToOptions'])->name('workflows.return-to-options');
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
