<form action="{{route("tasks.activities.store")}}" id="activityForm" method="POST">
    <div class="modal-body">
        <p>All fields with <span class="text-danger">*</span> are required.</p>
        @csrf
        <input type="hidden" id="_id" name="task_id" data-ignore="1" value="{{$task->id}}">
        <input type="hidden" id="_id" name="id" value="{{$activity->id}}">
        <input type="hidden" id="_name" name="me" value="{{$activity->name}}">
        <div class="row g-3">
            <div class="col-md-12">
                <div>
                    <label for="objective_id" class="form-label">Objective</label>
                    <select class="form-control selectpicker" name="objective">
                        <option selected disabled>Nothing Selected</option>
                        @forelse($objectives as $obj)
                            <option value="{{$obj->id}}" @if($obj->id == $activity->check_list_item_id) selected @endif>{{$obj->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('objective')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-objective"> </span>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <label for="name-field" class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" value="{{old('start_time', $activity->start_time)}}" class="form-control" required />
                    @error('start_time')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-start_time"> </span>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <label for="company_name-field" class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_time" value="{{old('end_time', $activity->end_time)}}" class="form-control" required />
                    @error('end_time')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-end_time"> </span>
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <label for="designation-field" class="form-label">Note <span class="text-danger">*</span></label>
                    <textarea rows="3" name="note" class="form-control">{!! old('note', $activity->note) !!}</textarea>
                    @error('note')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-note"> </span>
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <label for="comments-field" class="form-label">Comments </label>
                    <textarea rows="3" name="comments" class="form-control">{!! old('comments', $activity->comments) !!}</textarea>
                    @error('comments')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-comments"> </span>
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <label for="designation-field" class="form-label">Challenges</label>
                    <textarea rows="3" name="challenges" class="form-control">{!! old('challenges', $activity->challenges) !!}</textarea>
                    @error('challenges')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-note"> </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div>
                    <label for="budget" class="form-label">Expenses</label>
                    <div class="input-group">
                        <div class="input-group-text">{{currency()->symbol}}</div>
                        <input type="number" min="0"  name="expense" value="{{old('expense', $activity->expense)}}" class="form-control" />
                    </div>
                    @error('expense')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-budget"> </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div>
                    <label for="revenue" class="form-label">Revenue</label>
                    <div class="input-group">
                        <div class="input-group-text">{{currency()->symbol}}</div>
                        <input type="number" min="0" name="revenue" value="{{old('revenue', $activity->revenue)}}" class="form-control" />
                    </div>
                    @error('revenue')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-revenue"> </span>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
                <label for="name" class="control-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" required class="form-control selectpicker">
                    <option value="pending" @selected($activity->status == "pending")>Pending</option>
                    <option value="completed" @selected($activity->status == "completed")>Completed</option>
                    <option value="cancelled" @selected($activity->status == "cancelled")>Cancelled</option>
                </select>
                @error('status')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
                <span class="input-note text-danger" id="error-status"> </span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="hstack gap-2 justify-content-end">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            @if(user()->id == $task->assignee_id && !$task->is_closed)
                <button type="submit" class="btn btn-success" id="add-btn">Save</button>
            @endif
        </div>
    </div>
</form>
