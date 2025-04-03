@extends('layouts.main')

@section('title', 'Loan Repayment Report')
@section('page-title', 'Loan Repayment Report')

@section('content')
    <div class="row">
        <div class="col-12">
            @include("admin.report.partials.filter")
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Loan Repayment Report</h4>
                    <div class="flex-shrink-0">
                        <form action="" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="report_type" id="report-type">
                            <input type="hidden" name="filter_status" id="status__report" value="{{request()->get('filter_status') ?: ''}}">
                            <input type="hidden" name="branch_id" id="branch_id__report" value="{{request()->get('branch_id') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="start_date__report" value="{{request()->get('filter_start_date') ?: ''}}">
                            <input type="hidden" name="filter_start_date" id="end_date__report" value="{{request()->get('filter_end_date') ?: ''}}">
                            <input type="hidden" name="filter_subsidiary" id="subsidiary__report" value="{{request()->get('filter_subsidiary') ?: ''}}">
                            <input type="hidden" name="filter_department" id="department__report" value="{{request()->get('filter_department') ?: ''}}">
                            <input type="hidden" name="filter_client" id="filter_client__repot" value="{{request()->get('filter_client') ?: ''}}">
                            @includeIf('shared.export-buttons', ['class'=>'btn-sm'])
                        </form>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @include("admin.report.partials.loan-repayments-report")
                </div> <!-- .card-body-->
            </div>
        </div>
    </div>
@endsection
