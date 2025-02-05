@component('mail::message')

Hello {{$user->name}}!!

A user account has been created for you or your login details has been updated.
Go to {{$url}} and login with the credentials below.<br/><br/>

Username: {{$user->email??$user->username}}<br/>
Password: {{$user->password}}<br/><br/>


Cheers,<br>
{{ settings('app_name') }}
@endcomponent
