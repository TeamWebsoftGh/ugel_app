<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Organization Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'organization', 'as' => 'organization.', 'namespace' => 'Organization'], function ()
{
    //Company
    Route::get('companies/detail/{id}', 'CompanyController@show')->name('companies.detail');
    Route::resource('companies', 'CompanyController')->except(['show', 'update']);
    Route::get('companies/{id}/delete', 'CompanyController@destroy')->name('companies.destroy');
    Route::post('companies/delete/selected', 'CompanyController@delete_by_selection')->name('mass_delete_companies');

    //Subsidiaries
    Route::get('subsidiaries/detail/{id}', 'SubsidiaryController@show')->name('subsidiaries.detail');
    Route::resource('subsidiaries', 'SubsidiaryController')->except(['show', 'create', 'update']);
    Route::post('subsidiaries/delete/selected', 'SubsidiaryController@delete_by_selection')->name('mass_delete_subsidiaries');

    //Department
    Route::get('departments/detail/{id}', 'DepartmentController@show')->name('departments.detail');
    Route::resource('departments', 'DepartmentController')->except(['show', 'create', 'update']);
    Route::post('departments/delete/selected', 'DepartmentController@delete_by_selection')->name('mass_delete_departments');

    //Designation
    Route::get('designations/detail/{id}', 'DesignationController@show')->name('designations.detail');
    Route::resource('designations', 'DesignationController')->except(['show', 'create', 'update']);
    Route::post('designations/delete/selected', 'DesignationController@delete_by_selection')->name('mass_delete_departments');

    //Branches
    Route::get('branches/detail/{id}', 'BranchController@show')->name('branches.detail');
    Route::resource('branches', 'BranchController')->except(['show', 'create', 'update']);
    Route::post('branches/delete/selected', 'BranchController@delete_by_selection')->name('mass_delete_branches');

    //Units
    Route::get('department-units/detail/{id}', 'DepartmentUnitController@show')->name('department-units.detail');
    Route::resource('department-units', 'DepartmentUnitController')->except(['show', 'create', 'update']);
    Route::post('department-units/delete/selected', 'DepartmentUnitController@delete_by_selection')->name('mass_delete_department-units');

});
