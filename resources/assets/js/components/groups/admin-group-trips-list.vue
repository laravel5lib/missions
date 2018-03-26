<template>
    <div>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">

                <div class="form-group">
                    <select  class="form-control input-sm" v-model="filters.type">
                        <option value="">Any Type</option>
                        <option value="ministry">Ministry</option>
                        <option value="family">Family</option>
                        <option value="international">International</option>
                        <option value="media">Media</option>
                        <option value="medical">Medical</option>
                        <option value="leader">Leader</option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control input-sm" v-model="filters.status" style="width:100%;">
                        <option value="">Any Status</option>
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </mm-aside>

        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch"  placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                        Filters
                        <i class="fa fa-filter"></i>
                    </button>
                    <export-utility url="trips/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                    <!-- <import-utility title="Import trips List" 
                      url="trips/import"
                      :required-fields="importRequiredFields"
                      :optional-fields="importOptionalFields">
                    </import-utility> -->
                    <a class="btn btn-primary btn-sm" href="trips/create"><i class="fa fa-plus icon-left"></i> New</a>
                </form>
            </div>
        </div>
        <hr>
        <div style="position:relative">
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th :class="{'text-primary': orderByField === 'group.data.name'}">
                        Group
                        <i @click="setOrderByField('group.data.name')" v-if="orderByField !== 'group.data.name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'type'}">
                        Type
                        <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'campaign.data.name'}">
                        Campaign
                        <i @click="setOrderByField('campaign.data.name')" v-if="orderByField !== 'campaign.data.name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'status'}">
                        Status
                        <i @click="setOrderByField('status')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'status'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th>
                        Start &amp; End
                    </th>
                    <th><i class="fa fa-plane"></i></th>
                    <th><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="trip in orderByProp(trips, orderByField, direction)">
                    <td>{{trip.group.data.name}}</td>
                    <td>{{ trip.type|capitalize }}</td>
                    <td>{{ trip.campaign.data.name|capitalize }}</td>
                    <td>{{trip.status}}</td>
                    <td>{{trip.started_at|moment('ll', false, true)}} - <br>{{trip.ended_at|moment('ll', false, true)}}</td>
                    <td>{{trip.reservations}}</td>
                    <td>
                        <a :href="`/admin/trips/${ trip.id }`"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="col-sm-12 text-center">
                            <pagination :pagination="pagination" pagination-key="pagination" :callback="searchTrips"></pagination>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import exportUtility from '../export-utility.vue';
    import importUtility from '../import-utility.vue';
    export default{
        name: 'admin-group-trips',
        components: {exportUtility, importUtility},
        props: ['groupId'],
        data(){
            return{
                trips: [],
                filters: {
                    type: '',
                    status: ''
                },
                orderByField: 'group.data.name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                showFilters: false,
                exportOptions: {
                    id: 'ID',
                    group_name: 'Group Name',
                    campaign_name: 'Campaign Name',
                    spots: 'Spots',
                    companion_limit: 'Companion Limit',
                    country: 'Country',
                    country_code: 'Country Code',
                    type: 'Type',
                    difficulty: 'Difficulty',
                    started_at: 'Statred On',
                    ended_at: 'Ended On',
                    rep_name: 'Rep',
                    todos: 'Todos',
                    prospects: 'Prospects',
                    team_roles: 'Team Roles',
                    visibility: 'Visibility',
                    published_at: 'Published On',
                    closed_at: 'Closed On',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
                importRequiredFields: [
                  'type', 'country_code', 'started_at', 'ended_at'
                ],
                importOptionalFields: [
                ]
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.searchTrips();
                },
                deep: true
            },
            'search'(val, oldVal) {
                this.page = 1;
                this.pagination.current_page = 1;
            },
            'page'(val, oldVal) {
                this.searchTrips();
            },
            'per_page'(val, oldVal) {
                this.searchTrips();
            }
        },

        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'group.data.name';
                this.direction = 1;
                this.search = null;
                this.filters = {
                    type: '',
                    status: ''
                }
            },
            debouncedSearch: _.debounce(function() { this.searchTrips(); }, 250),
            searchTrips(){
                let params = {
                    include:'campaign,group',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    groups: new Array(this.groupId),
                };
                $.extend(params, this.filters);
                this.exportFilters = params;
                this.$http.get('trips', { params: params }).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.trips = response.data.data;
                })
            }
        },
        mounted(){
            this.searchTrips();
        }
    }
</script>
