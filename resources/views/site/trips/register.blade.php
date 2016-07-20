@extends('site.layouts.default')

@section('content')
<div class="dark-bg-primary">
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">
			<hr class="divider inv xlg" />
			<h1 class="text-center">Let's Go!</h1>
			<h4 class="text-center">Get registered to change the world!</h4>
			<hr class="divider inv xlg" />
		</div>
	</div><!-- end row -->
	<div class="row">
		<div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0">
		    <div class="panel panel-default">
		    	  <div class="panel-heading">
		    			<h5>{{ $trip->country_name }} Trip Registration</h5>
		    	  </div>
		    	  <div class="panel-body">
		                <trip-registration-wizard trip-id="{{ $trip->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></trip-registration-wizard>
		    	  </div>
		    </div><!-- end panel -->
		</div><!-- end col -->
	</div><!-- end row -->
	<hr class="divider inv xlg" />
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection