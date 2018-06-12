@extends('admin.reservations.show')

@section('tab')

@component('panel')
    @slot('title')<h5>Flight Itinerary</h5>@endslot
    @if($reservation->flightItinerary && $reservation->flightItinerary->published)
    <div class="panel-body">
        <div class="alert alert-warning">
            <div class="row">
                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                <div class="col-xs-11">Flight dates, times and locations are subject to change. Please verify all dates, times, and locations with your carrier.</div>
            </div>
        </div>
    </div>
    <div class="list-group">
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Record Locator
                </div>
                <div class="col-xs-8">
                    <strong>
                        <a href="{{ url('/admin/campaigns/'.$reservation->trip->campaign_id.'/itineraries/'.$reservation->flightItinerary->uuid)}}">{{ $reservation->flightItinerary->record_locator }}</a></strong>
                </div>
            </div>
        </div>
        @foreach($reservation->flightItinerary->flights as $index => $flight)
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-4 text-muted text-right">
                        {{ $flight->flightSegment->name }}
                    </div>
                    <div class="col-xs-8 small">
                        <span style="display:block">{{ $flight->date->format('M d, Y')}} - {{ carbon($flight->time)->format('h:i a') }}</span>
                        <span style="display:block">Flight #: {{ $flight->flight_no }}</span>
                        <span style="display:block">{{ airline($flight->flight_no)['name'] }}</span>
                        <span style="display:block">{{ $flight->iata_code }}</span>
                        <span style="display:block">{{ airport($flight->iata_code)['name'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
        @slot('body')<p class="text-muted text-center">No flight itinerary assigned.@endslot
    @endif
@endcomponent

<companion-manager reservation-id="{{ $reservation->id }}"></companion-manager>


<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Trip Assignments</h5>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
            <label>Squad(s)</label>
            <p>
                @forelse($reservation->squads as $squad)
                    <strong>{{ ucwords($squad->team->callsign) }}</strong> &middot; {{ $squad->callsign }}
                @empty
                    Unassigned
                @endforelse
            </p>
            <hr class="divider">
            <label>Region(s)</label>
            <p>
                @forelse($reservation->squads as $squad)
                    {{
                        $squad->team && $squad->team->regions ?
                        implode(',', $squad->team->regions->pluck('name')->all()) :
                        'Unassigned'
                    }}
                @empty
                    Unassigned
                @endforelse
            </p>
            <hr class="divider">
        </div>
        <div class="col-md-6">
            <label>Accommodations</label>
            <p>
                @forelse($reservation->rooms as $room)
                    <strong>{{ ucwords($room->type->name) }}</strong>
                    &middot; {{  implode(',', $room->accommodations->pluck('name')->all()) }}
                @empty
                    Unassigned
                @endforelse
            </p>
            <hr class="divider">
        </div>
    </div>
</div>

@endsection