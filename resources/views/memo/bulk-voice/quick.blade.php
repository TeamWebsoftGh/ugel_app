@extends('layouts.main')
@section('title', 'Send Quick Voice')
@section('page-title', 'Quick Voice')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('bulk-voice.index')}}">Bulk Voice</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Quick Voice Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        <form method="POST" action="{{route('bulk-voice.quick')}}" enctype="multipart/form-data">
                            <p>All fields with <span class="text-danger">*</span> are required.</p>
                            @csrf
                            <input type="hidden" id="_id" name="id" value="{{$sms->id}}">
                            <input type="hidden" id="_name" name="me" value="{{$sms->title}}">
                            <div class="row clearfix">
                                <div class="col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="name" class="control-label">Select Contact Group </label>
                                            <select name="contact_group_id" id="contact_group_id" class="form-control selectpicker" data-live-search="true">
                                                <option selected value="">Nothing Selected</option>
                                                @forelse($contact_groups as $contact_group)
                                                    <option value="{{ $contact_group->id }}" {{ old('contact_group_id', $sms->contact_group_id) == $contact_group->id ? 'selected' : '' }}>{{ $contact_group->name }}</option>
                                                @empty
                                                    <option>No groups available</option>
                                                @endforelse
                                            </select>
                                            @error('contact_group_id')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-contact_group_id"> </span>
                                        </div>
                                        <div class="form-group col-12 col-md-12">
                                            <label for="name" class="control-label">Recipient(s) </label>
                                            <input type="text" id="recipient" name="recipient" value="{{old('recipient', $sms->recipient)}}" class="form-control">
                                            @error('recipient')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-recipient"> </span>
                                            <p class="text-info">Add recipients separated by comma(,) Eg 0200000000,0240000000</p>
                                        </div>
                                        <div class="form-group col-12">
                                            <label for="file" class="control-label">Voice Note <span class="text-danger">*</span></label>
                                            <input type="file" id="file" name="file" class="form-control">
                                            @error('file')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-file"> </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if(user()->can('create-'.get_permission_name().'|update-'.get_permission_name()))
                                            <button type="submit" class="btn btn-success save_btn"><i class="mdi mdi-content-save fa-save"></i> Send</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
