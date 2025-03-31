<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span data-key="t-menu">Main Menu</span></li>
    @can("read-admin-dashboard")
        <li class="nav-item">
            <a class="nav-link menu-link" href="{{route("home")}}">
                <i class="ri-dashboard-2-line"></i> <span data-key="t-widgets">Dashboard</span>
            </a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link menu-link" href="#announcement" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-notification-2-fill"></i> <span data-key="t-authentication">Communication</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*communication*')||request()->is('*popups*')) ? 'show' : '' }}" id="announcement">
            <ul class="nav nav-sm flex-column">
                @can("read-bulk-sms")
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sms" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="sms">
                            <span data-key="t-my-requests">Sms</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('*bulk-sms*') ? 'show' : '' }}" id="sms">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("bulk-sms.quick")}}" class="nav-link {{ request()->is('*quick-sms*') ? 'active' : '' }}" data-key="t-login_activity"> Quick Sms </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("bulk-sms.create")}}" class="nav-link {{ request()->is('*bulk-sms/create*') ? 'active' : '' }}" data-key="t-login_activity"> Send Sms </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("bulk-sms.index")}}" class="nav-link {{ request()->is('*bulk-sms') ? 'active' : '' }}" data-key="t-login_activity"> Bulk Sms </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#whatsapp" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="whatsapp">
                            <span data-key="t-my-requests">WhatsApp</span>
                        </a>
                        <div class="collapse menu-dropdown" id="whatsapp">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("whatsapp.quick")}}" class="nav-link {{ request()->is('*quick-whatsapp*') ? 'active' : '' }}" data-key="t-login_activity">Quick WhatsApp </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("whatsapp.create")}}" class="nav-link {{ request()->is('*whatsapp/create*') ? 'active' : '' }}" data-key="t-login_activity"> Send WhatsApp </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("whatsapp.index")}}" class="nav-link {{ request()->is('*whatsapp*') ? 'active' : '' }}" data-key="t-login_activity">All Messages </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{route("contacts.index")}}" class="nav-link {{ request()->is('*contacts*') ? 'active' : '' }}" data-key="t-user_activity"> Contacts </a>
                    </li>
                @endcan
                @can("read-popups")
                    <li class="nav-item">
                        <a href="{{route("popups.index")}}" class="nav-link {{ request()->is('*popups*') ? 'active' : '' }}" data-key="t-user_activity"> Popups </a>
                    </li>
                @endif
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link" href="#operations" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-calculator-fill"></i> <span data-key="t-authentication">Properties</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*properties*')||request()->is('*property-*')||request()->is('*amenities')|request()->is('*rooms')) ? 'show' : '' }}" id="operations">
            <ul class="nav nav-sm flex-column">
{{--                <li class="nav-item">--}}
{{--                    <a href="{{route("properties.create")}}" class="nav-link" data-key="t-login_activity"> Add New Property </a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a href="{{route("properties.all")}}" class="nav-link" data-key="t-user_activity"> List Properties </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("properties.index")}}" class="nav-link" data-key="t-user_activity"> Manage Properties </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("property-units.index")}}" class="nav-link" data-key="t-property_unit">Property Units </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("rooms.index")}}" class="nav-link" data-key="t-user_activity"> Rooms </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("property-types.index")}}" class="nav-link" data-key="t-property-types"> Property Types</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("property-categories.index")}}" class="nav-link" data-key="t-property-categories"> Property Categories</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("amenities.index")}}" class="nav-link" data-key="t-amenities">Amenities</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("reviews.index")}}" class="nav-link" data-key="t-user_activity"> Reviews </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link" href="#bookings" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-calculator-fill"></i> <span data-key="t-authentication">Billing Center</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*booking*')||request()->is('*invoice*')||request()->is('*booking')|request()->is('*booking')) ? 'show' : '' }}" id="bookings">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{route("bookings.index")}}" class="nav-link" data-key="t-amenities">Bookings</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("invoices.index")}}" class="nav-link" data-key="t-property-types"> Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("invoices.index")}}" class="nav-link" data-key="t-user_activity"> Transactions </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("invoice-items.index")}}" class="nav-link" data-key="t-property_unit">Invoice Items </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("booking-periods.index")}}" class="nav-link" data-key="t-amenities">Booking Periods</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link" href="#customers" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-calculator-fill"></i> <span data-key="t-authentication">Customers</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*cs*')) ? 'show' : '' }}" id="customers">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{route("admin.customers.create")}}" class="nav-link {{ (request()->is('*customers/create')) ? 'active' : '' }}" data-key="t-login_activity"> Add New Customer </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("admin.customers.index")}}" class="nav-link {{ (request()->is('*customers')) ? 'active' : '' }}" data-key="t-user_activity"> List Customers </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("admin.customers.organizations")}}" class="nav-link {{ (request()->is('*customers/organizations*')) ? 'active' : '' }}" data-key="t-user_activity"> Organizations </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("admin.customers.students")}}" class="nav-link {{ (request()->is('*customers/students*')) ? 'active' : '' }}" data-key="t-students">Students </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("admin.customer-types.index")}}" class="nav-link {{ (request()->is('*customer-types*')) ? 'active' : '' }}" > Customer Types</a>
                </li>
                <li class="nav-item">
                    <a href="{{route("admin.customer-types.index")}}" class="nav-link {{ (request()->is('*customer-types*')) ? 'active' : '' }}" >FAQS</a>
                </li>
            </ul>
        </div>
    </li>
    @if(settings('enable_workflow', 0))
        <li class="nav-item">
            <a class="nav-link menu-link" href="#requests" data-bs-toggle="collapse" role="button"
               aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-message-2-fill"></i> <span data-key="t-authentication">Requests</span>
            </a>
            <div class="collapse menu-dropdown {{ (request()->is('*workflow-requests*')) ? 'show' : '' }}" id="requests">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{route("employee-requests.my-requests")}}" class="nav-link {{ (request()->is('*my-requests*')) ? 'active' : '' }}" data-key="t-login_activity"> My Request </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route("employee-requests.index")}}" class="nav-link" data-key="t-user_activity"> Employee Requests </a>
                    </li>
                    @if(user()->can('read-error-logs'))
                        <li class="nav-item">
                            <a href="{{route("employee-requests.all-requests")}}" class="nav-link {{ (request()->is('*all-requests*')) ? 'active' : '' }}" data-key="t-error_logs"> All Request </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link menu-link" href="#customerService" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-ticket-2-fill"></i> <span data-key="t-authentication">Customer Service</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*customer-service*')) ? 'show' : '' }}" id="customerService">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="#ticket" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2">Support Ticket</a>
                    <div class="menu-dropdown collapse {{ (request()->is('*support-tickets*')) ? 'show' : '' }}" id="ticket" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route("support-tickets.create")}}" class="nav-link" data-key="t-hub"> Open a Ticket </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("support-tickets.my-tickets")}}" class="nav-link" data-key="t-calendar"> My Tickets </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("support-tickets.assigned")}}" class="nav-link" data-key="t-calendar"> Assigned Tickets </a>
                            </li>
                            @if(user()->can('read-support-tickets'))
                                <li class="nav-item">
                                    <a href="{{route("support-tickets.index")}}" class="nav-link" data-key="t-calendar"> All Tickets </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#maintenance" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2">Maintenance</a>
                    <div class="menu-dropdown collapse {{ (request()->is('*maintenance*')) ? 'show' : '' }}" id="maintenance" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route("maintenance-requests.create")}}" class="nav-link" data-key="t-hub">Add a Request </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("maintenance-requests.assigned")}}" class="nav-link" data-key="t-calendar"> Assigned Issues </a>
                            </li>
                            @if(user()->can('read-support-tickets'))
                                <li class="nav-item">
                                    <a href="{{route("maintenance-requests.index")}}" class="nav-link" data-key="t-calendar"> All Issues </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("maintenance-categories.index")}}" class="nav-link" data-key="t-hub"> Categories </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{route("enquiries.index")}}" class="nav-link" data-key="t-user_activity"> Enquiries </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link" href="#resources" data-bs-toggle="collapse" role="button"
           aria-expanded="false" aria-controls="sidebarAuth">
            <i class="ri-book-2-fill"></i> <span data-key="t-authentication">Resources</span>
        </a>
        <div class="collapse menu-dropdown {{ (request()->is('*resource*')) ? 'show' : '' }}" id="resources">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{route("resource.knowledge-base.all")}}" class="nav-link" data-key="t-user_activity"> Knowledge Hub </a>
                </li>
                @if(user()->can('create-knowledge-bases'))
                    <li class="nav-item">
                        <a href="{{route("resource.knowledge-base.index")}}" class="nav-link" data-key="t-calendar"> Manage Topics </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route("resource.categories.index")}}" class="nav-link" data-key="t-calendar"> Categories </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{route("resource.resources.all")}}" class="nav-link" data-key="t-user_activity"> Resources </a>
                </li>
                @if(user()->can('create-resources'))
                    <li class="nav-item">
                        <a href="{{route("resource.resources.index")}}" class="nav-link" data-key="t-calendar"> Manage Resources </a>
                    </li>
                @endif
            </ul>
        </div>
    </li>
    @canany(['read-payroll-reports', 'read-property-reports', 'read-logs'])
        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Audit & Report</span></li>
    @endcanany
    @if(user()->can('read-logs|read-error-logs'))
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
               aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-eye-2-fill"></i> <span data-key="t-authentication">Audit</span>
            </a>
            <div class="collapse menu-dropdown {{ (request()->is('*audit*')) ? 'show' : '' }}" id="sidebarAuth">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{route("audit.login_activity")}}" class="nav-link" data-key="t-login_activity"> User Logins </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route("audit.user_activity")}}" class="nav-link" data-key="t-user_activity"> User Activity </a>
                    </li>
                    @if(user()->can('read-error-logs'))
                        <li class="nav-item">
                            <a href="{{route('audit.error_logs')}}" class="nav-link" data-key="t-error_logs"> Error Logs </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @canany(['read-payroll-reports', 'read-property-reports'])
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button"
               aria-expanded="false" aria-controls="sidebarPages">
                <i class="ri-bar-chart-2-fill"></i> <span data-key="t-pages">Report</span>
            </a>
            <div class="collapse menu-dropdown {{ (request()->is('*reports*')) ? 'show' : '' }}" id="sidebarPages">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-key="t-starter"> Test Report </a>
                    </li>
                </ul>
            </div>
        </li>
    @endif

    @if(user()->can('read-subsidiaries|read-departments|read-users|read-site-settings'))
        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Configurations</span></li>
    @endif
    @canany(['read-companies', 'read-subsidiaries', 'read-departments', 'read-branches', 'read-designations'])
        <li class="nav-item">
            <a class="nav-link menu-link" href="#organization" data-bs-toggle="collapse" role="button"
               aria-expanded="false" aria-controls="sidebarAdvanceUI">
                <i class="ri-organization-chart"></i> <span data-key="t-advance-ui">Organization</span>
            </a>
            <div class="menu-dropdown collapse" id="organization" style="">
                <ul class="nav nav-sm flex-column">
                    @can('read-companies')
                        <li class="nav-item">
                            <a href="{{route('companies.kyc')}}" class="nav-link" data-key="t-site-settings">KYC</a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{route('organization.companies.index')}}" class="nav-link" data-key="t-site-settings">Companies</a>
                    </li>
                </ul>
            </div>
        </li>
    @endcanany

    @if(user()->can('read-users'))
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button"
               aria-expanded="false" aria-controls="sidebarAdvanceUI">
                <i class="ri-user-settings-line"></i> <span data-key="t-advance-ui">Users</span>
            </a>
            <div class="collapse menu-dropdown {{ (request()->is('*user-access*'))? 'show' : '' }}" id="sidebarAdvanceUI">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{route("admin.users.index")}}" class="nav-link {{ (request()->is('*users*')) ? 'active' : '' }}" data-key="t-users">Manage Users</a>
                    </li>
                    @if(user()->can('read-teams'))
                        <li class="nav-item">
                            <a href="{{route("admin.teams.index")}}" class="nav-link {{ (request()->is('*teams*')) ? 'active' : '' }}" data-key="t-roles">Teams</a>
                        </li>
                    @endif
                    @if(user()->can('read-roles'))
                        <li class="nav-item">
                            <a href="{{route("admin.roles.index")}}" class="nav-link {{ (request()->is('*roles*')) ? 'active' : '' }}" data-key="t-roles">User Roles</a>
                        </li>
                    @endif
                    @if(user()->can('read-permissions'))
                        <li class="nav-item">
                            <a href="{{route("admin.permissions.index")}}" class="nav-link {{ (request()->is('*permissions*')) ? 'active' : '' }}" data-key="t-permissions">Permissions</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if(user()->can('read-site-settings|read-mail-settings'))
        <li class="nav-item">
            <a class="nav-link menu-link collapsed" href="#configurations" data-bs-toggle="collapse" role="button" aria-controls="sidebarMultilevel">
                <i class="ri-share-line"></i> <span data-key="t-multi-level">Configuration</span>
            </a>
            <div class="menu-dropdown collapse {{ (request()->is('*configuration*')) || (request()->is('*workflows*'))? 'show' : '' }}" id="configurations" style="">
                <ul class="nav nav-sm flex-column">
                    @can('read-site-settings')
                        <li class="nav-item">
                            <a href="#generalSettings" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2">General Settings</a>
                            <div class="menu-dropdown collapse" id="generalSettings" style="">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('configuration.settings.site')}}" class="nav-link" data-key="t-site-settings">Site Settings</a>
                                    </li>
                                    @if(user()->can('read-mail-settings'))
                                        <li class="nav-item">
                                            <a href="{{route('configuration.settings.mail')}}" class="nav-link" data-key="t-mail-settings">Mail Settings</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('configuration.settings.sms')}}" class="nav-link" data-key="t-sms-settings">SMS Settings</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('configuration.settings.whatsapp')}}" class="nav-link" data-key="t-sms-settings">WhatsApp Settings</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a href="#workflowSettings" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2">Workflows</a>
                        <div class="menu-dropdown collapse  {{ (request()->is('*workflows*'))? 'show' : '' }}" id="workflowSettings" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('workflows.position-types.index')}}" class="nav-link" data-key="t-basic-tables">Position Types</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('workflows.positions.index')}}" class="nav-link" data-key="t-grid-js">Workflow Position</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('workflows.workflow-types.index')}}" class="nav-link" data-key="t-level-2.1">Workflow Types</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('workflows.workflows.index')}}" class="nav-link" data-key="t-list-js">Workflows</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                            <a href="#paymentSettings" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2">Payment</a>
                            <div class="menu-dropdown collapse  {{ (request()->is('*payment-gateways*'))? 'show' : '' }}" id="paymentSettings" style="">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route("configuration.currencies.index")}}" class="nav-link" data-key="t-level-2.1">Currencies</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route("configuration.payment-gateways.index")}}" class="nav-link" data-key="t-level-2.1">Payment Gateways</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                </ul>
            </div>
        </li>
    @endif
</ul>
