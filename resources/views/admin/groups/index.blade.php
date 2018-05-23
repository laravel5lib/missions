@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Organizations'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container-fluid">
<fetch-json :url="'/groups'" ref="groupList" v-cloak>
    <div class="row" slot-scope="{ json:groups, loading, pagination, filters, addFilter, removeFilter, changePage }">

        <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked">
                <li :class="{ 'active' : !filters.type }"><a role="tab" @click="removeFilter('type')">All</a></li>
                <li class="divider">
                <li :class="{ 'active' : filters.type === 'business' }">
                    <a role="tab" @click="addFilter('type', 'business')">Businesses</a>
                </li>
                <li :class="{ 'active' : filters.type === 'church' }">
                    <a role="tab" @click="addFilter('type', 'church')">Churches</a>
                </li>
                <li :class="{ 'active' : filters.type === 'independent' }">
                    <a role="tab" @click="addFilter('type', 'independent')">Independents</a>
                </li>
                <li :class="{ 'active' : filters.type === 'nonprofit' }">
                    <a role="tab" @click="addFilter('type', 'nonprofit')">Nonprofits</a>
                </li>
                <li :class="{ 'active' : filters.type === 'other' }">
                    <a role="tab" @click="addFilter('type', 'other')">Other</a>
                </li>
                <li :class="{ 'active' : filters.type === 'school' }">
                    <a role="tab" @click="addFilter('type', 'school')">Schools</a>
                </li>
                <li :class="{ 'active' : filters.type === 'youth' }">
                    <a role="tab" @click="addFilter('type', 'youth')">Youth</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-10">
            @component('panel')
                @slot('title')
                <div class="row">
                    <div class="col-xs-8">
                        <h5>Organizations <span class="badge badge-default">@{{ pagination.pagination.total }}</span></h5>
                    </div>
                    <div class="col-xs-4 text-right">
                        @can('create', \App\Models\v1\Group::class)
                            <a href="/admin/organizations/create" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus icon-left"></i> New Organization
                            </a>
                        @endcan
                    </div>
                </div>
                @endslot
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input class="form-control" 
                                placeholder="Search by name..." 
                                v-model="filters.search" 
                                @keypress.enter="addFilter('search', $event.target.value)"
                            >
                        </div>
                        <span class="help-block">
                            Press enter to search. 
                            <a role="button" @click="removeFilter('search')">Clear search</a>
                        </span>
                    </div>
                </div>
                <div class="panel-body" v-if="loading">
                    <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
                </div>
                <div class="table-responsive" v-if="groups && groups.length && !loading">
                    <table class="table">
                        <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(group, index) in groups" :key="group.id">
                                <td>@{{ index+1 }}</td>
                                <td>
                                    <strong><a :href="'/admin/organizations/' + group.id">@{{ group.name }}</a></strong>
                                </td>
                                <td><em>@{{ group.type | capitalize }}</em></td>
                                <td>@{{ group.state }}</td>
                                <td>@{{ group.country_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body text-center" v-if="!groups.length && !loading">
                    <span class="lead">No Organizations</span>
                    <p>Modify your search term or add groups by creating a new one.</p>
                </div>
                <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
                    <pager :pagination="pagination.pagination" :callback="changePage"></pager>
                </div>
            @endcomponent
        </div>
    </div>
</fetch-json>
</div>
@endsection