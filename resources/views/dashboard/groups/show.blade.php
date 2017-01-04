@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($group->avatar->source) }}"> {{ $group->name }} <small>&middot; Group</small>
                </h3>
                <div class="visible-xs text-center">
                    <hr class="divider inv">
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($group->avatar->source) }}">
                    <h4 style="margin-bottom:0;">{{ $group->name }}</h4>
                    <label>Group</label>
                </div>
            </div>
            <div class="col-sm-4 hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ $group->id }}/edit" class="btn btn-primary pull-right">
                    Group Settings
                </a>
            </div>
            <div class="col-sm-4 visible-xs text-center">
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
                                                <h4 class="hidden-xs"><a href="/groups/{{ $group->url }}">http://missions.me/groups/{{ $group->url }}</a></h4>
                                                <p class="visible-xs"><a href="/groups/{{ $group->url }}">http://missions.me/groups/{{ $group->url }}</a></p>
                                            @else
                                                <h4 class="text-strike text-muted hidden-xs">http://missions.me/groups/{{ $group->url }}</h4>
                                                <p class="text-strike text-muted visible-xs">http://missions.me/groups/{{ $group->url }}</p>
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
            <div class="col-sm-3">
                <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <dashboard-group-trips id="{{ $group->id }}"></dashboard-group-trips>
            </div>
        </div>
    </div>
@endsection