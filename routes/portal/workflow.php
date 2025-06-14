<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'workflow-requests', 'namespace' => 'App\Http\Controllers\WorkflowRequest'], function () {
    Route::get('all', 'WorkflowRequestController@index')->name('workflow-requests.index');
    Route::get('pending', 'WorkflowRequestController@pending')->name('workflow-requests.pending');
    Route::get('my-requests', 'WorkflowRequestController@myRequests')->name('workflow-requests.my-requests');



    Route::get('employee-requests', 'EmployeeRequestController@index')->name('employee-requests.index');
    Route::get('pending-requests', 'EmployeeRequestController@pending')->name('employee-requests.pending');
    Route::get('my-request', 'EmployeeRequestController@myRequests')->name('employee-requests.my-requests');
    Route::get('all-requests', 'EmployeeRequestController@allRequests')->name('employee-requests.all-requests');
    Route::get('all-requests/{id}', 'EmployeeRequestController@getRequest')->name('employee-requests.show');
    Route::get('update-request/{id}', 'EmployeeRequestController@resendRequest')->name('employee-requests.resend');
    Route::post('employee-requests', 'EmployeeRequestController@processRequest')->name('employee-requests.process');

    Route::post('forward-requests/{id}', 'EmployeeRequestController@forwardRequest')->name('employee-requests.forward');
    Route::post('send-forward-requests/{id}', 'EmployeeRequestController@sendForwardRequest')->name('employee-requests.send-forward');
});

//Workflow
Route::group(['prefix' => 'workflows', 'namespace' => 'App\Http\Controllers\Workflow', 'as' => 'workflows.'], function ()
{
    //Workflow Positions types
    Route::delete('position-types/delete/selected', 'PositionTypeController@bulkDelete')->name('position-types.delete.selected');
    Route::resource('position-types', 'PositionTypeController')->except(['update', 'show']);

    //Workflow Positions
    Route::delete('positions/delete/selected', 'WorkflowPositionController@bulkDelete')->name('positions.delete.selected');
    Route::resource('positions', 'WorkflowPositionController')->except(['update', 'show']);

    //Workflow Types
    Route::delete('workflow-types/delete/selected', 'WorkflowTypeController@bulkDelete')->name('workflow-types.delete.selected');
    Route::resource('workflow-types', 'WorkflowTypeController')->except(['update']);

    //Workflows
    Route::get('workflows/detail/{id}', 'WorkflowController@show')->name('workflows.detail');
    Route::get('workflows/{id}/delete', 'WorkflowController@destroy')->name('workflows.destroy');
    Route::resource('workflows', 'WorkflowController')->except(['update']);
});
