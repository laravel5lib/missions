@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">{{ $reservation->trip->campaign->name }}
                    <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                </h3>
                <div class="visible-xs text-center">
                    <hr class="divider inv">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                    <h4>{{ $reservation->trip->campaign->name }}</h4>
                    <h6 class="text-uppercase">{{ country($reservation->trip->campaign->country_code) }}</h6>
                </div>
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
                <div class="row">
                    @foreach($reservation->fundraisers as $fundraiser)
                    <div class="col-sm-12 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ $fundraiser->name }}
                                <span class="pull-right">Ends: {{ carbon($fundraiser->expires_at)->toFormattedDateString() }}</span>
                            </div>
                            <div class="panel-body">
                                <p>{{ $fundraiser->description or 'No Description'}}</p>
                                <h6>
                                    Amount Raised
                                    <span class="pull-right">Goal: ${{ number_format($fundraiser->goal_amount, 2) }}</span>
                                </h6>
                                <div class="progress">
                                    <?php $fundraiser->raised_percent = (($fundraiser->raised() /100) > $fundraiser->goal_amount) ? 100 : $fundraiser->raised() /100 ?>
                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $fundraiser->raised_percent }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: {{ $fundraiser->raised_percent }}%;">
                                        ${{ number_format($fundraiser->raised() / 100, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <a href="{{ url()->current() . '/' . $fundraiser->uuid . '/donations' }}" class="btn btn-sm btn-block btn-primary">View Donations</a>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection