<form method="POST" action="{{route('configuration.currencies.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$currency->id}}">
    <input type="hidden" id="_name" name="me" value="{{$currency->currency}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <x-form.input-field name="currency" label="Currency" required type="text" placeholder="Enter currency name" :value="$currency->currency" />
                <x-form.input-field name="code" required label="Currency Code" type="text" placeholder="Currency Code" :value="$currency->code" />
                <x-form.input-field name="symbol" required label="Currency Symbol" type="text" placeholder="Currency symbol" :value="$currency->symbol" />
                <x-form.input-field name="is_default" label="Is Default?" type="select" :options="['1' => 'Yes', '0' => 'No']" :value="$currency->is_default" required />
                <x-form.input-field name="symbol_first" label="Symbol Position" type="select" :options="['1' => 'Before', '0' => 'After']" :value="$currency->symbol_first" required />
                <x-form.input-field name="thousand_separator" required label="Thousand Separator" type="text" placeholder="Thousand Separator" :value="$currency->thousand_separator" />
                <x-form.input-field name="decimal_separator" required label="Decimal Separator" type="text" placeholder="Decimal Separator" :value="$currency->decimal_separator" />
                <x-form.input-field name="precision" required label="Precision" type="text" placeholder="Precision" :value="$currency->precision" />
                <x-form.input-field name="exchange_rate" required label="Exchange Rate" type="number" placeholder="Exchange Rate" :value="$currency->exchange_rate" />
                <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$currency->is_active" required />

                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
@if($currency->id != null)
@endif
