@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li><a href="{{ url('/admin/groups') }}">Groups</a></li>
                    <li class="active">{{ $group->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
                <li role="presentation"><a href="#trips" aria-controls="profile" role="tab" data-toggle="tab">Trips</a></li>
            </ul>
        </div>
        <div class="col-xs-12 col-md-7">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="row">
                        <div class="col-xs-12">
                            @component('panel')
                                @slot('title')
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <h5>Group Details</h5>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            <hr class="divider inv sm">
                                            <div class="btn-group">
                                                <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <strong class="text-primary"><i class="fa fa-cog"></i> Manage</strong>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @can('update', $group)
                                                        <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                                                    @endcan
                                                    @can('delete', $group)
                                                        <li role="separator" class="divider"></li>
                                                        <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endslot
                                @component('list-group', ['data' => [
                                    'Name' => $group->name,
                                    'Type' => $group->type,
                                    'Status' => ($group->public ? 'Public' : 'Private'),
                                    'Email' => $group->email,
                                    'Primary Phone' => $group->phone_one,
                                    'Secondary Phone' => $group->phone_two,
                                    'Timezone' => $group->timezone,
                                    'Address' => $group->address.'<br />'.$group->address_two.'<br />'.$group->city.', '.$group->state.' '.$group->zip.'<br />'.country($group->country_code),
                                    'Description' => $group->description,
                                    'Created' => '<datetime-formatted value="'.$group->created_at->toIso8601String().'" />',
                                    'Last Updated' => '<datetime-formatted value="'.$group->updated_at->toIso8601String().'" />'
                                ]])
                                @endcomponent
                            @endcomponent
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
                        </div>
                    </div>

                            
                    <!-- @unless( ! $group->slug)
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
                    @endunless -->

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
        <div class="col-md-3 small">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                </li>
                <li role="presentation">
                    <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="notes">
                    <notes type="groups"
                        id="{{ $group->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10"
                        :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                    </notes>
                </div>
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <todos type="groups"
                        id="{{ $group->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                    </todos>
                </div>
            </div>

        </div>
    </div>
</div>
<admin-group-delete group-id="{{ $group->id }}"></admin-group-delete>
@endsection