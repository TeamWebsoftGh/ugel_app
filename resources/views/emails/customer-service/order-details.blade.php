@component('mail::message')

# Hello {{$customer->username}}!

Thank you for you order {{$order->name}}.

##Your request has been submitted successfully.

Your request is currently being processed and we will contact you via email once your request meets all necessary requirements and is accepted.


Thank You<br>
Support Team<br/>
{{ config('app.name') }}
@endcomponent
