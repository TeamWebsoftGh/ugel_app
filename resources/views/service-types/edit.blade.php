<form method="POST" action="{{route('service-types.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$service_type->id}}">
    <input type="hidden" id="_name" name="me" value="{{$service_type->title}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <div class="form-group col-12 col-md-12">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{old('title', $service_type->name)}}" class="form-control">
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-name"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="start_date" class="control-label">Min Amount <span class="text-danger">*</span></label>
                    <input type="number" id="min_amount" name="min_amount" value="{{old('min_amount', $service_type->min_amount)}}" class="form-control">
                    @error('min_amount')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-min_amount"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="max_amount" class="control-label">Max Amount <span class="text-danger">*</span></label>
                    <input type="number" id="max_amount" name="max_amount" value="{{old('end_date', $service_type->max_amount)}}" class="form-control">
                    @error('max_amount')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-max_amount"> </span>
                </div>

                <div class="form-group col-12 col-md-4">
                    <label for="category" class="control-label">Category <span class="text-danger">*</span></label>
                    <input type="text" id="category" name="category" value="{{old('category', $service_type->category)}}" class="form-control">
                    @error('category')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-category"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Provider <span class="text-danger">*</span></label>
                    <select name="provider" id="provider" class="form-control selectpicker">
                        <option value="mtn" @if($service_type->provider == 'mtn') selected="selected" @endif>MTN</option>
                        <option value="others" @if($service_type->provider == 'others') selected="selected" @endif>Others</option>
                    </select>
                    <span class="input-note text-danger" id="error-provider"> </span>
                    @error('provider')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($service_type->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($service_type->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    <label for="description" class="control-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control ckeditor-classic" rows="9" name="description" id="description">{!! $service_type->description  !!}</textarea>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-description"> </span>
                </div>
            </div>
            <div class="form-group">
                @if(user()->can('create-'.get_permission_name().'|update-'.get_permission_name()))
                    <button type="submit" class="btn btn-success save_btn"><i class="mdi mdi-content-save fa-save"></i> Save</button>
                @endif
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        const fp = flatpickr(".date", {
            dateFormat: '{{ env('Date_Format')}}',
            autoclose: true,
            todayHighlight: true
        }); // flatpickr
    });
    $('#status').selectpicker('val', '{{$service_type->is_active}}');
</script>
