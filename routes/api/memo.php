<?php


use App\Http\Controllers\Api\Mobile\BulkSmsController;
use App\Http\Controllers\Api\Mobile\ContactController;
use App\Http\Controllers\Api\Mobile\ParliamentaryCandidateController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'memo'], function () {
    Route::get('contact-groups', [ContactController::class, 'groups']);
    Route::post('contact-groups', [ContactController::class, 'groupStore']);
    Route::apiResource('contacts', ContactController::class);

    Route::post('bulk-sms/quick-sms', [BulkSmsController::class, 'quickSms']);
});
