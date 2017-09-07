@extends('site.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs text-capitalize">
                    <img class="img-circle img-sm av-left" src="{{ image($trip->group->getAvatar()->source . '?w=100') }}">{{ $trip->group->name }} <small>&middot; {{ $trip->type }}</small>
                </h3>
                <div class="visible-xs text-center text-capitalize">
                    <hr class="divider inv">
                    <img class="img-circle img-sm" src="{{ image($trip->group->getAvatar()->source . '?w=100') }}">
                    <h4 style="margin-bottom:0;">{{ $trip->group->name }}</h4>
                    <label>{{ $trip->type }}</label>
                    <hr class="divider inv">
                </div>
            </div><!-- end col -->
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ url($trip->campaign->slug->url.'/trips') }}" class="btn btn-default">
                    <i class="fa fa-chevron-left icon-left"></i> More Options
                </a>
            </div>
            <div class="col-xs-12 text-center visible-xs">
                <a href="{{ url($trip->campaign->slug->url.'/trips') }}" class="btn btn-default">
                    <i class="fa fa-chevron-left icon-left"></i> More Options
                </a>
                <hr class="divider inv">
            </div>
        </div>
    </div>
</div>
<div class="container">
    <hr class="divider inv lg">
    <div class="row">
        <div class="visible-xs">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 text-center">
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">Read Details</a>
                <hr class="divider inv lg">
            </div>
            <div id="collapseDetails" class="collapse">
                <div class="col-xs-12">
                    {!! Markdown::convertToHtml($trip->description) !!}
                    <hr class="divider inv" />
                </div><!-- end col -->
            </div><!-- end collapse -->
        </div><!-- end visible-xs -->
        <div class="col-sm-7 col-md-7 col-lg-8 hidden-xs">

            {!! Markdown::convertToHtml($trip->description) !!}

            <hr class="divider inv" />

        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
            <div class="panel panel-default">
            	<div class="panel-body">

                    @if($trip->status <> 'active')
                        <btn class="btn btn-default btn-lg btn-block" disabled>Registration Closed</btn>
                    @else
                        <a href="/trips/{{ $trip->id }}/register" class="btn btn-info btn-lg btn-block">Register Now</a>
                    @endif

                    @unless($trip->reservations->count() < 2)
                        <h5 class="text-center">
                            <small>{{ $trip->reservations->count() }} people have registered for this trip.</small>
                        </h5>
                    @endunless

                    <hr class="divider lg">
                    <h6 class="text-center text-uppercase small text-muted">Start Date</h6>
                    <h4 class="text-center">{{ $trip->started_at->format('F d, Y') }}</h4>
                    <hr class="divider inv">
                    <h6 class="text-center text-uppercase small text-muted">End Date</h6>
                    <h4 class="text-center">{{ $trip->ended_at->format('F d, Y') }}</h4>
                    <hr class="divider lg">

                    <h6 class="text-uppercase text-center">
                        <img class="img-xs img-circle av-left"
                             src="../images/why-mm/{{ strtolower(str_replace(' ', '', $trip->difficulty)) }}.png"
                             alt="{{ $trip->difficulty }}"
                        >
                        Difficulty
                    </h6>
                    <hr class="divider lg">
                    <h6 class="text-center text-uppercase small text-muted">Starts At</h6>
                    <h4 class="text-center">${{  number_format($trip->startingCostInDollars(), 2, '.', ',') }}</h4>
            	</div>
            </div>
            @if($trip->requirements->count())
                @include('site.trips.partials._requirements', ['trip' => $trip])
            @endif

            @if($trip->deadlines->count())
                @include('site.trips.partials._deadlines', ['trip' => $trip])
            @endif
        </div>
    </div>
    <hr class="divider inv lg">
</div>
@endsection