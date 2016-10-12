@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>
                    {{ $group->name }} <small>&middot; Group</small>
                </h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="create">New</a></li>
                        <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                        {{--<li><a data-toggle="modal" data-target="#duplicationModal">Duplicate</a></li>--}}
                        {{--<li role="separator" class="divider"></li>--}}
                        {{--<li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                	  <div class="panel-heading">
                			<h5>{{ $group->name }} <small>&middot; Details</small></h5>
                	  </div>
                	  <div class="panel-body">
                			<div class="col-sm-8">
                                @unless( ! $group->description)
                                <label>Description</label>
                                <p>{{ $group->description }}</p>
                                <hr class="divider">
                                @endunless
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
                                @unless( ! $group->url)
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <div class="well">
                                            <label>Url slug</label>
                                            @if($group->public)
                                                <h4><a href="/groups/{{ $group->url }}">http://missions.me/groups/{{ $group->url }}</a></h4>
                                            @else
                                                <h4 class="text-strike text-muted">http://missions.me/groups/{{ $group->url }}</h4>
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
                <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <notes type="groups"
                       id="{{ $group->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3">
                </notes>
            </div>
        </div>
    </div>
@endsection