@component('mail::message')

# Hello {{$employee->fullname}}!

Your {{$wf_type->name}} has been submitted successfully. It is pending the approval of {{$implementor->fullname}}.

Thank You<br>
ESS<br/>
@endcomponent
