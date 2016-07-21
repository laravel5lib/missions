@extends('site.layouts.default')

@section('content')
<div class="dark-bg-primary">
<div class="container">
	<trip-registration-wizard trip-id="{{ $trip->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></trip-registration-wizard>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection