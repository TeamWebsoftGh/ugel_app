<form method="POST" action="{{route("employee-requests.process")}}">
    @csrf
    <input type="hidden" name="status" id="action">
    <input type="hidden" name="workflow_request_detail" value="@isset($workflow_request_detail){{$workflow_request_detail}}@endisset">
    @include("shared.approver-comments")
    @include("shared.approval-controls")
</form>
<script type="text/javascript" src="{{ asset('/js/general-approval.js') }}"></script>
