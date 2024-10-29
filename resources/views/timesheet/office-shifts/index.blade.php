@extends('layouts.main')
@section('title', 'Office Shift')
@section('page-title', 'Office Shift')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="office_shift-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                            <th width="100">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/property/awards/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'shift_name', name: 'shift_name',},
                        {data: 'monday', name: 'monday'},
                        {data: 'tuesday', name: 'tuesday'},
                        {data: 'wednesday', name: 'wednesday'},
                        {data: 'thursday', name: 'thursday'},
                        {data: 'friday', name: 'friday'},
                        {data: 'saturday', name: 'saturday'},
                        {data: 'sunday', name: 'sunday'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("office_shift", "{{ route('timesheet.office-shifts.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
