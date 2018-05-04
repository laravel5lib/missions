@extends('admin.campaigns.groups.show')

@section('tab')

<fetch-json url="/trips?groups[]={{ $group->group_id }}&campaign={{ $group->campaign_id }}">
    <div class="col-sm-12" slot-scope="{ json: trips, loading, pagination }">
        @component('panel')
            @slot('title')
                <div class="row">
                    <div class="col-sm-6">
                        <h5>Current Trips</h5>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            @endslot
            <table class="table">
                <tr class="active">
                    <th>#</th>
                    <th>Type</th>
                    <th>Dates</th>
                    <th>Reservations</th>
                    <th>Status</th>
                </tr>
                <tr v-if="loading"><td>Loading...</td></tr>
                <tr v-for="(trip, index) in trips" :key="trip.id" v-else>
                    <td>@{{ index+1 }}</td>
                    <td>
                        <strong><a :href="'/admin/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                    </td>
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
            </table>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination"></pager>
            </div>
        @endcomponent
    </div>
</fetch-json>

@endsection