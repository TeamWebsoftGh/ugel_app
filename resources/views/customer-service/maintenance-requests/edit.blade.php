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
        <x-form.input-field
            name="maintenance_category_id"
            label="Category"
            type="select"
            :options="$categories->pluck('name', 'id')"
            :value="$maintenance->maintenance_category_id"
            required
        />
        <x-form.input-field
            name="maintenance_subcategory_id"
            label="Sub Category"
            id="maintenance_subcategory_id"
            type="multiselect"
            :options="[]"
            multiple
            :value="$maintenance->categories->pluck('id')->toArray()"
        />
        <x-form.input-field
            name="other_issue"
            label="If Other, please specify"
            type="text"
            :value="$maintenance->other_issue"
        />
        <x-form.input-field
            name="priority_id"
            label="Priority"
            type="select"
            :options="$priorities->pluck('name', 'id')"
            :value="$maintenance->priority_id"
            required
        />
        <x-form.input-field
            name="client_id"
            label="Customer"
            type="select"
            :options="$customers->pluck('fullname', 'id')"
            :value="$maintenance->client_id"
            required
        />
        <x-form.input-field
            name="client_number"
            label="Customer/Student Number"
            type="text"
            :value="$maintenance->client_number"
            required
        />
        <x-form.input-field
            name="client_phone_number"
            label="Customer Phone Number"
            type="text"
            :value="$maintenance->client_phone_number"
            required
        />
        <x-form.input-field
            name="client_email"
            label="Customer Email"
            type="text"
            :value="$maintenance->client_email"
            required
        />
        <x-form.input-field
            name="property_id"
            label="Property"
            type="select"
            :options="$properties->pluck('property_name', 'id')"
            :value="$maintenance->property_id"
            required
        />
        <x-form.input-field
            name="property_unit_id"
            label="Property Unit"
            type="select"
            :options="[]"
            :value="$maintenance->property_unit_id"
            required
        />
        <x-form.input-field
            name="room_id"
            label="Room"
            type="select"
            :options="[]"
            :value="$maintenance->room_id"
            required
        />
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
        <x-form.input-field
            name="user_id"
            label="Responsible"
            type="select"
            :options="$users->pluck('fullname', 'id')"
            :value="$maintenance->user_id"
            required
        />
        <x-form.input-field
            name="assignee_ids"
            label="Assignees"
            type="multiselect"
            :options="$users->pluck('fullname', 'id')"
            :value="$maintenance->users->pluck('id')->toArray()"
            multiple
        />

        <x-form.input-field
            name="attachments"
            label="Upload Attachments"
            type="multifile"
            :value="$maintenance->attachments"
        />
        <div class="form-group col-12 col-md-12">
            <label for="note" class="control-label">Note</label>
            <textarea class="form-control" rows="3" name="note" id="ticket_note">{!! old('note', $maintenance->note)  !!}</textarea>
            @error('note')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
            <span class="input-note text-danger" id="error-note"> </span>
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
        @include("shared.save-button")
    </div>
</form>
<script>
    $(document).ready(function () {
        const propertyId = $('#property_id').val();
        const selectedUnitId = "{{ $maintenance->property_unit_id }}";
        const selectedRoomId = "{{ $maintenance->room_id }}";
        const selectedCategoryId = $('#maintenance_category_id').val();
        const selectedCategories = @json($maintenance->categories->pluck('id'));

        // On page load – load units and rooms if values exist
        if (propertyId) {
            updateDropdown(`/api/clients/common/property-units?filter_property=${propertyId}`, 'property_unit_id', 'Select Property Unit', selectedUnitId);
        }

        if (selectedUnitId) {
            updateDropdown(`/api/clients/common/rooms?filter_property_unit=${selectedUnitId}`, 'room_id', 'Select Room', selectedRoomId);
        }

        if (selectedCategoryId) {
            $.get(`/ajax/get-maintenance-categories/${selectedCategoryId}`, function (data) {
                let options = '';
                $.each(data, function (id, name) {
                    const selected = selectedCategories.includes(parseInt(id)) ? 'selected' : '';
                    options += `<option value="${id}" ${selected}>${name}</option>`;
                });

                $('#maintenance_subcategory_id').html(options).selectpicker?.('refresh');
            });
        }

        // On customer change → populate contact info + related properties
        $('#client_id').on('change', function () {
            const customerId = $(this).val();
            if (!customerId) return;

            $.get(`/ajax/get-customer-details/${customerId}`, function (data) {
                // 1. Update customer info
                $('#client_number').val(data.client_number);
                $('#client_phone_number').val(data.client_phone_number);
                $('#client_email').val(data.client_email);

                // 2. Preselect property from existing options
                $('#property_id').val(data.selected.property_id).selectpicker('refresh');

                // 3. Load units based on selected property
                if (data.selected.property_id) {
                    updateDropdown(
                        `/api/clients/common/property-units?filter_property=${data.selected.property_id}`,
                        'property_unit_id',
                        'Select Property Unit',
                        data.selected.property_unit_id
                    );
                }

                // 4. Load rooms based on selected unit
                if (data.selected.property_unit_id) {
                    updateDropdown(
                        `/api/clients/common/rooms?filter_property_unit=${data.selected.property_unit_id}`,
                        'room_id',
                        'Select Room',
                        data.selected.room_id
                    );
                }
            });
        });

        // On property change → load units and maybe preselect
        $('#property_id').on('change', function () {
            const propertyId = $(this).val();
            const selectedUnit = $('#property_unit_id').data('selected') || '';
            const selectedRoom = $('#room_id').data('selected') || '';

            $('#property_unit_id').html('<option value="">Loading...</option>');
            $('#room_id').html('<option value="">Select Room</option>');

            if (propertyId) {
                updateDropdown(`/api/clients/common/property-units?filter_property=${propertyId}`, 'property_unit_id', 'Select Property Unit', selectedUnit);
                if (selectedUnit) {
                    updateDropdown(`/api/clients/common/rooms?filter_property_unit=${selectedUnit}`, 'room_id', 'Select Room', selectedRoom);
                }
            }
        });

        // On unit change → load rooms
        $('#property_unit_id').on('change', function () {
            const unitId = $(this).val();
            $('#room_id').html('<option value="">Loading...</option>');
            if (unitId) {
                updateDropdown(`/api/clients/common/rooms?filter_property_unit=${unitId}`, 'room_id', 'Select Room');
            }
        });

        $('#maintenance_category_id').on('change', function () {
            const categoryId = $(this).val();
            if (!categoryId) return;

            $.get(`/ajax/get-maintenance-categories/${categoryId}`, function (data) {
                let options = '';
                $.each(data, function (id, name) {
                    options += `<option value="${id}">${name}</option>`;
                });

                $('#maintenance_subcategory_id').html(options).selectpicker?.('refresh');;
            });
        });

    });
</script>

