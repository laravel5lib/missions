@extends('emails.layouts.default')

@section('content')

    <p>Hi {{ $recipient->name }}</p>

    <p>{{ $donation->donor->name }} donated ${{ $donation->amount }} to {{ $donation->fund->name }}</p>

    <p>You have successfully raised a total of ${{ $donation->fund->balance }}</p>

    <p><small>All Missions.Me donations are considered 501(c)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.</small></p>

@stop