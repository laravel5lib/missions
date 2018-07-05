@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/squads' => 'Squads',
        'active' => $squad->callsign
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-9">
            @component('panel')
                @slot('title')
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Details</h5>
                    </div>
                    <div class="col-xs-6 text-right">
                        <a href="{{ url('/admin/campaigns/' . $campaign->id . '/reservations/squads/' . $squad->uuid . '/edit') }}" 
                            class="btn btn-sm btn-default"
                        >Edit</a>
                    </div>
                </div>
                @endslot
                @component('list-group', ['data' => [
                    'Callsign' => $squad->callsign,
                    'Region' => $squad->region->name,
                    'Members' => $squad->members_count
                ]])
                @endcomponent
            @endcomponent

            @component('panel')
                @slot('title')
                <ul class="nav nav-pills nav-justified">
                    <li class="active">
                        <a href="#organizations" role="tab" data-toggle="tab">Organizations</a>
                    </li>
                    <li>
                        <a href="#roles" role="tab" data-toggle="tab">Team Roles</a>
                    </li>
                </ul>
                @endslot
                @if($squad->members_count > 0)
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="organizations">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Organization</th>
                                    <th class="text-right">Missionaries</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $index => $organization)
                                <tr>
                                    <td class="text-muted">{{ $loop->index + 1 }}</td>
                                    <td>{{ $organization['name'] }}</td>
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
                </div>
                @else
                <div class="panel-body" 
                    style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                >
                    <div class="text-center">
                        <span class="lead">No Data</span>
                        <p>No missionaries have been added to this squad.</p>
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
                        <p>No leadership has been assigned to this squad yet.</p>
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
                    <h5>Delete Squad</h5>
                @endslot
                @slot('body')
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                            <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will delete the squad and remove all it's members.</div>
                        </div>
                    </div>
                    <delete-form 
                        url="squads/{{ $squad->id }}" 
                        redirect="/admin/campaigns/{{ $campaign->id }}/reservations/squads"
                        label="Enter the squad callsign to delete it"
                        button="Delete"
                        match-value="{{ $squad->callsign }}"
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
                    <notes type="squads"
                        id="{{ $squad->uuid }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10"
                        :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                    </notes>
                </div>
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <todos type="squads"
                        id="{{ $squad->uuid }}"
                        user_id="{{ auth()->user()->id }}"
                        :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                    </todos>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection