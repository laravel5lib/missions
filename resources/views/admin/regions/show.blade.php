@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/squads' => 'Regions',
        'active' => $region->name
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">

    <div class="row">
        <div class="col-md-9 col-xs-12">
            <div class="row text-center">
                <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <h1 class="text-primary">{{ $region->members_count }}</h1>
                    <h5 class="small text-muted">Missionaries</h5>
                    </div>
                </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <h1>{{ $region->squads_count }}</h1>
                    <h5 class="small text-muted">Squads</h5>
                    </div>
                </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <h1 class="text-primary">{{ $region->getPercentageOfAllMissionaries() }}%</h1>
                    <h5 class="small text-muted">of All Missionaries</h5>
                    </div>
                </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <h1>{{ $region->getAverageSquadSize() }}</h1>
                    <h5 class="small text-muted">Average Squad Size</h5>
                    </div>
                </div>
                </div>
            </div>

            @component('panel')
                @slot('title')
                <ul class="nav nav-pills nav-justified">
                    <li class="active">
                        <a href="#organizations" role="tab" data-toggle="tab">Organizations</a>
                    </li>
                    <li>
                        <a href="#roles" role="tab" data-toggle="tab">Team Roles</a>
                    </li>
                    <li>
                        <a href="#squads" role="tab" data-toggle="tab">Squads</a>
                    </li>
                    <!-- <li>
                        <a role="button">
                            Age Ranges 
                        </a>
                    </li> -->
                </ul>
                @endslot
                @if($region->members_count > 0)
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="organizations">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Organization</th>
                                    <th class="text-right">Squads</th>
                                    <th class="text-right">Missionaries</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $index => $organization)
                                <tr>
                                    <td class="text-muted">{{ $loop->index + 1 }}</td>
                                    <td>{{ $organization['name'] }}</td>
                                    <td class="text-right"><strong>{{ $organization['squads'] }}</strong></td>
                                    <td class="text-right"><strong class="text-primary">{{ $organization['missionaries'] }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="roles">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Role</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td class="text-muted">{{ $loop->index + 1 }}</td>
                                    <td>{{ $role['name'] }}</td>
                                    <td class="text-right"><strong class="text-primary">{{ $role['count'] }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="squads">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Squad</th>
                                    <th class="text-right">Missionaries</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($squads as $squad)
                                <tr>
                                    <td class="text-muted">{{ $loop->index + 1 }}</td>
                                    <td>{{ $squad['callsign'] }}</td>
                                    <td class="text-right"><strong class="text-primary">{{ $squad['missionaries'] }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="panel-body" 
                    style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                >
                    <div class="text-center">
                        <span class="lead">No Data</span>
                        <p>No missionaries have been added to a squad assigned to this region.</p>
                    </div>
                </div>
                @endif
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>Leadership</h5>
                @endslot
                @if (!$leaders->count())
                <div class="panel-body" 
                    style="min-height: 100px; display:flex; justify-content: center; align-items: center;" 
                >
                    <div class="text-center">
                        <span class="lead">No Leaders</span>
                        <p>No leadership has been assigned to this region yet.</p>
                    </div>
                </div>
                @else
                <table class="table">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Group</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($leaders as $key => $leader)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>
                                <a href="/admin/reservations/{{ $leader->reservation->id }}">
                                    {{ $leader->reservation->name }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ teamRole($leader->reservation->desired_role) }}</td>
                        <td>{{ $leader->reservation->gender }}</td>
                        <td>{{ $leader->reservation->age }}</td>
                        <td>{{ $leader->group }}</td>
                        <td>{{ $leader->reservation->trip->group->name }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            @endcomponent

            @component('panel')
                @slot('title')
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Details</h5>
                    </div>
                    <div class="col-xs-6 text-right">
                        <a href="{{ url('/admin/campaigns/' . $campaign->id . '/regions/' . $region->id . '/edit') }}" 
                           class="btn btn-sm btn-default"
                        >Edit</a>
                    </div>
                </div>
                @endslot
                @component('list-group', ['data' => [
                    'Name' => $region->name
                ]])
                @endcomponent
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>Delete Region</h5>
                @endslot
                @slot('body')
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                            <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will delete the region and all it's squads. It will also unassign any missionaries assigned to the region.</div>
                        </div>
                    </div>
                    <delete-form 
                        url="regions/{{ $region->id }}" 
                        redirect="/admin/campaigns/{{ $campaign->id }}/reservations/squads"
                        label="Enter the region name to delete it"
                        button="Delete"
                        match-value="{{ $region->name }}"
                    ></delete-form>
                @endslot
            @endcomponent
        </div>
        <div class="col-xs-12 col-md-3 small">
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
                    <notes type="regions"
                        id="{{ $region->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10"
                        :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                    </notes>
                </div>
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <todos type="regions"
                        id="{{ $region->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                    </todos>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection