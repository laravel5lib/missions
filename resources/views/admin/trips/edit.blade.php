@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-trip-create-update trip-id="{{ $tripId }}" campaign-id="{{ $trip->campaign->id }}" country-code="{{ $trip->campaign->country_code }}" :is-update="true"></admin-trip-create-update>
            </div>
        </div>
    </div>
@endsection