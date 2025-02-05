@extends('layouts.main')
@section('title', 'Manage Parliamentary Candidates')
@section('page-title', 'Parliamentary Candidates')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    @can("read-regions")
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title">
                            Filter
                        </h4>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" id="filter_form" method="GET">
                            <div class="row mt-3">
                                <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                                    <label>Constituency</label>
                                    <select name="filter_constituency" id="filter_constituency" data-live-search="true" class="form-control selectpicker">
                                        <option selected value="">All Constituencies</option>
                                        @foreach($constituencies as $constituency)
                                            <option @if($constituency->id == request()->filter_constituency) selected="selected" @endif value="{{ $constituency->id }}">{{ $constituency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                                    <label>Political Party</label>
                                    <select name="filter_political_party" id="filter_political_party" data-live-search="true" class="form-control selectpicker">
                                        <option selected value="">All Political Parties</option>
                                        @foreach($political_parties as $pp)
                                            <option @if($pp->id == request()->filter_political_party) selected="selected" @endif value="{{ $pp->id }}">{{ $pp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-lg-1 col-xl-1 pl-md-3 mt-4">
                                    <button type="button" name="btn" id="filterSubmit" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0 filter_submit">Go</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="parliamentary_candidate-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Election')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Political Party')}}</th>
                            <th>{{__('Constituency')}}</th>
                            <th>{{__('Status')}}</th>
                            <th class="not-exported">{{__('Action')}}</th>
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
        let baseUrl = '/delegates/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'election_name', name: 'election_name'},
                        {data: 'fullname', name: 'fullname'},
                        {data: 'political_party_name', name: 'political_party_name'},
                        {data: 'constituency_name', name: 'constituency_name'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("parliamentary_candidate", "{{ route('parliamentary-candidates.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
