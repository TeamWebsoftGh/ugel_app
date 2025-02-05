<?php

use App\Http\Controllers\Api\Mobile\CommonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile\CustomerService', 'prefix' => 'customer-service'], function () {
    Route::resource('enquiries', 'EnquiryController')->except(['update']);

});
