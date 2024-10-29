@component('mail::message')

# Hello admin!!

##An Application has been {{$status}} successfully by {{$user->fullname}}.


Thanks,<br>
{{ config('app.name') }} Team
@endcomponent