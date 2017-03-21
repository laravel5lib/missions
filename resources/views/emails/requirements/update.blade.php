@extends('emails.layouts.default')

@section('content')
    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hey {{ $recipient_name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">The status of {{ $reservation_name }}'s <strong style="font-size:18px;font-weight:bold;">{{ $requirement }} Travel Requirement</strong> for {{ strtolower($gender) == 'male' ? 'his' : 'her' }} trip to {{ $country }} has changed.

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;" width="100%">
        <tbody>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;" align="center">
                    Current Status:
                </td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;" align="center">
                @if($status == 'reviewing')
                    <h1 style="color:#808080;font-weight:bold;">Under Review</h1>
                @elseif($status == 'complete')
                    <h1 style="color:#05ce7b;font-weight:bold;">Complete</h1>
                @elseif($status == 'attention')
                    <h1 style="color:#0097cd;font-weight:bold;">Needs Attention</h1>
                @else
                    <h1 style="color:#d8262e;font-weight:bold;">Incomplete</h1>
                @endif
                </td>
            </tr>
        </tbody>
    </table>

@stop