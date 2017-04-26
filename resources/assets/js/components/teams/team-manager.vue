<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-md-7">
			<h4 v-if="currentTeam" class="">{{ currentTeam.callsign }} <small>&middot; Team Name</small></h4>
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#members" data-toggle="pill">Members <span class="badge" v-text="totalMembers"></span></a>
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
						<div class="form-group col-lg-8 col-md-8 col-sm-7 col-xs-12">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="membersSearch" debounce="250" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-lg-4 col-md-4 col-sm-5 col-xs-12">
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
								<template v-if="currentSquads && !currentSquads.length">
									<p class="text-center text-italic text-muted">This team currently has no Squads</p>
								</template>

								<template v-for="(tgIndex, squad) in currentSquads | filterBy membersSearch">
									<template v-if="squad.callsign === 'Team Leaders'">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title" v-text="squad.callsign"></h3>
											</div>
											<div class="panel-body">
												<div class="panel-group" id="SquadLeaderAccordion" role="tablist" aria-multiselectable="true">
													<div class="panel panel-default" v-for="member in squad.members">
														<div class="panel-heading" role="tab" id="headingOne">
															<h4 class="panel-title">
																<div class="row">
																	<div class="col-xs-9">
																		<a role="button" data-toggle="collapse" data-parent="#SquadLeaderAccordion" :href="'#squadLeaderItem' + $index" aria-expanded="true" aria-controls="collapseOne">
																			<img :src="member.avatar" class="img-circle img-xs pull-left" style="margin-right: 10px">
																			{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}<br>
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
																					<template v-if="subSquad.callsign !== 'Team Leaders'">
																						<li v-if="canAssignToTeamLeaders(member)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Team Leaders</a></li>
																						<li v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																						<li v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																					</template>
																				</template>
																				<li role="separator" class="divider"></li>
																				<li v-if="member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
																				<li v-if="!member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
																				<li><a @click="removeFromSquad(member, squad)">Remove</a></li>
																			</ul>
																		</dropdown>
																		<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#SquadLeaderAccordion" :href="'#squadLeaderItem' + $index" aria-expanded="true" aria-controls="collapseOne">
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
																		<p class="small">{{member.gender | capitalize}}</p>
																		<label>Marital Status</label>
																		<p class="small">{{member.status | capitalize}}</p>
																	</div><!-- end col -->
																	<div class="col-sm-6">
																		<label>Age</label>
																		<p class="small">{{member.age}}</p>
																		<label>Group Traveling with Companions</label>
																		<p class="small">{{member.companion_limit}}</p>
																	</div><!-- end col -->
																</div><!-- end row -->
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
								<template v-for="(tgIndex, squad) in currentSquads | filterBy membersSearch">
									<template v-if="squad.callsign !== 'Team Leaders'">
										<div class="panel panel-default">
											<div class="panel-heading">
												<div class="row">
													<h5 class="col-xs-8" v-text="squad.callsign"></h5>
													<div class="col-xs-4 text-right">
														<a @click="showSquadUpdateModal = true,selectedSquadObj = squad" class="btn btn-xs btn-default-hollow">
															<i class="fa fa-pencil"></i>
														</a>
														<a @click="showSquadDeleteModal = true,selectedSquadObj = squad" class="btn btn-xs btn-default-hollow">
															<i class="fa fa-trash"></i>
														</a>
													</div>
												</div>
											</div>
											<div class="panel-body">
												<div class="panel-group" :id="'membersAccordion' + tgIndex" role="tablist" aria-multiselectable="true">
													<div class="panel panel-default" v-for="member in squad.members">
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
																						<li v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ''"></a></li>
																						<li v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																					</template>
																					<template v-else>
																						<li v-if="canAssignToTeamLeaders(member)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Team Leaders</a></li>
																						<li v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																						<li v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																					</template>
																				</template>
																				<li role="separator" class="divider"></li>
																				<li v-if="member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
																				<li v-if="!member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
																				<li><a @click="removeFromSquad(member, squad)">Remove</a></li>
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
																		<label>Group Traveling with Companions</label>
																		<p class="small">{{member.companion_limit}}</p>
																	</div><!-- end col -->
																</div><!-- end row -->
															</div><!-- end panel-body -->
														</div>
													</div>
												</div>
											</div>
										</div>
									</template>
								</template>
							</template>
							<template v-else>
								<hr class="divider inv">
								<p class="text-center text-muted"><em>Select a Team to get started or create a new one!</em></p>
								<hr class="divider inv">
								<p class="text-center"><a class="btn btn-link btn-sm" @click="showTeamCreateModal = true">Create A Team</a></p>
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
							<label for="" class="col-sm-4 control-label">Team Leader</label>
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

						<ul class="list-group" v-if="currentSquads.length">
							<li class="list-group-item">
								<span class="badge" v-text="leaderSquad.members.length"></span>
								Team Leaders
							</li>
							<li class="list-group-item">
								<span class="badge" v-text="groupLeaders.length"></span>
								Group Leaders
							</li>
							<li class="list-group-item">
								<span class="badge" v-text="totalMembers"></span>
								Total Members
							</li>
						</ul>
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
							<button class="btn btn-primary btn-xs" @click="showTeamCreateModal = true">Create A Team</button>
							<hr class="divider lg">
						</div>
						<div class="col-xs-12">
							<template v-if="teams.length">
								<ul class="list-group">

									<a class="list-group-item" :class="{'active': currentTeam === team}" v-for="team in teams" @click="makeTeamCurrent(team)">
										<div class="row">
											<div class="col-xs-6">{{ team.callsign | capitalize }}</div>
											<div class="col-xs-6 text-right">Members: {{ countMembers(team) || 0 }}</div>
										</div>
									</a>
								</ul>
								<div class="col-xs-12 text-center">
									<pagination :pagination.sync="teamsPagination" :callback="getTeams"></pagination>
								</div>

							</template>
							<template v-else>
								<hr class="divider inv">
								<p class="text-center text-italic text-muted"><em>No Teams created yet. Create one to get started!</em></p>
								<hr class="divider inv">
								<p class="text-center"><a class="btn btn-link btn-sm" @click="showTeamCreateModal = true">Create A Team</a></p>
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
															<li class="dropdown-header">Assign To Team</li>
															<li role="separator" class="divider"></li>
															<template v-for="squad in currentSquads">
																<template v-if="squad.callsign === 'Team Leaders'">
																	<li v-if="canAssignToTeamLeaders(squad)"><a @click="assignToSquad(reservation, squad, false)">Team Leader</a></li>
																</template>
																<template v-else>
																	<li v-if="canAssignToSquadLeader(squad)"><a @click="assignToSquad(reservation, squad, true)" v-text="squad.callsign + ' Leader'"></a></li>
																	<li v-if="canAssignToSquad(squad)"><a @click="assignToSquad(reservation, squad, false)" v-text="squad.callsign"></a></li>
																</template>
															</template>
														</ul>
													</dropdown>
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
													<label>Group Traveling with Companions</label>
													<p class="small">{{reservation.companion_limit}}</p>
												</div><!-- end col -->
											</div><!-- end row -->
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

		<modal title="Delete Team" small ok-text="Delete" :callback="deleteTeam" :show.sync="showTeamDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedSquadObj">
					Are you sure you want to delete {{selectedSquadObj.callsign}} ?
				</p>
			</div>
		</modal>

		<modal title="Delete Group" small ok-text="Delete" :callback="deleteSquad" :show.sync="showSquadDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedSquadObj">
					Are you sure you want to delete {{selectedSquadObj.callsign}} ?
				</p>
			</div>
		</modal>

		<modal title="Create a new Group" small ok-text="Create" :callback="newSquad" :show.sync="showSquadCreateModal">
			<div slot="modal-body" class="modal-body">
				<validator name="SquadCreate">
					<form id="SquadCreateForm">
						<div class="form-group" :class="{'has-error': $SquadCreate.squadcallsign.invalid}">
							<label for="createSquadCallsign" class="control-label">Group Name</label>
							<input type="text" class="form-control" id="createSquadCallsign" placeholder="" v-validate:squadcallsign="['required']" v-model="newSquadCallsign">
						</div>
					</form>
				</validator>
			</div>
		</modal>

		<modal title="Edit Group" small ok-text="Update" :callback="updateSquad" :show.sync="showSquadUpdateModal">
			<div slot="modal-body" class="modal-body">
				<validator name="SquadEdit" v-if="selectedSquadObj">
					<form id="SquadEditForm">
						<div class="form-group" :class="{'has-error': $SquadEdit.editsquadcallsign.invalid}">
							<label for="createSquadCallsign" class="control-label">Group Name</label>
							<input type="text" class="form-control" id="createSquadCallsign" :value.once="selectedSquadObj.callsign" placeholder="" v-validate:editsquadcallsign="['required']" v-model="editSquadCallsign">
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
                showSquadUpdateModal: false,
                editSquadCallsign: '',
                editSquadTeamId: undefined,
                selectedSquadObj: null,
                showTeamDeleteModal: false,
                showSquadDeleteModal: false,
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
		                IDs = _.union(IDs, _.pluck(squad.members, 'id'));
	                });
                return _.uniq(IDs);
            },
		    leaderSquad() {
                return this.currentSquads.length ? _.findWhere(this.currentSquads, { callsign: 'Team Leaders'}) : [];
		    },
		    groupLeaders() {
                let leaders = [];
                leaders.push(_.filter(this.currentSquads, function (squad) {
	                return _.findWhere(squad.members, { leader: true});
                }));
                return leaders;
		    },
            totalMembers() {
                let members = 0;
                _.each(this.currentSquads, function (squad) {
	                members += squad.members.length;
                });
                return members;
            },
            /*missionaries() {
				let leaders = [];
				leaders.push(_.filter(this.currentSquads, function (squad) {
					return _.findWhere(squad.members, { desired_role: { code: 'MISS'}}) || _.findWhere(squad.members, { desired_role: { code: 'MINR'}});
				}));
				return leaders;
			},
			influencers() {
				let leaders = [];
				leaders.push(_.filter(this.currentSquads, function (squad) {
					return _.findWhere(squad.members, { desired_role: { code: 'INFL'}});
				}));
				return leaders;
			},*/
	    },
        methods: {
            canAssignToTeamLeaders(squad){
	            return squad.callsign === 'Team Leaders' && squad.members && squad.members.length < this.currentTeam.squad_leaders;
            },
            canAssignToSquadLeader(squad){
                return !_.some(squad.members, function (member) {
	                return member.leader;
                });
            },
            canAssignToSquad(squad){
	            return  squad.members && squad.members.length < this.currentTeam.max_group_members;
            },
            assignToSquad(reservation, squad, leader) {
                // Rules for team leader group
                if (squad.callsign === 'Team Leaders') {
                    if (squad.members.length) {
                        let test = false;
						test = _.some(squad.members, function (member) {
							return member.gender === reservation.gender;
                        });
	                    if (test){
                            this.$root.$emit('showError', 'Team Leaders members can not be of the same gender.');
                            return;
                        }
                    }
                }

                this.$http.post('squads/' + squad.id + '/members', {
                    id: reservation.id,
                    leader: leader || false,
                }).then(function (response) {
                    squad.members = response.body.data;
                });
            },
            moveToSquad(reservation, oldSquad, newSquad, leader) {
                // Rules for team leader group
                if (newSquad.callsign === 'Team Leaders') {
                    if (newSquad.members.length) {
                        let test = false;
						test = _.some(newSquad.members, function (member) {
							return member.gender === reservation.gender;
                        });
	                    if (test){
                            this.$root.$emit('showError', 'Team Leaders members can not be of the same gender.');
                            return;
                        }
                    }
                }

                this.removeFromSquad(reservation, oldSquad).then(function (response) {
	                this.assignToSquad(reservation, newSquad, leader);
                });
            },
            removeFromSquad(memberObj, squad) {
                return this.$http.delete('squads/' + squad.id + '/members/' + memberObj.id)
	                .then(function (response) {
	                    squad.members = _.reject(squad.members, function (member) {
		                    return member.id === memberObj.id;
                        });
                    });
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
                    _.each(response.body.data, function (team) {
                        team = _.extend(team, {
                            type: 'default',
                            squad_leaders: 2,
                            max_group_members: 5,
                            max_members: 25,
                        });
                    });


                    return this.teams = response.body.data;
                });
            },
            getSquads(){
                let params = {
                    include: 'members.companions',
                    page: this.squadsPagination.current_page,
                };

                return this.$http.get('teams/' + this.currentTeam.id + '/squads', { params: params}).then(function (response) {
                    _.each(response.body.data, function (squad) {
	                    squad.members = squad.members && squad.members.data ? squad.members.data : [];
                    });
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
                        this.newSquad('Team Leaders');
                        this.newSquad('Group #1');

                        this.showTeamCreateModal = false;
                        $('.nav-tabs a[href="#reservations"]').tab('show');
                    });
                } else {
//                    this.$root.$emit('showError', '');
                }
	        },
	        newSquad(callsign){
                if (this.$SquadCreate.valid || _.isString(callsign)) {
                    return this.$http.post('teams/' + this.currentTeam.id + '/squads', {callsign: _.isString(callsign) ? callsign : this.newSquadCallsign})
                        .then(function (response) {
							let squad = response.body.data;
							squad.members = [];

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
	            this.$http.put('teams/' + team.id, team).then(function (response) {
					this.$root.$emit('showSuccess', team.callsign + ' Updated!');
                });
	        },
	        updateSquad(){
	            let data = {
                    callsign: this.editSquadCallsign,
                    team_id: this.editSquadTeamId
                };

	            this.$http.put('teams/' + this.selectedSquadObj.team_id + '/squads/' + this.selectedSquadObj.id, data).then(function (response) {
	                let squad = _.findWhere(this.currentSquads, { id: this.selectedSquadObj.id});
	                squad.callsign = response.body.data.callsign;
                    this.$root.$emit('showSuccess', response.body.data.callsign + ' Updated!');
                    this.editSquadTeamId = undefined;
                    this.showSquadUpdateModal = false;
                    this.selectedSquadObj = null;
                });
	        },
	        deleteTeam(team){
	            this.$http.delete('teams/' + team.id).then(function (response) {
                    this.$root.$emit('showInfo', team.callsign + ' Deleted!');
                })
	        },
	        deleteSquad(){
	            this.$http.delete('teams/' + this.selectedSquadObj.team_id + '/squads/' + this.selectedSquadObj.id).then(function (response) {
                    this.$root.$emit('showInfo', this.selectedSquadObj.callsign + ' Deleted!');
                    this.selectedSquadObj = null;
                    this.showSquadDeleteModal = false;
                })
	        },
	        makeTeamCurrent(team){
	            this.currentTeam = team;
                $('.nav-tabs a[href="#reservations"]').tab('show');
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
            },
            squadHasLeader(squad) {
	            return _.findWhere(squad.members, { leader: true });
            },
            demoteToMember(member, squad) {
                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
					leader: false
                }).then(function (response) {
                    this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' demoted to a group member');
	                member.leader = false;
                    return member;
                });

            },
            promoteToLeader(member, squad) {
                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
                    leader: true
                }).then(function (response) {
                    this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' promoted to Group Leader');
                    member.leader = true;
                });
            },

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