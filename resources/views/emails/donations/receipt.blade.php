@extends('emails.layouts.default')

@section('content')
	<h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hey {{ $donation->donor->name }},</h3>

	<p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Thank you for your generous donation of <span style="color:#05ce7b;font-weight:bold;">${{ $donation->amount }}</span> to <span style="color:#3e3e3e;font-weight:bold;">{{ $donation->fund->name }}</span></p>

@stop