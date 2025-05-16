<div class="">
    <table id="datatable-buttons" class="table dt-responsive">
        <thead>
        <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Message</th>
            <th class="text-center">Date Created</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Message</th>
            <th class="text-center">Date Created</th>
            <th>Actions</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($notifications as $notification)
            <tr @if($notification['read_at'] != null)class="text-muted" @endif>
                <td>{{$i++}}</td>
                <td>{{$notification['title']}}</td>
                <td>{{$notification['message']}}</td>
                <td><span>{{$notification['created_at']}}</span></td>
                <td class="row-actions"><a href="#" class="btn btn-sm- btn-info"><i class="las la-readme"></i>Mark as read</a></td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>
@section('js')
    @include("layouts.shared.datatable")
    <script>
        let baseUrl = '/tasks/writers/';
        function ChangeOrderStatus(name, status, url)
        {
            let ActiveOrInactive="";
            let selIcon = "";
            let order = "";

            if(status == 1){
                ActiveOrInactive = "confirm"; selIcon="<i class='fa fa-ban'></i>";
                order = ''
            }
            else{
                ActiveOrInactive = "reject"; selIcon="<i class='fa fa-caret-square-o-right'></i>";
                order = ''
            }

            if(url)
            {
                bootbox.confirm("<h4>"+ActiveOrInactive.toUpperCase()+"</h4><hr/><div> This action will "+ ActiveOrInactive + " order request for " + name.toUpperCase() + "</div> Are you sure you want to <b><span style='color:blue'>"+ ActiveOrInactive +" order for </span> " + name.toUpperCase() + "</b></div>", function (result) {
                    if (result === true) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: ({_token:token,status:order}),
                            timeout:60000,
                            datatype: "json",
                            cache: false,
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                            },
                            success: function (data) {
                                bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                                    window.location.reload();
                                });
                            },
                        });
                    }
                    else {

                    }
                });
            }else{
                alert("No item selected.")
            }
        }
    </script>
@endsection


