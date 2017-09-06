<template>
	<div>
		<mm-aside :show="showReservationsFilters" @open="showReservationsFilters=true" @close="showReservationsFilters=false" placement="left" header="Reservation Filters" :width="375">
			<reservations-filters ref="filters" v-model="reservationFilters" :reset-callback="resetFilter" :pagination="reservationsPagination" pagination-key="reservationsPagination" :callback="searchReservations" storage="" teams></reservations-filters>
		</mm-aside>
		<!-- Search and Filter -->
		<form class="form-inline row">
			<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
				<div class="input-group input-group-sm col-xs-12">
					<input type="text" class="form-control" v-model="reservationFilters.search" @keyup="debouncedSearchReservations" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</div><!-- end col -->
			<div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="showReservationsFilters=!showReservationsFilters">
					Filters
					<i class="fa fa-filter"></i>
				</button>
			</div>
			<div class="col-xs-12">
				<hr class="divider inv">
				<div>
					<label>Active Filters</label>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.type != ''" @click="reservationFilters.type = ''" >
									Trip Type
									<i class="fa fa-close"></i>
								</span>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.groups.length" @click="reservationFilters.groups = []" >
									Travel Group
									<i class="fa fa-close"></i>
								</span>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.hasCompanions !== null" @click="reservationFilters.hasCompanions = null" >
									Companions
									<i class="fa fa-close"></i>
								</span>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.role !== ''" @click="reservationFilters.role = ''" >
									Role
									<i class="fa fa-close"></i>
								</span>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.gender != ''" @click="reservationFilters.gender = ''" >
									Gender
									<i class="fa fa-close"></i>
								</span>
					<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.status != ''" @click="reservationFilters.status = ''" >
									Status
									<i class="fa fa-close"></i>
								</span>
					<template v-if="reservationFilters.age">
									<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.age[0] != 0" @click="reservationFilters.age[0] = 0" >
										Min. Age
										<i class="fa fa-close"></i>
									</span>
						<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.age[1] != 120" @click="reservationFilters.age[1] = 120" >
										Max. Age
										<i class="fa fa-close"></i>
									</span>
					</template>
				</div>
			</div>
		</form>
		<!-- Reservation Lists and Pagination -->
		<div class="row">
			<div class="col-xs-12">
				<div class="panel-group" id="reservationsAccordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default" v-for="(reservation, reservationIndex) in reservations">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<div class="row">
									<div class="col-xs-9">
										<div class="media">
											<div class="media-left" style="padding-right:0;">
												<a :href="getReservationLink(reservation)" target="_blank">
													<img :src="reservation.avatar" class="img-circle img-xs av-left" style="margin-right: 10px"><!--<span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member && member.leader">GL</span>-->
												</a>
											</div>
											<div class="media-body" style="vertical-align:middle;">
												<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
													<i :class="getGenderStatusIcon(reservation)"></i>
													<a :href="getReservationLink(reservation)" target="_blank">
														{{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}</a></h6>
												<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ reservation.desired_role.name }} <span class="text-muted">&middot; {{ reservation.trip.data.group.data.name }}</span></p>
											</div><!-- end media-body -->
										</div><!-- end media -->
									</div>
									<div class="col-xs-3 text-right action-buttons">
										<slot name="action-buttons" :reservation="reservation">

										</slot>
										<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#reservationsAccordion" :href="'#reservationItem' + reservationIndex" aria-expanded="true" aria-controls="collapseOne">
											<i class="fa fa-angle-down"></i>
										</a>
									</div>
								</div>

							</h4>
						</div>
						<div :id="'reservationItem' + reservationIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<slot name="details" :reservation="reservation">
									<div class="row">
										<div class="col-sm-6">
											<label>Gender</label>
											<p class="small">{{ reservation.gender | capitalize }}</p>
											<label>Marital Status</label>
											<p class="small">{{ reservation.status | capitalize }}</p>
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
											<p class="small">{{ reservation.trip.data.type | capitalize }}</p>
										</div>
										<div class="col-sm-6">
											<label>Designation</label>
											<p class="small">{{ reservation.arrival_designation | capitalize }}</p>
										</div>
									</div><!-- end row -->
								</slot>
							</div>
						</div>
						<div class="panel-footer" v-if="reservation.companions.data.length">
							I have {{reservation.companions.data.length}} companions.
						</div>
					</div>
				</div>
				<div class="col-sm-12 text-center">
					<pagination :pagination="reservationsPagination" pagination-key="reservationsPagination" :callback="searchReservations"></pagination>
				</div>
			</div>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import reservationsFilters from '../filters/reservations-filters.vue';
    export default {
        name: 'reservations-list-slot',
	    components: {reservationsFilters},
	    props: {
            params: {
                type: Object,
	            default() { return {}; }
            },
            filters: {
                type: Object,
	            default() { return {
                    search: '',
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: '',
                    designation: '',
                    age: [0, 120],
	            }; }
            },
	    },
        data() {
            return {
                reservations: [],
                reservationsPerPage: 10,
                reservationsPagination: { current_page: 1 },
                lastReservationRequest: null,
                showReservationsFilters: false,
	            reservationFilters: {
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: '',
                    designation: '',
                    age: [0, 120],
                }
            }
        },
	    watch: {
            filters: {
                handler(val, oldVal) {
                    this.reservationsPagination.current_page = 1;
                    this.searchReservations();
                },
                deep: true
            },
            params: {
                handler(val) {
                     this.searchReservations();
                },
	            deep: true
            }

        },
        methods: {
            getReservationLink(reservation){
                return `${this.isAdminRoute ? '/admin' : '/dashboard'}/reservations/${reservation.id}`;
            },
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
            resetFilter(){
                this.reservationsSearch = null;
                this.$root.$emit('reservations-filters:reset');
                this.reservationFilters = {
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: '',
                    designation: '',
                    age: [0, 120],
                };
            },
            debouncedSearchReservations: _.debounce(function () {
                this.searchReservations();
            }, 250),
            searchReservations(){
                let params = {
                    per_page: this.reservationsPerPage,
                    page: this.reservationsPagination.current_page,
                };

                $.extend(params, this.params);
                $.extend(params, this.filters, this.reservationFilters);


                // For Rooming Manager
                if (_.isObject(this.reservationFilters.role)) {
                    params.role = this.reservationFilters.role.value;
                }

                // For Rooming Manager
                if (this.reservationFilters.groups.length)
                    params.groups = _.union(params.groups, _.pluck(this.reservationFilters.groups, 'id'));

                return this.$http.get('reservations', { params: params, before: function (xhr) {
                    if (this.lastReservationRequest) {
                        this.lastReservationRequest.abort();
                    }
                    this.lastReservationRequest = xhr;
                } }).then((response) => {
                    this.reservations = response.data.data;
                    this.reservationsPagination = response.data.meta.pagination;
                }, this.$root.handleApiError);
            },

        },
        mounted() {
            this.searchReservations();

            this.$root.$on('Reservations::exclusions', val => {
//                this.searchReservations();
            });
            this.$root.$on('Reservations::refresh', () => {
                this.searchReservations();
            });
        }
    }
</script>