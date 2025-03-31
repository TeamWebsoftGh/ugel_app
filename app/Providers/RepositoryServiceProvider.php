<?php

namespace App\Providers;

use App\Repositories\AuditRepository;
use App\Repositories\Billing\BookingPeriodRepository;
use App\Repositories\Billing\BookingRepository;
use App\Repositories\Billing\Interfaces\IBookingPeriodRepository;
use App\Repositories\Billing\Interfaces\IBookingRepository;
use App\Repositories\Billing\Interfaces\IInvoiceItemRepository;
use App\Repositories\Billing\Interfaces\IInvoiceRepository;
use App\Repositories\Billing\InvoiceItemRepository;
use App\Repositories\Billing\InvoiceRepository;
use App\Repositories\BranchRepository;
use App\Repositories\BulkSmsRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ClientTypeRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ContactRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\DesignationRepository;
use App\Repositories\EnquiryRepository;
use App\Repositories\EventRepository;
use App\Repositories\FinancialYearRepository;
use App\Repositories\Interfaces\IAuditRepository;
use App\Repositories\Interfaces\IBranchRepository;
use App\Repositories\Interfaces\IBulkSmsRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IClientRepository;
use App\Repositories\Interfaces\IClientTypeRepository;
use App\Repositories\Interfaces\ICompanyRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\ICurrencyRepository;
use App\Repositories\Interfaces\IDepartmentRepository;
use App\Repositories\Interfaces\IDesignationRepository;
use App\Repositories\Interfaces\IEnquiryRepository;
use App\Repositories\Interfaces\IEventRepository;
use App\Repositories\Interfaces\IFinancialYearRepository;
use App\Repositories\Interfaces\IKnowledgeBaseRepository;
use App\Repositories\Interfaces\IMaintenanceCategoryRepository;
use App\Repositories\Interfaces\IMaintenanceRepository;
use App\Repositories\Interfaces\IMeetingRepository;
use App\Repositories\Interfaces\IPaymentGatewayRepository;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IPublicationRepository;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\ISettingRepository;
use App\Repositories\Interfaces\ISupportTicketRepository;
use App\Repositories\Interfaces\ITaskRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Interfaces\IVisitorLogRepository;
use App\Repositories\Interfaces\IWorkflowPositionRepository;
use App\Repositories\Interfaces\IWorkflowPositionTypeRepository;
use App\Repositories\Interfaces\IWorkflowRepository;
use App\Repositories\Interfaces\IWorkflowTypeRepository;
use App\Repositories\KnowledgeBaseRepository;
use App\Repositories\MaintenanceCategoryRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\Property\AmenityRepository;
use App\Repositories\Property\Interfaces\IAmenityRepository;
use App\Repositories\Property\Interfaces\IPropertyCategoryRepository;
use App\Repositories\Property\Interfaces\IPropertyRepository;
use App\Repositories\Property\Interfaces\IPropertyTypeRepository;
use App\Repositories\Property\Interfaces\IReviewRepository;
use App\Repositories\Property\Interfaces\IRoomRepository;
use App\Repositories\Property\PropertyCategoryRepository;
use App\Repositories\Property\PropertyRepository;
use App\Repositories\Property\PropertyTypeRepository;
use App\Repositories\Property\ReviewRepository;
use App\Repositories\Property\RoomRepository;
use App\Repositories\PublicationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SupportTicketRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
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
        //User
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IPermissionRepository::class, PermissionRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IAuditRepository::class, AuditRepository::class);
        $this->app->bind(ISettingRepository::class, SettingRepository::class);
        $this->app->bind(IClientRepository::class, ClientRepository::class);
        $this->app->bind(IClientTypeRepository::class, ClientTypeRepository::class);
        $this->app->bind(IFinancialYearRepository::class, FinancialYearRepository::class);
        $this->app->bind(ICurrencyRepository::class, CurrencyRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);

        //Common
        $this->app->bind(IBulkSmsRepository::class, BulkSmsRepository::class);
        $this->app->bind(IEventRepository::class, EventRepository::class);
        $this->app->bind(IMeetingRepository::class, MeetingRepository::class);
        $this->app->bind(IContactRepository::class, ContactRepository::class);

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
        $this->app->bind(IMaintenanceCategoryRepository::class, MaintenanceCategoryRepository::class);
        $this->app->bind(IMaintenanceRepository::class, MaintenanceRepository::class);

        //Properties
        $this->app->bind(IPropertyCategoryRepository::class, PropertyCategoryRepository::class);
        $this->app->bind(IPropertyTypeRepository::class, PropertyTypeRepository::class);
        $this->app->bind(IAmenityRepository::class, AmenityRepository::class);
        $this->app->bind(IPropertyRepository::class, PropertyRepository::class);
        $this->app->bind(IRoomRepository::class, RoomRepository::class);
        $this->app->bind(IReviewRepository::class, ReviewRepository::class);

        //Billing
        $this->app->bind(IBookingPeriodRepository::class, BookingPeriodRepository::class);
        $this->app->bind(IBookingRepository::class, BookingRepository::class);
        $this->app->bind(IInvoiceItemRepository::class, InvoiceItemRepository::class);
        $this->app->bind(IInvoiceRepository::class, InvoiceRepository::class);

        //Configurations
        $this->app->bind(IPaymentGatewayRepository::class, PaymentGatewayRepository::class);
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
