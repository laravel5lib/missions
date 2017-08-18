<template>
    <div>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <reservations-filters ref="filters" :filters="filters" :reset-callback="resetFilter" :pagination="pagination" :callback="getReservations" storage="DashboardReservations" :starter="startUp" :facilitator="isFacilitator" :trip-specific="!!tripId"></reservations-filters>
        </mm-aside>
        <div class="row">
            <div class="col-xs-12 tour-step-find">
                <form class="form-inline">
                <div class="row">
                    <div class="form-group col-lg-3 col-md-12 col-xs-12">
                        <div class="input-group input-group-sm col-xs-12">
                            <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        </div>
                    </div><!-- end col -->
                    <div class="form-group col-lg-9 col-md-12">
                        <hr class="divider inv hidden-lg">
                        <div class="form-group" style="display: inline-block;" v-if="isFacilitator">
                            <label>Show</label>
                            <select class="form-control input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>

                        <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                            Filters
                            <i class="fa fa-filter"></i>
                        </button>
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Sort By <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li v-for="option in sortOptions" :class="{'active': filters.sort === option.value && filters.direction === option.direction}"><a @click="filters.sort = option.value,filters.direction = option.direction" v-text="option.name"></a></li>
							</ul>
						</div>
						<export-utility url="reservations/export"
                                        :options="exportOptions"
                                        :filters="exportFilters">
                        </export-utility>
                        <div class="btn-group">
                            <button class="btn btn-sm"
                                    :class="[layout == 'list' ? 'btn-primary' : 'btn-default']"
                                    @click.prevent="layout = 'list'"><i class="fa fa-list-ul"></i></button>
                            <button class="btn btn-sm"
                                    :class="[layout == 'grid' ? 'btn-primary' : 'btn-default']"
                                    @click.prevent="layout = 'grid'"><i class="fa fa-th-large"></i></button>
                        </div>
                        <div style="margin-left:5px;display: inline-block;" v-if="isFacilitator">
                            <label>
                                <input type="checkbox" v-model="includeManaging"> Include my group's reservations
                            </label>
                        </div>
                    </div><!-- end col -->
                </div>
                </form>
                <hr class="divider sm inv">
            </div>
            <div class="col-xs-12 tour-step-list" style="position:relative">
                <spinner ref="spinner" size="sm" text="Loading"></spinner>
                <template v-if="reservations.length > 0">
                    <div class="row" v-if="layout == 'grid'">
                        <div class="col-xs-12 col-sm-6 col-md-4" v-for="reservation in reservations">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center" :class="'panel-' + reservation.trip.data.type">
                                    <h5 class="text-uppercase">{{ reservation.trip.data.type }}</h5>
                                </div>
                                <div class="panel-body text-center">
                                    <img :src="reservation.avatar" class="img-circle img-md">
                                    <hr class="divider inv sm">
                                    <h4>{{ reservation.surname }}, {{ reservation.given_names }}</h4>
                                    <p class="text-capitalize small">{{ reservation.trip.data.group.data.name }}</p>
                                    <label style="margin-bottom:2px;">Campaign</label>
                                    <p class="text-capitalize small" style="margin-top:2px;">{{ reservation.trip.data.campaign.data.name }}</p>
                                    <label style="margin-bottom:2px;font-size:10px;">Country</label>
                                    <p class="text-capitalize small" style="margin-top:2px;">{{ reservation.trip.data.country_name }}</p>
                                    <hr class="divider inv sm">
                                    <a class="btn btn-sm btn-primary" :href="'/dashboard/reservations/' + reservation.id">View Reservation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="layout == 'list'">
                        <div class="col-xs-12">
                            <div class="list-group">
                                <div class="list-group-item hidden-xs">
                                    <div class="row">
                                        <div class="col-sm-3"><h5>Name/Role</h5></div>
                                        <div class="col-sm-3"><h5>Campaign/Country</h5></div>
                                        <div class="col-sm-3"><h5>Group/Type</h5></div>
                                        <div class="col-sm-3"><h5>Raised/Requirements</h5></div>
                                    </div>
                                </div>
                                <a :href="'/dashboard/reservations/' + reservation.id" class="list-group-item" v-for="reservation in reservations">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            {{ reservation.surname|capitalize }}, {{ reservation.given_names|capitalize }}<br>
                                            <label>{{ reservation.desired_role.name }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.campaign.data.name|capitalize }}<br>
                                            <label>{{ reservation.trip.data.country_name|capitalize }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.group.data.name|capitalize }}<br>
                                            <label>{{ reservation.trip.data.type|capitalize }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            <span class="text-success">
                                            {{ reservation.percent_raised }}% &middot;
                                            </span> <small class="text-muted">{{ reservation.total_raised | currency }} of {{ reservation.total_cost | currency }}</small>
                                            <br>
                                            <tooltip effect="scale" placement="top" content="Complete">
                                                <span class="label label-success">{{ complete(reservation) }}</span>
                                            </tooltip>
                                            <tooltip effect="scale" placement="top" content="Needs Attention">
                                                <span class="label label-info">{{ attention(reservation) }}</span>
                                            </tooltip>
                                            <tooltip effect="scale" placement="top" content="Under Review">
                                                <span class="label label-default">{{ reviewing(reservation) }}</span>
                                            </tooltip>
                                            <tooltip effect="scale" placement="top" content="Incomplete">
                                                <span class="label label-danger" v-text="getIncomplete(reservation)"></span>
                                            </tooltip>
                                        </div><!-- end col -->
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <pagination :pagination="pagination" :callback="getReservations"></pagination>
                        </div>
                    </div>
                </template>
            </div>
    <div class="col-xs-12" v-if="reservations.length < 1">
        <p class="text-muted text-center"><em>Register for a trip to view your reservation details here!</em></p>
        <p class="text-center"><a class="btn btn-link btn-sm" href="/campaigns">Go On A Trip</a></p>
    </div>
</div>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from '../export-utility.vue';
    import reservationsFilters from '../filters/reservations-filters.vue';
    export default{
        name: 'reservations-list',
        components: {vSelect, exportUtility, reservationsFilters},
        props: {
            userId: {
                type: String,
                required: true
            },
            type: {
                type: String,
            },
            groupOnly: {
                type: Boolean,
                default: false
            },
            groupId: {
                type: String,

            },
            tripId: {
                type: String,
            }
        },
        data(){
            return {
                reservations: [],
                pagination: { current_page: 1},
                trips: [],
                includeManaging: false,
                search: '',
                showFilters: false,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                filters: {
                    type: '',
                    groups: [],
                    campaign: null,
                    gender: '',
                    status: '',
                    shirtSize: [],
                    hasCompanions: null,
                    due: '',
                    role: '',
                    todoName: '',
                    todoStatus: null,
                    requirementName: '',
                    requirementStatus: '',
                    due: '',
                    dueStatus: '',
                    rep: '',
                    sort: 'created_at',
                    direction: 'desc',
                    age: [0, 120],

                },
                sortOptions: [
                    { name: 'Given Names - Ascending', value: 'given_names', direction: 'asc'},
                    { name: 'Given Names - Descending', value: 'given_names', direction: 'desc'},
                    { name: 'Surname - Ascending', value: 'surname', direction: 'asc'},
                    { name: 'Surname - Descending', value: 'surname', direction: 'desc'},
                    { name: 'Registration Date - Ascending', value: 'created_at', direction: 'asc'},
                    { name: 'Registration Date - Descending', value: 'created_at', direction: 'desc'},
                ],
                exportOptions: {
                    managing_user: 'Managing User',
                    user_email: 'User Email',
                    user_primary_phone: 'User Primary Phone',
                    user_secondary_phone: 'User Secondary Phone',
                    group: 'Group',
                    trip_type: 'Trip Type',
                    campaign: 'Campaign',
                    country_located: 'Country Located',
                    start_date: 'Trip Start Date',
                    end_date: 'Trip End Date',
                    given_names: 'Given Names',
                    surname: 'Surname',
                    gender: 'Gender',
                    marital_status: 'Marital Status',
                    shirt_size: 'Shirt Size',
                    age: 'Age',
                    birthday: 'Birthday',
                    email: 'Email',
                    primary_phone: 'Primary Phone',
                    secondary_phone: 'Secondary Phone',
                    street_address: 'Street Address',
                    city: 'City',
                    state_providence: 'State/Providence',
                    zip_postal: 'Zip/Postal Code',
                    country: 'Country',
                    companions: 'Companions',
                    payments: 'Payments Due',
                    incremental_costs: 'Incremental Costs',
                    static_costs: 'Static Costs',
                    optional_costs: 'Optional Costs',
                    requirements: 'Travel Requirements',
                    percent_raised: 'Percent Raised',
                    amount_raised: 'Amount Raised',
                    outstanding: 'Amount Outstanding',
                    deadlines: 'Other Deadlines',
                    desired_role: 'Role',
                    registered_at: 'Register Date',
                    updated_at: 'Last Updated'
                },
                exportFilters: {},
                layout: 'list',
                incomplete: [],
                startUp: true,
                lastReservationRequest: null
            }
        },
        watch: {
            'layout'(val, oldVal) {
                if (val !== oldVal && !this.startUp)
                    this.putConfig();
            },
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    if (this.startUp)
                        return;
                    this.putConfig();
                },
                deep: true
            },
            'search'(val, oldVal) {
                this.pagination.current_page = 1;
                this.getReservations();
            },
            'includeManaging'(val, oldVal) {
                if (val !== oldVal && !this.startUp) {
                    this.putConfig();
                    this.pagination.current_page = 1;
                    this.getReservations();
                }
            },
            'per_page'(val, oldVal) {
                if (this.startUp)
                    return;
                this.putConfig();
                this.getReservations();
            }
        },
        computed: {
            isFacilitator() {
                return this.trips.length > 0 ? true : false;
            }
        },
        methods: {
            getIncomplete(reservation) {
                return _.where(reservation.requirements.data, {status: 'incomplete'}).length;
            },
            complete(reservation) {
                return _.where(reservation.requirements.data, {status: 'complete'}).length;
            },
            attention(reservation) {
                return _.where(reservation.requirements.data, {status: 'attention'}).length;
            },
            reviewing(reservation) {
                return _.where(reservation.requirements.data, {status: 'reviewing'}).length;
            },
            country(code){
                return code;
            },
            getReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,requirements',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,

                };
                if (this.includeManaging) {
                    params.user = null;
                    params.trip = this.trips.length ? this.trips : new Array();
                } else {
                    params.user = !this.includeManaging ? new Array(this.userId) : null;
                    params.trip = null;
                }

                switch (this.type) {
                    case 'active':
                        params.current = true;
                        break;
                    case 'archive':
                        params.archived = true;
                        break;
                }
                $.extend(params, this.filters);

                if (this.groupOnly) {
//                    this.filters.groups = this.groupId;
                    params.groups = new Array(this.groupId);
                    params.trip = new Array(this.tripId);
                }

                this.exportFilters = params;

                this.$http.get('reservations', { params: params, before: function(xhr) {
                    if (this.lastReservationRequest) {
                        this.lastReservationRequest.abort();
                    }
                    this.lastReservationRequest = xhr;
                }}).then((response) => {
                    this.reservations = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            updateConfig(){
                window.localStorage['DashboardReservations'] = JSON.stringify({
                    layout: this.layout,
                    includeManaging: this.includeManaging,
                    per_page: this.per_page,
                    filters: {
                        type: this.filters.type,
                        groups: this.filters.groups,
                        campaign: this.filters.campaign,
                        gender: this.filters.gender,
                        status: this.filters.status,
                        shirtSize: this.filters.shirtSize,
                        hasCompanions: this.filters.hasCompanions,
                        todoName: this.filters.todoName,
                        todoStatus: this.filters.todoStatus,
                        requirementName: this.filters.requirementName,
                        requirementStatus: this.filters.requirementStatus,
                        dueName: this.filters.dueName,
                        dueStatus: this.filters.dueStatus,
                        rep: this.filters.rep,
                        age: this.filters.age,
                    }
                });

                this.$root.$emit('reservations-filters:update-storage');


            },
            resetFilter(){
                this.orderByField = 'surname';
                this.direction = 1;
                this.search = null;
                this.$root.$emit('reservations-filters:reset');
                this.filters = {
                    type: '',
                    role: '',
                    groups: [],
                    campaign: null,
                    user: [],
                    gender: '',
                    status: '',
                    shirtSize: [],
                    hasCompanions: null,
                    todoName: '',
                    todoStatus: null,
                    requirementName: '',
                    requirementStatus: '',
                    rep: '',
                    dueName: '',
                    dueStatus: '',
                    age: [0, 120],
                }
            },

        },
        mounted(){
            // load view state
            if (window.localStorage['DashboardReservations']) {
                let config = JSON.parse(window.localStorage['DashboardReservations']);
                this.layout = config.layout;
                this.per_page = config.per_page;
                this.filters = config.filters;
                this.includeManaging = config.includeManaging;
            }

            let userPromise = this.$http.get('users/' + this.userId, { params: {include: 'facilitating,managing.trips'}}).then((response) => {
                let user = response.data.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, (group) => {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }

                if (this.trips.length === 0) {
                    this.includeManaging = false;
                }

                if (this.groupOnly && this.groupId) {
                    this.includeManaging = true;
                }

                if (this.isFacilitator) {
                    // this.getCosts();
                    //this.getRequirements();
                    //this.getRoles();
                }

            });

            Promise.all([userPromise]).then((values) => {
                this.startUp = false;
                this.getReservations();
            });

        }
    }
</script>
