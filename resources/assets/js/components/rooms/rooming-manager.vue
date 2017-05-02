<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-sm-4">
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
		<div class="col-sm-4">
			<div class="panel panel-default">
				  <div class="panel-heading">
						<h3 class="panel-title">Occupants</h3>
				  </div>
				  <div class="panel-body">
						Panel body
				  </div>
			</div>
		</div>
		<div class="col-sm-4">
			<select class="form-control input-sm" v-model="currentTeam">
				<option :value="" v-if="!currentTeam">Select Team</option>
				<option :value="team" v-for="team in teams">{{team.callsign | capitalize}}</option>
			</select>

			<div class="panel panel-default" v-for="member in currentTeamMembers">
				<div class="panel-heading" role="tab" id="headingOne">
					<h4 class="panel-title">
						<div class="row">
							<div class="col-xs-9">
								<a role="button" data-toggle="collapse" :data-parent="'#membersAccordion' + tgIndex" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
									<img :src="member.avatar" class="img-circle img-xs pull-left" style="margin-right: 10px">
									{{ member.surname | capitalize }}, {{ member.given_names | capitalize }} <span class="label label-info" v-if="member.leader">Group Leader</span><br>
									<label>{{ member.desired_role.name }}</label>
								</a>
							</div>
							<div class="col-xs-3 text-right action-buttons">
								<dropdown type="default">
									<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
										<span class="fa fa-ellipsis-h"></span>
									</button>
									<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
										<template v-for="subSquad in currentSquads">
											<template v-if="subSquad.callsign === 'Team Leaders'">
												<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ''"></a></li>
												<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
											</template>
											<template v-else>
												<template v-if="subSquad.id !== squad.id">
													<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(member)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Team Leaders</a></li>
													<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
													<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
												</template>
											</template>
										</template>
										<li :class="{'disabled': isLocked}" role="separator" class="divider"></li>
										<li :class="{'disabled': isLocked}" v-if="member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
										<li :class="{'disabled': isLocked}" v-if="!member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
										<li :class="{'disabled': isLocked}"><a @click="removeFromSquad(member, squad)">Remove</a></li>
									</ul>
								</dropdown>
								<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#membersAccordion" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
									<i class="fa fa-angle-down"></i>
								</a>
							</div>
						</div>
					</h4>
				</div>
				<div :id="'memberItem' + tgIndex + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6">
								<label>Gender</label>
								<p class="small">{{member.gender | capitalize}}</p>
								<label>Marital Status</label>
								<p class="small">{{member.status | capitalize}}</p>
							</div><!-- end col -->
							<div class="col-sm-6">
								<label>Age</label>
								<p class="small">{{member.age}}</p>
								<label>Travel Group</label>
								<p class="small">{{member.trip.data.group.data.name}}</p>
							</div><!-- end col -->
						</div><!-- end row -->
					</div><!-- end panel-body -->
				</div>
				<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions.data.length && companionsPresentSquad(member, squad)">
					<i class=" fa fa-info-circle"></i> I have {{member.present_companions}} companions not in this group. And {{companionsPresentTeam(member)}} not on this team.
					<button type="button" class="btn btn-xs btn-default-hollow" @click="addCompanionsToSquad(member, squad)">Add Companions</button>
				</div>
			</div>

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
                startUp: true,

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
                roomTypes: [],

                // Filters vars
                showReservationsFilters: false,
            }
        },
	    computed: {
            currentTeamMembers() {
                let members = [];
                if (_.isObject(this.currentTeam))
	                _.each(this.currentTeam.squads.data, function (squad) {
						_.each(squad.members.data, function (member) {
							members.push(member);
	                    });
	                });
                return members;
            }
	    },
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
            getRoomTypes(){
                return this.$http.get('rooms/types').then(function (response) {
                        return this.roomTypes = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getPlans(){
                let params = {
                    // include: '',
                    page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming-plans', { params: params }).then(function (response) {
                        this.plansPagination = response.body.meta.pagination;
                        return this.plans = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getTeams(){
                let params = {
                    include: 'squads.members,type',
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

            promises.push(this.getPlans());
            promises.push(this.getRoomTypes());
            promises.push(this.getTeams());
            Promise.all(promises).then(function (values) {
                this.startUp = false;
//                this.searchReservations();
            }.bind(this));
        }
    }
</script>