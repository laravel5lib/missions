@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>Reservations <small>Details</small></h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <div class="btn-group pull-right">
                    <a href="/admin/trips/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ $reservation->id }}/edit">Edit</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
        {{--<div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ url('dashboard/reservations') }}" class="btn btn-block btn-default"><span class="fa fa-chevron-left icon-left"></span> My Reservations</a>
                </div>
                <ul class="list-group">
                    <a class="list-group-item " href="{{ url('dashboard/reservations', [$reservation->id, 'deadlines']) }}">Due Dates</a>
                </ul>
            </div><!-- end panel -->
            --@include('dashboard.reservations.layouts.menu')--
        </div>--}}
        <div class="col-sm-8">
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                </a>
                <div class="media-body">
                    <h3 class="media-heading">
                        {{ $reservation->trip->campaign->name }}
                        <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                    </h3>
                    <h4>
                        Details

                    </h4>
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