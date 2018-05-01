@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 'active' => 'Campaigns'
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">

    <div class="container-fluid">
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <fetch-json url="/campaigns?current=true">
                <div class="col-sm-12" slot-scope="{ json: campaigns, loading, pagination }">
                    @component('panel')
                        @slot('title')
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Current Campaigns</h5>
                                </div>
                                <div class="col-sm-6">
                                    
                                </div>
                            </div>
                        @endslot
                        <table class="table">
                            <tr class="active">
                                <th>#</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Groups</th>
                                <th>Status</th>
                            </tr>
                            <tr v-if="loading"><td>Loading...</td></tr>
                            <tr v-for="(campaign, index) in campaigns" :key="campaign.id" v-else>
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
                        </table>
                        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                            <pager :pagination="pagination"></pager>
                        </div>
                    @endcomponent
                </div>
                </fetch-json>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            filters
        </div>
    </div>
</div>
@endsection