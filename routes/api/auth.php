<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Mobile\Account\AccountController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('tokens/delete', [AuthenticatedSessionController::class, 'destroy']);
Route::namespace('Account')->group(function () {
    Route::get('account/details',  [AccountController::class, 'show'])->name('account.show');
    Route::put('account/edit', [AccountController::class, 'update'])->name('account.update');
    Route::put('account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::post('account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::get('account/notifications', [AccountController::class, 'notifications'])->name('account.notifications');
    Route::delete('account/notifications', [AccountController::class, 'clearNotifications'])->name('account.notifications.clear');
});
// API route for email verification
Route::middleware('auth:sanctum')->get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
