@extends('admin.campaigns.show')

@section('tab')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A group was added.</template>
</alert-success>

@component('panel')
    @slot('title')
        <h5>Add Group</h5>
    @endslot
    @slot('body')
        <ajax-form method="post" action="/campaigns/{{ $campaign->id }}/groups" :initial="{status_id: 1}">
            <template slot-scope="{ form }">
            <div class="form-group">
                <label class="col-sm-3 control-label">Organization</label>
                <div class="col-sm-9">
                    <select-group name="group_id" v-model="form.group_id" placeholder="Search organizations by name"></select-group>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Status</label>
                <div class="col-sm-9">
                    <input-select name="status_id" :options="{1:'Pending', 2:'Committed', 3:'Ready to Launch', 4:'Launched'}" v-model="form.status_id"></input-select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <hr class="divider">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
            </template>
        </ajax-form>
    @endslot
@endcomponent

<fetch-json url="/campaigns/{{ $campaign->id }}/groups" ref="group-list">
<div slot-scope="{ json:groups, loading, pagination }">
    @component('panel')
        @slot('title')
            <h5>Participating Groups</h5>
        @endslot
        <table class="table" v-if="groups && groups.length">
            <tr class="active">
                <th>#</th>
                <th>Name</th>
                <th>Trips</th>
                <th>Status</th>
            </tr>
            <tr v-if="loading"><td>Loading...</td></tr>
            <tr v-for="(group, index) in groups" :key="group.id" v-else>
                <td>@{{ index+1 }}</td>
                <td>
                    <strong><a :href="'/admin/groups/' + group.group_id">@{{ group.name }}</a></strong>
                </td>
                <td class="col-sm-1 text-right">
                    <strong>0</strong>
                </td>
                <td>
                    @{{ group.status }}
                </td>
            </tr>
        </table>
        <div class="panel-body text-center" v-else>
            <span class="lead">No Groups</span>
            <p>Add a group to this campaign to get started.</p>
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination"></pager>
        </div>
    @endcomponent
</div>
</fetch-json>


@endsection