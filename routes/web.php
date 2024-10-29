<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'App\Http\Controllers'], function ()
{
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => 'auth'], function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
        Route::post('forgot', 'Auth\Forgot@store')->name('forgot.store');

        //Route::get('reset', 'Auth\Reset@create');
        Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
        Route::post('reset', 'Auth\Reset@store')->name('reset.store');

        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::get('register', [RegisteredUserController::class, 'store'])->name('register.store');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    Route::group(['middleware' => ['auth', \App\Http\Middleware\CheckIfTodoIsSet::class]], function ()
    {
        includeRouteFiles(__DIR__.'/admin/');
        includeRouteFiles(__DIR__.'/employee/');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('employee/dashboard', 'DashboardController@employeeDashboard')->name('employee.dashboard');

        Route::namespace('Account')->group(function () {
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

        Route::get('/profile', 'DashboardController@profile')->name('profile');
        Route::put('/profile/{id}', 'DashboardController@profile_update')->name('profile_update');
        Route::post('/profile/employee/{id}', 'DashboardController@employeeProfileUpdate')->name('employee_profile_update');
        Route::post('/profile/change_password/{id}', 'DashboardController@change_password')->name('change_password');

        Route::prefix('training')->group(function ()
        {
            {
                Route::post('training_lists/update', 'TrainingListController@update')->name('training_lists.update');
                Route::resource('training_lists', 'TrainingListController')->except([
                    'destroy', 'create', 'update'
                ]);
                Route::get('training_lists/{id}/delete', 'TrainingListController@destroy')->name('training_lists.destroy');
                Route::post('training_lists/delete/selected', 'TrainingListController@delete_by_selection')->name('mass_delete_training_lists');

                Route::get('training_lists/{id}/calendarable', 'TrainingListController@calendarableDetails')->name('training_lists.calendarable');
            }
            {
                Route::post('trainers/update', 'TrainerController@update')->name('trainers.update');
                Route::resource('trainers', 'TrainerController')->except([
                    'destroy', 'create', 'update']);
                Route::get('trainers/{id}/delete', 'TrainerController@destroy')->name('trainers.destroy');
                Route::post('trainers/delete/selected', 'TrainerController@delete_by_selection')->name('mass_delete_trainers');
            }
        });

        Route::prefix('todos')->group(function ()
        {
            Route::post('projects/{project}/assigned', 'EmployeeAssignedController@employeeProjectAssigned')->name('projects.assigned');
            Route::post('projects/update', 'ProjectController@update')->name('projects.update');
            Route::resource('projects', 'ProjectController')->except([
                'destroy', 'create', 'update'
            ]);
            Route::get('projects/{id}/delete', 'ProjectController@destroy')->name('projects.destroy');

            Route::get('projects/{id}/calendarable', 'ProjectController@calendarableDetails')->name('projects.calendarable');

            Route::post('projects/{project}/progress', 'ProjectController@progressStore')->name('project_progress.store');

            Route::post('projects/{project}/discussions', 'ProjectDiscussionController@index')->name('project_discussions.index');
            Route::post('projects/store_discussions/{project}', 'ProjectDiscussionController@store')->name('project_discussions.store');
            Route::get('projects/{id}/delete_discussions', 'ProjectDiscussionController@destroy')->name('project_discussions.destroy');

            Route::post('projects/{project}/bugs', 'ProjectBugController@index')->name('project_bugs.index');
            Route::post('projects/store_bugs/{project}', 'ProjectBugController@store')->name('project_bugs.store');

            Route::post('projects/{project}/files', 'ProjectFileController@index')->name('project_files.index');
            Route::post('projects/store_files/{project}', 'ProjectFileController@store')->name('project_files.store');
            Route::get('projects/{id}/delete_files', 'ProjectFileController@destroy')->name('project_files.destroy');

            Route::post('projects/{project}/tasks', 'ProjectTaskController@index')->name('project_tasks.index');
            Route::post('projects/store_tasks/{project}', 'ProjectTaskController@store')->name('project_tasks.store');
            Route::get('projects/{id}/delete_tasks', 'ProjectTaskController@destroy')->name('project_tasks.destroy');


            Route::get('projects/bug_status', 'ProjectBugController@default')->name('bug_status.default');
            Route::get('projects/bug_status/{id}', 'ProjectBugController@editStatus')->name('bug_status.edit');
            Route::post('projects/bug_status/update', 'ProjectBugController@updateStatus')->name('bug_status.update');
            Route::get('projects/{id}/delete_bugs', 'ProjectBugController@destroy')->name('project_bugs.destroy');
            Route::get('projects/discussion_download/{id}', 'ProjectDiscussionController@download')->name('projects.downloadAttachment');
            Route::get('projects/bug_download/{id}', 'ProjectBugController@download')->name('projects.downloadBug');
            Route::get('projects/file_download/{id}', 'ProjectFileController@download')->name('projects.downloadFile');
            Route::post('projects/{project}/notes', 'ProjectController@notesStore')->name('project_notes.store');

            Route::post('tasks/{task}/assigned', 'EmployeeAssignedController@employeeTaskAssigned')->name('tasks.assigned');
            Route::post('tasks/update', 'TaskController@update')->name('tasks.update');
            Route::resource('tasks', 'TaskController')->except([
                'destroy', 'create', 'update'
            ]);
            Route::get('tasks/{id}/delete', 'TaskController@destroy')->name('tasks.destroy');

            Route::get('tasks/{id}/calendarable', 'TaskController@calendarableDetails')->name('tasks.calendarable');

            Route::post('tasks/{task}/progress', 'TaskController@progressStore')->name('task_progress.store');

            Route::post('tasks/{task}/discussions', 'TaskDiscussionController@index')->name('task_discussions.index');
            Route::post('tasks/store_discussions/{task}', 'TaskDiscussionController@store')->name('task_discussions.store');
            Route::get('tasks/{id}/delete_discussions', 'TaskDiscussionController@destroy')->name('task_discussions.destroy');

            Route::post('tasks/{task}/files', 'TaskFileController@index')->name('task_files.index');
            Route::post('tasks/store_files/{task}', 'TaskFileController@store')->name('task_files.store');
            Route::get('tasks/{id}/delete_files', 'TaskFileController@destroy')->name('task_files.destroy');

            Route::get('tasks/file_download/{id}', 'TaskFileController@download')->name('tasks.downloadFile');
            Route::post('tasks/{task}/notes', 'TaskController@notesStore')->name('task_notes.store');
        });

        Route::prefix('file_manager')->group(function ()
        {
            Route::post('files/update', 'FileManagerController@update')->name('files.update');
            Route::resource('files', 'FileManagerController')->except([
                'destroy', 'create', 'update', 'show'
            ]);
            Route::get('files/{id}/delete', 'FileManagerController@destroy')->name('files.destroy');
            Route::get('files/new/download/{id}', 'FileManagerController@download')->name('files.downloadFile');
            Route::post('files/delete/selected', 'FileManagerController@delete_by_selection')->name('mass_delete_files');

            Route::post('official_documents/update', 'OfficialDocumentController@update')->name('official_documents.update');
            Route::resource('official_documents', 'OfficialDocumentController')->except([
                'destroy', 'create', 'update', 'show'
            ]);
            Route::get('official_documents/{id}/delete', 'OfficialDocumentController@destroy')->name('official_documents.destroy');
            Route::get('official_documents/new/download/{id}', 'OfficialDocumentController@download')->name('official_documents.downloadFile');
            Route::post('official_documents/delete/selected', 'OfficialDocumentController@delete_by_selection')->name('mass_delete_official_documents');


            Route::get('file_config', 'Variables\FileManagerSettingController@index')->name('file_config.index');
            Route::post('file_config', 'Variables\FileManagerSettingController@store')->name('file_config.store');

        });

        Route::get('switch/language/{lang}', 'LocaleController@languageSwitch')->name('language.switch');

        Route::get('calendar/hr', 'CalendarableController@index')->name('calendar.index');
        Route::get('calendar/hr/load', 'CalendarableController@load')->name('calendar.load');

        Route::prefix('payroll')->group(function ()
        {
            Route::get('list', 'PayrollController@index')->name('payroll.index');
            Route::get('payslip', 'PayrollController@dummy')->name('paySlip.index');

            // Route::get('payslip/{id}', 'PayrollController@paySlip')->name('paySlip.show');
            Route::get('payslip_show', 'PayrollController@paySlip')->name('paySlip.show');

            Route::post('payslip/pay/{id}', 'PayrollController@payEmployee')->name('paySlip.pay');
            Route::post('payslip/payment/bulk', 'PayrollController@payBulk')->name('paySlip.bulk_pay');

            // Route::get('payslip/generate/{id}', 'PayrollController@paySlipGenerate')->name('paySlip.generate');
            Route::get('payslip/generate', 'PayrollController@paySlipGenerate')->name('paySlip.generate');

            Route::get('payment_history', 'PayslipController@index')->name('payment_history.index');
            Route::get('payslip/delete/{payslip}', 'PayslipController@delete')->name('payslip.destroy');
            Route::get('payslip/id/{payslip}', 'PayslipController@show')->name('payslip_details.show');
            Route::get('payslip/pdf/id/{payslip}', 'PayslipController@printPdf')->name('payslip.pdf');

        });

        Route::prefix('recruitment')->group(function ()
        {
            {
                Route::post('job_posts/update', 'JobPostController@update')->name('job_posts.update');
                Route::resource('job_posts', 'JobPostController')->except([
                    'destroy', 'create', 'update'
                ]);
                Route::get('job_posts/{id}/delete', 'JobPostController@destroy')->name('job_posts.destroy');
                Route::post('job_posts/delete/selected', 'JobPostController@delete_by_selection')->name('mass_delete_job_posts');
            }
            {
                Route::resource('job_candidates', 'JobCandidateController')->except([
                    'destroy', 'create', 'update', 'store'
                ]);
                Route::get('job_candidates/{id}/delete', 'JobCandidateController@destroy')->name('job_candidates.destroy');
            }
            {
                Route::post('job_interviews/update', 'JobInterviewController@update')->name('job_interviews.update');
                Route::resource('job_interviews', 'JobInterviewController')->except([
                    'destroy', 'create', 'update'
                ]);
                Route::get('job_interviews/{id}/delete', 'JobInterviewController@destroy')->name('job_interviews.destroy');
            }
            {
                Route::get('cms', 'CmsController@index')->name('cms.index');
                Route::post('cms', 'CmsController@store')->name('cms.store');
            }
        });


//        Route::post('events/update', 'EventController@update')->name('events.update');
//        Route::resource('events', 'EventController')->except([
//            'destroy', 'create', 'update'
//        ]);
//        Route::get('events/{id}/delete', 'EventController@destroy')->name('events.destroy');
//        Route::post('events/delete/selected', 'EventController@delete_by_selection')->name('mass_delete_events');
//
//        Route::get('events/{id}/calendarable', 'EventController@calendarableDetails')->name('events.calendarable');

//        Route::post('service-types/update', 'ServiceTypeController@update')->name('service-types.update');
//        Route::resource('service-types', 'ServiceTypeController')->except([
//            'destroy', 'create', 'update'
//        ]);
//        Route::get('service-types/{id}/delete', 'ServiceTypeController@destroy')->name('service-types.destroy');
//        Route::post('service-types/delete/selected', 'ServiceTypeController@delete_by_selection')->name('mass_delete_meetings');
//
//        Route::get('service-types/{id}/calendarable', 'ServiceTypeController@calendarableDetails')->name('service-types.calendarable');
    });


//	Route::get('/login', 'RouteClosureHandlerController@redirectToLogin')->name('redirectToLogin');
    Route::get('help', 'RouteClosureHandlerController@help')->name('help');
    Route::get('download/{slug}', 'RouteClosureHandlerController@getDownload')->name('resource.download');

    Route::get('/home', 'FrontEnd\HomeController@index')->name('home.front');
    Route::get('about', 'FrontEnd\AboutController@index')->name('about.front');
    Route::get('contact', 'FrontEnd\ContactController@index')->name('contact.front');


    Route::get('jobs', 'FrontEnd\JobController@index')->name('jobs');
    Route::get('jobs/details/{job_post}', 'FrontEnd\JobController@details')->name('jobs.details');
    Route::get('jobs/search/category/{url}', 'FrontEnd\JobController@searchByCategory')->name('jobs.searchByCategory');
    Route::get('jobs/search/job_type/{job_type}', 'FrontEnd\JobController@searchByJobType')->name('jobs.searchByJobType');
    Route::post('jobs/apply/{job}', 'FrontEnd\JobController@applyForJob')->name('jobs.apply');

    Route::post('dynamic_dependent/fetch-company', 'DynamicDependent@fetchCompany')->name('dynamic_company');
    Route::post('dynamic_dependent/fetch_subsidiary', 'DynamicDependent@fetchSubsidiaries')->name('dynamic_subsidiary');
    Route::post('dynamic_dependent/fetch_department', 'DynamicDependent@fetchDepartment')->name('dynamic_department');
    Route::post('dynamic_dependent/fetch_leave_types', 'DynamicDependent@fetchLeaveTypes')->name('dynamic_leave_types');
    Route::post('dynamic_dependent/fetch_employee', 'DynamicDependent@fetchEmployee')->name('dynamic_employee');
    Route::post('dynamic_dependent/fetch_employee_details', 'DynamicDependent@fetchEmployeeDetails')->name('dynamic_employee_details');
    Route::post('dynamic_dependent/fetch_employee_department', 'DynamicDependent@fetchEmployeeDepartment')->name('dynamic_employee_department');
    Route::post('dynamic_dependent/fetch_designation_department', 'DynamicDependent@fetchDesignationDepartment')->name('dynamic_designation_department');
    Route::post('dynamic_dependent/fetch_office_shifts', 'DynamicDependent@fetchOfficeShifts')->name('dynamic_office_shifts');
    Route::post('dynamic_dependent/company_employee/{ticket}', 'DynamicDependent@companyEmployee')->name('company_employee');
    Route::post('dynamic_dependent/fetch_candidate', 'DynamicDependent@fetchCandidate')->name('dynamic_candidate');
    Route::post('dynamic_dependent/fetch_branch', 'DynamicDependent@fetchBranch')->name('dynamic_branch');
    Route::post('dynamic_dependent/fetch_unit', 'DynamicDependent@fetchUnits')->name('dynamic_unit');



//Route::resource('employees', 'EmployeeController');


    //Performance Feature By - Md Irfan Chowdhury

    Route::group(['prefix' => 'performance','namespace'=>'Performance'], function (){

        Route::group(['prefix' => 'goal-type'], function () {
            Route::get('/index', 'GoalTypeController@index')->name('performance.goal-type.index');
            Route::post('/store', 'GoalTypeController@store')->name('performance.goal-type.store');
            Route::get('/edit', 'GoalTypeController@edit')->name('performance.goal-type.edit');
            Route::post('/update', 'GoalTypeController@update')->name('performance.goal-type.update');
            Route::get('/delete', 'GoalTypeController@delete')->name('performance.goal-type.delete');
            Route::get('/delete-checkbox', 'GoalTypeController@deleteCheckbox')->name('performance.goal-type.delete.checkbox');
        });

        Route::group(['prefix' => 'goal-tracking'], function () {
            Route::get('/index', 'GoalTrackingController@index')->name('performance.goal-tracking.index');
            Route::post('/store', 'GoalTrackingController@store')->name('performance.goal-tracking.store');
            Route::get('/edit', 'GoalTrackingController@edit')->name('performance.goal-tracking.edit');
            Route::post('/update', 'GoalTrackingController@update')->name('performance.goal-tracking.update');
            Route::get('/delete', 'GoalTrackingController@delete')->name('performance.goal-tracking.delete');
            Route::get('/delete-checkbox', 'GoalTrackingController@deleteCheckbox')->name('performance.goal-tracking.delete.checkbox');
        });

        Route::group(['prefix' => 'indicator'], function () {
            Route::get('/index', 'IndicatorController@index')->name('performance.indicator.index');
            Route::get('/get-designation', 'IndicatorController@getDesignationByComapny')->name('performance.indicator.get-designation-by-company');
            Route::post('/store', 'IndicatorController@store')->name('performance.indicator.store');
            Route::get('/edit', 'IndicatorController@edit')->name('performance.indicator.edit');
            Route::post('/update', 'IndicatorController@update')->name('performance.indicator.update');
            Route::get('/delete', 'IndicatorController@delete')->name('performance.indicator.delete');
            Route::get('/delete-checkbox', 'IndicatorController@deleteCheckbox')->name('performance.indicator.delete.checkbox');
        });

        Route::group(['prefix' => 'appraisal'], function () {
            Route::get('/index', 'AppraisalController@index')->name('performance.appraisal.index');
            Route::get('/get-employee','AppraisalController@getEmployee')->name('performance.appraisal.get-employee');
            Route::post('/store','AppraisalController@store')->name('performance.appraisal.store');
            Route::get('/edit','AppraisalController@edit')->name('performance.appraisal.edit');
            Route::post('/update','AppraisalController@update')->name('performance.appraisal.update');
            Route::get('/delete','AppraisalController@delete')->name('performance.appraisal.delete');
            Route::get('/delete-checkbox', 'AppraisalController@deleteCheckbox')->name('performance.appraisal.delete.checkbox');
        });
    });

});
