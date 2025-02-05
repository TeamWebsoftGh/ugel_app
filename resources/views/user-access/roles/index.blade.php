@extends('layouts.admin.main')

@section('content')
    <!--------------------
    START - Breadcrumbs
    -------------------->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="page-title br-0">Roles</h3>
            </div>
            <div class="right-title w-170">
					<span class="subheader_daterange font-weight-600" id="dashboard_daterangepicker">
						<span class="subheader_daterange-label">
							<span class="subheader_daterange-title"></span>
							<span class="subheader_daterange-date text-primary"></span>
						</span>
						<a href="#" class="btn btn-rounded btn-sm btn-primary">
							<i class="fa fa-angle-down"></i>
						</a>
					</span>
            </div>
        </div>
    </div>
    <!--------------------
    END - Breadcrumbs
    -------------------->
    <section class="content">
        <div class="row">
            <div class="col-5">
                <div class="box">
                    <div class="box-header">
                        <h4 class="form-header">List of all Roles</h4>
                        <a href="{{route('tasks.roles.create')}}" class="btn btn-outline-primary">Create New Role</a>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th><input type="text"></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td><a id="edit-item" href="#" data-id="{{ $role->id }}">{{$role->display_name}}</a></td>
                                    </tr>
                                    <div class="modal fade" id="delete-role-{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Applicant </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure  you  want to delete <b class='text-danger'>{{$role->display_name}}</b>?
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('tasks.roles.destroy', $role->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="view-permission-{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Permisions for {{$role->display_name}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul>
                                                        @foreach($role->permissions as $permission)
                                                            <li>{{$permission->display_name}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box">
                    <div class="box-header">
                        <h4 class="form-header"> Role</h4>
                    </div>
                    <div id="content" class="box-body">
                        <form id="myform" action="{{route('tasks.roles.update', $role->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            <h5 class="form-header"></h5>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4 col-md-3" for=""> Role Name</label>
                                <div class="col-sm-8 col-md-9">
                                    <input class="form-control" name="name" value="{{$role->name}}" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4 col-md-3" for=""> Display name</label>
                                <div class="col-sm-8 col-md-9">
                                    <input class="form-control" name="display_name" value="{{$role->display_name}}" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-md-3 col-form-label">Description</label>
                                <div class="col-sm-8 col-md-9">
                                    <textarea class="form-control" name="description" rows="3">{{$role->description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-md-3 col-form-label" for="permissions">Permissions</label>
                                <div class="col-sm-8 col-md-9">
                                    <select name="permissions[]" id="permissions" class="form-control select2" multiple="multiple">
                                        @foreach($permissions as $permission)
                                            <option @if(in_array($permission->id, $attachedPermissionsArrayIds)) selected="selected" @endif value="{{ $permission->id }}">{{ $permission->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-buttons-w text-right">
                                <button class="btn btn-primary" type="submit"> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('layouts.admin.action')
            </div>

        </div>
    </section>
@endsection
@section('scripts')
@endsection
