@component('mail::message')

# Hello {{$implementor->fullname}}!

{{$wf_type->name}} from {{$employee->fullname}} is pending your approval. Kindly login to ESS and approve it.

Thank You<br>
ESS<br/>
@endcomponent
