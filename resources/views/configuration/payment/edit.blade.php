<form method="POST" action="{{route('admin.configurations.payment-gateways.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" name="offline" value="1">
    <input type="hidden" id="_id" name="id" value="{{$paymentGateway->id}}">
    <input type="hidden" id="_name" name="me" value="{{$paymentGateway->name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-lg-12">
            <div class="row">
                <div class="form-group col-sm-6 col-xl-4">
                    <label for="app_name" class="control-label"> Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old_set('name', NULL, $paymentGateway) }}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="express_amount" class="control-label">Status</label>
                    <select class="form-control select2" data-msg="Required" name="status">
                        <option value="1" @if(old_set('status', NULL, $paymentGateway) == 1) selected @endif>Enabled</option>
                        <option value="0" @if(old_set('status', NULL, $paymentGateway) == 0) selected @endif>Disabled</option>
                    </select>
                    <span class="input-note text-danger" id="error-status"> </span>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="mode" class="control-label">Mode</label>
                    <select class="form-control select2" data-msg="Required" name="mode">
                        <option value="online" @if(old_set('mode', NULL, $paymentGateway) == 'online') selected @endif>Online</option>
                        <option value="offline" @if(old_set('mode', NULL, $paymentGateway) == 'offline') selected @endif>Offline</option>
                    </select>
                    <span class="input-note text-danger" id="error-mode"> </span>
                    @error('mode')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="description" class="control-label">Description</label>
                    <textarea class="form-control" rows="4" name="description">{{ old_set('description', NULL, $paymentGateway) }}</textarea>
                    <span class="input-note text-danger" id="error-description"> </span>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-6">
                    <label for="instruction" class="control-label">Instruction</label>
                    <textarea class="form-control" rows="4" name="instruction">{{ old_set('instruction', NULL, $paymentGateway) }}</textarea>
                    <span class="input-note text-danger" id="error-instruction"> </span>
                    @error('instruction')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-6 col-xl-4">
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="requires_transaction_number"
                               {{ old_set('requires_transaction_number', NULL, $paymentGateway->settings) ? 'checked="checked"' : '' }}
                         name="requires_transaction_number" value="1">
                        <label class="custom-control-label" for="requires_transaction_number">Requires Evidence / Transaction
                            Number</label>
                    </div>
                    <span class="input-note text-danger" id="error-requires_transaction_number"> </span>
                    @error('requires_transaction_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-8 reference_field_label">
                    <label for="reference_field_label" class="control-label">Field name to display for entering transaction number</label>
                    <input type="text" name="reference_field_label" class="form-control" value="{{ old_set('reference_field_label', NULL, $paymentGateway->settings) }}">
                    <span class="input-note text-danger" id="error-reference_field_label"> </span>
                    @error('reference_field_label')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-6 col-xl-4">
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="requires_uploading_attachment"
                               {{ old_set('requires_uploading_attachment', NULL, $paymentGateway->settings) ? 'checked="checked"' : '' }}
                               name="requires_uploading_attachment" value="1">
                        <label class="custom-control-label" for="requires_uploading_attachment">Requires Uploading Attachment</label>
                    </div>
                    <span class="input-note text-danger" id="error-requires_uploading_attachment"> </span>
                    @error('requires_uploading_attachment')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-sm-12 col-xl-8 attachment_field_label">
                    <label for="attachment_field_label" class="control-label">Field name to display for attachment uploading</label>
                    <input type="text" name="attachment_field_label" class="form-control" value="{{ old_set('attachment_field_label', NULL, $paymentGateway->settings) }}">
                    <span class="input-note text-danger" id="error-attachment_field_label"> </span>
                    @error('attachment_field_label')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function () {

        $('#requires_transaction_number').on('click', function () {

            if (this.checked) {
                $('.reference_field_label').show();
            } else {
                $('.reference_field_label').hide();
            }

        });

        $('#requires_uploading_attachment').on('click', function () {

            if (this.checked) {
                $('.attachment_field_label').show();
            } else {
                $('.attachment_field_label').hide();
            }
        });
    });
</script>
