<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'facilities', 'namespace' => 'App\Http\Controllers\Property'], function ()
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
    Route::get('properties/all', 'PropertyController@all')->name('properties.all');
    Route::get('properties/lease', 'PropertyController@propertyLease')->name('properties.lease');
    Route::resource('properties', 'PropertyController')->except(['update']);

    //Property Units
    Route::resource('property-units', 'PropertyUnitController')->except(['update']);
    Route::resource('rooms', 'RoomController')->except(['update']);
    Route::delete('reviews/delete/selected', 'ReviewController@bulkDelete')->name('reviews.delete.selected');
    Route::resource('reviews', 'ReviewController')->except(['update']);


});
