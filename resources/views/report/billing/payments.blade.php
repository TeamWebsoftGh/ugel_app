@extends('layouts.main')

@section('title', 'Payment Report')
@section('page-title', 'Payment Report')

@section('content')
    <div class="row">
        <div class="col-12">
            @include("report.billing.partials.filter")
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Payment Report</h4>
                    <div class="flex-shrink-0">
                        <form action="" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="report_type" id="report-type">
                            <input type="hidden" name="filter_status" id="status__report" value="{{request()->get('filter_status') ?: ''}}">
                            <input type="hidden" name="filter_property" id="property__report" value="{{request()->get('filter_property') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="start_date__report" value="{{request()->get('filter_start_date') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="end_date__report" value="{{request()->get('filter_end_date') ?: ''}}">
                            <input type="hidden" name="filter_client" id="client__report" value="{{request()->get('filter_client') ?: ''}}">
                            @includeIf('shared.export-buttons', ['class'=>'btn-sm'])
                        </form>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @include("report.billing.partials.payments-report")
                </div> <!-- .card-body-->
            </div>
        </div>
    </div>
@endsection
