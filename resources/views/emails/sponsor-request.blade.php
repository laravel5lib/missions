@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">New Project Request</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Missions.Me has received a new project request. Please review the contact's information below and follow up.</p>

    <table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;">
        <tbody>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Project Name:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['project_name'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Name:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['name'] }}</td>
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
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Completion Date:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['complete_at'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Cause:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['cause'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Initiative:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['initiative'] }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;">Total:</td>
            <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;">{{ $data['total'] }}</td>
        </tr>
        </tbody>
    </table>
@endsection