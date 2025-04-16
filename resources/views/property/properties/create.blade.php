@extends('layouts.main')
@section('title', 'Property')
@section('page-title', 'Properties')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    @include("property.partials.filter", ["type" => true, 'unit' => true])
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    @include("property.properties.edit")
                </div>
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/property/awards/' ;
    </script>
    @include("layouts.shared.dt-scripts")
@endsection
