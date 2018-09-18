@extends('site.layouts.default')

@section('content')

<fetch-json url="trips?include=tags" :parameters="{{ json_encode([
    'filter' => [
        'campaign_id' => $campaign->id,
        'group_id' => $group->id,
        'public' => true,
        'published' => true,
    ]
]) }}" v-cloak>
<div slot-scope="{ json:trips, loading, pagination, filters, addFilter, removeFilter, changePage }">

<div class="dark-bg-primary">
    <div class="container">
        <hr class="divider inv lg">
        <div class="row">
            <div class="col-xs-12">
                <hr class="divider inv">
                <h3>Step Two: Choose a trip option.</h3>
                <p class="small">Find the trip that's right for you. If you don't see the trip type you desire, try choosing a different team to travel with.</p>
                <hr class="divider inv">
            </div>
        </div>
        <hr class="divider inv lg">
    </div>
</div>
<div class="darker-bg-primary">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="text-center"><i class="fa fa-chevron-down"></i></h3>
            </div>
        </div>
    </div>
</div>

<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <hr class="divider inv">
                <div class="media">
                  <div class="media-left">
                    <a href="{{ url($campaign->slug->url) }}">
                      <img class="media-object" src="{{ image($campaign->getAvatar()->source . '?w=100') }}" alt="{{ $campaign->name }}">
                    </a>
                  </div>
                  <div class="media-body">
                    <label>Trip</label>
                    <h3 class="media-heading">{{ $campaign->name }}</h3>
                    <a href="{{ url('trips') }}" class="small text-primary"><i class="fa fa-arrow-circle-left"></i> Change Trip</a>
                  </div>
                </div>
                <hr class="divider inv">
            </div><!-- end col -->
            <div class="col-sm-6">
                <hr class="divider inv">
                <div class="media">
                  <div class="media-left">
                    <a href="{{ url($group->slug ? $group->slug->url : '') }}">
                      <img class="media-object" src="{{ image($group->getAvatar()->source . '?w=100') }}" alt="{{ $group->name }}">
                    </a>
                  </div>
                  <div class="media-body">
                    <label>Team</label>
                    <h3 class="media-heading">{{ $group->name }}</h3>
                    <a href="{{ url("{$campaign->slug->url}/teams") }}" class="small text-primary"><i class="fa fa-arrow-circle-left"></i> Change Team</a>
                  </div>
                  <hr class="divider inv">
                </div>
            </div><!-- end col -->
        </div>
    </div>
</div>

<hr class="divider inv xlg">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div v-if="trips && trips.length && !loading">
                @verbatim
                    <div class="panel panel-default" v-for="trip in trips" :key="trip.id">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Option</label>
                                            <h4 style="margin: 0">{{ trip.type | capitalize }} Trip</h4>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Starting Cost</label>
                                            <h4 style="margin: 0">{{ currency(trip.starting_cost) }}</h4>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Dates</label>
                                            <h4 style="margin: 0">{{ trip.started_at | mFormat('MMMM D') }} - {{ trip.ended_at | mFormat('MMMM D, Y') }}</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr class="divider inv">
                                            <span class="label label-filter" v-for="tag in trip.tags">{{ tag.name | capitalize }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="display: flex; justify-content: space-between;">
                                        <h5 style="display: inline-block;" :class="{'text-success' : trip.status === 'active', 'label-danger' : trip.status != 'active'}">
                                            {{ trip.status | capitalize }} <small>Registration</small>
                                        </h5>
                                        <h5 style="display: inline-block;">{{ trip.spots }} <small>Spots Left</small></h5>
                                    </div>
                                    <a :href="'/trips/'+trip.id" class="btn btn-primary-hollow btn-md btn-block">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endverbatim
            </div>

            <div class="text-center text-muted" v-if="!trips.length && !loading">
                <h4>No trip options found.</h4>
                <p>Please try a different team.</p>
                <hr class="divider inv xlg">
            </div>
            <div class="text-center text-muted" v-if="loading">
                <p class="lead"><i class="fa fa-spinner fa-spin"></i> Loading...</p>
            </div>
            <div v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </div>
</div>

<hr class="divider inv xlg">

</div>
</fetch-json>
@endsection