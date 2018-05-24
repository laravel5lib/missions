@extends('site.layouts.default')

@section('content')

<fetch-json url="campaigns/{{ $campaign->id }}/groups?hasPublishedTrips=true&per_page=20" v-cloak>
<div slot-scope="{ json:groups, loading, pagination, filters, addFilter, removeFilter, changePage }">

<div class="dark-bg-primary">
    <div class="container">
        <hr class="divider inv lg">
        <div class="row">
            <div class="col-xs-12">
                <hr class="divider inv">
                <h3>Step One: Find your team.</h3>
                <p class="small">If you don't have a church or organization, <a href="{{ url($campaign->slug->url.'/teams/'.$defaultGroup->id.'/trips') }}" style="color: white"><strong>click here</strong></a> and we'll help place you on a team.</p>
                <hr class="divider inv">
            </div>
        </div>
        <hr class="divider inv lg">
        <h3 class="text-center"><i class="fa fa-chevron-down"></i></h3>
    </div>
</div>
<div class="darker-bg-primary">
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 col-xs-12">
            <hr class="divider inv">
            <input type="text" class="form-control"
                placeholder="Search teams by name..." 
                @keydown="addFilter('search', $event.target.value)">
            <hr class="divider inv">
        </div>
    </div>
</div>
<hr class="divider inv xlg">
<div class="container" v-if="groups && groups.length && !loading">
    <div class="row">
        <div v-for="group in groups" :key="group.id">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a :href="'teams/'+group.id+'/trips'">
                            <h5 style="margin:0px;">
                                <img :src="group.avatar" :alt="group.name" class="av-left img-circle img-xs">
                                @{{ group.name | truncate(30, '...') }}
                            </h5>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container text-center" v-if="!groups.length && !loading">
    <span class="lead">No Teams</span>
    <p>Please modify your search.</p>
</div>
<div class="text-center text-muted" v-if="loading">
    <p class="lead"><i class="fa fa-spinner fa-spin"></i> Loading...</p>
</div>
<div class="container" v-if="pagination.total > pagination.per_page">
    <div class="row">
        <div class="col-xs-12">
            <hr class="divider inv xlg">
            <pager :pagination="pagination" :callback="changePage"></pager>
        </div>
    </div>
</div>

<hr class="divider inv xlg">
</div>
</fetch-json>
@endsection