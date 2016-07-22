@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Groups <small>{{ $group->name }}</small></h3>
                <!-- Single button -->
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                <hr>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-default">
                	  <div class="panel-heading">
                			<h3 class="panel-title">Details</h3>
                	  </div>
                	  <div class="panel-body">
                			<dl class="dl-horizontal">
                                <dt>Name</dt>
                                <dd>{{ $group->name }}</dd>
                                <dt>Description</dt>
                                <dd>{{ $group->description }}</dd>
                                <dt>Type</dt>
                                <dd>{{ $group->type }}</dd>
                                <dt>Status</dt>
                                <dd>{{ $group->public ? 'Public': 'Private' }}</dd>
                                <dt>Phone 1</dt>
                                <dd>{{ $group->phone_one }}</dd>
                                <dt>Phone 2</dt>
                                <dd>{{ $group->phone_two or 'Not Available' }}</dd>
                                <dt>Email Address</dt>
                                <dd>{{ $group->email or 'Not Available' }}</dd>
                                <dt>Address</dt>
                                <dd>
                                    {{ $group->address_one or 'Not Available' }}@if($group->address_one)<br>@endif
                                    {{ $group->address_two }}@if($group->address_two)<br>@endif
                                    {{ $group->city }}{{ ($group->city && $group->state) ? ',' : '' }} {{ $group->state }} {{ $group->zip }}
                                </dd>
                                <dt>Country</dt>
                                <dd>{{ country($group->country_code) }}</dd>
                                <dt>Timezone</dt>
                                <dd>{{ $group->timezone }}</dd>
                                <dt>Url slug</dt>
                                <dd>{{ $group->url }}</dd>
                                <dt>Trips</dt>
                                <dd>
                                    @foreach($group->trips as $trip)
                                        {{ $trip->type }} Missionary to {{ country($trip->country_code) }}<br>
                                    @endforeach
                                </dd>
                            </dl>
                	  </div>
                </div>
                <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
            </div>
        </div>
    </div>
@endsection