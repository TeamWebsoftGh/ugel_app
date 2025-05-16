<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Common\CallbackController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['namespace' => '\App\Http\Controllers\Api', 'prefix' => 'clients'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('tokens/create', [AuthenticatedSessionController::class, 'store']);
        Route::post('tokens/delete', [AuthenticatedSessionController::class, 'destroy']);
        Route::get('register', [RegisteredUserController::class, 'register']);
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('otp/send', [RegisteredUserController::class, 'sendCode']);
        Route::post('otp/verify', [RegisteredUserController::class, 'verifyCode']);
    });


    Route::group(['namespace' => 'App\Http\Controllers\Api\Mobile', 'prefix' => 'common'], function () {
        Route::get('property-types/{id}', [CommonController::class, 'propertyTypeDetails']);
        Route::get('property-types', [CommonController::class, 'propertyTypes']);
        Route::get('property-categories/{id}', [CommonController::class, 'propertyCategoryDetails']);
        Route::get('property-categories', [CommonController::class, 'propertyCategories']);
        Route::get('properties/{id}', [PropertyController::class, 'show']);
        Route::get('properties', [PropertyController::class, 'index']);
        Route::get('property-units', [PropertyController::class, 'units']);
        Route::get('rooms', [PropertyController::class, 'rooms']);
        Route::get('countries', [CommonController::class, 'countries']);
        Route::get('regions', [CommonController::class, 'regions']);
        Route::get('cities', [CommonController::class, 'cities']);
        Route::post('getPrice', [CommonController::class, 'getPrice']);

    });

    Route::group(['middleware' => ['auth:sanctum', 'api.client.verified']], function () {
        includeRouteFiles(__DIR__.'/api/');
    });

    Route::group(['namespace' => '\App\Http\Controllers\Api', 'prefix' => 'callbacks'], function () {
        Route::any('yoovi', [CallbackController::class, 'handleYooviCallback'])->name('callback.yoovi');
//        Route::any('hubtel', [CallbackController::class, 'handleHubtelCallback'])->name('callback.hubtel');
//        Route::any('hubtel/invoice', [CallbackController::class, 'handleHubtelInvoiceCallback'])->name('callback.hubtel.invoice');
//        Route::any('hubtel/preapproval', [CallbackController::class, 'handleHubtelPreapproval'])->name('callback.hubtel.preapproval');
    });
});
