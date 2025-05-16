@component('mail::message')

# Hello user!!

#{{$message}}.

Maintenance Request Overview.

## Request # : {{ $ticket->ticket_code }}

## Category : {{ $ticket->maintenanceCategory->name }}

## Priority : {{ $ticket->priority->name }}

## Date Created : {{ $ticket->created_at }}

## Client : {{ $ticket->client->fullname }}

## Responsible : {{ $ticket->assignee_names }}


Cheers,<br>
{{ settings("app_name") }} Team
@endcomponent
