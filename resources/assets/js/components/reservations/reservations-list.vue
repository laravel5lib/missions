<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <label>Groups</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
                              :value.sync="groupsArr" :options="groupOptions" label="name"
                              placeholder="Filter Groups"></v-select>
                </div>

                <div class="form-group" v-if="!tripId">
                    <label>Campaign</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
                              :value.sync="campaignObj" :options="campaignOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>

                <div class="form-group">
                    <label>Trip Type</label>
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

                <template v-if="isFacilitator">
                    <div class="form-group">
                        <label>Desired Role</label>
                        <!--<select class="form-control input-sm" v-model="filters.requirementName" style="width:100%;">-->
                        <select class="form-control input-sm" id="desiredRole" v-model="filters.role">
                            <option value="">Any Role</option>
                            <option v-for="role in rolesArr" :value="role.value">{{role.name}}</option>
                        </select>
                        <!--</select>-->
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control input-sm" v-model="filters.gender" style="width:100%;">
                            <option value="">Any Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marital Status</label>
                        <select class="form-control input-sm" v-model="filters.status" style="width:100%;">
                            <option value="">Any Status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                        </select>
                    </div>

                    <!-- Cost/Payments -->
                    <div class="form-group">
                        <label>Applied Cost</label>
                        <select class="form-control input-sm" v-model="filters.dueName" style="width:100%;">
                            <option value="">Any Cost</option>
                            <option v-for="option in dueOptions" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group" v-if="filters.dueName">
                        <label>Payment Status</label>
                        <select class="form-control input-sm" v-model="filters.dueStatus" style="width:100%;">
                            <option value="">Any Status</option>
                            <option value="overdue">Overdue</option>
                            <option value="late">Late</option>
                            <option value="extended">Extended</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <!-- end cost/payments -->

                    <!-- Requirements -->
                    <div class="form-group">
                        <label>Requirements</label>
                        <select class="form-control input-sm" v-model="filters.requirementName" style="width:100%;">
                            <option value="">Any Requirement</option>
                            <option v-for="option in requirementOptions" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group" v-if="filters.requirementName">
                        <select class="form-control input-sm" v-model="filters.requirementStatus" style="width:100%;">
                            <option value="">Any Status</option>
                            <option value="incomplete">Incomplete</option>
                            <option value="reviewing">Reviewing</option>
                            <option value="attention">Attention</option>
                            <option value="complete">Complete</option>
                        </select>
                    </div>
                    <!-- end requirements -->

                    <div class="form-group">
                        <label>Shirt Size</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="ShirtSizeFilter" :value.sync="shirtSizeArr" multiple
                                  :options="shirtSizeOptions" label="name" placeholder="Shirt Sizes"></v-select>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Age Range</label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">Age Min</span>
                                    <input type="number" class="form-control" number v-model="ageMin" min="0">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">Max</span>
                                    <input type="number" class="form-control" number v-model="ageMax" max="120">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Travel Companions</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" name="companions" id="companions1" v-model="filters.hasCompanions" :value="null"> Any
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="companions" id="companions2" v-model="filters.hasCompanions" value="yes"> Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="companions" id="companions3" v-model="filters.hasCompanions" value="no"> No
                            </label>
                        </div>
                    </div>
                </template>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>
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
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
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
                                            {{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}<br>
                                            <label>{{ reservation.desired_role.name }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.campaign.data.name | capitalize }}<br>
                                            <label>{{ reservation.trip.data.country_name | capitalize }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.group.data.name | capitalize }}<br>
                                            <label>{{ reservation.trip.data.type | capitalize }}</label>
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
                            <pagination :pagination.sync="pagination" :callback="getReservations"></pagination>
                        </div>
                    </div>
                </template>
            </div>
    <div class="col-xs-12" v-if="reservations.length < 1">
        <p class="text-muted text-center"><em>Register for a trip to view your reservation details here!</em></p>
        <p class="text-center"><a class="btn btn-link btn-sm" href="/campaigns">Go On A Trip</a></p>
    </div>
