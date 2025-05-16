<?php

use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\CustomerService\MaintenanceRequestController;
use App\Http\Controllers\Api\Mobile\InvoiceController;
use App\Http\Controllers\Api\Mobile\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'billing'], function () {
    Route::get('bookings/lookup', [BookingController::class, 'lookup'])->name('bookings.lookup');
    Route::apiResource('bookings', BookingController::class)->except(['update']);
    Route::post('bookings/update', [BookingController::class, 'update'])->name('bookings.update');

    Route::get('invoices/get-by-booking-id/{id}', [InvoiceController::class, 'showByBookingId'])->name('invoices.booking');
    Route::apiResource('invoices', InvoiceController::class)->except(['update']);
    Route::post('invoices/update', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('maintenance-categories', [MaintenanceRequestController::class, 'categories']);

    Route::get('payments/lookup', [PaymentController::class, 'paymentOptions'])->name('payments.options');
    Route::apiResource('payments', PaymentController::class)->except(['update']);
    Route::post('payments/update', [PaymentController::class, 'update'])->name('payments.update');
});
