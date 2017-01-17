<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <v-select class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
                              :value.sync="groupsArr" :options="groupOptions" label="name"
                              placeholder="Filter Groups"></v-select>
                </div>
                <div class="form-group" v-if="!tripId">
                    <v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaignsg"
                              :value.sync="campaignObj" :options="campaignOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>
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
                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>
        <div class="row">
            <div class="col-xs-12">
                <form>
                <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        </div>
                    </div><!-- end col -->
                    <div class="form-group col-lg-8 col-md-8 col-sm-12">
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
            <div class="col-xs-12" style="position:relative">
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
                                    <p class="text-capitalize small" style="margin-top:2px;">{{ reservation.country_name }}</p>
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
                                            <label class="visible-xs">Name</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.campaign.data.name | capitalize }}<br>
                                            <label>{{ reservation.country_name | capitalize }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            {{ reservation.trip.data.group.data.name | capitalize }}<br>
                                            <label>{{ reservation.trip.data.type | capitalize }}</label>
                                            <hr class="divider inv sm visible-xs">
                                        </div><!-- end col -->
                                        <div class="col-sm-3">
                                            <span class="text-success small">
                                            {{ reservation.percent_raised }}%
                                            </span> <small>of {{ reservation.total_cost | currency }}</small>
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
        <p class="text-muted text-center"><em>No reservations found</em></p>
    </div>
</div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'reservations-list',
        components: {vSelect, exportUtility},
        props: ['userId', 'type'],
        data(){
            return {
                reservations: [],
                pagination: { current_page: 1},
                trips: [],
                includeManaging: false,
                search: '',
                showFilters: false,
                isFacilitator: false,
                groupsArr: [],
                groupOptions: [],
                campaignObj: null,
                campaignOptions: [],
                filters: {
                    groups: '',
                    campaign: '',
                    type: '',
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
                    trip_type: 'Trip Type',
                    campaign: 'Campaign',
                    group: 'Group',
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
                    payments: 'Payments Due',
                    applied_costs: 'Applied Costs',
                    requirements: 'Travel Requirements',
                    percent_raised: 'Percent Raised',
                    amount_raised: 'Amount Raised',
                    outstanding: 'Outstanding',
                    deadlines: 'Other Deadlines'
                },
                exportFilters: {},
                layout: 'list',
                incomplete: []
            }
        },
        watch: {
            'layout': function () {
                this.updateConfig();
            },
            // watch filters obj
            'filters': {
                handler: function (val) {
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
                this.pagination.current_page = 1;
                this.getReservations();
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
                    page: this.pagination.current_page
                };

                if (this.includeManaging) {
                    params.trip = this.trips;
                } else {
                    params.user = new Array(this.userId);
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

                this.exportFilters = params;

                this.$http.get('reservations', params).then(function (response) {
                    this.reservations = response.data.data
                    this.pagination = response.data.meta.pagination;
                });
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { search: search}).then(function (response) {
                    this.groupOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('campaigns', { search: search}).then(function (response) {
                    this.campaignOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            updateConfig(){
                localStorage['DashboardReservations'] = JSON.stringify({
                    layout: this.layout,
                    filters: {
                        groups: this.filters.groups,
                        campaign: this.filters.campaign,
                        type: this.filters.type,
                        sort: this.filters.sort,
                        direction: this.filters.direction,
                    }
                });

            }

        },
        ready(){
            // load view state
            if (localStorage['DashboardReservations']) {
                let config = JSON.parse(localStorage['DashboardReservations']);
                this.layout = config.layout;
                this.filters = config.filters;
            }

            this.$http.get('users/' + this.userId + '?include=facilitating,managing.trips').then(function (response) {
                let user = response.data.data;
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
            });

            this.getReservations();
        }
    }
</script>
