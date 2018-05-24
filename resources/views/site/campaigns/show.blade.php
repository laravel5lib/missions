@extends('site.layouts.default')

@section('content')
	@component('top-banner', ['class' => 'dark-bg-primary'])
		@if($campaign->ended_at->isFuture())
			@slot('message')
				<h4>Find a team to travel with and register for the trip.</h4>
			@endslot
			@slot('cta')
				<a role="button" class="btn btn-white-hollow" data-toggle="modal" data-target="#confirm">Register for this Trip</a>
			@endslot
		@else
			@slot('message')
				<h4>This trip has finished. Looking for more trips?</h4>
			@endslot
			@slot('cta')
				<a href="{{ url('/trips') }}" class="btn btn-white-hollow" >See Current Trips</a>
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
					<h5 class="text-uppercase">Let's Change the World</h5>
					<h3>Find a team to travel with and register for the trip.</h3>
					<hr class="divider inv">
					<a role="button" data-toggle="modal" data-target="#confirm" class="btn btn-white-hollow">Register for this Trip</a>
				@else
					<h5 class="text-uppercase">This Trip has Finished.</h5>
					<h3>Looking for more trips?</h3>
					<hr class="divider inv">
					<a href="{{ url('/trips') }}" class="btn btn-white-hollow">See Current Trips</a>
				@endif
			</div>
		</div>
	@endcomponent


<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Please confirm to continue</h4>
      </div>
      <div class="modal-body text-center">
        <h4 id="myModalLabel">Are you traveling with a team?</h4>
      </div>
      <div class="modal-footer">
        <a href="{{ url($campaign->slug->url).'/teams/'.$defaultGroup->id.'/trips' }}" class="btn btn-link">No</a>
        <a href="{{ url($campaign->slug->url . '/teams') }}" class="btn btn-primary">Yes</a>
      </div>
    </div>
  </div>
</div>

@endsection