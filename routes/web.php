<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    includeRouteFiles(__DIR__.'/portal/');

    Route::namespace('App\Http\Controllers\Account')->group(function () {
        // Route::get('account', 'AccountController@index')->name('account.index');
        Route::get('account/edit', 'AccountController@edit')->name('account.edit');
        Route::put('account/edit', 'AccountController@update')->name('account.update');
        Route::get('account/change-password', 'ChangePasswordController@edit')->name('account.change-password');
        Route::put('account/change-password', 'ChangePasswordController@update')->name('account.change-password');
        Route::post('account/change-password', 'ChangePasswordController@update')->name('account.change-password');
    });

    Route::get('markAsRead', 'RouteClosureHandlerController@markAsReadNotification')->name('markAsRead');
    Route::get('/all/notifications', 'RouteClosureHandlerController@allNotifications')->name('seeAllNoti');
    Route::get('clearAll', 'RouteClosureHandlerController@clearAll')->name('clearAll');
    Route::delete('/attachments/{id}', [HomeController::class, 'deleteAttachment'])->name('attachments.destroy');


});

require __DIR__.'/auth.php';
