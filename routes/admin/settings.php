<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'namespace' => 'Setting'], function () {
    Route::prefix('settings')->group(function ()
    {
        Route::resource('variables', 'Variables\VariableController')->only(['index']);
        Route::resource('variables_method', 'Variables\VariableMethodController')->only(['index']);

        Route::resource('payroll_settings', 'Variables\PayrollSettingController')->only(['index']);
        Route::get('/empty_database', 'GeneralSettingController@emptyDatabase')->name('empty_database');
        Route::get('/export_database', 'GeneralSettingController@exportDatabase')->name('export_database');
    });

    //Payroll Settings
    Route::group(['prefix' => 'payroll-settings', 'namespace' => 'PayrollSetting'], function ()
    {
        //Financial Year & Pay Periods
        Route::get('financial_year/detail/{id}', 'FinancialYearController@show')->name('financial_year.detail');
        Route::get('financial_year/{id}/delete', 'FinancialYearController@destroy')->name('financial_year.destroy');
        Route::get('financial_year/payperiods', 'FinancialYearController@payPeriods')->name('pay_period.index');
        Route::resource('financial_year', 'FinancialYearController')->except(['create', 'update', 'show']);

        //Income Tax and income Tax Table
        Route::get('income_tax/detail/{id}', 'IncomeTaxController@show')->name('income_tax.detail');
        Route::get('income_tax/{id}/delete', 'IncomeTaxController@destroy')->name('income_tax.destroy');
       // Route::get('income_tax/income_tax_table', 'IncomeTaxController@taxTable')->name('income_tax_table.index');
        Route::resource('income_tax', 'IncomeTaxController')->except(['create', 'update', 'show']);

        Route::get('income_tax_table/detail/{id}', 'IncomeTaxTableController@show')->name('income_tax_table.detail');
        Route::get('income_tax_table/{id}/delete', 'IncomeTaxTableController@destroy')->name('income_tax_table.destroy');
        Route::resource('income_tax_table', 'IncomeTaxTableController')->except(['update', 'show']);

        Route::get('pay_benefit/detail/{id}', 'PayBenefitController@show')->name('pay_benefit.detail');
        Route::get('pay_benefit/{id}/delete', 'PayBenefitController@destroy')->name('pay_benefit.destroy');
        Route::resource('pay_benefit', 'PayBenefitController')->except(['create', 'update', 'show']);

        Route::get('pay_benefit_detail/detail/{id}', 'PayBenefitDetailController@show')->name('pay_benefit_details.detail');
        Route::get('pay_benefit_detail/{id}/delete', 'PayBenefitDetailController@destroy')->name('pay_benefit_details.destroy');
        Route::resource('pay_benefit_detail', 'PayBenefitDetailController')->except(['update', 'destroy']);

        Route::get('pay_deduction/detail/{id}', 'PayDeductionController@show')->name('pay_deduction.detail');
        Route::get('pay_deduction/{id}/delete', 'PayDeductionController@destroy')->name('pay_deduction.destroy');
        Route::resource('pay_deduction', 'PayDeductionController')->except(['create', 'update', 'show']);

        Route::get('pay_deduction_detail/detail/{id}', 'PayDeductionDetailController@show')->name('pay_deduction_details.detail');
        Route::get('pay_deduction_detail/{id}/delete', 'PayDeductionDetailController@destroy')->name('pay_deduction_details.destroy');
        Route::resource('pay_deduction_detail', 'PayDeductionDetailController')->except(['update', 'destroy']);

        Route::get('tax_relief/detail/{id}', 'TaxReliefController@show')->name('pay_deduction.detail');
        Route::get('tax_relief/{id}/delete', 'TaxReliefController@destroy')->name('pay_deduction.destroy');
        Route::resource('tax_relief', 'TaxReliefController')->except(['create', 'update', 'show']);

        Route::get('tax_relief_detail/detail/{id}', 'TaxReliefDetailController@show')->name('tax_relief_details.detail');
        Route::get('tax_relief_detail/{id}/delete', 'TaxReliefDetailController@destroy')->name('tax_relief_details.destroy');
        Route::resource('tax_relief_detail', 'TaxReliefDetailController')->except(['update', 'destroy']);

    });

    Route::prefix('dynamic_variable')->group(function ()
    {
        Route::post('award_type/update', 'Variables\AwardTypeController@update')->name('award_type.update');
        Route::get('award_type/{id}/delete', 'Variables\AwardTypeController@destroy')->name('award_type.destroy');
        Route::resource('award_type', 'Variables\AwardTypeController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('warning_type/update', 'Variables\WarningTypeController@update')->name('warning_type.update');
        Route::get('warning_type/{id}/delete', 'Variables\WarningTypeController@destroy')->name('warning_type.destroy');
        Route::resource('warning_type', 'Variables\WarningTypeController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('termination_type/update', 'Variables\TerminationTypeController@update')->name('termination_type.update');
        Route::get('termination_type/{id}/delete', 'Variables\TerminationTypeController@destroy')->name('termination_type.destroy');
        Route::resource('termination_type', 'Variables\TerminationTypeController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('employee_type/update', 'Variables\EmployeeTypeController@update')->name('employee_type.update');
        Route::get('employee_type/{id}/delete', 'Variables\EmployeeTypeController@destroy')->name('employee_type.destroy');
        Route::resource('employee_type', 'Variables\EmployeeTypeController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('training_type/update', 'Variables\TrainingTypeController@update')->name('training_type.update');
        Route::get('training_type/{id}/delete', 'Variables\TrainingTypeController@destroy')->name('training_type.destroy');
        Route::resource('training_type', 'Variables\TrainingTypeController')->except([
            'create', 'update', 'destroy', 'show']);


        Route::get('asset-categories/detail/{id}', 'Variables\AssetCategoryController@show')->name('asset-categories.detail');
        Route::resource('asset-categories', 'Variables\AssetCategoryController')->except([
            'create', 'update', 'show']);

        Route::post('document_type/update', 'Variables\DocumentTypeController@update')->name('document_type.update');
        Route::get('document_type/{id}/delete', 'Variables\DocumentTypeController@destroy')->name('document_type.destroy');
        Route::resource('document_type', 'Variables\DocumentTypeController')->except([
            'create', 'update', 'destroy', 'show']);
    });


    Route::prefix('dynamic_variable_method')->group(function ()
    {
        Route::post('travel_method/update', 'Variables\TravelMethodController@update')->name('travel_method.update');
        Route::get('travel_method/{id}/delete', 'Variables\TravelMethodController@destroy')->name('travel_method.destroy');
        Route::resource('travel_method', 'Variables\TravelMethodController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('payment_method/update', 'Variables\PaymentMethodController@update')->name('payment_method.update');
        Route::get('payment_method/{id}/delete', 'Variables\PaymentMethodController@destroy')->name('payment_method.destroy');
        Route::resource('payment_method', 'Variables\PaymentMethodController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::get('employee_qualification', 'Variables\QualificationEducationLevelController@index')->name('employee_qualification.index');

        Route::post('education_level/update', 'Variables\QualificationEducationLevelController@update')->name('education_level.update');
        Route::get('education_level/{id}/delete', 'Variables\QualificationEducationLevelController@destroy')->name('education_level.destroy');
        Route::resource('education_level', 'Variables\QualificationEducationLevelController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('language_skill/update', 'Variables\QualificationLanguageController@update')->name('language_skill.update');
        Route::get('language_skill/{id}/delete', 'Variables\QualificationLanguageController@destroy')->name('language_skill.destroy');
        Route::resource('language_skill', 'Variables\QualificationLanguageController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('general_skill/update', 'Variables\QualificationSkillController@update')->name('general_skill.update');
        Route::get('general_skill/{id}/delete', 'Variables\QualificationSkillController@destroy')->name('general_skill.destroy');
        Route::resource('general_skill', 'Variables\QualificationSkillController')->except([
            'create', 'update', 'destroy', 'show']);

        Route::post('job_categories/update', 'Variables\JobCategoryController@update')->name('job_categories.update');
        Route::get('job_categories/{id}/delete', 'Variables\JobCategoryController@destroy')->name('job_categories.destroy');
        Route::resource('job_categories', 'Variables\JobCategoryController')->except([
            'create', 'update', 'destroy', 'show']);

    });

    //IP Settings
    Route::group(['prefix' => 'ip_settings'], function () {
        Route::get('/', 'IPSettingController@index')->name('ip_setting.index');
        Route::post('/store', 'IPSettingController@store')->name('ip_setting.store');
        Route::get('/edit', 'IPSettingController@edit')->name('ip_setting.edit');
        Route::post('/update', 'IPSettingController@update')->name('ip_setting.update');
        Route::get('/delete', 'IPSettingController@delete')->name('ip_setting.delete');
        Route::get('/bulk_delete', 'IPSettingController@bulkDelete')->name('ip_setting.bulk_delete');
    });
});

//Settings
Route::group(['prefix' => 'configurations', 'as' => 'configuration.', 'namespace' => 'Configuration'],function () {
    // Route::get('account', 'AccountController@index')->name('account.index');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'],function () {
        // Route::get('account', 'AccountController@index')->name('account.index');
        Route::get('site', 'GeneralSettingController@general')->name('site');
        Route::put('site', 'GeneralSettingController@update')->name('site.store');
        Route::get('mail', 'GeneralSettingController@siteMail')->name('mail');
        Route::put('mail', 'GeneralSettingController@updateSiteMail')->name('mail.store');
        Route::get('sms', 'GeneralSettingController@siteSms')->name('sms');
        Route::put('sms', 'GeneralSettingController@updateSiteSms')->name('sms');
        Route::get('task', 'GeneralSettingController@task')->name('task');
        Route::put('task', 'GeneralSettingController@update')->name('task');
    });
    Route::get('currencies/detail/{id}', 'CurrencyController@edit')->name('currencies.detail');
    Route::resource('currencies', 'CurrencyController', ['except' => ['update', 'show', 'create']]);

    Route::group(['prefix' => 'menus'], function(){
        Route::get('/main-menu', ['uses'=>'DynamicMenusController@createMainMenu'])->name('menu.create');
        Route::post('/main-menu', ['uses'=>'DynamicMenusController@storeMainMenu'])->name('menu.store');
        Route::post('/remove-main-menu', ['uses'=>'DynamicMenusController@removeMainMenu'])->name('menu.destroy') ;
    });

    //Employee Settings
    Route::group(['namespace' => 'Leave'], function ()
    {
        //Leave types
        Route::get('leave-types/{leave_type_id}/create', 'LeaveTypeDetailController@create')->name('leave-type-detail.detail');
        Route::resource('leave-types', 'LeaveTypeController')->except(['update']);

        Route::resource('leave-type-details', 'LeaveTypeDetailController')->except(['update', 'destroy']);
    });
    Route::get('export-database', 'GeneralSettingController@exportDatabase')->name('database.export');
});
