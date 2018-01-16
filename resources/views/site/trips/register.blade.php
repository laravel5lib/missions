@extends('site.layouts.default')

@section('content')
<div class="container">
	<hr class="divider inv md">
	<div class="row">
		<div class="col-xs-12">
			<h2>Trip Registration</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-md-push-9">
			<div class="panel panel-default">
				<div class="panel-body text-center">
					<div class="row">
						<div class="col-md-12 col-sm-4 col-xs-12">
							<img src="{{ image($trip->group->getAvatar()->source) }}" class="img img-responsive">
						</div>
						<div class="col-md-12 col-sm-8 col-xs-12">
							<h4 class="text-uppercase">{{ ucwords($trip->type) }} Trip</h4>
							<h5 class="text-muted"><i class="fa fa-map-marker"></i> {{ country($trip->country_code) }}</h5>
							<hr class="divider inv lg">
							<h5>{{ $trip->group->name }}</h5>
							<p>
								{{ $trip->started_at->format('M d') }} - {{ $trip->ended_at->format('M d') }}
								<br /> {{ $trip->ended_at->format('Y') }}
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9 col-md-pull-3">
			<trip-registration-wizard trip-id="{{ $trip->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></trip-registration-wizard>
		</div>
	</div>
	<hr class="divider inv xlg">
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endsection