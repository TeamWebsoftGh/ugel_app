<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'CustomerService', 'prefix' => 'customer-service'], function () {
    //Comments
    Route::post('support-tickets/upload-file', 'SupportTicketController@uploadFile')->name('support-tickets.file-upload');
    Route::delete('support-tickets/delete-file/{ticket_id}/{id}', 'SupportTicketController@deleteDocument')->name('support-tickets.delete-file');
    Route::post('support-tickets/post-comment', 'SupportTicketController@postComment')->name('support-tickets.comments.store');
    Route::delete('support-tickets/comments/{task_id}/{id}', 'SupportTicketController@deleteComment')->name('support-tickets.comments.destroy');
    Route::get('my-support-tickets', 'SupportTicketController@myTickets')->name('support-tickets.my-tickets');
    Route::get('support-tickets/pending', 'SupportTicketController@pending')->name('support-tickets.pending');
    Route::get('support-tickets/assigned', 'SupportTicketController@assigned')->name('support-tickets.assigned');
    Route::resource('support-tickets', 'SupportTicketController')->except(['update']);

    //Visitor Log
    Route::resource('visitor-logs', 'VisitorLogController')->except(['update', 'show']);

    Route::get('enquiries/change-status/{id}', 'EnquiryController@show')->name('enquiries.change-status');
    Route::resource('enquiries', 'EnquiryController')->except(['update']);


});



