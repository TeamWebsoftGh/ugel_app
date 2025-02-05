@component('mail::message')

# Hello {{$employee->fullname}}!

Your {{$wf_type->name}} has been {{$wf_request_detail->status}} by {{$implementor->fullname}}.

Thank You<br>
ESS<br/>
@endcomponent
