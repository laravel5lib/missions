@extends('site.layouts.default')

@section('content')
	@component('top-banner', ['class' => 'dark-bg-primary'])
		@if($campaign->ended_at->isFuture())
			@slot('message')
				<h4>Join The Campaign. Find a group to travel with and register for a trip.</h4>
			@endslot
			@slot('cta')
				<a href="{{ url($campaign->slug->url . '/trips') }}" class="btn btn-white-hollow">Register For A Trip</a>
			@endslot
		@else
			@slot('message')
				<h4>This campaign has finished. Looking for more campaigns?</h4>
			@endslot
			@slot('cta')
				<a href="{{ url('/campaigns') }}" class="btn btn-white-hollow">See Current Campaigns</a>
			@endslot
		@endif
	@endcomponent

	@if($campaign->page_src)
		@include('site/campaigns/partials/' . $campaign->page_src)
	@else
		@include('site/campaigns/partials/_generic')
	@endif

	@component('section', ['class' => 'dark-bg-primary'])
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 text-center">
				@if($campaign->ended_at->isFuture())
					<h5 class="text-uppercase">Join The Campaign</h5>
					<h3>Find a group to travel with and register for a trip.</h3>
					<hr class="divider inv">
					<a href="{{ url($campaign->slug->url . '/trips') }}" class="btn btn-white-hollow">Register For A Trip</a>
				@else
					<h5 class="text-uppercase">This Campaign has Finished.</h5>
					<h3>Looking for more campaigns?</h3>
					<hr class="divider inv">
					<a href="{{ url('/campaigns') }}" class="btn btn-white-hollow">See Current Campaigns</a>
				@endif
			</div>
		</div>
	@endcomponent
@endsection