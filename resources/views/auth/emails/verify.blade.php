@component('mail::message')
# Hi {{ $name }},

Thanks for registering with Missions.Me! Before we get started, you need to verify your email address.

@component('mail::button', ['url' => $actionUrl, 'color' => 'red'])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "Verify Email" button, copy and paste the URL below
into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent

@endcomponent
