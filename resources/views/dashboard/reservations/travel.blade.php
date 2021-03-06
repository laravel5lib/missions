@extends('dashboard.reservations.show')

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
                    {{ $reservation->flightItinerary->record_locator }}
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


<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h5>
                    Companions {{ $reservation->companionReservations->count() }} of {{ $reservation->companion_limit }}
                </h5>
            </div>
            <div class="col-xs-6 text-right tour-step-request">
                <a class="btn btn-primary btn-xs" href="mailto:{{ $rep ? $rep->email : 'go@missions.me' }}?subject=New Companion(s) Request&body=Please list the names, emails and your relationship to of those whom you'd like to be travel companions with. Please remember that you have a limited number of companions available. Thank you! Your trip rep will contact you soon.">
                    <span class="fa fa-group"></span> Request Companions
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        @if( ! $reservation->companionReservations->count())
            <p class="text-center text-muted">No companions found.</p>
        @endif
        <div class="row">
            <div class="col-xs-12">
                @foreach($reservation->companionReservations as $companion)
                    <div class="col-xs-12 panel panel-default">
                        <h5>
                            <img src="{{ image($companion->getAvatar()->source.'?w=50&h=50') }}" 
                                 class="img-circle av-left" 
                                 alt="{{ $companion->name }}">
                            {{ $companion->name }}
                            <small> &middot; <em>{{ ucwords($companion->pivot->relationship) }}</em></small>
                        </h5>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm text-muted">
                    <small><strong><u>DISCLAIMER:</u></strong> There is a limit to the number of travel companions allowed. While we do strive to keep you and your companions together, we <strong><u>cannot guarantee</u></strong> you will all be on the same team, room, or flight as there are many factors that can affect these decisions. Thanks for understanding.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection