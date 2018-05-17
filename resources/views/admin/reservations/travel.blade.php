@extends('admin.reservations.show')

@section('tab')

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

<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Travel</h5>
    </div>
    @include('partials.reservations.transportation', ['reservation' => $reservation])
</div>

@endsection