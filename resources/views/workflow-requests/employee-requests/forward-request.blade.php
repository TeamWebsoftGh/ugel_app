<form method="POST" action="{{$url}}" accept-charset="UTF-8" role="form" class="form-loading-button" novalidate="" enctype="multipart/form-data">
	@csrf
	<div class="card-body">
		<div class="row">
			<div class="form-group col-md-10 ">
				<label for="form-control-label">New Implementor </label>
				<select class="form-control" name="employee" id="employee">
					@forelse($all_employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->fullname}}</option>
                    @empty
                    @endforelse
				</select>
			</div>
		</div>
	</div>

    <div class="card-footer">
        <p class="message" style="color: #e41;"></p>
        <div class="row save-buttons float-right">
            <input class="btn btn-success send_forward_request" type="submit" value="Update" autocomplete="off">
             
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    
</form>