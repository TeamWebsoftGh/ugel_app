<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Communication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'communication', 'namespace' => 'App\Http\Controllers\Memo'], function ()
{
    Route::get('bulk-sms/quick-sms', 'BulkSmsController@quickSms')->name('bulk-sms.quick');
    Route::post('bulk-sms/quick-sms', 'BulkSmsController@quickSmsPost')->name('bulk-sms.bulk-voice');
    Route::resource('bulk-sms', 'BulkSmsController')->except(['update']);

    Route::get('bulk-voice/quick-voice', 'BulkVoiceController@quickVoice')->name('bulk-voice.quick');
    Route::post('bulk-voice/quick-voice', 'BulkVoiceController@quickVoicePost')->name('bulk-voice.quick');
    Route::resource('bulk-voice', 'BulkVoiceController')->except(['update']);


    Route::get('whatsapp/quick', 'WhatsappController@quickWhatsApp')->name('whatsapp.quick');
    Route::post('whatsapp/quick', 'WhatsappController@quickWhatsAppPost')->name('whatsapp.quick');
    Route::resource('whatsapp', 'WhatsappController')->except(['update']);

    Route::post('contact-groups/store', 'ContactController@groupStore')->name('contact-groups.store');
    Route::resource('contacts', 'ContactController')->except(['update']);
    Route::resource('popups', 'PopUpController')->except(['update']);
});
