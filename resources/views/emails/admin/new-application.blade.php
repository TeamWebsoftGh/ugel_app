@component('mail::message')

# Hello admin!!

##An Application has been submitted successfully by {{$applicant->fullname}}.

An Application with application Number has been submitted pending review and approval.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent