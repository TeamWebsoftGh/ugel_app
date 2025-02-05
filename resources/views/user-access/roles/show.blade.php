@extends('layouts.admin.main')

@section('content')
    <!--------------------
    START - Breadcrumbs
    -------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('tasks.dashboard')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{route('tasks.roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item"><span>Edit</span></li>
    </ul>
    <!--------------------
    END - Breadcrumbs
    -------------------->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-10 col-lg-8">
                    <div class="element-wrapper">
                        <h6 class="element-header">Roles</h6>
                        <div class="element-box">
                            <h5 class="form-header">Role</h5>
                            <form id="formValidation" action="{{route('tasks.roles.store')}}" method="post">
                                @csrf
                                <h5 class="form-header"></h5>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4 col-md-3" for=""> Role Name</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input class="form-control" name="name" value="{{old('name')}}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4 col-md-3" for=""> Display name</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input class="form-control" name="display_name" value="{{old('display_name')}}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-3 col-form-label">Description</label>
                                    <div class="col-sm-8 col-md-9">
                                        <textarea class="form-control" name="description" rows="3">{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-3 col-form-label" for="permissions">Permissions</label>
                                    <div class="col-sm-8 col-md-9">
                                        <select name="permissions[]" id="permissions" class="form-control select2" multiple="multiple">
                                            @foreach($permissions as $permission)
                                                <option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
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
                </div>
            </div>
        </div>
    </div>

@endsection
