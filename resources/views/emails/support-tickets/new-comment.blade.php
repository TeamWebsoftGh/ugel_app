@component('mail::message')

# Hello!

#{{$message}}.

Ticket Overview.

## Ticket # : {{ $ticket->ticket_code }}

## Subject : {{ $ticket->subject }}

## Priority : {{ $ticket->priority->name }}

## Note : {{ $ticket->ticket_note }}


Cheers,<br>
{{ settings("app_name") }} Team
@endcomponent
