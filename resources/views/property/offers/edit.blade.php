<form method="POST" id="asset" action="{{route('offers.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$offer->id}}">
    <input type="hidden" id="_name" name="me" value="{{$offer->asset_name}}">
    <div class="row clearfix">
        <div class="form-group col-6 col-md-4">
            <label for="username" class="control-label">Service Type <span class="text-danger">*</span></label>
            <select name="service_type_id"  id="service_type_id"
                    class="selectpicker form-control"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>trans('file.Employee')])}}...'>
                @foreach($service_types as $category)
                    <option value="{{$category->id}}" @selected(old('service_type_id', $offer->service_type_id) == $category->id)>{{$category->name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-assets_category_id"> </span>
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Offer Code')}} </label>
            <input type="text" name="offer_code" id="offer_code" readonly class="form-control"
                   placeholder="{{__('Offer Code')}}" value="{{old('offer_code', $offer->offer_code)}}">
            <span class="input-note text-danger" id="error-offer_code"> </span>
            @error('offer_code')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Amount')}} <span class="text-danger">*</span></label>
            <input type="text" name="amount" id="amount" required class="form-control"
                   placeholder="{{__('Amount')}}" value="{{old('amount', $offer->amount)}}">
            <span class="input-note text-danger" id="error-amount"> </span>
            @error('amount')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Status')}} <span class="text-danger">*</span></label>
            <select name="status" id="status" class="selectpicker form-control" data-live-search="true">
                <option value="submitted" @selected(old('status', $offer->status) == "submitted")>{{__('Submitted')}}</option>
                <option value="queued" @selected(old('status', $offer->status) == "queued")>{{__('Queued')}}</option>
                <option value="active" @selected(old('status', $offer->status) == "active")>{{__('Active')}}</option>
                <option value="completed" @selected(old('status', $offer->status) == "completed")>{{__("Completed")}}</option>
                <option value="cancelled" @selected(old('status', $offer->status) == "cancelled")>{{__("Cancelled")}}</option>
                <option value="declined" @selected(old('status', $offer->status) == "declined")>{{__("Declined")}}</option>
            </select>
        </div>

        <div class="form-group col-6 col-md-4">
            <label>{{__('Attachment')}} </label>
            <input type="file" name="attachment" id="attachment" class="form-control">
            <span class="input-note text-danger" id="error-attachment"> </span>
            @error('attachment')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            @if(isset($offer->attachment))
                <a target="_blank" href="{{ asset("uploads/$offer->attachment") }}">View Attachment</a>
            @endif
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $offer->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12">
            @if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
                <a href="{{route("payments.create")}}" class="btn btn-success save_dt_btn"><i class="fa fa-save"></i> Proceed to Pay</a>
            @endif
        </div>
    </div>
</form>
<script>
    $('#status').selectpicker('val', '{{$offer->status}}');
</script>
