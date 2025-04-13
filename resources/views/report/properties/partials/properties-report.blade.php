
<div style="width:100%;display:inline;">
    @if(count($data['properties']) > 0)
        <div style="border: 1px solid black; height: 100%; padding: 5px;">
            @include("shared.export-header")
            <div class="table-responsive">
                <table class="table-bordered" style="width: 100%">
                    <thead>
                    <tr style="background: #153e6f; color: #ffffff!important; padding: 5px">
                        <th style="color: #ffffff">#</th>
                        <th style="color: #ffffff">Property Name</th>
                        <th style="color: #ffffff">Property Code</th>
                        <th style="color: #ffffff">Property Type</th>
                        <th style="color: #ffffff">Property Category</th>
                        <th style="color: #ffffff">Purpose</th>
                        <th style="color: #ffffff">Address</th>
                        <th style="color: #ffffff">Status</th>
                        <th style="color: #ffffff">Date Added</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['properties']  as $task)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$task?->property_name}}</td>
                            <td>{{$task?->property_code}}</td>
                            <td>{{$task?->propertyType->name}}</td>
                            <td>{{$task?->propertyType?->propertyCategory->name}}</td>
                            <td>{{$task->propertyPurpose->name}}</td>
                            <td>{{$task->physical_address}}</td>
                            <td>{{$task->is_active?"Active":"Inactive"}}</td>
                            <td>{{$task->created_at}}</td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">INFORMATION NOT AVAILABLE</h5>
    @endif
</div>
