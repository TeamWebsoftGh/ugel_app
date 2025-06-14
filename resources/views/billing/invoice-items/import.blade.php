@extends('layouts.main')
@section('title', 'Import Amenities')
@section('page-title', 'Amenities')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header  with-border">
                    <h3 class="card-title">{{__('Import CSV/Excel file only')}}</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">The first line in downloaded csv file should remain as it is. Please do not change
                        the order of columns in csv file.</p>
                    <p class="card-text">The correct column order is (Name,Short Name) and you must follow the csv file,
                        otherwise you will get an error while importing the csv file.</p>
                    <h6><a href="{{asset('sample_file/sample_amenities.csv')}}" class="btn btn-primary"> <i
                                class="fa fa-download"></i> {{__('Download sample File')}} </a></h6>
                    <hr/>
                    <form action="{{ route('amenities.importPost') }}" name="import_employee" id="import_employee" autocomplete="off" enctype="multipart/form-data"
                          method="post" accept-charset="utf-8">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <fieldset class="form-group">
                                        <label for="logo">{{__('Upload')}} {{__('File')}}</label>
                                        <input type="file" class="form-control" id="file" name="import"
                                               accept=".xlsx, .xls, .csv">
                                        <p>{{__('Please select csv or excel')}} file (allowed file size 10MB)</p>
                                        <span class="input-note text-danger" id="error-import"> </span>
                                        @error('import')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="form-actions box-footer">
                                <button name="import_form" type="submit" class="btn btn-primary"><i
                                        class="fa fa fa-check-square-o"></i> {{__('Save')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
