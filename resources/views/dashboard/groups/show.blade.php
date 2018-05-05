@extends('dashboard.layouts.default')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <hr class="divider inv">
            @breadcrumbs(['links' => [
                'dashboard/groups' => 'Organizations',
                'active' => $group->name
            ]])
            @endbreadcrumbs
            <hr class="divider">
            <hr class="divider inv">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            @sidenav(['links' => [
                'dashboard/groups/'.$group->id => 'Overview',
                'dashboard/groups/'.$group->id.'/teams' => 'Squads',
                'dashboard/groups/'.$group->id.'/rooms' => 'Rooming'
            ]])
            @endsidenav
        </div>
        <div class="col-sm-7">
            <fetch-json url="/trips?&status=current&onlyPublished=true&groups[]={{ $group->id }}" ref="tripList">
                <div class="panel panel-default" 
                        style="border-top: 5px solid #f6323e" 
                        slot-scope="{ json: trips, loading, pagination }"
                >
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Current Trips <span class="badge badge-default">@{{ pagination.pagination.total }}</span></h5>
                            </div>
                            <div class="col-xs-6 text-right text-muted">
                                <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table" v-if="trips && trips.length">
                        <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Type</th>
                                <th>Country</th>
                                <th>Dates</th>
                                <th>Reservations</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(trip, index) in trips" :key="trip.id">
                                <td>@{{ index+1 }}</td>
                                <td>
                                    <strong><a :href="'/dashboard/groups/' + trip.group_id +'/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                                </td>
                                <td class="col-sm-3">@{{ trip.country_name }}</td>
                                <td class="col-sm-5">
                                    @{{ trip.started_at | moment('MMM d') }} - @{{ trip.ended_at | moment('MMM d') }}
                                </td>
                                <td class="col-sm-1 text-right">
                                    <strong>@{{ trip.reservations}}</strong> / @{{ trip.spots }}
                                </td>
                                <td>
                                    @{{ trip.status | capitalize }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="panel-body text-center" v-else>
                        <span class="lead">No Trips</span>
                        <p>Please contact Missions.Me staff to setup a trip.</p>
                    </div>
                    </div>
                    <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
                        <pager :pagination="pagination.pagination"></pager>
                    </div>
                </div>
            </fetch-json>

            <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>

            @component('panel')
                @slot('title')
                    <div class="row">
                        <div class="col-xs-8">
                            <h5>{{ $group->name }}</h5>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="{{ $group->id }}/edit" class="text-muted">
                                <strong><i class="fa fa-cog"></i> Settings</strong>
                            </a>
                        </div>
                    </div>
                @endslot
                @component('list-group', ['data' => [
                    'Organization Type' => $group->type,
                    'Description' => $group->description,
                    'Visibility' => ($group->public ? 'Public' : 'Private'),
                    'Email' => $group->email,
                    'Primary Phone' => $group->phone_one,
                    'Secondary Phone' => $group->phone_two,
                    'Timezone' => $group->timezone,
                    'Address' => $group->address_one.'<br />'.($group->address_two ? $group->address_two.'<br />' : '').$group->city.', '.$group->state.' '.$group->zip.'<br />'.country($group->country_code),
                ]])
                @endcomponent
            @endcomponent

        </div>
    </div>
</div>
@endsection