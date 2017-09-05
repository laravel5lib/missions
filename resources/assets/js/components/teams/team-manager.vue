<template>
	<div>
		<div class="row" v-if="!isAdminRoute">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="text-uppercase"><a data-toggle="collapse" href="#collapseHints" aria-expanded="true" aria-controls="collapseHints">Squad Management Made Simple <span class="pull-right"><i class="fa" :class="{ 'fa-chevron-up': toggleHintsCollapse, 'fa-chevron-down': !toggleHintsCollapse}"></i></span></a></h6>
					</div><!-- end panel-heading -->
					<div class="panel-body panel-collapse collapse in" id="collapseHints">
						<div class="row">
							<div class="col-xs-12 col-sm-4">
								<h6 class="text-uppercase" style="margin-top:0px;">Follow these simple steps</h6>
								<p class="small">Missions.Me has created a system to help you manage your squads. Add missionaries to specific squads using this simple tool.</p>
							</div><!-- end col -->
							<div class="col-xs-12 col-sm-8">
								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<p class="small"><strong>Step 1</strong> Select a squad.</p>
										<p class="small"><strong>Step 2</strong> Assign Squad Members using the dropdown menu on reservations.</p>
										<p class="small"><strong>Step 3</strong> Assign a Squad Leader.</p>
									</div><!-- end col -->
									<div class="col-xs-12 col-sm-6">
										<p class="small"><strong>Step 4</strong> Assign a Group Leader.</p>
										<p class="small"><strong>Step 5</strong> Add Squad Members to groups.</p>
										<p class="small"><strong>Step 6</strong> Create more groups as necessary then repeat!</p>
									</div><!-- end col -->
								</div><!-- end row -->
							</div><!-- end col -->
						</div><!-- end row -->
					</div><!-- end panel-body -->
				</div><!-- end panel -->
			</div>
		</div>

		<div class="row" style="position:relative;">
			<spinner ref="spinner" size="sm" text="Loading"></spinner>
			<mm-aside :show="showTeamsFilters" @open="showTeamsFilters=true" @close="showTeamsFilters=false" placement="left" header="Team Filters" :width="375">
				<hr class="divider inv sm">
				<form class="col-sm-12">

					<div class="form-group" v-if="isAdminRoute">
						<label>Travel Group</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
						          v-model="groupObj" :options="groupsOptions" label="name"
						          placeholder="Filter Group"></v-select>
					</div>

					<hr class="divider inv sm">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="resetTeamFilter()"><i class="fa fa-times"></i> Reset Team Filters</button>
				</form>
			</mm-aside>
			<mm-aside :show="showMembersFilters" @open="showMembersFilters=true" @close="showMembersFilters=false" placement="left" header="Members Filters" :width="375">
				<hr class="divider inv sm">
				<form class="col-sm-12">

					<hr class="divider inv sm">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter"><i class="fa fa-times"></i> Reset Filters</button>
				</form>
			</mm-aside>

			<div class="col-xs-12" v-if="currentTeam">
				<h3>{{ currentTeam.callsign }} <span v-if="isLocked" class="label label-info"><i class="fa fa-lock"></i> Locked</span> <small>&middot; Squad Name</small></h3>
				<hr class="divider lg">
			</div>

			<div class="col-md-7">
				<ul class="nav nav-tabs">
					<li role="presentation" class="active">
						<a href="#members" data-toggle="pill">Members <span class="badge" v-text="totalMembers"></span></a>
					</li>
					<li role="presentation">
						<a href="#teamdetails" data-toggle="pill">Squad Details</a>
					</li>
					<li role="presentation">
						<a href="#notes" data-toggle="pill">Notes</a>
					</li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="members">
						<!-- Search and Filter -->
						<form class="form-inline row">
							<div class="form-group col-lg-8 col-md-8 col-sm-7 col-xs-12">
								<div class="input-group input-group-sm col-xs-12">
									<input type="text" class="form-control" v-model="membersSearch" debounce="300" placeholder="Search">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
								</div>
							</div>
							<div class="col-xs-12">
								<hr class="divider inv">
							</div>
						</form>
						<!-- Squad Groups List -->
						<div class="row">
							<div class="col-xs-12">
								<template v-if="currentTeam && currentSquadGroups">
									<template v-if="currentSquadGroups && !currentSquadGroups.length">
										<p class="text-center text-italic text-muted">This squad currently has no Squads</p>
									</template>

									<!-- Squad Leaders Group -->
									<template v-for="(squad, teamGroupIndex) in currentSquadGroupsFiltered">
										<template v-if="squad.callsign === 'Squad Leaders'">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h3 class="panel-title">Squad Leaders</h3>
												</div>
												<div class="panel-body">
													<div class="alert alert-warning" v-if="squad.members.length < currentTeam.type.data.rules.min_leaders">
														Squad does not have the minimum {{currentTeam.type.data.rules.min_leaders}} squad {{currentTeam.type.data.rules.min_leaders > 1 ? 'leaders' : 'leader'}}.
													</div>
													<div class="alert alert-success" v-if="squad.members_count >= currentTeam.type.data.rules.max_leaders">
														Complete! You've filled all the positions.
													</div>
													<members-list-slot :members="sortByLeader(squad.members)" list-id="SquadLeaderAccordion" :list-index="teamGroupIndex">
														<template slot="action-buttons" scope="props">
															<dropdown type="default" btn-classes="btn btn-xs btn-primary-hollow">
																<span slot="button" class="fa fa-ellipsis-h"></span>
																<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																	<template v-for="subSquad in currentSquadGroupsOrdered">
																		<template v-if="subSquad.callsign !== 'Squad Leaders'">
																			<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(subSquad)"><a @click="moveToSquad(props.member, squad, subSquad, false)">Move to Squad Leaders</a></li>
																			<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad, subSquad)"><a @click="moveToSquad(props.member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																			<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad)"><a @click="moveToSquad(props.member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																		</template>
																	</template>
																	<li :class="{'disabled': isLocked}" role="separator" class="divider"></li>
																	<!--<li :class="{'disabled': isLocked}" v-if="member && member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>-->
																	<!--<li :class="{'disabled': isLocked}" v-if="member && !member.leader && !squadHasLeader(squad)"<a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>-->
																	<li :class="{'disabled': isLocked}"><a @click="removeFromSquad(props.member, squad)">Remove</a></li>
																</ul>
															</dropdown>
														</template>
													</members-list-slot>
												</div>
											</div>
										</template>
									</template>
									<p class=" text-right" v-if="currentSquadGroups.length < currentTeam.type.data.rules.max_groups">
										<button :disabled="isLocked" class="btn btn-xs btn-primary" @click="showSquadCreateModal = true">Add Group</button>
									</p>
									<hr class="divider sm">
									<!-- Other Groups -->
									<template v-for="(squad, teamGroupIndex) in currentSquadGroupsOrderedFiltered">
										<template v-if="squad.callsign !== 'Squad Leaders'">
											<div class="panel panel-default">
												<div class="panel-heading">
													<div class="row">
														<h5 class="col-xs-8" v-text="squad.callsign"></h5>
														<div class="col-xs-4 text-right">
															<dropdown type="default" btn-classes="btn btn-xs btn-primary-hollow">
																<span slot="button" class="fa fa-ellipsis-h"></span>
																<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																	<li :class="{'disabled': isLocked}"><a @click="showSquadUpdateModal = true,selectedSquadObj = squad">Edit</a></li>
																	<template v-for="subTeam in teams">
																		<li role="separator" class="divider" v-if="teamGroupIndex === 0 && teams.length > 1"></li>
																		<li v-if="subTeam.id !== squad.team_id" :class="{'disabled': isLocked}"><a @click="moveToTeam(squad, subTeam)">Move group to {{subTeam.callsign}}</a></li>
																	</template>
																	<li role="separator" class="divider"></li>
																	<li :class="{'disabled': isLocked}"><a @click="showSquadDeleteModal = true,selectedSquadObj = squad">Delete</a></li>
																</ul>
															</dropdown>
														</div>
													</div>
												</div><!-- end panel-heading -->
												<div class="panel-body">
													<div class="alert alert-warning" v-if="!squadHasLeader(squad)">
														Group does not have a group leader. Please assign one.
													</div>
													<div class="alert alert-success" v-if="squad.members_count >= currentTeam.type.data.rules.max_group_members">
														Complete! You've filled all the positions.
													</div>
													<members-list-slot :members="sortByLeader(squad.members)" list-id="memberAccordion" :list-index="teamGroupIndex">
														<template slot="action-buttons" scope="props">
															<dropdown type="default" btn-classes="btn btn-xs btn-primary-hollow">
																<span slot="button" class="fa fa-ellipsis-h"></span>
																<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																	<template v-for="subSquad in currentSquadGroupsOrdered">
																		<template v-if="subSquad.callsign === 'Squad Leaders'">
																			<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad) && isLeadership(props.member)"><a @click="moveToSquad(props.member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																			<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad) && isLeadership(props.member)"><a @click="moveToSquad(props.member, squad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																			<li role="separator" class="divider"></li>
																		</template>
																		<template v-else>
																			<template v-if="subSquad.id !== squad.id">
																				<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(subSquad)"><a @click="moveToSquad(props.member, squad, subSquad, false)">Move to Squad Leaders</a></li>
																				<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad) && isLeadership(props.member)"><a @click="moveToSquad(props.member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																				<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad)"><a @click="moveToSquad(props.member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																				<li role="separator" class="divider"></li>
																			</template>
																		</template>
																	</template>
																	<li :class="{'disabled': isLocked}" v-if="props.member && props.member.leader"><a @click="demoteToMember(props.member, squad)">Demote to Group Member</a></li>
																	<li :class="{'disabled': isLocked}" v-if="props.member && !props.member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(props.member, squad)">Promote to Group Leader</a></li>
																	<li :class="{'disabled': isLocked}"><a @click="removeFromSquad(props.member, squad)">Remove</a></li>
																	<li role="separator" class="divider"></li>
																	<li class="dropdown-header">Change Role</li>
																	<li role="separator" class="divider"></li>
																	<li v-if="props.member.desired_role.name !== 'Squad Leader'"><a @click="updateRole(props.member, 'Squad Leader')">Squad Leader</a></li>
																	<li v-if="props.member.desired_role.name !== 'Group Leader'"><a @click="updateRole(props.member, 'Group Leader')">Group Leader</a></li>
																</ul>
															</dropdown>
														</template>
													</members-list-slot>
												</div>
											</div>
										</template>
									</template>
								</template>
								<template v-else>
									<hr class="divider inv">
									<p class="text-center text-muted"><em>Select a Squad to get started or create a new one!</em></p>
									<hr class="divider inv" v-if="isAdminRoute">
									<p class="text-center" v-if="isAdminRoute"><a class="btn btn-link btn-sm" @click="openNewTeamModel">Create A Squad</a></p>
								</template>
							</div>
						</div>

					</div>
					<div role="tabpanel" class="tab-pane" id="teamdetails">
						<form class="form-horizontal" v-if="currentTeam">
							<div class="row">
								<div class="col-sm-4">
									<label for="" class="control-label">Name</label>
									<input v-if="isAdminRoute && editTeamMode" type="text" class="form-control input-sm"  placeholder="Name" v-model="currentTeam.callsign">
									<p v-else v-text="currentTeam.callsign"></p>
								</div>
								<div class="col-sm-4">
									<label for="" class="control-label">Type</label>
									<!--<select v-if="isAdminRoute && editTeamMode" class="form-control" v-model="currentTeam.type_id" @change="updateCurrentTeamType">
										<option :value="type.id" v-for="type in teamTypes">{{ type.name | capitalize }}</option>
									</select>-->
									<p>{{currentTeam.type.data.name | capitalize}}</p>
								</div>
								<div class="col-sm-2">
									<label for="" class="control-label">Locked</label>
									<select v-if="isAdminRoute && editTeamMode" class="form-control" v-model="currentTeam.locked">
										<option :value="true">Yes</option>
										<option :value="false">No</option>
									</select>
									<p v-else v-text="currentTeam.locked ? 'Yes' : 'No'"></p>
								</div>
								<div class="col-sm-2" v-if="isAdminRoute && !editTeamMode">
									<label class="control-label"><i class="fa fa-cog"></i>
									<a @click="editTeamMode = true;">
										Edit
									</a>
									</label>
								</div>
								<div class="col-sm-12" v-if="isAdminRoute">
									<hr class="divider sm">
									<div class="form-group">
										<label class="control-label">Associated Groups</label>
										<div class="list-group">
											<div class="list-group-item" v-for="group in currentTeam.groups.data">
												{{ group.name }}
												<button v-if="editTeamMode" class="btn btn-xs btn-default-hollow pull-right" type="button" @click="confirmRemoveAssociation('groups', group)">
													<i class="fa fa-close"></i>
												</button>
											</div>
										</div>
										<template v-if="editTeamMode">
											<div class="form-group">
												<div class="col-sm-8">
													<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
													          v-model="addAssociationData.object" :options="unassociatedGroups" label="name"
													          placeholder="Add a Travel Group"></v-select>
												</div>
												<div class="col-sm-4">
													<button class="btn btn-primary btn-block" type="button" @click="addAssociation('groups', addAssociationData.object)">
														<i class="fa fa-plus-circle"></i> Group
													</button>
												</div>


											</div>
										</template>
									</div>
								</div>
								<div class="col-sm-12 text-right" v-if="editTeamMode">
									<hr class="divider inv">
									<a class="btn btn-default btn-sm" @click="editTeamMode = false;">
										Cancel
									</a>
									<button type="button" class="btn btn-primary btn-sm" @click="updateTeamSettings">Save Changes</button>
									<button type="button" v-if="isAdminRoute" class="btn btn-default btn-sm" @click="deleteTeam(currentTeam)">Delete Squad</button>
								</div>
							</div>

							<hr class="divider">
							<div class="form-group">
								<div class="col-sm-6">
									<label class="control-label">Assignment Rules</label>
									<hr class="divider sm inv">
									<ul class="list-group" v-if="currentSquadGroups.length">
										<li class="list-group-item" v-for="(value, key) in currentTeam.type.data.rules">
											<span class="badge" v-text="value"></span>
											{{ getRuleLabel(key) }}
										</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<label class="control-label">Currently Assigned</label>
									<hr class="divider sm inv">
									<ul class="list-group" v-if="currentSquadGroups.length">
										<li class="list-group-item" v-if="leaderSquad && leaderSquad.members">
											<span class="badge" v-text="leaderSquad.members.length"></span>
											Squad Leaders
										</li>
										<li class="list-group-item" v-if="groupLeaders">
											<span class="badge" v-text="groupLeaders.length"></span>
											Group Leaders
										</li>
										<li class="list-group-item" v-if="totalMembers">
											<span class="badge" v-text="totalMembers"></span>
											Total Members
										</li>
									</ul>
								</div>
							</div>
						</form>
					</div>
					<div role="tabpanel" class="tab-pane" id="notes" v-if="currentTeam">
						<notes type="teams"
					           :id="currentTeam.id"
					           :user_id="userId"
					           :per_page="5"
					           :can-modify="isAdminRoute ? 1 : 0">
					    </notes>
					</div>
				</div>
			</div>

			<div class="col-md-5">
				<ul class="nav nav-tabs">
					<li role="presentation" class="active">
						<a href="#teams" data-toggle="pill">Squads <span class="badge" v-text="teamsPagination.total"></span></a>
					</li>
					<li role="presentation">
						<a href="#reservations" data-toggle="pill">Reservations <span class="badge" v-if="$refs.reservations" v-text="$refs.reservations.reservationsPagination.total"></span></a>
					</li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="teams">
						<div class="row">
							<template v-if="isAdminRoute">
								<div class="col-xs-6">
									<div class="input-group input-group-sm col-xs-12">
										<input type="text" class="form-control" v-model="teamsSearch" @keyup="debouncedTeamsSearch" placeholder="Search">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
									</div>
								</div>
								<div class="col-xs-6">
									<button class="btn btn-primary btn-sm btn-block" @click="showTeamsFilters = true;">Filter</button>
								</div>
							</template>

							<div class="col-xs-12 text-right" v-if="isAdminRoute">
								<hr class="divider sm inv">
								<button class="btn btn-primary btn-sm" @click="openNewTeamModel">Create A Squad</button>
							</div>

							<div class="col-xs-12">
								<hr class="divider sm">
							</div>

							<div class="col-xs-12">
								<template v-if="teams.length">
									<ul class="list-group">

										<a class="list-group-item" :class="{'active': currentTeam === team}" v-for="team in teams" @click="makeTeamCurrent(team)">
											<div class="row list-group-item-heading">
												<div class="col-xs-6">
													{{ team.callsign | capitalize }}
													<span class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">{{team.type.data.name | capitalize}}</span>
													<span v-if="team.locked" style="padding:3px 10px;font-size:10px;line-height:1.4;" class="badge text-uppercase"><i class="fa fa-lock"></i> Locked</span>
												</div>
												<div class="col-xs-6 text-right"><i class="fa fa-users"></i> {{ team.members_count || 0 }}</div>
											</div>
											<p class="list-group-item-text small" v-if="team.groups.data.length">
												<span v-for="group in team.groups.data">{{group.name}}<span v-if="!team.groups.data[team.groups.data.length-1] && team.groups.data.length > 1">, </span></span>
											</p>
										</a>
									</ul>
									<div class="col-xs-12 text-center">
										<pagination :pagination="teamsPagination" pagination-key="teamsPagination" :callback="getTeams"></pagination>
									</div>

								</template>
								<template v-else>
									<hr class="divider inv">
									<p class="text-center text-italic text-muted"><em>No Squads created yet. Create one to get started!</em></p>
									<hr class="divider inv" v-if="isAdminRoute">
									<p class="text-center" v-if="isAdminRoute"><a class="btn btn-link btn-sm" @click="openNewTeamModel">Create A Squad</a></p>
								</template>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="reservations">
						<reservations-list-slot v-if="!startUp" ref="reservations">
							<template slot="action-buttons" scope="props">
								<dropdown type="default" btn-classes="btn btn-xs btn-primary-hollow">
									<span slot="button" class="fa fa-ellipsis-h"></span>
									<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
										<li class="dropdown-header">Assign To Squad</li>
										<li role="separator" class="divider"></li>
										<template v-for="squad in currentSquadGroupsOrdered">
											<template v-if="squad.callsign === 'Squad Leaders'">
												<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(squad) && isLeadership(props.reservation)"><a @click="assignToSquad(props.reservation, squad, false)">Squad Leader</a></li>
											</template>
											<template v-else>
												<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(squad) && isLeadership(props.reservation)"><a @click="assignToSquad(props.reservation, squad, true)" v-text="squad.callsign + ' Leader'"></a></li>
												<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(squad)"><a @click="assignToSquad(props.reservation, squad, false)" v-text="squad.callsign"></a></li>
											</template>
										</template>
										<li role="separator" class="divider"></li>
										<li class="dropdown-header">Change Role</li>
										<li role="separator" class="divider"></li>
										<li v-if="props.reservation.desired_role.name !== 'Squad Leader'"><a @click="updateRole(props.reservation, 'Squad Leader')">Squad Leader</a></li>
										<li v-if="props.reservation.desired_role.name !== 'Group Leader'"><a @click="updateRole(props.reservation, 'Group Leader')">Group Leader</a></li>
									</ul>
								</dropdown>
							</template>
						</reservations-list-slot>
					</div>
				</div>
			</div>

			<!-- Modals -->
			<modal title="Create a new Squad" small ok-text="Create" :callback="newTeam" :value="showTeamCreateModal" @closed="showTeamCreateModal=false">
				<div slot="modal-body" class="modal-body">

						<form id="TeamCreateForm" data-vv-scope="team-create">
							<div class="form-group" :class="{'has-error': errors.has('teamcallsign', 'team-create')}">
								<label for="createTeamCallsign" class="control-label">Squad Name</label>
								<input @keydown.enter.prevent="newTeam" type="text" class="form-control" id="createTeamCallsign" placeholder="" name="teamcallsign" v-model="newTeamCallSign" v-validate="'required'">
							</div>
							<div class="form-group" :class="{'has-error': errors.has('teamtype', 'team-create')}">
								<label for="" class="control-label">Type</label>
								<select class="form-control" v-model="newTeamType" name="teamtype" v-validate="'required'">
									<option :value="type.id" v-for="type in teamTypes" :key="type.id">{{ type.name | capitalize }}</option>
								</select>
							</div>
							<div class="form-group" :class="{'has-error': errors.has('teamgroup', 'team-create')}" v-if="isAdminRoute">
								<label>Travel Group</label>
								<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
										  v-model="newTeamGroup" :options="groupsOptions" label="name" name="teamgroup" v-validate="'required'"
										  placeholder="Assign Travel Group"></v-select>
								<!--<select class="hidden" v-model="newTeamGroup">
									<option :value="group" v-for="group in groupsOptions">{{ group.name||capitalize() }}</option>
								</select>-->
							</div>
						</form>

				</div>
			</modal>
			<modal title="Delete Squad" small ok-text="Delete" :callback="deleteTeam" :value="showTeamDeleteModal" @closed="showTeamDeleteModal=false">
				<div slot="modal-body" class="modal-body">
					<p v-if="selectedSquadObj">
						Are you sure you want to delete {{selectedSquadObj.callsign}} ?
					</p>
				</div>
			</modal>
			<modal title="Delete Group" small ok-text="Delete" :callback="deleteSquad" :value="showSquadDeleteModal" @closed="showSquadDeleteModal=false">
				<div slot="modal-body" class="modal-body">
					<p v-if="selectedSquadObj">
						Are you sure you want to delete {{selectedSquadObj.callsign}} ?
					</p>
				</div>
			</modal>
			<modal title="Remove Association" small ok-text="Remove" :callback="removeAssociation" :value="removeAssociationData.show" @closed="removeAssociationData.show=false">
				<div slot="modal-body" class="modal-body">
					<p v-if="removeAssociationData.object">
						Are you sure you want to disassociate {{removeAssociationData.object.name}} with this Team ?
					</p>
				</div>
			</modal>
			<modal title="Create a new Group" small ok-text="Create" :callback="newSquad" :value="showSquadCreateModal" @closed="showSquadCreateModal=false">
				<div slot="modal-body" class="modal-body">

						<form id="SquadCreateForm" data-vv-scope="group-create">
							<div class="form-group" :class="{'has-error': errors.has('squadcallsign', 'group-create')}">
								<label for="createSquadCallsign" class="control-label">Group Name</label>
								<input @keydown.enter.prevent="newSquad" type="text" class="form-control" id="createSquadCallsign" placeholder="" name="squadcallsign" v-model="newSquadCallsign" v-validate="'required'">
							</div>
						</form>

				</div>
			</modal>
			<modal title="Edit Group" small ok-text="Update" :callback="updateSquad" :value="showSquadUpdateModal" @closed="showSquadUpdateModal=false">
				<div slot="modal-body" class="modal-body">

						<form id="SquadEditForm" data-vv-scope="group-edit" v-if="selectedSquadObj">
							<div class="form-group" :class="{'has-error': errors.has('editsquadcallsign', 'group-edit')}">
								<label for="createSquadCallsign" class="control-label">Group Name</label>
								<input @keydown.enter.prevent="updateSquad" type="text" class="form-control" id="createSquadCallsign" :value.once="selectedSquadObj.callsign" placeholder="" name="editsquadcallsign" v-model="editSquadCallsign" v-validate="'required'">
							</div>
						</form>

				</div>
			</modal>
		</div>
	</div>
</template>
<style>

</style>
<script type="text/javascript">
	import _ from 'underscore';
	import $ from 'jquery';
	import vSelect from 'vue-select';
	import notes from '../notes.vue';
	import utilities from '../utilities.mixin';
	import reservationsListSlot from '../slots/reservations-list-slot.vue';
	import membersListSlot from '../slots/members-list-slot.vue';
    export default{
        name: 'team-manager',
	    components: {vSelect, notes, reservationsListSlot, membersListSlot},
	    mixins: [utilities],
	    props: {
            userId: {
                type: String,
                required: true
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
                teamTypes: [],
                teams: [],
                teamsPagination: { current_page: 1 },
                membersSearch: '',
                teamsSearch: '',
	            currentTeam: null,
	            currentSquadGroups: [],
                squadsPagination: { current_page: 1 },
	            group: null,
                startUp: true,
	            reservationsFacilitator: false,
                includeReservationsManaging: false,
                reservationsTrips: [],

                showTeamCreateModal: false,
                newTeamCallSign: '',
                newTeamType: '',
                newTeamCampaigns: [],
                newTeamGroup: null,

                showSquadCreateModal: false,
                newSquadCallsign: '',
                showSquadUpdateModal: false,
                editSquadCallsign: '',
                editSquadTeamId: undefined,
                selectedSquadObj: null,
                showTeamDeleteModal: false,
                showSquadDeleteModal: false,

	            // squad dis/association vars
                addAssociationData: {
                    type: null,
	                object: null
                },
                removeAssociationData: {
                    type: null,
                    object: null,
	                show: false
                },
	            // Filters vars
                showTeamsFilters: false,
                showMembersFilters: false,
                campaignsArr: [],
                groupsArr: [],
                groupObj: null,
	            leadershipRoles: [],
                campaignsOptions: [],
                groupsOptions: [],
                teamFilters: {
                    group: '',
	                campaign: null
                },

	            // members filters
	            membersFilters: {
                    groups: [],
		            gender: '',
		            status: '',
                    hasCompanions: '',
                    role: ''
                },

                TeamResource: this.$resource('teams{/team}{/path}{/pathId}'),
                TeamSquadResource: this.$resource('teams{/team}/squads{/squad}', { team: this.currentTeam ? this.currentTeam.id : null}),

	            editTeamMode: false,
                toggleHintsCollapse: true,
                storageName: 'TravelGroupSquads',
            }
        },
	    watch: {
            // watch filters obj
            'teamFilters': {
                handler(val, oldVal) {
                    this.teamsPagination.current_page = 1;
                    this.getTeams();
                },
                deep: true
            },
            'groupsArr'(val, oldVal) {
                this.reservationFilters.groups = _.pluck(val, 'id') || '';
                this.$root.$emit('Reservations::refresh');
            },
            'groupObj'(val, oldVal) {
                this.teamFilters.group = val ? val.id : '';
                this.$root.$emit('Reservations::refresh');
            },
            'teamsSearch'(val, oldVal) {
                this.teamsPagination.current_page = 1;
//                this.getTeams();
            },

		    'currentTeam'(val, oldVal) {
                if (val && val.id) {
                    this.getSquads();
                    this.updateConfig();
                } else {
                    window.localStorage.removeItem(this.storageName);
                }
            },

        },
	    computed: {
            currentSquadGroupsFiltered() {
                return this.currentSquadGroups.filter((group) => {
                    return _.filter(group.members, (member) => {
                        let search = this.membersSearch;
	                    return member.given_names.includes(search) || member.surname.includes(search);
                    });
                })
            },

            currentSquadGroupsOrdered() {
                return _.sortBy(this.currentSquadGroups, 'callsign');
            },

            currentSquadGroupsOrderedFiltered() {
                return _(this.currentSquadGroups).chain().sortBy('callsign').filter((group) => {
                    return _.filter(group.members, (member) => {
                        let search = this.membersSearch;
                        return member.given_names.includes(search) || member.surname.includes(search);
                    });
                }).value();
            },

            excludeReservationIds() {
                let IDs = [];
                if (this.currentTeam)
	                _.each(this.currentSquadGroups, (squad) => {
		                IDs = _.union(IDs, _.pluck(squad.members, 'id'));
	                });
                this.$root.$emit('Reservations::exclusions', _.uniq(IDs));
                return _.uniq(IDs);
            },
		    leaderSquad() {
                return this.currentSquadGroups.length ? _.findWhere(this.currentSquadGroups, { callsign: 'Squad Leaders'}) : null;
		    },
		    groupLeaders() {
                let leaders = [];
                _.each(this.currentSquadGroups, (group) => {
                    let leader = _.findWhere(group.members, { leader: true });
                    if (leader)
	                    leaders.push(leader);
                });
                return leaders;
		    },
            totalMembers() {
                let members = 0;
                _.each(this.currentSquadGroups, (squad) => {
	                members += squad.members.length;
                });
                return members;
            },
		    isLocked(){
                return !this.isAdminRoute && this.currentTeam.locked;
		    },
            unassociatedGroups() {
		        return _.difference(this.groupsOptions, this.currentTeam.groups.data);
		    }
	    },
        methods: {
            sortByLeader(members) {
                return members ? _.sortBy(members, 'leader') : [];
            },

            getRuleLabel(key){
                switch(key) {
	                case 'min_members':
	                    return 'Minimum Squad Members Required';
	                case 'max_members':
	                    return 'Maximum Squad Members Allowed';
	                case 'min_leaders':
	                    return 'Minimum Squad Leaders Required';
	                case 'max_leaders':
	                    return 'Maximum Squad Leaders Allowed';
	                case 'min_groups':
	                case 'min_squads':
	                    return 'Minimum Groups Required';
	                case 'max_squads':
	                case 'max_groups':
	                    return 'Maximum Groups Allowed';
	                case 'min_group_members':
	                case 'min_squad_members':
	                    return 'Minimum Members per Group';
	                case 'max_group_members':
	                case 'max_squad_members':
	                    return 'Maximum Members per Group';
	                case 'min_group_leaders':
	                case 'min_squad_leaders':
	                    return 'Minimum Leaders per Group';
	                case 'max_group_leaders':
	                case 'max_squad_leaders':
	                    return 'Maximum Leaders per Group';
                }
            },
            getReservationLink(reservation){
                return (this.isAdminRoute ? '/admin/reservations/' : '/dashboard/reservations/') + reservation.id;
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
            updateConfig(){
                localStorage[this.storageName] = JSON.stringify({
					currentTeam: this.currentTeam.id,
                });
            },
            updateRole(member, roleName) {
                this.getRoles().then((roles) => {
                    let role = _.findWhere(roles, { name: roleName});
                    this.$http.put('reservations/' + member.id, { desired_role: role.value },
                        { params: { include: 'trip.campaign,trip.group,fundraisers,costs.payments,user,companions' }
                        }).then((response) => {
                        this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' role updated!');
                        member.desired_role = response.data.data.desired_role;
                        return member = response.data.data;
                    }, (response) =>  {
                        console.log(response);
                        this.$root.$emit('showError', response.data.message);
                    });
                });
            },
	        openNewTeamModel(){
                let campaign = _.findWhere(this.campaignsOptions, { id: this.campaignId });
                if (campaign)
                    this.newTeamCampaigns = [campaign];
                let group = _.findWhere(this.groupsOptions, { id: this.groupId });
                if (group)
                    this.newTeamGroup = group;

                this.showTeamCreateModal = true;
            },
            isLeadership(member){
                return _.contains(this.leadershipRoles, member.desired_role.code);
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
                }
            },
            resetTeamFilter(){
                this.teamsSearch = null;
                this.groupObj = null;
                this.teamFilters = {
                    group: '',
                }
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('campaigns', { params: {search: search} }).then((response) => {
                    this.campaignsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.campaignsOptions;
                    }
                });
            },
	        getGroups(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('groups', { params: {search: search} }).then((response) => {
                    this.groupsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return response.data.data;
                    }
                });
            },
            canAssignToTeamLeaders(squad){
	            if (this.currentTeam)
	                return squad.callsign === 'Squad Leaders' && squad.members && squad.members.length < this.currentTeam.type.data.rules.max_leaders;
	            return false;
            },
            canAssignToSquadLeader(squad){
                if (this.currentTeam)
                    return !_.some(squad.members, (member) => {
		                return member.leader;
	                });
                return false;
            },
            canAssignToSquad(squad){
                if (this.currentTeam)
                    return  squad.members && squad.members.length < this.currentTeam.type.data.rules.max_group_members;
                return false
            },
            assignToSquad(reservation, squad, leader) {
                if (leader) {
                    if (!_.contains(this.leadershipRoles, reservation.desired_role.code)) {
                        this.$root.$emit('showInfo', 'The member is not in a leadership role');
                        return;
                    }
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                // Rules for team leader group
                if (squad.callsign === 'Squad Leaders') {
                    if (squad.members.length) {
                        //let test = false;
						/*test = _.some(squad.members, (member) => {
							return member.gender === reservation.gender;
                        });
	                    if (test){
                            this.$root.$emit('showError', 'Team Leaders members can not be of the same gender.');
                            return;
                        }*/
                    }
                }

                this.$http.post('squads/' + squad.id + '/members', {
                    id: reservation.id,
                    leader: leader || false,
                }, { params: { include: 'companions,trip.group'} }).then((response) => {
                    squad.members = response.data.data;
                    squad.members_count = squad.members.length;
                    this.currentTeam.members_count++;
                    this.$root.$emit('Reservations::refresh');
                });
            },
            assignMassToSquad(reservations, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                // Rules for team leader group
                if (squad.callsign === 'Squad Leaders') {
                    this.$root.$emit('showError', 'Please add leaders individually.');
                }

                this.$http.post('squads/' + squad.id + '/members', { members: reservations },
	                { params: { include: 'companions,trip.group'} }).then((response) => {
                    squad.members = response.data.data;
                    squad.members_count = squad.members.length
                    this.$root.$emit('Reservations::refresh');
                });
            },
            moveToSquad(reservation, oldSquad, newSquad, leader) {
                if (leader) {
                    if (!_.contains(this.leadershipRoles, reservation.desired_role.code)) {
                        this.$root.$emit('showInfo', 'The member is not in a leadership role');
                        return;
                    }
                }

                if (newSquad.squads_count >= this.currentTeam.type.data.rules.max_group_members) {
                    this.$root.$emit('showInfo', newSquad.name +' currently has the max number of members');
                    return;
                }
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                // Rules for team leader group
                if (newSquad.callsign === 'Squad Leaders') {
                    if (newSquad.members.length) {
                        let test = false;
						test = _.some(newSquad.members, (member) => {
							return member.gender === reservation.gender;
                        });
	                    if (test){
                            this.$root.$emit('showError', 'Squad Leaders members can not be of the same gender.');
                            return;
                        }
                    }
                }

                this.$http.put('squads/' + oldSquad.id + '/members/' + reservation.id, { team_squad_id: newSquad.id, leader: leader }, { params: { include: 'trip.group,companions'}})
	                .then((response) => {
                        console.log(response);
		                // update old squad
                        oldSquad.members = _.reject(oldSquad.members, (member) => {
                            return member.id === reservation.id;
                        });
                        oldSquad.members_count = oldSquad.members.length;
		                // update new Squad
                        newSquad.members.push(response.data.data);
                        newSquad.members_count = newSquad.members.length;
                    }, (response) =>  {
                        console.log(response);
	                    return response;
	                });

                /*this.removeFromSquad(reservation, oldSquad).then((response) => {
	                this.assignToSquad(reservation, newSquad, leader);
                });*/
            },
            moveToTeam(squad, newTeam) {
                if (newTeam.squads_count >= newTeam.type.data.rules.max_groups) {
                    this.$root.$emit('showInfo', newTeam.name +' currently has the max number of squads');
                    return;
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                let data = {
                    callsign: squad.callsign,
                    team_id: newTeam.id
                };

                let params = {
                    team: squad.team_id,
	                squad: squad.id,
                    include: 'companions,trip.group'
                };

                this.TeamSquadResource.put(params , data).then((response) => {
                    let groups =_.reject(this.currentSquadGroups, (sq) => {
                        return sq.id === squad.id;
                    });

                    this.currentSquadGroups = groups;
                    this.currentTeam.squads_count--;
                    newTeam.squads_count++;
                    newTeam.members_count += squad.members_count;
                    this.$root.$emit('showSuccess', squad.callsign + ' moved to ' + newTeam.callsign);
                });

            },
            removeFromSquad(memberObj, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                return this.$http.delete('squads/' + squad.id + '/members/' + memberObj.id)
	                .then((response) => {
	                    squad.members = _.reject(squad.members, (member) => {
		                    return member.id === memberObj.id;
                        });
                        squad.members_count = squad.members.length;
                        this.currentTeam.members_count--;
                    });
            },
            debouncedTeamsSearch: _.debounce(function() { this.getTeams(); }, 300),
            getTeams(){
                let params = {
                    include: 'type,groups',
                    page: this.teamsPagination.current_page,
	                search: this.teamsSearch,
                };

                if (this.isAdminRoute) {
					params.campaign = this.campaignId;
                    params.group = this.teamFilters.group || undefined;
                } else {
                    params.group = this.groupId;
                    params.campaign = this.campaignId;
                }

                return this.TeamResource.get(params).then((response) => {
	                this.teamsPagination = response.data.meta.pagination;
                    return this.teams = response.data.data;
                },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
	        getTeamTypes() {
                return this.$http.get('teams/types?campaign='+this.campaignId).then((response) => {
	                return this.teamTypes = response.data.data;
                }, (error) =>  {
                    console.log(error);
                    return error;
                });
	        },
            getSquads(){
                let params = {
                    team: this.currentTeam.id,
	                path: 'squads',
                    include: 'members.companions,members.trip.group',
                    page: this.squadsPagination.current_page,
                };

                return this.TeamResource.get(params).then((response) => {
                    _.each(response.data.data, (squad) => {
	                    squad.members = squad.members && squad.members.data ? squad.members.data : [];
                    });
                    return this.currentSquadGroups = response.data.data;
                });
            },
	        newTeam(){
                this.$validator.validateAll('team-create').then(result => {
                    if (result) {
                        let associations = [];
                        if (this.isAdminRoute) {
                            if (this.newTeamCampaigns.length)
                                _.each(this.newTeamCampaigns, (campaign) => {
                                    associations.push({
                                        type: 'campaigns',
                                        id: campaign.id
                                    });
                                });

                            if (this.newTeamGroup && this.newTeamGroup.id)
                                associations.push({
                                    type: 'groups',
                                    id: this.newTeamGroup.id
                                });
                        } else {
                            if (this.newTeamCampaigns.length)
                                _.each(this.newTeamCampaigns, (campaign) => {
                                    associations.push({
                                        type: 'campaigns',
                                        id: campaign.id
                                    });
                                });
                            else
                                associations.push({
                                    type: 'campaigns',
                                    id: this.campaignId
                                });
                            if (this.groupId) {
                                associations.push({
                                    type: 'groups',
                                    id: this.groupId
                                });
                            }
                        }
                        let data = {
                            type_id: this.newTeamType,
                            callsign: this.newTeamCallSign,
                            associations: associations
                        };
                        let params = {
                            include: 'type,groups',
                        };
                        this.TeamResource.post(params, data).then((response) => {
                            let team = response.data.data;

                            this.teams.push(team);
                            this.getTeams().then(() => {

                            });
                            this.currentSquadGroups = [];
                            this.currentTeam = team;
                            // Create default squads for current team
                            this.newSquad('Group #1');

                            if (team.type.data.rules.max_groups > 1)
                                this.newSquad('Squad Leaders');

                            this.showTeamCreateModal = false;
                            $('.nav-tabs a[href="#reservations"]').tab('show');
                        });
                    } else {
//                    this.$root.$emit('showError', '');
                    }
                });
	        },
	        newSquad(callsign){
	            if (this.currentSquadGroups.length >= this.currentTeam.type.data.rules.max_groups) {
	                this.$root.$emit('showError', 'This squad already has the max amount of ' + this.currentTeam.type.data.rules.max_groups + ' groups');
	                return;
	            }

                this.$validator.validateAll('group-create').then(result => {
                    if (result || _.isString(callsign)) {
                        return this.TeamSquadResource.post({team: this.currentTeam.id}, {callsign: _.isString(callsign) ? callsign : this.newSquadCallsign})
                            .then((response) => {
                                let squad = _.extend(response.data.data, {
                                    members: [],
                                    members_count: 0
                                });

                                this.currentSquadGroups.push(squad);
                                this.currentTeam.squads_count++;
                                this.showSquadCreateModal = false;
                                return squad;
                            });
                    }
                });
	        },
	        updateTeam(team){
	            this.TeamResource.put({team: team.id, include: 'type,groups'}, team).then((response) => {
					this.$root.$emit('showSuccess', team.callsign + ' Updated!');
                });
	        },
	        updateTeamSettings(ignoreMessage){
	            return this.TeamResource.put({ team: this.currentTeam.id, include: 'type,groups' }, { callsign: this.currentTeam.callsign, locked: this.currentTeam.locked }).then((response) => {
                    this.currentTeam = response.data.data;
                    if (!ignoreMessage)
						this.$root.$emit('showSuccess', this.currentTeam.callsign + ' Updated!');
					this.editTeamMode = false;
					return response.data.data;
                });
	        },
	        updateSquad(){
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                let data = {
                    callsign: this.editSquadCallsign,
                    team_id: this.editSquadTeamId
                };

                let params = {
                    team: this.selectedSquadObj.team_id,
	                squad: this.selectedSquadObj.id,
                    include: 'companions,trip.group'
                };

	            this.TeamSquadResource.put(params, data).then((response) => {
	                let squad = _.findWhere(this.currentSquadGroups, { id: this.selectedSquadObj.id});
	                squad.callsign = response.data.data.callsign;
                    this.$root.$emit('showSuccess', response.data.data.callsign + ' Updated!');
                    this.editSquadTeamId = undefined;
                    this.showSquadUpdateModal = false;
                    this.selectedSquadObj = null;
                });
	        },
	        deleteTeam(team){
                if (this.isLocked && team.id === this.currentTeam.id) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                this.TeamResource.delete({ team: team.id }).then((response) => {
                    this.$root.$emit('showInfo', team.callsign + ' Deleted!');
                    window.location.reload();
                });
	        },
	        deleteSquad(){
                if (this.currentSquadGroups.length <= this.currentTeam.type.data.rules.min_groups) {
                    this.$root.$emit('showError', 'This squad must have a minimum of ' + this.currentTeam.type.data.rules.min_groups + ' groups.');
                    return;
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                let squadCopy = this.selectedSquadObj;

                this.TeamSquadResource.delete({team: squadCopy.team_id, squad:squadCopy.id}).then((response) => {
                    this.$root.$emit('showInfo', squadCopy.callsign + ' Deleted!');
                    this.currentSquadGroups = _.reject(this.currentSquadGroups, (squad) => {
	                    return squad.id === squadCopy.id;
                    });
                    this.selectedSquadObj = null;
                    this.showSquadDeleteModal = false;
                    this.currentTeam.squads_count--;
                    this.currentTeam.members_count -= squadCopy.members_count;
                });

	        },
	        makeTeamCurrent(team){
	        	if (team) {
	        		this.currentTeam = team;
		            if (_.isFunction($.fn.tab))
	                    $('.nav-tabs a[href="#reservations"]').tab('show');
	        	}
            },
            updateCurrentTeamType() {
                if (this.currentTeam.type_id !== this.currentTeam.type.data.id) {
                    let type = _.findWhere(this.teamTypes, {id: this.currentTeam.type_id});
                    this.currentTeam.type.data = type;
                }
            },
            countMembers(team) {
	            let total = 0;
	            _.each(team.team_squads, function (group) {
		            total += group.members.length;
                });
	            return total;
            },
	        addAssociation(type, object) {
                return this.$http.post(type + '/' + object.id + '/teams', { ids: [this.currentTeam.id] }).then((response) => {
                    this.additionalAssociatedGroup = null;
                    this.updateTeamSettings(true).then(() => {
                        this.$root.$emit('showSuccess', this.currentTeam.callsign + ' is now associated with ' + object.name);
                    });
                }, (response) =>  {
                    console.log(response.data);
                    this.$root.$emit('showError', response.data.message);
                });
	        },
	        removeAssociation() {
		        switch(this.removeAssociationData.type) {
		            case 'groups':

		                break;
                    case 'regions':

                        break;
		            case 'campaigns':

		                break;
		        }

	            return this.$http.delete(this.removeAssociationData.type + '/' + this.removeAssociationData.object.id + '/teams/' + this.currentTeam.id).then((response) => {
                    this.updateTeamSettings(true).then(() => {
                        this.$root.$emit('showSuccess', this.currentTeam.callsign + ' is now unassociated with ' + this.removeAssociationData.object.name);
                        this.removeAssociationData.type = null;
                        this.removeAssociationData.object = null;
                        this.removeAssociationData.show = false;
                    });
                }, (response) =>  {
                    console.log(response.data);
                    this.$root.$emit('showError', response.data.message);
                });
	        },
	        confirmRemoveAssociation(type, object) {
                this.removeAssociationData.type = type;
                this.removeAssociationData.object = object;
                this.removeAssociationData.show = true;

	        },
            squadHasLeader(squad) {
	            return _.findWhere(squad.members, { leader: true });
            },
            demoteToMember(member, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
					leader: false
                }).then((response) => {
                    this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' demoted to a group member');
	                member.leader = false;
                    return member;
                });

            },
            promoteToLeader(member, squad) {
	            if (!_.contains(this.leadershipRoles, member.desired_role.code)) {
                    this.$root.$emit('showInfo', 'The member is not in a leadership role');
                    return;
	            }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This squad is currently locked');
                    return;
                }

                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
                    leader: true
                }).then((response) => {
                    this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' promoted to Group Leader');
                    member.leader = true;
                });
            },
            companionsPresentSquad(member, squad) {
	            let memberIds = _.filter(_.pluck(squad.members, 'id'), function (id) { return id !== member.id; });
	            let companionIds = _.pluck(member.companions.data, 'id');
				let presentIds = [];
				_.each(memberIds, (id) => {
					if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
	            return member.present_companions = companionIds.length - presentIds.length;
            },
            companionsPresentTeam(member) {
	            let memberIds = [];
                _.each(this.currentSquadGroups, (squad) => {
                    memberIds = _.union(memberIds, (_.pluck(squad.members, 'id')));
                });
                memberIds = _.filter(memberIds, (id) => { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, (id) => {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                return member.present_companions_team = companionIds.length - presentIds.length;
            },
            addCompanionsToSquad(member, squad) {
                let memberIds = _.filter(_.pluck(squad.members, 'id'), function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, (id) => {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                let notPresentIds = _.difference(companionIds, presentIds);

                // Check for limitations
	            // Available Space
	            let availableSpace = this.currentTeam.type.data.rules.max_group_members - squad.members.length;
	            if (availableSpace < companionIds.length) {
					this.$root.$emit('showError', 'There isn\'t enough space in this group for all ' + companionIds.length + ' companions.');
	                return;
	            }

	            // Leadership Position
	            if (squad.callsign === 'Squad Leaders') {
	                let testLeadership = _.some(member.companions.data, (companion) => {
		                return !_.contains(this.leadershipRoles, companion.desired_role.code)
                    });

	                if (testLeadership) {
	                    this.$root.$emit('showError', 'Some companions do not have a leadership role');
	                    return;
	                }
	            }

	            // Detect companions in other groups and remove them
	            if (member.present_companions_team) {
                    _.each(this.currentSquadGroups, (group) => {
                        let companionObj;
                        _.each(notPresentIds, (companionId) => {
	                        companionObj = _.findWhere(group.members, {id: companionId});
	                        if (companionObj) {
	                            this.removeFromSquad(companionObj, group);
	                        }
                        });

                    });
	            }

	            // package for mass assignment
	            let compArray = [];
                _.each(companionIds, (compId) => {
                    compArray.push({ id: compId, leader: false});
                });
                this.assignMassToSquad(compArray, squad)

            },
        },
        mounted(){
        	console.log(this.userId);
            let self = this;
            let promises = [];
            if (this.isAdminRoute) {
                //campaign already scoped
                this.newTeamCampaigns = [{id: this.campaignId}];
                promises.push(this.getGroups());
                promises.push(this.getTeams());
            } else {
                promises.push(this.$http.get('users/' + this.userId, {
                    params: {include: 'facilitating,managing.trips'}
                }).then((response) => {
                    let user = response.data.data;
                    let managing = [];
                    if (user.facilitating.data.length) {
                        this.reservationsFacilitator = true;
                        let facilitating = _.pluck(user.facilitating.data, 'id');
                        this.reservationsTrips = _.union(this.reservationsTrips, facilitating);
                    }
                    if (user.managing.data.length) {
                        _.each(user.managing.data, (group) => {
                            managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                        });
                        this.reservationsTrips = _.union(this.reservationsTrips, managing);
                    }
                    this.includeReservationsManaging = true;
                }));
            }
            promises.push(this.getTeamTypes());
            promises.push(this.getCampaigns());
            promises.push(this.getRoles());
            promises.push(this.$http.get('utilities/team-roles/leadership').then((response) => {
                let roles = [];
	            _.each(response.data.roles, function (role, code) {
		            roles.push(code);
                });
	            this.leadershipRoles = roles;
            }, (error) =>  {
                console.log(error);
                return error;
            }));

            Promise.all(promises).then((values) => {
                this.startUp = false;

                // load view state
                if (localStorage[this.storageName]) {
                    let config = JSON.parse(localStorage[this.storageName]);
                    if (this.isAdminRoute && location.search.length > 1) {
                        let squad;
                        let searchObj = this.$root.convertSearchToObject();
                        // https://stackoverflow.com/questions/8648892/convert-url-parameters-to-a-javascript-object
                        if (searchObj.hasOwnProperty('squad')) {
                            squad = _.findWhere(this.teams, {id: searchObj.squad});
                            if (squad)
                                this.makeTeamCurrent(squad);
                        }
                    } else {
                        if (config.currentTeam) {
                            this.makeTeamCurrent(_.findWhere(this.teams, { id: config.currentTeam}));
                        }
                    }
                }
            });

            this.$root.$on('campaign-scope', (val) =>  {
                if(val) {
	                this.campaignId = val ? val.id : '';
                    this.newTeamCampaigns = [{id: val.id}];
                    this.$root.$emit('update-title', val ? val.name : '');
                    this.currentTeam = null;
                    this.getTeams();
                    $('.nav-tabs a[href="#teams"]').tab('show');
                }
                this.$root.$emit('Reservations::refresh');
            });

            $('#collapseHints')
	            .on('show.bs.collapse', () =>  {
	                self.toggleHintsCollapse = true;
	            })
	            .on('hide.bs.collapse', () =>  {
                    self.toggleHintsCollapse = false;
                });
        }
    }
</script>