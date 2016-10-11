@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">{{ $reservation->trip->campaign->name }}
                    <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                </h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ url('dashboard/reservations') }}" class="btn icon-left btn-default"><span class="fa fa-chevron-left icon-left"></span> Return To All</a>
                <hr class="divider inv">
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Reservation Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <label>Reservation ID</label>
                            <p>{{ $reservation->id }}</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Surname</label>
                                    <p>{{ $reservation->surname }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Given Names</label>
                                    <p>{{ $reservation->given_names }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Gender</label>
                                    <p>{{ $reservation->gender }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Marital Status</label>
                                    <p>{{ $reservation->status }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Shirt Size</label>
                                    <p>{{ $reservation->shirt_size }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Age</label>
                                    <p>{{ $reservation->birthday->age }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 panel panel-default panel-body text-center">
                            <label>Group</label>
                            <p>{{ $reservation->trip->group->name }}</p>
                            <label>Trip Type</label>
                            <p>{{ $reservation->trip->type }} Missionary</p>
                            <label>Start Date</label>
                            <p>{{ $reservation->trip->started_at->toFormattedDateString() }}</p>
                            <label>End Date</label>
                            <p>{{ $reservation->trip->ended_at->toFormattedDateString() }}</p>
                            <label>Trip Starts In</label>
                            <p>{{ $reservation->trip->started_at->diffInDays() }} days</p>
                        </div><!-- end col -->
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
@endsection