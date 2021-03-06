@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">New Speaker Request</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Missions.Me has received a new speaker request. Please review the contact's information below and follow up.</p>

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;">
        <tbody>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Name:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['name'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Organization:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['organization'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Email:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['email'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Phone:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['phone_one'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Address 1:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['address_one'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Address 2:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['address_two'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">City:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['city'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">State:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['state'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Zip:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['zip'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Comments/Questions:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['comments'] }}</td>
        </tr>
        </tbody>
    </table>
@endsection