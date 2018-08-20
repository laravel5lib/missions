@extends('dashboard.layouts.default')

@section('content')

@breadcrumbs(['links' => [
    'dashboard/groups' => 'Organizations',
    'dashboard/groups/'.$group->id => $group->name,
    'active' => $campaign->name
]])
@endbreadcrumbs

<div class="container-fluid">

    <hr class="divider inv">

    <div class="row">
        <div class="col-md-2">
            @include('dashboard.groups._sidenav')
        </div>
        <div class="col-md-10 col-lg-8">
            @component('panel')
                @slot('title')<h5>Missionaries <span class="badge">{{ $group->reservations()->campaign($campaign->id)->count() }}</span></h5>@endslot
                @slot('body')
                    <div class="row">
                        <div class="col-sm-3">
                            <h4 class="text-primary">{{ $group->reservations()->campaign($campaign->id)->deposited()->count() }}</h4>
                            <p class="small text-muted">Deposit Only</p>
                        </div>
                        <div class="col-sm-3">
                            <h4 class="text-primary">{{ $group->reservations()->campaign($campaign->id)->inProcess()->count() }}</h4>
                            <p class="small text-muted">In Process</p>
                        </div>
                        <div class="col-sm-3">
                            <h4 class="text-primary">{{ $group->reservations()->campaign($campaign->id)->funded()->count() }}</h4>
                            <p class="small text-muted">Fully Funded</p>
                        </div>
                        <div class="col-sm-3">
                            <h4 class="text-primary">{{ $group->reservations()->campaign($campaign->id)->ready()->count() }}</h4>
                            <p class="small text-muted">Travel Ready</p>
                        </div>
                    </div>
                @endslot
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>Details</h5>
                @endslot
                @component('list-group', ['data' => [
                    'Name' => $campaign->name,
                    'Country' => country($campaign->country_code),
                    'Start Date' => $campaign->started_at->format('D, F j, Y'),
                    'End Date' => $campaign->ended_at->format('D, F j, Y'),
                    'Short Description' => $campaign->short_desc
                ]])
                @endcomponent
            @endcomponent


            <fetch-json url="/trips?include=tags" :parameters="{{ json_encode([
                    'filter' => [
                        'group_id' => $group->id,
                        'campaign_id' => $campaign->id
                    ]
                ])}}" ref="tripList" v-cloak>
                <div class="panel panel-default" 
                        style="border-top: 5px solid #f6323e" 
                        slot-scope="{ json: trips, loading, pagination, addFilter, removeFilter, filters }"
                >
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Trips <span class="badge badge-default">@{{ pagination.total }}</span></h5>
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
                                    <strong><a :href="'/dashboard/groups/' + trip.group_id +'/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                                </td>
                                <td class="col-sm-5">
                                    @{{ trip.started_at | mFormat('MMM D') }} - @{{ trip.ended_at | mFormat('MMM D') }}
                                </td>
                                <td class="col-sm-1 text-right">
                                    <strong>@{{ trip.reservations}}</strong> / @{{ trip.spots }}
                                </td>
                                <td>
                                    <i :class="{ 'fa fa-check-circle text-success' : trip.status === 'active', 'fa fa-times-circle text-danger' : trip.status != 'active' }"></i>
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
                    <div class="panel-body text-center" v-else>
                        <span class="lead">No Trips</span>
                        <p>Please contact Missions.Me staff to setup a trip.</p>
                    </div>
                    </div>
                    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                        <pager :pagination="pagination"></pager>
                    </div>
                </div>
            </fetch-json>
        </div>
    </div>

</div>

@endsection