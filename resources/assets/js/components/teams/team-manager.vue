<template>
	<div class="row" style="position:relative;">
		<div class="col-md-7">
			<h3 v-if="currentTeam" class="">{{ currentTeam.name }}</h3>
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#members" data-toggle="pill"><i class="fa fa-user"></i> Members</a>
				</li>
				<li role="presentation">
					<a href="#teamdetails" data-toggle="pill"><i class="fa fa-list"></i> Team Details</a>
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
							<template v-if="currentTeam">
								<template v-if="currentTeam.team_groups.length">
									<template v-for="(tgIndex, team_group) in currentTeam.team_groups | filterBy membersSearch">
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
											<p class=" text-right">
												<button class="btn btn-xs btn-primary" @click="newGroup">Add Group</button>
											</p>
											<hr class="divider sm">
										</template>
									</template>
									<template v-for="(tgIndex, team_group) in currentTeam.team_groups | filterBy membersSearch">
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
									<p>This team currently has no Groups</p>
								</template>
							</template>
							<template v-else>
								<p>Select a Team to get started or <a @click="newTeam">create a new one</a>!</p>
							</template>
						</div>
					</div>

				</div>
				<div role="tabpanel" class="tab-pane" id="teamdetails">
					<form class="form-horizontal" v-if="currentTeam">
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  placeholder="Name" v-model="currentTeam.name">
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
					<a href="#teams" data-toggle="pill"><i class="fa fa-group"></i> Teams</a>
				</li>
				<li role="presentation">
					<a href="#reservations" data-toggle="pill"><i class="fa fa-ticket"></i> Reservations</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="teams">
					<div class="row">
						<div class="col-xs-12 text-right">
							<button class="btn btn-primary btn-xs" @click="newTeam">New Team</button>
							<hr class="divider sm">
						</div>
						<div class="col-xs-12">
							<template v-if="teams.length">
								<ul class="list-group">

									<a class="list-group-item" :class="{'active': currentTeam === team}" v-for="team in teams" @click="makeTeamCurrent(team)">
										<div class="row">
											<div class="col-xs-6">{{ team.name | uppercase }}</div>
											<div class="col-xs-6 text-right">Members: {{ countMembers(team) || 0 }}</div>
										</div>
									</a>
								</ul>
								<pagination :pagination.sync="teamsPagination" :callback=""></pagination>
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
																<li v-for="team_group in currentTeam.team_groups" v-show="canAssignToGroup(team_group)"><a @click="assignToTeamGroup(reservation, team_group)" v-text="team_group.squad_leaders ? 'Assign as Squad Leader' : ('Assign to Team Group #' + $index)"></a></li>
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
	            group: null,
                startUp: true,
	            reservationsFacilitator: false,
                includeReservationsManaging: false,
                reservationsTrips: [],
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
            }

        },
	    computed: {
            excludeReservationIds() {
                let IDs = [];
                if (this.currentTeam)
	                _.each(this.currentTeam.team_groups, function (teamGroup) {
		                IDs = _.union(IDs, _.pluck(teamGroup.members, 'reservation_id'));
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
                this.$http.get('groups/' + this.groupId + '/teams', { params: { }}).then(function (response) {
	                this.teams = response.body.data;
                });
            },
	        newTeam(){
                let team = {
                    name: 'Team #' + (this.teams.length + 1),
	                type: 'default',
	                squad_leaders: 2,
	                max_group_members: 5,
	                max_members: 25,
	                team_groups: [
		                {
		                    squad_leaders: true,
		                    members: []
		                },
		                {
                            squad_leaders: false,
                            members: []
		                },
	                ]
                };

                this.teams.push(team);
                this.currentTeam = team;
                $('.nav-tabs a[href="#reservations"]').tab('show');
	        },
	        newGroup(){
	            let teamGroup = {
                    squad_leaders: false,
                    members: []
	            };

                this.currentTeam.team_groups.splice(1, 0, teamGroup);
	        },
	        makeTeamCurrent(team){
	            this.currentTeam = team;
	        },
	        assignToTeamGroup(reservation, teamGroup) {
				//Find if a parent(group leader) is present
	            let parent = _.findWhere(teamGroup.members, { parent: true});

                let spot = {
                    parent: false,
                    parent_id: parent ? parent.id : '',
                    team_id: this.currentTeam.id || '',
                    role_id: '',
                    reservation_id: reservation.id,
                    reservation: reservation
                };

                teamGroup.members.push(spot);
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
	            _.each(team.team_groups, function (group) {
		            total += group.members.length;
                });
	            return total;
            }

        },
        ready(){
            let userPromise = this.$http.get('users/' + this.userId, {
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
//                    this.getCosts();
//                    this.getRequirements();
//                    this.getRoles();
                }
            });

            Promise.all([userPromise]).then(function (values) {
                this.startUp = false;
                this.searchReservations();
            }.bind(this));

        }
    }
</script>