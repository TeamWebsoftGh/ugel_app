<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'ObjectiveSettings', 'prefix' => 'objective-setups', 'as' => 'objective.'], function () {
    // Appraisal Settings
    Route::group(['prefix' => 'appraisal'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@appraisalSettings')->name('appraisal.settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@appraisalSettingsStore')->name('appraisal.settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@appraisalSettingsEdit')->name('appraisal.settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@appraisalSettingsDelete')->name('appraisal.settings.delete');
    });
    // Appraisal Settings Review 
    Route::group(['prefix' => 'review'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@reviewSettings')->name('review.settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@reviewSettingsStore')->name('review.settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@reviewSettingsEdit')->name('review.settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@reviewSettingsDelete')->name('review.settings.delete');
    });

    // Appraisal Category

    Route::group(['prefix' => 'category', 'as'=>'category.'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@categorySettings')->name('settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@categorySettingsStore')->name('settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@categorySettingsEdit')->name('settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@categorySettingsDelete')->name('settings.delete');
    });

     //  Category Areas
     Route::group(['prefix' => 'areas', 'as'=>'areas.'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@areasSettings')->name('settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@areasSettingsStore')->name('settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@areasSettingsEdit')->name('settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@areasSettingsDelete')->name('settings.delete');
    });

     //  Employees Contract
     Route::group(['prefix' => 'employees-contracts', 'as'=>'employees-contracts.'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@employeesContractsSettings')->name('settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@employeesContractsSettingsStore')->name('settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@employeesContractsSettingsEdit')->name('settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@employeesContractsSettingsDelete')->name('settings.delete');
    });

    // Appraisal Category Member

    Route::group(['prefix' => 'categorymember', 'as'=>'categorymember.'], function () {
        Route::get('/setups', 'ObjectiveSettingsController@categorymemberSettings')->name('settings');
        Route::post('/settings-store', 'ObjectiveSettingsController@categorymemberSettingsStore')->name('settings.store');
        Route::get('/settings-edit', 'ObjectiveSettingsController@categorymemberSettingsEdit')->name('settings.edit');
        Route::get('/settings-delete', 'ObjectiveSettingsController@categorymemberSettingsDelete')->name('settings.delete');
    });

});


