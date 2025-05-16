<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'legal', 'namespace' => 'App\Http\Controllers\Legal'], function ()
{
    //Court Cases
    Route::delete('court-cases/delete/selected', 'CourtCaseController@bulkDelete')->name('court-cases.delete.selected');
    Route::get('court-cases/active', 'CourtCaseController@active')->name('court-cases.active');
    Route::get('court-cases/import', 'CourtCaseController@import')->name('court-cases.import');
    Route::post('court-cases/import', 'CourtCaseController@importPost')->name('court-cases.importPost');
    Route::resource('court-cases', 'CourtCaseController')->except(['update']);


    //Court Hearings
    Route::delete('court-hearings/delete/selected', 'CourtHearingController@bulkDelete')->name('court-hearings.delete.selected');
    Route::get('court-hearings/import', 'CourtHearingController@import')->name('court-hearings.import');
    Route::post('court-hearings/import', 'CourtHearingController@importPost')->name('court-hearings.importPost');
    Route::resource('court-hearings', 'CourtHearingController')->except(['update']);

});
