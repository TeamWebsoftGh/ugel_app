<form method="POST" action="{{route('maintenance-requests.store')}}" enctype="multipart/form-data" novalidate>
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$maintenance->id}}">
    <input type="hidden" id="_name" name="me" value="{{$maintenance->reference}}">
    <div class="row">
        @if($maintenance->reference)
            <div class="form-group col-12 col-md-4">
                <label for="subject" class="control-label">Reference Number </label>
                <input type="text" readonly value="{{$maintenance->reference}}" class="form-control">
            </div>
        @endif
            <div class="form-group col-12 col-md-4">
                <label for="maintenance_category" class="control-label">Category <span class="text-danger">*</span></label>
                <select name="maintenance_category_id" id="maintenance_category" required class="form-control selectpicker">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('maintenance_category_id')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
                <span class="input-note text-danger" id="error-maintenance_category_id"> </span>
            </div>
        <div class="form-group col-12 col-md-4">
            <label for="priority_id" class="control-label">Priority <span class="text-danger">*</span></label>
            <select name="priority_id" id="priority" required class="form-control selectpicker">
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                @endforeach
            </select>
            @error('priority_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            <span class="input-note text-danger" id="error-priority_id"> </span>
        </div>
        <div class="form-group col-12 col-md-4">
            <label for="name" class="control-label">Customer <span class="text-danger">*</span></label>
            <select name="customer_id" id="customer_id" data-live-search="true" class="form-control selectpicker">
                <option selected value=""></option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @if($maintenance->customer_id == $customer->id) selected @endif>{{ $customer->fullname }}</option>
                @endforeach
            </select>
            @error('customer_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            <span class="input-note text-danger" id="error-customer_id"> </span>
        </div>
        @if(user()->can('create-support-tickets|update-support-tickets'))
            <div class="form-group col-12 col-md-4">
                <label for="exampleFormControlTextarea1" class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option @selected("opened" == $maintenance->status) value="opened">Opened</option>
                    <option @selected("closed" == $maintenance->status) value="closed">Closed</option>
                    <option @selected("cancelled" == $maintenance->status) value="cancelled">Cancelled</option>
                    <option @selected("reopened" == $maintenance->status) value="cancelled">Reopened</option>
                </select>
                <span class="input-note text-danger" id="error-status"> </span>
                @error('status')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div><!--end col-->
        @endif
        <div class="form-group col-12 col-md-4">
            <label for="exampleFormControlTextarea1" class="form-label">Upload Attachment</label>
            <input name="ticket_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
            <span class="input-note text-danger" id="error-ticket_files"> </span>
            @error('ticket_files')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div><!--end col-->
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Note</label>
            <textarea class="form-control" rows="3" name="ticket_note" id="ticket_note">{!! old('ticket_note', $maintenance->ticket_note)  !!}</textarea>
            @error('ticket_note')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            <span class="input-note text-danger" id="error-description"> </span>
        </div>
        <div class="form-group col-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control summernote" rows="6" name="description" id="description">{!! old('description', $maintenance->description)  !!}</textarea>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            <span class="input-note text-danger" id="error-description"> </span>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Save</button>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#priority').selectpicker('val', '{{old("priority_id", $maintenance->priority_id)}}');
        $('#maintenance_category').selectpicker('val', '{{old("maintenance_category_id", $maintenance->maintenance_category_id)}}');
    });
</script>
