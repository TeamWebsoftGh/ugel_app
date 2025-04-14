@extends('layouts.main')
@section('title', 'Property List')
@section('page-title', 'Properties')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                   <div class="row">
                       @forelse($properties as $prop)
                           <div class="col-md-6">
                               <div class="card">
                                   <div class="row g-0">
                                       <div class="col-md-5">
                                           <img style="max-height: 150px!important;" class="rounded-start img-fluid object-fit-cover" src="{{asset($prop->cover_image)}}" alt="{{$prop->property_code}}">
                                       </div>
                                       <div class="col-md-7">
                                           <div class="card-header">
                                               <h5 class="card-title mb-0">{{$prop->property_name}}</h5>
                                           </div>
                                           <div class="card-body">
                                               <p class="sub-title">{{$prop->propertyType->name??"N/A"}} | {{$prop->propertyType->propertyCategory->name??"N/A"}}</p>
                                               <p class="card-text mb-2">{!! \Illuminate\Support\Str::limit($prop->description, 150, '...') !!}</p>
                                           </div>
                                           <div class="card-footer">
                                               <a href="{{route("properties.show", $prop->id)}}" class="link-success float-end">View Details <i class="ri-arrow-right-s-line align-middle ms-1 lh-1"></i></a>
                                               <p class="text-muted mb-0">1 days Ago</p>
                                           </div>
                                       </div>
                                   </div>
                               </div><!-- end card -->
                           </div>
                       @empty
                           <div class="text-center">
                               <img class="" src="/img/empty-search.jpg" alt="Order loading">
                               <h5 class="title-font mb-3" >Your property list is currently empty.</h5>
                               <a href="{{route('properties.index')}}" class="btn btn-secondary">Add New</a>
                           </div>
                       @endforelse
                   </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/property/' ;
    </script>
    @include("layouts.shared.dt-scripts")
@endsection
