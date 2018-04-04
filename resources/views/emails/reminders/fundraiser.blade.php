@component('mail::message')
# Just A Friendly Reminder!

Don't miss out on the opportunity to be part of life change! You've asked us to remind you that the fundraiser, *{{ $fundraiser->name }}*, is about to end. Here's the thing, it's still needing some help to hit it's goal!

Take action and donate before the fundraiser closes! 

@component('mail::button', ['url' => url($fundraiser->url), 'color' => 'red'])
Donate Now
@endcomponent

Sharing a fundraiser can be a huge help towards reaching goals. Help by simply sharing!

@component('mail::button', ['url' => url($fundraiser->url), 'color' => 'grey'])
Share Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "View Fundraiser" button, copy and paste the URL below
into your web browser: [{{ url($fundraiser->url) }}]({{ url($fundraiser->url) }})
@endcomponent

@endcomponent
