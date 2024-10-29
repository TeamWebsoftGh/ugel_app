@extends('layouts.main')

@section('title', 'Update Mail Settings')
@section('page-title', 'Mail Settings')
@section('styles')
    <style type="text/css">
        <?php
      if(old_set('company_email_send_using', NULL, $rec) == 'mailgun') { ?>
   #otherMailConfigInfo{
            display: none;
        }
        #mailgunInfo{
            display: block;
        }
        <?php } else { ?>
   #mailgunInfo{
            display: none;
        }
        #otherMailConfigInfo{
            display: block;
        }
        <?php } ?>
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Update Mail Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.mail.store')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="mail" value="1">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-sm-6 col-xl-5">
                                        <label for="company_email_send_using" class="control-label">Send email using <span class="text-danger">*</span></label>
                                        <select class="form-control select2 email_sending_options" data-msg="Required" name="company_email_send_using">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            @forelse($data['email_sending_options'] as $key => $type)
                                                <option value="{{$key}}" @if(old_set('company_email_send_using', NULL, $rec) == $key) selected="selected" @endif>{{$type}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('company_email_send_using')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-5 configuration">
                                        <label for="queue_connection" class="control-label">Queue Connection <span class="text-danger">*</span></label>
                                        <select class="form-control select2" data-msg="Required" name="queue_connection">
                                            <option value="" disabled selected>Nothing Selected</option>
                                            @forelse($data['queue_connection_options'] as $key => $type)
                                                <option value="{{$key}}" @if(old_set('queue_connection', NULL, $rec) == $key) selected="selected" @endif>{{$type}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('queue_connection')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-10 configuration">
                                        <label for="company_email_from_address" class="control-label">Email From Address</label>
                                        <input type="email" name="company_email_from_address" class="form-control" value="{{ old_set('company_email_from_address', NULL, $rec) }}">
                                        @error('company_email_from_address')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div id="mailgunInfo">
                                    <div class="row configuration">
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="company_email_mailgun_domain" class="control-label">Mailgun Domain</label>
                                            <input type="text" name="company_email_mailgun_domain" class="form-control" value="{{ old_set('company_email_mailgun_domain', NULL, $rec) }}">
                                            @error('company_email_mailgun_domain')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="company_email_mailgun_key" class="control-label"> Mailgun Key</label>
                                            <input type="text" name="company_email_mailgun_key" value="{{ old_set('company_email_mailgun_key', NULL, $rec) }}" class="form-control">
                                            @error('company_email_mailgun_key')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="otherMailConfigInfo">
                                    <div class="row configuration">
                                        <div class="form-group col-sm-6 col-xl-5">
                                            <label for="company_email_smtp_host" class="control-label">Smtp Host</label>
                                            <input type="text" name="company_email_smtp_host" class="form-control" value="{{ old_set('company_email_smtp_host', NULL, $rec) }}">
                                            @error('company_email_smtp_host')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-3 col-xl-2">
                                            <label for="company_email_smtp_port" class="control-label">SMTP Port</label>
                                            <input type="text" name="company_email_smtp_port" value="{{ old_set('company_email_smtp_port', NULL, $rec) }}" class="form-control">
                                            @error('company_email_smtp_port')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-3 col-xl-3">
                                            <label for="company_email_encryption" class="control-label">Email Encryption </label>
                                            <select class="form-control select2" data-msg="Required" name="company_email_encryption">
                                                <option value="" disabled selected>Nothing Selected</option>
                                                <option value="ssl" @if( old_set('company_email_encryption', NULL, $rec) == 'ssl') selected @endif>SSL</option>
                                                <option value="tls" @if( old_set('company_email_encryption', NULL, $rec)== 'tls') selected @endif>TLS</option>
                                            </select>
                                            @error('company_email_encryption')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                   <div class="row configuration">
                                       <div class="form-group col-sm-6 col-xl-5">
                                           <label for="company_email_smtp_username" class="control-label">SMTP Username</label>
                                           <input type="text" name="company_email_smtp_username" class="form-control" value="{{ old_set('company_email_smtp_username', NULL, $rec) }}">
                                           @error('company_email_smtp_username')
                                           <span class="input-note text-danger">{{ $message }} </span>
                                           @enderror
                                       </div>
                                       <div class="form-group col-sm-6 col-xl-5">
                                           <label for="company_email_smtp_password" class="control-label"> SMTP Password</label>
                                           <input type="text" name="company_email_smtp_password" value="{{ old_set('company_email_smtp_password', NULL, $rec) }}" class="form-control">
                                           @error('company_email_smtp_password')
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Test Email Configuration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.mail.store')}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">
                            <label for="test_email_address" class="control-label">Enter an email address to test the email configuration</label>
                            <input type="email" name="test_email_address" class="form-control" value="{{ old('test_email_address') }}">
                            @error('test_email_address')
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
            $('.email_sending_options').change(function(){

                if($(this).val() == 'mailgun')
                {
                    $('.configuration').show();
                    $("#mailgunInfo").show();
                    $("#otherMailConfigInfo").hide();
                }
                else if($(this).val() == 'log')
                {
                    $('.configuration').hide();
                }
                else
                {
                    $("#mailgunInfo").hide();
                    $("#otherMailConfigInfo").show();
                    $('.configuration').show();
                }
            });

            $('.email_sending_options').trigger("change");
        });
    </script>
@endsection
