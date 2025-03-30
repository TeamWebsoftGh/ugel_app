<?php

use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\InvoiceController;
use App\Http\Controllers\Api\Mobile\MaintenanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'billing'], function () {
    Route::apiResource('bookings', BookingController::class)->except(['update']);
    Route::post('bookings/update', [BookingController::class, 'update'])->name('bookings.update');
    Route::apiResource('invoices', InvoiceController::class)->except(['update']);
    Route::post('invoices/update', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('maintenance-categories', [MaintenanceController::class, 'categories']);
});
