<template>
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" style="width:100%" v-model="tagsString"
                           :debounce="250" placeholder="Tag, tag2, tag3...">
                </div>

                <legend>By Designation</legend>
                <div class="form-group">
                    <v-select class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
                              :value.sync="groupObj" :options="groupsOptions" label="name"
                              placeholder="Filter by Group"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="reservationFilter" :debounce="250" :on-search="getReservations"
                              :value.sync="reservationObj" :options="reservationsOptions" label="given_names"
                              placeholder="Filter by Reservation"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
                              :value.sync="campaignObj" :options="campaignsOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="causesFilter" :debounce="250" :on-search="getCauses"
                              :value.sync="causeObj" :options="causesOptions" label="name"
                              placeholder="Filter by Cause"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="tripsFilter" :debounce="250" :on-search="getTrips"
                              :value.sync="tripObj" :options="tripsOptions" label="name"
                              placeholder="Filter by Trip"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="projectsFilter" :debounce="250" :on-search="getProjects"
                              :value.sync="projectObj" :options="projectsOptions" label="name"
                              placeholder="Filter by Project"></v-select>
                </div>

                <legend>By Account Holder</legend>
                <div class="form-group">
                    <v-select class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
                              :value.sync="groupAccountObj" :options="groupsOptions" label="name"
                              placeholder="Filter Groups"></v-select>
                </div>
                <div class="form-group">
                    <v-select class="form-control" id="userFilter" :debounce="250" :on-search="getUsers"
                              :value.sync="userObj" :options="usersOptions" label="name"
                              placeholder="Filter Users"></v-select>
                </div>


                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Start</span>
                                <input type="datetime-local" class="form-control" v-model="filters.starts"/>
                            </div>
                            <br>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">End</span>
                                <input type="datetime-local" class="form-control" v-model="filters.ends"/>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>

        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control  input-sm" v-model="per_page">
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
                                    <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')"> Name
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="company" :disabled="maxCheck('company')"> Company
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="phone" :disabled="maxCheck('phone')"> Phone
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="zip" :disabled="maxCheck('zip')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="total_donated" :disabled="maxCheck('total_donated')"> Amount
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
                        <span class="caret"></span>
                    </button>
                    <export-utility url="donors/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                </form>
            </div>
        </div>
        <hr class="divider sm">
        <div>
            <label>Active Filters</label>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.reservation && filters.reservation.length" @click="filters.reservation = ''" >
                Reservation
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.group && filters.group.length" @click="filters.group = ''" >
                Group
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.campaign && filters.campaign.length" @click="filters.campaign = ''" >
                Campaign
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.cause && filters.cause.length" @click="filters.cause = ''" >
                Cause
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.trip && filters.trip.length" @click="filters.trip = ''" >
                Trip
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.project && filters.project.length" @click="filters.project = ''" >
                Project
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.starts && filters.starts.length" @click="filters.starts = ''" >
                Starts
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.ends && filters.ends.length" @click="filters.ends = ''" >
                Ends
                <i class="fa fa-close"></i>
            </span>
        </div>
        <hr class="divider sm">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
                    Name
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('company')" :class="{'text-primary': orderByField === 'company'}">
                    Company
                    <i @click="setOrderByField('company')" v-if="orderByField !== 'company'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'company'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('email')" :class="{'text-primary': orderByField === 'email'}">
                    Email
                    <i @click="setOrderByField('email')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('phone')">
                    Phone
                </th>
                <th v-if="isActive('zip')">
                    Zip
                </th>
                <th v-if="isActive('total_donated')" :class="{'text-primary': orderByField === 'total_donated'}">
                    Amount
                    <i @click="setOrderByField('total_donated')" v-if="orderByField !== 'total_donated'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'total_donated'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="donor in donors|filterBy search|orderBy orderByField direction">
                <td v-if="isActive('name')" v-text="donor.name"></td>
                <td v-if="isActive('company')" v-text="donor.company"></td>
                <td v-if="isActive('email')" v-text="donor.email"></td>
                <td v-if="isActive('phone')" v-text="donor.phone|phone"></td>
                <td v-if="isActive('zip')" v-text="donor.zip"></td>
                <td v-if="isActive('total_donated')" v-text="donor.total_donated|currency"></td>
                <td><a href="/admin/donors/{{ donor.id }}"><i class="fa fa-cog"></i></a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination.sync="pagination" class="small" :callback="searchDonors"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<style>
	#toggleFilters li {
		margin-bottom: 3px;
	}

	@media (min-width: 991px) {
		.aside.left {
			left: 55px;
		}
	}
