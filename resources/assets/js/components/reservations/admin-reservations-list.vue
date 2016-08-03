<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <div id="toggleFields" class="form-toggle-menu dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Fields
                        <span class="caret"></span>
                        </button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="given_names" :disabled="maxCheck('given_names')"> Given Names
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="surname" :disabled="maxCheck('surname')"> Surname
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="group" :disabled="maxCheck('group')"> Group
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="campaign" :disabled="maxCheck('campaign')"> Campaign
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="amount_raised" :disabled="maxCheck('amount_raised')"> Amout Raised
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="percent_raised" :disabled="maxCheck('percent_failed')"> Percent Raised
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="registered" :disabled="maxCheck('registered')"> Registered On
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="gender" :disabled="maxCheck('gender')"> Gender
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="age" :disabled="maxCheck('age')"> Age
								</label>
							</li>
							<li>
								<label style="padding: 3px 20px;">
									<input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
								</label>
							</li>
							<li role="separator" class="divider"></li>
							<li>
								<div class="input-group input-group-sm">
									<span class="input-group-addon">Max Visible Fields</span>
									<select class="form-control" v-model="maxActiveFields">
										<option v-for="option in maxActiveFieldsOptions" :value="option">{{option}}</option>
									</select>
								</div>
							</li>
						</ul>
                    </div>
                    <div id="toggleFilters" class="form-toggle-menu dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Filters
                        <span class="caret"></span>
                        </button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu2" style="min-width:300px;">
							<li>
								<input type="text" class="form-control" style="width:100%" v-model="tagsString"
									   :debounce="250" placeholder="Tag, tag2, tag3...">
							</li>
							<li>
								<v-select class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups()"
										  :value.sync="groupsArr" :options="groupsOptions" label="name"
										  placeholder="Filter Groups"></v-select>
							</li>
							<li>
								<v-select class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers()"
										  :value.sync="usersArr" :options="usersOptions" label="name"
										  placeholder="Filter Users"></v-select>
							</li>
							<li>
								<v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns()"
										  :value.sync="campaignObj" :options="campaignOptions" label="name"
										  placeholder="Filter by Campaign"></v-select>
							</li>
							<li>
								<select class="form-control" v-model="filters.gender" style="width:100%;">
									<option value="">Any Genders</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
							</li>

							<li>
								<select class="form-control" v-model="filters.status" style="width:100%;">
									<option value="">Any Status</option>
									<option value="single">Single</option>
									<option value="married">Married</option>
								</select>
							</li>

							<li>
								<v-select class="form-control" id="ShirtSizeFilter" :value.sync="shirtSizeArr" multiple
										  :options="shirtSizeOptions" label="name" placeholder="Filter Sizes"></v-select>
							</li>

							<li>
								<div class="row">
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
							</li>

							<li style="padding: 3px 20px;">
								<label class="control-label">Travel Companions</label>
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
							</li>

							<li style="padding: 3px 20px;">
								<label class="control-label">Passport</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports1" v-model="filters.hasPassport" :value="null"> Any
									</label>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports2" v-model="filters.hasPassport" value="yes"> Yes
									</label>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports3" v-model="filters.hasPassport" value="no"> No
									</label>
								</div>
							</li>

							<li role="separator" class="divider"></li>
							<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
						</ul>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Show</span>
                        <select class="form-control" v-model="per_page">
                            <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                        </select>
                    </div>
                    | <a class="btn btn-primary btn-sm" href="reservations/create"><i class="fa fa-plus"></i> New</a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover">
            <thead>
            <tr>
                <th v-if="isActive('given_names')" :class="{'text-primary': orderByField === 'given_names'}">
                    Given Names
                    <i @click="setOrderByField('given_names')" v-if="orderByField !== 'given_names'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'given_names'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('surname')" :class="{'text-primary': orderByField === 'surname'}">
                    Surname
                    <i @click="setOrderByField('surname')" v-if="orderByField !== 'surname'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'surname'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('group')" :class="{'text-primary': orderByField === 'trip.data.group.data.name'}">
                    Group
                    <i @click="setOrderByField('trip.data.group.data.name')" v-if="orderByField !== 'trip.data.group.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('campaign')" :class="{'text-primary': orderByField === 'trip.data.campaign.data.name'}">
                    Campaign
                    <i @click="setOrderByField('trip.data.campaign.data.name')" v-if="orderByField !== 'trip.data.campaign.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'trip.data.type'}">
                    Type
                    <i @click="setOrderByField('trip.data.type')" v-if="orderByField !== 'trip.data.type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('amount_raised')" :class="{'text-primary': orderByField === 'amount_raised'}">
                    $ Raised
                    <i @click="setOrderByField('amount_raised')" v-if="orderByField !== 'amount_raised'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'amount_raised'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('percent_raised')" :class="{'text-primary': orderByField === 'percent_raised'}">
                    % Raised
                    <i @click="setOrderByField('percent_raised')" v-if="orderByField !== 'percent_raised'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'percent_raised'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('registered')">
                    Registered On
                </th>
                <th v-if="isActive('gender')">
                    Gender
                </th>
                <th v-if="isActive('status')">
                    Status
                </th>
                <th v-if="isActive('age')">
                    Age
                </th>
                <th v-if="isActive('email')">
                    Email
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="reservation in reservations|filterBy search|orderBy orderByField direction">
                <td v-if="isActive('given_names')" v-text="reservation.given_names"></td>
                <td v-if="isActive('surname')" v-text="reservation.surname"></td>
                <td v-if="isActive('group')" v-text="reservation.trip.data.group.data.name|capitalize"></td>
                <td v-if="isActive('campaign')" v-text="reservation.trip.data.campaign.data.name|capitalize"></td>
                <td v-if="isActive('type')" v-text="reservation.trip.data.type|capitalize"></td>
                <td v-if="isActive('amount_raised')" v-text="reservation.amount_raised|currency"></td>
                <td v-if="isActive('percent_raised')">{{reservation.percent_raised|number '2'}}%</td>
                <td v-if="isActive('registered')" v-text="reservation.created_at|moment 'll'"></td>
                <td v-if="isActive('gender')" v-text="reservation.gender|capitalize"></td>
                <td v-if="isActive('status')" v-text="reservation.status|capitalize"></td>
                <td v-if="isActive('age')" v-text="age(reservation.birthday)"></td>
                <td v-if="isActive('email')" v-text="reservation.user.data.email|capitalize"></td>
                <!--<td>
                    <a href="/admin{{reservation.links[0].uri}}"><i class="fa fa-eye"></i></a>
                    <a href="/admin{{campaignId + reservation.links[0].uri}}/edit"><i class="fa fa-pencil"></i></a>
                </td>-->

            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li :class="{ 'disabled': pagination.current_page == 1 }">
                                    <a aria-label="Previous" @click="page=pagination.current_page-1">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                                <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                                    <a aria-label="Next" @click="page=pagination.current_page+1">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
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
</style>
<script>
	import vSelect from "vue-select";
	export default{
        name: 'admin-reservations-list',
		components: {vSelect},
		data(){
            return{
                reservations: [],
                orderByField: 'surname',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {},
                search: '',
				activeFields: ['given_names', 'surname', 'group', 'campaign', 'type', 'registered'],
				maxActiveFields: 6,
				maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],
				groupsArr: [],
				groupsOptions: [],
				usersArr: [],
				tagsArr: [],
				tagsString: '',
				usersOptions: [],
				campaignObj: null,
				campaignOptions: [],
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

				// filter vars
				filters: {
					tags:[],
					user: [],
                	groups: [],
					campaign: '',
					gender: '',
					status: '',
					shirtSize: [],
					hasCompanions:null,
					hasPassport:null,
				}
            }
        },
		computed: {
		},
        watch: {
			// watch filters obj
			'filters': {
				handler: function (val) {
					// console.log(val);
					this.searchReservations();
				},
				deep: true
			},
        	'campaignObj': function (val) {
				this.filters.campaign = val ? val.id : '';
			},
			'shirtSizeArr': function (val) {
				this.filters.shirtSize = _.pluck(val, 'id')||'';
			},
			'groupsArr': function (val) {
				this.filters.groups = _.pluck(val, 'id')||'';
				this.searchReservations();
			},
			'usersArr': function (val) {
				this.filters.user = _.pluck(val, 'id')||'';
				this.searchReservations();
			},
			'tagsString': function (val) {
				var tags = val.split(/[\s,]+/);
				this.filters.tags = tags[0] !== '' ? tags : '';
				this.searchReservations();
			},
			'ageMin': function (val) {
				this.searchReservations();
			},
			'ageMax': function (val) {
				this.searchReservations();
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
				this.updateConfig();
				this.page = 1;
                this.searchReservations();
            },
            'page': function (val, oldVal) {
				this.updateConfig();
				this.searchReservations();
            },
            'per_page': function (val, oldVal) {
				this.updateConfig();
				this.searchReservations();
            },
			'groups':function () {
				this.searchReservations();
			}
        },
        methods: {
			consoleCallback (val) {
				console.dir(JSON.stringify(val))
			},
        	updateConfig(){
				localStorage.AdminReservationsListConfig = JSON.stringify({
					activeFields: this.activeFields,
					maxActiveFields: this.maxActiveFields,
					per_page: this.per_page,
					ageMin: this.ageMin,
					ageMax: this.ageMax,
					groupsArr: this.groupsArr,
					tagsArr: this.tagsArr,
					usersArr: this.usersArr,
					campaignObj: this.campaignObj,
					filters: {
						tags:this.filters.tags,
						user: this.filters.user,
						groups: this.filters.groups,
						campaign: this.filters.campaign,
						gender: this.filters.gender,
						status: this.filters.status,
						shirtSize: this.filters.shirtSize,
						hasCompanions: this.filters.hasCompanions,
						hasPassport: this.filters.hasPassport,
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
                return this.orderByField = field, this.direction = 1;
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
				this.filters =  {
					tags:[],
					user: [],
					groups: [],
					campaign: '',
					gender: '',
					status: '',
					shirtSize: [],
					hasCompanions:null,
					hasPassport:null,
				}


			},
            country(code){
                return code;
            },
            totalAmountRaised(reservation){
                var total = 0;
                _.each(reservation.fundraisers.data, function (fundraiser) {
                    total += fundraiser.raised_amount;
                });
                return total;
            },
            totalPercentRaised(reservation){
                var totalDue = 0;
                _.each(reservation.costs.data, function (cost) {
                    totalDue += cost.amount;
                });
                return this.totalAmountRaised(reservation) / totalDue * 100;

            },
            age(birthday){
                return moment().diff(birthday, 'years')
            },
            searchReservations(){
            	var params = {
					include: 'trip.campaign,trip.group,fundraisers,costs.payments,user',
					search: this.search,
					per_page: this.per_page,
					page: this.page,
				};

				$.extend(params, this.filters);
				$.extend(params, {
					age: [ this.ageMin, this.ageMax]
				});
                this.$http.get('reservations', params).then(function (response) {
                    var self = this;
                    _.each(response.data.data, function (reservation) {
                        reservation.amount_raised = this.totalAmountRaised(reservation);
                        reservation.percent_raised = this.totalPercentRaised(reservation);
                    }, this);
                    this.reservations = response.data.data;
                    this.pagination = response.data.meta.pagination;
                })
            },
			getGroups(search, loading){
				loading ? loading(true) : void 0;
            	this.$http.get('groups', { search: search}).then(function (response) {
					this.groupsOptions = response.data.data;
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
			getUsers(search, loading){
				loading ? loading(true) : void 0;
				this.$http.get('users', { search: search}).then(function (response) {
					this.usersOptions = response.data.data;
					loading ? loading(false) : void 0;
				})
			},
        },
        ready(){
            // load view state
			if (localStorage.AdminReservationsListConfig) {
				var config = JSON.parse(localStorage.AdminReservationsListConfig);
				this.activeFields = config.activeFields;
				this.maxActiveFields = config.maxActiveFields;
			}
			// populate
            this.getGroups();
            this.getCampaigns();
            this.searchReservations();

			//Manually handle dropdown functionality to keep dropdown open until finished
			$('.form-toggle-menu .dropdown-menu').on('click', function(event){
				var events = $._data(document, 'events') || {};
				events = events.click || [];
				for(var i = 0; i < events.length; i++) {
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
