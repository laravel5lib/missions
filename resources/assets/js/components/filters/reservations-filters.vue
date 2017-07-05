<template>
	<div>
		<hr class="divider inv sm">
		<form class="col-sm-12">
			<template v-if="propertyExists('groups')">
				<template v-if="isAdminRoute">
					<div class="form-group">
						<label>Travel Groups</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
						          :value.sync="groupsArr" :options="groupsOptions" label="name"
						          placeholder="Filter Groups"></v-select>
					</div>
				</template>
				<template v-else>
					<div class="form-group">
						<label>Groups</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
						          :value.sync="groupsArr" :options="groupsOptions" label="name"
						          placeholder="Filter Groups"></v-select>
					</div>
				</template>
			</template>

			<div class="form-group" v-if="propertyExists('campaign')">
				<label>Campaign</label>
				<v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
				          :value.sync="campaignObj" :options="campaignOptions" label="name"
				          placeholder="Filter by Campaign"></v-select>
			</div>

			<div class="form-group" v-if="propertyExists('type')">
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

			<template v-if="isAdminRoute || facilitator || teams || rooms || transports">

				<div class="form-group" v-if="propertyExists('role') && (!isAdminRoute || transports)">
					<label v-text="teams ? 'Role' : 'Desired Role'"></label>
					<v-select @keydown.enter.prevent="" class="form-control" id="roleFilter" :debounce="250" :on-search="getRolesSearch"
					          :value.sync="roleObj" :options="UTILITIES.roles" label="name"
					          placeholder="Filter Roles"></v-select>

					<!--<select class="form-control input-sm" id="desiredRole" v-model="filters.role">
						<option value="">Any Role</option>
						<option v-for="role in UTILITIES.roles" :value="role.value">{{role.name}}</option>
					</select>-->
				</div>

				<div class="form-group" v-if="isAdminRoute && (propertyExists('users') || propertyExists('user'))">
					<label>Managing Users</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
					          :value.sync="usersArr" :options="usersOptions" label="name"
					          placeholder="Filter Users"></v-select>
				</div>

				<div class="form-group" v-if="propertyExists('gender')">
					<label>Gender</label>
					<select class="form-control input-sm" v-model="filters.gender" style="width:100%;">
						<option value="">Any Genders</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select>
				</div>

				<div class="form-group" v-if="propertyExists('status')">
					<label>Marital Status</label>
					<select class="form-control input-sm" v-model="filters.status" style="width:100%;">
						<option value="">Any Status</option>
						<option value="single">Single</option>
						<option value="married">Married</option>
					</select>
				</div>

				<!-- Cost/Payments -->
				<template v-if="!teams && propertyExists('dueName')">
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
				</template>
				<!-- end cost/payments -->

				<div class="form-group" v-if="(isAdminRoute || teams || rooms) && propertyExists('designation')">
					<label>Arrival Designation</label>
					<select  class="form-control input-sm" v-model="filters.designation">
						<option value="">Any</option>
						<option value="eastern">Eastern</option>
						<option value="western">Western</option>
						<option value="international">International</option>
                        <option value="weekend">Weekend</option>
                        <option value="other">Other</option>
                        <option value="none">None</option>
					</select>
				</div>

				<!-- Requirements -->
				<template v-if="!teams && !rooms && propertyExists('requirementName')">
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
				</template>

				<!-- end requirements -->

				<!-- Todos -->
				<template v-if="isAdminRoute && !teams && !rooms && propertyExists('todoName')">
					<div class="form-group">
						<label>Todos</label>
						<select class="form-control input-sm" v-model="filters.todoName" style="width:100%;">
							<option value="">Any Todo</option>
							<option v-for="option in todoOptions" v-bind:value="option">
								{{ option }}
							</option>
						</select>
					</div>
					<div class="form-group" v-if="filters.todoName">
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions1" v-model="filters.todoStatus" :value="null"> Any
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions2" v-model="filters.todoStatus" value="complete"> Complete
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions3" v-model="filters.todoStatus" value="incomplete"> Incomplete
						</label>
					</div>
				</template>

				<!-- end todos -->

				<!-- Trip Rep -->
				<div class="form-group" v-if="isAdminRoute && propertyExists('rep')">
					<label>Trip Rep</label>
					<select class="form-control input-sm" v-model="filters.rep" style="width:100%;">
						<option value="">Any Rep</option>
						<option v-for="option in repOptions" v-bind:value="option.id">
							{{ option.name | capitalize }}
						</option>
					</select>
				</div>
				<!-- end trip rep -->

				<div class="form-group" v-if="!teams && !rooms && propertyExists('shirtSize')">
					<label>Shirt Size</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="ShirtSizeFilter" :value.sync="shirtSizeArr" multiple
					          :options="shirtSizeOptions" label="name" placeholder="Shirt Sizes"></v-select>
				</div>

				<div class="form-group" v-if="propertyExists('age')">
					<div class="row">
						<div class="col-xs-12">
							<label>Age Range</label>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Age Min</span>
								<input type="number" class="form-control" number v-model="filters.age[0]" debounce="250" min="0">
							</div>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Max</span>
								<input type="number" class="form-control" number v-model="filters.age[1]" debounce="250" max="120">
							</div>
						</div>
					</div>
				</div>

                <div class="form-group" v-if="propertyExists('minPercentRaised')">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Percent Raised</label>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Min</span>
                                <input type="number" class="form-control"  v-model="filters.minPercentRaised" min="0">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Max</span>
                                <input type="number" class="form-control"  v-model="filters.maxPercentRaised" min="0">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" v-if="propertyExists('minAmountRaised')">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Amount Raised</label>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Min $</span>
                                <input type="number" class="form-control"  v-model="filters.minAmountRaised" min="0">
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Max $</span>
                                <input type="number" class="form-control"  v-model="filters.maxAmountRaised" min="0">
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="form-group" v-if="propertyExists('hasCompanions')">
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

                <div class="form-group" v-if="!transports">
                    <label>Rooms</label>
                    <div>
                        <label class="checkbox-inline">
                            <input type="checkbox" v-model="hasRoomInPlan"> Has Room in Plan
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" v-model="noRoomInPlan"> No Room in Plan
                        </label>
                    </div>
                </div>
			</template>

			<hr class="divider inv sm">
			<button class="btn btn-default btn-sm btn-block" type="button" @click="resetCallback"><i class="fa fa-times"></i> Reset Filters</button>
		</form>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
	import vSelect from 'vue-select';
	import utilities from '../utilities.mixin';
    export default{
        name: 'reservations-filters',
        mixins: [utilities],
        components: {vSelect},
	    props: {
            // Main object that contains all filters used by the parent component for API calls
			filters: {
			    type: Object,
				required: true,
				default: null
			},
		    // Reset function used to restore defaults of variables that are needed for filters,
		    // but are not in the filters object
		    resetCallback: {
			    type: Function,
			    required: true
		    },
		    // Pagination of the reservations list that the filters variable influences
            pagination: {
                type: Object,
                required: true
            },
		    // This is references the function that would/should be run when filters variable is updated
            callback: {
                type: Function,
                required: true
            },
		    // This variabe is the name of the localStorage property that holds the config of the parent component
            storage: {
                type: String,
                required: false
            },
		    // This prop is used to ignore watching filters var for changes until the parent component is ready
		    starter: {
			    type: Boolean,
			    required: false,
			    default: false
		    },
		    // We need to know if the user is a facilitator
		    facilitator: {
			    type: Boolean,
			    default: false
		    },
		    // There are some uses of reservations lists specific to trips
		    tripSpecific: {
			    type: Boolean,
			    default: false
		    },
		    // We need to know if the user is managing teams
		    teams: {
			    type: Boolean,
			    default: false
		    },
		    // We need to know if the user is managing rooming
		    rooms: {
			    type: Boolean,
			    default: false
		    },
		    // We need to know if the user is managing transports
            transports: {
			    type: Boolean,
			    default: false
		    },

        },
        data(){
            return {
                groupsArr: [],
                usersArr: [],
                shirtSizeArr: [],
                campaignObj: null,
                roleObj: null,
                groupsOptions: [],
                usersOptions: [],
                campaignOptions: [],
                shirtSizeOptions: [
                    {id: 'XS', name: 'Extra Small'},
                    {id: 'S', name: 'Small'},
                    {id: 'M', name: 'Medium'},
                    {id: 'L', name: 'Large'},
                    {id: 'XL', name: 'Extra Large'},
                    {id: 'XXL', name: 'Extra Large X2'},
                ],
                todoOptions: [],
                requirementOptions: [],
                repOptions: [],
                dueOptions: [],
            }
        },
	    watch: {
            'filters': {
                handler: function (val) {
                    if (this.starter)
                        return;
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.callback();
                },
                deep: true
            },
            'campaignObj': function (val) {
		        this.filters.campaign = val ? val.id : null;
		     },
		    'shirtSizeArr': function (val) {
		        this.filters.shirtSize = _.pluck(val, 'id') || [];
		     },
		    'groupsArr': function (val) {
		        this.filters.groups = _.pluck(val, 'id') || [];
		     },
		    'usersArr': function (val) {
		        this.filters.user = _.pluck(val, 'id') || [];
		     },
            'roleObj': function (val) {
                this.filters.role = val ? val.value : '';
            },
            'facilitator': function (val) {
                if (val) {
                    this.getRoles();
                }
            },
            'hasRoomInPlan': function (val, oldVal) {
                if (val) {
                    this.filters.hasRoom = 'plans';
                } else {
                    this.filters.hasRoom = null;
                }
            },
            'noRoomInPlan': function (val, oldVal) {
                if (val) {
                    this.filters.noRoom = 'plans';
                } else {
                    this.filters.noRoom = null;
                }
            }

	    },
        methods: {
            propertyExists(property) {
                // Instead of concerning the filters component about where it is
	            // It is may be best to behave based on the filter object passed in
	            // We can ho this be checking whether a property exists in it
	            return this.filters && this.filters.hasOwnProperty(property);
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('groups', { params: {search: search} }).then(function (response) {
                    this.groupsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }, this.$root.handleApiError);
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('campaigns', { params: {search: search} }).then(function (response) {
                    this.campaignOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }, this.$root.handleApiError);
            },
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('users', { params: {search: search} }).then(function (response) {
                    this.usersOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }, this.$root.handleApiError);
            },
            getTodos(){
                return this.$http.get('todos', { params: {
                    'type': 'reservations',
                    'per_page': 100,
                    'unique': true
                }}).then(function (response) {
                    this.todoOptions = _.uniq(_.pluck(response.body.data, 'task'));
                }, this.$root.handleApiError);
            },
            getReps(){
                return this.$http.get('users', { params: {
                    'rep': true,
                    'per_page': 100
                }}).then(function (response) {
                    this.repOptions = response.body.data;
                }, this.$root.handleApiError);
            },
            getRequirements(){
                return this.$http.get('requirements', { params: {
                    'type': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then(function (response) {
                    this.requirementOptions = _.uniq(_.pluck(response.body.data, 'name'));
                }, this.$root.handleApiError);
            },
            getCosts(){
                return this.$http.get('costs', { params: {
                    'assignment': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then(function (response) {
                    this.dueOptions = _.uniq(_.pluck(response.body.data, 'name'));
                }, this.$root.handleApiError);
            }

        },
	    created(){

        },
        ready(){
            let self = this;
            this.$root.$on('reservations-filters:reset', function () {
                // the reset callback handles reset of the filters object
	            // variables that influence the filters object need to be reset here
                self.groupsArr = [];
                self.usersArr = [];
                self.campaignObj = null;
            });

            this.$root.$on('reservations-filters:update:filter', function (filters) {
				self.filters = _.extend(self.filters, val);
            });

            this.$root.$on('reservations-filters:update-storage', function () {
                if (self.storage) {
                    let config = window.localStorage[self.storage] ? JSON.parse(window.localStorage[self.storage]) : {};
                    config = _.extend(config, {
                        usersArr: this.usersArr,
                        groupsArr: this.groupsArr,
                        campaignObj: this.campaignObj,
                    });
                    window.localStorage[self.storage] = JSON.stringify(config);
                } else {
                    console.error('reservations-filters: Please set storage attribute.');
                }
            });

            let promises = [];
            // populate
            promises.push(this.getGroups());
            promises.push(this.getCampaigns());

            if (this.isAdminRoute || this.facilitator)
                promises.push(this.getCosts());
            if (this.isAdminRoute || this.facilitator)
                promises.push(this.getRequirements());
            if (this.isAdminRoute)
                promises.push(this.getTodos());
            if (this.isAdminRoute)
                promises.push(this.getReps());
            if (this.facilitator && !this.isAdminRoute || this.teams)
                promises.push(this.getRoles());

            Promise.all(promises).then(function () {
                if (!self.starter)
                    self.callback()
            });
        }
    }
</script>