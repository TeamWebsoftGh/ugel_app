@extends('layouts.main')
@section('title', 'Manage Polling Stations')
@section('page-title', 'Polling Stations')
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
                                    <label>Regions</label>
                                    <select name="filter_region" id="filter_region" data-live-search="true" class="form-control selectpicker">
                                        <option selected value="">All Regions</option>
                                        @foreach($regions as $region)
                                            <option @if($region->id == request()->filter_region) selected="selected" @endif value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                    <label>Electoral Areas</label>
                                    <select name="filter_electoral_area" id="filter_electoral_area" data-live-search="true" class="form-control selectpicker">
                                        <option selected value="">All Electoral Areas</option>
                                        @foreach($electoral_areas as $electoral_area)
                                            <option @if($electoral_area->id == request()->filter_electoral_area) selected="selected" @endif value="{{ $electoral_area->id }}">{{ $electoral_area->name }}</option>
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
                @include("layouts.partials.dt-header", ["import" => 1])
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="polling_station-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Code')}}</th>
                            <th>{{__('Electoral Area')}}</th>
                            <th>{{__('Constituency')}}</th>
                            <th>{{__('Region')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Last Modified')}}</th>
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
        let baseUrl = '/electoral-areas/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'code', name: 'code'},
                        {data: 'electoral_area_name', name: 'electoral_area_name'},
                        {data: 'constituency_name', name: 'constituency_name'},
                        {data: 'region_name', name: 'region'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        { data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("polling_station", "{{ route('polling-stations.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
