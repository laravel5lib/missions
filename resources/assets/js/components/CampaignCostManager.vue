<template>
<div>

    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading">
            <h5>New Cost</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                            <div class="col-xs-11">Define a new cost for the campaign. These costs can then be assigned with dollar amounts to groups, trips, and reservations.</div>
                        </div>
                    </div>
                </div>
            </div>
            <ajax-form method="post" 
                    :action="'/campaigns/'+campaignId+'/costs'" 
                    :initial="{type: null, name: null, description: null}"
                    @form:success="updateList"
            >
                <template slot-scope="{ form }">
                    <div class="row">
                        <div class="col-md-6">
                            <input-select name="type" v-model="form.type" :options="{ upfront: 'Upfront', incremental: 'Incremental', static: 'Static', optional: 'Optional'}">
                                <label slot="label" class="control-label">Select a Type</label>
                            </input-select>
                        </div>
                        <div class="col-md-6">
                            <input-text name="name" v-model="form.name">
                                <label slot="label" class="control-label">Name</label>
                            </input-text>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input-textarea name="description" v-model="form.description">
                                <label slot="label" class="control-label">Description</label>
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
        </div>
    </div>

    <fetch-json :url="'campaigns/'+campaignId+'/costs'" ref="costList">
        <div class="panel panel-default" slot-scope="{ json: costs, loading, pagination, changePage }" style="border-top: 5px solid #f6323e">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Costs <span class="badge badge-default">{{ pagination.total }}</span></h5>
                    </div>
                    <div class="col-xs-6 text-muted text-right">
                        <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                    </div>
                </div>
            </div>
                <table class="table" v-if="costs && costs.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Name</th>
                        <th>Reservations</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(cost, index) in costs" :key="cost.id">
                        <td>{{ index+1 }}</td>
                        <td>
                            <strong><a :href="'/admin/campaigns/'+campaignId+'/costs/' + cost.id">{{ cost.name }}</a></strong>
                            <br><span class="small text-muted">{{ cost.description }}</span>
                        </td>
                        <td class="col-sm-1 text-right">
                            <strong>{{ cost.reservations_count }}</strong>
                        </td>
                        <td>
                            <em>{{ cost.type | capitalize }}</em>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="panel-body text-center" v-else>
                <span class="lead">No Costs</span>
                <p>Create a cost for this campaign to get started.</p>
            </div>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success :timer="3000">
        <template slot="title">Nice Work!</template>
        <template slot="message">A new price was added.</template>
    </alert-success>

</div>
</template>

<script>
export default {
    name: 'campaign-cost-manager',

    props: ['campaignId'],

    methods: {
        updateList(params) {
            this.$refs.costList.fetch(params);
        }
    }
}
</script>
