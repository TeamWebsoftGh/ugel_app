@extends('layouts.admin.main')
@section('title', 'Order Details')
@section('page-title', 'Orders')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('tasks.orders.index')}}">Orders</a></li>
@endsection

@section('content')
    @include('layouts.admin.includes.error')
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Order Details
                        <div class="float-right">
                            <div class="btn-group pull-right" role="group">
                                <button id="btnGroupVerticalDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Action <i class="fa fa-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <a class="dropdown-item text-danger" onclick="DeleteItem('{{$order->service->name}}', '{{route('tasks.orders.destroy', $order->id)}}')" href="#"><i class="fa fa-trash"></i> Delete</a>
                                    @if($order->status_id == \App\Helpers\OrderStatus::NEW)
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" onclick="ChangeOrderStatus('{{$order->service->name}}', '1', '{{route('tasks.orders.change-status', $order->id)}}')" href="#"><i class="fa fa-check-circle"></i> Confirm Order</a>
                                        <a class="dropdown-item" onclick="ChangeOrderStatus('{{$order->service->name}}', '0', '{{route('tasks.orders.change-status', $order->id)}}')" href="#"><i class="fa fa-times-circle"></i> Cancel Order</a>
                                    @elseif($order->status_id == \App\Helpers\OrderStatus::IN_PROGRESS)
                                        <a class="dropdown-item" onclick="ChangeOrderStatus('{{$order->service->name}}', '2', '{{route('tasks.orders.change-status', $order->id)}}')" href="#"><i class="fa fa-check-circle"></i>Mark as complete</a>
                                    @endif
                                    @if($order->status_id == \App\Helpers\OrderStatus::NEW || $order->status_id == \App\Helpers\OrderStatus::IN_PROGRESS)
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#assign-writer"><i class="fa fa-user-circle"></i> Assign/Change Writer</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </h4>
                    <p class="card-subtitle mb-4"></p>
                    <div class="row" id="order-content">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <td>Title</td>
                                    <td><b>{{$order->title}}</b></td>
                                </tr>
                                <tr>
                                    <td>Service Type</td>
                                    <td><b>{{$order->service->name}}</b></td>
                                </tr>
                                <tr>
                                    <td>Academic Level</td>
                                    <td><b>{{$order->academicLevel->name??"N/A"}}</b></td>
                                </tr>
                                <tr>
                                    <td>Discipline</td>
                                    <td><b>{{$order->paperType->name}}</b></td>
                                </tr>
                                <tr>
                                    <td>Deadline</td>
                                    <td><b>{{format_date($order->deadline)}}</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <td>Customer Username</td>
                                    <td><b>{{$order->customer->username}}</b></td>
                                </tr>
                                <tr>
                                    <td>Customer Email</td>
                                    <td><b>{{$order->customer->email}}</b></td>
                                </tr>
                                <tr>
                                    <td>Number of Pages</td>
                                    <td><b>{{$order->pages}}</b></td>
                                </tr>
                                <tr>
                                    <td>Citation Style</td>
                                    <td><b>{{$order->reference_style}}</b></td>
                                </tr>
                                <tr>
                                    <td>Number of Sources</td>
                                    <td><b>{{$order->number_of_sources??"N/A"}}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h4 class="card-title"><b>Additional Information</b></h4>
                    {!! $order->description??"N/A" !!}
                    <br/>
                    <br/>
                    <h4 class="card-title"><b>Uploaded Documents</b></h4>
                    @forelse($order->customerDocuments as $doc)
                        <h5><a href="{{asset("storage/$doc->src")}}"> View Document </a> - {{format_date($doc->created_at)}}</h5>
                    @empty
                        No Uploads
                    @endforelse
                </div> <!-- end card-body-->
            </div> <!-- end card-->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Progress</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div class="row" id="order-content">
                        <div class="col-md-8">
                            <table class="table">
                                <tr>
                                    <td>Writer Name</td>
                                    <td><b>{{$order->writer->fullname??"N/A"}}</b></td>
                                </tr>
                                <tr>
                                    <td>Writer Email</td>
                                    <td><b>{{$order->writer->email??"N/A"}}</b></td>
                                </tr>
                                <tr>
                                    <td>Order Status</td>
                                    <td><span class="badge text-light" style="background-color: {{$order->orderStatus->color}}">{{$order->orderStatus->name}}</span></td>
                                </tr>
                                <tr>
                                    <td>Writer's Remark</td>
                                    <td><b>{{$order->writer_remarks??"N/A"}}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h4 class="card-title">Final Work</h4>
                    @if(isset($order->final_document))
                        <h5><a href="{{asset("storage/$order->final_document")}}"> Download Final Work </a> - {{format_date($order->date_completed)}}</h5>
                    @else
                        N/A
                    @endif
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
        <div class="col-sm-4">
            @include("portal.partials.cost-summary")
        </div>
    </div>
    <div class="modal fade" id="assign-writer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Assign writer
                </div>
                <div class="modal-body">
                    <p>Assign a writer for this order.</p>
                    <form id="formValidate" method="POST" action="{{ route('tasks.orders.update', $order->id) }}" aria-label="{{ __('Assign Writer') }}">
                        @csrf
                        @method("PUT")
                        <div class="form-group row">
                            <label for="current-password" class="col-md-3 col-form-label text-md-right">{{ __('Select Writer') }}</label>
                            <div class="col-md-8">
                                <select class="form-control ignore {{ $errors->has('email') ? ' is-invalid' : '' }}" required name="writer_id">
                                    <option selected disabled> Nothing Selected</option>
                                    @forelse($writers as $writer)
                                        <option value="{{$writer->id}}" @if($writer->id == $order->writer_id) selected @endif>{{$writer->fullname}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="help-block form-text with-errors form-control-feedback" id="error_writer_id">{{ $errors->first('current-password')?:'' }}</div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Assign') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end row-->
@endsection
@section('js')
    @include("layouts.admin.shared.datatable")
    <script>
        let baseUrl = '/tasks/writers/';
        function ChangeOrderStatus(name, status, url)
        {
            let ActiveOrInactive="";
            let selIcon = "";
            let order = "";

            if(status == 1){
                ActiveOrInactive = "confirm"; selIcon="<i class='fa fa-ban'></i>";
                order = '{{\App\Helpers\OrderStatus::IN_PROGRESS}}'
            }else if(status ==2){
                ActiveOrInactive = "mark as complete"; selIcon="<i class='fa fa-check-circle'></i>";
                order = '{{\App\Helpers\OrderStatus::COMPLETED}}'
            }
            else{
                ActiveOrInactive = "reject"; selIcon="<i class='fa fa-caret-square-o-right'></i>";
                order = '{{\App\Helpers\OrderStatus::DECLINED}}'
            }

            if(url)
            {
                bootbox.confirm("<h4>"+ActiveOrInactive.toUpperCase()+"</h4><hr/><div> This action will "+ ActiveOrInactive + " order request for " + name.toUpperCase() + "</div> Are you sure you want to <b><span style='color:blue'>"+ ActiveOrInactive +" order for </span> " + name.toUpperCase() + "</b></div>", function (result) {
                    if (result === true) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: ({_token:token,status:status}),
                            timeout:60000,
                            datatype: "json",
                            cache: false,
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                            },
                            success: function (data) {
                                bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                                    window.location.reload();
                                });
                            },
                        });
                    }
                    else {

                    }
                });
            }else{
                alert("No item selected.")
            }
        }
    </script>
@endsection
