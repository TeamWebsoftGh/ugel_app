<?php

namespace App\Providers;

use App\Services\ContactService;
use App\Services\EnquiryService;
use App\Services\HolidayService;
use App\Services\Interfaces\IContactService;
use App\Services\Interfaces\IEnquiryService;
use App\Services\Interfaces\IHolidayService;
use App\Services\Interfaces\ILeaveScheduleService;
use App\Services\Interfaces\IMedicalService;
use App\Services\Interfaces\IServiceTypeService;
use App\Services\Interfaces\IVisitorLogService;
use App\Services\LeaveScheduleService;
use App\Services\MedicalService;
use App\Services\OfficeShiftService;
use App\Services\AnnouncementService;
use App\Services\OfferService;
use App\Services\PropertyTypeService;
use App\Services\BranchService;
use App\Services\CategoryService;
use App\Services\CompanyService;
use App\Services\CurrencyService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EmployeeService;
use App\Services\EventService;
use App\Services\IncomeTaxService;
use App\Services\Interfaces\IOfficeShiftService;
use App\Services\Interfaces\IAnnouncementService;
use App\Services\Interfaces\IOfferService;
use App\Services\Interfaces\IPropertyTypeService;
use App\Services\Interfaces\IBranchService;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\ICompanyService;
use App\Services\Interfaces\ICurrencyService;
use App\Services\Interfaces\IDepartmentService;
use App\Services\Interfaces\IDesignationService;
use App\Services\Interfaces\IEmployeeService;
use App\Services\Interfaces\IEventService;
use App\Services\Interfaces\IIncomeTaxService;
use App\Services\Interfaces\IKnowledgeBaseService;
use App\Services\Interfaces\ILeaveService;
use App\Services\Interfaces\ILeaveTypeService;
use App\Services\Interfaces\IMeetingService;
use App\Services\Interfaces\IPayDeductionService;
use App\Services\Interfaces\IPaymentService;
use App\Services\Interfaces\IPayRunService;
use App\Services\Interfaces\IPayStackService;
use App\Services\Interfaces\IDesignationChangeService;
use App\Services\Interfaces\IPublicationService;
use App\Services\Interfaces\IReviewService;
use App\Services\Interfaces\IRoleService;
use App\Services\Interfaces\ISmsService;
use App\Services\FinancialYearService;
use App\Services\AuditService;
use App\Services\ComplaintService;
use App\Services\ContactUsService;
use App\Services\Interfaces\IContactUsService;
use App\Services\Interfaces\IOrderService;
use App\Services\Interfaces\IPayBenefitService;
use App\Services\Interfaces\ISubsidiaryService;
use App\Services\Interfaces\ISupportTicketService;
use App\Services\Interfaces\ITaskService;
use App\Services\Interfaces\ITaxReliefService;
use App\Services\Interfaces\ITransferService;
use App\Services\Interfaces\ITravelService;
use App\Services\Interfaces\IOffenseService;
use App\Services\Interfaces\IWorkflowPositionService;
use App\Services\Interfaces\IWorkflowPositionTypeService;
use App\Services\Interfaces\IWorkflowService;
use App\Services\Interfaces\IWorkflowTypeService;
use App\Services\Interfaces\IWriterService;
use App\Services\CustomerService;
use App\Services\KnowledgeBaseService;
use App\Services\LeaveService;
use App\Services\LeaveTypeService;
use App\Services\MeetingService;
use App\Services\PayDeductionService;
use App\Services\PayRunService;
use App\Services\DesignationChangeService;
use App\Services\PublicationService;
use App\Services\RoleService;
use App\Services\ServiceTypeService;
use App\Services\SubsidiaryService;
use App\Services\SupportTicketService;
use App\Services\TaskService;
use App\Services\TaxReliefService;
use App\Services\TerminationService;
use App\Services\MessageService;
use App\Services\Interfaces\IFinancialYearService;
use App\Services\Interfaces\IAuditService;
use App\Services\Interfaces\IComplaintService;
use App\Services\Interfaces\ICustomerService;
use App\Services\Interfaces\ITerminationService;
use App\Services\Interfaces\IMessageService;
use App\Services\Interfaces\IUserService;
use App\Services\Interfaces\ISettingService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\PayStackService;
use App\Services\PayBenefitService;
use App\Services\ReviewService;
use App\Services\TransferService;
use App\Services\TravelService;
use App\Services\OffenseService;
use App\Services\SmsService;
use App\Services\UserService;
use App\Services\SettingService;
use App\Services\VisitorLogService;
use App\Services\WorkflowPositionService;
use App\Services\WorkflowPositionTypeService;
use App\Services\WorkflowService;
use App\Services\WorkflowTypeService;
use App\Services\WriterService;
use Illuminate\Support\ServiceProvider;

class IOCServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuditService::class, AuditService::class);
        $this->app->bind(ISettingService::class, SettingService::class);
        $this->app->bind(IContactService::class, ContactService::class);
        $this->app->bind(IServiceTypeService::class, ServiceTypeService::class);
        $this->app->bind(IFinancialYearService::class, FinancialYearService::class);
        $this->app->bind(IPaymentService::class, PaymentService::class);
        $this->app->bind(ITaxReliefService::class, TaxReliefService::class);
        $this->app->bind(IPayRunService::class, PayRunService::class);
        $this->app->bind(IDesignationChangeService::class, DesignationChangeService::class);
        $this->app->bind(IEmployeeService::class, EmployeeService::class);
        $this->app->bind(ITaskService::class, TaskService::class);

        //Common
        $this->app->bind(IAnnouncementService::class, AnnouncementService::class);
        $this->app->bind(IEventService::class, EventService::class);
        $this->app->bind(IMeetingService::class, MeetingService::class);

        //Workflow
        $this->app->bind(IWorkflowPositionService::class, WorkflowPositionService::class);
        $this->app->bind(IWorkflowPositionTypeService::class, WorkflowPositionTypeService::class);
        $this->app->bind(IWorkflowTypeService::class, WorkflowTypeService::class);
        $this->app->bind(IWorkflowService::class, WorkflowService::class);

        //Configurations
        $this->app->bind(IRoleService::class, RoleService::class);
        $this->app->bind(ICurrencyService::class, CurrencyService::class);

        //Organization
        $this->app->bind(ICompanyService::class, CompanyService::class);
        $this->app->bind(ISubsidiaryService::class, SubsidiaryService::class);
        $this->app->bind(IDepartmentService::class, DepartmentService::class);
        $this->app->bind(IDesignationService::class, DesignationService::class);
        $this->app->bind(IBranchService::class, BranchService::class);

        //Resource
        $this->app->bind(ICategoryService::class, CategoryService::class);
        $this->app->bind(IKnowledgeBaseService::class, KnowledgeBaseService::class);
        $this->app->bind(IPublicationService::class, PublicationService::class);

        //Customer Service
        $this->app->bind(ISupportTicketService::class, SupportTicketService::class);
        $this->app->bind(IVisitorLogService::class, VisitorLogService::class);
        $this->app->bind(IEnquiryService::class, EnquiryService::class);

        //HRM
        $this->app->bind(IOfferService::class, OfferService::class);
        $this->app->bind(IOffenseService::class, OffenseService::class);
        $this->app->bind(ITerminationService::class, TerminationService::class);
        $this->app->bind(IComplaintService::class, ComplaintService::class);
        $this->app->bind(IPropertyTypeService::class, PropertyTypeService::class);
        $this->app->bind(IMedicalService::class, MedicalService::class);
        $this->app->bind(ITransferService::class, TransferService::class);
        $this->app->bind(ITravelService::class, TravelService::class);

        //Timesheet
        $this->app->bind(ILeaveTypeService::class, LeaveTypeService::class);
        $this->app->bind(ILeaveService::class, LeaveService::class);
        $this->app->bind(IOfficeShiftService::class, OfficeShiftService::class);
        $this->app->bind(IHolidayService::class, HolidayService::class);
        $this->app->bind(ILeaveScheduleService::class, LeaveScheduleService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
