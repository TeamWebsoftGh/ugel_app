<div class="table-responsive">
    <table id="datatable-buttons" class="table dt-responsive">
        <thead>
        <tr>
            <th>#</th>
            <th>Cover</th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Total Products</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Cover</th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Total Products</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($categories as $category)
            <tr>
                <td>{{$i++}}</td>
                <td>
                    @if(isset($category->cover))
                        <img src="{{ asset("storage/$category->cover") }}" height="40" alt="" class="img-responsive">
                    @else
                        <img src="{{ asset("storage/categories/default-sm.png") }}" height="40" alt="{{ $category->name }}" />
                    @endif
                </td>
                <td>
                    <span class="c_name">{{$category->name}} </span>
                </td>
                <td>{{$category->parent->name}}</td>
                <td>{{$category->products->count()}}</td>
                <td><span class="badge badge-{{$category->status?'success':'danger'}}">{{$category->status?'Enabled':'Disabled'}}</span></td>
                <td>
                    <a class="btn btn-sm btn-info" href="#"><i class="fa fa-info"></i> View</a>
                    <a class="btn btn-sm btn-danger" onclick="DeleteItem('{{$category->name}}', '{{route('admin.categories.destroy', $category->id)}}')" href="#"><i class="fa fa-trash"></i> Delete</a>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>
@section('js')
    <script>
        let baseUrl = '/admin/writers/';
    </script>
    @include("layouts.admin.shared.datatable")
@endsection


