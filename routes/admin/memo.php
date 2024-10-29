<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Organization Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Memo'], function ()
{
    Route::get('bulk-sms/quick-sms', 'AnnouncementController@quickSms')->name('bulk-sms.quick');
    Route::post('bulk-sms/quick-sms', 'AnnouncementController@quickSmsPost')->name('bulk-sms.quick');
    Route::resource('bulk-sms', 'AnnouncementController')->except(['update']);
    Route::post('contact-groups/store', 'ContactController@groupStore')->name('contact-groups.store');
    Route::resource('contacts', 'ContactController')->except(['update']);
    Route::resource('announcements', 'AnnouncementController')->except(['update']);
    Route::resource('popups', 'PopUpController')->except(['update']);
});
