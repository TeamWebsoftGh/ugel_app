<form method="POST" action="{{route('portal.configurations.payment-gateways.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" name="stripe" value="1">
    <input type="hidden" id="_id" name="id" value="{{$rec->id}}">
    <input type="hidden" id="_name" name="me" value="{{$rec->name}}">
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
                    <select class="form-control select2" data-msg="Required" name="status">
                        <option value="1" @if(old('status', $rec->status) == 1) selected @endif>Enabled</option>
                        <option value="0" @if(old('status', $rec->status) == 0) selected @endif>Disabled</option>
                    </select>
                    <span class="input-note text-danger" id="error-status"> </span>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="publishable_key" class="control-label"> Publishable Key <span class="text-danger">*</span></label>
                    <input type="text" id="publishable_key" name="publishable_key" value="{{ old('public_key', $rec->publishable_key) }}" class="form-control">
                    <span class="input-note text-danger" id="error-publishable_key"> </span>
                    @error('publishable_key')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="secret_key" class="control-label"> Secrete Key <span class="text-danger">*</span></label>
                    <input type="text" id="secret_key" name="secret_key" value="{{ old('secret_key', $rec->secret_key) }}" class="form-control">
                    <span class="input-note text-danger" id="error-secret_key"> </span>
                    @error('secret_key')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="base_url" class="control-label"> Base Url <span class="text-danger">*</span></label>
                    <input type="text" id="base_url" name="base_url" value="{{ old('base_url', $rec->base_url) }}" class="form-control">
                    <span class="input-note text-danger" id="error-base_url"> </span>
                    @error('base_url')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-12">
                    @include("shared.controls")
                </div>
            </div>
        </div>
    </div>
</form>
