@component('mail::message')
**Hello {{ $name }}**,

Your flight itinerary is now available! You can view it anytime online using the button below. We'll do our best to notify you of changes, but please always be sure to double-check flight dates, times, and numbers with the airline carrier as these details are subject to change.

@component('mail::panel')
**This is NOT a boarding pass or eTicket. You must obtain your boarding pass from your airline carrier.**
@endcomponent

@component('mail::table')
| Record Locator |
| :------------------------------- |
| {{ $itinerary->record_locator }} |
@endcomponent

@foreach($itinerary->flights as $flight)
@component('mail::table')
| {{ $flight->flightSegment->name }} | |
| :-- | :-------------- |
@if(airline($flight->flight_no)['name'])
| Airline | {{ airline($flight->flight_no)['name'] }} |
@endif
| Flight No. | {{ $flight->flight_no }} |
| City | {{ $flight->iata_code }} |
| Date | {{ $flight->date->format('F j, Y') }} |
| Local Time | {{ carbon($flight->time)->format('h:i a') }} |
@endcomponent
@endforeach

@component('mail::button', ['url' => $url, 'color' => 'red'])
View Flight Itinerary
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "View Flight Itinerary" button, copy and paste the URL below
into your web browser: [{{ $url }}]({{ $url }})
@endcomponent

@endcomponent
