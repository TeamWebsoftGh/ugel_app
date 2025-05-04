@extends('layouts.main')
@section('title', ' Property Details')
@section('page-title', 'Properties')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('properties.index')}}">Properties</a></li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gx-lg-5">
                    <div class="col-xl-4 col-md-8 mx-auto">
                        <div class="product-img-slider sticky-side-div">
                            <div class="swiper product-thumbnail-slider p-2 rounded bg-light swiper-initialized swiper-horizontal swiper-backface-hidden">
                                <div class="swiper-wrapper" id="swiper-wrapper-aaa8d8cef3aa11bd" aria-live="polite">
                                    <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 4" style="width: 490px; margin-right: 24px;">
                                        <img src="{{asset($property->cover_image)}}" alt="" class="img-fluid d-block">
                                    </div>
                                    @forelse($property->attachments as $image)
                                        <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 4" style="width: 490px; margin-right: 24px;">
                                            <img src="{{asset($image->file_path)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="swiper-button-next material-shadow" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-aaa8d8cef3aa11bd" aria-disabled="false"></div>
                                <div class="swiper-button-prev material-shadow swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-aaa8d8cef3aa11bd" aria-disabled="true"></div>
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                            <!-- end swiper thumbnail slide -->
                            <div class="swiper product-nav-slider mt-2 swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-backface-hidden swiper-thumbs">
                                <div class="swiper-wrapper" id="swiper-wrapper-d8775edd37333526" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                                    <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible" role="group" aria-label="4 / 4" style="width: 119px; margin-right: 10px;">
                                        <div class="nav-slide-item">
                                            <img src="{{asset($property->cover_image)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    </div>
                                    @forelse($property->attachments as $image)
                                        <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible swiper-slide-active swiper-slide-thumb-active" role="group" aria-label="1 / 4" style="width: 119px; margin-right: 10px;">
                                            <div class="nav-slide-item">
                                                <img src="{{asset($image->file_path)}}" alt="" class="img-fluid d-block">
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                            <!-- end swiper nav slide -->
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-8">
                        <div class="mt-xl-0 mt-5">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h4>{{$property->property_name}}</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block"><span class="text-muted">Property Type: </span>{{$property->propertyType->name}}</a></div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Property Category: <span class="text-body fw-medium">{{$property->propertyType->propertyCategory->name}}</span></div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Date Added: <span class="text-body fw-medium">{{$property->created_at}}</span></div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div>
                                        <a href="{{route("properties.edit", $property->id)}}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="ri-pencil-fill align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-money-dollar-circle-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">Start Price :</p>
                                                <h5 class="mb-0">{{format_money($property->propertyUnits->min('rent_amount') ?? 0)}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-4 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-file-copy-2-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">No. of Bookings:</p>
                                                <h5 class="mb-0">{{number_format(count($property->bookings))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-4 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-stack-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">Total Units :</p>
                                                <h5 class="mb-0">{{number_format(count($property->propertyUnits))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-muted">
                                <h5 class="fs-14">Description :</h5>
                                {!! $property->description !!}
                            </div>
                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">Property Details:</h5>
                                <nav>
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab" href="#nav-speci" role="tab" aria-controls="nav-speci" aria-selected="true">Specification</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab" href="#nav-detail" role="tab" aria-controls="nav-detail" aria-selected="false" tabindex="-1">Property Units</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="nav-review-tab" data-bs-toggle="tab" href="#nav-review" role="tab" aria-controls="nav-review" aria-selected="false" tabindex="-1">Ratings &amp; Reviews</a>
                                        </li>
                                    </ul>
                                </nav>
                                <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-speci" role="tabpanel" aria-labelledby="nav-speci-tab">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr>
                                                    <th scope="row" style="width: 200px;">Property Code</th>
                                                    <td>{{$property->property_code}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style="width: 200px;">Property Name</th>
                                                    <td>{{$property->property_name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Type</th>
                                                    <td>{{$property->propertyType->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style="width: 200px;">Category</th>
                                                    <td>{{$property->propertyType->propertyCategory->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Digital Address</th>
                                                    <td>{{$property->digital_address??"N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Physical Address</th>
                                                    <td>{{$property->address}}<br/> {{$property->city->name}}<br/> {{$property->city->region?->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Status</th>
                                                    <td>{{$property->status}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                                        <div>
                                            <table id="property_unit-table" class="table dt-responsive" style="box-shadow: none; width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Unit Name</th>
                                                    <th>Property Name</th>
                                                    <th>Property Type</th>
                                                    <th>Rent Type</th>
                                                    <th>Rent Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">
                                        <div class="row gy-4 gx-0">
                                            <div class="col-lg-4">
                                                <div>
                                                    <div class="pb-3">
                                                        <div class="bg-light px-3 py-2 rounded-2 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1">
                                                                    @php
                                                                        $rating = $property->rating; // or pass as variable
                                                                         $fullStars = floor($rating);
                                                                         $halfStar = ($rating - $fullStars) >= 0.5;
                                                                         $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                                                    @endphp

                                                                    <div class="fs-16 align-middle text-warning">
                                                                        @for ($i = 0; $i < $fullStars; $i++)
                                                                            <i class="ri-star-fill"></i>
                                                                        @endfor

                                                                        @if ($halfStar)
                                                                            <i class="ri-star-half-fill"></i>
                                                                        @endif

                                                                        @for ($i = 0; $i < $emptyStars; $i++)
                                                                            <i class="ri-star-line"></i>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <h6 class="mb-0">{{number_format($rating, 1)}} out of 5</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="text-muted">Total <span class="fw-medium">{{count($property->reviews)}}</span> reviews
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        @php
                                                            $reviews = $property->reviews;
                                                            $totalReviews = $reviews->count();

                                                            $ratings = [
                                                                5 => $reviews->where('rating', 5)->count(),
                                                                4 => $reviews->where('rating', 4)->count(),
                                                                3 => $reviews->where('rating', 3)->count(),
                                                                2 => $reviews->where('rating', 2)->count(),
                                                                1 => $reviews->where('rating', 1)->count(),
                                                            ];

                                                            $percent = fn($count) => $totalReviews ? round(($count / $totalReviews) * 100, 2) : 0;
                                                        @endphp
                                                        @foreach($ratings as $star => $count)
                                                            <div class="row align-items-center g-2">
                                                                <div class="col-auto">
                                                                    <div class="p-2">
                                                                        <h6 class="mb-0">{{ $star }} star</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="p-2">
                                                                        <div class="progress animated-progress progress-sm">
                                                                            <div class="progress-bar {{ $star >= 4 ? 'bg-success' : ($star == 3 ? 'bg-warning' : 'bg-danger') }}"
                                                                                 role="progressbar"
                                                                                 style="width: {{ $percent($count) }}%"
                                                                                 aria-valuenow="{{ $percent($count) }}"
                                                                                 aria-valuemin="0"
                                                                                 aria-valuemax="100">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <div class="p-2">
                                                                        <h6 class="mb-0 text-muted">{{ $count }}</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->

                                            <div class="col-lg-8">
                                                <div class="ps-lg-4">
                                                    <div class="d-flex flex-wrap align-items-start gap-3">
                                                        <h5 class="fs-14">Reviews: </h5>
                                                    </div>

                                                    <div class="me-lg-n3 pe-lg-4" data-simplebar style="max-height: 225px;">
                                                        <ul class="list-unstyled mb-0">
                                                            @forelse($property->reviews as $review)
                                                                <li class="py-2">
                                                                    <div class="border border-dashed rounded p-3">
                                                                        <div class="d-flex align-items-start mb-3">
                                                                            <div class="hstack gap-3">
                                                                                <div class="badge rounded-pill bg-success mb-0">
                                                                                    <i class="mdi mdi-star"></i> {{$review->rating}}
                                                                                </div>
                                                                                <div class="vr"></div>
                                                                                <div class="flex-grow-1">
                                                                                    <p class="text-muted mb-0"> {{$review->comment}}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="d-flex align-items-end">
                                                                            <div class="flex-grow-1">
                                                                                <h5 class="fs-14 mb-0">{{$review->client->fullname}}</h5>
                                                                            </div>
                                                                            <div class="flex-shrink-0">
                                                                                <p class="text-muted fs-13 mb-0">{{$review->created_at}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @empty
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
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
                        {data: 'unit_name', name: 'unit_name'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'property_type_name', name: 'property_type_name'},
                        {data: 'rent_type', name: 'rent_type'},
                        {data: 'formatted_amount', name: 'formatted_amount'},
                    ];
                loadDataAndInitializeDataTable("property_unit", "{{ route('property-units.index', ['filter_property' => $property->id]) }}", cols);
            });
        })(jQuery);
    </script>
@endsection
