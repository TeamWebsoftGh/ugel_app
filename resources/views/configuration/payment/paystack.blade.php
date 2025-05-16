<form method="POST" action="{{route('configuration.payment-gateways.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$rec->id}}">
    <input type="hidden" id="_name" name="me" value="{{$rec->name}}">
    <input type="hidden" name="paystack" value="1">
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="row">
                <div class="form-group col-6 col-xl-4">
                    <label for="app_name" class="control-label"> Display Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $rec->name) }}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="mode" class="control-label">Mode</label>
                    <select class="form-control select2" data-msg="Required" name="mode">
                        <option value="online" @if(old_set('mode', NULL, $rec) == 'online') selected @endif>Online</option>
                        <option value="offline" @if(old_set('mode', NULL, $rec) == 'offline') selected @endif>Offline</option>
                    </select>
                    <span class="input-note text-danger" id="error-mode"> </span>
                    @error('mode')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="express_amount" class="control-label">Status</label>
                    <select class="form-control select2" data-msg="Required" name="is_active">
                        <option value="1" @if(old('is_active', $rec->is_active) == 1) selected @endif>Enabled</option>
                        <option value="0" @if(old('is_active', $rec->is_active) == 0) selected @endif>Disabled</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="public_key" class="control-label"> Public Key <span class="text-danger">*</span></label>
                    <input type="text" id="public_key" name="public_key" value="{{ old_set('public_key', NULL, $rec->settings) }}" class="form-control">
                    <span class="input-note text-danger" id="error-public_key"> </span>
                    @error('public_key')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="secret_key" class="control-label"> Secrete Key <span class="text-danger">*</span></label>
                    <input type="text" id="secret_key" name="secret_key" value="{{ old_set('secret_key', NULL, $rec->settings) }}" class="form-control">
                    <span class="input-note text-danger" id="error-secret_key"> </span>
                    @error('secret_key')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="base_url" class="control-label"> Base Url <span class="text-danger">*</span></label>
                    <input type="text" id="base_url" name="base_url" value="{{ old_set('base_url', NULL, $rec->settings) }}" class="form-control">
                    <span class="input-note text-danger" id="error-base_url"> </span>
                    @error('base_url')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="merchant_email" class="control-label"> Merchant Email <span class="text-danger">*</span></label>
                    <input type="text" id="merchant_email" name="merchant_email" value="{{ old_set('merchant_email', NULL, $rec->settings) }}" class="form-control">
                    <span class="input-note text-danger" id="error-merchant_email"> </span>
                    @error('merchant_email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
