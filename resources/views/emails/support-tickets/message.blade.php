@component('mail::message')

# Hello user!!

#{{$message}}.

Ticket Overview.

## Ticket # : {{ $ticket->ticket_code }}

## Subject : {{ $ticket->subject }}

## Priority : {{ $ticket->priority->name }}

## Date Created : {{ $ticket->date_created }}

## Employee : {{ $ticket->user->fullname }}

## Responsible : {{ $ticket->assignee_names }}


Cheers,<br>
{{ settings("app_name") }} Team
@endcomponent
