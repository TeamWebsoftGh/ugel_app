@component('mail::message')
# Congratulations! You have earned the {{$certificate->course_name}} Certificate.

Program: {{$certificate->course_name}}<br/>
Duration: {{$certificate->duration}}<br/><br/>

@component('mail::button', ['url' => route('login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