</div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'reservations-list',
        components: {vSelect, exportUtility},
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
                rolesArr: [],
                groupsArr: [],
                groupOptions: [],
                campaignObj: null,
                campaignOptions: [],
                requirementOptions: [],
                dueOptions: [],
                shirtSizeArr: [],
                shirtSizeOptions: [
                    {id: 'XS', name: 'Extra Small'},
                    {id: 'S', name: 'Small'},
                    {id: 'M', name: 'Medium'},
                    {id: 'L', name: 'Large'},
                    {id: 'XL', name: 'Extra Large'},
                    {id: 'XXL', name: 'Extra Large X2'},
                ],
                ageMin: 0,
                ageMax: 120,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                filters: {
                    type: '',
                    groups: [],
                    campaign: '',
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
                    dueName: '',
                    dueStatus: '',
                    rep: '',
                    sort: 'created_at',
                    direction: 'desc'

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
            'layout': function (val, oldVal) {
                if (val !== oldVal && !this.startUp)
                    this.updateConfig();
            },
            // watch filters obj
            'filters': {
                handler: function (val) {
                    if (this.startUp)
                        return;
                    // console.log(val);
                    this.updateConfig();
                    this.pagination.current_page = 1;
                    this.getReservations();
                },
                deep: true
            },
            'groupsArr': function (val) {
                this.filters.groups = _.pluck(val, 'id')||'';
            },
            'campaignObj': function (val) {
                this.filters.campaign = val ? val.id : '';
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.getReservations();
            },
            'includeManaging': function (val, oldVal) {
                if (val !== oldVal && !this.startUp) {
                    this.updateConfig();
                    this.pagination.current_page = 1;
                    this.getReservations();
                }
            },
            'per_page': function (val, oldVal) {
                if (this.startUp)
                    return;
                this.updateConfig();
                this.getReservations();
            },
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
                }}).then(function (response) {
                    this.reservations = response.body.data;
                    this.pagination = response.body.meta.pagination;
                });
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { params: { search: search} }).then(function (response) {
                    this.groupOptions = response.body.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('campaigns', { params: { search: search} }).then(function (response) {
                    this.campaignOptions = response.body.data;
                    loading ? loading(false) : void 0;
                })
            },
            getRequirements(){
                this.$http.get('requirements', { params: {
                    'type': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then(function (response) {
                    this.requirementOptions = _.uniq(_.pluck(response.body.data, 'name'));
                });
            },
            getRoles(){
                this.$http.get('utilities/team-roles').then(function (response) {
                    _.each(response.body.roles, function (name, key) {
                        this.rolesArr.push({ value: key, name: name});
                    }.bind(this));
                });
            },
            getCosts(){
                this.$http.get('costs', { params: {
                    'assignment': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then(function (response) {
                    this.dueOptions = _.uniq(_.pluck(response.body.data, 'name'));
                });
            },
            updateConfig(){
                localStorage['DashboardReservations'] = JSON.stringify({
                    layout: this.layout,
                    includeManaging: this.includeManaging,
                    per_page: this.per_page,
                    ageMin: this.ageMin,
                    ageMax: this.ageMax,
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
                    }
                });

            },
            resetFilter(){
                this.orderByField = 'surname';
                this.direction = 1;
                this.search = null;
                this.ageMin = 0;
                this.ageMax = 120;
                this.groupsArr = [];
                this.usersArr = [];
                this.campaignObj = null;
                this.filters = {
                    type: '',
                    role: '',
                    groups: [],
                    campaign: '',
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
                    dueStatus: ''
                }


            },

        },
        ready(){
            // load view state
            if (localStorage['DashboardReservations']) {
                let config = JSON.parse(localStorage['DashboardReservations']);
                this.layout = config.layout;
                this.per_page = config.per_page;
                this.filters = config.filters;
                this.includeManaging = config.includeManaging;
            }

            let userPromise = this.$http.get('users/' + this.userId, { params: {include: 'facilitating,managing.trips'}}).then(function (response) {
                let user = response.body.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, function (group) {
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
                    this.getCosts();
                    this.getRequirements();
                    this.getRoles();
                }

            });

            Promise.all([userPromise]).then(function (values) {
                this.startUp = false;
                this.getReservations();
            }.bind(this));

        }
    }
</script>
