<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Configuration'], function () {

    //IP Settings
    Route::group(['prefix' => 'ip_settings'], function () {
        Route::get('/', 'IPSettingController@index')->name('ip_setting.index');
        Route::post('/store', 'IPSettingController@store')->name('ip_setting.store');
        Route::get('/edit', 'IPSettingController@edit')->name('ip_setting.edit');
        Route::post('/update', 'IPSettingController@update')->name('ip_setting.update');
        Route::get('/delete', 'IPSettingController@delete')->name('ip_setting.delete');
        Route::get('/bulk_delete', 'IPSettingController@bulkDelete')->name('ip_setting.bulk_delete');
    });
});

//Settings
Route::group(['prefix' => 'configurations', 'as' => 'configuration.', 'namespace' => 'App\Http\Controllers\Configuration'],function () {
    // Route::get('account', 'AccountController@index')->name('account.index');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'],function () {
        // Route::get('account', 'AccountController@index')->name('account.index');
        Route::get('site', 'GeneralSettingController@general')->name('site');
        Route::put('site', 'GeneralSettingController@update')->name('site.store');
        Route::get('mail', 'GeneralSettingController@siteMail')->name('mail');
        Route::put('mail', 'GeneralSettingController@updateSiteMail')->name('mail.store');
        Route::get('sms', 'GeneralSettingController@siteSms')->name('sms');
        Route::put('sms', 'GeneralSettingController@updateSiteSms')->name('sms.store');
        Route::get('whatsapp', 'GeneralSettingController@siteWhatsApp')->name('whatsapp');
        Route::put('whatsapp', 'GeneralSettingController@updateSiteWhatsApp')->name('whatsapp.store');
    });
    Route::get('currencies/detail/{id}', 'CurrencyController@edit')->name('currencies.detail');
    Route::resource('currencies', 'CurrencyController', ['except' => ['update', 'show', 'create']]);

    Route::group(['prefix' => 'menus'], function(){
        Route::get('/main-menu', ['uses'=>'DynamicMenusController@createMainMenu'])->name('menu.create');
        Route::post('/main-menu', ['uses'=>'DynamicMenusController@storeMainMenu'])->name('menu.store');
        Route::post('/remove-main-menu', ['uses'=>'DynamicMenusController@removeMainMenu'])->name('menu.destroy') ;
    });

    //Employee Settings
    Route::get('export-database', 'GeneralSettingController@exportDatabase')->name('database.export');
});
