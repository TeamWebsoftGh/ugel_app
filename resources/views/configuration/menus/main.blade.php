@extends('layouts.admin.main')

@section('title', 'Add Page Menu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Main Menu Pages</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Add Main Menu</h4>
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
                    <form method="post" id="needs-validation" novalidate action="{{route('tasks.configurations.menu.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="id" id="_id">
                            <div class="form-group col-md-4 col-md-4">
                                <label for="name">Menu Name</label>
                                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' parsley-error' : '' }}" name="name" required value="{{old('name')}}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="parent_menu_id">Parent Menu</label>
                                <select class="form-control" name="parent_id" id="parent-id">
                                    <option value="">Select option</option>
                                    @forelse($menus_for_create as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="url">Page URL</label>
                                <input type="checkbox" id="external-link">
                                <input type="text" id="url" class="form-control{{ $errors->has('url') ? ' parsley-error' : '' }}" name="url" value="{{old('url')}}">
                                @if ($errors->has('url'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('url') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="sort-order">Sort Number</label>
                                <input id="sort-order" type="number" class="form-control{{ $errors->has('sort_order') ? ' parsley-error' : '' }}" name="sort_order" required value="{{old('sort_order')}}">
                                @if ($errors->has('sort_order'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sort_order') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="position">Position</label>
                                <select class="form-control" name="position" id="position">
                                    <option value="main-navigation">Main Navigation</option>
                                    <option value="footer-navigation">Footer Navigation</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="parent_menu_id">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">In Active</option>
                                    <option value="built-in">Built In</option>
                                </select>
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
                                <th>Name</th>
                                <th>Url</th>
                                <th>Position</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1 @endphp
                            @forelse($menus as $sub)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{($pn=$sub->parent)?$pn->name:''}}</td>
                                    <td>{{$sub->name}}</td>
                                    <td>{{$sub->url}}</td>
                                    <td>{{$sub->position}}</td>
                                    <td>{{$sub->sort_order}}</td>
                                    <td>{{$sub->status}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-subscribers-{{ $sub->id }}"><i class="fas fa-trash-alt text-white"></i></a>
                                        <a class="btn btn-sm btn-success edit-btn"
                                           data-name="{{ $sub->name }}"
                                           data-url="{{ $sub->url }}"
                                           data-id="{{ $sub->id }}"
                                           data-parent-id="{{ $sub->parent_id }}"
                                           data-status="{{ $sub->status }}"
                                           data-position="{{ $sub->position }}"
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


@section("js")
    <script src="{{ asset('js/edit.js') }}" type="text/javascript"></script>

    <script src="/dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/dist/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/dist/pages/jquery.datatable.init.js"></script>
@endsection
