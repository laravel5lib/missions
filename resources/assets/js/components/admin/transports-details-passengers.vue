<template>
	<div>
		<aside :show.sync="showPassengersFilters" placement="left" header="Passengers Filters" :width="375">
			<reservations-filters v-ref:filters :filters.sync="passengersFilters" :reset-callback="resetPassengerFilters" :pagination="passengersPagination" :callback="getPassengers" storage="" transports></reservations-filters>
		</aside>
		<aside :show.sync="showReservationsFilters" placement="left" header="Reservations Filters" :width="375">
			<reservations-filters v-ref:filters :filters.sync="reservationFilters" :reset-callback="resetReservationFilters" :pagination="reservationsPagination" :callback="searchReservations" storage="" teams></reservations-filters>
		</aside>

		<div class="row">
			<!-- Passengers List -->
			<div class="col-sm-8">
				<form class="form-inline row">
					<div class="col-sm-8">
						<div class="input-group input-group-sm col-xs-12">
							<input type="text" class="form-control" v-model="passengersFilters.search" debounce="300" placeholder="Search Passengers">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
						</div>
					</div>

					<div class="form-group col-sm-4">
						<a class="btn btn-default btn-sm btn-block" @click="showPassengersFilters = true;">Filters</a>
					</div>
					<div class="col-xs-12">
						<filters-indicator :filters.sync="passengersFilters"></filters-indicator>
					</div>
				</form>
				<div class="panel-group" id="PassengerAccordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default" v-for="passenger in passengers">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<div class="row">
									<div class="col-xs-9">
										<div class="media">
											<div class="media-left" style="padding-right:0;">
												<a :href="'/admin/reservations/' + passenger.reservation.data.id" target="_blank">
													<img :src="passenger.reservation.data.avatar" class="img-circle img-xs av-left" style="margin-right: 10px">
												</a>
											</div>
											<div class="media-body" style="vertical-align:middle;">
												<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
													<i :class="getGenderStatusIcon(passenger.reservation.data)"></i>
													<a :href="'/admin/reservations/' + passenger.reservation.data.id" target="_blank">{{ passenger.reservation.data.surname | capitalize }}, {{ passenger.reservation.data.given_names | capitalize }}</a></h6>
												<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ passenger.reservation.data.desired_role.name }} <span class="text-muted">&middot; {{ passenger.reservation.data.trip.data.group.data.name}}</span></p>
											</div><!-- end media-body -->
										</div><!-- end media -->
									</div>
									<div class="col-xs-3 text-right action-buttons">
										<a class="btn btn-xs btn-primary-hollow" @click="removePassenger(passenger)">
											<span class="fa fa-minus"></span>
										</a>
										<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#PassengerAccordion" :href="'#squadLeaderItem' + $index" aria-expanded="true" aria-controls="collapseOne">
											<i class="fa fa-angle-down"></i>
										</a>
									</div>
								</div>
							</h4>
						</div>
						<div :id="'squadLeaderItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<label>Gender</label>
										<p class="small">{{passenger.reservation.data.gender | capitalize}}</p>
										<label>Marital Status</label>
										<p class="small">{{passenger.reservation.data.status | capitalize}}</p>
									</div><!-- end col -->
									<div class="col-sm-6">
										<label>Age</label>
										<p class="small">{{passenger.reservation.data.age}}</p>
										<label>Travel Group</label>
										<p class="small">{{passenger.reservation.data.trip.data.group.data.name}}</p>
									</div><!-- end col -->
								</div><!-- end row -->
								<div class="row">
									<div class="col-sm-12">
										<label>Companions</label>
										<ul class="list-unstyled" v-if="passenger.reservation.data.companions.data.length">
											<li v-for="companion in passenger.reservation.data.companions.data">
												<i :class="getGenderStatusIcon(companion)"></i>
												{{ companion.surname | capitalize }}, {{ companion.given_names | capitalize }}
												<span class="text-muted">({{ companion.relationship | capitalize }})</span>
											</li>
										</ul>
										<p class="small" v-else>None</p>
									</div>
									<div class="col-sm-6">
										<label>Trip Type</label>
										<p class="small">{{passenger.reservation.data.trip.data.type | capitalize}}</p>
									</div>
									<div class="col-sm-6">
										<label>Designation</label>
										<p class="small">{{ passenger.reservation.data.arrival_designation }}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer small clearfix" style="background-color: #ffe000;" v-if="passenger.reservation.data.companions.data.length && companionsPresentTransport(passenger)">
							<i class=" fa fa-info-circle"></i> {{companionsPresentTransport(passenger)}} companion(s) not in transport.
							<button type="button" class="btn btn-xs btn-default-hollow pull-right" @click="addCompanionsToTransport(passenger)"><i class="fa fa-plus-circle"></i> Companions</button>
						</div>
					</div>
				</div>
				<div class="col-sm-12 text-center">
					<pagination :pagination.sync="passengersPagination" :callback="getPassengers"></pagination>
				</div>

			</div>
			<!-- Reservations List -->
			<div class="col-sm-4">
				<form class="form-inline row">
					<div class="col-sm-8">
					<div class="input-group input-group-sm col-xs-12">
						<input type="text" class="form-control" v-model="reservationFilters.search" debounce="300" placeholder="Search Reservations">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
					</div>

					<div class="form-group col-sm-4">
						<a class="btn btn-default btn-sm" @click="showReservationsFilters = true;">Filters</a>
					</div>
					<div class="col-xs-12">
						<filters-indicator :filters.sync="reservationFilters"></filters-indicator>
					</div>

				</form>
				<!-- Reservation Lists and Pagination -->
				<div class="row">
					<div class="col-xs-12">
						<div class="panel-group" id="reservationsAccordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default" v-for="reservation in reservations">
								<div class="panel-heading" role="tab" id="headingOne">
									<h4 class="panel-title">
										<div class="row">
											<div class="col-xs-9">
												<div class="media">
													<div class="media-left" style="padding-right:0;">
														<a :href="'/admin/reservations/' + reservation.id" target="_blank">
															<img :src="reservation.avatar" class="img-circle img-xs av-left" style="margin-right: 10px">
														</a>
													</div>
													<div class="media-body" style="vertical-align:middle;">
														<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
															<i :class="getGenderStatusIcon(reservation)"></i>
															<a :href="'/admin/reservations/' + reservation.id" target="_blank">
																{{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}</a></h6>
														<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ reservation.desired_role.name }} <span class="text-muted">&middot; {{ reservation.trip.data.group.data.name }}</span></p>
													</div><!-- end media-body -->
												</div><!-- end media -->
											</div>
											<div class="col-xs-3 text-right action-buttons">
												<a class="btn btn-xs btn-primary-hollow" @click="addPassenger(reservation)">
													<span class="fa fa-plus"></span>
												</a>
												<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#reservationsAccordion" :href="'#reservationItem' + $index" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-angle-down"></i>
												</a>
											</div>
										</div>

									</h4>
								</div>
								<div :id="'reservationItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
												<label>Gender</label>
												<p class="small">{{reservation.gender | capitalize}}</p>
												<label>Marital Status</label>
												<p class="small">{{reservation.status | capitalize}}</p>
											</div><!-- end col -->
											<div class="col-sm-6">
												<label>Age</label>
												<p class="small">{{reservation.age}}</p>
												<label>Travel Group</label>
												<p class="small">{{reservation.trip.data.group.data.name}}</p>
											</div><!-- end col -->
											<div class="col-sm-12">
												<label>Companions</label>
												<ul class="list-unstyled" v-if="reservation.companions.data.length">
													<li v-for="companion in reservation.companions.data">
														<i :class="getGenderStatusIcon(companion)"></i>
														{{ companion.surname | capitalize }}, {{ companion.given_names | capitalize }} <span class="text-muted">({{ companion.relationship | capitalize }})</span>
													</li>
												</ul>
												<p class="small" v-else>None</p>
											</div>
											<div class="col-sm-6">
												<label>Trip Type</label>
												<p class="small">{{reservation.trip.data.type | capitalize}}</p>
											</div>
											<div class="col-sm-6">
												<label>Designation</label>
												<p class="small">{{reservation.arrival_designation | capitalize}}</p>
											</div>
										</div><!-- end row -->
									</div>
								</div>
								<div class="panel-footer" v-if="reservation.companions.data.length">
									I have {{reservation.companions.data.length}} companions.
								</div>
							</div>
						</div>
						<div class="col-sm-12 text-center">
							<pagination :pagination.sync="reservationsPagination" :callback="searchReservations"></pagination>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import errorHandler from '../error-handler.mixin';
    import utilities from '../utilities.mixin';
    import reservationsFilters from '../filters/reservations-filters.vue'
    import filtersIndicator from '../filters/filters-indicator.vue';
    export default{
        name: 'transports-details-passengers',
//        mixins: [errorHandler, utilities],
	    components: {vSelect, reservationsFilters, filtersIndicator},
	    props: ['transport', 'campaignId'],
        data(){
            return {
                showPassengersFilters: false,
                showReservationsFilters: false,
                passengers: [],
                passengersPagination: { current_page: 1 },
                reservations: [],
                reservationsPagination: { current_page: 1 },
	            passengersFilters: {
                    groups: [],
                    gender: '',
                    designation: '',
                    role: '',
                    age: [0, 120],
                    hasCompanions: null,
                    search: '',
		            per_page: 10
	            },
                reservationFilters: {
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: '',
                    designation: '',
                    age: [0, 120],
	                search: '',
                    notInTransport: this.transport.id
                },

	            PassengersResource: this.$resource('transports{/transport}/passengers{/passenger}', { transport: this.transport.id })
            }
        },
	    watch: {
            activityOptions: {
                handler(val) {
                    this.getActivities();
                },
	            deep: true
            },
		    'passengersFilters.search'(){
                this.getPassengers();
		    },
		    'reservationFilters.search'(){
		        this.searchReservations();
		    },
	    },
        methods: {
            resetPassengerFilters(){},
            resetReservationFilters(){},
            getGenderStatusIcon(reservation){
                if (reservation.gender == 'male') {
                    if (reservation.status == 'married') {
                        return 'fa fa-venus-mars text-info';
                    }
                    return 'fa fa-mars text-info';
                }

                if (reservation.status == 'married') {
                    return 'fa fa-venus-mars text-danger';
                }
                return 'fa fa-venus text-danger';
            },

            getPassengers() {
                let params = _.extend({
	                include: 'reservation.trip.campaign,reservation.trip.group,reservation.companions',
                    page: this.passengersPagination.current_page,
                }, this.passengersFilters);

                this.PassengersResource.get(params).then(function (response) {
                    this.passengers = response.body.data;
                    this.passengersPagination = response.body.meta.pagination;
                }, this.$root.handleApiError);
            },
	        addPassenger(reservation) {
                return this.PassengersResource.save({
                    transport_id: this.transport.id,
                    reservation_id: reservation.id,
                }).then(function (response) {
                    this.getPassengers();
                    this.searchReservations();
                }, this.$root.handleApiError);
	        },
	        removePassenger(passenger) {
                this.PassengersResource.delete({ passenger: passenger.id }).then(function (response) {
                    this.getPassengers();
                    this.searchReservations();
                });
	        },
            searchReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,user,companions',
                    search: this.reservationFilters.search,
                    page: this.reservationsPagination.current_page,
                    current: true,
                    //ignore: this.excludeReservationIds,
                    //noSquad: true,
                    designation: this.reservationFilters.designation,
                };

                if (this.isAdminRoute) {
                    params.campaign = this.campaignId;
                } else {
                    params.campaign = this.campaignId;
                    //params.groups = new Array(this.groupId);
                    //params.trip = this.reservationsTrips.length ? this.reservationsTrips : new Array();
                }

                params = _.extend(params, this.reservationFilters);


                // this.$refs.spinner.show();
                return this.$http.get('reservations', { params: params, before: function(xhr) {
                    if (this.lastReservationRequest) {
                        this.lastReservationRequest.abort();
                    }
                    this.lastReservationRequest = xhr;
                } }).then(function (response) {
                    this.reservations = response.body.data;
                    this.reservationsPagination = response.body.meta.pagination;
                    // this.$refs.spinner.hide();
                }, this.$root.handleApiError);
            },
            companionsPresentTransport(passenger) {
				let companionIds = _.pluck(passenger.reservation.data.companions.data, 'id');
				let passengerIds = _.pluck(passenger.transportCompanions, 'id');
                let presentIds =  _.intersection(companionIds, passengerIds);
                return companionIds.length - presentIds.length;
            },
            addCompanionsToTransport(passenger) {
                let companionIds = _.pluck(passenger.reservation.data.companions.data, 'id');
                let passengerIds = _.pluck(passenger.transportCompanions, 'id');
				let notPresentIds = _.difference(companionIds, passengerIds);
                // Check Limitations
	            // Available Space

	            let promises = [];
	            _.each(notPresentIds, function (id) {
		            promises.push(this.addPassenger({ id: id }));
                }.bind(this));

	            Promise.all(promises).then(function (values) {
		            this.$root.$emit('showSuccess', 'Companions Added');
                }.bind(this));

            },
        },
        ready(){
			this.getPassengers();
			this.searchReservations();
        }
    }
</script>