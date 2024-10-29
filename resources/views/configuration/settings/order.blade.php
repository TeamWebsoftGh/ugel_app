@extends('layouts.admin.main')

@section('title', 'Update Other Settings')
@section('page-title', 'Other Settings')

@section('content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h5>Other Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.configurations.settings.site.store')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="order" value="1">
                        <div class="row clearfix">
                            <div class="col-md-8 col-lg-9">
                                <div class="row">
                                    <div class="form-group col-sm-6 col-xl-3 col-md-4">
                                        <label for="enforce_due_date" class="control-label">Enforce Task Due Date</label>
                                        <select class="form-control select2" required data-msg="Required" name="enforce_due_date">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            <option value="1" @if(old_set('enforce_due_date', NULL, $rec) == 1) selected="selected" @endif>Yes</option>
                                            <option value="0" @if(old_set('enforce_due_date', NULL, $rec) == 0) selected="selected" @endif>No</option>
                                        </select>
                                        <span class="input-note text-danger" id="error-enforce_due_date"> </span>
                                        @error('enforce_due_date')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-3 col-md-4">
                                        <label for="enable_sms_notification" class="control-label">Enable Sms Notification</label>
                                        <select class="form-control select2" required data-msg="Required" name="enable_sms_notification">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            <option value="1" @if(old_set('enable_sms_notification', NULL, $rec) == 1) selected="selected" @endif>Yes</option>
                                            <option value="0" @if(old_set('enable_sms_notification', NULL, $rec) == 0) selected="selected" @endif>No</option>
                                        </select>
                                        <span class="input-note text-danger" id="error-enable_sms_notification"> </span>
                                        @error('enable_sms_notification')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-3 col-md-4">
                                        <label for="task_number_prefix" class="control-label">Payment Number Prefix <span class="text-danger">*</span></label>
                                        <input type="text" name="task_number_prefix" class="form-control" value="{{ old_set('task_number_prefix', NULL, $rec) }}">
                                        @error('task_number_prefix')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-3 col-md-4">
                                        <label for="enable_browsing_work" class="control-label">Enable Wallet Option</label>
                                        <select class="form-control select2" required data-msg="Required" name="show_wallet_option">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            <option value="1" @if(old_set('show_wallet_option', NULL, $rec) == '1') selected="selected" @endif>Yes</option>
                                            <option value="0" @if(old_set('show_wallet_option', NULL, $rec) == '0') selected="selected" @endif>No</option>
                                        </select>
                                        @error('enable_task_reopen')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

@endsection
