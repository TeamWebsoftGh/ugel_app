<form method="POST" action="{{route('contacts.store')}}" id="contact" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$contact->id}}">
    <input type="hidden" id="_name" name="me" value="{{$contact->fullname}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="first_name" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{old('first_name', $contact->first_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-first_name"> </span>
                    @error('first_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="other_names" class="control-label">Middle Name</label>
                    <input type="text" name="other_names" value="{{old('other_names', $contact->other_names)}}" class="form-control">
                    <span class="input-note text-danger" id="error-other_names"> </span>
                    @error('other_names')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-4">
                    <label for="surname" class="control-label">Surname</label>
                    <input type="text" name="surname" value="{{old('surname', $contact->surname)}}" class="form-control">
                    <span class="input-note text-danger" id="error-surname"> </span>
                    @error('surname')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="date_of_birth" class="control-label">Date of Birth</label>
                    <input type="text" name="date_of_birth" value="{{old('date_of_birth', $contact->date_of_birth)}}" class="form-control date">
                    <span class="input-note text-danger" id="error-date_of_birth"> </span>
                    @error('date_of_birth')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="phone_number" class="control-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $contact->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="phone_number" class="control-label">Email</label>
                    <input type="email" name="email" value="{{old('email', $contact->email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email"> </span>
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="company" class="control-label">Company</label>
                    <input type="text" name="company" value="{{old('company', $contact->company)}}" class="form-control">
                    <span class="input-note text-danger" id="error-company"> </span>
                    @error('company')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Contact Group <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="contact_group_id" id="contact_group_id" class="form-control selectpicker" data-live-search="true">
                            @forelse($contact_groups as $contact_group)
                                <option value="{{ $contact_group->id }}" {{ old('contact_group_id', $contact->contact_group_id) == $contact_group->id ? 'selected' : '' }}>{{ $contact_group->name }}</option>
                            @empty
                                <option>No groups available</option>
                            @endforelse
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="addNewGroup"><i class="las la-plus"></i></button>
                        </div>
                    </div>
                    <span class="input-note text-danger" id="error-contact_group_id"> </span>
                    @error('contact_group_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($contact->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($contact->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    document.getElementById('addNewGroup').addEventListener('click', function() {
        const groupName = prompt('Enter the name of the new contact group:');
        if (groupName) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{route("contact-groups.store")}}', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = function() {
                if (this.status >= 200 && this.status < 300) {
                    const response = JSON.parse(this.responseText);
                    const newOption = new Option(groupName, response.id, false, true);
                    document.getElementById('contact_group_id').add(newOption);
                    const select = document.getElementById('contact_group_id');
                    select.appendChild(newOption);
                    $(select).selectpicker('refresh');
                } else {
                    console.error('Request failed with status:', this.status);
                }
            };
            xhr.send(`name=${encodeURIComponent(groupName)}`);
        }
    });
</script>
