@extends('layouts.main')
@section('title', 'Manage Tasks')
@section('page-title', 'Tasks')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('tasks.index')}}">Tasks</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Task Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("tasks.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true
            }); // flatpickr
            {{--let date = $('.date');--}}
            {{--date.datepicker({--}}
            {{--    format: '{{ env('Date_Format_JS')}}',--}}
            {{--    autoclose: true,--}}
            {{--    todayHighlight: true--}}
            {{--});--}}
        });
    </script>
@endsection
