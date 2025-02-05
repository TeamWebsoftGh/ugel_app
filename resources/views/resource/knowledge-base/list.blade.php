<div class="">
    <table id="datatable-buttons" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th width="25%">Topic</th>
            <th>Target</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th width="25%">Topic</th>
            <th>Target</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($topics as $topic)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$topic->title}}</td>
                <td>{{$topic->target??"N/A"}}</td>
                <td>{{$topic->CategoryName??"N/A"}}</td>
                <td class="table-action">
                   <a class="btn btn-info btn-sm" href="{{route("resource.knowledge-base.show", $topic->id)}}">View</a>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>
@section('js')
    <script>
        let baseUrl = '/tasks/knowledge-base/';
    </script>
    @include("layouts.shared.datatable")
@endsection


