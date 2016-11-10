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
            <div class="col-sm-4 text-right">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ url('dashboard/reservations') }}" class="btn icon-left btn-default hidden-xs"><span class="fa fa-chevron-left icon-left"></span> Return To All</a>
                <a href="{{ url('dashboard/reservations') }}" class="btn icon-left btn-default btn-sm btn-block visible-xs"><span class="fa fa-chevron-left icon-left"></span> Return To All</a>
                <hr class="divider inv">
            </div>
        </div>
    </div>
</div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu', [
                'links' => config('navigation.dashboard.reservation'),
                'rep' => $reservation->rep ? $reservation->rep : $reservation->trip->rep
                ])
            </div>
            <div class="col-sm-8">
                @yield('tab')
            </div>
        </div>
    </div>
@stop