<template>
	<div class="row" style="position:relative;">
		<div class="col-md-7">
			<h3 v-if="currentTeam" class="">{{ currentTeam.callsign }}</h3>
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#members" data-toggle="pill">Members <span class="badge" v-text="0"></span></a>
				</li>
				<li role="presentation">
					<a href="#teamdetails" data-toggle="pill">Team Details</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="members">
					<!-- Search and Filter -->
					<form class="form-inline row">
						<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="membersSearch" debounce="250" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showFilters=!showFilters">
								Filters
								<i class="fa fa-filter"></i>
							</button>
						</div>
						<div class="col-xs-12">
							<hr class="divider inv">
						</div>
					</form>
					<!-- Team Groups List -->
					<div class="row">
						<div class="col-xs-12">
							<template v-if="currentTeam && currentSquads">
								<template v-if="!currentSquads || (currentSquads && currentSquads.length)">
									<p class="text-center">This team currently has no Squads</p>
								</template>

								<template v-for="(tgIndex, team_group) in currentSquads | filterBy membersSearch">
									<template v-if="team_group.squad_leaders">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">Squad Leaders</h3>
											</div>
											<div class="panel-body">
												<div class="panel-group" id="SquadLeaderAccordion" role="tablist" aria-multiselectable="true">
													<div class="panel panel-default" v-for="member in team_group.members">
														<div class="panel-heading" role="tab" id="headingOne">
															<h4 class="panel-title">
																<div class="row">
																	<div class="col-xs-9">
																		<a role="button" data-toggle="collapse" data-parent="#SquadLeaderAccordion" :href="'#squadLeaderItem' + $index" aria-expanded="true" aria-controls="collapseOne">
																			<img :src="member.reservation.avatar" class="img-circle img-xs pull-left" style="margin-right: 10px">
																			{{ member.reservation.surname | capitalize }}, {{ member.reservation.given_names | capitalize }}<br>
																			<label>{{ member.reservation.desired_role.name }}</label>
																		</a>
																	</div>
																	<div class="col-xs-3 text-right action-buttons">
																		<dropdown type="default">
																			<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																				<span class="fa fa-ellipsis-h"></span>
																			</button>
																			<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																				<li><a href="#dropdown">Action</a></li>
																				<li role="separator" class="divider"></li>
																				<li><a href="#dropdown">Separated link</a></li>
																			</ul>
																		</dropdown>
																		<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#SquadLeaderAccordion" :href="'#squadLeaderItem' + $index" aria-expanded="true" aria-controls="collapseOne">
																			<i class="fa fa-caret-down"></i>
																		</a>
																	</div>
																</div>
															</h4>
														</div>
														<div :id="'memberItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
															<div class="panel-body">
																<dl class="dl-horizontal">
																	<dt>Gender</dt>
																	<dd>{{member.reservation.gender | capitalize}}</dd>
																	<dt>Marital Status</dt>
																	<dd>{{member.reservation.status | capitalize}}</dd>
																	<dt>Age</dt>
																	<dd>{{member.reservation.age}}</dd>
																	<dt>Group Traveling with Companions</dt>
																	<dd>{{member.reservation.companion_limit}}</dd>
																</dl>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</template>
								</template>
								<p class=" text-right">
									<button class="btn btn-xs btn-primary" @click="showSquadCreateModal = true">Add Group</button>
								</p>
								<hr class="divider sm">
								<template v-for="(tgIndex, team_group) in currentSquads | filterBy membersSearch">
										<template v-if="!team_group.squad_leaders">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h3 class="panel-title">Team Group #{{$index}}</h3>
												</div>
												<div class="panel-body">
													<div class="panel-group" :id="'membersAccordion' + tgIndex" role="tablist" aria-multiselectable="true">
														<div class="panel panel-default" v-for="member in team_group.members">
															<div class="panel-heading" role="tab" id="headingOne">
																<h4 class="panel-title">
																	<div class="row">
																		<div class="col-xs-9">
																			<a role="button" data-toggle="collapse" :data-parent="'#membersAccordion' + tgIndex" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
																				<img :src="member.reservation.avatar" class="img-circle img-xs pull-left" style="margin-right: 10px">
																				{{ member.reservation.surname | capitalize }}, {{ member.reservation.given_names | capitalize }}<br>
																				<label>{{ member.reservation.desired_role.name }}</label>
																			</a>
																		</div>
																		<div class="col-xs-3 text-right action-buttons">
																			<dropdown type="default">
																				<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																					<span class="fa fa-ellipsis-h"></span>
																				</button>
																				<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																					<li><a href="#dropdown">Action</a></li>
																					<li role="separator" class="divider"></li>
																					<li><a href="#dropdown">Separated link</a></li>
																				</ul>
																			</dropdown>
																			<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#membersAccordion" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
																				<i class="fa fa-caret-down"></i>
																			</a>
																		</div>
																	</div>
																</h4>
															</div>
															<div :id="'memberItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																<div class="panel-body">
																	<dl class="dl-horizontal">
																		<dt>Gender</dt>
																		<dd>{{member.reservation.gender | capitalize}}</dd>
																		<dt>Marital Status</dt>
																		<dd>{{member.reservation.status | capitalize}}</dd>
																		<dt>Age</dt>
																		<dd>{{member.reservation.age}}</dd>
																		<dt>Group Traveling with Companions</dt>
																		<dd>{{member.reservation.companion_limit}}</dd>
																	</dl>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</template>
									</template>
							</template>
							<template v-else>
								<p>Select a Team to get started or <a @click="showTeamCreateModal = true">create a new one</a>!</p>
							</template>
						</div>
					</div>

				</div>
				<div role="tabpanel" class="tab-pane" id="teamdetails">
					<form class="form-horizontal" v-if="currentTeam">
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  placeholder="Name" v-model="currentTeam.callsign">
							</div>

						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Type</label>
							<div class="col-sm-8">
								<select class="form-control" v-model="currentTeam.type">
									<option value="default">Default</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Squad Leader</label>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<input type="number" number class="form-control" min="2" max="5" value="2">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Max Members per Group</label>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<input type="number" number class="form-control" min="2" max="5" value="5">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Max Team Members</label>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<input type="number" number class="form-control" min="2" max="25" value="25">
							</div>
						</div>
						<hr class="divider sm">
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#teams" data-toggle="pill">Teams <span class="badge" v-text="teamsPagination.total"></span></a>
				</li>
				<li role="presentation">
					<a href="#reservations" data-toggle="pill">Reservations <span class="badge" v-text="reservationsPagination.total"></span></a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="teams">
					<div class="row">
						<div class="col-xs-12 text-right">
							<button class="btn btn-primary btn-xs" @click="showTeamCreateModal = true">New Team</button>
							<hr class="divider sm">
						</div>
						<div class="col-xs-12">
							<template v-if="teams.length">
								<ul class="list-group">

									<a class="list-group-item" :class="{'active': currentTeam === team}" v-for="team in teams" @click="makeTeamCurrent(team)">
										<div class="row">
											<div class="col-xs-6">{{ team.callsign | uppercase }}</div>
											<div class="col-xs-6 text-right">Members: {{ countMembers(team) || 0 }}</div>
										</div>
									</a>
								</ul>
								<div class="col-xs-12 text-center">
									<pagination :pagination.sync="teamsPagination" :callback="getTeams"></pagination>
								</div>

							</template>
							<template v-else>
								<p class="text-center">No Teams created yet. Create one to get started!ember</p>
							</template>

						</div>

					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="reservations">
					<!-- Search and Filter -->
					<form class="form-inline row">
						<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="reservationsSearch" debounce="250" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showFilters=!showFilters">
								Filters
								<i class="fa fa-filter"></i>
							</button>
						</div>
						<div class="col-xs-12">
							<hr class="divider inv">
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
													<a role="button" data-toggle="collapse" data-parent="#reservationsAccordion" :href="'#reservationItem' + $index" aria-expanded="true" aria-controls="collapseOne">
														<img :src="reservation.avatar" class="img-circle img-xs pull-left" style="margin-right: 10px">
														{{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}<br>
														<label>{{ reservation.desired_role.name }}</label>
													</a>
												</div>
												<div class="col-xs-3 text-right action-buttons">
													<dropdown type="default">
														<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
															<span class="fa fa-ellipsis-h"></span>
														</button>
														<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
															<template v-if="currentTeam">
																<li v-for="team_group in currentSquads" v-show="canAssignToGroup(team_group)"><a @click="assignTosquad(reservation, team_group)" v-text="team_group.squad_leaders ? 'Assign as Squad Leader' : ('Assign to Team Group #' + $index)"></a></li>
															</template>
															<li role="separator" class="divider"></li>
															<li><a href="#dropdown">Separated link</a></li>
														</ul>
													</dropdown>
													<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#reservationsAccordion" :href="'#reservationItem' + $index" aria-expanded="true" aria-controls="collapseOne">
														<i class="fa fa-caret-down"></i>
													</a>
												</div>
											</div>

										</h4>
									</div>
									<div :id="'reservationItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										<div class="panel-body">
											<dl class="dl-horizontal">
												<dt>Gender</dt>
												<dd>{{reservation.gender | capitalize}}</dd>
												<dt>Marital Status</dt>
												<dd>{{reservation.status | capitalize}}</dd>
												<dt>Age</dt>
												<dd>{{reservation.age}}</dd>
												<dt>Group Traveling with Companions</dt>
												<dd>{{reservation.companion_limit}}</dd>
											</dl>
											<!--<tooltip effect="scale" placement="top" content="Complete">
												<span class="label label-success">{{ complete(reservation) }}</span>
											</tooltip>
											<tooltip effect="scale" placement="top" content="Needs Attention">
												<span class="label label-info">{{ attention(reservation) }}</span>
											</tooltip>
											<tooltip effect="scale" placement="top" content="Under Review">
												<span class="label label-default">{{ reviewing(reservation) }}</span>
											</tooltip>
											<tooltip effect="scale" placement="top" content="Incomplete">
												<span class="label label-danger" v-text="getIncomplete(reservation)"></span>
											</tooltip>-->
										</div>
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

		<!-- Modals -->
		<modal title="Create a new Team" small ok-text="Create" :callback="newTeam" :show.sync="showTeamCreateModal">
			<div slot="modal-body" class="modal-body">
				<validator name="TeamCreate">
					<form id="TeamCreateForm">
						<div class="form-group" :class="{'has-error': $TeamCreate.teamcallsign.invalid}">
							<label for="createTeamCallsign" class="control-label">Team Name</label>
							<input type="text" class="form-control" id="createTeamCallsign" placeholder="" v-validate:teamcallsign="['required']" v-model="newTeamCallsign">
						</div>
					</form>
				</validator>
			</div>
		</modal>

		<modal title="Delete Team" small ok-text="Create" :callback="newTeam" :show.sync="showTeamDeleteModal">
			<div slot="modal-body" class="modal-body">
				<validator name="TeamCreate">
					<form id="TeamCreateForm">
						<div class="form-group" :class="{'has-error': $TeamCreate.teamcallsign.invalid}">
							<label for="createTeamCallsign" class="control-label">Team Name</label>
							<input type="text" class="form-control" id="createTeamCallsign" placeholder="" v-validate:teamcallsign="['required']" v-model="newTeamCallsign">
						</div>
					</form>
				</validator>
			</div>
		</modal>

		<modal title="Create a new Squad" small ok-text="Create" :callback="newSquad" :show.sync="showSquadCreateModal">
			<div slot="modal-body" class="modal-body">
				<validator name="SquadCreate">
					<form id="SquadCreateForm">
						<div class="form-group" :class="{'has-error': $SquadCreate.squadcallsign.invalid}">
							<label for="createSquadCallsign" class="control-label">Squad Name</label>
							<input type="text" class="form-control" id="createSquadCallsign" placeholder="" v-validate:squadcallsign="['required']" v-model="newSquadCallsign">
						</div>
					</form>
				</validator>
			</div>
		</modal>
	</div>
</template>
<style>

</style>
<script type="text/javascript">
    export default{
        name: 'team-manager',
	    props: {
            userId: {
                type: String,
                required: true
            },
            groupId: {
                type: String,
                required: true
            },

        },
        data(){
            return {
                teams: [],
                teamsPagination: { current_page: 1 },
                reservations: [],
                membersSearch: '',
                reservationsSearch: '',
                reservationsPerPage: 10,
                reservationsPagination: { current_page: 1 },
	            currentTeam: null,
	            currentSquads: [],
                squadsPagination: { current_page: 1 },
	            group: null,
                startUp: true,
	            reservationsFacilitator: false,
                includeReservationsManaging: false,
                reservationsTrips: [],

                showTeamCreateModal: false,
                newTeamCallsign: '',
                showSquadCreateModal: false,
                newSquadCallsign: '',
                showTeamDeleteModal: false,
            }
        },
	    watch: {
            'reservationsSearch': function (val, oldVal) {
                this.reservationsPagination.current_page = 1;
                this.searchReservations();
            },
		    'excludeReservationIds': function (val) {
                this.reservationsPagination.current_page = 1;
                this.searchReservations();
            },
		    'currentTeam': function (val) {
			    this.getSquads();
            }

        },
	    computed: {
            excludeReservationIds() {
                let IDs = [];
                if (this.currentTeam)
	                _.each(this.currentSquads, function (squad) {
		                IDs = _.union(IDs, _.pluck(squad.members, 'reservation_id'));
	                });
                return _.uniq(IDs);
            },
	    },
        methods: {
            canAssignToGroup(team_group){
                return team_group.squad_leaders && team_group.members.length < this.currentTeam.squad_leaders || !team_group.squad_leaders && team_group.members.length < this.currentTeam.max_group_members;
            },
            searchReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,fundraisers,costs.payments,user',
                    search: this.reservationsSearch,
                    per_page: this.reservationsPerPage,
                    page: this.reservationsPagination.current_page,
	                current: true,
	                groups: new Array(this.groupId),
	                trip: this.reservationsTrips.length ? this.reservationsTrips : new Array(),
	                ignore: this.excludeReservationIds,
                };

                /*$.extend(params, this.filters);
                $.extend(params, {
                    age: [ this.ageMin, this.ageMax]
                });*/

                // this.$refs.spinner.show();
                return this.$http.get('reservations', { params: params }).then(function (response) {
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
                    include: '',
                    page: this.teamsPagination.current_page,

                };

                return this.$http.get('teams', { params: params}).then(function (response) {
	                this.teamsPagination = response.body.meta.pagination;
                    return this.teams = response.body.data;
                });
            },
            getSquads(){
                let params = {
                    include: '',
                    page: this.squadsPagination.current_page,

                };

                return this.$http.get('teams/' + this.currentTeam.id + '/squads', { params: params}).then(function (response) {
                    return this.currentSquads = response.body.data;
                });
            },
	        newTeam(){
                if (this.$TeamCreate.valid) {
                    this.$http.post('teams', {callsign: this.newTeamCallsign}).then(function (response) {
                        let team = _.extend(response.body.data, {
                            type: 'default',
                            squad_leaders: 2,
                            max_group_members: 5,
                            max_members: 25,
                        });

                        this.currentTeam = team;

                        // Create default squads for current team
                        this.newSquad('Squad Leaders');
                        this.newSquad('Squad #1');

                        this.showTeamCreateModal = false;
                        $('.nav-tabs a[href="#reservations"]').tab('show');
                    });
                } else {
//                    this.$emit('showError', '');
                }
	        },
	        newSquad(callsign){
                if (this.$SquadCreate.valid || _.isString(callsign)) {
                    return this.$http.post('teams/' + this.currentTeam.id + '/squads', {callsign: _.isString(callsign) ? callsign : this.newSquadCallsign})
                        .then(function (response) {
							let squad = response.body.data;

							if (this.currentSquads.length) {
                                this.currentSquads.splice(1, 0, squad);
							} else {
                                this.currentSquads.push(squad);
							}

                            this.showSquadCreateModal = false;
                            return squad;
                        });
                }
	        },
	        updateTeam(team){
	            this.$http.put('team/' + team.id, team).then(function (response) {
					this.$emit('showSuccess', team.callsign + ' Updated!');
                });
	        },
	        updateSquad(squad){
	            this.$http.put('team/' + squad.team_id + '/squads/' + squad.id, squad).then(function (response) {
                    this.$emit('showSuccess', squad.callsign + ' Updated!');
                });
	        },
	        deleteTeam(team){
	            this.$http.delete('team/' + team.id).then(function (response) {

                })
	        },
	        deleteSquad(squad){
	            this.$http.delete('team/' + squad.team_id + '/squads/' + squad.id).then(function (response) {

                })
	        },
	        makeTeamCurrent(team){
	            this.currentTeam = team;
                $('.nav-tabs a[href="#reservations"]').tab('show');
            },
	        assignTosquad(reservation, squad) {
				//Find if a parent(group leader) is present
	            let parent = _.findWhere(squad.members, { parent: true});

                let member = {
                    parent: false,
                    parent_id: parent ? parent.id : '',
                    team_id: this.currentTeam.id || '',
                    role_id: '',
                    reservation_id: reservation.id,
                    reservation: reservation
                };

                squad.members.push(member);
	        },
            getIncomplete(reservation) {
                return _.where(reservation.requirements.data, {status: 'incomplete'}).length;
            },
            complete(reservation) {
                return _.where(reservation.requirements.data, {status: 'complete'}).length;
            },
            attention(reservation) {
                return _.where(reservation.requirements.data, {status: 'attention'}).length;
            },
            reviewing(reservation) {
                return _.where(reservation.requirements.data, {status: 'reviewing'}).length;
            },
            countMembers(team) {
	            let total = 0;
	            _.each(team.team_squads, function (group) {
		            total += group.members.length;
                });
	            return total;
            }

        },
        ready(){
            let promises = [];
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
//                    this.getRoles();
                }
            }));
            promises.push(this.getTeams());

            Promise.all(promises).then(function (values) {
                this.startUp = false;
                this.searchReservations();
            }.bind(this));

        }
    }
</script>