@component('mail::message')
#Hello {!! $contactUs->name !!},<br/>

Thank you for getting in touch!

We appreciate you contacting us. We have received your message and will respond as soon as possible.<br/>

Regards,<br>
settings('app_name')
@endcomponent
