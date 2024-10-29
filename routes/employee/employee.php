<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
*/

Route::prefix('property')->group(function ()
{
    {
        Route::post('profile_picture/{employee}/store', 'Hrm\EmployeeController@storeProfilePicture')->name('profile_picture.store');
    }
    {
        Route::get('documents', 'EmployeeDocumentController@index')->name('documents.index');
        Route::get('documents/{id}/edit', 'EmployeeDocumentController@edit')->name('documents.edit');
        Route::get('documents/{employee}', 'EmployeeDocumentController@show')->name('documents.show');
        Route::post('documents/update', 'EmployeeDocumentController@update')->name('documents.update');
        Route::post('documents/{employee}/store', 'EmployeeDocumentController@store')->name('documents.store');
        Route::get('documents/{id}/delete', 'EmployeeDocumentController@destroy')->name('documents.destroy');
        Route::get('documents/document/download/{id}', 'EmployeeDocumentController@download')->name('documents_document.download');
    }


    {
        Route::get('bank_account', 'EmployeeBankAccountController@index')->name('bank_account.index');
        Route::get('bank_account/{id}/edit', 'EmployeeBankAccountController@edit')->name('bank_account.edit');
        Route::get('bank_account/{employee}', 'EmployeeBankAccountController@show')->name('bank_account.show');
        Route::post('bank_account/update', 'EmployeeBankAccountController@update')->name('bank_account.update');
        Route::post('bank_account/{employee}/store', 'EmployeeBankAccountController@store')->name('bank_account.store');
        Route::get('bank_account/{id}/delete', 'EmployeeBankAccountController@destroy')->name('bank_account.destroy');
    }
    {
        Route::post('employees/{employee}/storeSalary', 'EmployeeController@storeSalary')->name('employees_basicSalary.store');
    }
    {
        Route::get('salary_basic', 'SalaryBasicController@index')->name('salary_basic.index');
        Route::get('salary_basic/{employee}', 'SalaryBasicController@show')->name('salary_basic.show');
        Route::post('salary_basic/{employee}/store', 'SalaryBasicController@store')->name('salary_basic.store');
        Route::get('salary_basic/{id}/edit', 'SalaryBasicController@edit')->name('salary_basic.edit');
        Route::post('salary_basic/update', 'SalaryBasicController@update')->name('salary_basic.update');
        Route::get('salary_basic/{id}/delete', 'SalaryBasicController@destroy')->name('salary_basic.destroy');

    }
    {
        Route::get('salary_allowance', 'SalaryAllowanceController@index')->name('salary_allowance.index');
        Route::get('salary_allowance/{id}/edit', 'SalaryAllowanceController@edit')->name('salary_allowance.edit');
        Route::get('salary_allowance/{employee}', 'SalaryAllowanceController@show')->name('salary_allowance.show');
        Route::post('salary_allowance/update', 'SalaryAllowanceController@update')->name('salary_allowance.update');
        Route::post('salary_allowance/{employee}/store', 'SalaryAllowanceController@store')->name('salary_allowance.store');
        Route::get('salary_allowance/{id}/delete', 'SalaryAllowanceController@destroy')->name('salary_allowance.destroy');
    }
    {
        Route::get('salary_commission', 'SalaryCommissionController@index')->name('salary_commission.index');
        Route::get('salary_commission/{id}/edit', 'SalaryCommissionController@edit')->name('salary_commission.edit');
        Route::get('salary_commission/{employee}', 'SalaryCommissionController@show')->name('salary_commission.show');
        Route::post('salary_commission/update', 'SalaryCommissionController@update')->name('salary_commission.update');
        Route::post('salary_commission/{employee}/store', 'SalaryCommissionController@store')->name('salary_commission.store');
        Route::get('salary_commission/{id}/delete', 'SalaryCommissionController@destroy')->name('salary_commission.destroy');
    }
    {
        Route::get('salary_loan', 'SalaryLoanController@index')->name('salary_loan.index');
        Route::get('salary_loan/{id}/edit', 'SalaryLoanController@edit')->name('salary_loan.edit');
        Route::get('salary_loan/{employee}', 'SalaryLoanController@show')->name('salary_loan.show');
        Route::post('salary_loan/update', 'SalaryLoanController@update')->name('salary_loan.update');
        Route::post('salary_loan/{employee}/store', 'SalaryLoanController@store')->name('salary_loan.store');
        Route::get('salary_loan/{id}/delete', 'SalaryLoanController@destroy')->name('salary_loan.destroy');
    }
    {
        Route::get('salary_deduction', 'SalaryDeductionController@index')->name('salary_deduction.index');
        Route::get('salary_deduction/{id}/edit', 'SalaryDeductionController@edit')->name('salary_deduction.edit');
        Route::get('salary_deduction/{employee}', 'SalaryDeductionController@show')->name('salary_deduction.show');
        Route::post('salary_deduction/update', 'SalaryDeductionController@update')->name('salary_deduction.update');
        Route::post('salary_deduction/{employee}/store', 'SalaryDeductionController@store')->name('salary_deduction.store');
        Route::get('salary_deduction/{id}/delete', 'SalaryDeductionController@destroy')->name('salary_deduction.destroy');
    }
    {
        Route::get('other_payment', 'SalaryOtherPaymentController@index')->name('other_payment.index');
        Route::get('other_payment/{id}/edit', 'SalaryOtherPaymentController@edit')->name('other_payment.edit');
        Route::get('other_payment/{employee}', 'SalaryOtherPaymentController@show')->name('other_payment.show');
        Route::post('other_payment/update', 'SalaryOtherPaymentController@update')->name('other_payment.update');
        Route::post('other_payment/{employee}/store', 'SalaryOtherPaymentController@store')->name('other_payment.store');
        Route::get('other_payment/{id}/delete', 'SalaryOtherPaymentController@destroy')->name('other_payment.destroy');
    }
    {
        Route::get('salary_overtime', 'SalaryOvertimeController@index')->name('salary_overtime.index');
        Route::get('salary_overtime/{id}/edit', 'SalaryOvertimeController@edit')->name('salary_overtime.edit');
        Route::get('salary_overtime/{employee}', 'SalaryOvertimeController@show')->name('salary_overtime.show');
        Route::post('salary_overtime/update', 'SalaryOvertimeController@update')->name('salary_overtime.update');
        Route::post('salary_overtime/{employee}/store', 'SalaryOvertimeController@store')->name('salary_overtime.store');
        Route::get('salary_overtime/{id}/delete', 'SalaryOvertimeController@destroy')->name('salary_overtime.destroy');
    }
    {
        Route::get('employee_leave/{employee}', 'EmployeeLeaveController@index')->name('employee_leave.index');
        Route::get('employee_leave/details', 'EmployeeLeaveController@details')->name('employee_leave.details');
        Route::get('employee_leave/details/{id}', 'EmployeeLeaveController@show')->name('employee_leave.show');
    }

    {
        Route::get('employee_travel/{employee}', 'EmployeeTravelController@index')->name('employee_travel.index');
        Route::get('employee_travel/details', 'EmployeeTravelController@details')->name('employee_travel.details');
        Route::get('employee_travel/details/{id}', 'EmployeeTravelController@show')->name('employee_travel.show');
    }
    {
        Route::get('employee_training/{employee}', 'EmployeeTrainingController@index')->name('employee_training.index');
        Route::get('employee_training/details', 'EmployeeTrainingController@details')->name('employee_training.details');
        Route::get('employee_training/details/{id}', 'EmployeeTrainingController@show')->name('employee_training.show');
    }
    {
        Route::get('employee_ticket/{employee}', 'EmployeeTicketController@index')->name('employee_ticket.index');
        Route::get('employee_ticket/details', 'EmployeeTicketController@details')->name('employee_ticket.details');
        Route::get('employee_ticket/details/{id}', 'EmployeeTicketController@show')->name('employee_ticket.show');
    }
    {
        Route::get('employee_transfer/{employee}', 'EmployeeTransferController@index')->name('employee_transfer.index');
        Route::get('employee_transfer/details', 'EmployeeTransferController@details')->name('employee_transfer.details');
        Route::get('employee_transfer/details/{id}', 'EmployeeTransferController@show')->name('employee_transfer.show');
    }
    {
        Route::get('employee_promotion/{employee}', 'EmployeePromotionController@index')->name('employee_promotion.index');
        Route::get('employee_promotion/details', 'EmployeePromotionController@details')->name('employee_promotion.details');
        Route::get('employee_promotion/details/{id}', 'EmployeePromotionController@show')->name('employee_promotion.show');
    }
    {
        Route::get('employee_complaint/{employee}', 'EmployeeComplaintController@index')->name('employee_complaint.index');
        Route::get('employee_complaint/details', 'EmployeeComplaintController@details')->name('employee_complaint.details');
        Route::get('employee_complaint/details/{id}', 'EmployeeComplaintController@show')->name('employee_complaint.show');
    }
    {
        Route::get('employee_warning/{employee}', 'EmployeeWarningController@index')->name('employee_warning.index');
        Route::get('employee_warning/details', 'EmployeeWarningController@details')->name('employee_warning.details');
        Route::get('employee_warning/details/{id}', 'EmployeeWarningController@show')->name('employee_warning.show');
    }
    {
        Route::get('todo-list', 'EmployeeProjectController@myTodo')->name('todolist.index');
        Route::get('employee_project/{employee}', 'EmployeeProjectController@index')->name('employee_project.index');
        Route::get('employee_project/details', 'EmployeeProjectController@details')->name('employee_project.details');
        Route::get('employee_project/details/{id}', 'EmployeeProjectController@show')->name('employee_project.show');
    }
    {
        Route::get('employee_task/{employee}', 'EmployeeTaskController@index')->name('employee_task.index');
        Route::get('employee_task/details', 'EmployeeTaskController@details')->name('employee_task.details');
        Route::get('employee_task/details/{id}', 'EmployeeTaskController@show')->name('employee_task.show');
    }
    {
        Route::get('employee_payslip/{employee}', 'EmployeePayslipController@index')->name('employee_payslip.index');
        Route::get('employee_payslip/details', 'EmployeePayslipController@details')->name('employee_payslip.details');
        Route::get('employee_payslip/details/{id}', 'EmployeePayslipController@show')->name('employee_payslip.show');
    }
});

