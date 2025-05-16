<form method="POST" id="knowledge_base" action="{{route('resource.knowledge-base.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$topic->id}}">
    <input type="hidden" id="_name" name="me" value="{{$topic->title}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <x-form.input-field
                    name="title"
                    label="Title"
                    type="text"
                    class="col-md-12"
                    :value="$topic->title"
                    required
                />
                <!-- Type -->
                <x-form.input-field
                    name="category_id"
                    label="Category"
                    type="select"
                    :options="$categories->pluck('name', 'id')"
                    :value="$topic->category_id"
                    required
                />

                <x-form.input-field
                    name="publish_date"
                    label="Publish Date"
                    type="date"
                    :value="$topic->publish_date"
                    required
                />
                <x-form.input-field
                    name="is_active"
                    label="Status"
                    type="select"
                    :options="['1' => 'Active', '0' => 'Inactive']"
                    :value="$topic->is_active"
                    required
                />
                <x-form.input-field
                    name="content"
                    label="Content"
                    type="summernote"
                    :value="$topic->content"
                    class="col-md-12"
                />
                <x-form.input-field
                    name="attachments"
                    label="Upload Attachments"
                    type="multifile"
                    :value="$topic->attachments"
                />
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
