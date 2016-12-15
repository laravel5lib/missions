@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Dear {{ $referral->referral_name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">{{ $referral->name }} is requesting a referral from you so they can participate in a Missions.Me mission trip.</p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Thank you for taking the time to honestly and thoroughly complete this referral.</p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;"><a href="{{ url('/referrals/' . $referral->id) }}">{{ url('/referrals/' . $referral->id) }}</a></p>

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">What is Missions.Me?</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">{ content and link to about us page }</p>

@endsection