<form method="POST" action="{{route('bulk-sms.store')}}" enctype="multipart/form-data">
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
                <x-form.input-field
                    name="is_active"
                    label="Status"
                    type="select"
                    :options="['1' => 'Active', '0' => 'Inactive']"
                    :value="$announcement->is_active"
                    required
                />
                <div class="form-group col-12">
                    <label for="short_message" class="control-label">Short Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" maxlength="160" name="short_message" id="short_message">{!! $announcement->short_message  !!}</textarea>
                    @error('short_message')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-short_message"> </span>
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
