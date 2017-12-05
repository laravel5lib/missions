@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>
                    <img src="{{ image($group->getAvatar()->source . '?w=100') }}" alt="{{ $group->name }}" class="img-circle av-left img-sm">
                    {{ $group->name }} <small>&middot; Group</small>
                </h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <hr class="divider inv">
                <div class="btn-group">
                    <a href="{{ url('admin/groups') }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                    <div class="btn-group">
                        <a type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Manage <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            @can('create', $group)
                                <li><a href="create">New</a></li>
                            @endcan
                            @can('update', $group)
                                <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                            @endcan
                            @can('delete', $group)
                                <li role="separator" class="divider"></li>
                                <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                            @endcan
                        </ul>
                    </div><!-- end btn-group -->
                </div><!-- end btn-group -->
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
            <li role="presentation"><a href="#trips" aria-controls="profile" role="tab" data-toggle="tab">Trips</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="details">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Group Details</h5>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-8">
                                    @unless( ! $group->description)
                                        <label>Description</label>
                                        <p>{{ $group->description }}</p>
                                        <hr class="divider">
                                    @endunless
                                    <div class="row">
                                        <div class="col-sm-4 text-center">
                                            <label>Type</label>
                                            <p>{{ $group->type }}</p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <label>Created At</label>
                                            <p>{{ $group->created_at->toDayDateTimeString() }}</p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <label>Status</label>
                                            <p>{{ $group->public ? 'Public': 'Private' }}</p>
                                        </div>
                                    </div>
                                    <hr class="divider">
                                    @unless( ! $group->slug)
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <div class="well">
                                                    <label>Url slug</label>
                                                    @if($group->public)
                                                        <h4><a href="/{{ $group->slug->url }}">http://missions.me/{{ $group->slug->url }}</a></h4>
                                                    @else
                                                        <h4 class="text-strike text-muted">http://missions.me/{{ $group->slug->url }}</h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endunless
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
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <notes type="groups"
                               id="{{ $group->id }}"
                               user_id="{{ auth()->user()->id }}"
                               :per_page="3">
                        </notes>
                    </div>
                    <div class="col-sm-4">
                        <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="trips">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Group Trips</h5>
                            </div>
                            <div class="panel-body">
                                <admin-group-trips group-id="{{ $group->id }}"></admin-group-trips>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <admin-group-delete group-id="{{ $group->id }}"></admin-group-delete>

@endsection