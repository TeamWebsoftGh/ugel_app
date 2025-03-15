@extends('layouts.main')
@section('title', 'Create Property')
@section('page-title', 'Create Property')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Property Setup</h4>
                </div>
                <div class="card-body">
                    <div class="text-center pt-3 pb-4 mb-1 d-flex justify-content-center">
                        <img src="{{ asset(settings('logo')) }}" class="card-logo card-logo-dark" alt="logo dark" height="60">
                        <img src="{{ asset(settings('logo')) }}" class="card-logo card-logo-light" alt="logo light" height="60">
                    </div>

                    <div class="step-arrow-nav mb-4">
                        <ul class="nav nav-pills custom-nav nav-justified" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#steparrow-gen-info">Property Information</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#steparrow-unit-info">Unit</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#steparrow-rent-charges">Rent & Charges</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-experience">Summary</button></li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="steparrow-gen-info">
                            @include("property.properties.property-info")
                        </div>

                        <div class="tab-pane fade" id="steparrow-unit-info">
                            <form action="#" class="form-steps">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload Image</label>
                                    <input class="form-control" type="file" id="formFile">
                                </div>
                                <div>
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" placeholder="Enter Description" id="description" rows="3" required></textarea>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="steparrow-rent-charges">
                            <form action="#" class="form-steps">
                                <p>Rent & Charges form content goes here...</p>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-experience">
                            <form action="#" class="form-steps">
                                <div class="text-center">
                                    <h5>Well Done!</h5>
                                    <p class="text-muted">You have Successfully Signed Up</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            function saveFormData(formId, callback) {
                let formData = $('#' + formId).serialize(); // Get form data

                $.ajax({
                    url: $('#' + formId).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('span.text-danger').html('');
                        Swal.fire({
                            icon: response.status,
                            title: '',
                            text: response.message,
                        });
                        if (response.status === 'success') {
                            callback(); // Move to next step if save is successful
                        } else {
                            console.error("Save failed:", response.message);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $('span.text-danger').html('');
                        for (control in XMLHttpRequest.responseJSON.errors) {
                            $('#error-' + control).html(XMLHttpRequest.responseJSON.errors[control]);
                        }
                        HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    },
                });
            }

            $('.save-next').on('click', function() {
                let formId = $(this).data('form'); // Get form ID from button attribute
                let nextTab = $(this).data('nexttab');

                saveFormData(formId, () => {
                    $('[data-bs-target="#' + nextTab + '"]').click(); // Move to next tab after saving
                });
            });

            $('.skip-step').on('click', function() {
                let nextTab = $(this).data('nexttab');
                $('[data-bs-target="#' + nextTab + '"]').click(); // Skip without saving
            });

            function updateDropdown(url, targetDropdown, defaultOption = 'Select an option') {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.status_code === '000') {
                            let dropdown = $('#' + targetDropdown);
                            dropdown.empty();
                            dropdown.append(`<option value="">${defaultOption}</option>`);
                            $.each(response.data, function(index, item) {
                                dropdown.append(`<option value="${item.id}">${item.name}</option>`);
                            });
                            dropdown.selectpicker('refresh');
                        } else {
                            dropdown.empty();
                            console.error("Error fetching data:", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        dropdown.empty();
                        console.error("Error fetching data:", error);
                    }
                });
            }

            $('#property_category_id').change(function() {
                let categoryId = $(this).val();
                if (categoryId) {
                    updateDropdown(`/api/clients/common/property-types?filter_property_category=${categoryId}`, 'property_type_id', 'Select Property Type');
                }
            });

            $('#country_id').change(function() {
                let countryId = $(this).val();
                if (countryId) {
                    updateDropdown(`/api/clients/common/regions?filter_country=${countryId}`, 'region_id', 'Select Region');
                }
            });

            $('#region_id').change(function() {
                let regionId = $(this).val();
                if (regionId) {
                    updateDropdown(`/api/clients/common/cities?filter_region=${regionId}`, 'city_id', 'Select City');
                }
            });

            if ($('#property_category_id').val()) {
                updateDropdown(`/api/clients/common/property-types?filter_property_category=${$('#property_category_id').val()}`, 'property_type_id');
            }

            if ($('#country_id').val()) {
                updateDropdown(`/api/clients/common/regions?filter_country=${$('#country_id').val()}`, 'region_id');
            }

            if ($('#region_id').val()) {
                updateDropdown(`/api/clients/common/cities?filter_region=${$('#region_id').val()}`, 'city_id');
            }
        });
    </script>
@endsection
