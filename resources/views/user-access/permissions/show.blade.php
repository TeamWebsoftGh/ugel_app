@extends('layouts.admin.main')
@section('title', 'Institution Details')
@section('css')

@endsection
@section('admin_content')
    <div class="inner bg-container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-white">
                        Institution Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table no_border">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td><span>{{$institution->name}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>E-mail</td>
                                        <td><span>{{$institution->email}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Contact Number</td>
                                        <td><span>{{$institution->phone_number}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><span>{{$institution->address}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td>{{$institution->created_at->diffForHumans()}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 border-left">
                                <table class="table no_border">
                                    <tbody>
                                    <tr>
                                        <td>Administrator</td>
                                        <td>{{count($institution->users)>0?$institution->users->first()->fullname:'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <td>E-mail</td>
                                        <td><span>{{$institution->email}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Contact Number</td>
                                        <td><span>{{$institution->phone_number}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><span>{{$institution->address}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td>{{$institution->created_at->diffForHumans()}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-25">
            <div class="col-5 col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        Institution Details
                    </div>
                    <div class="card-body">
                        <table class="table" id="users">
                            <tbody>
                            <tr>
                                <td>Name</td>
                                <td><span>{{$institution->name}}</span></td>
                            </tr>
                            <tr>
                                <td>E-mail</td>
                                <td><span>{{$institution->email}}</span></td>
                            </tr>
                            <tr>
                                <td>Contact Number</td>
                                <td><span>{{$institution->phone_number}}</span></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><span>{{$institution->address}}</span></td>
                            </tr>
                            <tr>
                                <td>Created At</td>
                                <td>{{$institution->created_at->diffForHumans()}}</td>
                            </tr>
                            <tr>
                                <td>Administrator</td>
                                <td>{{count($institution->users)>0?$institution->users->first()->fullname:''}}</td>
                            </tr>
                            <tr>
                                <td>Pincode</td>
                                <td>
                                    <span class="editable" data-title="Edit Pincode">522522</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-7 col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        Branches
                    </div>
                    <div class="card-body">
                        <table class="table" id="users">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <td>User Name</td>
                                    <td class="inline_edit">
                                        <span class="editable" data-title="Edit User Name">Micheal</span>
                                    </td>
                                </tr>
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

@endsection