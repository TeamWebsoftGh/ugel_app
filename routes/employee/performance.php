<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'ObjectiveSettings\PerformanceContract', 'prefix' => 'performance-contract', 'as' => 'performance.'], function () {

  Route::group(['prefix'=>'objectives'], function(){
    Route::get('/', 'ObjectiveSettingController@contract')->name('objectives.contract');
    // add new kra
    Route::group(['prefix'=>'add-kra-objectives'],function(){
      Route::post('/', 'ObjectiveSettingController@addKraObjectives')->name('objectives.add-kra-objectives');
      Route::delete('/delete/{id}', 'ObjectiveSettingController@deleteObjectives')->name('objectives.delete-kra-objectives');
    });
    // add new BC
    Route::group(['prefix'=>'add-bc-objectives'],function(){
    Route::post('/', 'ObjectiveSettingController@addBcObjectives')->name('objectives.add-bc-objectives');
    Route::delete('/delete/{id}', 'ObjectiveSettingController@deleteObjectives')->name('objectives.delete-bc-objectives');
    });

    // add Development Plan
    Route::group(['prefix'=>'add-development-plan'],function(){
      Route::post('/', 'ObjectiveSettingController@addDevelopmentPlan')->name('objectives.add-development-plan');
      Route::delete('/delete/{id}', 'ObjectiveSettingController@deleteObjectives')->name('objectives.delete-development-plan');
      });

       // sign contracts and declarations
    Route::group(['prefix'=>'sign-contract'],function(){
      Route::post('/', 'ObjectiveSettingController@signContract')->name('objectives.sign-contract');
      });

    // load detials to form
    Route::get('/get-partial-record-info', 'ObjectiveSettingController@getPartialRecordInfo')->name('objectives.get-partial-record-info');
    
    // load objectives
    Route::get('/contract-details', 'ObjectiveSettingController@contractDetails')->name('objectives.load.contract-details');
    Route::get('/load-objectives', 'ObjectiveSettingController@loadObjectives')->name('objectives.load.objectives');
    Route::post('/update-contract', 'ObjectiveSettingController@updateContract')->name('objectives.update.contract');

    

    // REVIEWS
    Route::get('mid-year-review', 'ObjectiveSettingController@midyear')->name('objectives.mid-year');
    Route::get('end-year-review', 'ObjectiveSettingController@endyear')->name('objectives.end-year');
    Route::get('q1-review', 'ObjectiveSettingController@q1')->name('objectives.q1');
    Route::get('q2-review', 'ObjectiveSettingController@q2')->name('objectives.q2');

  });
  

});
// Approvals
Route::group(['namespace' => 'ObjectiveSettings\PerformanceContract'], function () {
Route::get('/approve/performancecontract', 'ObjectiveSettingController@approvals')->name('approve.performancecontract');
Route::get('/approve/mid-year-review', 'ObjectiveSettingController@approvals')->name('approve.appraisal-mid-year-review');
Route::get('/approve/end-year-review', 'ObjectiveSettingController@approvals')->name('approve.appraisal-end-year-review');

///
Route::get('/approve/q1-review', 'ObjectiveSettingController@approvals')->name('approve.appraisal-q1-review');
Route::get('/approve/q2-review', 'ObjectiveSettingController@approvals')->name('approve.appraisal-q2-review');

// Exports 
Route::get('/export/contract-report', 'ObjectiveSettingController@exportContract')->name('export.contract-report');


});


