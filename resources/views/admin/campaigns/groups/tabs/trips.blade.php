@extends('admin.campaigns.groups.show')

@section('tab')

<fetch-json url="/trips?groups[]={{ $group->group_id }}&campaign={{ $group->campaign_id }}" v-cloak>
    <div class="panel panel-default" 
            style="border-top: 5px solid #f6323e" 
            slot-scope="{ json: trips, loading, pagination, changePage }"
    >
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6">
                    <h5>Trips <span class="badge badge-default">@{{ pagination.total }}</span></h5>
                </div>
                <div class="col-xs-6 text-right">
                    <h5 v-if="loading" class="text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                    <a href="{{ url('admin/campaign-groups/'.$group->uuid.'/trips/create') }}" class="btn btn-sm btn-primary">Add New Trip</a>
                </div>
            </div>
        </div>
        <table class="table" v-if="trips && trips.length">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Type</th>
                    <th>Dates</th>
                    <th>Reservations</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(trip, index) in trips" :key="trip.id">
                    <td>@{{ index+1 }}</td>
                    <td>
                        <strong><a :href="'/admin/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                    </td>
                    <td class="col-sm-5">
                        @{{ trip.started_at | moment('MMM D') }} - @{{ trip.ended_at | moment('MMM D') }}
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
            <p>Create a trip for this group to get started.</p>
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination" :callback="changePage"></pager>
        </div>
    </div>
</fetch-json>

@endsection