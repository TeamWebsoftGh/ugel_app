<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'cs', 'namespace' => 'App\Http\Controllers\Client', 'as' => 'admin.'], function ()
{
    //Customer Types
    Route::post('customer-types/delete/selected', 'CustomerTypeController@deleteSelected')->name('customer-types.delete.selected');
    Route::get('customer-types/import', 'CustomerTypeController@import')->name('customer-types.import');
    Route::post('customer-types/import', 'CustomerTypeController@importPost')->name('customer-types.import');
    Route::resource('customer-types', 'CustomerTypeController')->except(['update', 'show']);;

    //Customers
    Route::get('customers/organizations', 'CustomerController@organizations')->name('customers.organizations');
    Route::get('customers/students', 'CustomerController@students')->name('customers.students');
    Route::get('customers/import', 'CustomerController@import')->name('customers.import');
    Route::post('customers/import', 'CustomerController@importPost')->name('customers.import');
    Route::post('customers/change-status/{id}', 'CustomerController@changeStatus')->name('customers.change-status');
    Route::post('customers/reset-password/{id}', 'CustomerController@resetPassword')->name('customers.reset-password');
    Route::resource('customers', 'CustomerController')->except(['update', 'show']);
});
