@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container" v-tour-guide="">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($group->getAvatar()->source) }}"> {{ $group->name }} <small>&middot; Group</small>
                </h3>
                <div class="visible-xs text-center">
                    <hr class="divider inv">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($group->getAvatar()->source) }}">
                    <h4 style="margin-bottom:0;">{{ $group->name }}</h4>
                    <label>Group</label>
                </div>
            </div>
            <div class="col-sm-4 hidden-xs tour-step-settings">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ $group->id }}/edit" class="btn btn-primary pull-right">
                    Group Settings
                </a>
            </div>
            <div class="col-sm-4 visible-xs text-center tour-step-settings">
                <hr class="divider inv sm">
                <a href="{{ $group->id }}/edit" class="btn btn-primary">
                    Group Settings
                </a>
                <hr class="divider inv">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="panel panel-default">
                	  <div class="panel-heading">
                			<h5>{{ $group->name }} <small>&middot; Details</small></h5>
                	  </div>
                	  <div class="panel-body">
                			<div class="col-sm-8">
                                <label>Description</label>
                                <p>{{ $group->description }}</p>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-sm-6 text-center">
                                        <label>Type</label>
                                        <p>{{ $group->type }}</p>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        <label>Status</label>
                                        <p>{{ $group->public ? 'Public': 'Private' }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <div class="well">
                                            <label>Url slug</label>
                                            @if($group->public)
                                                <h4 class="hidden-xs"><a href="/{{ $group->slug->url }}">http://missions.me/{{ $group->slug->url }}</a></h4>
                                                <p class="visible-xs"><a href="/{{ $group->slug->url }}">http://missions.me/{{ $group->slug->url }}</a></p>
                                            @else
                                                <h4 class="text-strike text-muted hidden-xs">http://missions.me/{{ $group->slug->url }}</h4>
                                                <p class="text-strike text-muted visible-xs">http://missions.me/{{ $group->slug->url }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 panel panel-default">
                                <div class="panel-body">
                                    <label>Phone 1</label>
                                    <p>{{ $group->phone_one }}</p>
                                    <label>Phone 2</label>
                                    <p>{{ $group->phone_two or 'Not Available' }}</p>
                                    <label>Email Address</label>
                                    <p>{{ $group->email or 'Not Available' }}</p>
                                    <label>Address</label>
                                    <p>
                                        {{ $group->address_one or 'Not Available' }}@if($group->address_one)<br>@endif
                                        {{ $group->address_two }}@if($group->address_two)<br>@endif
                                        {{ $group->city }}{{ ($group->city && $group->state) ? ',' : '' }} {{ $group->state }} {{ $group->zip }}
                                    </p>
                                    <label>Country</label>
                                    <p>{{ country($group->country_code) }}</p>
                                    <label>Timezone</label>
                                    <p>{{ $group->timezone }}</p>
                                </div>
                            </div>
                	  </div>
                </div>
            </div>
            <div class="col-sm-3 tour-step-managers">
                <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Planning Tools</h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ url()->current() }}/teams" class="btn btn-primary btn-block tour-step-teams">Manage Teams</a>
                        <a href="{{ url()->current() }}/rooms" class="btn btn-block btn-primary tour-step-rooms"><i class="fa fa-bed"></i> Manage Rooming</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 tour-step-teams">
                <div class="panel panel-default">
                	  <div class="panel-heading">
                			<h3 class="panel-title">Teams</h3>
                	  </div>
                	  <div class="panel-body">
                          <a href="{{ url()->current() }}/teams" class="btn btn-primary btn-block">Manage Teams</a>
                	  </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 tour-step-trips">
                <dashboard-group-trips id="{{ $group->id }}"></dashboard-group-trips>
            </div>
        </div>
    </div>
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'settings',
                title: 'Group Settings',
                text: 'Change group settings and update the group profile page.',
                attachTo: {
                    element: '.tour-step-settings',
                    on: 'top'
                },
            },
            {
                id: 'managers',
                title: 'Managers',
                text: 'Add or remove group managers. Only a manager can edit group settings and add or remove other managers and team facilitators.',
                attachTo: {
                    element: '.tour-step-managers',
                    on: 'top'
                },
            },
            {
                id: 'trips',
                title: 'Trips',
                text: 'Any active trips the group is sponsoring will be listed here. Select a trip to see more details.',
                attachTo: {
                    element: '.tour-step-trips',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection