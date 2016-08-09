@extends('site.layouts.default')

@section('content')
<div class="dark-bg-primary">
    <div class="container">
        <hr class="divider inv xlg">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-capitalize"><img class="img-circle img-sm av-left" src="{{ image($trip->group->avatar->source . '?w=100') }}">{{ $trip->group->name }} <span class="hidden-xs small text-white text-capitalize">&middot; {{ $trip->type }}</span></h1>
            </div>
        </div>
        <hr class="divider inv xlg">
    </div>
</div>
<div class="container">
    <hr class="divider inv xlg hidden-xs">
    <hr class="divider inv lg visible-xs">
    <div class="row">
        <div class="visible-xs">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 text-center">
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">Read Details</a>
                <hr class="divider inv xlg">
            </div>
            <div id="collapseDetails" class="collapse">
                <div class="col-xs-12">

                    {{ $trip->description }}

                    <hr class="divider inv" />

                    @if($trip->requirements->count())
                        @include('site.trips.partials._requirements', ['trip' => $trip])
                    @endif

                    @if($trip->deadlines->count())
                        @include('site.trips.partials._deadlines', ['trip' => $trip])
                    @endif

                    <h4>Missionaries Registered</h4>
                    <hr class="divider">
                    <div class="row">
                        @each('site.trips.partials._missionaries', $trip->reservations, 'res', 'site.trips.partials._no_missionaries')
                    </div>
                </div><!-- end col -->
            </div><!-- end collapse -->
        </div><!-- end visible-xs -->
        <div class="col-sm-7 col-md-7 col-lg-8 hidden-xs">

            {{ $trip->description }}

            <hr class="divider inv" />

            @if($trip->requirements->count())
                @include('site.trips.partials._requirements', ['trip' => $trip])
            @endif

            @if($trip->deadlines->count())
                @include('site.trips.partials._deadlines', ['trip' => $trip])
            @endif

            <h4>Missionaries Registered</h4>
            <hr class="divider">
            <div class="row">
                @each('site.trips.partials._missionaries', $trip->reservations, 'res', 'site.trips.partials._no_missionaries')
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
            <div class="panel panel-default">
            	<div class="panel-body">

                    @unless($trip->closed_at->isPast())
                        <a href="/trips/{{ $trip->id }}/register" class="btn btn-info btn-lg btn-block">Register Now</a>
                    @endunless

                    <hr class="divider lg">
                    <h6 class="text-center text-uppercase small text-muted">Start Date</h6>
                    <h4 class="text-center">{{ date('F d, Y', strtotime($trip->started_at)) }}</h4>
                    <hr class="divider inv">
                    <h6 class="text-center text-uppercase small text-muted">End Date</h6>
                    <h4 class="text-center">{{ date('F d, Y', strtotime($trip->ended_at)) }}</h4>
                    <hr class="divider lg">

                    <h6 class="text-uppercase text-center">
                        <img class="img-xs av-left"
                             src="../images/why-mm/{{ strtolower(str_replace(' ', '', $trip->difficulty)) }}.png"
                             alt="{{ $trip->difficulty }}"
                        >
                        Difficulty
                    </h6>
                    <hr class="divider lg">
                    <ul class="list-group">
                        @foreach($trip->activeCosts as $cost)
                            <a   class="list-group-item">
                                <h5 class="list-group-item-heading">{{ $cost->name }}</h5>
                                <p class="list-group-item-text">${{ number_format($cost->amount, 2, '.', ',') }}</p>
                            </a>
                        @endforeach
                    </ul>
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection