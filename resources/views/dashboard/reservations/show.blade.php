@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" style="width:100px; height:100px" src="{{ $reservation->trip->campaign->thumb_src }}" alt="{{ $reservation->trip->campaign->name }}">
                    </a>
                    <div class="media-body">
                        <h3 class="media-heading">
                            {{ $reservation->trip->campaign->name }}
                            <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                        </h3>
                        <h4>Details</h4>
                    </div>
                </div>
                <br>
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
                    <dd>{{ carbon($reservation->birthday)->age }}</dd>
                    <dt>Group</dt>
                    <dd>{{ $reservation->trip->group->name }}</dd>
                    <dt>Trip Type</dt>
                    <dd>{{ $reservation->trip->type }} Missionary</dd>
                    <dt>Start Date</dt>
                    <dd>{{ carbon($reservation->trip->started_at)->toFormattedDateString() }}</dd>
                    <dt>End Date</dt>
                    <dd>{{ carbon($reservation->trip->ended_at)->toFormattedDateString() }}</dd>
                    <dt>Trip Starts In</dt>
                    <dd>{{ carbon($reservation->trip->started_at)->diffInDays() }} days</dd>
                </dl>

            </div>
        </div>
    </div>
@endsection