@extends('layouts.main')
@section('title', 'Create Property')
@section('page-title', 'Create Property')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Property Setup</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="#" class="form-steps" autocomplete="off">
                        <div class="text-center pt-3 pb-4 mb-1 d-flex justify-content-center">
                            <img src="{{asset(settings("logo"))}}" class="card-logo card-logo-dark" alt="logo dark" height="60">
                            <img src="{{asset(settings("logo"))}}}" class="card-logo card-logo-light" alt="logo light" height="60">
                        </div>
                        <div class="step-arrow-nav mb-4">
                            <ul class="nav nav-pills custom-nav nav-justified" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="property-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-gen-info" type="button" role="tab" data-position="0" tabindex="-1">Property Information</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-description-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-description-info" type="button" role="tab" data-position="1" tabindex="-1">Unit</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-description-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-description-info" type="button" role="tab" data-position="2" tabindex="-1">Rent & Charges</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-experience-tab" data-bs-toggle="pill" data-bs-target="#pills-experience" type="button" role="tab" data-position="3">Summary</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="property-info" role="tabpanel">
                                <div>
                                   @include("property.properties.property-info")
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="steparrow-description-info-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to more info</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="steparrow-description-info" role="tabpanel" aria-labelledby="steparrow-description-info-tab">
                                <div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload Image</label>
                                        <input class="form-control" type="file" id="formFile">
                                    </div>
                                    <div>
                                        <label class="form-label" for="des-info-description-input">Description</label>
                                        <textarea class="form-control" placeholder="Enter Description" id="des-info-description-input" rows="3" required=""></textarea>
                                        <div class="invalid-feedback">Please enter a description</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="steparrow-gen-info-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to General</button>
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="pills-experience-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="pills-experience" role="tabpanel" aria-labelledby="pills-experience-tab">
                                <div class="text-center">

                                    <div class="avatar-md mt-5 mb-4 mx-auto">
                                        <div class="avatar-title bg-light text-success display-4 rounded-circle">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </div>
                                    </div>
                                    <h5>Well Done !</h5>
                                    <p class="text-muted">You have Successfully Signed Up</p>
                                </div>
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </form>
                </div>
                <!-- end card body -->
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/property/properties/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        $(document).ready(function() {
            // Function to update property types based on selected category
            function updatePropertyTypes(categoryId) {
                $.ajax({
                    url: '/api/clients/common/property-types?filter_property_category=' + categoryId,
                    type: 'GET',
                    success: function(response) {
                        // Check if the status is "000" indicating success
                        if (response.status_code === '000') {
                            var propertyTypeSelect = $('#property_type_id');
                            propertyTypeSelect.find('option').remove(); // Clear existing options

                            // Add a default "Select" option
                            //propertyTypeSelect.append('<option value="">Select Property Type</option>');

                            // Populate the property types from the response data
                            $.each(response.data, function(index, type) {
                                propertyTypeSelect.append('<option value="' + type.id + '">' + type.name + '</option>');
                            });
                            // Refresh the selectpicker to apply the new options
                            propertyTypeSelect.selectpicker('refresh');
                        } else {
                            // Handle error if status_code is not "000"
                            console.error("Error fetching property types:", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX request error
                        console.error("Error fetching property types:", error);
                    }
                });
            }

            // Handle category change event
            $('#property_category_id').change(function() {
                var selectedCategoryId = $(this).val();
                if (selectedCategoryId) {
                    updatePropertyTypes(selectedCategoryId); // Update property types based on selected category
                } else {
                    $('#property_type_id').find('option').remove(); // Clear property types if no category is selected
                    $('#property_type_id').selectpicker('refresh'); // Refresh selectpicker
                }
            });

            // Trigger update when the page loads, in case a category is already selected
            if ($('#property_category_id').val()) {
                updatePropertyTypes($('#property_category_id').val());
            }
        });
    </script>

@endsection
