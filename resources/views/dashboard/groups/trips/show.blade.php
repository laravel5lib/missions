@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs text-capitalize">
                    <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}">
                    {{ $trip->campaign->name }} <small>&middot; Trip Details</small>
                </h3>
                <div class="visible-xs text-center">
                    <hr class="divider inv">
                    <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}">
                    <h4 class="text-capitalize">{{ $trip->campaign->name }}</h4>
                    <label>Trip Details</label>
                </div>
            </div>
            <div class="col-sm-4 hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="/dashboard/groups/{{ $group->id }}" class="btn btn-primary pull-right">
                    Group Details
                </a>
            </div>
            <div class="col-sm-4 visible-xs text-center">
                <hr class="divider inv">
                <a href="/dashboard/groups/{{ $group->id }}" class="btn btn-primary">
                    Group Details
                </a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            @include('dashboard.groups.trips.tabs.nav')
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            @include('dashboard.groups.trips.tabs.'.$tab)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endsection