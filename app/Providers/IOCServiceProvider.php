<?php

namespace App\Providers;

use App\Services\AuditService;
use App\Services\BranchService;
use App\Services\BulkSmsService;
use App\Services\CategoryService;
use App\Services\ClientService;
use App\Services\ClientTypeService;
use App\Services\CompanyService;
use App\Services\ContactService;
use App\Services\CurrencyService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EnquiryService;
use App\Services\EventService;
use App\Services\FinancialYearService;
use App\Services\Interfaces\IAuditService;
use App\Services\Interfaces\IBranchService;
use App\Services\Interfaces\IBulkSmsService;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\IClientService;
use App\Services\Interfaces\IClientTypeService;
use App\Services\Interfaces\ICompanyService;
use App\Services\Interfaces\IContactService;
use App\Services\Interfaces\ICurrencyService;
use App\Services\Interfaces\IDepartmentService;
use App\Services\Interfaces\IDesignationService;
use App\Services\Interfaces\IEnquiryService;
use App\Services\Interfaces\IEventService;
use App\Services\Interfaces\IFinancialYearService;
use App\Services\Interfaces\IKnowledgeBaseService;
use App\Services\Interfaces\IMaintenanceCategoryService;
use App\Services\Interfaces\IMaintenanceService;
use App\Services\Interfaces\IMeetingService;
use App\Services\Interfaces\IPaymentService;
use App\Services\Interfaces\IPayRunService;
use App\Services\Interfaces\IPermissionService;
use App\Services\Interfaces\IPublicationService;
use App\Services\Interfaces\IRoleService;
use App\Services\Interfaces\IServiceTypeService;
use App\Services\Interfaces\ISettingService;
use App\Services\Interfaces\ISubsidiaryService;
use App\Services\Interfaces\ISupportTicketService;
use App\Services\Interfaces\ITaskService;
use App\Services\Interfaces\ITaxReliefService;
use App\Services\Interfaces\IUserService;
use App\Services\Interfaces\IVisitorLogService;
use App\Services\Interfaces\IWorkflowPositionService;
use App\Services\Interfaces\IWorkflowPositionTypeService;
use App\Services\Interfaces\IWorkflowService;
use App\Services\Interfaces\IWorkflowTypeService;
use App\Services\KnowledgeBaseService;
use App\Services\MaintenanceCategoryService;
use App\Services\MaintenanceService;
use App\Services\MeetingService;
use App\Services\PaymentService;
use App\Services\PayRunService;
use App\Services\PermissionService;
use App\Services\Properties\AmenityService;
use App\Services\Properties\Interfaces\IAmenityService;
use App\Services\Properties\Interfaces\IPropertyCategoryService;
use App\Services\Properties\Interfaces\IPropertyService;
use App\Services\Properties\Interfaces\IPropertyTypeService;
use App\Services\Properties\Interfaces\IPropertyUnitService;
use App\Services\Properties\Interfaces\IRoomService;
use App\Services\Properties\PropertyCategoryService;
use App\Services\Properties\PropertyService;
use App\Services\Properties\PropertyTypeService;
use App\Services\Properties\PropertyUnitService;
use App\Services\Properties\RoomService;
use App\Services\PublicationService;
use App\Services\RoleService;
use App\Services\ServiceTypeService;
use App\Services\SettingService;
use App\Services\SubsidiaryService;
use App\Services\SupportTicketService;
use App\Services\TaskService;
use App\Services\TaxReliefService;
use App\Services\UserService;
use App\Services\VisitorLogService;
use App\Services\WorkflowPositionService;
use App\Services\WorkflowPositionTypeService;
use App\Services\WorkflowService;
use App\Services\WorkflowTypeService;
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
        $this->app->bind(IPermissionService::class, PermissionService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuditService::class, AuditService::class);
        $this->app->bind(ISettingService::class, SettingService::class);
        $this->app->bind(IContactService::class, ContactService::class);
        $this->app->bind(IServiceTypeService::class, ServiceTypeService::class);
        $this->app->bind(IFinancialYearService::class, FinancialYearService::class);
        $this->app->bind(IPaymentService::class, PaymentService::class);
        $this->app->bind(ITaxReliefService::class, TaxReliefService::class);
        $this->app->bind(IPayRunService::class, PayRunService::class);
        $this->app->bind(IClientTypeService::class, ClientTypeService::class);
        $this->app->bind(IClientService::class, ClientService::class);
        $this->app->bind(ITaskService::class, TaskService::class);

        //Common
        $this->app->bind(IBulkSmsService::class, BulkSmsService::class);
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
        $this->app->bind(IMaintenanceCategoryService::class, MaintenanceCategoryService::class);
        $this->app->bind(IMaintenanceService::class, MaintenanceService::class);

        //Properties
        $this->app->bind(IAmenityService::class, AmenityService::class);
        $this->app->bind(IPropertyCategoryService::class, PropertyCategoryService::class);
        $this->app->bind(IPropertyTypeService::class, PropertyTypeService::class);
        $this->app->bind(IPropertyService::class, PropertyService::class);
        $this->app->bind(IPropertyUnitService::class, PropertyUnitService::class);
        $this->app->bind(IRoomService::class, RoomService::class);
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
