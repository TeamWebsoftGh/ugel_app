<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Task', 'prefix' => ''], function () {
    //Activities
    Route::post('tasks/activities', 'TimesheetController@store')->name('tasks.activities.store');
    Route::get('tasks/activities/{task_id}/{id}', 'TimesheetController@edit')->name('tasks.activities.show');
    Route::delete('tasks/activities/{task_id}/{id}', 'TimesheetController@delete')->name('tasks.activities.destroy');

    //Comments
    Route::post('tasks/post-comment', 'TaskController@postComment')->name('tasks.comments.store');
    Route::delete('tasks/comments/{task_id}/{id}', 'TaskController@deleteComment')->name('tasks.comments.destroy');

    //Objectives
    Route::post('tasks/objectives', 'TaskDetailController@storeObjective')->name('tasks.objectives.store');
    Route::delete('tasks/objectives/{task_id}/{id}', 'TaskDetailController@deleteObjective')->name('tasks.objectives.destroy');


    Route::post('tasks/change-status/{id}', 'TaskController@changeStatus')->name('tasks.change-status');
    Route::post('tasks/upload-file', 'TaskController@uploadFile')->name('tasks.file-upload');
    Route::delete('tasks/delete-file/{task_id}/{id}', 'TaskController@deleteDocument')->name('tasks.delete-file');
    Route::get('my-tasks', 'TaskController@myTasks')->name('tasks.my-tasks');
    Route::get('tasks/pending', 'TaskController@pending')->name('tasks.pending');
    Route::resource('tasks', 'TaskController');
});
