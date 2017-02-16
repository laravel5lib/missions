@extends('site.layouts.default')

@section('content')
<div class="dark-bg-primary">
<div class="container">
	<div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-0">
		<hr class="divider inv xlg">
		<h1 class="text-center">Let's Go!</h1>
		<h4 class="text-center">Register to start changing the world!</h4>
		<hr class="divider inv xlg">
		<trip-registration-wizard trip-id="{{ $trip->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></trip-registration-wizard>
		<hr class="divider inv xlg">
	</div>
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection