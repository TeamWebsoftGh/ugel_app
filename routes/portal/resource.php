<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['as' => 'resource.', 'namespace' => 'App\Http\Controllers\Resource', 'prefix' => 'resource'], function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('knowledge-base', 'KnowledgeBaseController')->except(['update']);

    Route::get('resources/detail/{id}', 'ResourceController@edit')->name('resources.detail');
    Route::get('resources/all', 'ResourceController@showAll')->name("resources.all");
    Route::resource('resources', 'ResourceController');
});


