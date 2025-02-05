@component('mail::message')

# Hi {{$applicant->fullname}}!

Thank you for considering GAFCSC and taking the first step to submitting an application to GAFCSC!
To complete your application, go to {{route('applicant.login')}} and login with your application Number and Password
used during the registering<br/><br/>

For your reference, this will be your application number throughout the process:
{{$applicant->applicationNumber}}<br/><br/>

<br/><br/>



Sincerely,<br>
{{$applicant->division->name}} Team<br/>
{{ config('app.name') }}
@endcomponent