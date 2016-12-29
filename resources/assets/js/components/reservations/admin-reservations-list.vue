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
					<v-select class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
							  :value.sync="groupsArr" :options="groupsOptions" label="name"
							  placeholder="Filter Groups"></v-select>
				</div>
				<div class="form-group">
					<v-select class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
							  :value.sync="usersArr" :options="usersOptions" label="name"
							  placeholder="Filter Users"></v-select>
				</div>
				<div class="form-group" v-if="!tripId">
					<v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
							  :value.sync="campaignObj" :options="campaignOptions" label="name"
							  placeholder="Filter by Campaign"></v-select>
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
									<input type="checkbox" v-model="activeFields" value="given_names" :disabled="maxCheck('given_names')"> Given Names
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="surname" :disabled="maxCheck('surname')"> Surname
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="group" :disabled="maxCheck('group')"> Group
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="campaign" :disabled="maxCheck('campaign')"> Campaign
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="total_raised" :disabled="maxCheck('total_raised')"> Amout Raised
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="percent_raised" :disabled="maxCheck('percent_failed')"> Percent Raised
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="registered" :disabled="maxCheck('registered')"> Registered On
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="gender" :disabled="maxCheck('gender')"> Gender
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="age" :disabled="maxCheck('age')"> Age
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
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
					<export-utility url="reservations/export"
									:options="exportOptions"
									:filters="exportFilters">
					</export-utility>
                </form>
            </div>
        </div>
        <hr class="divider sm">
		<div>
			<label>Active Filters</label>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.tags.length" @click="filters.tags = []" >
				Tags
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.user.length" @click="filters.user = []" >
				Users
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.groups.length" @click="filters.groups = []" >
				Groups
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.campaign.length" @click="filters.campaign = ''" >
				Campaign
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.gender.length" @click="filters.gender = ''" >
				Gender
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.status.length" @click="filters.status = ''" >
				Status
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.shirtSize.length" @click="filters.shirtSize = ''" >
				Shirt Size
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.hasCompanions !== null" @click="filters.hasCompanions = null" >
				Companions
				<i class="fa fa-close"></i>
			</span>
		</div>
        <hr class="divider sm">
		<div>
			<spinner v-ref:spinner size="sm" text="Loading"></spinner>
			<table class="table table-striped">
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
					<th v-if="isActive('total_raised')" :class="{'text-primary': orderByField === 'total_raised'}">
						$ Raised
						<i @click="setOrderByField('total_raised')" v-if="orderByField !== 'total_raised'" class="fa fa-sort pull-right"></i>
						<i @click="direction=direction*-1" v-if="orderByField === 'total_raised'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
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
				<tbody v-if="reservations.length > 0">
				<tr v-for="reservation in reservations|filterBy search|orderBy orderByField direction">
					<td v-if="isActive('given_names')" v-text="reservation.given_names"></td>
					<td v-if="isActive('surname')" v-text="reservation.surname"></td>
					<td v-if="isActive('group')" v-text="reservation.trip.data.group.data.name|capitalize"></td>
					<td v-if="isActive('campaign')" v-text="reservation.trip.data.campaign.data.name|capitalize"></td>
					<td v-if="isActive('type')" v-text="reservation.trip.data.type|capitalize"></td>
					<td v-if="isActive('total_raised')" v-text="reservation.total_raised|currency"></td>
					<td v-if="isActive('percent_raised')">{{reservation.percent_raised|number '2'}}%</td>
					<td v-if="isActive('registered')" v-text="reservation.created_at|moment 'll'"></td>
					<td v-if="isActive('gender')" v-text="reservation.gender|capitalize"></td>
					<td v-if="isActive('status')" v-text="reservation.status|capitalize"></td>
					<td v-if="isActive('age')" v-text="age(reservation.birthday)"></td>
					<td v-if="isActive('email')" v-text="reservation.user.data.email|capitalize"></td>
					<td><a href="/admin/reservations/{{ reservation.id }}"><i class="fa fa-cog"></i></a></td>
				</tr>
				</tbody>
				<tbody v-else>
				<tr>
					<td colspan="10" class="text-center text-muted">
						No reservations found.
					</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="10" class="text-center">
						<pagination :pagination.sync="pagination"
									:callback="searchReservations"
									size="small">
						</pagination>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		<modal title="Export Reservations List" :show.sync="showExportModal" effect="zoom" width="400" ok-text="Export" :callback="exportList">
			<div slot="modal-body" class="modal-body">
				<ul class="list-unstyled">
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="given_names"> Given Names
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="surname"> Surname
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="group"> Group
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="campaign"> Campaign
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="type"> Type
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="total_raised"> Amout Raised
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="percent_raised"> Percent Raised
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="registered"> Registered On
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="gender"> Gender
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="status"> Status
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="age"> Age
						</label>
					</li>
					<li>
						<label class="small" style="margin-bottom: 0px;">
							<input type="checkbox" v-model="exportSettings.fields" value="email"> Email
						</label>
					</li>
				</ul>
			</div>
		</modal>
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
	import exportUtility from '../export-utility.vue';
	export default{
        name: 'admin-reservations-list',
		components: {vSelect, exportUtility},
		props:{
			tripId: {
				type: String,
				default: null
			},
			storageName: {
				type: String,
				default: 'AdminReservationsListConfig'
			},
			type: {
				type: String,
				default: 'current'
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
                pagination: { current_page: 1 },
                search: '',
				activeFields: ['given_names', 'surname', 'group', 'campaign', 'type', 'percent_raised'],
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
					hasCompanions:null
				},
				showFilters: false,
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
				exportFilters: {}
            }
        },
		computed: {
		},
        watch: {
			// watch filters obj
			'filters': {
				handler: function (val) {
					// console.log(val);
					this.pagination.current_page = 1;
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
//				this.searchReservations();
			},
			'usersArr': function (val) {
				this.filters.user = _.pluck(val, 'id')||'';
//				this.searchReservations();
			},
			'tagsString': function (val) {
				let tags = val.split(/[\s,]+/);
				this.filters.tags = tags[0] !== '' ? tags : '';
				this.searchReservations();
			},
			'ageMin': function (val) {
				this.searchReservations();
			},
			'ageMax': function (val) {
				this.searchReservations();
			},
			'direction': function (val) {
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
				this.page = 1;
				this.pagination.current_page = 1;
				this.searchReservations();
            },
            'page': function (val, oldVal) {
				this.searchReservations();
            },
            'per_page': function (val, oldVal) {
				this.searchReservations();
            },
			/*'groups':function () {
				this.searchReservations();
			}*/
        },
        methods: {
			consoleCallback (val) {
				console.dir(JSON.stringify(val))
			},
        	updateConfig(){
				localStorage[this.storageName] = JSON.stringify({
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
				this.searchReservations();
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
					hasCompanions:null
				}


			},
            country(code){
                return code;
            },
            age(birthday){
                return moment().diff(birthday, 'years')
            },
			getListSettings(){
				let params = {
					trip_id: this.tripId ? new Array(this.tripId) : undefined,
					include: 'trip.campaign,trip.group,fundraisers,costs.payments,user',
					search: this.search,
					per_page: this.per_page,
					page: this.pagination.current_page,
					sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
				};

				switch (this.type){
					case 'current':
						params.current = true;
						break;
					case 'archived':
						params.archived = true;
						break;
					case 'dropped':
						params.dropped = true;
						break;
				}


				$.extend(params, this.filters);
				$.extend(params, {
					age: [ this.ageMin, this.ageMax]
				});

				this.exportFilters = params;

				return params;
			},
            searchReservations(){
            	let params = this.getListSettings();
				// this.$refs.spinner.show();
				this.$http.get('reservations', params).then(function (response) {
                    let self = this;
                    _.each(response.data.data, function (reservation) {
                        reservation.percent_raised = reservation.total_raised / reservation.total_cost * 100
                    }, this);
                    this.reservations = response.data.data;
                    this.pagination = response.data.meta.pagination;
					// this.$refs.spinner.hide();
				}).then(function () {
					this.updateConfig();
					// this.$refs.spinner.hide();
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
			}
        },
        ready(){
            // load view state
			if (localStorage[this.storageName]) {
				let config = JSON.parse(localStorage[this.storageName]);
				this.activeFields = config.activeFields;
				this.maxActiveFields = config.maxActiveFields;
				this.filters = config.filters;
			}
			// populate
            this.getGroups();
            this.getCampaigns();

			// assign values from url search
			if (window.location.search !== '') {
				_.each(location.search.substr(1).split('&'), function (search) {
					let arr = search.split('=');
					switch (arr[0]) {
						case 'campaign':
							this.$http.get('campaigns/' + arr[1]).then(function (response) {
								this.campaignObj = response.data.data;
							});
							// this.campaignObj = _.findWhere(this.campaignOptions, {id: arr[1]})
					}
				}.bind(this));
			}

			this.searchReservations();

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