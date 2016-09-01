@extends('emails.layouts.default')

@section('content')

    <p>Hi {{ $reservation->user->name }}</p>

    <p>You have successfully registered {{ $reservation->name }} for the {{ $reservation->trip->campaign->name }} trip to {{ country($reservation->trip->country_code) }}. Some details about your reservation are listed below. You can <a href="{{ url('/dashboard/reservations/' . $reservation->id) }}">manage your reservation here.</a></p>

    <table>
        <tbody>
            <tr>
                <td>Name on Reservation:</td>
                <td>{{ $reservation->name }}</td>
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
            <tr>
                <td>Trip Dates</td>
                <td>
                    {{ $reservation->trip->started_at->format('F j') }} -
                    {{ $reservation->trip->ended_at->format('F j, Y') }}
                </td>
            </tr>
            @if($reservation->requirements->count())
                <tr>
                    <td colspan="2">Travel Documents Required</td>
                </tr>
                @foreach($reservation->requirements as $requirement)
                    <tr>
                        <td>
                            {{ $requirement->item }}
                        </td>
                        <td>
                            {{ $requirement->due_at->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
            @if($reservation->deadlines->count())
                <tr>
                    <td colspan="2">Other Deadlines</td>
                </tr>
                @foreach($reservation->deadlines as $deadline)
                    <tr>
                        <td>
                            {{ $deadline->name }}
                        </td>
                        <td>
                            {{ $deadline->date->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
            @if($reservation->dues->count())
                <tr>
                    <td colspan="2">Payments Due</td>
                </tr>
                @foreach($reservation->dues as $payment)
                    <tr>
                        <td>
                            {{ $payment->outstanding_balance }}
                        </td>
                        <td>
                            {{ $payment->due_at->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

@stop