@extends('layouts.main')
@section('title', 'Employee Details')
@section('page-title', 'Employees')
@section('content')
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="/assets/images/profile-bg.jpg" alt="" class="profile-wid-img">
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="{{asset($employee->UserImage)}}" alt="{{$employee->fullname}}" class="img-thumbnail rounded-circle">
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{$employee->fullname}}</h3>
                    <p class="text-white text-opacity-75">{{$employee->designation->designation_name}}</p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{$employee->subsidiary->subsidiary_name}}</div>
                        <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{$employee->department->department_name}}</div>
                        <div>
                            <i class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{$employee->branch->branch_name}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">24.3K</h4>
                            <p class="fs-14 mb-0">Followers</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">1.3K</h4>
                            <p class="fs-14 mb-0">Following</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex profile-wrapper">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab" aria-selected="true">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">General</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab" aria-selected="false" tabindex="-1">
                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Core HR</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab" aria-selected="false" tabindex="-1">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Payroll</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="false" tabindex="-1">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documents</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="false" tabindex="-1">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Leave</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="false" tabindex="-1">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Payslips</span>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="#" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <h4 class="text-white">General Info</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="nav flex-column nav-pills border" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link mb-2 active" id="basic-info-tab" data-bs-toggle="pill" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true" tabindex="-1">Bio Data</a>
                                            <a class="nav-link mb-2" id="company-info-tab" data-bs-toggle="pill" href="#company-info" role="tab" aria-controls="company-info" aria-selected="false">Employment Info</a>
                                            <a class="nav-link mb-2" id="contact-info-tab" data-bs-toggle="pill" href="#contact-info" role="tab" aria-controls="contact-info" aria-selected="false" tabindex="-1">Contact & Address</a>
                                            <a class="nav-link mb-2" id="social-info-tab" data-bs-toggle="pill" href="#social-info" role="tab" aria-controls="social-info" aria-selected="false" tabindex="-1">Social Info</a>
                                            <a class="nav-link mb-2 dt-link" id="contact-persons-tab" data-bs-toggle="pill" href="#contact-persons" role="tab" data-table="emergency" data-url="{{route('contact-persons.index',$employee->id)}}" tabindex="-1">Contact Persons</a>
                                            <a class="nav-link mb-2 dt-link" id="qualifications-tab" data-bs-toggle="pill" href="#qualifications" role="tab" data-table="qualifications" data-url="{{route('qualifications.index',$employee->id)}}" tabindex="-1">Qualifications</a>
                                            <a class="nav-link mb-2 dt-link" id="employment-tab" data-bs-toggle="pill" href="#employment" role="tab" aria-controls="employment" data-table="employment" data-url="{{route('work-experience.index',$employee->id)}}" tabindex="-1">Work Experiences</a>
                                            <a class="nav-link mb-2 dt-link" id="immigration-tab" data-bs-toggle="pill" href="#immigration" role="tab" aria-controls="immigration" data-table="immigration" data-url="{{route('immigrations.index',$employee->id)}}" tabindex="-1">Work Permit</a>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                                            <div class="tab-pane fade active show" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                                                <h4>Basic Information</h4>
                                                <hr>
                                                @include("employee.partials.basic-info")
                                            </div>
                                            <div class="tab-pane fade" id="company-info" role="tabpanel" aria-labelledby="company-info-tab">
                                                <h4>Employment Information</h4>
                                                <hr>
                                                @include("employee.partials.company-info")
                                            </div>
                                            <div class="tab-pane fade" id="contact-info" role="tabpanel" aria-labelledby="contact-info-tab">
                                                <h4>Contact Information</h4>
                                                <hr>
                                                @include("employee.partials.contact-info")
                                            </div>
                                            <div class="tab-pane fade" id="social-info" role="tabpanel" aria-labelledby="social-info-tab">
                                                <h4>Social Profiles</h4>
                                                <hr>
                                                @include('employee.partials.social-info')
                                            </div>
                                            <div class="tab-pane fade" id="contact-persons" role="tabpanel" aria-labelledby="contact-persons-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Contact Persons
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="emergency-table" class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>{{trans('general.name')}}</th>
                                                            <th>{{__('Contact Type')}}</th>
                                                            <th>{{trans('general.relation')}}</th>
                                                            <th>{{trans('general.email')}}</th>
                                                            <th>{{trans('general.phone')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="qualifications" role="tabpanel" aria-labelledby="qualifications-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Qualifications
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="qualifications-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('School/University')}}</th>
                                                            <th>{{__('Time Period')}}</th>
                                                            <th>{{__('Certificate')}}</th>
                                                            <th>{{__('Education Level')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Work Experience
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="employment-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('Company')}}</th>
                                                            <th>{{__('Start Date')}}</th>
                                                            <th>{{__('End Date')}}</th>
                                                            <th>{{__('Position Held')}}</th>
                                                            <th>{{trans('general.description')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="immigration" role="tabpanel" aria-labelledby="immigration-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Work Permit
                                                    <button type="button" class="btn btn-primary ms-auto add_dt_btn" data-url="{{route("immigrations.create", $employee->id)}}"><i class="fa fa-save"></i> Add New</button>
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="immigration-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{trans('general.document')}}</th>
                                                            <th>{{__('Issue Date')}}</th>
                                                            <th>{{__('Expired Date')}}</th>
                                                            <th>{{__('Issue By')}}</th>
                                                            <th>{{__('Review Date')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="activities" role="tabpanel">
                        <h4 class="text-white">Core HRM</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="nav flex-column nav-pills border" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link mb-2 active" id="awards-tab" data-bs-toggle="pill" href="#awards" role="tab" aria-controls="awards" aria-selected="true" tabindex="-1">Awards</a>
                                            <a class="nav-link mb-2" id="designation-change-tab" data-bs-toggle="pill" href="#designation-change" role="tab" aria-controls="designation-change" aria-selected="false">Designation Change</a>
                                            <a class="nav-link mb-2" id="trainings-tab" data-bs-toggle="pill" href="#trainings" role="tab" aria-controls="trainings" aria-selected="false" tabindex="-1">Trainings</a>
                                            <a class="nav-link mb-2" id="transfers-tab" data-bs-toggle="pill" href="#transfers" role="tab" aria-controls="transfers" aria-selected="false" tabindex="-1">Transfers</a>
                                            <a class="nav-link mb-2 dt-link" id="travels-tab" data-bs-toggle="pill" href="#travels" role="tab" data-table="travels" data-url="{{route('contact-persons.index',$employee->id)}}" tabindex="-1">Travels</a>
                                            <a class="nav-link mb-2 dt-link" id="complaints-tab" data-bs-toggle="pill" href="#complaints" role="tab" data-table="complaints" data-url="{{route('qualifications.index',$employee->id)}}" tabindex="-1">Complaints</a>
                                            <a class="nav-link mb-2 dt-link" id="sanction-tab" data-bs-toggle="pill" href="#sanction" role="tab" aria-controls="sanction" data-table="sanction" data-url="{{route('work-experience.index',$employee->id)}}" tabindex="-1">Sanction & Discipline</a>
                                            <a class="nav-link mb-2 dt-link" id="assets-tab" data-bs-toggle="pill" href="#assets" role="tab" aria-controls="assets" data-table="assets" data-url="{{route('immigrations.index',$employee->id)}}" tabindex="-1">Employee Asset</a>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                                            <div class="tab-pane fade active show" id="awards" role="tabpanel" aria-labelledby="awards-tab">
                                                <h4>Awards</h4>
                                                <hr>
                                                @include("employee.partials.basic-info")
                                            </div>
                                            <div class="tab-pane fade" id="designation-change" role="tabpanel" aria-labelledby="designation-change-tab">
                                                <h4>Designation Change</h4>
                                                <hr>
                                                @include("employee.partials.company-info")
                                            </div>
                                            <div class="tab-pane fade" id="trainings" role="tabpanel" aria-labelledby="trainings-tab">
                                                <h4>Trainings</h4>
                                                <hr>
                                                @include("employee.partials.contact-info")
                                            </div>
                                            <div class="tab-pane fade" id="transfers" role="tabpanel" aria-labelledby="transfers-tab">
                                                <h4>Transfers</h4>
                                                <hr>
                                                @include('employee.partials.social-info')
                                            </div>
                                            <div class="tab-pane fade" id="travels" role="tabpanel" aria-labelledby="travels-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Travels
                                                    <button type="button" class="btn btn-primary ms-auto add_dt_btn" data-url="{{route("contact-persons.create", $employee->id)}}"><i class="fa fa-save"></i> Add New</button>
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="emergency-table" class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>{{trans('general.name')}}</th>
                                                            <th>{{__('Contact Type')}}</th>
                                                            <th>{{trans('general.relation')}}</th>
                                                            <th>{{trans('general.email')}}</th>
                                                            <th>{{trans('general.phone')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="complaints" role="tabpanel" aria-labelledby="complaints-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Complaints
                                                    <button type="button" class="btn btn-primary ms-auto add_dt_btn" data-url="{{route("qualifications.create", $employee->id)}}"><i class="fa fa-save"></i> Add New</button>
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="qualifications-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('School/University')}}</th>
                                                            <th>{{__('Time Period')}}</th>
                                                            <th>{{__('Certificate')}}</th>
                                                            <th>{{__('Education Level')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="sanction" role="tabpanel" aria-labelledby="sanction-tab">
                                                <h4 class="d-flex justify-content-between align-items-center">
                                                    Sanction & Discipline
                                                    <button type="button" class="btn btn-primary ms-auto add_dt_btn" data-url="{{route("work-experience.create", $employee->id)}}"><i class="fa fa-save"></i> Add New</button>
                                                </h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="employment-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('Company')}}</th>
                                                            <th>{{__('Start Date')}}</th>
                                                            <th>{{__('End Date')}}</th>
                                                            <th>{{__('Position Held')}}</th>
                                                            <th>{{trans('general.description')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="assets-tab">
                                                <h4>Employee Assets</h4>
                                                <hr>
                                                <div class="table-responsive">
                                                    <table id="immigration-table" class="table ">
                                                        <thead>
                                                        <tr>
                                                            <th>{{trans('general.document')}}</th>
                                                            <th>{{__('Issue Date')}}</th>
                                                            <th>{{__('Expired Date')}}</th>
                                                            <th>{{__('Issue By')}}</th>
                                                            <th>{{__('Review Date')}}</th>
                                                            <th class="not-exported">{{trans('general.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="projects" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-warning">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Chat App Update</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">2 year Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                                            J
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">ABC Project Customization</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">2 month Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-primary-subtle text-primary fs-10"> Progress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-8.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-info">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Client - Frank Hook</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">1 hr Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0"> Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                                            M
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Velzon Project</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">11 hr Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">Completed</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-5.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-danger">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Brand Logo Design</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">10 min Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                                            E
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Chat App</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">8 hr Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                                            R
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-8.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-warning">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Project Update</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">48 min Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-5.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Client - Jennifer</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">30 min Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-primary-subtle text-primary fs-10">Process</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0"> Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none mb-xxl-0 profile-project-info">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Bsuiness Template - UI/UX design</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">7 month Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">Completed</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-2.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none mb-xxl-0  profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Update Project</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">1 month Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                                            A
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none mb-sm-0  profile-project-danger">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">Bank Management System</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">10 month Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">Completed</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-5.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none mb-0  profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#" class="text-body">PSD to HTML Convert</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">29 min Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mt-4">
                                            <ul class="pagination pagination-separated justify-content-center mb-0">
                                                <li class="page-item disabled">
                                                    <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                </li>
                                                <li class="page-item active">
                                                    <a href="javascript:void(0);" class="page-link">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">4</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">5</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-title flex-grow-1 mb-0">Documents</h5>
                                    <div class="flex-shrink-0">
                                        <input class="form-control d-none" type="file" id="formFile">
                                        <label for="formFile" class="btn btn-danger"><i class="ri-upload-2-fill me-1 align-bottom"></i> Upload File</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-borderless align-middle mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th scope="col">File Name</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Size</th>
                                                    <th scope="col">Upload Date</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-primary-subtle text-primary rounded fs-20">
                                                                    <i class="ri-file-zip-fill"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0"><a href="javascript:void(0)">Artboard-documents.zip</a>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>Zip File</td>
                                                    <td>4.57 MB</td>
                                                    <td>12 Dec 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink15" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink15">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                <li class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-danger-subtle text-danger rounded fs-20">
                                                                    <i class="ri-file-pdf-fill"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Bank Management System</a></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>PDF File</td>
                                                    <td>8.89 MB</td>
                                                    <td>24 Nov 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink3" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink3">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                <li class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-secondary-subtle text-secondary rounded fs-20">
                                                                    <i class="ri-video-line"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Tour-video.mp4</a></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>MP4 File</td>
                                                    <td>14.62 MB</td>
                                                    <td>19 Nov 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink4" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink4">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                <li class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-success-subtle text-success rounded fs-20">
                                                                    <i class="ri-file-excel-fill"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Account-statement.xsl</a></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>XSL File</td>
                                                    <td>2.38 KB</td>
                                                    <td>14 Nov 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink5" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink5">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                <li class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-info-subtle text-info rounded fs-20">
                                                                    <i class="ri-folder-line"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Project Screenshots Collection</a></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>Floder File</td>
                                                    <td>87.24 MB</td>
                                                    <td>08 Nov 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink6" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink6">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle"></i>View</a></li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-danger-subtle text-danger rounded fs-20">
                                                                    <i class="ri-image-2-fill"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <h6 class="fs-15 mb-0">
                                                                    <a href="javascript:void(0);">Velzon-logo.png</a>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>PNG File</td>
                                                    <td>879 KB</td>
                                                    <td>02 Nov 2021</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink7" data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="ri-equalizer-fill"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink7">
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle"></i>Download</a></li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="javascript:void(0);" class="text-success"><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load more </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

@endsection
@section("js")
    @include('layouts.shared.dt-scripts')
    <script src="/js/pages/employees/show.js"></script>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            const fp = flatpickr(".date", {
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true
            }); // flatpickr
        });

        $('[data-table="document"]').one('click', function (e) {
            @include('employee.documents.index_js')
        });

        $('[data-table="bank_account"]').one('click', function (e) {
            @include('employee.bank_account.index_js')
        });

        $('#profile-tab').one('click', function (e) {
            @include('employee.profile_picture.index_js')
        });

        $('#set_salary-tab').one('click', function (e) {
            @include('employee.salary.basic_salary_js')
        });

        $('#salary_allowance-tab').one('click', function (e) {
            @include('employee.salary.allowance.index_js')
        });

        $('#salary_commission-tab').one('click', function (e) {
            @include('employee.salary.commission.index_js')
        });

        $('#salary_loan-tab').one('click', function (e) {
            @include('employee.salary.loan.index_js')
        });

        $('#salary_deduction-tab').one('click', function (e) {
            @include('employee.salary.deduction.index_js')
        });

        $('#other_payment-tab').one('click', function (e) {
            @include('employee.salary.other_payment.index_js')
        });

        $('#salary_overtime-tab').one('click', function (e) {
            @include('employee.salary.overtime.index_js')
        });

        $('#leave-tab').one('click', function (e) {
            @include('employee.leave.index_js')
        });

        $('#employee_core_hr-tab').one('click', function (e) {
            @include('employee.core_hr.property-types.index_js')
        });

        $('#employee_travel-tab').one('click', function (e) {
            @include('employee.core_hr.travel.index_js')
        });

        $('#employee_training-tab').one('click', function (e) {
            @include('employee.core_hr.training.index_js')
        });

        $('#employee_ticket-tab').one('click', function (e) {
            @include('employee.core_hr.ticket.index_js')
        });

        $('#employee_transfer-tab').one('click', function (e) {
            @include('employee.core_hr.transfer.index_js')
        });

        $('#employee_promotion-tab').one('click', function (e) {
            @include('employee.core_hr.promotion.index_js')
        });

        $('#employee_complaint-tab').one('click', function (e) {
            @include('employee.core_hr.complaint.index_js')
        });

        $('#employee_warning-tab').one('click', function (e) {
            @include('employee.core_hr.warning.index_js')
        });

        $('#employee_project_task-tab').one('click', function (e) {
            @include('employee.project_task.project.index_js')
        });

        $('#employee_task-tab').one('click', function (e) {
            @include('employee.project_task.task.index_js')
        });

        $('#employee_payslip-tab').one('click', function (e) {
            @include('employee.payslip.index_js')
        });

        $('#basic_sample_form, #basic_sample_form3a, #basic_sample_form3, #basic_sample_form4').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('employees.store') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    for (control in XMLHttpRequest.responseJSON.errors) {
                        $('#error-' + control).html(XMLHttpRequest.responseJSON.errors[control]);
                    }
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.Result.toLowerCase(),
                        title: 'Employee Update',
                        text: data.Message,
                    });
                    $('span.text-danger').html('');
                }
            });
        });

        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let dependent = $(this).data('shift_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_office_shifts') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#office_shift_id').html(result);
                        $('#designation_id').html('');
                        $('select').selectpicker();
                    }
                });
            }
        });

        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let dependent = $(this).data('dependent');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#department_id').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });

        $('.designation').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let designation_name = $(this).data('designation_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_designation_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, designation_name: designation_name},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#designation_id').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });
    </script>
@endsection
