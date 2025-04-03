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

    Route::delete('knowledge-base/delete-file/{topic_id}/{id}', 'KnowledgeBaseController@deleteDocument')->name('knowledge-base.delete-file');
    Route::get('knowledge-base/detail/{id}', 'KnowledgeBaseController@edit')->name('knowledge-base.detail');
    Route::get('knowledge-base/all', 'KnowledgeBaseController@showAll')->name("knowledge-base.all");
    Route::resource('knowledge-base', 'KnowledgeBaseController');

    Route::delete('resources/delete-file/{topic_id}/{id}', 'ResourceController@deleteDocument')->name('resources.delete-file');
    Route::get('resources/detail/{id}', 'ResourceController@edit')->name('resources.detail');
    Route::get('resources/all', 'ResourceController@showAll')->name("resources.all");
    Route::resource('resources', 'ResourceController');
});