Route::group(['prefix' => 'property', 'namespace' => 'Employee'], function ()
{
    //Contact Persons
    Route::get('contact-persons/{employee}', 'EmployeeContactController@index')->name('contact-persons.index');
    Route::get('contact-persons/{employee}/create', 'EmployeeContactController@create')->name('contact-persons.create');
    Route::get('contact-persons/{employee}/{id}/edit', 'EmployeeContactController@edit')->name('contact-persons.edit');
    Route::get('contact-persons/{employee}/{id}', 'EmployeeContactController@show')->name('contact-persons.show');
    Route::post('contact-persons/{employee}/store', 'EmployeeContactController@store')->name('contact-persons.store');
    Route::delete('contact-persons/{employee}/{id}', 'EmployeeContactController@destroy')->name('contact-persons.destroy');

    //Qualifications
    Route::get('qualifications/{employee}', 'EmployeeQualificationController@index')->name('qualifications.index');
    Route::get('qualifications/{employee}/create', 'EmployeeQualificationController@create')->name('qualifications.create');
    Route::get('qualifications/{employee}/{id}/edit', 'EmployeeQualificationController@edit')->name('qualifications.edit');
    Route::get('qualifications/{employee}/{id}', 'EmployeeQualificationController@show')->name('qualifications.show');
    Route::post('qualifications/{employee}/store', 'EmployeeQualificationController@store')->name('qualifications.store');
    Route::delete('qualifications/{employee}/{id}', 'EmployeeQualificationController@destroy')->name('qualifications.destroy');

    //Work Experience
    Route::get('work-experience/{employee}', 'EmployeeWorkExperienceController@index')->name('work-experience.index');
    Route::get('work-experience/{employee}/create', 'EmployeeWorkExperienceController@create')->name('work-experience.create');
    Route::get('work-experience/{employee}/{id}/edit', 'EmployeeWorkExperienceController@edit')->name('work-experience.edit');
    Route::get('work-experience/{employee}/{id}', 'EmployeeWorkExperienceController@show')->name('work-experience.show');
    Route::post('work-experience/{employee}/store', 'EmployeeWorkExperienceController@store')->name('work-experience.store');
    Route::delete('work-experience/{employee}/{id}', 'EmployeeWorkExperienceController@destroy')->name('work-experience.destroy');

    //Work Permit
    Route::get('immigrations/{employee}', 'EmployeeImmigrationController@index')->name('immigrations.index');
    Route::get('immigrations/{employee}/create', 'EmployeeImmigrationController@create')->name('immigrations.create');
    Route::get('immigrations/{employee}/{id}/edit', 'EmployeeImmigrationController@edit')->name('immigrations.edit');
//    Route::get('immigrations/{employee}', 'EmployeeImmigrationController@show')->name('immigrations.show');
    Route::post('immigrations/{employee}/store', 'EmployeeImmigrationController@store')->name('immigrations.store');
    Route::delete('immigrations/{employee}/{id}', 'EmployeeImmigrationController@destroy')->name('immigrations.destroy');
    Route::get('immigrations/document/download/{id}', 'EmployeeImmigrationController@download')->name('immigrations_document.download');


    Route::get('employee-property-types/{employee}', 'EmployeeAwardController@index')->name('employee-property-types.index');
    Route::get('employee-property-types/details', 'EmployeeAwardController@details')->name('employee-property-types.details');
    Route::get('employee-property-types/details/{id}', 'EmployeeAwardController@show')->name('employee-property-types.show');

});
