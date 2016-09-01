@extends('emails.layouts.default)

@section('content')

    <p>Hi {{ $facilitator->name }}</p>

    <p>{{ $reservation->name }} registered for the {{ $reservation->trip->campaign->name }} trip to {{ country($reservation->trip->country_code) }}. Some details about the reservation are listed below.</p>

    <table>
        <tbody>
        <tr>
            <td>Name on Reservation:</td>
            <td>{{ $reservation->name }}</td>
        </tr>
        <tr>
            <td>Age:</td>
            <td>{{ $reservation->age }}</td>
        </tr>
        <tr>
            <td>Gender:</td>
            <td>{{ $reservation->gender }}</td>
        </tr>
        <tr>
            <td>Status:</td>
            <td>{{ $reservation->status }}</td>
        </tr>
        <tr>
            <td>Shirt Size:</td>
            <td>{{ $reservation->shirt_size }}</td>
        </tr>
        <tr>
            <td>Travel Group:</td>
            <td>{{ $reservation->trip->group->name }}</td>
        </tr>
        <tr>
            <td>Campaign Registered For:</td>
            <td>{{ $reservation->trip->campaign->name }}</td>
        </tr>
        <tr>
            <td>Trip Type</td>
            <td>{{ ucwords($reservation->trip->type) }} Trip</td>
        </tr>
        </tbody>
    </table>

@stop