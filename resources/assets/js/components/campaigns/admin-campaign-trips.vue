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
                        <option value="sports">Sports</option>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-8">
                        <h5>Trips</h5>
                    </div>
                    <div class="col-sm-4 text-right">
                        <a class="btn btn-primary btn-sm" :href="'/admin/campaigns/'+campaignId+'/trips/create'"><i class="fa fa-plus icon-left"></i> New</a>
                    </div>
                </div>
            </div><!-- end panel-heading -->
            <div class="panel-body">
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
                                <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                            <div id="toggleFields" class="form-toggle-menu dropdown" style="display: inline-block;">
                                <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Fields
                                    <span class="caret"></span>
                                </button>
                                <ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')"> Group Name
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="dates" :disabled="maxCheck('dates')"> Dates
                                        </label>
                                    </li>

                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="rep" :disabled="maxCheck('rep')"> Rep
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="starting_cost" :disabled="maxCheck('starting_cost')"> Starting Cost
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="companion_limit" :disabled="maxCheck('companion_limit')"> Companion Limit
                                        </label>
                                    </li>
                                    <li>
										<label class="small" style="margin-bottom: 0px;">
											<input type="checkbox" v-model="activeFields" value="country" :disabled="maxCheck('country')"> Country
										</label>
									</li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="difficulty" :disabled="maxCheck('difficulty')"> Difficulty
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="created_at" :disabled="maxCheck('created_at')"> Created At
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="updated_at" :disabled="maxCheck('updated_at')"> Updated At
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="public" :disabled="maxCheck('public')"> Public
                                        </label>
                                    </li>
                                    <li>
                                        <label class="small" style="margin-bottom: 0px;">
                                            <input type="checkbox" v-model="activeFields" value="reservations" :disabled="maxCheck('reservations')"> Reservations
                                        </label>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <div style="margin-bottom: 0px;" class="input-group input-group-sm">
                                            <label>Max Visible Fields</label>
                                            <select class="form-control" v-model="maxActiveFields">
                                                <option v-for="option in maxActiveFieldsOptions" :value="option">{{option}}</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                                Filters
                                <i class="fa fa-filter"></i>
                            </button>
                            <export-utility url="trips/export"
                                            :options="exportOptions"
                                            :filters="exportFilters">
                            </export-utility>
                        </form>
                    </div>
                </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'group.data.name'}">
                            Group
                            <i @click="setOrderByField('group.data.name')" v-if="orderByField !== 'group.data.name'" class="fa fa-sort pull-right"></i>
                            <i @click="direction=direction*-1" v-if="orderByField === 'group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                        </th>
                        <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'type'}">
                            Type
                            <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                            <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                        </th>
                        <th v-if="isActive('status')" :class="{'text-primary': orderByField === 'status'}">
                            Status
                            <i @click="setOrderByField('status')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                            <i @click="direction=direction*-1" v-if="orderByField === 'status'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                        </th>
                        <th v-if="isActive('dates')">
                            Start &amp; End
                        </th>

                        <th v-if="isActive('rep')">Rep</th>
                        <th v-if="isActive('spots')">Spots</th>
                        <th v-if="isActive('starting_cost')">Starting Cost</th>
                        <th v-if="isActive('companion_limit')">Companion Limit</th>
                        <th v-if="isActive('difficulty')">Difficulty</th>
                        <th v-if="isActive('created_at')">Created</th>
                        <th v-if="isActive('updated_at')">Updated</th>

                        <th v-if="isActive('public')"><i class="fa fa-eye"></i></th>
                        <th v-if="isActive('reservations')"><i class="fa fa-plane"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="trip in orderByProp(trips, orderByField, direction)">
                        <td v-if="isActive('name')">{{trip.group.data.name}}</td>
                        <td v-if="isActive('type')">{{ trip.type|capitalize }}</td>
                        <td v-if="isActive('status')">{{ trip.status|capitalize }}</td>
                        <td v-if="isActive('dates')">{{trip.started_at|moment('ll', false, true)}} - <br>{{trip.ended_at|moment('ll', false, true)}}</td>

                        <td v-if="isActive('rep')" v-text="trip.rep"></td>
                        <td v-if="isActive('spots')" v-text="trip.spots"></td>
                        <td v-if="isActive('starting_cost')">{{currency(trip.starting_cost)}}</td>
                        <td v-if="isActive('companion_limit')" v-text="trip.companion_limit"></td>
                        <td v-if="isActive('difficulty')">{{trip.difficulty|capitalize}}</td>
                        <td v-if="isActive('created_at')">{{trip.created_at|moment('ll')}}</td>
                        <td v-if="isActive('updated_at')">{{trip.updated_at|moment('ll')}}</td>

                        <td v-if="isActive('public')"><i class="fa fa-check" v-if="trip.public"></i></td>
                        <td v-if="isActive('reservations')">{{trip.reservations}}</td>
                        <td class="text-center">
                            <a :href="'/admin/trips/'+ trip.id"><i class="fa fa-gear"></i></a>
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
            </div><!-- end panel-body -->
        </div><!-- end panel -->
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import $ from 'jquery';
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'admin-campaign-trips',
        components: {exportUtility},
        props: {
            'campaignId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                showFilters: false,
                filters: {
                    type: '',
                    status: ''
                },
                trips: [],
                orderByField: 'group.data.name',
                direction: 1,
                page: 1,
                per_page: 50,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                activeFields: ['name', 'type', 'status', 'dates', 'public', 'reservations'],
                maxActiveFields: 6,
                maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],
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
                exportFilters: {}
            }
        },
        watch: {
            'orderByField' (val)  {
                this.searchTrips();
            },
            'direction' (val)  {
                this.searchTrips();
            },
            'activeFields' (val, oldVal) {
                // if the orderBy field is removed from view
                if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                // this.updateConfig();
            },
            'filters': {
                handler(val) {
                    // console.log(val);
                    this.searchTrips();
                },
                deep: true
            },
            'search'(val, oldVal) {
                this.page = 1;
            },
            'per_page'(val, oldVal) {
                this.searchTrips();
            }
        },
        computed:{
        },
        methods:{
            debouncedSearch: _.debounce(function () {
                this.searchTrips();
            }, 250),
            isActive(field){
                return _.contains(this.activeFields, field);
            },
            maxCheck(field){
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
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
                };
            },
            searchTrips(){
                let params = {
                    campaign_id: this.campaignId,
                    include:'campaign,group',
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'desc' : 'asc'),
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                };
                $.extend(params, this.filters);
                this.exportFilters = params;
                this.$http.get('trips', { params: params }).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.trips = response.data.data;
                }, (error) =>  {
                })
            }

        },
        mounted(){
            this.searchTrips();
        }
    }
</script>
