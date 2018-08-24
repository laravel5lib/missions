@extends('emails.layouts.default')

@section('content')
    <img style="display:block;max-width:100%;height:auto;border-radius:4px;margin-bottom:50px;" src="<?php echo $message->embed(public_path('images/emails/trip-reservation-banner.jpg')); ?>">

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;text-transform:capitalize;text-align:center;">Hi {{ $reservation->user->name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;line-height:24px;text-align:center;">You have successfully registered <span style="color:#242424;font-weight:bold;">{{ $reservation->name }}</span> for the <span style="color:#242424;font-weight:bold;">{{ $reservation->trip->campaign->name }}</span> trip to <span style="color:#242424;font-weight:bold;">{{ country($reservation->trip->country_code) }}</span>. Some details about your reservation are listed below.</p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;line-height:24px;text-align:center;"><a style="color:#fff;margin:0;padding:15px 25px;background:#f6323e;display:inline-block;text-decoration:none;border-radius:4px;font-weight:bold;margin-bottom:15px;" href="{{ url('/dashboard/reservations/' . $reservation->id) }}">Manage Your Reservation</a></p>
    
    <p style="text-align:center;"><table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;width:100%;">
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
        </tbody>
    </table></p>

    @if($reservation->fund->fundraisers && $reservation->user->url)
        <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;line-height:24px;text-align:center;"><a style="color:#fff;margin:0;padding:15px 25px;background:#f6323e;display:inline-block;text-decoration:none;border-radius:4px;font-weight:bold;margin-bottom:15px;" href="{{ url('@'.$reservation->user->url.'/'.$reservation->fund->fundraisers()->first()->url) }}">Manage Fundraiser</a></p>
    @endif
@stop