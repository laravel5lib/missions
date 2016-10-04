@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">{{ $reservation->trip->campaign->name }}
                    <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                </h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
                <dl class="dl-horizontal">
                    <dt>Reservation ID</dt>
                    <dd>{{ $reservation->id }}</dd>
                    <dt>Surname</dt>
                    <dd>{{ $reservation->surname }}</dd>
                    <dt>Given Names</dt>
                    <dd>{{ $reservation->given_names }}</dd>
                    <dt>Gender</dt>
                    <dd>{{ $reservation->gender }}</dd>
                    <dt>Marital Status</dt>
                    <dd>{{ $reservation->status }}</dd>
                    <dt>Shirt Size</dt>
                    <dd>{{ $reservation->shirt_size }}</dd>
                    <dt>Age</dt>
                    <dd>{{ $reservation->birthday->age }}</dd>
                    <dt>Group</dt>
                    <dd>{{ $reservation->trip->group->name }}</dd>
                    <dt>Trip Type</dt>
                    <dd>{{ $reservation->trip->type }} Missionary</dd>
                    <dt>Start Date</dt>
                    <dd>{{ $reservation->trip->started_at->toFormattedDateString() }}</dd>
                    <dt>End Date</dt>
                    <dd>{{ $reservation->trip->ended_at->toFormattedDateString() }}</dd>
                    <dt>Trip Starts In</dt>
                    <dd>{{ $reservation->trip->started_at->diffInDays() }} days</dd>
                </dl>

            </div>
        </div>
    </div>
@endsection