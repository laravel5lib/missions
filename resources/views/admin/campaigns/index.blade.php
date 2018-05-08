@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 'active' => 'Campaigns'
    ]])
    @endbreadcrumbs
@endsection

@section('content')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new campaign was started.</template>
    <template slot="cancel">Start Another</template>
    <template slot="confirm">Continue</template>
</alert-success>

<hr class="divider inv lg">

    <div class="container-fluid">

        <div class="col-md-offset-2 col-md-8">
            
            <div class="row">
                <div class="col-sm-12">
                    @component('panel')
                        @slot('title')
                            <h5>New Campaign</h5>
                        @endslot
                        @slot('body')
                            <ajax-form method="post" action="/campaigns" :initial="{name: null, country_code: null, started_at: null, ended_at: null}">
                                <template slot-scope="{ form }">
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h5>Campaign Details</h5>
                                            <p class="text-muted">These details appear to the end-user in the <a href="{{ url('trips') }}" target="_blank">campaign cards</a>.</p>
                                        </div>
                                        <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input-text name="name" v-model="form.name">
                                                            <label slot="label">Campaign Name</label>
                                                        </input-text>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <select-country name="country_code" v-model="form.country_code"></select-country>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    <hr class="divider">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <h5>Campaign Dates</h5>
                                            <p class="text-muted">The start and end of the campaign. These dates are visible to the end-user.</p>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input-date name="started_at" v-model="form.started_at">
                                                        <label slot="label">Start Date</label>
                                                    </input-date>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input-date name="ended_at" v-model="form.ended_at">
                                                        <label slot="label">End Date</label>
                                                    </input-date>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12 text-right">
                                            <hr class="divider">
                                            <button type="submit" class="btn btn-primary">Start Campaign</button>
                                        </div>
                                    </div>

                                </template>
                            </ajax-form>
                        @endslot
                    @endcomponent
                </div>
            </div>



            <div class="row">
                <fetch-json url="/campaigns" :parameters="{current: true}" v-cloak>
                <div class="col-sm-12" 
                     slot-scope="{ json: campaigns, loading, pagination, filters, addFilter, removeFilter, changePage }">
                    @component('panel')
                        @slot('title')
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Campaigns <span class="badge badge-default">@{{ pagination.pagination.total }}</span></h5>
                                </div>
                                <div class="col-sm-6 text-muted text-right">
                                    <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                                </div>
                            </div>
                        @endslot
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-justified">
                                <li role="presentation" :class="{'active' : filters.current}">
                                    <a role="button" @click="addFilter('current', true); removeFilter('archived')"><i class="fa fa-fire"></i> Current</a>
                                </li>
                                <li role="presentation" :class="{'active' : filters.archived}">
                                    <a role="button" @click="addFilter('archived', true); removeFilter('current')"><i class="fa fa-archive"></i> Past</a>
                                </li>
                            </ul>
                        </div>
                        <table class="table" v-if="campaigns && campaigns.length">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Groups</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(campaign, index) in campaigns" :key="campaign.id">
                                    <td>@{{ index+1 }}</td>
                                    <td class="col-sm-5">
                                        <strong><a :href="'/admin/campaigns/' + campaign.id">@{{ campaign.name }}</a></strong>
                                    </td>
                                    <td>
                                        @{{ campaign.country }}
                                    </td>
                                    <td class="col-sm-1 text-right">
                                        <strong>@{{ campaign.groups_count}}</strong>
                                    </td>
                                    <td>
                                        @{{ campaign.status}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="panel-body text-center" v-else>
                            <span class="lead">No Campaigns</span>
                            <p>Create a campaign to get started.</p>
                        </div>
                        <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
                            <pager :pagination="pagination.pagination" :callback="changePage"></pager>
                        </div>
                    @endcomponent
                </div>
                </fetch-json>
            </div>
        </div>
    </div>
</div>
@endsection