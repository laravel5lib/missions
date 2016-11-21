@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
        	<div class="col-sm-12">
	            <h3>Create A Trip</h3>
	        </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-trip-create-update campaign-id="{{ $campaign->id }}" trip-id="{{ $tripId or '' }}" country-code="{{ $campaign->country_code }}"></admin-trip-create-update>
                {{--<campaign-trip-create-wizard campaign-id="{{ $campaign->id }}" country-code="{{ $campaign->country_code }}"></campaign-trip-create-wizard>--}}
            </div>
        </div>
    </div>
@endsection