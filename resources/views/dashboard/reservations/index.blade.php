@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">User Reservations</h1>
            </div>
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#active" data-toggle="pill"><i class="fa fa-bolt"></i> Active</a>
                    </li>
                    <li role="presentation">
                        <a href="#archive" data-toggle="pill"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="active">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name (Surname, Given Names)</th>
                                <th>Country</th>
                                <th>Group</th>
                                <th>Trip Type</th>
                                <th>Campaign Name</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->reservations as $reservation)
                                @if ($reservation->trip->ended_at->gt(now()))
                                    <tr>
                                        <td>{{ $reservation->surname }}, {{ $reservation->given_names }}</td>
                                        <td>{{ country($reservation->trip->campaign->country_code) }}</td>
                                        <td>{{ $reservation->trip->group->name }}</td>
                                        <td>{{ $reservation->trip->type }} Missionary</td>
                                        <td>{{ $reservation->trip->campaign->name }}</td>
                                        <td><a href="/dashboard/reservations/{{ $reservation->id}}">View</a></td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="archive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name (Surname, Given Names)</th>
                                <th>Country</th>
                                <th>Group</th>
                                <th>Trip Type</th>
                                <th>Campaign Name</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->reservations as $reservation)
                                @if ($reservation->trip->ended_at->lt(now()))
                                    <tr>
                                        <td>{{ $reservation->surname }}, {{ $reservation->given_names }}</td>
                                        <td>{{ country($reservation->trip->campaign->country_code) }}</td>
                                        <td>{{ $reservation->trip->group->name }}</td>
                                        <td>{{ $reservation->trip->type }} Missionary</td>
                                        <td>{{ $reservation->trip->campaign->name }}</td>
                                        <td><a href="/dashboard/reservations/{{ $reservation->id}}">View</a></td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection