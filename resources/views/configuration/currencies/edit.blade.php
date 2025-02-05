<form method="POST" action="{{route('configurations.currencies.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$currency->id}}">
    <input type="hidden" id="_name" name="me" value="{{$currency->currency}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="currency" value="{{old('currency', $currency->currency)}}" class="form-control">
                    @error('currency')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="code" class="control-label">Currency Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{old('code', $currency->code)}}" class="form-control">
                    @error('code')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="symbol" class="control-label">Currency Symbol <span class="text-danger">*</span></label>
                    <input type="text" name="symbol" value="{{old('symbol', $currency->symbol)}}" class="form-control">
                    @error('symbol')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Is Default?</label>
                    <select name="is_default" id="is_default" class="form-control">
                        <option value="0" @if($currency->is_default == 0) selected="selected" @endif>No</option>
                        <option value="1" @if($currency->is_default == 1) selected="selected" @endif>Yes</option>
                    </select>
                    @error('is_default')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Symbol Position</label>
                    <select name="symbol_first" id="symbol_first" class="form-control">
                        <option value="0" @if($currency->symbol_first == 0) selected="selected" @endif>After</option>
                        <option value="1" @if($currency->symbol_first == 1) selected="selected" @endif>Before</option>
                    </select>
                    @error('symbol_first')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="thousand_separator" class="control-label">Thousand Separator <span class="text-danger">*</span></label>
                    <input type="text" name="thousand_separator" value="{{old('thousand_separator', $currency->thousand_separator)}}" class="form-control">
                    @error('thousand_separator')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="decimal_separator" class="control-label">Decimal Separator <span class="text-danger">*</span></label>
                    <input type="text" name="decimal_separator" value="{{old('decimal_separator', $currency->decimal_separator)}}" class="form-control">
                    @error('decimal_separator')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="exchange_rate" class="control-label">Precision <span class="text-danger">*</span></label>
                    <input type="number" min="0" name="precision" value="{{old('precision', $currency->precision)}}" class="form-control">
                    @error('precision')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="exchange_rate" class="control-label">Exchange Rate <span class="text-danger">*</span></label>
                    <input type="number" min="0" name="exchange_rate" value="{{old('exchange_rate', $currency->exchange_rate)}}" class="form-control">
                    @error('exchange_rate')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="0" @if($currency->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($currency->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
    </div>
</form>
@if($currency->id != null)
@endif
