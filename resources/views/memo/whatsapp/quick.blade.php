@extends('layouts.main')
@section('title', 'Send Quick WhatsApp')
@section('page-title', 'Quick WhatsApp')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('whatsapp.index') }}">Bulk WhatsApp</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Quick WhatsApp Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        <form method="POST" action="{{ route('whatsapp.quick') }}" enctype="multipart/form-data">
                            <p>All fields with <span class="text-danger">*</span> are required.</p>
                            @csrf
                            <input type="hidden" id="_id" name="id" value="{{ $sms->id }}">
                            <input type="hidden" id="_name" name="me" value="{{ $sms->title }}">
                            <div class="row clearfix">
                                <div class="col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="contact_group_id" class="control-label">Select Contact Group </label>
                                            <select name="contact_group_id" id="contact_group_id" class="form-control" data-choices>
                                                <option selected value="">Nothing Selected</option>
                                                @forelse($contact_groups as $contact_group)
                                                    <option value="{{ $contact_group->id }}" {{ old('contact_group_id', $sms->contact_group_id) == $contact_group->id ? 'selected' : '' }}>
                                                        {{ $contact_group->name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('contact_group_id')
                                            <span class="input-note text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-contact_group_id"></span>
                                        </div>
                                        <div class="form-group col-12 col-md-12">
                                            <label for="recipient" class="control-label">Recipient(s) <span class="text-danger">*</span></label>
                                            <input type="text" id="recipient" name="recipient" value="{{ old('recipient', $sms->recipient) }}" class="form-control" required>
                                            @error('recipient')
                                            <span class="input-note text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-recipient"></span>
                                            <p class="text-info">Add recipients separated by comma(,) e.g., 0200000000,0240000000</p>
                                        </div>
                                        <h4 class="card-title">Media</h4>
                                        <hr />
                                        <div class="form-group col-4">
                                            <label for="file_type" class="control-label">Select File Type</label>
                                            <select id="file_type" name="file_type" class="form-control">
                                                <option value="">Select file type</option>
                                                <option value="audio">Audio</option>
                                                <option value="image">Image</option>
                                                <option value="document">Document</option>
                                                <option value="video">Video</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-8">
                                            <label for="file" class="control-label">File </label>
                                            <input type="file" id="file" name="file" class="form-control">
                                            @error('file')
                                            <span class="input-note text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-file"></span>
                                        </div>
                                        <h4 class="card-title">Message</h4>
                                        <hr />
                                        <div class="form-group col-4">
                                            <label for="tem_type" class="control-label">Select Template</label>
                                            <select id="tem_type" name="tem_type" class="form-control">
                                                <option value="">Select template</option>
                                                @forelse($tem_types as $type)
                                                    <option value="{{ $type }}" {{ old('tem_type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @empty
                                                    <option>No templates available</option>
                                                @endforelse
                                                <!-- Ensure 'custom' is one of the options -->
                                                <option value="custom" {{ old('tem_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                                            </select>
                                        </div>
                                        <!-- Short Message Container -->
                                        <div class="form-group col-8" id="short_message_container" style="{{ (old('tem_type') == 'custom' || $sms->tem_type == 'custom') ? '' : 'display: none;' }}">
                                            <label for="short_message" class="control-label">Short Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="3" name="short_message" id="short_message" required>{{ old('short_message', $sms->short_message) }}</textarea>
                                            @error('short_message')
                                            <span class="input-note text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-short_message"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if(user()->can('create-'.get_permission_name().'|update-'.get_permission_name()))
                                            <button type="submit" class="btn btn-success save_btn">
                                                <i class="mdi mdi-content-save fa-save"></i> Send
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('js')
    <!-- If using jQuery -->
    <script>
        $(document).ready(function() {
            function toggleShortMessage() {
                var selectedType = $('#tem_type').val();
                if (selectedType === 'custom') {
                    $('#short_message_container').slideDown();
                    $('#short_message').attr('required', 'required');
                } else {
                    $('#short_message_container').slideUp();
                    $('#short_message').removeAttr('required');
                }
            }

            // Initial check on page load
            toggleShortMessage();

            // Listen for changes on the tem_type select
            $('#tem_type').on('change', function() {
                toggleShortMessage();
            });
        });
    </script>
@endsection
