@extends('emails.layouts.default')

@section('content')
    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hey {{ $reservation->user->name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">The costs associated with {{ $reservation->name }}'s {{ $reservation->trip->campaign->name }} trip to {{ country($reservation->trip->campaign->country_code) }} have been updated.

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;" width="100%">
        <tbody>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;" align="center">
                    Your Fundraising Goal:
                </td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;" align="center">
                ${{ number_format($reservation->getTotalCost(), 2) }}
                </td>
            </tr>
        </tbody>
    </table>

@stop