@extends('layouts.admin.main')

@section('title', 'Add Page Menu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('tasks.newsroom.index')}}">Sub Menu Pages</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Add Sub Menu</h4>
                    @if($errors->all())
                        @foreach($errors->all() as $message)
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">×</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                {{ $message }}
                            </div>
                        @endforeach
                    @endif
                    <form method="post" id="needs-validation" novalidate action="{{route("tasks.configurations.menu.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 col-sm-10 col-12">
                                <input type="hidden" name="id" id="_id">
                                <div class="form-group">
                                    <label for="name">Menu Name</label>
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' parsley-error' : '' }}" name="name" required value="{{old('name')}}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="parent_menu_id">Menu Name</label>
                                    <select class="form-control" name="parent_menu_id" id="parent-menu-id">
                                        @forelse($menus as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="url">Page URL</label>
                                    <input type="checkbox" id="external-link">
                                    <input type="text" id="url" class="form-control{{ $errors->has('url') ? ' parsley-error' : '' }}" name="url" value="{{old('url')}}">
                                    @if ($errors->has('url'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Sort Number</label>
                                    <input type="number" id="sort-order" class="form-control{{ $errors->has('sort_order') ? ' parsley-error' : '' }}" name="sort_order" required value="{{old('sort_order')}}">
                                    @if ($errors->has('sort_order'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sort_order') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="parent_menu_id">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">In Active</option>
                                        <option value="built-in">Built In</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#" onclick="history.back()" class="btn btn-danger"> Go Back</a>
                                <button type="submit" class="btn btn-primary"> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end card-body-->
            </div>
        </div>
    </div>


    <div class="row" style="height:400px;overflow-y:auto;">
        <div class="col-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body table-responsive">
                    <h5 class="header-title">Menu List</h5>
                    <p class="text-muted mb-4 font-13"></p>
                    <div class="">
                        <table id="datatable2" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Parent Menu</th>
                                <th>Parent Sub Menu</th>
                                <th>Sub Menu</th>
                                <th>Url</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1 @endphp
                            @forelse($sub_menus as $sub)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$sub->menu->name}}</td>
                                    <td>{{($p=$sub->parent_sub_menu)?$p->name:''}}</td>
                                    <td>{{$sub->name}}</td>
                                    <td>{{$sub->url}}</td>
                                    <td>{{$sub->sort_order}}</td>
                                    <td>{{$sub->status}}</td>
                                    <td> <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-subscribers-{{ $sub->id }}"><i class="fas fa-trash-alt text-white"></i></a>
                                        <a class="btn btn-sm btn-success edit-btn"
                                           data-name="{{ $sub->name }}"
                                           data-url="{{ $sub->url }}"
                                           data-id="{{ $sub->id }}"
                                           data-status="{{ $sub->status }}"
                                           data-parent-menu-id="{{ $sub->parent_menu_id }}"
                                           data-sort-order="{{ $sub->sort_order }}"><i class="fas fa-pencil-alt text-white"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="delete-subscribers-{{ $sub->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Remove Item</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure  you  want to remove <b class='text-danger'>{{$sub->name}}</b> from the list?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('tasks.configurations.menu.destroy') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $sub->id }}">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="{{asset('js/dynamic-pages.js')}}"></script>
    <script src="{{ asset('js/edit.js') }}" type="text/javascript"></script>
@stop
