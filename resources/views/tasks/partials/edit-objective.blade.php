@if(!$task->is_closed)
    <form class="mt-4" method="POST" id="objectiveForm" action="{{route("tasks.objectives.store")}}">
        @csrf
        <input type="hidden" id="_id2" name="task_id" value="{{$task->id}}">
        <input type="hidden" id="_id" name="id" value="{{$objective->id}}">
        <input type="hidden" id="_name" name="me" value="{{$objective->name}}">
        <div class="row">
            <div class="col-md-6">
                <label for="objective" class="form-label">Add Objective</label>
                <div class="input-group">
                    <input type="text" name="objective" value="{{old("objective", $objective->name)}}" class="form-control">
                    <button type="submit" class="btn-group btn btn-primary">Save</button>
                </div>
                <span class="input-note text-danger" id="error-objective"> </span>
                @error('objective')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div><!--end col-->
        </div><!--end row-->
    </form>
@endif
