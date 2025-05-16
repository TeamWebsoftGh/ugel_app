<?php

use App\Http\Controllers\Api\Mobile\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'properties'], function () {
    Route::apiResource('reviews', ReviewController::class)->except(['update']);
    Route::post('reviews/update', [ReviewController::class, 'update'])->name('reviews.update');

});

