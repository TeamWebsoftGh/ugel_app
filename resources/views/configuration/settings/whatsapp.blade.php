@extends('layouts.main')

@section('title', 'Update WhatsApp Settings')
@section('page-title', 'WhatsApp Settings')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Update WhatsApp Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.whatsapp.store')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="whatsapp" value="1">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="whatsapp_base_url" class="control-label"> WhatsApp url</label>
                                            <input type="text" name="whatsapp_base_url" class="form-control" value="{{ old_set('whatsapp_base_url', NULL, $rec) }}">
                                            @error('whatsapp_base_url')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="whatsapp_access_token" class="control-label">Access Token</label>
                                            <input type="text" name="whatsapp_access_token" value="{{ old_set('whatsapp_access_token', NULL, $rec) }}" class="form-control">
                                            @error('whatsapp_access_token')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="whatsapp_version" class="control-label">Version</label>
                                            <input type="text" name="whatsapp_version" value="{{ old_set('whatsapp_version', NULL, $rec) }}" class="form-control">
                                            @error('whatsapp_version')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="whatsapp_phone_number_id" class="control-label">Phone Number Id</label>
                                            <input type="text" name="whatsapp_phone_number_id" value="{{ old_set('whatsapp_phone_number_id', NULL, $rec) }}" class="form-control">
                                            @error('whatsapp_phone_number_id')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 col-xl-4">
                                            <label for="whatsapp_templates" class="control-label">Templates</label>
                                            <input type="text" name="whatsapp_templates" value="{{ old_set('whatsapp_templates', NULL, $rec) }}" class="form-control">
                                            @error('whatsapp_templates')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <i>Enter template names separated by comma</i>
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
                    <h4>Test WhatsApp Configuration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('configuration.settings.whatsapp.store')}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">
                            <label for="whatsapp_test_number" class="control-label">Enter a phone number to test the whatsapp configuration</label>
                            <input type="tel" name="whatsapp_test_number" class="form-control" value="{{ old('whatsapp_test_number') }}">
                            @error('whatsapp_test_number')
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

    </script>
@endsection
