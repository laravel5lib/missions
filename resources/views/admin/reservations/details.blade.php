@extends('admin.reservations.show')

@section('tab')

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Traveler Info</h5>
            </div>
            <div class="col-xs-4 text-right">
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Name' => $reservation->name,
        'Gender' => ucwords($reservation->gender),
        'Marital Status' => ucwords($reservation->status),
        'Birthday' => $reservation->birthday->format('F j, Y'),
        'Age' => $reservation->birthday->age,
        'Team Role' => teamRole($reservation->desired_role),
        'Shirt Size' => shirtSize($reservation->shirt_size),
        'Email' => $reservation->email,
        'Home Phone' => $reservation->phone_one,
        'Mobile Phone' => $reservation->phone_two,
        'Address' => $reservation->address.'<br />'.$reservation->city.', '.$reservation->state.' '.$reservation->zip.'<br />'.country($reservation->country_code)
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Trip Details</h5>
            </div>
            <div class="col-xs-4 text-right">
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => $reservation->trip->campaign->name,
        'Country' => country($reservation->trip->campaign->country_code),
        'Group' => $reservation->trip->group->name,
        'Trip Type' => '<a href="'.url('/admin/trips/'. $reservation->trip->id).'">'.ucfirst($reservation->trip->type).'</a>',
        'Start Date' => $reservation->trip->started_at->format('F j, Y'),
        'End Date' => $reservation->trip->ended_at->format('F j, Y')
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Registration Details</h5>
            </div>
            <div class="col-xs-4 text-right">
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Managing Account' => $reservation->user->name,
        'Date Registered' => $reservation->created_at->format('F j, Y h:i a'),
        'Reservation ID' => $reservation->id
    ]])
    @endcomponent
    @slot('footer')
    <div class="text-right">
        <hr class="divider inv sm">
        <send-email label="Resend confirmation email"
                 icon="fa fa-envelope icon-left"
                 classes="btn btn-default btn-sm"
                 command="email:send-reservation-confirmation"
                 :parameters="{id: '{{ $reservation->id }}', email: '{{ $reservation->user->email }}'}">
        </send-email>
    </div>
    @endslot
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Travel</h5>
    </div>
    @include('partials.reservations.transportation', ['reservation' => $reservation])
</div>

@endsection