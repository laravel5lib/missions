@extends('dashboard.layouts.default')

@section('content')

@breadcrumbs(['links' => [
    'dashboard/groups' => 'Organizations',
    'active' => $group->name
]])
@endbreadcrumbs

<div class="container-fluid">

    <hr class="divider inv">

    <div class="row">
        <div class="col-md-10 col-lg-8 col-lg-offset-2 col-md-offset-1">
            <fetch-json url="/campaigns" :parameters="{{ json_encode([
                    'filter' => [
                        'organization' => $group->id,
                        'active' => true
                    ]
                ])}}" ref="tripList" v-cloak>
                <div class="panel panel-default" 
                        style="border-top: 5px solid #f6323e" 
                        slot-scope="{ json: campaigns, loading, pagination, addFilter, removeFilter, filters }"
                >
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Campaigns <span class="badge badge-default">@{{ pagination.total }}</span></h5>
                            </div>
                            <div class="col-xs-6 text-right text-muted">
                                <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-justified">
                            <li role="presentation" :class="{'active' : filters.filter.active}">
                                <a role="button" @click="addFilter('active', true); removeFilter('inactive')"><i class="fa fa-fire"></i> Current</a>
                            </li>
                            <li role="presentation" :class="{'active' : filters.filter.inactive}">
                                <a role="button" @click="addFilter('inactive', true); removeFilter('active')"><i class="fa fa-archive"></i> Past</a>
                            </li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                    <table class="table" v-if="campaigns && campaigns.length">
                        <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Dates</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(campaign, index) in campaigns" :key="campaign.id">
                                <td>@{{ index+1 }}</td>
                                <td>
                                    <strong><a :href="'/dashboard/groups/{{ $group->id }}/campaigns/' + campaign.id">@{{ campaign.name | capitalize }}</a></strong>
                                </td>
                                <td class="col-sm-3">@{{ campaign.country.name }}</td>
                                <td class="col-sm-5">
                                    @{{ campaign.started_at | mFormat('MMM D') }} - @{{ campaign.ended_at | mFormat('MMM D') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="panel-body text-center" v-else>
                        <span class="lead">No Campaigns</span>
                        <p>Please contact Missions.Me staff to participate in an upcoming campaign.</p>
                    </div>
                    </div>
                    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                        <pager :pagination="pagination"></pager>
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

            @component('panel')
            @slot('title')
                <h5>Public Profile &amp; Interest Form</h5>
            @endslot
            @slot('body')
                <label>Public Profile</label>
                @if($group->public)
                    <pre><a href="{{ url('/'.$group->slug->url) }}">{{ url('/'.$group->slug->url) }}</a></pre>
                @else
                    <pre><a href="{{ url('/'.$group->slug->url) }}">{{ url('/'.$group->slug->url) .'(private)' }}</a></pre>
                @endif
                <label>Interest Form</label>
                <pre><a href="{{ url('/'.$group->slug->url.'/signup') }}">{{ url('/'.$group->slug->url.'/signup') }}</a></pre>
            @endslot
        @endcomponent

        </div>
    </div>
</div>
@endsection