@extends('layouts.main')

@section('title', 'Update SMS Settings')
@section('page-title', 'SMS Settings')
@section('styles')
    <style type="text/css">
        <?php
      if(old_set('sms_service', NULL, $rec) == 'yoovi') { ?>
   #otherSmsConfigInfo{
            display: none;
        }
        #mailgunInfo{
            display: block;
        }
        <?php } else { ?>
   #mailgunInfo{
            display: none;
        }
        #otherSmsConfigInfo{
            display: block;
        }
        <?php } ?>
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Update SMS Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.sms.store')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="mail" value="1">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="sms_service" class="control-label">SMS Service<span class="text-danger">*</span></label>
                                        <select class="form-control select2 sms_service" data-msg="Required" name="sms_service">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            @forelse($data['sms_sending_options'] as $key => $type)
                                                <option value="{{$key}}" @if(old_set('sms_service', "yoovi", $rec) == $key) selected="selected" @endif>{{$type}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('sms_service')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div id="yoovi">
                                    <br/>
                                    <h4>SMS REMAINING: {{settings("yoovi_sms_balance", 0)}}</h4>
                                    <h4>VOICE SMS REMAINING: {{settings("voice_main_balance", 0)}}</h4>
                                    <br/>
                                    <div class="row configuration">
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="company_email_mailgun_domain" class="control-label">Yoovi Sms url</label>
                                            <input type="text" name="yoovi_sms_url" class="form-control" value="{{ old_set('yoovi_sms_url', NULL, $rec) }}">
                                            @error('yoovi_sms_url')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="yoovi_sms_api_key" class="control-label">Yoovi Api Key</label>
                                            <input type="password" name="yoovi_sms_api_key" value="{{ old_set('yoovi_sms_api_key', NULL, $rec) }}" class="form-control">
                                            @error('yoovi_sms_api_key')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="yoovi_sms_send_id" class="control-label">Yoovi Sender Id</label>
                                            <input type="text" name="yoovi_sms_send_id" value="{{ old_set('yoovi_sms_send_id', NULL, $rec) }}" class="form-control">
                                            @error('yoovi_sms_send_id')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="npontu">
                                    <div class="row configuration">
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="company_email_mailgun_domain" class="control-label">Base Sms url</label>
                                            <input type="text" name="npontu_sms_url" class="form-control" value="{{ old_set('npontu_sms_url', NULL, $rec) }}">
                                            @error('npontu_sms_url')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="npontu_sms_username" class="control-label">Username</label>
                                            <input type="text" name="npontu_sms_username" value="{{ old_set('npontu_sms_username', NULL, $rec) }}" class="form-control">
                                            @error('npontu_sms_username')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="npontu_sms_password" class="control-label">Password</label>
                                            <input type="password" name="npontu_sms_password" value="{{ old_set('npontu_sms_password', NULL, $rec) }}" class="form-control">
                                            @error('npontu_sms_password')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="npontu_sms_source" class="control-label">Source</label>
                                            <input type="text" name="npontu_sms_source" value="{{ old_set('npontu_sms_source', NULL, $rec) }}" class="form-control">
                                            @error('npontu_sms_source')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="otherSmsConfigInfo">
                                    <div class="row">
                                        <div class="form-group col-sm-6 col-xl-3">
                                            <label for="sms_base_url" class="control-label">Url</label>
                                            <input type="text" name="sms_base_url" class="form-control" value="{{ old_set('sms_base_url', NULL, $rec) }}">
                                            @error('sms_base_url')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-3">
                                            <label for="sms_send_to_param_name" class="control-label">Send to parameter name:</label>
                                            <input type="text" name="sms_send_to_param_name" value="{{ old_set('sms_send_to_param_name', NULL, $rec) }}" class="form-control">
                                            @error('sms_send_to_param_name')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-3">
                                            <label for="sms_msg_param_name" class="control-label">Message parameter name:</label>
                                            <input type="text" name="sms_msg_param_name" value="{{ old_set('sms_msg_param_name', NULL, $rec) }}" class="form-control">
                                            @error('sms_msg_param_name')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-3">
                                            <label for="sms_request_method" class="control-label">Request Method:</label>
                                            <select class="form-control select2" data-msg="Required" name="sms_request_method">
                                                <option value="" disabled selected>Nothing Selected</option>
                                                <option value="get" @if( old_set('sms_request_method', NULL, $rec) == 'get') selected @endif>GET</option>
                                                <option value="post" @if( old_set('sms_request_method', NULL, $rec)== 'post') selected @endif>POST</option>
                                            </select>
                                            @error('sms_request_method')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr/>
                                    <h5>Headers</h5>
                                    <div class="row">
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_1" class="control-label">Header 1 Key</label>
                                            <input type="text" name="sms_header_1" class="form-control" value="{{ old_set('sms_header_1', NULL, $rec) }}">
                                            @error('sms_header_1')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_val_1" class="control-label">Header 1 Value</label>
                                            <input type="text" name="sms_header_val_1" class="form-control" value="{{ old_set('sms_header_val_1', NULL, $rec) }}">
                                            @error('sms_header_val_1')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_2" class="control-label">Header 2 Key</label>
                                            <input type="text" name="sms_header_2" class="form-control" value="{{ old_set('sms_header_2', NULL, $rec) }}">
                                            @error('sms_header_2')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_val_2" class="control-label">Header 2 Value</label>
                                            <input type="text" name="sms_header_val_2" class="form-control" value="{{ old_set('sms_header_val_2', NULL, $rec) }}">
                                            @error('sms_header_val_2')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_3" class="control-label">Header 3 Key</label>
                                            <input type="text" name="sms_header_2" class="form-control" value="{{ old_set('sms_header_3', NULL, $rec) }}">
                                            @error('sms_header_3')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_header_val_3" class="control-label">Header 3 Value</label>
                                            <input type="text" name="sms_header_val_3" class="form-control" value="{{ old_set('sms_header_val_3', NULL, $rec) }}">
                                            @error('sms_header_val_3')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr/>
                                    <h5>Parameters</h5>
                                    <div class="row">
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_1" class="control-label">Parameter 1 Key</label>
                                            <input type="text" name="sms_param_1" class="form-control" value="{{ old_set('sms_param_1', NULL, $rec) }}">
                                            @error('sms_param_1')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_val_1" class="control-label">Parameter 1 Value</label>
                                            <input type="text" name="sms_param_val_1" class="form-control" value="{{ old_set('sms_param_val_1', NULL, $rec) }}">
                                            @error('sms_param_val_1')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_2" class="control-label">Parameter 2 Key</label>
                                            <input type="text" name="sms_param_2" class="form-control" value="{{ old_set('sms_param_2', NULL, $rec) }}">
                                            @error('sms_param_2')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_val_2" class="control-label">Parameter 2 Value</label>
                                            <input type="text" name="sms_param_val_2" class="form-control" value="{{ old_set('sms_param_val_2', NULL, $rec) }}">
                                            @error('sms_param_val_2')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_3" class="control-label">Parameter 3 Key</label>
                                            <input type="text" name="sms_param_3" class="form-control" value="{{ old_set('sms_param_3', NULL, $rec) }}">
                                            @error('sms_param_3')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_val_3" class="control-label">Parameter 3 Value</label>
                                            <input type="text" name="sms_param_val_3" class="form-control" value="{{ old_set('sms_param_val_3', NULL, $rec) }}">
                                            @error('sms_param_val_3')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_4" class="control-label">Parameter 4 Key</label>
                                            <input type="text" name="sms_param_4" class="form-control" value="{{ old_set('sms_param_4', NULL, $rec) }}">
                                            @error('sms_param_4')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_val_4" class="control-label">Parameter 4 Value</label>
                                            <input type="text" name="sms_param_val_4" class="form-control" value="{{ old_set('sms_param_val_4', NULL, $rec) }}">
                                            @error('sms_param_val_4')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_5" class="control-label">Parameter 5 Key</label>
                                            <input type="text" name="sms_param_5" class="form-control" value="{{ old_set('sms_param_5', NULL, $rec) }}">
                                            @error('sms_param_5')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="sms_param_val_5" class="control-label">Parameter 5 Value</label>
                                            <input type="text" name="sms_param_val_5" class="form-control" value="{{ old_set('sms_param_val_5', NULL, $rec) }}">
                                            @error('sms_param_val_5')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Test SMS Configuration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.sms.store')}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">
                            <label for="sms_test_number" class="control-label">Enter a phone number to test the sms configuration</label>
                            <input type="tel" name="sms_test_number" class="form-control" value="{{ old('sms_test_number') }}">
                            @error('sms_test_number')
                            <span class="input-note text-danger">{{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $('.sms_service').change(function(){

                if($(this).val() == 'yoovi')
                {
                    $('#yoovi').show();
                    $("#npontu").hide();
                    $("#twilo").hide();
                    $("#otherSmsConfigInfo").hide();
                }
                else if($(this).val() == 'npontu')
                {
                    $('#yoovi').hide();
                    $("#npontu").show();
                    $("#twilo").hide();
                    $("#otherSmsConfigInfo").hide();
                }
                else if($(this).val() == 'twilo')
                {
                    $('#yoovi').hide();
                    $("#npontu").hide();
                    $("#twilo").show();
                    $("#otherSmsConfigInfo").hide();
                }
                else
                {
                    $('#yoovi').hide();
                    $("#npontu").hide();
                    $("#twilo").hide();
                    $("#otherSmsConfigInfo").show();
                }
            });

            $('.sms_service').trigger("change");
        });
    </script>
@endsection
