<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Timesheet Routes
|--------------------------------------------------------------------------
*/

Route::prefix('timesheet')->group(function ()
{
    Route::get('attendances', 'AttendanceController@index')->name('attendances.index');
    Route::get('date-wise-attendances', 'AttendanceController@dateWiseAttendance')->name('date_wise_attendances.index');
    Route::get('monthly_attendances', 'AttendanceController@monthlyAttendance')->name('monthly_attendances.index');

    Route::get('update_attendances', 'AttendanceController@updateAttendance')->name('update_attendances.index');
    Route::get('update_attendances/{id}/get', 'AttendanceController@updateAttendanceGet')->name('update_attendances.get');
    Route::post('update_attendances/store', 'AttendanceController@updateAttendanceStore')->name('update_attendances.store');
    Route::post('update_attendances/update', 'AttendanceController@updateAttendanceUpdate')->name('update_attendances.update');
    Route::get('update_attendances/{id}/delete', 'AttendanceController@updateAttendanceDelete')->name('update_attendances.delete');


    Route::get('attendances/page/import', 'AttendanceController@import')->name('attendances.import');
    Route::post('attendances/page/import', 'AttendanceController@importPost')->name('attendances.importPost');

    Route::post('attendance/employee/{id}', 'AttendanceController@employeeAttendance')->name('employee_attendance.post');

    Route::namespace('Timesheet')->group(function ()
    {
        Route::resource('leave-schedules', 'LeaveScheduleController')->except(['update']);
    });

    {

        Route::get('holidays/{id}/calendarable', 'HolidayController@calendarableDetails')->name('holidays.calendarable');

    }
});

Route::group(['prefix' => 'timesheet', 'namespace' => 'Timesheet', 'as'=> 'timesheet.', 'middleware' => ['date.format']], function ()
{
    //Office Shifts
    Route::resource('office-shifts', 'OfficeShiftController')->except(['update', 'show']);
    Route::post('office-shifts/bulk-delete', 'OfficeShiftController@bulkDelete')->name('office-shifts.bulkDelete');

    //Holidays
    Route::get('holidays/{id}/calendarable', 'HolidayController@calendarableDetails')->name('holidays.calendarable');
    Route::resource('holidays', 'HolidayController')->except(['update', 'show']);
    Route::post('holidays/bulk-delete', 'HolidayController@bulkDelete')->name('holidays.bulkDelete');

    //Leaves
    Route::post('leaves/check-for-holiday-or-weekend', 'LeaveController@holidayOrWeekend')->name('leaves.checkForHolidayOrWeekend');
    Route::post('leaves/get-leave-end-date', 'LeaveController@getLeaveEndDate')->name('leaves.getLeaveEndDate');
    Route::post('leaves/get-leave-resume-date', 'LeaveController@getLeaveResumeDate')->name('leaves.getLeaveResumeDate');
    Route::get('leaves/leave-balances', 'LeaveBalanceController@index')->name('leave-balances.index');
    Route::post('leaves/leave-balances/update', 'LeaveBalanceController@update')->name('leave-balances.update');
    Route::get('leaves/employee-leaves', 'LeaveController@employeeLeaves')->name('leaves.employees');
    Route::delete('leaves/employee-leaves/{id}', 'LeaveController@destroy')->name('leaves.destroy1');
    Route::resource('leaves', 'LeaveController')->except([
        'update'
    ]);

    Route::resource('leave-schedules', 'LeaveScheduleController')->except([
        'destroy'
    ]);
    Route::get('leaves/{id}/calendarable', 'LeaveController@calendarableDetails')->name('leaves.calendarable');
});
