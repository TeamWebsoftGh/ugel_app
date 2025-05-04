@extends('layouts.main')
@section('title', ' Property Unit Details')
@section('page-title', 'Property Unit')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('property-units.index')}}">Property Units</a></li>
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
                                        <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                    </div>
                                    <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 4" style="width: 490px; margin-right: 24px;">
                                        <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                    </div>
                                    <div class="swiper-slide" role="group" aria-label="3 / 4" style="width: 490px; margin-right: 24px;">
                                        <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                    </div>
                                    <div class="swiper-slide" role="group" aria-label="4 / 4" style="width: 490px; margin-right: 24px;">
                                        <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                    </div>
                                </div>
                                <div class="swiper-button-next material-shadow" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-aaa8d8cef3aa11bd" aria-disabled="false"></div>
                                <div class="swiper-button-prev material-shadow swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-aaa8d8cef3aa11bd" aria-disabled="true"></div>
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                            <!-- end swiper thumbnail slide -->
                            <div class="swiper product-nav-slider mt-2 swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-backface-hidden swiper-thumbs">
                                <div class="swiper-wrapper" id="swiper-wrapper-d8775edd37333526" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                                    <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible swiper-slide-active swiper-slide-thumb-active" role="group" aria-label="1 / 4" style="width: 119px; margin-right: 10px;">
                                        <div class="nav-slide-item">
                                            <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible swiper-slide-next" role="group" aria-label="2 / 4" style="width: 119px; margin-right: 10px;">
                                        <div class="nav-slide-item">
                                            <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible" role="group" aria-label="3 / 4" style="width: 119px; margin-right: 10px;">
                                        <div class="nav-slide-item">
                                            <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible" role="group" aria-label="4 / 4" style="width: 119px; margin-right: 10px;">
                                        <div class="nav-slide-item">
                                            <img src="{{asset($property_unit->cover_image)}}" alt="" class="img-fluid d-block">
                                        </div>
                                    </div>
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
                                    <h4>{{$property_unit->unit_name}}</h4>
                                    <h5>{{$property_unit->property->property_name}}</h5>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block"><span class="text-muted">Property Type: </span>{{$property_unit->property->propertyType->name}}</a></div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Property Type : <span class="text-body fw-medium">{{$property_unit->property->propertyType->propertyCategory->name}}</span></div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Published : <span class="text-body fw-medium">{{$property_unit->created_at}}</span></div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div>
                                        <a href="{{route("properties.edit", $property_unit->id)}}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="ri-pencil-fill align-bottom"></i></a>
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
                                                <h5 class="mb-0">{{format_money($property_unit->rent_amount ?? 0)}}</h5>
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
                                                <p class="text-muted mb-1">No. of Bookings :</p>
                                                <h5 class="mb-0">{{number_format(count($property_unit->bookings))}}</h5>
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
                                                <p class="text-muted mb-1">Total Rooms :</p>
                                                <h5 class="mb-0">{{number_format(count($property_unit->rooms))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">Product Description :</h5>
                                <nav>
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab" href="#nav-speci" role="tab" aria-controls="nav-speci" aria-selected="true">Specification</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab" href="#nav-detail" role="tab" aria-controls="nav-detail" aria-selected="false" tabindex="-1">Details</a>
                                        </li>
                                    </ul>
                                </nav>
                                <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-speci" role="tabpanel" aria-labelledby="nav-speci-tab">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr>
                                                    <th scope="row" style="width: 200px;">Category</th>
                                                    <td>{{$property_unit->property->propertyType->propertyCategory->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Type</th>
                                                    <td>{{$property_unit->property->propertyType->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Address</th>
                                                    <td>{{$property_unit->property->address}}<br/> {{$property_unit->property->city->name}}<br/> {{$property_unit->property->city->region?->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Country</th>
                                                    <td>{{$property_unit->property->city?->region?->country?->name??"N/A"}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                                        <div>
                                            {{$property_unit->description}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- product-content -->
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
