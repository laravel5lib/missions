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
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
            <li role="presentation"><a href="#reservations" aria-controls="profile" role="tab" data-toggle="tab">Reservations</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3 tour-step-navigation">
                            @include('dashboard.groups.trips.tabs.nav')
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            @include('dashboard.groups.trips.tabs.'.($tab === 'reservations' ? 'details' : $tab))
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="reservations">
                    <reservations-list trip-id="{{ $trip->id }}" group-id="{{ $groupId }}" :group-only="true" user-id="{{ Auth::user()->id }}" type="active"></reservations-list>
                </div>
            </div>
    </div>
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Javascript to enable link to tab
        var url = document.location.toString();
        var tab = '{{ $tab }}';
        if (url.match('reservations')) {
            $('.nav-tabs a[href="#reservations"]').tab('show');
        }

        // Change history for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            var newHistory = e.currentTarget.href.match('#reservations')
                ? e.currentTarget.href.replace(tab, 'reservations').split('#')[0]
                : e.currentTarget.href.replace('reservations', tab === 'reservations' ? 'details' : tab).split('#')[0]
            history.pushState('data', '', newHistory);
        })
    </script>
@endsection