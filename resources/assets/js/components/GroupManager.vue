<template>
<div>
<div class="panel panel-default" style="border-top: 5px solid #f6323e">
    <div class="panel-heading">
        <h5>Add Group</h5>
    </div>
    <div class="panel-body">
        <ajax-form method="post" 
                   :action="'/campaigns/'+campaignId+'/groups'"
                   :horizontal="true"
                   :initial="defaultData"
                   @form:success="updateList">
            <template slot-scope="{ form }">

                <select-group name="group_id" 
                              v-model="form.group_id" 
                              placeholder="Search organizations by name"
                              classes="col-sm-9"
                              :horizontal="true">
                    <label slot="label" class="control-label col-sm-3">Organization</label>
                    <span slot="help-text" class="help-block">
                        <a href="/admin/organizations/create">+ add new organization</a>
                    </span>
                </select-group>

                <input-select name="status_id" 
                            :options="statusOptions" 
                            v-model="form.status_id"
                            classes="col-sm-9"
                            :horizontal="true">
                    <label slot="label" class="control-label col-sm-3">Status</label>
                </input-select>

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <hr class="divider">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </template>
        </ajax-form>
    </div>
</div>

<fetch-json :url="'/campaigns/'+campaignId+'/groups'" ref="groupList" @filter:removed="removeActiveFilter">
    <div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json:groups, loading, pagination, filters, addFilter, removeFilter, changePage, sortBy }">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Groups <span class="badge badge-default">{{ pagination.total }}</span></h5>
                </div>
                <div class="col-xs-6 text-right text-muted">
                    <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                </div>
            </div>
        </div>
        <div class="panel-heading">
            <span v-for="(label, index) in activeFilters" 
                :key="index" 
                class="label label-filter">
                {{ label.text | capitalize }}: "{{ label.value | capitalize}}" <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
            </span>
            <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">Filter By</li>
                    <li>
                        <a type="button" 
                            @click="openFilterModal(filterConfiguration.name)"
                        >Name</a>
                    </li>
                    <li>
                        <a type="button" 
                            @click="openFilterModal(filterConfiguration.status)"
                        >Status</a>
                    </li>
                </ul>

                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                            </div>
                            <div class="modal-body">
                            <component :is="filterModal.component" 
                                        :callback="addFilter" 
                                        :config="filterModal"
                                        @apply:filter="closeFilterModal"
                                ></component>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <em class="text-muted small">
                Showing {{ groups.length || 0 }} of {{ pagination.total || 0 }} results
            </em>

        </div>
        <div class="table-responsive">
        <table class="table" v-if="groups && groups.length">
            <thead>
            <tr class="active">
                <th>#</th>
                <th>Name</th>
                <th>Reservations</th>
                <th>Trips</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(group, index) in groups" :key="group.id">
                <td>{{ index+1 }}</td>
                <td>
                    <strong><a :href="'/admin/campaign-groups/' + group.group_id">{{ group.name }}</a></strong>
                </td>
                <td class="col-sm-1 text-right">
                    <strong>{{ group.reservations }}</strong>
                </td>
                <td class="col-sm-1 text-right">
                    <strong>{{ group.trips }}</strong>
                </td>
                <td>
                    <em>{{ group.status }}</em>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
        <div class="panel-body text-center" v-if="!loading && groups.length < 1">
            <span class="lead">No Groups</span>
            <p>Try chainging filters or add a group to this campaign to get started.</p>
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
    <template slot="message">A group was added.</template>
</alert-success>

</div>
</template>
<script>
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
export default {
    props: ['campaignId'],
    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
    },
    data() {
        return {
            defaultData: {
                'status_id': '1',
                'group_id': null
            },
            statusOptions: {1:'Pending', 2:'Committed', 3:'Ready to Launch', 4:'Launched'},
            activeFilters: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                name: {
                    component: 'filter-search',
                    title: 'Name', 
                    field: 'search',
                },
                status: {
                    component: 'filter-radio',
                    title: 'Status', 
                    field: 'status',
                    options: [
                        {value: 1, label: 'Pending'},
                        {value: 2, label: 'Committed'},
                        {value: 3, label: 'Ready to Launch'},
                        {value: 4, label: 'Launch'}
                    ]
                }
            }
        }
    },

    methods: {
        updateList(params) {
            this.$refs.groupList.fetch(params);
        },
        openFilterModal(filter) {
            this.filterModal = filter;
            $('#filterModal').modal('show');
        },
        closeFilterModal(data) {
            this.activeFilters.push(data);
            this.filterModal = {
                component: null,
                title: null
            }
            $('#filterModal').modal('hide');
        },
        removeActiveFilter(key) {
            this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
        }
    }
}
</script>
