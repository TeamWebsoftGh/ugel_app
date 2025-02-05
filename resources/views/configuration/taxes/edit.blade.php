<form method="POST" action="{{route('portal.configurations.taxes.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$tax->id}}">
    <input type="hidden" id="_name" name="me" value="{{$tax->name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label"> Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $tax->name)}}" class="form-control">
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="rate" class="control-label">Rate <span class="text-danger">*</span></label>
                    <input type="text" name="rate" class="form-control" value="{{old('rate', $tax->rate)}}">
                    @error('rate')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="type" class="control-label">Type <span class="text-danger">*</span></label>
                    <select class="form-control" name="type">
                        @forelse(\App\Constants\Constants::TAX_TYPES as $key => $name)
                            <option value="{{$key}}" @if($key == $tax->type) selected @endif>{{$name}}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('type')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control selectpicker">
                        <option value="0" @if($tax->status == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($tax->status == 1) selected="selected" @endif>Enable</option>
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
