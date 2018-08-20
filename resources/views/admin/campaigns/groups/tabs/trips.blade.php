@extends('admin.campaigns.groups.show')

@section('tab')

<fetch-json url="/trips?include=tags" :parameters="{{ json_encode([
    'filter' => [
        'group_id' => $group->group_id,
        'campaign_id' => $group->campaign_id
    ]
])}}" v-cloak>
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
        <div class="table-responsive">
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
                <template v-for="(trip, index) in trips" :key="trip.id">
                <tr>
                    <td rowspan="2">@{{ index+1 }}</td>
                    <td>
                        <strong><a :href="'/admin/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                    </td>
                    <td class="col-sm-5">
                        @{{ trip.started_at | mFormat('MMM D', false, false) }} - @{{ trip.ended_at | mFormat('MMM D') }}
                    </td>
                    <td class="col-sm-1 text-right">
                        <strong>@{{ trip.reservations}}</strong> / @{{ trip.spots }}
                    </td>
                    <td>
                        @{{ trip.status | capitalize }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: none; padding-top: 0;">
                        <span class="label label-filter" 
                              style="padding: 5px;"
                              v-for="tag in trip.tags" 
                              style="margin-right: 1em;">@{{ tag.name | capitalize }}</span>
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
        </div>
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