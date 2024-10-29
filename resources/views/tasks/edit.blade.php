<form method="POST" action="{{route('tasks.store')}}" enctype="multipart/form-data" novalidate>
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$task->id}}">
    <input type="hidden" id="_name" name="me" value="{{$task->title}}">
    <div class="row clearfix">
        <div class="col-md-12 col-xl-9 col-lg-10">
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label for="name" class="control-label">Parent Task </label>
                    <select name="parent_task_id" id="parent_task_id" required class="form-control selectpicker">
                        <option selected value="">Nothing Selected</option>
                        @foreach($tasks as $t)
                            <option @if($t->id == old('parent_task_id', $task->parent_task_id)) selected="selected" @endif value="{{ $t->id }}">{{ $t->title }}</option>
                        @endforeach
                    </select>
                    @error('parent_task_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-parent_task_id"> </span>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{old('title', $task->title)}}" class="form-control">
                    @error('title')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-title"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="start_date" class="control-label">Start Date <span class="text-danger">*</span></label>
                    <input type="text" id="start_date" name="start_date" value="{{old('start_date', $task->start_date)}}" class="form-control date">
                    @error('start_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-start_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Due Date <span class="text-danger">*</span></label>
                    <input type="text" id="due_date" name="due_date" value="{{old('due_date', $task->due_date)}}" class="form-control date">
                    @error('due_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-due_date"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="priority_id" class="control-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority_id" id="priority" required class="form-control selectpicker">
                        <option selected value="">Nothing Selected</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                    @error('priority_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-priority_id"> </span>
                </div>
                @if(user()->can('create-tasks|create-all-tasks'))
                    <div class="form-group col-12 col-md-4">
                        <label for="name" class="control-label">Assign To <span class="text-danger">*</span></label>
                        <select name="assignee_id" id="assignee" required data-live-search="true" class="form-control selectpicker">
                            <option selected value="">Nothing Selected</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('assignee_id')
                        <span class="input-note text-danger">{{ $message }} </span>
                        @enderror
                        <span class="input-note text-danger" id="error-assigned_to"> </span>
                    </div>
                @else
                    <input type="hidden" name="assignee_id" value="{{user()->id}}">
                @endif
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Always Copy </label>
                    <select name="always_copy[]" id="always_copy" required data-live-search="true" multiple class="form-control selectpicker">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                        @endforeach
                    </select>
                    @error('assignee_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-assigned_to"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="budget" class="control-label">Revenue Target</label>
                    <div class="input-group">
                        <div class="input-group-text">{{currency()->symbol}}</div>
                        <input type="number" min="0"  name="expected_revenue" value="{{old('expected_revenue', $task->expected_revenue)}}" class="form-control" />
                    </div>
                    @error('expected_revenue')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-expected_budget"> </span>
                </div>
                @if(is_workflow_admin())
                    <div class="form-group col-12 col-md-4">
                        <label for="budget" class="control-label">Budget (Optional)</label>
                        <div class="input-group">
                            <div class="input-group-text">{{currency()->symbol}}</div>
                            <input type="number" min="0"  name="budget" value="{{old('budget', $task->budget)}}" class="form-control" />
                        </div>
                        @error('budget')
                        <span class="input-note text-danger">{{ $message }} </span>
                        @enderror
                        <span class="input-note text-danger" id="error-expected_budget"> </span>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="resources" class="control-label">Resources</label>
                        <input type="text" id="resources" name="resources" value="{{old('resources', $task->resources)}}" class="form-control">
                        @error('resources')
                        <span class="input-note text-danger">{{ $message }} </span>
                        @enderror
                        <span class="input-note text-danger" id="error-resources"> </span>
                    </div>
                @endif
                <div class="form-group col-12 col-md-4">
                    <label for="total_weightage" class="control-label">Total Weightage <span class="text-danger">*</span></label>
                    <input type="number" min="1" id="total_weightage" name="total_weightage" value="{{old('total_weightage', $task->total_weightage)}}" class="form-control">
                    @error('total_weightage')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-total_weightage"> </span>
                </div>
                <div class="col-12 col-md-4">
                    <label for="exampleFormControlTextarea1" class="form-label">Upload Document</label>
                    <input name="task_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                    <span class="input-note text-danger" id="error-task_files"> </span>
                    @error('task_files')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div><!--end col-->
                <div class="form-group col-12">
                    <label for="description" class="control-label">Description</label>
                    <textarea class="form-control summernote" rows="6" name="description" id="description">{!! $task->description  !!}</textarea>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-content"> </span>
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
        $('#status').selectpicker('val', '{{old("status_id", $task->status_id)}}');
        $('#parent_task').selectpicker('val', '{{old("parent_task_id", $task->parent_task_id)}}');
        $('#assignee').selectpicker('val', '{{old("assignee_id", $task->assignee_id)}}');
        $('#priority').selectpicker('val', '{{old("priority_id", $task->priority_id)}}');
    });
</script>
