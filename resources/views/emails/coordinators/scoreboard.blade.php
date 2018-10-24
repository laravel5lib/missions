@component('mail::message')
**Hi there!**

So far, a total of **{{ $reservations }}** missionaries have registered with your group for **{{ $campaign }}**. That's **{{ $percentage }}%** of your commitment to register **{{ $commitment }}** missionaries!

@component('mail::panel')
You're currently ranked **#{{ $rank }}** among other groups with similiar commitment sizes. 
@endcomponent

## Top 5 Groups
@component('mail::table')
| # | Group         | % of Commitment |
|:--|:--------------|----------------:|
@foreach($scores as $index => $score)
| {{ $index + 1 }} | {{ $score['group']['name'] }} | {{ $score['percentage'] }}% |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'red'])
View All Rankings
@endcomponent

Sincerely,<br>
Missions.Me Staff
@endcomponent
