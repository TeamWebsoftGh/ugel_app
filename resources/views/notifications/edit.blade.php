<form method="POST" action="{{route('tasks.customers.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$customer->id}}">
    <input type="hidden" id="_name" name="me" value="{{$customer->fullname}}">
    <div class="row clearfix">
        <div class="col-md-10 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{old('first_name', $customer->first_name)}}" class="form-control">
                    @error('first_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="email" class="control-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{old('last_name', $customer->last_name)}}">
                    @error('last_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="email" class="control-label">Other Names</label>
                    <input type="text" name="other_names" class="form-control" value="{{old('other_names', $customer->other_names)}}">
                    @error('other_names')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{old('email', $customer->email)}}" class="form-control">
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="username" class="control-label">Username</label>
                    <input type="text" id="username" @if($customer->username != null)readonly @endif name="username" class="form-control" value="{{$customer->username}}">
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Phone Number</label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $customer->phone_number)}}" class="form-control">
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Gender</label>
                    <br />
                    <label class="fancy-radio">
                        <input type="radio" name="gender" value="Male" @if(old('gender', $customer->gender) == "Male") checked @endif>
                        <span><i></i>Male</span>
                    </label>
                    <label class="fancy-radio">
                        <input type="radio" name="gender" value="Female" @if(old('gender', $customer->gender) == "Female") checked @endif>
                        <span><i></i>Female</span>
                    </label>
                    @error('gender')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.controls")
                </div>
            </div>
        </div>
    </div>
</form>
