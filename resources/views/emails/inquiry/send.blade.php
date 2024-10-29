@component('mail::message')

# Inquiry Message from the website

## Name : {{ $contactUs->name }}

## Email : {{ $contactUs->email }}

## Subject : {{ $contactUs->title }}

## Message : {{ $contactUs->message }}

Thanks,<br>
{{  settings('app_name') }} Team
@endcomponent
