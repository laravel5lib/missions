@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Greetings <span style="color:#f6323e;">{{ $referral->attention_to }}</span>,</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">{{ $referral->applicant_name }} is requesting a referral from you so they can participate in a Missions.Me mission trip.</p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Thank you for taking the time to honestly and thoroughly complete this referral.</p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;"><a href="{{ url('/referrals/' . $referral->id) }}">{{ url('/referrals/' . $referral->id) }}</a></p>

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;margin-top:40px;">What is Missions.Me?</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Missions.Me specializes in taking groups around the world on life-changing missions experiences. Through various outreaches and humanitarian efforts, Missions.Me empowers individuals to change the world.</p>

    <p style="font-family:Helvetica, Arial, sans-serif;"><a style="text-decoration:none;background-color:#f6323e;padding:15px 35px;border-radius:4px;color:#fff;font-size:14px;font-weight:bold;" href="https://missions.me/about-mm">Learn More</a></p>

@endsection