<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<!-- Plans List-->
		<div class="col-sm-4">
			<div class="col-xs-12 text-right">
				<button class="btn btn-primary btn-xs" type="button" @click="openNewPlanModel">Create A Plan</button>
				<hr class="divider lg">
			</div>

			<div class="col-xs-12">
				<template v-if="plans.length">
					<accordion :one-at-atime="true" type="info">
						<panel :is-open="currentPlan && currentPlan.id === plan.id" :header="plan.name" v-for="plan in plans" @click="currentPlan = plan">
							<tabs>
								<tab header="Rooms">
									<!-- Search and Filter -->
									<form class="form-inline row">
										<div class="form-group col-xs-12 text-right">
											<button class="btn btn-primary btn-xs" type="button" @click="openNewRoomModel">Add Room</button>
											<hr class="divider lg">
										</div>

										<div class="form-group col-xs-8">
											<div class="input-group input-group-sm col-xs-12">
												<input type="text" class="form-control" v-model="roomsSearch" debounce="300" placeholder="Search">
												<span class="input-group-addon"><i class="fa fa-search"></i></span>
											</div>
										</div><!-- end col -->
										<div class="form-group col-xs-4">
											<button class="btn btn-default btn-sm btn-block" type="button" @click="showMembersFilters=!showMembersFilters">
												<i class="fa fa-filter"></i>
											</button>
										</div>
										<div class="col-xs-12">
											<hr class="divider inv">
										</div>
									</form>

									<div class="row">
										<div class="col-xs-12" v-if="currentPlan">
											<template v-if="currentPlan.rooms.length">
												<div class="panel panel-default" v-for="room in currentPlan.rooms">
													<div class="panel-heading">
														<h3 class="panel-title" v-text="(room.label ? (room.label + ' - ' + room.type.name) : room.type.name) | capitalize"></h3>
													</div>
													<div class="panel-body">
														<div class="row">
															<div class="col-sm-6">
																<label>Occupancy Limit</label>
																<p class="small">{{room.type.rules.occupancy_limit}}</p>
																<label>Limited to Gender</label>
																<p class="small">{{room.type.rules.gender | capitalize}}</p>
																<label>Limited to Status</label>
																<p class="small">{{room.type.rules.status | capitalize}}</p>
															</div><!-- end col -->
															<div class="col-sm-6">
																<label>Current Number of Occupants</label>
																<p class="small">{{room.occupants.length}}</p>
																<label>Room Leader</label>
																<p class="small">{{room.leader}}</p>
															</div><!-- end col -->
														</div><!-- end row -->
													</div>
												</div>
											</template>
											<template v-else>
												<hr class="divider inv">
												<p class="text-center text-muted"><em>Create a room to get started!</em></p>
											</template>
										</div>
									</div>
								</tab>
								<tab header="Plan Details">
									<button class="btn btn-block btn-default btn-sm" type="button" @click="openDeletePlanModal">Delete Plan</button>
								</tab>
							</tabs>
						</panel>
					</accordion>
				</template>
				<template v-else>
					<hr class="divider inv">
					<p class="text-center text-muted"><em>Create a new plan to get started!</em></p>
					<hr class="divider inv">
					<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewPlanModel">Create A Plan</a></p>
				</template>

			</div>

		</div>

		<!-- Occupants List -->
		<div class="col-sm-4">
			<template v-if="activeRooms.length">
				<div class="panel panel-default" v-for="room in activeRooms">
					<div class="panel-heading">
						<h3 class="panel-title">{{(room.label ? (room.label + ' - ' + room.type.name) : room.type.name) | capitalize}} Occupants</h3>
					</div>
					<div class="panel-body">
						<template v-if="room.occupants.length">
							<div class="panel panel-default" v-for="member in room.occupants">
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
						</template>
						<template v-else>
							<hr class="divider inv">
							<p class="text-center text-italic text-muted"><em>No occupants in this room yet.</em></p>
						</template>
					</div>
				</div>
			</template>
			<template v-else>

			</template>

		</div>

		<!-- Teams Select & Members List -->
		<div class="col-sm-4">
			<select class="form-control input-sm" v-model="currentTeam">
				<option :value="" v-if="!currentTeam">Select Team</option>
				<option :value="team" v-for="team in teams">{{team.callsign | capitalize}}</option>
			</select>
			<hr class="divider lg">
			<!-- Search and Filter -->
			<form class="form-inline row">
				<div class="form-group col-xs-8">
					<div class="input-group input-group-sm col-xs-12">
						<input type="text" class="form-control" v-model="teamMembersSearch" debounce="300" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div><!-- end col -->
				<div class="form-group col-xs-4">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="showMembersFilters=!showMembersFilters">
						<i class="fa fa-filter"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider inv">
				</div>
			</form>

			<template v-if="currentTeam">
				<template v-if="currentTeamMembers.length">
					<div class="panel-group" id="reservationsAccordion" role="tablist" aria-multiselectable="true">
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
				</template>
				<template v-else>
					<hr class="divider inv">
					<p class="text-center text-italic text-muted"><em>No members in {{currentTeam.callsign}}. Select another team or add them using the team manager!</em></p>
					<hr class="divider inv">
					<p class="text-center"><a class="btn btn-link btn-sm" href="teams">Manage Teams</a></p>
				</template>
			</template>
			<template v-else>
				<hr class="divider inv">
				<p class="text-center text-italic text-muted"><em>Select a team to begin</em></p>
			</template>
		</div>

		<!-- Modals -->
		<modal title="Create a new Plan" small ok-text="Create" :callback="newPlan" :show.sync="showPlanModal">
			<div slot="modal-body" class="modal-body">
				<validator name="PlanCreate">
					<form id="PlanCreateForm">
						<div class="form-group" :class="{'has-error': $PlanCreate.planname.invalid}">
							<label for="createPlanCallsign" class="control-label">Plan Name</label>
							<input @keydown.enter.prevent="newPlan" type="text" class="form-control" id="createPlanCallsign" placeholder="" v-validate:planname="['required']" v-model="selectedPlan.name">
						</div>
					</form>
				</validator>
			</div>
		</modal>

		<modal title="Create a new Room" small ok-text="Create" :callback="newRoom" :show.sync="showRoomModal">
			<div slot="modal-body" class="modal-body">
				<validator name="RoomCreate">
					<form id="RoomCreateForm">
						<div class="form-group" :class="{'has-error': $RoomCreate.roomtype.invalid}">
							<label for="" class="control-label">Type</label>
							<select class="form-control" v-model="selectedRoom.type" v-validate:roomtype="['required']" @change="selectedRoom.room_type_id = selectedRoom.type.id">
								<option :value="type" v-for="type in roomTypes">{{type.name | capitalize}}</option>
							</select>
							<hr class="divider sm">
							<div v-if="selectedRoom.type" class="">
								<template  v-for="(key, value) in selectedRoom.type.rules">
									<label v-text="key | underscoreToSpace | capitalize"></label>
									<p class="small" v-text="value | capitalize"></p>
								</template>
							</div>
						</div>
						<div class="form-group">
							<label for="roomname" class="control-label">Room Name (Optional)</label>
							<input @keydown.enter.prevent="" type="text" class="form-control" id="roomname" v-model="selectedRoom.label">
						</div>
					</form>
				</validator>
			</div>
		</modal>

		<modal title="Delete Rooming Plan" small ok-text="Delete" :callback="deletePlan" :show.sync="showPlanDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="currentPlan">
					Are you sure you want to delete plan: "{{currentPlan.name}}" ?
				</p>
			</div>
		</modal>


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
            },
            campaignId: {
                type: String,
                required: false
            }
        },
        data(){
            return {
                startUp: true,

                plans: [],
                plansPagination: { current_page: 1 },
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
                teamMembersSearch: '',
                roomsSearch: '',
                showReservationsFilters: false,


	            // modal vars
	            showPlanModal: false,
                showPlanDeleteModal: false,
                selectedPlan: {
                    name: '',
	                rooms: []
                },
	            showRoomModal: false,
                selectedRoom: {
                    room_type_id: null,
                    type: null,
	                label: '',
                    occupants: [],
                },
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
                return this.$http.get('rooming/types').then(function (response) {
                        return this.roomTypes = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getPlans(){
                let params = {
	                include: 'rooms',
                    page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/plans', { params: params }).then(function (response) {
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
                    include: 'squads.members.companions,squads.members.trip.group,type',
                    page: this.teamsPagination.current_page,
                };
                return this.$http.get('teams', { params: params }).then(function (response) {
                        this.teamsPagination = response.body.meta.pagination;
                        return this.teams = response.body.data;
                    }, function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },

	        // Modal Functions
            openNewPlanModel(){
                this.showPlanModal = true;
				this.selectedPlan = {
                    name: '',
                    short_desc: '',
                    rooms: [],
                    locked: false,
					campaign_id: this.campaignId,
					group_id: this.groupId,
                };
            },
	        newPlan() {
                this.$http.post('rooming/plans', this.selectedPlan).then(function (response) {
	                let plan = response.body.data;
                    this.plans.push(plan);
                    this.currentPlan = plan;
                    this.showPlanModal = false;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
            openNewRoomModel(){
                this.showRoomModal = true;
                this.selectedRoom = {
                    room_type_id: null,
                    type: null,
                    label: '',
                    occupants: [],
                };
            },
	        newRoom() {
		         this.$http.post('rooming/plans/' + this.currentPlan.id + '/rooms' , this.selectedRoom).then(function (response) {
                     let room = response.body.data;
                     this.showRoomModal = false;
                     this.currentPlan.rooms.push(room);
                     this.activeRooms.push(room);
		         }, function (response) {
			         console.log(response);
			         return response.body.data;
		         });
            },
            openDeletePlanModal() {
                this.showPlanDeleteModal = true;
            },
            deletePlan() {
                let plan = _.extend({}, this.currentPlan);
                this.$http.delete('rooming/plans/' + plan.id).then(function (response) {
                    this.showPlanDeleteModal = false;
                    this.$root.$emit('showInfo', plan.name + ' Deleted!');
                    this.plans = _.reject(this.plans, function (obj) {
	                    return plan.id === obj.id;
                    });
                    this.currentplan = this.plans.length ? this.plans[0] : null;
                });

            }
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
                }));
            }

            promises.push(this.getPlans());
            promises.push(this.getRoomTypes());
            promises.push(this.getTeams());
//            promises.push(this.getRoles());
            Promise.all(promises).then(function (values) {
                this.startUp = false;
            }.bind(this));

            this.$root.$on('campaign-scope', function (val) {
                this.campaignId = val ? val.id : '';
                this.$root.$emit('update-title', val ? val.name : '');
            }.bind(this));

        }
    }
</script>