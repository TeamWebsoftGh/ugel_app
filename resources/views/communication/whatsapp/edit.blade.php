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
                <x-form.input-field name="property_type_id" label="Property Type" type="select" :options="$property_types->pluck('name', 'id')" :value="$announcement->property_type_id" />
                <x-form.input-field
                    name="property_id"
                    label="Property"
                    type="select"
                    :options="[]"
                    :value="$announcement->property_id"
                />
                <x-form.input-field
                    name="client_type_id"
                    label="Client Type"
                    type="select"
                    :options="$client_types->pluck('name', 'id')"
                    :value="$announcement->client_type_id"
                />
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
                @if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
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
    $('#tem_type').selectpicker('val', '{{$announcement->tem_type}}');
    $('#file_type').selectpicker('val', '{{$announcement->file_type}}');
</script>

<script>
    function updateDropdown(url, targetDropdown, defaultOption = 'Select an option', selectedValue = null) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.status_code === '000') {
                    let dropdown = $('#' + targetDropdown);
                    dropdown.empty();
                    dropdown.append(`<option value="">${defaultOption}</option>`);

                    $.each(response.data, function(index, item) {
                        let isSelected = selectedValue && selectedValue == item.id ? 'selected' : '';
                        dropdown.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
                    });

                    dropdown.selectpicker('refresh');
                } else {
                    console.error("Error fetching data:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    $(document).ready(function() {
        let property = $('#property_id').val();

        // Load dropdowns with existing values on page load
        if ($('#property_type_id').val()) {
            updateDropdown(
                `/api/clients/common/properties?filter_property_type=${$('#property_type_id').val()}`,
                'property_id',
                'Select Property',
                property
            );
        }

        // Update dropdowns dynamically when user changes selection
        $('#property_type_id').change(function() {
            let typeId = $(this).val();
            updateDropdown(`/api/clients/common/properties?filter_property_type=${typeId}`, 'property_id', 'Select Property');
        });
    });

</script>