</style>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from "../../export-utility.vue";
    export default{
        name: 'admin-donors-list',
        components: {vSelect, exportUtility},
        props:{
            storageName: {
                type: String,
                default: 'AdminDonorsListConfig'
            },
        },
        data(){
            return {
                donors: [],
                orderByField: 'name',
                direction: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {
                    current_page: 1
                },
                search: '',
                activeFields: ['name', 'company', 'email', 'phone', 'zip', 'total_donated'],
                maxActiveFields: 8,

                // filter vars
                groupsOptions: [],
                groupObj: null,
                groupAccountObj: null,
                usersOptions: [],
                userObj: null,
                reservationsOptions: [],
                reservationObj: null,
                campaignsOptions: [],
                campaignObj: null,
                causesOptions: [],
                causeObj: null,
                tripsOptions: [],
                tripObj: null,
                projectsOptions: [],
                projectObj: null,
                filters: {
                    tags: [],
                    reservation:'',
                    group:'',
                    campaign:'',
                    cause:'',
                    trip:'',
                    project:'',
                    userAccount:'',
                    groupAccount:'',
                    starts:'',
                    ends:'',
                },
                showFilters: false,
                exportOptions: {
                    name: 'Name',
                    company: 'Company',
                    email: 'Email',
                    phone: 'Phone',
                    address: 'Address',
                    city: 'City',
                    state: 'State/Providence',
                    zip: 'Zip/Postal Code',
                    country: 'Country',
                    account_type: 'Account Type',
                    created: 'Created On',
                    updated: 'Updated On'
                },
                exportFilters: {}
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    console.log(val);
                    this.searchDonors();
                },
                deep: true
            },
            'campaignObj': function (val) {
                this.filters.campaign = val ? val.id : '';
                this.searchDonors();
            },
            'causeObj': function (val) {
                this.filters.cause = val ? val.id : '';
                this.searchDonors();
            },
            'tripObj': function (val) {
                this.filters.trip = val ? val.id : '';
                this.searchDonors();
            },
            'projectObj': function (val) {
                this.filters.project = val ? val.id : '';
                this.searchDonors();
            },
            'userObj': function (val) {
                this.filters.userAccount = val ? val.id : '';
                this.searchDonors();
            },
            'groupObj': function (val) {
                this.filters.group = val ? val.id : '';
                this.searchDonors();
            },
            'groupAccountObj': function (val) {
                this.filters.groupAccount = val ? val.id : '';
                this.searchDonors();
            },
            'reservationObj': function (val) {
                this.filters.reservation = val ? val.id : '';
                this.searchDonors();
            },
            'direction': function (val) {
                this.searchDonors();
            },
            'tagsString': function (val) {
                let tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchDonors();
            },
            'activeFields': function (val, oldVal) {
                // if the orderBy field is removed from view
                if(!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                this.updateConfig();
            },
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchDonors();
            },
            'per_page': function (val, oldVal) {
                this.searchDonors();
            },

        },
        methods: {
            updateConfig(){
                localStorage[this.storageName] = JSON.stringify({
                    activeFields: this.activeFields,
                    maxActiveFields: this.maxActiveFields,
                    per_page: this.per_page,
                    groupObj: this.groupObj,
                    userObj: this.userObj,
                    campaignObj: this.campaignObj,
                    causeObj: this.causeObj,
                    reservationObj: this.reservationObj,
                    tripObj: this.tripObj,
                    projectObj: this.projectObj,
                    filters: {
                        tags:this.filters.tags,
                        reservation: this.filters.reservation,
                        group: this.filters.group,
                        campaign: this.filters.campaign,
                        cause: this.filters.cause,
                        trip: this.filters.trip,
                        project: this.filters.project,
                        userAccount: this.filters.userAccount,
                        groupAccount: this.filters.groupAccount,
                        starts: this.filters.starts,
                        ends: this.filters.ends,
                    }
                });

            },
            isActive(field){
                return _.contains(this.activeFields, field);
            },
            maxCheck(field){
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
            setOrderByField(field){
                this.orderByField = field;
                this.direction = 1;
                this.searchDonors();
            },
            resetFilter(){
                $.extend(this, {
                    orderByField: 'name',
                    direction: 1,
                    per_page: 10,
                    perPageOptions: [5, 10, 25, 50, 100],
                    pagination: {
                        current_page: 1
                    },
                    search: '',
                    activeFields: ['name', 'company', 'email', 'phone', 'zip', 'total_donated'],
                    maxActiveFields: 8,
                    filters: {
                        tags: '',
                        reservation:'',
                        group:'',
                        campaign:'',
                        cause:'',
                        trip:'',
                        project:'',
                        userAccount:'',
                        groupAccount:'',
                        starts:'',
                        ends:'',
                    }
                });
            },
            getListSettings(){
                let params = {
                    include: '',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
                };


                $.extend(params, this.filters);

                this.exportFilters = params;

                return params;
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { search: search}).then(function (response) {
                    this.groupsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getReservations(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('reservations', { search: search}).then(function (response) {
                    this.reservationsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('campaigns', { search: search}).then(function (response) {
                    this.campaignsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCauses(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('causes', { search: search}).then(function (response) {
                    this.causesOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getTrips(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('trips', { search: search, include: 'group'}).then(function (response) {
                    this.tripsOptions = response.data.data;
                    _.each(this.tripsOptions, function (trip) {
                        trip.name = trip.type + ' | ' + trip.country_name + ' | ' + trip.group.data.name;
                    });
                    loading ? loading(false) : void 0;
                })
            },
            getProjects(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('projects', { search: search}).then(function (response) {
                    this.projectsOptions = response.data.data;
                    _.each(this.projectsOptions, function (project) {
                        project.name = project.plaque.prefix + ' ' + project.plaque.message;
                    });
                    loading ? loading(false) : void 0;
                })
            },
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('users', { search: search}).then(function (response) {
                    this.usersOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            searchDonors(){
                let params = this.getListSettings();
                // this.$refs.spinner.show();
                this.$http.get('donors', params).then(function (response) {
                    let self = this;
                    this.donors = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }).then(function () {
                    this.updateConfig();
                });
            }
        },
        ready() {
            // load view state
            if (localStorage[this.storageName]) {
                let config = JSON.parse(localStorage[this.storageName]);
                this.filters = config.filters;
            }
            // populate
            // this.$refs.spinner.show();
            this.getGroups();
            this.getCampaigns();
            this.searchDonors();

            //Manually handle dropdown functionality to keep dropdown open until finished
            $('.form-toggle-menu .dropdown-menu').on('click', function(event){
                let events = $._data(document, 'events') || {};
                events = events.click || [];
                for(let i = 0; i < events.length; i++) {
                    if(events[i].selector) {

                        //Check if the clicked element matches the event selector
                        if($(event.target).is(events[i].selector)) {
                            events[i].handler.call(event.target, event);
                        }

                        // Check if any of the clicked element parents matches the
                        // delegated event selector (Emulating propagation)
                        $(event.target).parents(events[i].selector).each(function(){
                            events[i].handler.call(this, event);
                        });
                    }
                }
                event.stopPropagation(); //Always stop propagation
            });

        }
    }
</script>