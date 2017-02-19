@extends('emails.layouts.default')

@section('content')
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;"><span style="color:#242424;font-weight:bold;">{{ $interest->name }}</span> signed up to learn more about <span style="color:#242424;font-weight:bold;">{{ $interest->trip->campaign->name }}</span> trip to <span style="color:#242424;font-weight:bold;">{{ country($interest->trip->country_code) }}</span>. Some details about the interested party are listed below.</p>

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;">
        <tbody>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Name</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $interest->name }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Email</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $interest->email }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Phone</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $interest->phone }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Communication Preferences</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">
                @foreach($interest->communication_preferences as $preference)
                    {{ $preference . ', ' }}
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Sponsoring Group</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $interest->trip->group->name }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Trip Type of Interest</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ ucwords($interest->trip->type) }} Trip</td>
        </tr>
        </tbody>
    </table>

@stop