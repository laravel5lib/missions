<template>
	<div>
		<hr class="divider inv sm">
		<form class="col-sm-12">
			<div class="form-group">
				<button class="btn btn-success btn-sm btn-block" type="button" @click="applyFilters">Apply Filters</button>
			</div>
			<template v-if="propertyExists('groups')">
				<template v-if="isAdminRoute">
					<div class="form-group">
						<label>Travel Groups</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
						          v-model="groupsArr" :options="groupsOptions" label="name"
						          placeholder="Filter Groups"></v-select>
					</div>
				</template>
				<template v-else>
					<div class="form-group">
						<label>Groups</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
						          v-model="groupsArr" :options="groupsOptions" label="name"
						          placeholder="Filter Groups"></v-select>
					</div>
				</template>
			</template>

			<!-- <div class="form-group" v-if="propertyExists('campaign')">
				<label>Campaign</label>
				<v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
				          v-model="campaignObj" :options="campaignOptions" label="name"
				          placeholder="Filter by Campaign"></v-select>
			</div> -->

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
                    <option value="sports">Sports</option>
				</select>
			</div>

			<template v-if="isAdminRoute || facilitator || teams || rooms || transports">

				<div class="form-group" v-if="propertyExists('role')">
					<label v-text="teams ? 'Role' : 'Desired Role'"></label>
					<v-select @keydown.enter.prevent="" class="form-control" id="roleFilter" :debounce="250" :on-search="getRoles"
					          v-model="roleObj" :options="UTILITIES.roles" label="name"
					          placeholder="Filter Roles"></v-select>
				</div>

				<div class="form-group" v-if="isAdminRoute && (propertyExists('users') || propertyExists('user'))">
					<label>Managing Users</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
					          v-model="usersArr" :options="usersOptions" label="name"
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

				<!-- Cost -->
				<template v-if="propertyExists('cost')">
					<div class="form-group">
						<label>Applied Cost</label>
						<select class="form-control input-sm" v-model="filters.cost" style="width:100%;">
							<option value="">Any Cost</option>
							<option v-for="cost in costs" :value="cost.id" :key="cost.id">
								{{ cost.name }}
							</option>
						</select>
					</div>
				</template>
				<!-- end cost -->

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

				<div class="form-group" v-if="isAdminRoute && propertyExists('region') && (campaignId || filters.campaign)">
					<label>Region Assignment</label>
                    <select  class="form-control input-sm" v-model="filters.region">
                        <option value="">Any</option>
                        <option :value="region.id" v-for="region in regionOptionsOrdered">{{ region.name}}</option>
                    </select>
				</div>

				<!-- Requirements -->
				<template v-if="!teams && !rooms && propertyExists('requirementName')">
					<div class="form-group">
						<label>Requirements</label>
						<select class="form-control input-sm" v-model="filters.requirementName" style="width:100%;">
							<option value="">Any Requirement</option>
							<option v-for="option in requirementOptions" :value="option">
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
							<option v-for="option in todoOptions" :value="option">
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
						<option v-for="option in repOptions" :value="option.id">
							{{ option.name|capitalize }}
						</option>
					</select>
				</div>
				<!-- end trip rep -->

				<div class="form-group" v-if="!teams && !rooms && propertyExists('shirtSize')">
					<label>Shirt Size</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="ShirtSizeFilter" v-model="shirtSizeArr" multiple
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

                <div class="form-group" v-if="(propertyExists('hasRoomInPlan') || propertyExists('noRoomInPlan'))">
                    <label>Rooms</label>
                    <div>
                        <label class="checkbox-inline" v-if="propertyExists('hasRoomInPlan')">
                            <input type="checkbox" v-model="hasRoomInPlan"> Has Room in Plan
                        </label>
                        <label class="checkbox-inline" v-if="propertyExists('noRoomInPlan')">
                            <input type="checkbox" v-model="noRoomInPlan"> No Room in Plan
                        </label>
                    </div>
                </div>

                <!-- Transports -->
                <template v-if="propertyExists('transportation')">
                    <div class="form-group">
                        <label>Transportation</label>
                        <select class="form-control input-sm" v-model="hasTransportation" style="width:100%;">
                            <option value="">Any</option>
                            <option value="yes">Has Transportation</option>
                            <option value="no">No Transportation</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="hasTransportation">
                        <label>Designation</label>
                        <select class="form-control input-sm" v-model="traveling" style="width:100%;">
                            <option value="">Any</option>
                            <option value="outbound">Outbound</option>
                            <option value="return">Return</option>
                        </select>
                    </div>
                </template>
                <!-- End Transports -->
			</template>

			<hr class="divider inv sm">
			<button class="btn btn-success btn-sm btn-block" type="button" @click="applyFilters">Apply Filters</button>
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
			value: {
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
            // We need to know the campaign we're scoped to
            campaignId: {
                type: String,
                default: null
            }
        },
        data(){
            return {
              filtersChanged: false,
              groupsArr: [],
                usersArr: [],
                shirtSizeArr: [],
                campaignObj: null,
                roleObj: null,
                groupsOptions: [],
                usersOptions: [],
                campaignOptions: [],
                regionOptions: [],
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
                costs: [],
                hasTransportation: null,
                traveling: null,
                filters: {},
                hasRoomInPlan: false,
                noRoomInPlan: false,
            }
        },
	    computed: {
            regionOptionsOrdered() {
                return _.sortBy(this.regionOptions, 'name');
            }
	    },
	    watch: {
            'value': {
                handler (val, oldVal) {
                    if (this.starter)
                        return;

                    if (val.campaign) {
                        if (this.campaignObj === null) {
                            this.campaignObj = _.findWhere(this.campaignOptions, { id: val.campaign });
                        }
                    }

                    //if (val !== oldVal) {
                        this.filters = val;
                        this.pagination.current_page = 1;
                        this.filtersChanged = true;

                    //}
                },
                deep: true
            },
		    filters:{
                handler(val, oldVal) {
                    if (val !== oldVal) {
                        this.$emit('input', val);
                        this.$emit('update:value', val);
                    }
                },
			    deep: true
		    },
            campaignObj (val) {
		        this.filters.campaign = val ? val.id : null;
		        if (val)
                    this.getRegions();
                    this.getCosts();
            },
            campaignId(val) {
                this.getRegions();
                this.getCosts();
            },
		    shirtSizeArr (val) {
                if ( this.propertyExists('shirtSize') )
                    this.filters.shirtSize = _.pluck(val, 'id') || [];
		     },
		    groupsArr(val) {
                if ( this.propertyExists('groups') )
                    this.filters.groups = _.pluck(val, 'id') || [];
		     },
		    usersArr (val) {
                if ( this.propertyExists('user') )
		            this.filters.user = _.pluck(val, 'id') || [];
		     },
            roleObj (val) {
                if ( this.propertyExists('role') )
                    this.filters.role = val ? val.value : '';
            },
            facilitator (val) {
                if (val) {
                    this.getRoles();
                }
            },
            hasRoomInPlan (val, oldVal) {
                if (val) {
                    this.filters.hasRoom = 'plans';
                } else {
                    this.filters.hasRoom = null;
                }
            },
            noRoomInPlan (val, oldVal) {
                if (val) {
                    this.filters.noRoom = 'plans';
                } else {
                    this.filters.noRoom = null;
                }
            },
            hasTransportation (val, oldVal) {
                if (val === 'yes') {
                    this.filters.inTransport = true;
                    this.filters.notInTransport = null;
                } else if (val === 'no') {
                    this.filters.notInTransport = true;
                    this.filters.inTransport = null;
                } else {
                    this.filters.inTransport = null;
                    this.filters.notInTransport = null;
                }
            },
            traveling (val, oldVal) {
                if (this.hasTransportation === 'yes') {
                    this.filters.traveling = val;
                    this.filters.notTraveling = null;
                    this.filters.inTransport = null;
                    this.filters.notInTransport = null;
                } else if(this.hasTransportation === 'no') {
                    this.filters.notTraveling = val;
                    this.filters.traveling = null;
                    this.filters.inTransport = null;
                    this.filters.notInTransport = null;
                } else {
                    this.filters.traveling = null;
                    this.filters.notTraveling = null;
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
                let promise = this.$http.get('groups', { params: {search: search} }).then((response) => {
                    this.groupsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }).catch(this.$root.handleApiError);
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('campaigns', { params: {search: search} }).then((response) => {
                    this.campaignOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }).catch(this.$root.handleApiError);
            },
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('users', { params: {search: search} }).then((response) => {
                    this.usersOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }).catch(this.$root.handleApiError);
            },
            getTodos(){
                return this.$http.get('todos', { params: {
                    'type': 'reservations',
                    'per_page': 100,
                    'unique': true
                }}).then((response) => {
                    this.todoOptions = _.uniq(_.pluck(response.data.data, 'task'));
                }).catch(this.$root.handleApiError);
            },
            getReps(){
                return this.$http.get('representatives', { params: {
                    'rep': true,
                    'per_page': 100
                }}).then((response) => {
                    this.repOptions = response.data.data;
                }).catch(this.$root.handleApiError);
            },
            getRequirements(){
                return this.$http.get('requirements', { params: {
                    'type': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then((response) => {
                    this.requirementOptions = _.uniq(_.pluck(response.data.data, 'name'));
                }).catch(this.$root.handleApiError);
            },
            getCosts(){
                let campaign = this.campaignId || this.filters.campaign;
                return this.$http.get('campaigns/'+campaign+'/costs', { params: {
                    'per_page': 100
                }}).then((response) => {
                    this.costs = response.data.data;
                }).catch(this.$root.handleApiError);
            },
            getRegions(){
                let campaign = this.campaignId || this.filters.campaign;
                return this.$http.get('campaigns/'+campaign+'/regions', { params: {
                    'campaign': campaign,
                    'per_page': 100,
                    'unique': true
                }}).then((response) => {
                    this.regionOptions = response.data.data;
                }).catch(this.$root.handleApiError);
            },
            applyFilters() {
              this.callback();
            }
        },
	    created(){

        },
        mounted(){
	        this.filters = this.value;
            let self = this, data;
            this.$root.$on('reservations-filters:reset', () =>  {
                // the reset callback handles reset of the filters object
	            // variables that influence the filters object need to be reset here
                self.groupsArr = [];
                self.usersArr = [];
                self.campaignObj = null;
            });

            this.$root.$on('reservations-filters:update:filter', function (filters) {
				self.filters = _.extend(self.filters, filters);
            });

            this.$root.$on('reservations-filters:update-storage', () =>  {
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
                promises.push(this.getRequirements());
            if (this.isAdminRoute)
                promises.push(this.getTodos());
            if (this.isAdminRoute)
                promises.push(this.getReps());
            if (this.facilitator && !this.isAdminRoute || this.teams)
                promises.push(this.getRoles());
            if (this.campaignId || this.filters.campaign) {
                promises.push(this.getRegions());
                promises.push(this.getCosts());
            }

            Promise.all(promises).then(() => {
                if (!self.starter)
                  self.filtersChanged = true;
            });

            // Load extra filter data from localStorage if it exists
            if (this.storage) {
              data = window.localStorage[self.storage] ? JSON.parse(window.localStorage[self.storage]) : {};
              if (data.hasOwnProperty('groupsArr')) {
                this.groupsArr = data.groupsArr;
              }
              console.log(this.groupsArr);
              if (data.hasOwnProperty('usersArr')) {
                this.usersArr = data.usersArr;
              }
              if (data.hasOwnProperty('campaignObj')) {
                this.campaignObj = data.campaignObj;
              }

            }
        }
    }
</script>