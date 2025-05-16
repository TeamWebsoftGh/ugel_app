@extends('layouts.main')
@section('title', 'Import Contacts')
@section('page-title', 'Contacts')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header  with-border">
                    <h3 class="card-title">{{__('Import Csv/Excel file only')}}</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">The first line in downloaded csv file should remain as it is. Please do not change
                        the order of columns in csv file.</p>
                    <p class="card-text">The correct column order is (First Name, Last Name, Phone Number,Email,Company, Date of Birth, Contact Group) and you must follow the csv file,
                        otherwise you will get an error while importing the csv file.</p>
                    <h6><a href="{{asset('sample_file/sample_contacts.csv')}}" class="btn btn-primary"> <i
                                class="fa fa-download"></i> {{__('Download sample File')}} </a></h6>
                    <hr/>
                    <form action="{{ route('contacts.importPost') }}" name="import_contact" id="import_contact" autocomplete="off" enctype="multipart/form-data"
                          method="post" accept-charset="utf-8">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <fieldset class="form-group">
                                        <label for="logo">{{__('Upload')}} {{__('File')}}</label>
                                        <input type="file" class="form-control" id="file" name="file"
                                               accept=".xlsx, .xls, .csv">
                                        <small>{{__('Please select csv or excel')}} file (allowed file size 5MB)</small>
                                        <span class="input-note text-danger" id="error-file"> </span>
                                        @error('file')
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
