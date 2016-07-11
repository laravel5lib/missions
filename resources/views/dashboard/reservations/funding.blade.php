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
                        <h4>Fundraisers</h4>
                    </div>
                </div>
                <br>
                <div class="row">
                    @foreach($reservation->costs as $cost)
                    <div class="col-sm-12 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ $cost->name }}
                                <span class="pull-right">Ends: {{ carbon($cost->expires_at)->toFormattedDateString() }}</span>
                            </div>
                            <div class="panel-body">
                                {{ $cost }}
                                {{--{{ $cost->description or 'No Description'}}--}}
                            </div>
                            <div class="panel-footer">
                                <h6>
                                    Amount Raised
                                    <span class="pull-right">Goal: ${{ number_format($cost->goal_amount, 2) }}</span>
                                </h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 50%;">
                                        50%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection