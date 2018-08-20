@extends('dashboard.reservations.show')

@section('tab')

    @include('dashboard.reservations._funding_progress')

    @component('panel')
        @slot('title')
            <div class="row">
                <div class="col-xs-8">
                    <h5>Traveler Details</h5>
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
            'Shirt Size' => shirtSize($reservation->shirt_size),
            'Email' => $reservation->email,
            'Home Phone' => $reservation->phone_one,
            'Mobile Phone' => $reservation->phone_two,
            'Address' => $reservation->address.'<br />'.$reservation->city.', '.$reservation->state.' '.$reservation->zip.'<br />'.country($reservation->country_code)
        ]])
        @endcomponent
    @endcomponent

@endsection