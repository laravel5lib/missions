@extends('dashboard.reservations.show')

@section('tab')
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