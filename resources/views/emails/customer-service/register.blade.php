@component('mail::message')

# Hi {{$customer->username}}!

Thank you for registering with {{settings('app_name', config('app.name'))}} Our writers are ready to get started on any order you throw their way.
<br/>

{{settings('app_name', config('app.name'))}}  team and I will be monitoring the process of your order to make sure the paper is fully original and on time.

@component('mail::button', ['url' => $url])
    Make an Order
@endcomponent

Login to access all sections of your control panel and dozens of options that will help you along the way.
<br/>
<br/>
Cheers,<br>
{{settings('app_name', config('app.name'))}}
@endcomponent
