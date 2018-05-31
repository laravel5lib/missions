@component('mail::message')
# New Organization Lead

You have a new organization interested in joining the Missions.Me team. Please follow up!

@component('mail::table')
| Overview | |
| :------------------------ | :-------------------- |
| *Organization* | {{ $lead->content['organization'] }} |
| *Type* | {{ $lead->content['type'] }} |
| *Campaign of Interest* | {{ $lead->content['campaign_of_interest'] }} |
| *Contact* | {{ $lead->content['contact'] }} |
| *Position* | {{ $lead->content['position'] }} |
| *Email* | <a href="mailto:{{ $lead->content['email'] }}">{{ $lead->content['email'] }}</a> |
| *Primary Phone* | <a href="tel:{{ $lead->content['phone_one'] }}">{{ $lead->content['phone_one'] }}</a> |
| *Created* | {{ $lead->created_at->format('F j, Y h:i a') }} |
@endcomponent

@component('mail::button', ['url' => url('/admin/leads/' . $lead->uuid), 'color' => 'red'])
View Lead
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "View Lead" button, copy and paste the URL below
into your web browser: [{{ url('/admin/leads/'.$lead->uuid) }}]({{ url('/admin/leads/'.$lead->uuid) }})
@endcomponent

@endcomponent
