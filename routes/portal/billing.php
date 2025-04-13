<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core  Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'billing', 'namespace' => 'App\Http\Controllers\Billing'], function ()
{
    //Booking Periods
    Route::delete('booking-periods/delete/selected', 'BookingPeriodController@bulkDelete')->name('booking-periods.delete.selected');
    Route::get('booking-periods/import', 'BookingPeriodController@import')->name('booking-periods.import');
    Route::post('booking-periods/import', 'BookingPeriodController@importPost')->name('booking-periods.importPost');
    Route::resource('booking-periods', 'BookingPeriodController')->except(['update']);

    //Invoice Items
    Route::delete('invoice-items/delete/selected', 'InvoiceItemController@bulkDelete')->name('invoice-items.delete.selected');
    Route::get('invoice-items/import', 'InvoiceItemController@import')->name('invoice-items.import');
    Route::post('invoice-items/import', 'InvoiceItemController@importPost')->name('invoice-items.importPost');
    Route::resource('invoice-items', 'InvoiceItemController')->except(['update']);

    //Booking Periods
    Route::delete('bookings/delete/selected', 'AmenityController@bulkDelete')->name('bookings.delete.selected');
    Route::get('bookings/import', 'AmenityController@import')->name('bookings.import');
    Route::post('bookings/import', 'AmenityController@importPost')->name('bookings.importPost');
    Route::resource('bookings', 'AmenityController')->except(['update']);

    //Bookings
    Route::delete('bookings/delete/selected', 'BookingController@bulkDelete')->name('bookings.delete.selected');
    Route::get('bookings/import', 'BookingController@import')->name('bookings.import');
    Route::post('bookings/import', 'BookingController@importPost')->name('bookings.importPost');
    Route::resource('bookings', 'BookingController')->except(['update']);

    //Invoice Items
    Route::delete('invoices/delete/selected', 'InvoiceController@bulkDelete')->name('invoices.delete.selected');
    Route::get('invoices/import', 'InvoiceController@import')->name('invoices.import');
    Route::post('invoices/import', 'InvoiceController@importPost')->name('invoices.importPost');
    Route::resource('invoices', 'InvoiceController')->except(['update']);


    //Payment
    Route::get('payment/{slug}/{invoice_id}', 'PaymentController@showPay')->name('payments.pay');
    Route::delete('payments/delete/selected', 'PaymentController@bulkDelete')->name('payments.delete.selected');
    Route::get('payments/import', 'PaymentController@import')->name('payments.import');
    Route::post('payments/import', 'PaymentController@importPost')->name('payments.importPost');
    Route::resource('payments', 'PaymentController')->except(['update']);

});
