<form method="POST" action="{{route('support-tickets.store')}}" enctype="multipart/form-data" novalidate>
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$ticket->id}}">
    <input type="hidden" id="_name" name="me" value="{{$ticket->ticket_code}}">
    <div class="row clearfix">
        <div class="col-md-12 col-xl-9 col-lg-10">
            <div class="row">
                @if($ticket->ticket_code)
                <div class="form-group col-12 col-md-4">
                    <label for="subject" class="control-label">Ticket Number </label>
                    <input type="text" readonly value="{{$ticket->ticket_code}}" class="form-control">
                </div>
                @endif
                <div class="form-group col-12 col-md-4">
                    <label for="subject" class="control-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" id="subject" name="subject" value="{{old('subject', $ticket->subject)}}" class="form-control">
                    @error('subject')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-subject"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="priority_id" class="control-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority_id" id="priority" required class="form-control selectpicker">
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                    @error('priority_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-priority_id"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" id="customer_id" data-live-search="true" class="form-control selectpicker">
                        <option selected value=""></option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @if($ticket->customer_id == $customer->id) selected @endif>{{ $customer->fullname }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-customer_id"> </span>
                </div>
                @if(user()->can('create-support-tickets|update-support-tickets'))
                    <div class="form-group col-12 col-md-4">
                        <label for="name" class="control-label">Assign To <span class="text-danger">*</span></label>
                        <select name="assigned_to" id="assignee" multiple data-live-search="true" class="form-control selectpicker">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @if(in_array($employee->id, $ticket->assignedIds())) selected @endif>{{ $employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                        <span class="input-note text-danger">{{ $message }} </span>
                        @enderror
                        <span class="input-note text-danger" id="error-assigned_to"> </span>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="exampleFormControlTextarea1" class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option @selected("opened" == $ticket->status) value="opened">Opened</option>
                            <option @selected("closed" == $ticket->status) value="closed">Closed</option>
                            <option @selected("cancelled" == $ticket->status) value="cancelled">Cancelled</option>
                            <option @selected("reopened" == $ticket->status) value="cancelled">Reopened</option>
                        </select>
                        <span class="input-note text-danger" id="error-status"> </span>
                        @error('status')
                        <span class="input-note text-danger">{{ $message }} </span>
                        @enderror
                    </div><!--end col-->
                @endif
                <div class="form-group col-12 col-md-4">
                    <label for="exampleFormControlTextarea1" class="form-label">Upload Attachment</label>
                    <input name="ticket_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                    <span class="input-note text-danger" id="error-ticket_files"> </span>
                    @error('ticket_files')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div><!--end col-->
                <div class="form-group col-12 col-md-8">
                    <label for="description" class="control-label">Note</label>
                    <textarea class="form-control" rows="3" name="ticket_note" id="ticket_note">{!! old('ticket_note', $ticket->ticket_note)  !!}</textarea>
                    @error('ticket_note')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-description"> </span>
                </div>
                <div class="form-group col-12">
                    <label for="description" class="control-label">Description</label>
                    <textarea class="form-control summernote" rows="6" name="description" id="description">{!! old('description', $ticket->description)  !!}</textarea>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-description"> </span>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Save</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#priority').selectpicker('val', '{{old("priority_id", $ticket->priority_id)}}');
    });
</script>
