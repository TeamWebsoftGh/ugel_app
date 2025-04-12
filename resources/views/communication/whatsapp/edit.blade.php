<form method="POST" action="{{route('whatsapp.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$announcement->id}}">
    <input type="hidden" id="_name" name="me" value="{{$announcement->title}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <div class="form-group col-12 col-md-12">
                    <label for="name" class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{old('title', $announcement->title)}}" class="form-control">
                    @error('title')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-title"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="start_date" class="control-label">Start Date <span class="text-danger">*</span></label>
                    <input type="text" id="start_date" name="start_date" value="{{old('start_date', $announcement->start_date)}}" class="form-control date">
                    @error('start_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-start_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="end_date" class="control-label">End Date <span class="text-danger">*</span></label>
                    <input type="text" id="end_date" name="end_date" value="{{old('end_date', $announcement->end_date)}}" class="form-control date">
                    @error('end_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-end_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Gender </label>
                    <select name="gender" id="gender" data-live-search="true" class="form-control selectpicker constituency">
                        <option selected value="">All</option>
                        @foreach(\App\Constants\Constants::GENDER as $gender)
                            <option @if($announcement->gender == $gender) selected="selected" @endif value="{{ $gender }}">{{ $gender }}</option>
                        @endforeach
                    </select>
                    @error('gender')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-gender"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Constituency </label>
                    <select name="constituency_id" id="constituency_id" data-live-search="true" class="form-control selectpicker constituency">
                        <option selected value="">All</option>
                        @foreach($constituencies as $constituency)
                            <option @if($announcement->constituency_id == $constituency->id) selected="selected" @endif value="{{ $constituency->id }}">{{ $constituency->name }}</option>
                        @endforeach
                    </select>
                    @error('constituency_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-constituency_id"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Electoral Area </label>
                    <select name="electoral_area_id" id="electoral_area_id" data-live-search="true" class="form-control selectpicker electoral_area">
                        <option selected value="">All</option>
                        @foreach($electoral_areas as $department)
                            <option @if($announcement->electoral_area_id == $department->id) selected="selected" @endif value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('electoral_area_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-electoral_area_id"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Polling Station </label>
                    <select name="polling_station_id" id="polling_station_id" data-live-search="true" class="form-control selectpicker polling_station">
                        <option selected value="">All</option>
                        @foreach($polling_stations as $branch)
                            <option @if($announcement->polling_station_id == $branch->id) selected="selected" @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('polling_station_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-polling_station_id"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Min Age </label>
                    <input type="text" id="min_age" name="min_age" value="{{old('min_age', $announcement->min_age)}}" class="form-control">
                    @error('min_age')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-min_age"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="max_age" class="control-label">Max Age </label>
                    <input type="text" id="max_age" name="max_age" value="{{old('max_age', $announcement->max_age)}}" class="form-control">
                    @error('max_age')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-max_age"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($announcement->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($announcement->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-status"> </span>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <h4 class="card-title">Media</h4>
                <hr />
                <div class="form-group col-4">
                    <label for="file_type" class="control-label">Select File Type</label>
                    <select id="file_type" name="file_type" class="form-control">
                        <option value="">Select file type</option>
                        <option value="audio">Audio</option>
                        <option value="image">Image</option>
                        <option value="document">Document</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <div class="form-group col-8">
                    <label for="file" class="control-label">File </label>
                    <input type="file" id="file" name="file" class="form-control">
                    @error('file')
                    <span class="input-note text-danger">{{ $message }}</span>
                    @enderror
                    <span class="input-note text-danger" id="error-file"></span>
                </div>
                <h4 class="card-title">Message</h4>
                <hr />
                <div class="form-group col-4">
                    <label for="tem_type" class="control-label">Select Template</label>
                    <select id="tem_type" name="tem_type" class="form-control">
                        <option value="">Select template</option>
                        @forelse($tem_types as $type)
                            <option value="{{ $type }}" {{ old('tem_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @empty
                            <option value="">No templates available</option>
                        @endforelse
                        <!-- Ensure 'custom' is one of the options -->
                        <option value="custom" {{ old('tem_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <!-- Short Message Container -->
                <div class="form-group col-8" id="short_message_container" style="{{ (old('tem_type') == 'custom' || $announcement->tem_type == 'custom') ? '' : 'display: none;' }}">
                    <label for="short_message" class="control-label">Short Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" name="short_message" id="short_message" required>{{ old('short_message', $announcement->short_message) }}</textarea>
                    @error('short_message')
                    <span class="input-note text-danger">{{ $message }}</span>
                    @enderror
                    <span class="input-note text-danger" id="error-short_message"></span>
                </div>

            </div>
            <div class="form-group">
                @if(user()->can('create-'.get_permission_name().'|update-'.get_permission_name()))
                    <button type="submit" class="btn btn-success save_btn"><i class="mdi mdi-send-check"></i> Send</button>
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
    $('#status').selectpicker('val', '{{$announcement->status}}');
    $('#constituency_id').selectpicker('val', '{{$announcement->constituency_id}}');
    $('#electoral_area_id').selectpicker('val', '{{$announcement->electoral_area_id}}');
    $('#polling_station_id').selectpicker('val', '{{$announcement->polling_station_id}}');
    $('#tem_type').selectpicker('val', '{{$announcement->tem_type}}');
    $('#file_type').selectpicker('val', '{{$announcement->file_type}}');
</script>

<script>
    $('.constituency').change(function () {
        if ($(this).val() !== '') {
            let value = $(this).val();
            let _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('ajax.electoralAreas') }}",
                method: "POST",
                data: {value: value, _token: _token},
                success: function (result) {
                    $('select').selectpicker("destroy");
                    var allOption = '<option selected value="">All</option>';
                    var updatedResult = allOption + result;
                    $('#electoral_area_id').html(updatedResult);
                    $('select').selectpicker();
                }
            });
        }
    });

    $('.electoral_area').change(function () {
        if ($(this).val() !== '') {
            let value = $(this).val();
            let _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('ajax.pollingStations') }}",
                method: "POST",
                data: {value: value, _token: _token},
                success: function (result) {
                    $('select').selectpicker("destroy");
                    var allOption = '<option selected value="">All</option>';
                    var updatedResult = allOption + result;
                    $('#polling_station_id').html(updatedResult);
                    $('select').selectpicker();
                }
            });
        }
    });

    function toggleShortMessage() {
        var selectedType = $('#tem_type').val();
        if (selectedType === 'custom') {
            $('#short_message_container').slideDown();
            $('#short_message').attr('required', 'required');
        } else {
            $('#short_message_container').slideUp();
            $('#short_message').removeAttr('required');
        }
    }

    // Initial check on page load
    toggleShortMessage();

    // Listen for changes on the tem_type select
    $('#tem_type').on('change', function() {
        toggleShortMessage();
    });
</script>
