<?php

use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\MaintenanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'billing'], function () {
    Route::resource('bookings', BookingController::class)->except(['update']);
    Route::get('maintenance-categories', [MaintenanceController::class, 'categories']);
});
