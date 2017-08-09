<template>
	<div>
		<mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
			<reservations-filters ref="filters" :filters.sync="filters" :reset-callback="resetFilter" :pagination.sync="pagination" :callback="searchReservations" :storage="storageName" :trip-specific="!!tripId"></reservations-filters>
		</mm-aside>

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
                        <input type="text" class="form-control" v-model="search" debounce="500" placeholder="Search for anything">
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
									<input type="checkbox" v-model="activeFields" value="desired_role" :disabled="maxCheck('desired_role')"> Role
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
									<input type="checkbox" v-model="activeFields" value="teams" :disabled="maxCheck('teams')"> Teams
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="fund" :disabled="maxCheck('fund')"> Fund
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
									<input type="checkbox" v-model="activeFields" value="dropped" :disabled="maxCheck('dropped')"> Dropped On
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
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="designation" :disabled="maxCheck('designation')"> Designation
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="requirements" :disabled="maxCheck('requirements')"> Requirements
								</label>
							</li>
							<li>
								<label class="small" style="margin-bottom: 0px;">
									<input type="checkbox" v-model="activeFields" value="rep" :disabled="maxCheck('rep')"> Trip Rep
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
					<reservation-reports :filters="exportFilters" :search="search"></reservation-reports>
                </form>
            </div>
        </div>
        <filters-indicator :filters.sync="filters" :requirement="requirement" :due="due"></filters-indicator>
        <hr class="divider sm">
		<div style="position:relative;">
			<spinner ref="spinner" size="sm" text="Loading"></spinner>
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
					<th v-if="isActive('desired_role')" :class="{'text-primary': orderByField === 'desired_role'}">
						Role
						<i @click="setOrderByField('desired_role')" v-if="orderByField !== 'desired_role'" class="fa fa-sort pull-right"></i>
						<i @click="direction=direction*-1" v-if="orderByField === 'desired_role'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
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
					<th v-if="isActive('teams')">
						Teams
					</th>
					<th v-if="isActive('fund')">
						Fund
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
						<i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
						<i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
					</th>
					<th v-if="isActive('dropped')">
						Dropped On
						<i @click="setOrderByField('deleted_at')" v-if="orderByField !== 'deleted_at'" class="fa fa-sort pull-right"></i>
						<i @click="direction=direction*-1" v-if="orderByField === 'deleted_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
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
						<i @click="setOrderByField('email')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
						<i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
					</th>
					<th v-if="isActive('designation')">
						Designation
					</th>
					<th v-if="isActive('requirements')">
						Requirements
					</th>
					<th v-if="isActive('rep')">
						Trip Rep
					</th>
					<th><i class="fa fa-cog"></i></th>
				</tr>
				</thead>
				<tbody v-if="reservations.length > 0">
				<tr v-for="reservation in reservations|orderBy orderByField direction">
					<td v-if="isActive('given_names')" v-text="reservation.given_names"></td>
					<td v-if="isActive('surname')" v-text="reservation.surname"></td>
					<td v-if="isActive('desired_role')" v-text="reservation.desired_role.name"></td>
					<td v-if="isActive('group')" v-text="reservation.trip.data.group.data.name ? reservation.trip.data.group.data.name[0].toUpperCase() + reservation.trip.data.group.data.name.slice(1) : ''"></td>
					<td v-if="isActive('campaign')" v-text="reservation.trip.data.campaign.data.name ? reservation.trip.data.campaign.data.name[0].toUpperCase() + reservation.trip.data.campaign.data.name.slice(1) : ''"></td>
					<td v-if="isActive('teams')">
						<template v-if="reservation.squads.data.length">
							<span v-for="squad in reservation.squads.data">
								<span v-if="squad.team_id">
									<span v-if="$index!=0">, </span>{{squad.team.data.callsign}}
								</span>
							</span>
						</template>
						<template v-else>
							No Team
						</template>
					</td>
					<td v-if="isActive('fund')"><a :href="'/admin/funds/'+reservation.fund.data.id" target="_blank">{{ reservation.fund.data.name ? reservation.fund.data.name[0].toUpperCase() + reservation.fund.data.name.slice(1) : '' }}</a></td>
					<td v-if="isActive('type')" v-text="reservation.trip.data.type ? reservation.trip.data.type[0].toUpperCase() + reservation.trip.data.type.slice(1) : ''"></td>
					<td v-if="isActive('total_raised')" v-text="reservation.total_raised|currency"></td>
					<td v-if="isActive('percent_raised')">{{reservation.percent_raised}}%</td>
					<td v-if="isActive('registered')" v-text="reservation.created_at|moment('ll')"></td>
					<td v-if="isActive('dropped')" v-text="reservation.deleted_at|moment('ll')"></td>
					<td v-if="isActive('gender')" v-text="reservation.gender ? reservation.gender[0].toUpperCase() + reservation.gender.slice(1) : ''"></td>
					<td v-if="isActive('status')" v-text="reservation.status ? reservation.status[0].toUpperCase() + reservation.status.slice(1) : ''"></td>
					<td v-if="isActive('age')" v-text="age(reservation.birthday)"></td>
					<td v-if="isActive('email')" v-text="reservation.user.data.email ? reservation.user.data.email[0].toUpperCase() + reservation.user.data.email.slice(1) : ''"></td>
					<td v-if="isActive('designation')" v-text="reservation.arrival_designation ? reservation.arrival_designation[0].toUpperCase() + reservation.arrival_designation.slice(1) : ''"></td>
					<td v-if="isActive('requirements')">
						<div style="position:relative;">
							<popover effect="fade" trigger="hover" placement="top" title="Complete" :content="complete(reservation).join('<br>')">
								<a href="/admin/reservations/{{ reservation.id }}/requirements"><span class="label label-success">{{ complete(reservation).length }}</span></a>
							</popover>
							<popover effect="fade" trigger="hover" placement="top" title="Needs Attention" :content="attention(reservation).join('<br>')">
								<a href="/admin/reservations/{{ reservation.id }}/requirements"><span class="label label-info">{{ attention(reservation).length }}</span></a>
							</popover>
							<popover effect="fade" trigger="hover" placement="top" title="Under Review" :content="reviewing(reservation).join('<br>')">
								<a href="/admin/reservations/{{ reservation.id }}/requirements"><span class="label label-default">{{ reviewing(reservation).length }}</span></a>
							</popover>
							<popover effect="fade" trigger="hover" placement="top" title="Incomplete" :content="getIncomplete(reservation).join('<br>')">
								<a href="/admin/reservations/{{ reservation.id }}/requirements"><span class="label label-danger" v-text="getIncomplete(reservation).length"></span></a>
							</popover>
						</div>
					</td>
					<td v-if="isActive('rep')" v-text="reservation.rep.data.name ? reservation.rep.data.name[0].toUpperCase() + reservation.rep.data.name.slice(1) : ''"></td>
					<td><a href="/admin/reservations/{{ reservation.id }}"><i class="fa fa-cog"></i></a></td>
				</tr>
				</tbody>
				<tbody v-else>
				<tr>
					<td colspan="10" class="text-center text-muted">
						Sign missionaries up for trips and view their reservations here!
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
	import reservationsFilters from '../filters/reservations-filters.vue';
	import filtersIndicator from '../filters/filters-indicator.vue';
	import reservationReports from '../admin/reporting/reservation-reports.vue';
	export default{
		name: 'admin-reservations-list',
		components: {vSelect, exportUtility, reservationReports, reservationsFilters, filtersIndicator},
		props: {
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
			return {
				reservations: [],
				orderByField: 'surname',
				direction: 1,
				per_page: 10,
				perPageOptions: [5, 10, 25, 50, 100],
				pagination: {current_page: 1},
				search: '',
				activeFields: ['given_names', 'surname', 'group', 'campaign', 'type', 'percent_raised'],
				maxActiveFields: 6,
				maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],
				// filter vars
				filters: {
                    type: '',
					//tags: [],
					user: [],
					groups: [],
					campaign: null,
					gender: '',
					status: '',
					shirtSize: [],
					hasCompanions: null,
                    hasRoomInPlan: null,
                    noRoomInPlan: null,
					due: '',
					todoName: '',
					todoStatus: null,
					designation: '',
					requirementName: '',
					requirementStatus: '',
					dueName: '',
					dueStatus: '',
					rep: '',
					age: [0, 120],
                    minPercentRaised: '',
                    maxPercentRaised: '',
                    minAmountRaised: '',
                    maxAmountRaised: '',
					transportation: true,
                    inTransport: null,
                    notInTransport: null,
                    traveling: null,
                    notTraveling: null,
					region: '',
                },
				showFilters: false,
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
					designation: 'Arrival Designation',
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
					promocodes: 'Promo Codes',
					registered_at: 'Register Date',
					updated_at: 'Last Updated',
					dropped_at: 'Drop Date'
				},
				exportFilters: {},
                lastReservationRequest: null
			}
		},
		computed: {
			'todo': function () {
				if (this.filters.todoStatus) {
					return this.filters.todoName + '|' + this.filters.todoStatus;
				} else {
					return this.filters.todoName;
				}
			},
			'requirement': function () {
				if (this.filters.requirementName && this.filters.requirementStatus)
					return this.filters.requirementName + '|' + this.filters.requirementStatus;

				return this.filters.requirementName;
			},
			'due': function () {
				if (this.filters.dueStatus)
					return this.filters.dueName + '|' + this.filters.dueStatus;

				return this.filters.dueName;
			}
			// 'rep': function() {
			// 	return this.reservation.rep.data.name;
			// 	// if (this.reservation.rep)
			// 	// 	return this.reservation.rep.data.name;
				
			// 	// return 'none';
			// }
		},
		watch: {
			// watch filters obj
			/*'tagsString': function (val) {
				let tags = val.split(/[\s,]+/);
				this.filters.tags = tags[0] !== '' ? tags : '';
				this.searchReservations();
			},*/
			'direction': function (val) {
				this.searchReservations();
			},
			'activeFields': function (val, oldVal) {
				// if the orderBy field is removed from view
				if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
					// default to first visible field
					this.orderByField = val[0];
				}
				this.updateConfig();
			},
			'search': function (val, oldVal) {
//                this.page = 1;
                this.pagination.current_page = 1;
                this.searchReservations();
			},
			'per_page': function (val, oldVal) {
                this.updateConfig();
                this.searchReservations();
			}
        },
        methods: {
        	getIncomplete(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'incomplete'}), function(req) {
                	return '&middot; ' + req.name;
                });
            },
            complete(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'complete'}), function(req) {
                	return '&middot; ' + req.name;
                });
            },
            attention(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'attention'}), function(req) {
                	return '&middot; ' + req.name;
                });
            },
            reviewing(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'reviewing'}), function(req) {
                	return '&middot; ' + req.name;
                });
            },
			consoleCallback (val) {
				console.dir(JSON.stringify(val))
			},
			updateConfig(){
                window.localStorage[this.storageName] = JSON.stringify({
					activeFields: this.activeFields,
					maxActiveFields: this.maxActiveFields,
					per_page: this.per_page,
                    //tagsArr: this.tagsArr,
					filters: {
                        type: this.filters.type,
                        //tags: this.filters.tags,
						user: this.filters.user,
						groups: this.filters.groups,
						campaign: this.filters.campaign,
						gender: this.filters.gender,
						status: this.filters.status,
						shirtSize: this.filters.shirtSize,
						hasCompanions: this.filters.hasCompanions,
                        hasRoom: this.filters.hasRoom,
                        noRoom: this.filters.noRoom,
						todoName: this.filters.todoName,
						todoStatus: this.filters.todoStatus,
						designation: this.filters.designation,
						requirementName: this.filters.requirementName,
						requirementStatus: this.filters.requirementStatus,
						dueName: this.filters.dueName,
						dueStatus: this.filters.dueStatus,
						rep: this.filters.rep,
						age: this.filters.age,
                        minPercentRaised: this.filters.minPercentRaised ? this.filters.minPercentRaised : null,
                        maxPercentRaised: this.filters.maxPercentRaised ? this.filters.maxPercentRaised : null,
                        minAmountRaised: this.filters.minAmountRaised ? this.filters.minAmountRaised : null,
                        maxAmountRaised: this.filters.maxAmountRaised ? this.filters.maxAmountRaised : null,
                        transportation: true,
                        inTransport: null,
                        notInTransport: null,
                        traveling: null,
                        notTraveling: null,
                        region: '',
                    }
				});

				this.$root.$emit('reservations-filters:update-storage');

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
				this.$root.$emit('reservations-filters:reset');
				this.filters = {
                    type: '',
					//tags: [],
					user: [],
					groups: [],
					campaign: null,
					gender: '',
					status: '',
					shirtSize: [],
					hasCompanions: null,
                    hasRoom: null,
                    noRoom: null,
					todoName: '',
					todoStatus: null,
					designation: '',
					requirementName: '',
					requirementStatus: '',
					rep: '',
					dueName: '',
					dueStatus: '',
					age: [0, 120],
                    minPercentRaised: '',
                    maxPercentRaised: '',
                    minAmountRaised: '',
                    maxAmountRaised: '',
					transportation: true,
                    inTransport: null,
                    notInTransport: null,
                    traveling: null,
                    notTraveling: null,
                    region: '',
                };
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
					include: 'trip.campaign,trip.group,costs.payments,user,requirements,rep,fund,squads.team',
					search: this.search ? this.search.trim() : this.search,
					per_page: this.per_page,
					page: this.pagination.current_page,
					sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
				};

				switch (this.type) {
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
                    todo: this.todo,
                    requirement: this.requirement,
                    due: this.due,
                });

				this.exportFilters = params;

				return params;
			},
			searchReservations(){
				let params = this.getListSettings();
				this.$http.get('reservations', {params: params, before: function(xhr) {
                    if (this.lastReservationRequest) {
                        this.lastReservationRequest.abort();
                    }
                    this.lastReservationRequest = xhr;
                }}).then(function (response) {
					this.reservations = response.body.data;
					this.pagination = response.body.meta.pagination;
				}).then(function () {
					this.updateConfig();
				});
			},

		},
		mounted(){
			// load view state
			if (window.localStorage[this.storageName]) {
				let config = JSON.parse(window.localStorage[this.storageName]);
				this.activeFields = _.extend(this.activeFields, config.activeFields);
                this.per_page = config.per_page;
                this.maxActiveFields = config.maxActiveFields;
				this.filters = _.extend(this.filters, config.filters);
			}

			// assign values from url search
			if (window.location.search !== '') {
				_.each(location.search.substr(1).split('&'), function (search) {
					let arr = search.split('=');
					switch (arr[0]) {
						case 'campaign':
							this.$http.get('campaigns/' + arr[1]).then(function (response) {
								this.campaignObj = response.body.data;
							});
							// this.campaignObj = _.findWhere(this.campaignOptions, {id: arr[1]})
					}
				}.bind(this));
			}

            if (!this.$refs.filters)
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
<style type="text/scss">
.popover {
	width: 200px !important;
	min-width: 200px !important;
	max-width: 400px !important;
}
</style>
