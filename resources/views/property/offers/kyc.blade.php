@extends('layouts.main')
@section('title', 'KYC')
@section('page-title', 'KYC')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <h4 class="mt-4 fw-semibold">KYC Verification</h4>
                                <p class="text-muted mt-3">When you get your KYC verification process done, you have given the crypto exchange in this case, information.</p>
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Click here for Verification
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5 mb-2">
                            <div class="col-sm-7 col-8">
                                <img src="/assets/images/verification-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title text-uppercase" id="exampleModalLabel">Verify your Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" class="checkout-tab">
                    <div class="modal-body p-0">
                        <div class="step-arrow-nav">
                            <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3 done" id="pills-bill-info-tab" data-bs-toggle="pill" data-bs-target="#pills-bill-info" type="button" role="tab" aria-controls="pills-bill-info" aria-selected="false" data-position="0" tabindex="-1">Personal Info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3 done" id="pills-bill-address-tab" data-bs-toggle="pill" data-bs-target="#pills-bill-address" type="button" role="tab" aria-controls="pills-bill-address" aria-selected="false" data-position="1" tabindex="-1">Bank Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3 done" id="pills-payment-tab" data-bs-toggle="pill" data-bs-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment" aria-selected="false" data-position="2" tabindex="-1">Document Verification</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3 active" id="pills-finish-tab" data-bs-toggle="pill" data-bs-target="#pills-finish" type="button" role="tab" aria-controls="pills-finish" aria-selected="true" data-position="3">Verified</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end modal-body-->
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane fade" id="pills-bill-info" role="tabpanel" aria-labelledby="pills-bill-info-tab">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="Enter your firstname">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" placeholder="Enter your lastname">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="phoneNumber" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="phoneNumber" placeholder="Enter your phone number">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="dateofBirth" class="form-label">Date of Birth</label>
                                            <input type="text" class="form-control flatpickr-input" id="dateofBirth" data-provider="flatpickr" placeholder="Enter your date of birth" readonly="readonly">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="emailID" class="form-label">Email ID</label>
                                            <input type="email" class="form-control" id="emailID" placeholder="Enter your email">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="Enter your password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirmPassword" placeholder="Enter your confirm password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="vatNo" class="form-label">VAT/TIN No.</label>
                                            <input type="text" class="form-control" id="vatNo" placeholder="Enter your VAT/TIN no">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="serviceTax" class="form-label">Registration No.</label>
                                            <input type="text" class="form-control" id="serviceTax" placeholder="Enter your service tax no">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div>
                                            <label for="country-select" class="form-label">Country</label>
                                            <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" data-choices="" name="country-select" id="country-select" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">Select country</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">Select country</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Select country" placeholder=""><div class="choices__list" role="listbox"><div id="choices--country-select-item-choice-10" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="10" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Select country</div><div id="choices--country-select-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Argentina" data-select-text="Press to select" data-choice-selectable="">Argentina</div><div id="choices--country-select-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Belgium" data-select-text="Press to select" data-choice-selectable="">Belgium</div><div id="choices--country-select-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Brazil" data-select-text="Press to select" data-choice-selectable="">Brazil</div><div id="choices--country-select-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Colombia" data-select-text="Press to select" data-choice-selectable="">Colombia</div><div id="choices--country-select-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="Denmark" data-select-text="Press to select" data-choice-selectable="">Denmark</div><div id="choices--country-select-item-choice-6" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="6" data-value="France" data-select-text="Press to select" data-choice-selectable="">France</div><div id="choices--country-select-item-choice-7" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="7" data-value="Germany" data-select-text="Press to select" data-choice-selectable="">Germany</div><div id="choices--country-select-item-choice-8" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="8" data-value="Mexico" data-select-text="Press to select" data-choice-selectable="">Mexico</div><div id="choices--country-select-item-choice-9" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="9" data-value="Russia" data-select-text="Press to select" data-choice-selectable="">Russia</div><div id="choices--country-select-item-choice-11" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="11" data-value="Spain" data-select-text="Press to select" data-choice-selectable="">Spain</div><div id="choices--country-select-item-choice-12" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="12" data-value="Syria" data-select-text="Press to select" data-choice-selectable="">Syria</div><div id="choices--country-select-item-choice-13" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="13" data-value="United Kingdom" data-select-text="Press to select" data-choice-selectable="">United Kingdom</div><div id="choices--country-select-item-choice-14" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="14" data-value="United States of America" data-select-text="Press to select" data-choice-selectable="">United States of America</div></div></div></div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="d-flex align-items-start gap-3 mt-3">
                                            <button type="button" class="btn btn-primary btn-label right ms-auto nexttab" data-nexttab="pills-bill-address-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i> Next Step</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="pills-bill-address" role="tabpanel" aria-labelledby="pills-bill-address-tab">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="banknameInput" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" id="banknameInput" placeholder="Enter your bank name">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="branchInput" class="form-label">Branch</label>
                                            <input type="text" class="form-control" id="branchInput" placeholder="Branch">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="accountnameInput" class="form-label">Account Holder Name</label>
                                            <input type="text" class="form-control" id="accountnameInput" placeholder="Enter account holder name">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="accountnumberInput" class="form-label">Account Number</label>
                                            <input type="number" class="form-control" id="accountnumberInput" placeholder="Enter account number">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ifscInput" class="form-label">Account Type</label>
                                            <input type="number" class="form-control" id="ifscInput" placeholder="Type">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab" data-previous="pills-bill-info-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to Personal Info</button>
                                            <button type="button" class="btn btn-primary btn-label right ms-auto nexttab" data-nexttab="pills-payment-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Next Step</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                                <h5 class="mb-3">Choose Document Type</h5>

                                <div class="d-flex gap-2">
                                    <div>
                                        <input type="radio" class="btn-check" id="passport" checked="" name="choose-document">
                                        <label class="btn btn-outline-info" for="passport">Passport</label>
                                    </div>
                                    <div>
                                        <input type="radio" class="btn-check" id="aadhar-card" name="choose-document">
                                        <label class="btn btn-outline-info" for="aadhar-card">Ghana Card</label>
                                    </div>
{{--                                    <div>--}}
{{--                                        <input type="radio" class="btn-check" id="pan-card" name="choose-document">--}}
{{--                                        <label class="btn btn-outline-info" for="pan-card">Pan Card</label>--}}
{{--                                    </div>--}}
                                    <div>
                                        <input type="radio" class="btn-check" id="other" name="choose-document">
                                        <label class="btn btn-outline-info" for="other">Other</label>
                                    </div>
                                </div>

                                <div class="dropzone d-flex align-items-center dz-clickable">

                                    <div class="dz-message needsclick text-center">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                        </div>

                                        <h4>Drop files here or click to upload.</h4>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-0" id="dropzone-preview">

                                </ul>
                                <!-- end dropzon-preview -->
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="pills-bill-address-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to Bank Details</button>
                                    <button type="button" class="btn btn-primary btn-label right ms-auto nexttab" data-nexttab="pills-finish-tab"><i class="ri-save-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade active show" id="pills-finish" role="tabpanel" aria-labelledby="pills-finish-tab">
                                <div class="row text-center justify-content-center py-4">
                                    <div class="col-lg-11">
                                        <div class="mb-4">
                                            <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>
                                        </div>
                                        <h5>Verification Completed</h5>
                                        <p class="text-muted mb-4"></p>

                                        <div class="hstack justify-content-center gap-2">
                                            <button type="button" class="btn btn-ghost-success material-shadow-none" data-bs-dismiss="modal">Done <i class="ri-thumb-up-fill align-bottom me-1"></i></button>
                                            <button type="button" class="btn btn-primary"><i class="ri-home-4-line align-bottom ms-1"></i> Back to Home</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!--end modal-body-->
                </form>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="/assets/js/pages/crypto-kyc.init.js"></script>
@endsection

