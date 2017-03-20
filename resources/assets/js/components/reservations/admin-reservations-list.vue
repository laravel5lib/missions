<template>
	<div>
		<aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Tags</label>
					<input type="text" class="form-control input-sm" style="width:100%" v-model="tagsString"
						   :debounce="250" placeholder="Tag, tag2, tag3...">
				</div>
				<div class="form-group">
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
							  :value.sync="groupsArr" :options="groupsOptions" label="name"
							  placeholder="Filter Groups"></v-select>
				</div>
				<div class="form-group">
					<v-select @keydown.enter.prevent=""  class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
							  :value.sync="usersArr" :options="usersOptions" label="name"
							  placeholder="Filter Users"></v-select>
				</div>
				<div class="form-group" v-if="!tripId">
					<v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
							  :value.sync="campaignObj" :options="campaignOptions" label="name"
							  placeholder="Filter by Campaign"></v-select>
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
				
				<!-- Todos -->
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
				<!-- end todos -->
				
				<!-- Trip Rep -->
				<div class="form-group">
				<label>Trip Rep</label>
					<select class="form-control input-sm" v-model="filters.rep" style="width:100%;">
						<option value="">Any Rep</option>
						<option v-for="(key, option) in repOptions" v-bind:value="key">
					    {{ option.name | capitalize }}
					  </option>
					</select>
				</div>
				<!-- end trip rep -->

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
			<span style="margin-right:2px;" class="label label-default" v-show="filters.campaign != ''" @click="filters.campaign = ''" >
				Campaign
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.gender != ''" @click="filters.gender = ''" >
				Gender
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.status != ''" @click="filters.status = ''" >
				Status
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.rep != ''" @click="filters.rep = ''" >
				Trip Rep.
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.shirtSize != ''" @click="filters.shirtSize = ''" >
				Shirt Size
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.hasCompanions !== null" @click="filters.hasCompanions = null" >
				Companions
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.todoName != ''" @click="filters.todoName = '', filters.todoStatus = null" >
				{{ todo }}
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.requirementName != ''" @click="filters.requirementName = '', filters.requirementStatus = ''" >
				{{ requirement }}
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.dueName != ''" @click="filters.dueName = '', filters.dueStatus = ''" >
				{{ due }}
				<i class="fa fa-close"></i>
			</span>
		</div>
        <hr class="divider sm">
		<div style="position:relative;">
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
				<tr v-for="reservation in reservations|filterBy search|orderBy orderByField direction">
					<td v-if="isActive('given_names')" v-text="reservation.given_names"></td>
					<td v-if="isActive('surname')" v-text="reservation.surname"></td>
					<td v-if="isActive('desired_role')" v-text="reservation.desired_role.name"></td>
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
					<td v-if="isActive('rep')" v-text="reservation.rep.data.name|capitalize"></td>
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
	export default{
		name: 'admin-reservations-list',
		components: {vSelect, exportUtility},
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
				page: 1,
				per_page: 10,
				perPageOptions: [5, 10, 25, 50, 100],
				pagination: {current_page: 1},
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
				todoOptions: [],
				requirementOptions: [],
				dueOptions: [],
				repOptions: [],
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
					tags: [],
					user: [],
					groups: [],
					campaign: '',
					gender: '',
					status: '',
					shirtSize: [],
					hasCompanions: null,
					due: '',
					todoName: '',
					todoStatus: null,
					requirementName: '',
					requirementStatus: '',
					dueName: '',
					dueStatus: '',
					rep: ''
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
					// payments: 'Payments Due',
					// applied_costs: 'Applied Costs',
					// requirements: 'Travel Requirements',
					percent_raised: 'Percent Raised',
					amount_raised: 'Amount Raised',
					outstanding: 'Outstanding',
					// deadlines: 'Other Deadlines'
					desired_role: 'Role'
				},
				exportFilters: {}
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
				if (this.filters.requirementStatus)
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
			'reservations': function (val) {
				if (val.length) {
					// use object/dictionary instead of array
					let arr = {};
					for (let index in val) {
						// duplicate rep ids will be overwritten
						if(val[index].rep)
							arr[val[index].rep.data.id] = val[index].rep.data;
					}
					this.repOptions = arr;
				}
			},
			'shirtSizeArr': function (val) {
				this.filters.shirtSize = _.pluck(val, 'id') || '';
			},
			'groupsArr': function (val) {
				this.filters.groups = _.pluck(val, 'id') || '';
//				this.searchReservations();
			},
			'usersArr': function (val) {
				this.filters.user = _.pluck(val, 'id') || '';
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
				if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
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
                this.updateConfig();
                this.searchReservations();
			},
			/*'groups':function () {
				this.searchReservations();
			}*/
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
						tags: this.filters.tags,
						user: this.filters.user,
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
				this.filters = {
					tags: [],
					user: [],
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
			country(code){
				return code;
			},
			age(birthday){
				return moment().diff(birthday, 'years')
			},
			getListSettings(){
				let params = {
					trip_id: this.tripId ? new Array(this.tripId) : undefined,
					include: 'trip.campaign,trip.group,costs.payments,user,requirements,rep',
					search: this.search,
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
					age: [this.ageMin, this.ageMax],
					todo: this.todo,
					requirement: this.requirement,
					due: this.due
				});

				this.exportFilters = params;

				return params;
			},
			searchReservations(){
				let params = this.getListSettings();
				// this.$refs.spinner.show();
				this.$http.get('reservations', {params: params}).then(function (response) {
					let self = this;
					_.each(response.body.data, function (reservation) {
						reservation.percent_raised = reservation.total_raised / reservation.total_cost * 100
					}, this);
					this.reservations = response.body.data;
					this.pagination = response.body.meta.pagination;
					// this.$refs.spinner.hide();
				}).then(function () {
					this.updateConfig();
					// this.$refs.spinner.hide();
				})
			},
			getGroups(search, loading){
				loading ? loading(true) : void 0;
				this.$http.get('groups', { params: {search: search} }).then(function (response) {
					this.groupsOptions = response.body.data;
					loading ? loading(false) : void 0;
				})
			},
			getCampaigns(search, loading){
				loading ? loading(true) : void 0;
				this.$http.get('campaigns', { params: {search: search} }).then(function (response) {
					this.campaignOptions = response.body.data;
					loading ? loading(false) : void 0;
				})
			},
			getUsers(search, loading){
				loading ? loading(true) : void 0;
				this.$http.get('users', { params: {search: search} }).then(function (response) {
					this.usersOptions = response.body.data;
					loading ? loading(false) : void 0;
				})
			},
			getTodos(){
				this.$http.get('todos', { params: {
					'type': 'reservations',
					'per_page': 100,
					'unique': true
				}}).then(function (response) {
					this.todoOptions = _.uniq(_.pluck(response.body.data, 'task'));
				});
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
			getCosts(){
				this.$http.get('costs', { params: {
					'assignment': 'trips',
					'per_page': 100,
					'unique': true
				}}).then(function (response) {
					this.dueOptions = _.uniq(_.pluck(response.body.data, 'name'));
				});
			}
		},
		ready(){
			// load view state
			if (localStorage[this.storageName]) {
				let config = JSON.parse(localStorage[this.storageName]);
				this.activeFields = config.activeFields;
                this.per_page = config.per_page;
                this.maxActiveFields = config.maxActiveFields;
				this.filters = config.filters;
			}
			// populate
			this.getGroups();
			this.getCampaigns();
			this.getCosts();
			this.getRequirements();
			this.getTodos();

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
