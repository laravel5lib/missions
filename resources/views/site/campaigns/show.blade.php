@extends('site.layouts.default')

@section('content')
	<div class="dark-bg-primary">
		<div class="container">
			<div class="col-sm-8 text-center">
				<hr class="divider inv sm">
				<hr class="divider inv sm">
				<h4>Join The Campaign. Find a group to travel with and register for a trip.</h4>
				<hr class="divider inv sm">
				<hr class="divider inv sm hidden-xs">
			</div>
			<div class="col-sm-4 text-center">
				<hr class="divider inv sm hidden-xs">
				<a href="{{ url($campaign->slug->url . '/trips') }}" class="btn btn-white-hollow">Register For A Trip</a>
				<hr class="divider inv sm">
			</div>
		</div><!-- end container -->
	</div><!-- end dark-bg-primary -->
	@if($campaign->page_src)
		@include('site/campaigns/partials/' . $campaign->page_src)
	@else
		@include('site/campaigns/partials/_generic')
	@endif
	<div class="dark-bg-primary">
		<div class="content-section">
			<div class="container">
				<div class="col-sm-8 col-sm-offset-2 text-center">
					<h5 class="text-uppercase">Join The Campaign</h5>
					<h3>Find a group to travel with and register for a trip.</h3>
					<hr class="divider inv">
					<a href="{{ url($campaign->slug->url . '/trips') }}" class="btn btn-white-hollow">Register For A Trip</a>
				</div>
			</div><!-- end container -->
		</div><!-- end content-section -->
	</div><!-- end dark-bg-primary -->
@endsection