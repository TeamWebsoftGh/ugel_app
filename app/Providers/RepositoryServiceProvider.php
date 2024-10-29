<?php

namespace App\Providers;

use App\Repositories\ServiceTypeRepository;
use App\Repositories\OfferRepository;
use App\Repositories\ContactRepository;
use App\Repositories\EnquiryRepository;
use App\Repositories\EventRepository;
use App\Repositories\AuditRepository;
use App\Repositories\PropertyTypeRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\DesignationRepository;
use App\Repositories\FinancialYearRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\Interfaces\IServiceTypeRepository;
use App\Repositories\Interfaces\IOfferRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\IDesignationChangeRepository;
use App\Repositories\Interfaces\IEnquiryRepository;
use App\Repositories\Interfaces\IEventRepository;
use App\Repositories\Interfaces\IHolidayRepository;
use App\Repositories\Interfaces\ILeaveScheduleRepository;
use App\Repositories\Interfaces\IMedicalRepository;
use App\Repositories\Interfaces\IOfficeShiftRepository;
use App\Repositories\Interfaces\IAnnouncementRepository;
use App\Repositories\Interfaces\IPropertyTypeRepository;
use App\Repositories\Interfaces\IBranchRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\ICompanyRepository;
use App\Repositories\Interfaces\IContactUsRepository;
use App\Repositories\Interfaces\ICurrencyRepository;
use App\Repositories\Interfaces\IDepartmentRepository;
use App\Repositories\Interfaces\IDesignationRepository;
use App\Repositories\Interfaces\IFinancialYearRepository;
use App\Repositories\Interfaces\IIncomeTaxRepository;
use App\Repositories\Interfaces\IKnowledgeBaseRepository;
use App\Repositories\Interfaces\ILeaveRepository;
use App\Repositories\Interfaces\ILeaveTypeRepository;
use App\Repositories\Interfaces\IMeetingRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IComplaintRepository;
use App\Repositories\Interfaces\IPayBenefitRepository;
use App\Repositories\Interfaces\IPayDeductionRepository;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IPriceRepository;
use App\Repositories\Interfaces\IPublicationRepository;
use App\Repositories\Interfaces\ISupportTicketRepository;
use App\Repositories\Interfaces\ITaskRepository;
use App\Repositories\Interfaces\ITaxReliefRepository;
use App\Repositories\Interfaces\ITransferRepository;
use App\Repositories\Interfaces\ITravelRepository;
use App\Repositories\Interfaces\IOffenseRepository;
use App\Repositories\Interfaces\ITerminationRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\IAuditRepository;
use App\Repositories\Interfaces\ICustomerRepository;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Interfaces\ISettingRepository;
use App\Repositories\Interfaces\IVisitorLogRepository;
use App\Repositories\Interfaces\IWorkflowPositionRepository;
use App\Repositories\Interfaces\IWorkflowPositionTypeRepository;
use App\Repositories\Interfaces\IWorkflowRepository;
use App\Repositories\Interfaces\IWorkflowTypeRepository;
use App\Repositories\KnowledgeBaseRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\LeaveScheduleRepository;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\MedicalRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\MessageRepository;
use App\Repositories\OfficeShiftRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ComplaintRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\DesignationChangeRepository;
use App\Repositories\PublicationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SupportTicketRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TransferRepository;
use App\Repositories\TravelRepository;
use App\Repositories\OffenseRepository;
use App\Repositories\UserRepository;
use App\Repositories\SettingRepository;
use App\Repositories\VisitorLogRepository;
use App\Repositories\WorkflowPositionRepository;
use App\Repositories\WorkflowPositionTypeRepository;
use App\Repositories\WorkflowRepository;
use App\Repositories\WorkflowTypeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IPermissionRepository::class, PermissionRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IAuditRepository::class, AuditRepository::class);
        $this->app->bind(ISettingRepository::class, SettingRepository::class);
        $this->app->bind(IContactRepository::class, ContactRepository::class);
        $this->app->bind(IFinancialYearRepository::class, FinancialYearRepository::class);
        $this->app->bind(ICurrencyRepository::class, CurrencyRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);

        //Common
        $this->app->bind(IAnnouncementRepository::class, AnnouncementRepository::class);
        $this->app->bind(IEventRepository::class, EventRepository::class);
        $this->app->bind(IMeetingRepository::class, MeetingRepository::class);

        //Workflow
        $this->app->bind(IWorkflowPositionRepository::class, WorkflowPositionRepository::class);
        $this->app->bind(IWorkflowPositionTypeRepository::class, WorkflowPositionTypeRepository::class);
        $this->app->bind(IWorkflowTypeRepository::class, WorkflowTypeRepository::class);
        $this->app->bind(IWorkflowRepository::class, WorkflowRepository::class);

        //Organization
        $this->app->bind(ICompanyRepository::class, CompanyRepository::class);
        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);
        $this->app->bind(IDepartmentRepository::class, DepartmentRepository::class);
        $this->app->bind(IDesignationRepository::class, DesignationRepository::class);
        $this->app->bind(IBranchRepository::class, BranchRepository::class);

        //Resources
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IKnowledgeBaseRepository::class, KnowledgeBaseRepository::class);
        $this->app->bind(IPublicationRepository::class, PublicationRepository::class);

        //Customer Service
        $this->app->bind(ISupportTicketRepository::class, SupportTicketRepository::class);
        $this->app->bind(IVisitorLogRepository::class, VisitorLogRepository::class);
        $this->app->bind(IEnquiryRepository::class, EnquiryRepository::class);

        //Properties
        $this->app->bind(IOffenseRepository::class, OffenseRepository::class);
        $this->app->bind(IComplaintRepository::class, ComplaintRepository::class);
        $this->app->bind(IPropertyTypeRepository::class, PropertyTypeRepository::class);
        $this->app->bind(IOfferRepository::class, OfferRepository::class);
        $this->app->bind(ITravelRepository::class, TravelRepository::class);
        $this->app->bind(ITransferRepository::class, TransferRepository::class);
        $this->app->bind(IMedicalRepository::class, MedicalRepository::class);
        $this->app->bind(IDesignationChangeRepository::class, DesignationChangeRepository::class);

        //Timesheet
        $this->app->bind(IOfficeShiftRepository::class, OfficeShiftRepository::class);
        $this->app->bind(IHolidayRepository::class, HolidayRepository::class);
        $this->app->bind(IServiceTypeRepository::class, ServiceTypeRepository::class);
        $this->app->bind(ILeaveRepository::class, LeaveRepository::class);
        $this->app->bind(ILeaveScheduleRepository::class, LeaveScheduleRepository::class);
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
