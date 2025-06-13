<?php

use App\Http\Controllers\Api\Mobile\CustomerService\MaintenanceRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile\CustomerService', 'prefix' => 'customer-service'], function () {
    Route::get('maintenance-categories', [MaintenanceRequestController::class, 'categories']);
    Route::get('maintenance-requests/lookup', [MaintenanceRequestController::class, 'lookup'])->name('maintenance-requests.lookup');
    Route::post('maintenance-request/post-comment', [MaintenanceRequestController::class, 'postComment'])->name('maintenance-requests.postComment');
    Route::delete('maintenance-request/post-comment', [MaintenanceRequestController::class, 'deleteComment'])->name('maintenance-requests.deleteComment');
    Route::post('maintenance-request/update', [MaintenanceRequestController::class, 'update'])->name('maintenance-requests.update');
    Route::apiResource('maintenance-requests', MaintenanceRequestController::class)->except(['update']);
});
