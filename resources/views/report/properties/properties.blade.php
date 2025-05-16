@extends('layouts.main')

@section('title', 'Properties Report')
@section('page-title', 'Properties Report')

@section('content')
    <div class="row">
        <div class="col-12">
            @include("report.properties.partials.filter")
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Properties Report</h4>
                    <div class="flex-shrink-0">
                        <form action="" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="report_type" id="report-type">
                            <input type="hidden" name="filter_status" id="status__report" value="{{request()->get('filter_status') ?: ''}}">
                            <input type="hidden" name="filter_property_type" id="property__report" value="{{request()->get('filter_property_type') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="start_date__report" value="{{request()->get('filter_start_date') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="end_date__report" value="{{request()->get('filter_end_date') ?: ''}}">
                            @includeIf('shared.export-buttons', ['class'=>'btn-sm'])
                        </form>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @include("report.properties.partials.properties-report")
                </div> <!-- .card-body-->
            </div>
        </div>
    </div>
@endsection
