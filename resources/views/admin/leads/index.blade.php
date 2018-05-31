@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        '/admin' => 'Dashboard',
        'active' => 'Leads'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container-fluid">
        <fetch-json url="leads" :parameters="{ filter: {category_id: 1} }" v-cloak>
            <div class="row" slot-scope="{ json:leads, loading, pagination, filters, addFilter, removeFilter, changePage }">
                <div class="col-sm-2">
                    <label>Categories</label>
                    <ul class="nav nav-pills nav-stacked">
                        <li :class="{ 'active' : filters.filter.category_id === 1 }">
                            <a role="tab" @click="addFilter('category_id', 1)">Organizations</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-10">
                    @component('panel')
                        @slot('title')<h5>Organization Leads</h5> @endslot
                        <div class="panel-body" v-if="loading">
                            <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
                        </div>
                        <table class="table" v-if="!loading && leads && leads.length">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Organization</th>
                                    <th>Contact</th>
                                </tr>
                                <tr v-for="(lead, index) in leads" :key="lead.id">
                                    <td>@{{ index + 1 }}</td>
                                    <td>
                                        <a :href="'/admin/leads/'+lead.id">
                                            <strong>@{{ lead.created_at | moment('lll') }}</strong>
                                        </a>
                                    </td>
                                    <td>@{{ lead.content.organization }}</td>
                                    <td>@{{ lead.content.contact }}</td>
                                </tr>
                            </thead>
                        </table>
                        <div class="panel-body text-center" v-if="!leads.length && !loading">
                            <span class="lead">No Leads</span>
                            <p>Try a different category.</p>
                        </div>
                        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                            <pager :pagination="pagination" :callback="changePage"></pager>
                        </div>
                    @endcomponent
                </div>
            </div>
        </fetch-json>
    </div>

@endsection