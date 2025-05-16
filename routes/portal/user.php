<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'user-access', 'namespace' => 'App\Http\Controllers\User', 'as' => 'admin.'], function ()
{
    //
    Route::get('users/detail/{id}', 'UserController@edit')->name('users.detail');
    Route::post('users/change-status/{id}', 'UserController@changeStatus')->name('users.change-status');
    Route::post('users/reset-password/{id}', 'UserController@resetPassword')->name('users.reset-password');
    Route::resource('users', 'UserController')->except(['update', 'show']);

    Route::get('roles/detail/{id}', 'RoleController@edit')->name('roles.detail');
    Route::get('roles/{id}/delete', 'RoleController@destroy')->name('roles.destroy');
    Route::resource('roles', 'RoleController', ['except' => ['show']]);

    //Permissions
    Route::get('permissions/detail/{id}', 'PermissionController@edit')->name('permissions.detail');
    Route::get('permissions/{id}/delete', 'PermissionController@destroy')->name('permissions.destroy');
    Route::resource('permissions', 'PermissionController', ['only' => ['index', 'edit', 'store', 'destroy']]);


    //Teams
    Route::delete('teams/delete/selected', 'TeamController@bulkDelete')->name('teams.delete.selected');
    Route::get('teams/import', 'TeamController@import')->name('teams.import');
    Route::post('teams/import', 'TeamController@importPost')->name('teams.importPost');
    Route::resource('teams', 'TeamController')->except(['update']);

//    Route::get('/detail/{id}', 'UserController@show')->name('get-user');
//    Route::post('/reset-password/{id}', 'UserController@resetPassword')->name('user.reset-password');
//    Route::get('/bulk-password-reset', 'UserController@bulkPasswordReset')->name('user.bulk-password-reset');
//    Route::get('/', 'UserController@index')->name('users-list');
//    Route::post('/users-list', 'UserController@store')->name('users.store');
//    Route::get('/create', 'UserController@add_user_form')->name('add-user');
//    Route::post('/create', 'UserController@add_user_process')->name('add-user');;
//    Route::get('/login-info', 'UserController@login_info')->name('login-info');
//    Route::get('/user-roles', 'UserController@user_roles')->name('user-roles');
////    Route::get('/user/edit/{id}', 'AllUserController@edit')->name('edit_user');
//    Route::post('/update-user', 'UserController@process_update')->name('update-user');
//    Route::delete('/users-list/{id}', 'UserController@delete_user')->name('delete_user');
//    Route::post('/user-mass-delete', 'UserController@delete_by_selection')->name('delete_by_selection');
//    Route::post('/assign-role/{user}', 'AssignRoleController@update')->name('assign_role');
//    Route::post('/mass-assign', 'AssignRoleController@mass_update')->name('mass_assign_role');
//
//    Route::resource('roles', 'RoleController');
//    Route::get('/roles/{id}/delete', 'RoleController@destroy')->name('roles.destroy');
//    Route::get('roles/role-permission/{id}', 'PermissionController@rolePermission')->name('rolePermission');
//    Route::get('roles/permission-details/{id}', 'PermissionController@permissionDetails')->name('permissionDetails');
//    Route::post('roles/permission', 'PermissionController@set_permission')->name('set_permission');
});
