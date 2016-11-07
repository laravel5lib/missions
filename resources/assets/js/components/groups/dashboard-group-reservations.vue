<template>
    <div>
		<aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<input type="text" class="form-control input-sm" style="width:100%" v-model="tagsString"
						   :debounce="250" placeholder="Tag, tag2, tag3...">
				</div>
				<div class="form-group">
					<v-select class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
							  :value.sync="usersArr" :options="usersOptions" label="name"
							  placeholder="Filter Users"></v-select>
				</div>
				<div class="form-group">
					<select class="form-control input-sm" v-model="filters.gender" style="width:100%;">
						<option value="">Any Genders</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select>
				</div>

				<div class="form-group">
					<select class="form-control input-sm" v-model="filters.status" style="width:100%;">
						<option value="">Any Status</option>
						<option value="single">Single</option>
						<option value="married">Married</option>
					</select>
				</div>

				<div class="form-group">
					<v-select class="form-control" id="ShirtSizeFilter" :value.sync="shirtSizeArr" multiple
							  :options="shirtSizeOptions" label="name" placeholder="Shirt Sizes"></v-select>
				</div>

				<div class="form-group">
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
				</div>

				<div class="form-group" style="padding: 3px 20px;">
					<label class="control-label small">Travel Companions</label>
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

				<div class="form-group" style="padding: 3px 20px;">
					<label class="control-label small">Passport</label>
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
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>

		<div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
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
					<button class="btn btn-default btn-sm " type="button" @click="showFilters=!showFilters">
						Filters
						<span class="caret"></span>
					</button>
                    <!--<a class="btn btn-primary btn-sm" href="reservations/create">New <i class="fa fa-plus"></i> </a>-->
                </form>
            </div>
        </div>
        <hr class="divider sm">
		<div>
			Active Filters:
			<button type="button"class="btn btn-xs btn-default" v-show="filters.tags.length" @click="filters.tags = []" >
				Tags
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.user.length" @click="filters.user = []" >
				Users
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.groups.length" @click="filters.groups = []" >
				Groups
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.campaign.length" @click="filters.campaign = ''" >
				Campaign
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.gender.length" @click="filters.gender = ''" >
				Gender
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.status.length" @click="filters.status = ''" >
				Status
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.shirtSize.length" @click="filters.shirtSize = ''" >
				Shirt Size
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.hasCompanions !== null" @click="filters.hasCompanions = null" >
				Companions
				<span class="badge">x</span>
			</button>
			<button type="button"class="btn btn-xs btn-default" v-show="filters.hasPassport !== null" @click="filters.hasPassport = null" >
				Passport
				<span class="badge">x</span>
			</button>
		</div>
        <hr class="divider sm">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="panel panel-default" v-for="reservation in reservations|filterBy search|orderBy orderByField direction">
				<div class="panel-heading text-center">
					<h5>{{ reservation.given_names }} {{ reservation.surname }}</h5>
				</div>
				<div class="panel-body text-center">
					<img :src="reservation.avatar" class="img-circle img-lg">
					<hr class="divider inv sm">
					<h6 class=" text-uppercase text-center">Registered on <br>{{ reservation.created_at | moment 'll' }}</h6>
					<!--<div class="btn-group btn-group-justified">
						<a class="btn btn-sm btn-primary" href="/dashboard/groups{{reservation.links[0].uri}}"><i class="fa fa-pencil"></i> Manage</a>
					</div>-->
				</div>
				<div class="panel-footer text-center">
					<h5 class="text-capitalize">{{ reservation.percent_raised | number '2' }}% Raised</h5>
				</div>
			</div>
		</div>
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
<script>
	import vSelect from "vue-select";
	export default{
        name: 'dashboard-group-reservations',
		components: {vSelect},
		props:{
			tripId: {
				type: String,
				default: null
			}
		},
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
				},
				showFilters: false
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
					trip_id: this.tripId ? new Array(this.tripId) : undefined,
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
