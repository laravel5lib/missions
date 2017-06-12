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
			<spinner v-ref:spinner size="sm" text="Loading"></spinner>
			<aside :show.sync="showTeamsFilters" placement="left" header="Team Filters" :width="375">
				<hr class="divider inv sm">
				<form class="col-sm-12">

					<div class="form-group" v-if="isAdminRoute">
						<label>Travel Group</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
						          :value.sync="groupObj" :options="groupsOptions" label="name"
						          placeholder="Filter Group"></v-select>
					</div>

					<hr class="divider inv sm">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="resetTeamFilter()"><i class="fa fa-times"></i> Reset Team Filters</button>
				</form>
			</aside>
			<aside :show.sync="showReservationsFilters" placement="left" header="Reservation Filters" :width="375">
				<hr class="divider inv sm">
				<form class="col-sm-12">

					<div class="form-group">
						<label>Trip Type</label>
						<select  class="form-control input-sm" v-model="reservationFilters.type">
							<option value="">Any Type</option>
							<option value="ministry">Ministry</option>
							<option value="family">Family</option>
							<option value="international">International</option>
							<option value="media">Media</option>
							<option value="medical">Medical</option>
							<option value="leader">Leader</option>
						</select>
					</div>

					<div class="form-group">
						<label>Role</label>
						<v-select @keydown.enter.prevent="" class="form-control" id="roleFilter" :debounce="250" :on-search="getRoles"
						          :value.sync="roleObj" :options="UTILITIES.roles" label="name"
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

					<div class="form-group">
						<label>Arrival Designation</label>
						<select  class="form-control input-sm" v-model="reservationFilters.designation">
							<option value="">Any</option>
							<option value="eastern">Eastern</option>
							<option value="western">Western</option>
							<option value="international">International</option>
							<option value="none">None</option>
						</select>
					</div>


					<div class="form-group">
						<label>Travel Companions</label>
						<div>
							<label class="radio-inline">
								<input type="radio" name="companions" id="companions1" v-model="reservationFilters.hasCompanions" :value=""> Any
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
			<aside :show.sync="showMembersFilters" placement="left" header="Members Filters" :width="375">
				<hr class="divider inv sm">
				<form class="col-sm-12">

					<hr class="divider inv sm">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
				</form>
			</aside>

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
									<template v-for="(tgIndex, squad) in currentSquadGroups | filterBy membersSearch">
										<template v-if="squad.callsign === 'Squad Leaders'">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h3 class="panel-title">Squad Leaders</h3>
												</div>
												<div class="panel-body">
													<div class="alert alert-success" v-if="squad.members_count >= currentTeam.type.data.rules.max_leaders">
														Complete! You've filled all the positions.
													</div>
													<div class="panel-group" id="SquadLeaderAccordion" role="tablist" aria-multiselectable="true">
														<div class="panel panel-default" v-for="member in squad.members | orderBy '-leader'">
															<div class="panel-heading" role="tab" id="headingOne">
																<h4 class="panel-title">
																	<div class="row">
																		<div class="col-xs-9">
																			<div class="media">
																				<div class="media-left" style="padding-right:0;">
																					<a :href="getReservationLink(member)" target="_blank">
																						<img :src="member.avatar" class="img-circle img-xs av-left" style="margin-right: 10px"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member && member.leader">GL</span>
																					</a>
																				</div>
																				<div class="media-body" style="vertical-align:middle;">
																					<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
																					<i :class="getGenderStatusIcon(member)"></i>
																					<a :href="getReservationLink(member)" target="_blank">{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}</a></h6>
																					<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }} <span class="text-muted">&middot; {{ member.travel_group}}</span></p>
																				</div><!-- end media-body -->
																			</div><!-- end media -->
																		</div>
																		<div class="col-xs-3 text-right action-buttons">
																			<dropdown type="default">
																				<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																					<span class="fa fa-ellipsis-h"></span>
																				</button>
																				<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																					<template v-for="subSquad in currentSquadGroups | orderBy 'callsign'">
																						<template v-if="subSquad.callsign !== 'Squad Leaders'">
																							<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(subSquad)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Squad Leaders</a></li>
																							<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad, subSquad)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																							<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																						</template>
																					</template>
																					<li :class="{'disabled': isLocked}" role="separator" class="divider"></li>
																					<li :class="{'disabled': isLocked}" v-if="member && member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
																					<li :class="{'disabled': isLocked}" v-if="member && !member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
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
																	<div class="col-sm-6">
																		<label>Companions</label>
																		<ul class="list-unstyled" v-if="member.companions.data.length">
																			<li v-for="companion in member.companions.data">
																				<i :class="getGenderStatusIcon(companion)"></i> 
																				{{ companion.surname | capitalize }}, {{ companion.given_names | capitalize }} 
																				<span class="text-muted">({{ companion.relationship | capitalize }})</span>
																			</li>
																		</ul>
																		<p class="small" v-else>None</p>
																	</div>
																	<div class="col-sm-6">
																		<label>Designation</label>
																		<p class="small">{{ member.arrival_designation }}</p>
																	</div>
																</div>
															</div>
															<div class="panel-footer small clearfix" style="background-color: #ffe000;" v-if="member.companions.data.length && companionsPresentSquad(member, squad)">
																<i class=" fa fa-info-circle"></i> {{member.present_companions}} companions not in group &middot; {{companionsPresentTeam(member)}} not on this squad.
																<button type="button" class="btn btn-xs btn-default-hollow pull-right" @click="addCompanionsToSquad(member, squad)"><i class="fa fa-plus-circle"></i> Companions</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</template>
									</template>
									<p class=" text-right" v-if="currentSquadGroups.length < currentTeam.type.data.rules.max_groups">
										<button :disabled="isLocked" class="btn btn-xs btn-primary" @click="showSquadCreateModal = true">Add Group</button>
									</p>
									<hr class="divider sm">
									<!-- Other Groups -->
									<template v-for="(tgIndex, squad) in currentSquadGroups | orderBy 'callsign' | filterBy membersSearch">
										<template v-if="squad.callsign !== 'Squad Leaders'">
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
												</div><!-- end panel-heading -->
												<div class="panel-body">
													<div class="alert alert-success" v-if="squad.members_count >= currentTeam.type.data.rules.max_group_members">
														Complete! You've filled all the positions.
													</div>
													<div class="panel-group" :id="'membersAccordion' + tgIndex" role="tablist" aria-multiselectable="true">
														<div class="panel panel-default" v-for="member in squad.members | orderBy '-leader'">
															<div class="panel-heading" role="tab" id="headingOne">
																<h4 class="panel-title">
																	<div class="row">
																		<div class="col-xs-9">
																			<div class="media">
																				<div class="media-left" style="padding-right:0;">
																					<a :href="getReservationLink(member)" target="_blank">
																						<img :src="member.avatar" class="img-circle img-xs av-left" style="margin-right: 10px"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member && member.leader">GL</span>
																					</a>
																				</div>
																				<div class="media-body" style="vertical-align:middle;">
																					<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
																					<i :class="getGenderStatusIcon(member)"></i> 
																					<a :href="getReservationLink(member)" target="_blank">{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}</a></h6>
																					<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }} <span class="text-muted">&middot; {{ member.travel_group }}</span></p>
																				</div><!-- end media-body -->
																			</div><!-- end media -->
																		</div>
																		<div class="col-xs-3 text-right action-buttons">
																			<dropdown type="default">
																				<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																					<span class="fa fa-ellipsis-h"></span>
																				</button>
																				<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																					<template v-for="subSquad in currentSquadGroups | orderBy 'callsign'">
																						<template v-if="subSquad.callsign === 'Squad Leaders'">
																							<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad) && isLeadership(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																							<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad) && isLeadership(member)"><a @click="moveToSquad(member, squad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																						</template>
																						<template v-else>
																							<template v-if="subSquad.id !== squad.id">
																								<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(subSquad)"><a @click="moveToSquad(member, squad, subSquad, false)">Move to Squad Leaders</a></li>
																								<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(subSquad) && isLeadership(member)"><a @click="moveToSquad(member, squad, subSquad, true)" v-text="'Move to ' + subSquad.callsign + ' as leader'"></a></li>
																								<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(subSquad)"><a @click="moveToSquad(member, squad, subSquad, false)" v-text="'Move to ' + subSquad.callsign"></a></li>
																							</template>
																						</template>
																					</template>
																					<li :class="{'disabled': isLocked}" role="separator" class="divider"></li>
																					<li :class="{'disabled': isLocked}" v-if="member && member.leader"><a @click="demoteToMember(member, squad)">Demote to Group Member</a></li>
																					<li :class="{'disabled': isLocked}" v-if="member && !member.leader && !squadHasLeader(squad)"><a @click="promoteToLeader(member, squad)">Promote to Group Leader</a></li>
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
																		<div class="col-sm-6">
																			<label>Companions</label>
																			<ul class="list-unstyled" v-if="member.companions.data.length">
																				<li v-for="companion in member.companions.data">
																					<i :class="getGenderStatusIcon(companion)"></i> 
																					{{ companion.surname | capitalize }}, {{ companion.given_names | capitalize }}
																					<span class="text-muted">({{ companion.relationship | capitalize }})</span>
																				</li>
																			</ul>
																			<p class="small" v-else>None</p>
																		</div>
																		<div class="col-sm-6">
																			<label>Designation</label>
																			<p class="small">{{ member.arrival_designation }}</p>
																		</div>
																	</div><!-- end row -->
																</div><!-- end panel-body -->
															</div>
															<div class="panel-footer small clearfix" style="background-color: #ffe000;" v-if="member.companions.data.length && companionsPresentSquad(member, squad)">
																<i class=" fa fa-info-circle"></i> {{member.present_companions}} companions not in this group &middot; {{companionsPresentTeam(member)}} not on this squad.
																<button type="button" class="btn btn-xs btn-default-hollow pull-right" @click="addCompanionsToSquad(member, squad)"><i class="fa fa-plus-circle"></i> Companions</button>
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
										<option :value="type.id" v-for="type in teamTypes">{{type.name | capitalize}}</option>
									</select>-->
									<p v-text="currentTeam.type.data.name | capitalize"></p>
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
													          :value.sync="addAssociationData.object" :options="unassociatedGroups" label="name"
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
										<li class="list-group-item" v-for="(key, value) in currentTeam.type.data.rules">
											<span class="badge" v-text="value"></span>
											{{ key | underscoreToSpace | capitalize }}
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
						<a href="#reservations" data-toggle="pill">Reservations <span class="badge" v-text="reservationsPagination.total"></span></a>
					</li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="teams">
						<div class="row">
							<template v-if="isAdminRoute">
								<div class="col-xs-6">
									<div class="input-group input-group-sm col-xs-12">
										<input type="text" class="form-control" v-model="teamsSearch" debounce="300" placeholder="Search">
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
											<div class="row">
												<div class="col-xs-6">
													{{ team.callsign | capitalize }}
													<span class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;" v-text="team.type.data.name | capitalize"></span>
													<span v-if="team.locked" class="badge text-uppercase"><i class="fa fa-lock"></i> Locked</span>
												</div>
												<div class="col-xs-6 text-right"><i class="fa fa-users"></i> {{ team.members_count || 0 }}</div>
											</div>
										</a>
									</ul>
									<div class="col-xs-12 text-center">
										<pagination :pagination.sync="teamsPagination" :callback="getTeams"></pagination>
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
														<div class="media">
															<div class="media-left" style="padding-right:0;">
																<a :href="getReservationLink(reservation)" target="_blank">
																	<img :src="reservation.avatar" class="img-circle img-xs av-left" style="margin-right: 10px"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member && member.leader">GL</span>
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
														<dropdown type="default">
															<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																<span class="fa fa-ellipsis-h"></span>
															</button>
															<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																<li class="dropdown-header">Assign To Squad</li>
																<li role="separator" class="divider"></li>
																<template v-for="squad in currentSquadGroups | orderBy 'callsign'">
																	<template v-if="squad.callsign === 'Squad Leaders'">
																		<li :class="{'disabled': isLocked}" v-if="canAssignToTeamLeaders(squad) && isLeadership(reservation)"><a @click="assignToSquad(reservation, squad, false)">Squad Leader</a></li>
																	</template>
																	<template v-else>
																		<li :class="{'disabled': isLocked}" v-if="canAssignToSquadLeader(squad) && isLeadership(reservation)"><a @click="assignToSquad(reservation, squad, true)" v-text="squad.callsign + ' Leader'"></a></li>
																		<li :class="{'disabled': isLocked}" v-if="canAssignToSquad(squad)"><a @click="assignToSquad(reservation, squad, false)" v-text="squad.callsign"></a></li>
																	</template>
																</template>
																<li role="separator" class="divider"></li>
																<li class="dropdown-header">Change Role</li>
																<li role="separator" class="divider"></li>
																<li v-if="reservation.desired_role.name !== 'Squad Leader'"><a @click="updateRole(reservation, 'Squad Leader')">Squad Leader</a></li>
																<li v-if="reservation.desired_role.name !== 'Group Leader'"><a @click="updateRole(reservation, 'Group Leader')">Group Leader</a></li>

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
													<div class="col-sm-6">
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

			<!-- Modals -->
			<modal title="Create a new Squad" small ok-text="Create" :callback="newTeam" :show.sync="showTeamCreateModal">
				<div slot="modal-body" class="modal-body">
					<validator name="TeamCreate">
						<form id="TeamCreateForm">
							<div class="form-group" :class="{'has-error': $TeamCreate.teamcallsign.invalid}">
								<label for="createTeamCallsign" class="control-label">Squad Name</label>
								<input @keydown.enter.prevent="newTeam" type="text" class="form-control" id="createTeamCallsign" placeholder="" v-validate:teamcallsign="['required']" v-model="newTeamCallSign">
							</div>
							<div class="form-group" :class="{'has-error': $TeamCreate.teamtype.invalid}">
								<label for="" class="control-label">Type</label>
								<select class="form-control" v-model="newTeamType" v-validate:teamtype="['required']">
									<option :value="type.id" v-for="type in teamTypes">{{type.name | capitalize}}</option>
								</select>
							</div>
							<div class="form-group" :class="{'has-error': $TeamCreate.teamgroup.invalid}" v-if="isAdminRoute">
								<label>Travel Group</label>
								<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
										  :value.sync="newTeamGroup" :options="groupsOptions" label="name"
										  placeholder="Assign Travel Group"></v-select>
								<select class="hidden" v-model="newTeamGroup" v-validate:teamgroup="['required']">
									<option :value="group" v-for="group in groupsOptions">{{group.name | capitalize}}</option>
								</select>
							</div>
						</form>
					</validator>
				</div>
			</modal>
			<modal title="Delete Squad" small ok-text="Delete" :callback="deleteTeam" :show.sync="showTeamDeleteModal">
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
			<modal title="Remove Association" small ok-text="Remove" :callback="removeAssociation" :show.sync="removeAssociationData.show">
				<div slot="modal-body" class="modal-body">
					<p v-if="removeAssociationData.object">
						Are you sure you want to disassociate {{removeAssociationData.object.name}} with this Team ?
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
    export default{
        name: 'team-manager',
	    components: {vSelect, notes},
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
                reservations: [],
                membersSearch: '',
                teamsSearch: '',
                reservationsSearch: '',
                reservationsPerPage: 10,
                reservationsPagination: { current_page: 1 },
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

                lastReservationRequest: null,

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
                showReservationsFilters: false,
                showMembersFilters: false,
                campaignsArr: [],
                groupsArr: [],
                groupObj: null,
                roleObj: null,
	            leadershipRoles: [],
                campaignsOptions: [],
                groupsOptions: [],
                teamFilters: {
                    group: '',
	                campaign: null
                },

                // reservations filters
	            reservationFilters: {
                    type: '',
		            groups: [],
		            gender: '',
		            status: '',
                    hasCompanions: null,
                    role: '',
                    designation: ''
                },

	            // members filters
	            membersFilters: {
                    groups: [],
		            gender: '',
		            status: '',
                    hasCompanions: '',
                    role: ''
                },

                reservationsAgeMin: 0,
                reservationsAgeMax: 120,

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
                handler: function (val) {
                    this.teamsPagination.current_page = 1;
                    this.getTeams();
                },
                deep: true
            },
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
            'groupObj': function (val) {
                this.teamFilters.group = val ? val.id : '';
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
            'teamsSearch': function (val, oldVal) {
                this.teamsPagination.current_page = 1;
                this.getTeams();
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
			    this.updateConfig();
            },

        },
	    computed: {
            excludeReservationIds() {
                let IDs = [];
                if (this.currentTeam)
	                _.each(this.currentSquadGroups, function (squad) {
		                IDs = _.union(IDs, _.pluck(squad.members, 'id'));
	                });
                return _.uniq(IDs);
            },
		    leaderSquad() {
                return this.currentSquadGroups.length ? _.findWhere(this.currentSquadGroups, { callsign: 'Squad Leaders'}) : null;
		    },
		    groupLeaders() {
                let leaders = [];
                _.each(this.currentSquadGroups, function (group) {
                    let leader = _.findWhere(group.members, { leader: true });
                    if (leader)
	                    leaders.push(leader);
                });
                return leaders;
		    },
            totalMembers() {
                let members = 0;
                _.each(this.currentSquadGroups, function (squad) {
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
                this.getRoles().then(function (roles) {
                    let role = _.findWhere(roles, { name: roleName});
                    this.$http.put('reservations/' + member.id, { desired_role: role.value },
                        { params: { include: 'trip.campaign,trip.group,fundraisers,costs.payments,user,companions' }
                        }).then(function (response) {
                        this.$root.$emit('showSuccess', member.given_names + ' ' + member.surname + ' role updated!');
                        member.desired_role = response.body.data.desired_role;
                        return member = response.body.data;
                    }, function (response) {
                        console.log(response);
                        this.$root.$emit('showError', response.body.message);
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
                this.reservationsAgeMin = 0;
                this.reservationsAgeMax = 120;
                this.groupsArr = [];
                this.reservationFilters = {
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
	                role: '',
                    designation: ''
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
	            return squad.callsign === 'Squad Leaders' && squad.members && squad.members.length < this.currentTeam.type.data.rules.max_leaders;
            },
            canAssignToSquadLeader(squad){
                return !_.some(squad.members, function (member) {
	                return member.leader;
                });
            },
            canAssignToSquad(squad){
	            return  squad.members && squad.members.length < this.currentTeam.type.data.rules.max_group_members;
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
                    squad.members_count = squad.members.length;
                    this.currentTeam.members_count++;
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
						test = _.some(newSquad.members, function (member) {
							return member.gender === reservation.gender;
                        });
	                    if (test){
                            this.$root.$emit('showError', 'Squad Leaders members can not be of the same gender.');
                            return;
                        }
                    }
                }

                this.$http.put('squads/' + oldSquad.id + '/members/' + reservation.id, { team_squad_id: newSquad.id, leader: leader }, { params: { include: 'trip.group,companions'}})
	                .then(function (response) {
                        console.log(response);
		                // update old squad
                        oldSquad.members = _.reject(oldSquad.members, function (member) {
                            return member.id === reservation.id;
                        });
                        oldSquad.members_count = oldSquad.members.length;
		                // update new Squad
                        newSquad.members.push(response.body.data);
                        newSquad.members_count = newSquad.members.length;
                    }, function (response) {
                        console.log(response);
	                    return response;
	                });

                /*this.removeFromSquad(reservation, oldSquad).then(function (response) {
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

                this.TeamSquadResource.update(params , data).then(function (response) {
                    let groups =_.reject(this.currentSquadGroups, function (sq) {
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
	                .then(function (response) {
	                    squad.members = _.reject(squad.members, function (member) {
		                    return member.id === memberObj.id;
                        });
                        squad.members_count = squad.members.length;
                        this.currentTeam.members_count--;
                    });
            },
            searchReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,user,companions',
                    search: this.reservationsSearch,
                    per_page: this.reservationsPerPage,
                    page: this.reservationsPagination.current_page,
	                current: true,
	                ignore: this.excludeReservationIds,
                    noSquad: true,
                    designation: this.reservationFilters.designation,
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
                    return this.currentSquadGroups = response.body.data;
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

                        if (this.newTeamGroup && this.newTeamGroup.id)
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
                    this.TeamResource.save(data, params).then(function (response) {
                        let team = response.body.data;

                        this.teams.push(team);
                        this.getTeams().then(function () {

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
	        },
	        newSquad(callsign){
	            if (this.currentSquadGroups.length >= this.currentTeam.type.data.rules.max_groups) {
	                this.$root.$emit('showError', 'This squad already has the max amount of ' + this.currentTeam.type.data.rules.max_groups + ' groups');
	                return;
	            }

                if (this.$SquadCreate.valid || _.isString(callsign)) {
                    return this.TeamSquadResource.save({team: this.currentTeam.id}, {callsign: _.isString(callsign) ? callsign : this.newSquadCallsign})
                        .then(function (response) {
							let squad = _.extend(response.body.data, {
							    members: [],
                                members_count: 0
                            });

	                        this.currentSquadGroups.push(squad);
                            this.currentTeam.squads_count++;
                            this.showSquadCreateModal = false;
                            return squad;
                        });
                }
	        },
	        updateTeam(team){
	            this.TeamResource.update({team: team.id, include: 'type,groups'}, team).then(function (response) {
					this.$root.$emit('showSuccess', team.callsign + ' Updated!');
                });
	        },
	        updateTeamSettings(ignoreMessage){
	            return this.TeamResource.update({ team: this.currentTeam.id, include: 'type,groups' }, { callsign: this.currentTeam.callsign, locked: this.currentTeam.locked }).then(function (response) {
                    this.currentTeam = response.body.data;
                    if (!ignoreMessage)
						this.$root.$emit('showSuccess', this.currentTeam.callsign + ' Updated!');
					this.editTeamMode = false;
					return response.body.data;
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

	            this.TeamSquadResource.update(params, data).then(function (response) {
	                let squad = _.findWhere(this.currentSquadGroups, { id: this.selectedSquadObj.id});
	                squad.callsign = response.body.data.callsign;
                    this.$root.$emit('showSuccess', response.body.data.callsign + ' Updated!');
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

                this.TeamResource.delete({ team: team.id }).then(function (response) {
                    this.$root.$emit('showInfo', team.callsign + ' Deleted!');
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

                this.TeamSquadResource.delete({team: squadCopy.team_id, squad:squadCopy.id}).then(function (response) {
                    this.$root.$emit('showInfo', squadCopy.callsign + ' Deleted!');
                    this.currentSquadGroups = _.reject(this.currentSquadGroups, function (squad) {
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
                return this.$http.post(type + '/' + object.id + '/teams', { ids: [this.currentTeam.id] }).then(function (response) {
                    this.additionalAssociatedGroup = null;
                    this.updateTeamSettings(true).then(function () {
                        this.$root.$emit('showSuccess', this.currentTeam.callsign + ' is now associated with ' + object.name);
                    });
                }, function (response) {
                    console.log(response.body);
                    this.$root.$emit('showError', response.body.message);
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

	            return this.$http.delete(this.removeAssociationData.type + '/' + this.removeAssociationData.object.id + '/teams/' + this.currentTeam.id).then(function (response) {
                    this.updateTeamSettings(true).then(function () {
                        this.$root.$emit('showSuccess', this.currentTeam.callsign + ' is now unassociated with ' + this.removeAssociationData.object.name);
                        this.removeAssociationData.type = null;
                        this.removeAssociationData.object = null;
                        this.removeAssociationData.show = false;
                    });
                }, function (response) {
                    console.log(response.body);
                    this.$root.$emit('showError', response.body.message);
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
                    this.$root.$emit('showInfo', 'This squad is currently locked');
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
                _.each(this.currentSquadGroups, function (squad) {
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
	                let testLeadership = _.some(member.companions.data, function (companion) {
		                return !_.contains(this.leadershipRoles, companion.desired_role.code)
                    });

	                if (testLeadership) {
	                    this.$root.$emit('showError', 'Some companions do not have a leadership role');
	                    return;
	                }
	            }

	            // Detect companions in other groups and remove them
	            if (member.present_companions_team) {
                    _.each(this.currentSquadGroups, function (group) {
                        let companionObj;
                        _.each(notPresentIds, function (companionId) {
	                        companionObj = _.findWhere(group.members, {id: companionId});
	                        if (companionObj) {
	                            this.removeFromSquad(companionObj, group);
	                        }
                        }.bind(this));

                    }.bind(this));
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
            promises.push(this.getCampaigns());
            promises.push(this.getRoles());
            promises.push(this.$http.get('utilities/team-roles/leadership').then(function (response) {
                let roles = [];
	            _.each(response.body.roles, function (role, code) {
		            roles.push(code);
                });
	            this.leadershipRoles = roles;
            }, function (error) {
                console.log(error);
                return error;
            }));

            Promise.all(promises).then(function (values) {
                this.startUp = false;
                this.searchReservations();

                // load view state
                if (localStorage[this.storageName]) {
                    let config = JSON.parse(localStorage[this.storageName]);
                    if (config.currentTeam) {
                    	this.makeTeamCurrent(_.findWhere(this.teams, { id: config.currentTeam}));
                    }
                }
            }.bind(this));

            this.$root.$on('campaign-scope', function (val) {
                if(val) {
	                this.campaignId = val ? val.id : '';
                    this.newTeamCampaigns = [{id: val.id}];
                    this.$root.$emit('update-title', val ? val.name : '');
                    this.getTeams()
                }
                this.searchReservations();
            }.bind(this));

            $('#collapseHints')
	            .on('show.bs.collapse', function () {
	                self.toggleHintsCollapse = true;
	            })
	            .on('hide.bs.collapse', function () {
                    self.toggleHintsCollapse = false;
                });
        }
    }
</script>