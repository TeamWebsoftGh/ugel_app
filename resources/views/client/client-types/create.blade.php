@extends('layouts.main')
@section('title', 'Manage Customers')
@section('page-title', 'Customers')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('admin.customers.index')}}">Customers</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("client.clients.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // Function to toggle business-specific fields based on selected client type
            function toggleBusinessFields() {
                var selectedOption = $('#client_type_id option:selected'); // Get the selected option
                var clientTypeId = selectedOption.val(); // Get the selected client type id
                var category = selectedOption.data('category'); // Get the category from the selected option

                // Now you can check the category and display the business fields
                if (category == 'business') {
                    $('#businessFields').show(); // Show business fields
                } else {
                    $('#businessFields').hide(); // Hide business fields
                }
            }

            // Trigger the toggle on page load
            toggleBusinessFields();

            // Handle change in client type selection
            $('#client_type_id').change(function() {
                toggleBusinessFields(); // Toggle business fields when client type changes
            });
        });

    </script>
@endsection
