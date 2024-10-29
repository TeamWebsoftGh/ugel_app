<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '', 'namespace' => 'Property'], function ()
{
    Route::resource('property-categories', 'PropertyCategoryController')->except(['update']);
    Route::resource('property-types', 'PropertyTypeController')->except(['update']);

    //service-types
    Route::get('companies/kyc', 'OfferController@kyc')->name('companies.kyc');
    Route::get('offers/payment', 'OfferController@payment')->name('offers.payment');
    Route::get('offers/queued', 'OfferController@queued')->name('offers.queued');
    Route::get('offers/active', 'OfferController@active')->name('offers.active');
    Route::resource('service-types', 'ServiceTypeController')->except(['update']);
    Route::resource('offers', 'OfferController')->except(['update']);
    Route::resource('payments', 'PaymentController')->except(['update']);


});
