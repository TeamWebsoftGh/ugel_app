<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '', 'namespace' => 'App\Http\Controllers\Property'], function ()
{
    //Amenities
    Route::delete('amenities/delete/selected', 'AmenityController@bulkDelete')->name('amenities.delete.selected');
    Route::get('amenities/import', 'AmenityController@import')->name('amenities.import');
    Route::post('amenities/import', 'AmenityController@importPost')->name('amenities.importPost');
    Route::resource('amenities', 'AmenityController')->except(['update']);

    //Property Categories
    Route::get('property-categories/import', 'PropertyCategoryController@import')->name('property-categories.import');
    Route::post('property-categories/import', 'PropertyCategoryController@importPost')->name('property-categories.importPost');
    Route::delete('property-categories/delete/selected', 'PropertyCategoryController@bulkDelete')->name('property-categories.delete.selected');
    Route::resource('property-categories', 'PropertyCategoryController')->except(['update']);

    //Property Categories
    Route::get('property-types/import', 'PropertyTypeController@import')->name('property-types.import');
    Route::post('property-types/import', 'PropertyTypeController@importPost')->name('property-types.importPost');
    Route::delete('property-types/delete/selected', 'PropertyTypeController@bulkDelete')->name('property-types.delete.selected');
    Route::resource('property-types', 'PropertyTypeController')->except(['update']);

    //Properties
    Route::get('properties/lease', 'PropertyController@propertyLease')->name('properties.lease');
    Route::resource('properties', 'PropertyController')->except(['update']);

    //service-types
    Route::get('companies/kyc', 'AmenityController@kyc')->name('companies.kyc');
    Route::get('offers/payment', 'AmenityController@payment')->name('offers.payment');
    Route::get('offers/queued', 'AmenityController@queued')->name('offers.queued');
    Route::get('offers/active', 'AmenityController@active')->name('offers.active');
    Route::resource('service-types', 'PropertyController')->except(['update']);
    Route::resource('offers', 'AmenityController')->except(['update']);
    Route::resource('payments', 'PaymentController')->except(['update']);


});
