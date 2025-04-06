<form method="POST" id="support_topic" action="{{route('support-topics.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$item->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$item->name}}">
    <div class="row">
        <x-form.input-field
            name="name"
            label="Name"
            type="text"
            placeholder="Name"
            :value="$item->name"
            required
        />

        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$item->is_active"
            required
        />

        <x-form.input-field
            name="description"
            label="Description"
            class="col-md-8"
            type="textarea"
            placeholder="Enter a description"
            :value="$item->description"
        />
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        let date = $('.date');
        date.datepicker({
            format: '{{ env('Date_Format_JS')}}',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

