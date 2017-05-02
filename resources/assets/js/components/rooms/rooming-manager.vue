<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-sm-3">
			<div class="col-xs-12 text-right">
				<button class="btn btn-primary btn-xs" @click="openNewPlanModel">Create A Plan</button>
				<hr class="divider lg">
			</div>

			<div class="col-xs-12">
				<accordion :one-at-atime="true" type="info">
					<panel header="Room Plan" v-for="plan in plans">
						<tabs>
							<tab header="Rooms">
								<!-- Search and Filter -->
								<form class="form-inline row">
									<div class="form-group col-xs-12 text-right">
										<button class="btn btn-primary btn-xs" @click="openNewRoomModel">Add Room</button>
										<hr class="divider lg">
									</div>

									<div class="form-group col-xs-12">
										<div class="input-group input-group-sm col-xs-12">
											<input type="text" class="form-control" v-model="membersSearch" debounce="300" placeholder="Search">
											<span class="input-group-addon"><i class="fa fa-search"></i></span>
										</div>
									</div><!-- end col -->
									<div class="form-group col-xs-12">
										<button class="btn btn-default btn-sm btn-block" type="button" @click="showMembersFilters=!showMembersFilters">
											Filters
											<i class="fa fa-filter"></i>
										</button>
									</div>
									<div class="col-xs-12">
										<hr class="divider inv">
									</div>
								</form>

								<div class="row">
									<div class="col-xs-12">
										<template v-for="room in rooms">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h3 class="panel-title">Room Type</h3>
												</div>
												<div class="panel-body">
													<div class="row">
														<div class="col-sm-6">
															<label>Occupancy Limit</label>
															<p class="small">{{room.max | capitalize}}</p>
															<label>Limited to Gender</label>
															<p class="small">{{room.gender | capitalize}}</p>
															<label>Limited to Status</label>
															<p class="small">{{room.status | capitalize}}</p>
														</div><!-- end col -->
														<div class="col-sm-6">
															<label>Current Number of Occupants</label>
															<p class="small">{{room.occupants_count}}</p>
															<label>Room Leader</label>
															<p class="small">{{room.leader}}</p>
														</div><!-- end col -->
													</div><!-- end row -->
												</div>
											</div>
										</template>
									</div>
								</div>
							</tab>
							<tab header="Plan Details">

							</tab>
						</tabs>
					</panel>
				</accordion>
			</div>

		</div>
		<div class="col-sm-6">
			<div class="panel panel-default">
				  <div class="panel-heading">
						<h3 class="panel-title">Occupants</h3>
				  </div>
				  <div class="panel-body">
						Panel body
				  </div>
			</div>
		</div>
		<div class="col-sm-3">
			<select class="form-control" v-model="currentTeam">
				<option :value="team" v-for="team in teams">{{team.name | capitalize}}</option>
			</select>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    export default{
        name: 'rooming-manager',
        components: {vSelect},
        props: {
            userId: {
                type: String,
                required: false
            },
            groupId: {
                type: String,
                required: false
            }
        },
        data(){
            return {
                plans: [],
                plansPagination: { current_page: 1 },
                rooms: [],
                roomsPagination: { current_page: 1 },
                teams: [],
                teamsPagination: { current_page: 1 },
                reservations: [],
                reservationsSearch: '',
                reservationsPerPage: 10,
                reservationsPagination: { current_page: 1 },

                currentTeam: null,
                currentPlan: null,
				activeRooms: [],

                // Filters vars
                showReservationsFilters: false,
            }
        },
	    computed: {},
        methods: {
            searchReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,fundraisers,costs.payments,user,companions',
                    search: this.reservationsSearch,
                    per_page: this.reservationsPerPage,
                    page: this.reservationsPagination.current_page,
                    current: true,
                    ignore: this.excludeReservationIds,
                };
                if (this.isAdminRoute) {
                    params.campaign = this.campaignId;
                } else {
                    params.groups = new Array(this.groupId);
                    params.trip = this.reservationsTrips.length ? this.reservationsTrips : new Array();
                }
                /*$.extend(params, this.reservationFilters);
                $.extend(params, {
                    age: [this.ageMin, this.ageMax]
                });*/
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
                }, function (error) {
                    // this.$refs.spinner.hide();
                    //TODO add error alert
                });
            },
            getTeams(){
                let params = {
                    include: 'type',
                    page: this.teamsPagination.current_page,
                };
                return this.$http.get('teams', { params: params }).then(function (response) {
                        this.teamsPagination = response.body.meta.pagination;
                        return this.teams = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },

	        // Modal Functions
            openNewPlanModel(){

            },
        },
        ready(){
            let promises = [];
            if (this.isAdminRoute) {
            } else {
                promises.push(this.$http.get('users/' + this.userId, {
                    params: {include: 'facilitating,managing.trips'}
                }).then(function (response) {
                    let user = response.body.data;
                    let managing = [];
                    if (user.facilitating.data.length) {
                        this.reservationsFacilitator = true;
                        let facilitating = _.pluck(user.facilitating.data, 'id');
                        this.reservationsTrips = _.union(this.reservationsTrips, facilitating);
                    }
                    if (user.managing.data.length) {
                        _.each(user.managing.data, function (group) {
                            managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                        });
                        this.reservationsTrips = _.union(this.reservationsTrips, managing);
                    }
                    this.includeReservationsManaging = true;
                    if (this.reservationsFacilitator) {
//                    this.getRequirements();
                        this.getRoles();
                    }
                }));
            }

            promises.push(this.getTeams());
            Promise.all(promises).then(function (values) {
                this.startUp = false;
                this.searchReservations();
            }.bind(this));
        }
    }
</script>