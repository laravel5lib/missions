@extends('emails.layouts.default')

@section('content')

    <p>Hi {{ $donation->donor->name }}</p>

    <p>Thank you for your generous donation of ${{ $donation->amount }} to {{ $donation->fund->name }}</p>

    <p><small>All Missions.Me donations are considered 501(c)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.</small></p>

@stop