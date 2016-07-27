<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <div id="toggleFields" class="dropdown" style="display: inline-block;">
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
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Show</span>
                        <select class="form-control" v-model="per_page">
                            <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                        </select>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
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
<script>
    export default{
        name: 'admin-reservations-list',
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
				maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9]
            }
        },
        watch: {
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
            }
        },
        methods: {
        	updateConfig(){
				localStorage.AdminReservationsListConfig = JSON.stringify({
					activeFields: this.activeFields,
					maxActiveFields: this.maxActiveFields,
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
                this.$http.get('reservations', {
                    include: 'trip.campaign,trip.group,fundraisers,costs.payments,user',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.page
                }).then(function (response) {
                    var self = this;
                    _.each(response.data.data, function (reservation) {
                        reservation.amount_raised = this.totalAmountRaised(reservation);
                        reservation.percent_raised = this.totalPercentRaised(reservation);
                    }, this);
                    this.reservations = response.data.data;
                    this.pagination = response.data.meta.pagination;
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
            this.searchReservations();

			//Manually handle dropdown functionality to keep dropdown open until finished
			$('#toggleFields ul.dropdown-menu').on('click', function(event){
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
