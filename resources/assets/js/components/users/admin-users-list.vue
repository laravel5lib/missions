<template>
	<div style="position:relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="row">
			<div class="col-sm-12">
				<form class="form-inline" novalidate>
					<div class="form-inline" style="display: inline-block;">
						<div class="form-group">
							<label>Show</label>
							<select class="form-control input-sm" v-model="per_page">
								<option v-for="option in perPageOptions" :value="option">{{option}}</option>
							</select>
						</div>
					</div>
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="search" debounce="250"
						       placeholder="Search for anything">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
					<div id="toggleFields" class="form-toggle-menu dropdown" style="display: inline-block;">
						<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1"
						        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							Sort By
							<span class="caret"></span>
						</button>
						<ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="name"
									       :disabled="maxCheck('name')"> Name
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="email"
									       :disabled="maxCheck('email')"> Email
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="alt_email"
									       :disabled="maxCheck('alt_email')"> Alt Email
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="gender"
									       :disabled="maxCheck('gender')"> Gender
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="status"
									       :disabled="maxCheck('status')"> Status
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="birthday"
									       :disabled="maxCheck('birthday')"> Birthday
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="phone_one"
									       :disabled="maxCheck('phone_one')"> Phone 1
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="phone_two"
									       :disabled="maxCheck('phone_two')"> Phone 2
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="street"
									       :disabled="maxCheck('street')"> Street
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="city"
									       :disabled="maxCheck('city')"> City
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="zip"
									       :disabled="maxCheck('zip')"> Zip
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="state"
									       :disabled="maxCheck('state')"> State
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="country_name"
									       :disabled="maxCheck('country_name')"> Country
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="public"
									       :disabled="maxCheck('public')"> Public
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="isAdmin"
									       :disabled="maxCheck('isAdmin')"> Admin
								</label>
							</li>
							<li>
								<label style="margin-bottom: 0" class="small">
									<input type="checkbox" v-model="activeFields" value="timezone"
									       :disabled="maxCheck('timezone')"> Timezone
								</label>
							</li>
							<li role="separator" class="divider"></li>
							<li>
								<div class="input-group input-group-sm text-center">
									<label>Max Visible Fields</label>
									<select class="form-control" v-model="maxActiveFields">
										<option v-for="option in maxActiveFieldsOptions" :value="option">{{option}}
										</option>
									</select>
								</div>
							</li>
						</ul>
					</div>
					<div id="toggleFilters" class="form-toggle-menu dropdown" style="display: inline-block;">
						<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu2"
						        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							Filters
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu2"
						    style="min-width:300px; max-height: 575px; padding: 10px 20px; overflow: scroll;">
							<li>
								<v-select @keydown.enter.prevent="" class="form-control" id="countryFilter" multiple
								          :debounce="250" :on-search="getCountries"
								          v-model="countriesArr" :options="UTILITIES.countries" label="name"
								          placeholder="Filter Countries"></v-select>
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

							<li style="padding: 3px 20px;">
								<label class="control-label">Public</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="isPublic" id="isPublic1" v-model="filters.isPublic"
										       :value="null"> Any
									</label>
									<label class="radio-inline">
										<input type="radio" name="isPublic" id="isPublic2" v-model="filters.isPublic"
										       value="yes"> Yes
									</label>
									<label class="radio-inline">
										<input type="radio" name="isPublic" id="isPublic3" v-model="filters.isPublic"
										       value="no"> No
									</label>
								</div>
							</li>

							<li style="padding: 3px 20px;">
								<label class="control-label">Admin</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports1" v-model="filters.isAdmin"
										       :value="null"> Any
									</label>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports2" v-model="filters.isAdmin"
										       value="yes"> Yes
									</label>
									<label class="radio-inline">
										<input type="radio" name="passports" id="passports3" v-model="filters.isAdmin"
										       value="no"> No
									</label>
								</div>
							</li>

							<li role="separator" class="divider"></li>
							<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()">
								Reset Filters
							</button>
						</ul>
						<export-utility url="users/export"
						                :options="exportOptions"
						                :filters="exportFilters">
						</export-utility>
						<!-- <import-utility title="Import Users List"
										url="users/import"
										:required-fields="importRequiredFields"
										:optional-fields="importOptionalFields">
						</import-utility> -->
					</div>
				</form>
			</div>
		</div>
		<hr>
		<table class="table table-hover table-striped">
			<thead>
			<tr>
				<th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
					Name
					<i @click="setOrderByField('name')" v-if="orderByField !== 'name'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('email')" :class="{'text-primary': orderByField === 'email'}">
					Email
					<i @click="setOrderByField('email')" v-if="orderByField !== 'email'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('alt_email')" :class="{'text-primary': orderByField === 'alt_email'}">
					Alt Email
					<i @click="setOrderByField('alt_email')" v-if="orderByField !== 'alt_email'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'alt_email'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('gender')">
					Gender
				</th>
				<th v-if="isActive('status')">
					Status
				</th>
				<th v-if="isActive('birthday')" :class="{'text-primary': orderByField === 'birthday'}">
					Birthday
					<i @click="setOrderByField('birthday')" v-if="orderByField !== 'birthday'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'birthday'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('phone_one')">
					Phone 1
				</th>
				<th v-if="isActive('phone_two')">
					Phone 2
				</th>
				<th v-if="isActive('street')">
					Street
				</th>
				<th v-if="isActive('city')" :class="{'text-primary': orderByField === 'city'}">
					City
					<i @click="setOrderByField('city')" v-if="orderByField !== 'city'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'city'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('zip')" :class="{'text-primary': orderByField === 'zip'}">
					Zip
					<i @click="setOrderByField('zip')" v-if="orderByField !== 'zip'" class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'zip'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('state')" :class="{'text-primary': orderByField === 'state'}">
					State
					<i @click="setOrderByField('state')" v-if="orderByField !== 'state'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'state'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('country_name')" :class="{'text-primary': orderByField === 'country_name'}">
					Country
					<i @click="setOrderByField('country_name')" v-if="orderByField !== 'country_name'"
					   class="fa fa-sort pull-right"></i>
					<i @click="direction=direction*-1" v-if="orderByField === 'country_name'" class="fa pull-right"
					   :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
				</th>
				<th v-if="isActive('public')">
					Public
				</th>
				<th v-if="isActive('isAdmin')">
					Admin
				</th>
				<th v-if="isActive('timezone')">
					Timezone
				</th>
				<th><i class="fa fa-cog"></i></th>
			</tr>
			</thead>
			<tbody>
			<tr v-for="user in orderByProp(users, orderByField, direction)">
				<td v-if="isActive('name')">{{user.name | capitalize}}</td>
				<td v-if="isActive('email')" v-text="user.email"></td>
				<td v-if="isActive('alt_email')" v-text="user.alt_email"></td>
				<td v-if="isActive('gender')">{{user.gender | capitalize}}</td>
				<td v-if="isActive('status')">{{user.status | capitalize}}</td>
				<td v-if="isActive('birthday')">{{user.birthday | moment('ll')}}</td>
				<td v-if="isActive('phone_one')" v-text="user.phone_one"></td>
				<td v-if="isActive('phone_two')" v-text="user.phone_two"></td>
				<td v-if="isActive('street')" v-text="user.street"></td>
				<td v-if="isActive('city')" v-text="user.city"></td>
				<td v-if="isActive('zip')" v-text="user.zip"></td>
				<td v-if="isActive('state')" v-text="user.state"></td>
				<td v-if="isActive('country_name')" v-text="user.country_name"></td>
				<td v-if="isActive('public')" v-text="user.public?'Yes':'No'"></td>
				<td v-if="isActive('isAdmin')" v-text="user.isAdmin?'Yes':'No'"></td>
				<td v-if="isActive('timezone')" v-text="user.timezone"></td>
				<td>
					<a :href="'/admin' + user.links[0].uri"><i class="fa fa-gear"></i></a>
				</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="7">
					<div class="col-sm-12 text-center">
						<pagination :pagination="pagination" :callback="searchUsers"></pagination>
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
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from '../export-utility.vue';
    import importUtility from '../import-utility.vue';
    import utilities from '../utilities.mixin';

    export default {
        name: 'admin-users-list',
        components: {vSelect, exportUtility, importUtility},
        mixins: [utilities],
        data() {
            return {
                users: [],
                orderByField: 'email',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {current_page: 1},
                search: '',
                activeFields: ['name', 'email', 'birthday', 'status', 'public', 'isAdmin'],
                maxActiveFields: 6,
                maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],
                countriesArr: [],
                exportOptions: {
                    name: 'Name',
                    email: 'Email',
                    alt_email: 'Alternate Email',
                    gender: 'Gender',
                    status: 'Marital Status',
                    birthday: 'Birthday',
                    phone_one: 'Primary Phone',
                    phone_two: 'Secondary Phone',
                    address: 'Street Address',
                    city: 'City',
                    state: 'State/Providence',
                    country_code: 'Country',
                    timezone: 'Timezone',
                    bio: 'Bio',
                    visibility: 'Visibility',
                    created_at: 'Created On',
                    updated_at: 'Last Updated'
                },
                exportFilters: {},
                importRequiredFields: [
                    'name', 'email', 'country_code', 'timezone',
                ],
                importOptionalFields: [
                    'alt_email', 'password', 'gender', 'status', 'birthday',
                    'phone_one', 'phone_two', 'address', 'city', 'state', 'zip',
                    'url', 'bio', 'visibility', 'avatar_source', 'banner_source',
                    'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url',
                    'pinterest_url', 'website_url', 'youtube_url', 'vimeo_url', 'google_url'
                ],

                // filter vars
                filters: {
                    isPublic: null,
                    isAdmin: null,
                    country: [],
                    gender: '',
                    status: '',
                }
            }
        },
        computed: {},
        watch: {
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.searchUsers();
                },
                deep: true
            },
            'countriesArr'(val, oldVal) {
                this.filters.country = _.pluck(val, 'code') || '';
                this.searchUsers();
            },
            'activeFields'(val, oldVal) {
                // if the orderBy field is removed from view
                if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                this.updateConfig();
            },
            'search'(val, oldVal) {
                this.updateConfig();
                this.pagination.current_page = 1;
                this.page = 1;
                this.searchUsers();
            },
            'page'(val, oldVal) {
                this.updateConfig();
                this.searchUsers();
            },
            'per_page'(val, oldVal) {
                this.updateConfig();
                this.searchUsers();
            },
            'groups'() {
                this.searchUsers();
            }
        },
        methods: {
            consoleCallback(val) {
                console.dir(JSON.stringify(val));
            },
            updateConfig() {
                localStorage.AdminUsersListConfig = JSON.stringify({
                    activeFields: this.activeFields,
                    maxActiveFields: this.maxActiveFields,
                    per_page: this.per_page,
                    countriesArr: this.countriesArr,
                    filters: {
                        isPublic: this.filters.isPublic,
                        isAdmin: this.filters.isAdmin,
                        countries: this.filters.countries,
                        gender: this.filters.gender,
                        status: this.filters.status,
                    }
                });
            },
            isActive(field) {
                return _.contains(this.activeFields, field);
            },
            maxCheck(field) {
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
            setOrderByField(field) {
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter() {
                this.orderByField = 'email';
                this.direction = 1;
                this.search = null;
                this.countriesArr = [];
                this.filters = {
                    isPublic: '',
                    isAdmin: '',
                    countries: [],
                    gender: '',
                    status: '',
                }
            },
            country(code) {
                return code;
            },
            totalAmountRaised(user) {
                let total = 0;
                _.each(user.fundraisers.data, (fundraiser) => {
                    total += fundraiser.raised_amount;
                });
                return total;
            },
            totalPercentRaised(user) {
                let totalDue = 0;
                _.each(user.costs.data, (cost) => {
                    totalDue += cost.amount;
                });
                return this.totalAmountRaised(user) / totalDue * 100;

            },
            age(birthday) {
                return moment().diff(birthday, 'years')
            },
            searchUsers() {
                let params = {
                    include: '',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                };

                $.extend(params, this.filters);
                this.exportFilters = params;

                this.$http.get('users', {params: params}).then((response) => {
                    this.users = response.data.data;
                    this.pagination = response.data.meta.pagination;
                })
            },
        },
        mounted() {
            // load view state
            if (localStorage.AdminUsersListConfig) {
                let config = JSON.parse(localStorage.AdminUsersListConfig);
                this.activeFields = config.activeFields;
                this.maxActiveFields = config.maxActiveFields;
            }
            // populate
            this.getCountries();
            this.searchUsers();

            //Manually handle dropdown functionality to keep dropdown open until finished
            $('.form-toggle-menu .dropdown-menu').on('click', function (event) {
                var events = $._data(document, 'events') || {};
                events = events.click || [];
                for (var i = 0; i < events.length; i++) {
                    if (events[i].selector) {

                        //Check if the clicked element matches the event selector
                        if ($(event.target).is(events[i].selector)) {
                            events[i].handler.call(event.target, event);
                        }

                        // Check if any of the clicked element parents matches the
                        // delegated event selector (Emulating propagation)
                        $(event.target).parents(events[i].selector).each(function () {
                            events[i].handler.call(this, event);
                        });
                    }
                }
                event.stopPropagation(); //Always stop propagation
            });
        },
        events: {
            'importComplete': function (msg) {
                console.log(msg);
                this.searchUsers();
            }
        },
    }
</script>
