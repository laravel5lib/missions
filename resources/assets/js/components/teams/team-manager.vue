<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<aside :show.sync="showReservationsFilters" placement="left" header="Reservation Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<!-- <div class="form-group">
					<label>Tags</label>
					<input type="text" class="form-control input-sm" style="width:100%" v-model="tagsString"
						   :debounce="250" placeholder="Tag, tag2, tag3...">
				</div> -->
				<div class="form-group">
					<label>Role</label>
					<v-select @keydown.enter.prevent="" class="form-control" id="roleFilter" :debounce="250" :on-search="getRoles"
					          :value.sync="roleObj" :options="rolesOptions" label="name"
					          placeholder="Filter Roles"></v-select>
				</div>

				<div class="form-group" v-if="isAdminRoute">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
					          :value.sync="groupsArr" :options="groupsOptions" label="name"
					          placeholder="Filter Groups"></v-select>
				</div>

				<div class="form-group">
					<label>Gender</label>
					<select class="form-control input-sm" v-model="reservationFilters.gender" style="width:100%;">
						<option value="">Any Genders</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select>
				</div>

				<div class="form-group">
					<label>Marital Status</label>
					<select class="form-control input-sm" v-model="reservationFilters.status" style="width:100%;">
						<option value="">Any Status</option>
						<option value="single">Single</option>
						<option value="married">Married</option>
					</select>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12">
							<label>Age Range</label>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Age Min</span>
								<input type="number" class="form-control" number v-model="reservationsAgeMin" min="0">
							</div>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Max</span>
								<input type="number" class="form-control" number v-model="reservationsAgeMax" max="120">
							</div>
						</div>
					</div>
				</div>

				<div class="form-group" v-if="isAdminRoute">
					<label>Travel Companions</label>
					<div>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions1" v-model="reservationFilters.hasCompanions" value=""> Any
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions2" v-model="reservationFilters.hasCompanions" value="yes"> Yes
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions3" v-model="reservationFilters.hasCompanions" value="no"> No
						</label>
					</div>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>
		<div class="col-md-7">
			<h4 v-if="currentTeam" class="">{{ currentTeam.callsign }} <span v-if="isLocked" class="label label-info"><i class="fa fa-lock"></i> Locked</span></h4>
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
								<input type="text" class="form-control" v-model="membersSearch" debounce="300" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-lg-4 col-md-4 col-sm-5 col-xs-12">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showMembersFilters=!showMembersFilters">
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

								<!-- Team Leaders Group -->
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
																						<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(member)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Team Leaders</a></li>
																						<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																						<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(member)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																					</template>
																				</template>
																				<li :class="{'disabled': isLocked}" role="separator" class="divider"></li>
																				<li :class="{'disabled': isLocked}" v-if="member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
																				<li :class="{'disabled': isLocked}" v-if="!member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
																				<li :class="{'disabled': isLocked}"><a @click="removeFromSquad(member, squad)">Remove</a></li>
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
																		<label>Travel Group</label>
																		<p class="small">{{member.trip.data.group.data.name}}</p>
																	</div><!-- end col -->
																</div><!-- end row -->
															</div>
														</div>
														<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions.data.length && companionsPresentSquad(member, squad)">
															<i class=" fa fa-info-circle"></i> I have {{member.present_companions}} companions not in this group. And {{companionsPresentTeam(member)}} not on this team.
															<button type="button" class="btn btn-xs btn-default-hollow" @click="addCompanionsToSquad(member, squad)">Add Companions</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</template>
								</template>
								<p class=" text-right" v-if="currentSquads.length < currentTeam.type.data.rules.max_squads">
									<button :disabled="isLocked" class="btn btn-xs btn-primary" @click="showSquadCreateModal = true">Add Group</button>
								</p>
								<hr class="divider sm">
								<!-- Other Groups -->
								<template v-for="(tgIndex, squad) in currentSquads | filterBy membersSearch">
									<template v-if="squad.callsign !== 'Team Leaders'">
										<div class="panel panel-default">
											<div class="panel-heading">
												<div class="row">
													<h5 class="col-xs-8" v-text="squad.callsign"></h5>
													<div class="col-xs-4 text-right">
														<dropdown type="default">
															<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																<span class="fa fa-ellipsis-h"></span>
															</button>
															<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																<li :class="{'disabled': isLocked}"><a @click="showSquadUpdateModal = true,selectedSquadObj = squad">Edit</a></li>
																<template v-for="subTeam in teams">
																	<li role="separator" class="divider" v-if="$index === 0 && teams.length > 1"></li>
																	<li v-if="subTeam.id !== squad.team_id" :class="{'disabled': isLocked}"><a @click="moveToTeam(squad, subTeam)">Move group to {{subTeam.callsign}}</a></li>
																</template>
																<li role="separator" class="divider"></li>
																<li :class="{'disabled': isLocked}"><a @click="showSquadDeleteModal = true,selectedSquadObj = squad">Delete</a></li>
															</ul>
														</dropdown>
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
										</div>
									</template>
								</template>
							</template>
							<template v-else>
								<hr class="divider inv">
								<p class="text-center text-muted"><em>Select a Team to get started or create a new one!</em></p>
								<hr class="divider inv">
								<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewTeamModel">Create A Team</a></p>
							</template>
						</div>
					</div>

				</div>
				<div role="tabpanel" class="tab-pane" id="teamdetails">
					<form class="form-horizontal" v-if="currentTeam">
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-8">
								<input v-if="isAdminRoute" type="text" class="form-control"  placeholder="Name" v-model="currentTeam.callsign">
								<p v-else v-text="currentTeam.callsign"></p>
							</div>

						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Locked</label>
							<div class="col-sm-8">
								<select v-if="isAdminRoute" class="form-control" v-model="currentTeam.locked">
									<option :value="true">Yes</option>
									<option :value="false">No</option>
								</select>
								<p v-else v-text="currentTeam.locked ? 'Yes' : 'No'"></p>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Type</label>
							<div class="col-sm-8">
								<select v-if="isAdminRoute" class="form-control" v-model="currentTeam.type_id" @change="updateCurrentTeamType">
									<option :value="type.id" v-for="type in teamTypes">{{type.name | capitalize}}</option>
								</select>
								<p v-else v-text="currentTeam.type.data.name | capitalize"></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Rules</label>
							<div class="col-sm-8">
								<ul class="list-group" v-if="currentSquads.length">
									<li class="list-group-item" v-for="(key, value) in currentTeam.type.data.rules">
										<span class="badge" v-text="value"></span>
										{{ key | underscoreToSpace | capitalize }}
									</li>
								</ul>
							</div>
						</div>

						<!--<div class="form-group">
							<label for="" class="col-sm-4 control-label">Team Leader</label>
							<div v-if="isAdminRoute" class="col-sm-4"></div>
							<div class="col-sm-4">
								<input v-if="isAdminRoute" type="number" number class="form-control" v-model="currentTeam.squad_leaders" min="2" max="5" value="2">
								<p v-else v-text="currentTeam.squad_leaders"></p>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Max Members per Group</label>
							<div v-if="isAdminRoute" class="col-sm-4"></div>
							<div class="col-sm-4">
								<input v-if="isAdminRoute" type="number" number class="form-control" v-model="currentTeam.max_group_members" min="2" max="5" value="5">
								<p v-else v-text="currentTeam.max_group_members"></p>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Max Team Members</label>
							<div v-if="isAdminRoute" class="col-sm-4"></div>
							<div class="col-sm-4">
								<input v-if="isAdminRoute" type="number" number class="form-control" v-model="currentTeam.max_members" min="2" max="25" value="25">
								<p v-else v-text="currentTeam.max_members"></p>
							</div>
						</div>-->

						<div class="form-group">
							<div class="col-sm-4"></div>
							<div class="col-sm-8">
								<button type="button" v-if="isAdminRoute" class="btn btn-primary btn-sm" @click="updateTeamSettings">Update Settings</button>
								<button type="button" v-if="isAdminRoute" class="btn btn-default btn-sm" @click="deleteTeam(currentTeam)">Delete Team</button>
							</div>
						</div>
						<hr class="divider sm">

						<ul class="list-group" v-if="currentSquads.length">
							<li class="list-group-item" v-if="leaderSquad.members">
								<span class="badge" v-text="leaderSquad.members.length"></span>
								Team Leaders
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
							<button class="btn btn-primary btn-sm" @click="openNewTeamModel">Create A Team</button>
							<hr class="divider lg">
						</div>
						<div class="col-xs-12">
							<template v-if="teams.length">
								<ul class="list-group">

									<a class="list-group-item" :class="{'active': currentTeam === team}" v-for="team in teams" @click="makeTeamCurrent(team)">
										<div class="row">
											<div class="col-xs-6">
												{{ team.callsign | capitalize }}
												<span class="label label-info" v-text="team.type.data.name | capitalize"></span>
												<span v-if="team.locked" class="label label-danger"><i class="fa fa-lock"></i> Locked</span>
											</div>
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
								<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewTeamModel">Create A Team</a></p>
							</template>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="reservations">
					<!-- Search and Filter -->
					<form class="form-inline row">
						<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="reservationsSearch" debounce="300" placeholder="Search">
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
								<span v-if="isAdminRoute" style="margin-right:2px;" class="label label-default" v-show="reservationFilters.groups.length" @click="reservationFilters.groups = []" >
									Travel Group
									<i class="fa fa-close"></i>
								</span>
								<span v-if="isAdminRoute" style="margin-right:2px;" class="label label-default" v-show="reservationFilters.hasCompanions !== null" @click="reservationFilters.hasCompanions = null" >
									Companions
									<i class="fa fa-close"></i>
								</span>
								<span v-if="isAdminRoute" style="margin-right:2px;" class="label label-default" v-show="reservationFilters.role !== ''" @click="reservationFilters.role = ''" >
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
								<span style="margin-right:2px;" class="label label-default" v-show="reservationsAgeMin != 0" @click="reservationsAgeMin = 0" >
									Min. Age
									<i class="fa fa-close"></i>
								</span>
								<span style="margin-right:2px;" class="label label-default" v-show="reservationsAgeMax != 120" @click="reservationsAgeMax = 120" >
									Max. Age
									<i class="fa fa-close"></i>
								</span>
							</div>
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
																	<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(squad) && isLeadership(reservation)"><a @click="assignToSquad(reservation, squad, false)">Team Leader</a></li>
																</template>
																<template v-else>
																	<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(squad) && isLeadership(reservation)"><a @click="assignToSquad(reservation, squad, true)" v-text="squad.callsign + ' Leader'"></a></li>
																	<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(squad)"><a @click="assignToSquad(reservation, squad, false)" v-text="squad.callsign"></a></li>
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
													<label>Travel Group</label>
													<p class="small">{{reservation.trip.data.group.data.name}}</p>
												</div><!-- end col -->
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

		<!-- Modals -->
		<modal title="Create a new Team" small ok-text="Create" :callback="newTeam" :show.sync="showTeamCreateModal">
			<div slot="modal-body" class="modal-body">
				<validator name="TeamCreate">
					<form id="TeamCreateForm">
						<div class="form-group" :class="{'has-error': $TeamCreate.teamcallsign.invalid}">
							<label for="createTeamCallsign" class="control-label">Team Name</label>
							<input @keydown.enter.prevent="newTeam" type="text" class="form-control" id="createTeamCallsign" placeholder="" v-validate:teamcallsign="['required']" v-model="newTeamCallSign">
						</div>
						<div class="form-group" :class="{'has-error': $TeamCreate.teamtype.invalid}">
							<label for="" class="control-label">Type</label>
							<select class="form-control" v-model="newTeamType" v-validate:teamtype="['required']">
								<option :value="type.id" v-for="type in teamTypes">{{type.name | capitalize}}</option>
							</select>
						</div>
						<!--<div class="form-group" v-if="isAdminRoute">
							<label>Campaigns</label>
							<v-select @keydown.enter.prevent="" multiple class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
							          :value.sync="newTeamCampaigns" :options="campaignsOptions" label="name"
							          placeholder="Filter by Campaign"></v-select>
						</div>
						<div class="form-group" v-if="isAdminRoute">
							<label>Travel Group</label>
							<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
							          :value.sync="newTeamGroup" :options="groupsOptions" label="name"
							          placeholder="Filter Groups"></v-select>
						</div>-->
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
							<input @keydown.enter.prevent="newSquad" type="text" class="form-control" id="createSquadCallsign" placeholder="" v-validate:squadcallsign="['required']" v-model="newSquadCallsign">
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
							<input @keydown.enter.prevent="updateSquad" type="text" class="form-control" id="createSquadCallsign" :value.once="selectedSquadObj.callsign" placeholder="" v-validate:editsquadcallsign="['required']" v-model="editSquadCallsign">
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
	import _ from 'underscore';
	import vSelect from 'vue-select';
    export default{
        name: 'team-manager',
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
                teamTypes: [],
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
                newTeamCallSign: '',
                newTeamType: '',
                newTeamCampaigns: [],
                newTeamGroup: [],

                showSquadCreateModal: false,
                newSquadCallsign: '',
                showSquadUpdateModal: false,
                editSquadCallsign: '',
                editSquadTeamId: undefined,
                selectedSquadObj: null,
                showTeamDeleteModal: false,
                showSquadDeleteModal: false,

                lastReservationRequest: null,

	            // Filters vars
                showReservationsFilters: false,
                showMembersFilters: false,
                campaignsArr: [],
                groupsArr: [],
                roleObj: null,
                rolesOptions: [],
	            leadershipRoles: [],
                campaignsOptions: [],
                groupsOptions: [],
                // reservations filters
	            reservationFilters: {
                    groups: [],
		            gender: '',
		            status: '',
                    hasCompanions: '',
                    role: ''
                },

                reservationsAgeMin: 0,
                reservationsAgeMax: 120,

                TeamResource: this.$resource('teams{/team}{/path}{/pathId}'),
                TeamSquadResource: this.$resource('teams{/team}/squads/{/squad}', { team: this.currentTeam ? this.currentTeam.id : null}),
            }
        },
	    watch: {
            // watch filters obj
            'reservationFilters': {
                handler: function (val) {
                    this.reservationsPagination.current_page = 1;
                    this.searchReservations();
                },
                deep: true
            },
            'groupsArr': function (val) {
                this.reservationFilters.groups = _.pluck(val, 'id') || '';
//				this.searchReservations();
            },
            'roleObj': function (val) {
                this.reservationFilters.role = val ? val.value : '';
//				this.searchReservations();
            },
            'reservationsAgeMin': function (val) {
                this.searchReservations();
            },
            'reservationsAgeMax': function (val) {
                this.searchReservations();
            },
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
            },

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
		    isLocked(){
                return !this.isAdminRoute && this.currentTeam.locked;
		    }
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
                this.reservationsAgeMin = 0;
                this.reservationsAgeMax = 120;
                this.groupsArr = [];
                this.reservationFilters = {
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
	                role: ''
                }


            },
            getRoles(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('utilities/team-roles').then(function (response) {
                    _.each(response.body.roles, function (name, key) {
                        this.rolesOptions.push({ value: key, name: name});
                    }.bind(this));
                    if (loading) {
                        loading(false);
                    } else {
                        return this.rolesOptions;
                    }
                });
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('campaigns', { params: {search: search} }).then(function (response) {
                    this.campaignsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.campaignsOptions;
                    }
                });
            },
	        getGroups(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('groups', { params: {search: search} }).then(function (response) {
                    this.groupsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return response.body.data;
                    }
                });
            },
            canAssignToTeamLeaders(squad){
	            return squad.callsign === 'Team Leaders' && squad.members && squad.members.length < this.currentTeam.type.data.rules.max_leaders;
            },
            canAssignToSquadLeader(squad){
                return !_.some(squad.members, function (member) {
	                return member.leader;
                });
            },
            canAssignToSquad(squad){
	            return  squad.members && squad.members.length < this.currentTeam.type.data.rules.max_squad_members;
            },
            assignToSquad(reservation, squad, leader) {
                if (leader) {
                    if (!_.contains(this.leadershipRoles, reservation.desired_role.code)) {
                        this.$root.$emit('showInfo', 'The member is not in a leadership role');
                        return;
                    }
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                // Rules for team leader group
                if (squad.callsign === 'Team Leaders') {
                    if (squad.members.length) {
                        //let test = false;
						/*test = _.some(squad.members, function (member) {
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
                }, { params: { include: 'companions,trip.group'} }).then(function (response) {
                    squad.members = response.body.data;
                    squad.members_count = squad.members.length
                });
            },
            assignMassToSquad(reservations, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                // Rules for team leader group
                if (squad.callsign === 'Team Leaders') {
                    this.$root.$emit('showError', 'Please add leaders individually.');
                }

                this.$http.post('squads/' + squad.id + '/members', { members: reservations },
	                { params: { include: 'companions,trip.group'} }).then(function (response) {
                    squad.members = response.body.data;
                    squad.members_count = squad.members.length
                });
            },
            moveToSquad(reservation, oldSquad, newSquad, leader) {
                if (leader) {
                    if (!_.contains(this.leadershipRoles, reservation.desired_role.code)) {
                        this.$root.$emit('showInfo', 'The member is not in a leadership role');
                        return;
                    }
                }

                if (newSquad.squads_count >= this.currentTeam.type.data.rules.max_squad_members) {
                    this.$root.$emit('showInfo', newSquad.name +' currently has the max number of members');
                    return;
                }
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

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
            moveToTeam(squad, newTeam) {
                if (newTeam.squads_count >= newTeam.type.data.rules.max_squads) {
                    this.$root.$emit('showInfo', newTeam.name +' currently has the max number of squads');
                    return;
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
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

                this.TeamSquadResource.update(params , data).then(function (response) {
                    this.currentSquads = _.reject(this.currentSquads, function (sq) {
                        return sq.id === squad.id;
                    });
                    this.currentTeam.squads_count--;
                    newTeam.squads_count++;
                    this.$root.$emit('showSuccess', squad.callsign + ' moved to ' + newTeam.callsign);
                });

            },
            removeFromSquad(memberObj, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                return this.$http.delete('squads/' + squad.id + '/members/' + memberObj.id)
	                .then(function (response) {
	                    squad.members = _.reject(squad.members, function (member) {
		                    return member.id === memberObj.id;
                        });
                        squad.members_count = squad.members.length
                    });
            },
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

                $.extend(params, this.reservationFilters);
                $.extend(params, {
                    age: [this.ageMin, this.ageMax]
                });

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

                return this.TeamResource.get(params).then(function (response) {
	                this.teamsPagination = response.body.meta.pagination;
                    return this.teams = response.body.data;
                },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
	        getTeamTypes() {
                return this.$http.get('teams/types').then(function (response) {
	                return this.teamTypes = response.body.data;
                }, function (error) {
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

                return this.TeamResource.get(params).then(function (response) {
                    _.each(response.body.data, function (squad) {
	                    squad.members = squad.members && squad.members.data ? squad.members.data : [];
                    });
                    return this.currentSquads = response.body.data;
                });
            },
	        newTeam(){
                if (this.$TeamCreate.valid) {
                    let associations = [];
                    if (this.isAdminRoute) {
                        if (this.newTeamCampaigns.length)
	                        _.each(this.newTeamCampaigns, function (campaign) {
	                            associations.push({
	                                type: 'campaigns',
	                                id: campaign.id
	                            });
                            });

                        if (this.newTeamGroup)    
                            associations.push({
                                type: 'groups',
                                id: this.newTeamGroup.id
                            });
                    } else {
                        if (this.newTeamCampaigns.length)
                            _.each(this.newTeamCampaigns, function (campaign) {
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
                        associations.push({
                            type: 'groups',
                            id: this.groupId
                        });
                    }
                    let data = {
                        type_id: this.newTeamType,
                        callsign: this.newTeamCallSign,
                        associations: associations
                    };
                    let params = {
                        include: 'type',
                    };
                    this.TeamResource.save(data, params).then(function (response) {
                        let team = response.body.data;

                        this.teams.push(team);
                        this.getTeams().then(function () {

                        });
                        this.currentSquads = [];
                        this.currentTeam = team;
                        // Create default squads for current team
                        this.newSquad('Group #1');

                        if (team.type.data.rules.max_squads > 1)
                            this.newSquad('Team Leaders');

                        this.showTeamCreateModal = false;
                        $('.nav-tabs a[href="#reservations"]').tab('show');
                    });
                } else {
//                    this.$root.$emit('showError', '');
                }
	        },
	        newSquad(callsign){
	            if (this.currentSquads.length >= this.currentTeam.type.data.rules.max_squads) {
	                this.$root.$emit('showError', 'This team already has the max amount of ' + this.currentTeam.type.data.rules.max_squads + ' groups');
	                return;
	            }

                if (this.$SquadCreate.valid || _.isString(callsign)) {
                    return this.TeamSquadResource.save({team: this.currentTeam.id}, {callsign: _.isString(callsign) ? callsign : this.newSquadCallsign})
                        .then(function (response) {
							let squad = _.extend(response.body.data, {
							    members: [],
                                members_count: 0
                            });

							if (this.currentSquads.length) {
                                this.currentSquads.splice(1, 0, squad);
							} else {
                                this.currentSquads.push(squad);
							}
                            this.currentTeam.squads_count++;

                            this.showSquadCreateModal = false;
                            return squad;
                        });
                }
	        },
	        updateTeam(team){
	            this.TeamResource.update({team: team.id}, team).then(function (response) {
					this.$root.$emit('showSuccess', team.callsign + ' Updated!');
                });
	        },
	        updateTeamSettings(){
	            this.TeamResource.update({ team: this.currentTeam.id}, { callsign: this.currentTeam.callsign }).then(function (response) {
                    this.currentTeam = response.body.data;
					this.$root.$emit('showSuccess', this.currentTeam.callsign + ' Updated!');
                });
	        },
	        updateSquad(){
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
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

	            this.TeamSquadResource.update(params, data).then(function (response) {
	                let squad = _.findWhere(this.currentSquads, { id: this.selectedSquadObj.id});
	                squad.callsign = response.body.data.callsign;
                    this.$root.$emit('showSuccess', response.body.data.callsign + ' Updated!');
                    this.editSquadTeamId = undefined;
                    this.showSquadUpdateModal = false;
                    this.selectedSquadObj = null;
                });
	        },
	        deleteTeam(team){
                if (this.isLocked && team.id === this.currentTeam.id) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                this.TeamResource.delete({ team: team.id }).then(function (response) {
                    this.$root.$emit('showInfo', team.callsign + ' Deleted!');
                });
	        },
	        deleteSquad(){
                if (this.currentSquads.length <= this.currentTeam.type.data.rules.min_squads) {
                    this.$root.$emit('showError', 'This team must have a minimum of ' + this.currentTeam.type.data.rules.min_squads + ' groups.');
                    return;
                }

                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                let squadCopy = this.selectedSquadObj;

                this.TeamSquadResource.delete({team: squadCopy.team_id, squad:squadCopy.id}).then(function (response) {
                    this.$root.$emit('showInfo', squadCopy.callsign + ' Deleted!');
                    this.currentSquads = _.reject(this.currentSquads, function (squad) {
	                    return squad.id === squadCopy.id;
                    });
                    this.selectedSquadObj = null;
                    this.showSquadDeleteModal = false;
                    this.currentTeam.squads_count--;
                });
	        },
	        makeTeamCurrent(team){
	            this.currentTeam = team;
                $('.nav-tabs a[href="#reservations"]').tab('show');
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
            squadHasLeader(squad) {
	            return _.findWhere(squad.members, { leader: true });
            },
            demoteToMember(member, squad) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
					leader: false
                }).then(function (response) {
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
                    this.$root.$emit('showInfo', 'This team is currently locked');
                    return;
                }

                this.$http.put('squads/' + squad.id + '/members/' + member.id, {
                    leader: true
                }).then(function (response) {
                    this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' promoted to Group Leader');
                    member.leader = true;
                });
            },
            companionsPresentSquad(member, squad) {
	            let memberIds = _.filter(_.pluck(squad.members, 'id'), function (id) { return id !== member.id; });
	            let companionIds = _.pluck(member.companions.data, 'id');
				let presentIds = [];
				_.each(memberIds, function (id) {
					if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
	            return member.present_companions = companionIds.length - presentIds.length;
            },
            companionsPresentTeam(member) {
	            let memberIds = [];
                _.each(this.currentSquads, function (squad) {
                    memberIds = _.union(memberIds, (_.pluck(squad.members, 'id')));
                });
                memberIds = _.filter(memberIds, function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, function (id) {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                return member.present_companions_team = companionIds.length - presentIds.length;
            },
            addCompanionsToSquad(member, squad) {
                let memberIds = _.filter(_.pluck(squad.members, 'id'), function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, function (id) {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });

                // Check for limitations
	            let availableSpace = this.currentTeam.type.data.rules.max_group_members - squad.members.length;

	            if (availableSpace < companionIds.length) {
					this.$root.$emit('showError', 'There isn\'t enough space in this group for all ' + companionIds.length + ' companions.');
	                return;
	            }

	            // package for mass assignment
	            let compArray = [];
                _.each(companionIds, function (compId) {
                    compArray.push({ id: compId, leader: false});
                }.bind(this));
                this.assignMassToSquad(compArray, squad)

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
                }));
            }
            promises.push(this.getTeamTypes());
            promises.push(this.getTeams());
            promises.push(this.getCampaigns());
            promises.push(this.getRoles());
            promises.push(this.$http.get('utilities/team-roles/leadership').then(function (response) {
                let roles = [];
	            _.each(response.body.roles, function (role, code) {
		            roles.push(code);
                });
	            this.leadershipRoles = roles;
            }));

            Promise.all(promises).then(function (values) {
                this.startUp = false;
                this.searchReservations();
            }.bind(this));

            this.$root.$on('campaign-scope', function (val) {
                if(val) {
                    this.campaignId = val ? val.id : '';
                    this.newTeamCampaigns = [{id: val.id}];
                    this.$root.$emit('update-title', val ? val.name : '');
                }
                this.searchReservations();
            }.bind(this));
        }
    }
</script>