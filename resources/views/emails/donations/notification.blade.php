@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;text-transform:capitalize;">Hi {{ $recipient->name }},</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;"><span style="color:#3e3e3e;font-weight:bold;">{{ $donation->donor->name }}</span> donated <span style="color:#05ce7b;font-weight:bold;">${{ $donation->amount }}</span> to <span style="color:#3e3e3e;font-weight:bold;">{{ $donation->fund->name }}</span></p>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">You have successfully raised a total of <span style="color:#05ce7b;font-weight:bold;">${{ $donation->fund->balanceInDollars() }}</span></p>

@stop