@extends('emails.layouts.default')

@section('content')
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;"><span style="color:#242424;font-weight:bold;">{{ $reservation->name }}</span> registered for the <span style="color:#242424;font-weight:bold;">{{ $reservation->trip->campaign->name }}</span> trip to <span style="color:#242424;font-weight:bold;">{{ country($reservation->trip->country_code) }}</span>. Some details about the reservation are listed below.</p>

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;">
        <tbody>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Name on Reservation</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->name }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Age</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->age }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Gender</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->gender }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Status</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->status }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Shirt Size</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->shirt_size }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Travel Group</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->trip->group->name }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Campaign Registered For</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->trip->campaign->name }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Trip Type</td>
            <td>{{ ucwords($reservation->trip->type) }} Trip</td>
        </tr>
        </tbody>
    </table>

@stop