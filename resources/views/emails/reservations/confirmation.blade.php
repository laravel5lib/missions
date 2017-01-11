@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hi {{ $reservation->user->name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">You have successfully registered <span style="color:#242424;font-weight:bold;">{{ $reservation->name }}</span> for the <span style="color:#242424;font-weight:bold;">{{ $reservation->trip->campaign->name }}</span> trip to <span style="color:#242424;font-weight:bold;">{{ country($reservation->trip->country_code) }}</span>. Some details about your reservation are listed below. You can <a style="color:#f6323e;text-decoration:none;" href="{{ url('/dashboard/reservations/' . $reservation->id) }}">manage your reservation here.</a></p>
    
    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;">
        <tbody>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Name on Reservation:</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->name }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Travel Group:</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->trip->group->name }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Campaign Registered For:</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $reservation->trip->campaign->name }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Trip Type</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ ucwords($reservation->trip->type) }} Trip</td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Trip Dates</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">
                    {{ $reservation->trip->started_at->format('F j') }} -
                    {{ $reservation->trip->ended_at->format('F j, Y') }}
                </td>
            </tr>
            @if($reservation->requirements->count())
                <tr>
                    <td style="font-size:10px;letter-spacing:2px;text-transform:uppercase;border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;text-align:center;" colspan="2">Travel Documents Required</td>
                </tr>
                @foreach($reservation->requirements as $requirement)
                    <tr>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">
                            {{ $requirement->requirement->name }}
                        </td>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">
                            {{ $requirement->requirement->due_at->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
            @if($reservation->deadlines->count())
                <tr>
                    <td style="font-size:10px;letter-spacing:2px;text-transform:uppercase;border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;text-align:center;" colspan="2">Other Deadlines</td>
                </tr>
                @foreach($reservation->deadlines as $deadline)
                    <tr>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">
                            {{ $deadline->name }}
                        </td>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">
                            {{ $deadline->date->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
            @if($reservation->dues->count())
                <tr>
                    <td style="font-size:10px;letter-spacing:2px;text-transform:uppercase;border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;text-align:center;" colspan="2">Payments Due</td>
                </tr>
                @foreach($reservation->dues as $payment)
                    <tr>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">
                            ${{ number_format($payment->outstanding_balance, 2) }} - {{ $payment->payment->percent_owed }}% {{ $payment->payment->cost->name }}
                        </td>
                        <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">
                            {{ $payment->due_at->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    @if($reservation->fund->fundraisers && $reservation->user->url)
        <p><a href="{{ url('@'.$reservation->user->url.'/'.$reservation->fund->fundraisers()->first()->url) }}">Manage Fundraiser</a></p>
    @endif
@stop