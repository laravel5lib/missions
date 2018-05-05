@extends('dashboard.reservations.show')

@section('tab')
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

    @if( $reservation->assignmentsArePublished() )
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Trip Assignments</h5>
        </div>
        <div class="panel-body">
            @if( $reservation->squadsArePublished() )
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
            </div>
            @endif
            @if( $reservation->regionsArePublished() )
            <div class="col-md-6">
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
            @endif
            @if( $reservation->roomsArePublished() )
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
            @endif
        </div>
    </div>
    @endif

    @if( $reservation->transportsArePublished() )
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Travel</h5>
            </div>
            @include('partials.reservations.transportation', ['reservation' => $reservation])
        </div>
    @endif


    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>My Squad</h5>
        </div>

        @if($reservation->squads->count() >= 0 && $reservation->squadsArePublished())
            <div class="panel-body">
                <p>Below is your assigned squad! You'll be with this squad throughout the trip.</p>
            </div>
        @endif

        @if($reservation->squads->count() == 0 || ! $reservation->squadsArePublished())
            <div class="panel-body">
                <p>Come back later! You have not been assigned to a squad yet. </p>
            </div>
        @endif
    </div>

    @unless($reservation->squads->count() == 0 || ! $reservation->squadsArePublished())
        @each('partials.reservations.squad', $reservation->squads->pluck('team.squads')->flatten()->filter(function($squad) {
        return $squad->callsign == 'Squad Leaders';
    })->sortBy('callsign')->all(), 'squad')

        @each('partials.reservations.squad', $reservation->squads->pluck('team.squads')->flatten()->reject(function($squad) {
            return $squad->callsign == 'Squad Leaders';
        })->sortBy('callsign')->all(), 'squad')
    @endunless

@endsection