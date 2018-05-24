@extends('site.layouts.default')

@section('content')

<fetch-json url="trips" :parameters="{{ json_encode([
    'filter' => [
        'campaign_id' => $campaign->id,
        'group_id' => $group->id,
        'public' => true,
        'published' => true
    ]
]) }}" v-cloak>
<div slot-scope="{ json:trips, loading, pagination, filters, addFilter, removeFilter, changePage }">

<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs text-capitalize">
                    <img class="img-circle img-sm av-left" src="{{ image($group->getAvatar()->source . '?w=100') }}">{{ $group->name }}
                </h3>
                <div class="visible-xs text-center text-capitalize">
                    <hr class="divider inv">
                    <img class="img-circle img-sm" src="{{ image($group->getAvatar()->source . '?w=100') }}">
                    <h4 style="margin-bottom:0;">{{ $group->name }}</h4>
                    <hr class="divider inv">
                </div>
            </div><!-- end col -->
            @if($campaign->slug)
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ url($campaign->slug->url.'/teams') }}" class="btn btn-link">
                    <i class="fa fa-chevron-left icon-left"></i> Change Teams
                </a>
            </div>
            <div class="col-xs-12 text-center visible-xs">
                <a href="{{ url($campaign->slug->url.'/teams') }}" class="btn btn-link">
                    <i class="fa fa-chevron-left icon-left"></i> Change Teams
                </a>
                <hr class="divider inv">
            </div>
            @endif
        </div>
    </div>
</div>
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
        <h3 class="text-center"><i class="fa fa-chevron-down"></i></h3>
    </div>
</div>
<hr class="divider inv xlg">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default" v-if="trips && trips.length && !loading">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Difficulty</th>
                            <th class="text-right">Starts At</th>
                            <th class="text-right">Spots Left</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @verbatim
                            <tr v-for="trip in trips" :key="trip.id">
                                <td><strong><a :href="'/trips/'+trip.id">{{ trip.type | capitalize }}</a></strong></td>
                                <td>{{ trip.started_at | mFormat('MMMM D') }} - {{ trip.ended_at | mFormat('MMMM D, Y') }}</td>
                                <td><span class="label" :class="{'label-success' : trip.status === 'active', 'label-danger' : trip.status != 'active'}">{{ trip.status }}</span></td>
                                <td><em>{{ trip.difficulty }}</em></td>
                                <td class="text-primary text-right"><strong>{{ currency(trip.starting_cost) }}</strong></td>
                                <td class="text-right"><strong>{{ trip.spots }}</strong></td>
                                <td class="text-right"><a :href="'/trips/'+trip.id" class="btn btn-primary-hollow btn-sm">Select</a></td>
                            </tr>
                            @endverbatim
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center text-muted" v-if="!trips.length && !loading">
                <h4>No trip options found.</h4>
                <p>Please try a different team.</p>
                <hr class="divider inv xlg">
            </div>
            <div class="text-center text-muted" v-if="loading">
                <p class="lead"><i class="fa fa-spinner fa-spin"></i> Loading...</p>
            </div>
        </div>
    </div>
</div>

</div>
</fetch-json>
@endsection