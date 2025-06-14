<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
@include("layouts.partials.head")

<body>

<!-- Begin page -->
<div id="layout-wrapper">

   @include("layouts.partials.header")
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark Logo-->
            <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset(settings("logo"))}}" alt="" height="22">
                    </span>
                <span class="logo-lg">
                        <img src="{{asset(settings("logo"))}}" alt="" height="17">
                    </span>
            </a>
            <!-- Light Logo-->
            <a href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset(settings("logo"))}}" alt="" height="40">
                    </span>
                <span class="logo-lg">
                        <img src="{{asset(settings("logo"))}}" alt="" height="60">
                    </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                @include("layouts.partials.sidebar")
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                @include('layouts.partials.page-header')
                @includeIf('layouts.partials.messages')
                @yield("content")
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @include("layouts.partials.footer")

    </div>
    <!-- end main content-->
       <div class="container" id="loading" style="display:none;z-index: 9999 !important; top: 40%; left: 50%; width: 200px; position: fixed;">
           <div class="row">
               <div class="please_wait pull-right"
                    style="background-color:#fff;font-size:15px;position:absolute;z-index:9999 !important;
                                         border:2px solid #cecece;padding:5px 20px;border-radius:5px;">
                   <i class="fa fa-spin fa fa-gear"></i> Please wait...
               </div>
           </div>
       </div>

</div>
@if(user()->ask_password_reset &&  url()->current() != url('account/change-password'))
    <div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Change Password
                </div>
                <div class="modal-body">
                    <p>Your Password has expired or you are logged in for the first time.</p>
                    @include('layouts.partials.messages')
                    @include('account.password')
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Default Modals -->
<div id="FormModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="FormModalLabel">Heading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body" id="modal_form_content">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="input-note text-danger" id="form_result"> </span>
                <div class="modal-form-body">

                </div>
            </div>
        </div>
    </div>
</div>

<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

@include("layouts.partials.scripts")
@include('layouts.popup.popup-structure')
@include('layouts.popup.popup-jspart')
@yield("js")
@yield("scripts")
@stack('scripts')
</body>
</html>
