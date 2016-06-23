@extends('site.layouts.default')

@section('content')
<div class="container">
    <div class="panel panel-default">
    	  <div class="panel-heading">
    			<h3 class="panel-title">{{ $trip->country_name }} Trip Registration</h3>
    	  </div>
    	  <div class="panel-body">
                <trip-registration-wizard trip-id="{{ $trip->id }}"></trip-registration-wizard>
    	  </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection