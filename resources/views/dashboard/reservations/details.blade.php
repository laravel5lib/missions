@extends('dashboard.reservations.show')

@section('tab')
    @include('dashboard.reservations._funding_progress')

    @component('panel')
        @slot('title')
            <h5>Trip Details</h5>
        @endslot
        @component('list-group', ['data' => [
            'Campaign' => $reservation->trip->campaign->name,
            'Country' => country($reservation->trip->campaign->country_code),
            'Team' => $reservation->trip->group->name,
            'Trip Type' => $reservation->trip->type,
            'Start Date' => $reservation->trip->started_at->format('F j, Y'),
            'End Date' => $reservation->trip->ended_at->format('F j, Y')
        ]])
        @endcomponent
    @endcomponent

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

@endsection