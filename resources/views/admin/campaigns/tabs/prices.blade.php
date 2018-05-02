@extends('admin.campaigns.show')

@section('tab')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new price was added.</template>
</alert-success>

    @component('panel')
        @slot('title')
            <h5>New Cost</h5>
        @endslot
        @slot('body')
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <div class="row">
                        <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                        <div class="col-xs-11">Define a new cost for the campaign. These costs can then be assigned to groups, trips, and reservations.</div>
                    </div>
                </div>
            </div>
        </div>
        <ajax-form method="post" action="/costs">
            <template slot-scope="{ form }">
                <div class="row">
                    <div class="col-md-6">
                        <input-select name="type" v-model="form.type" :options="{ upfront: 'Upfront', incremental: 'Incremental', static: 'Static', optional: 'Optional'}">
                            <label slot="label">Select a Type</label>
                        </input-select>
                    </div>
                    <div class="col-md-6">
                        <input-text name="name" v-model="form.name">
                            <label slot="label">Name</label>
                        </input-text>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input-textarea name="description" v-model="form.description">
                            <label slot="label">Description</label>
                        </input-textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <hr class="divider">
                        <button type="submit" class="btn btn-md btn-primary">Create</button>
                    </div>
                </div>
            </template>
        </ajax-form>
        @endslot
    @endcomponent

    @component('panel')
        @slot('title')
            <h5>All Costs</h5>
        @endslot
        <fetch-json url="campaigns/{{ $campaign->id }}/costs">
            <div class="list-group" slot-scope="{ json: costs, loading, pagination }">
                <div class="list-group-item" v-if="loading">Loading...</div>
                <div class="list-group-item" v-for="cost in costs" :key="cost.id" v-else>
                    <div class="row">
                        <div class="col-sm-8">
                            <h5>@{{ cost.name }} <small>&middot; @{{ cost.type }}</small></h5>
                            <p class="text-muted">@{{ cost.description }}</p>
                        </div>
                        <div class="col-sm-4 col-xs-6 text-right">
                            <a class="small text-primary" :href="'/admin/campaigns/costs/' + cost.id"><i class="fa fa-cog"></i> Manage</a>
                        </div>
                    </div>
                </div>
            </div>
        </fetch-json>
    @endcomponent

@endsection