<form method="POST" action="{{route('events.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$event->id}}">
    <input type="hidden" id="_name" name="me" value="{{$event->title}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <div class="form-group col-12 col-md-12">
                    <label for="name" class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{old('title', $event->title)}}" class="form-control">
                    @error('title')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-title"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="start_date" class="control-label">Start Date <span class="text-danger">*</span></label>
                    <input type="text" id="start_date" name="start_date" value="{{old('start_date', $event->start_date)}}" class="form-control date">
                    @error('start_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-start_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="end_date" class="control-label">End Date <span class="text-danger">*</span></label>
                    <input type="text" id="end_date" name="end_date" value="{{old('end_date', $event->end_date)}}" class="form-control date">
                    @error('end_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-end_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Subsidiary </label>
                    <select name="subsidiary_id" id="subsidiary_id" data-live-search="true" class="form-control selectpicker">
                        <option selected value="">All</option>
                        @foreach($subsidiaries as $subsidiary)
                            <option @if($event->subsidiary_id == $subsidiary->id) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                        @endforeach
                    </select>
                    @error('subsidiary_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-subsidiary_id"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Department </label>
                    <select name="department_id" id="department_id" data-live-search="true" class="form-control selectpicker">
                        <option selected value="">All</option>
                        @foreach($departments as $department)
                            <option @if($event->departemnt_id == $department->id) selected="selected" @endif value="{{ $department->id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-department_id"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="status" class="form-control selectpicker">
                        <option value="0" @if($event->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($event->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    <label for="description" class="control-label">Description</label>
                    <textarea class="form-control ckeditor-classic" rows="6" name="description" id="description">{!! $event->description  !!}</textarea>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-description"> </span>
                </div>
                @include('shared.form.attachment')
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
</script>
