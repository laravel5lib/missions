@component('mail::message')
**Hello {{ $name }}**,

You've been assigned to a squad! You can view your squad anytime online using the button below.

@component('mail::panel')
**Squad assignments are subject to change. You must be fully funded and travel ready to participate.**
@endcomponent

@component('mail::table')
| | |
| :-- | :-------------- |
| **Location:** | {{ $region->name }} |
| **Squad:** | {{ $squad->callsign }} |
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'red'])
View My Squad
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "View My Squad" button, copy and paste the URL below
into your web browser: [{{ $url }}]({{ $url }})
@endcomponent

@endcomponent
