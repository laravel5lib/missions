@extends('site.layouts.default')

@section('content')
		<div class="container">
	        <h5>{{$campaign->name}}</h5>
	    </div>
        {{--<campaign-groups id="{{ $campaign->id }}"></campaign-groups>--}}
        {{--<group-trips id="9fa518bb-2cdf-4b41-8546-6c6eeaed24b2"></group-trips>--}}
        <group-trip-wrapper campaign-id="{{ $campaign->id }}"></group-trip-wrapper>
@endsection