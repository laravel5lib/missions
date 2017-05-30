<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>

		<template v-if="currentPlan">
			<div class="col-sm-8">

				<!-- Occupants List -->
				<template v-if="currentRoom">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h5>
								<template v-if="currentRoom.label">
									{{currentRoom.label | capitalize}} &middot; {{currentRoom.type.data.name | capitalize}}
								</template>
								<template v-else>
									{{currentRoom.type.data.name | capitalize}}
								</template>
								<span v-if="currentRoomHasLeader"> ({{ currentRoomHasLeader.surname }}, {{ currentRoomHasLeader.given_names | capitalize }}) </span>
								<span class="small">&middot; Details</span></h5>
						</div><!-- end panel-heading -->
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12">
									<div class="row">
										<div class="col-xs-6">
											<label>Room Occupants</label>
											<div class="label label-info" v-if="currentRoom.type.data.rules.gender">Same Gender</div>
											<div class="label label-info" v-if="currentRoom.type.data.rules.married_only">Must be Married</div>
										</div>
										<div class="col-xs-6 text-right">
											<label>{{currentRoom.occupants_count}} of {{currentRoom.type.data.rules.occupancy_limit}} occupants</label>
										</div>
									</div>
									<hr class="divider sm">
									<template v-if="currentRoom.occupants && currentRoom.occupants.length">
										<div class="panel-group" id="occupantsAccordion" role="tablist" aria-multiselectable="true">
											<div class="col-sm-6" v-for="member in currentRoom.occupants | orderBy 'surname'">
												<div class="panel panel-default" style="min-height: 75px">
													<div class="panel-heading" role="tab" id="headingOne">
														<h5 class="panel-title">
															<div class="row">
																<div class="col-xs-8">
																	<!--<a role="button" data-toggle="collapse" :data-parent="'#occupantsAccordion' + tgIndex" :href="'#occupantItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
																		<img :src="member.avatar" class="img-circle img-xs av-left">
																		{{ member.surname | capitalize }}, {{ member.given_names | capitalize }} <span class="label label-info" v-if="member.room_leader">Room Leader</span>
																	</a>-->
																	<div class="media">
																		<div class="media-left" style="padding-right:0;">
																			<a role="button" data-toggle="collapse" :data-parent="'#occupantsAccordion' + tgIndex" :href="'#occupantItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
																				<img :src="member.avatar" class="media-object img-circle img-xs av-left"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member.room_leader">RL</span>
																			</a>
																		</div>
																		<div class="media-body" style="vertical-align:middle;">
																			<h6 class="media-heading text-capitalize" style="margin-bottom:3px;"><a role="button" data-toggle="collapse" :data-parent="'#occupantsAccordion' + tgIndex" :href="'#occupantItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}</a></h6>
																			<p class="text-muted" style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }}</p>
																		</div><!-- end media-body -->
																	</div><!-- end media -->
																</div>
																<div class="col-xs-4 text-right action-buttons">
																	<dropdown type="default">
																		<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
																			<span class="fa fa-ellipsis-h"></span>
																		</button>
																		<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
																			<li v-if="member.room_leader" :class="{'disabled': isLocked}"><a @click="demoteToOccupant(member)">Demote to Occupant</a></li>
																			<li v-if="!member.room_leader && !currentRoomHasLeader" :class="{'disabled': isLocked}"><a @click="promoteToLeader(member)">Promote to Room Leader</a></li>
																			<li role="separator" class="divider"></li>
																			<li :class="{'disabled': isLocked}"><a @click="removeFromRoom(member, this.currentRoom)">Remove</a></li>
																		</ul>
																	</dropdown>
																	<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#membersAccordion" :href="'#occupantItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
																		<i class="fa fa-angle-down"></i>
																	</a>
																</div>
															</div>
														</h5>
													</div>
													<div :id="'occupantItem' + tgIndex + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
														<div class="panel-body">
															<div class="row">
																<div class="col-sm-2">
																	<label>Age</label>
																	<p class="small">{{member.age}}</p>
																	<!--<label>Travel Group</label>
																	<p class="small">{{member.trip.data.group.data.name}}</p>-->
																</div><!-- end col -->
																<div class="col-sm-3">
																	<label>Gender</label>
																	<p class="small">{{member.gender | capitalize}}</p>
																</div><!-- end col -->
																<div class="col-sm-6">
																	<label>Marital Status</label>
																	<p class="small">{{member.status | capitalize}}</p>
																</div><!-- end col -->
															</div><!-- end row -->
														</div><!-- end panel-body -->
													</div>
													<!--<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions.data.length && companionsPresentSquad(member, squad)">
														<i class=" fa fa-info-circle"></i> I have {{member.present_companions}} companions not in this group. And {{companionsPresentTeam(member)}} not on this team.
														<button type="button" class="btn btn-xs btn-default-hollow" @click="addCompanionsToSquad(member, squad)">Add Companions</button>
													</div>-->
													<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions && member.companions.data.length && companionsPresentRoom(member, currentRoom)">
														<i class=" fa fa-info-circle"></i> I have {{member.present_companions}} companions not in this group. And {{companionsPresentTeam(member)}} not on this team.
														<!--<button type="button" class="btn btn-xs btn-default-hollow" @click="addCompanionsToSquad(member, squad)">Add Companions</button>-->
													</div>
												</div>
											</div>
										</div>
									</template>
									<template v-else>
										<hr class="divider inv">
										<p class="text-center text-italic text-muted" style="padding:0 10px;"><em>This room is empty! Add occupants from your squad's list to fill the room.</em></p>
									</template>
								</div><!-- end col -->
							</div><!-- end row -->
						</div><!-- end panel-body -->
					</div><!-- end panel -->
				</template>
				<template v-else>
					<div class="well">
						<p style="margin-top:8px;" class="text-center text-muted"><em>Select a room to view details.</em></p>
					</div>

				</template>

				<div class="panel panel-default">
					<div class="panel-body">
						<!-- Search and Filter -->
						<form class="form-inline row">
							<div class="from-group col-xs-6 text-left">
								<h5>Rooms</h5>
							</div>
							<div class="form-group col-xs-6 text-right">
								<button class="btn btn-primary btn-xs" type="button" @click="openNewRoomModel">Add Room</button>
							</div>

							<div class="form-group col-xs-12">
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
								<template v-if="currentRooms.length">
									<!-- List group List-->
									<div class="list-group">
										<a @click="setActiveRoom(room)" class="list-group-item" :class="{ 'active': currentRoom && currentRoom.id === room.id}" v-for="room in currentRooms | orderBy 'label'" style="cursor: pointer;">
											{{(room.label ? (room.label + ' &middot; ' + room.type.data.name) : room.type.data.name) | capitalize}}
											<span v-if="room.type.data.rules.occupancy_limit === room.occupants_count" class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">Full</span>
											<span v-if="room.type.data.rules.occupancy_limit > room.occupants_count" class="badge text-uppercase" style="font-size:10px;line-height:1.4;letter-spacing: 0;">{{room.occupants_count}}</span>
										</a>
									</div>
								</template>
								<template v-else>
									<hr class="divider inv">
									<p class="text-center text-muted"><em>Create a room to get started!</em></p>
								</template>
							</div>
						</div>
					</div>
				</div>



				<!-- Plans List-->
				<!--<div class="col-sm-6">
					<div class="col-xs-12 text-right">
						<button class="btn btn-primary btn-xs" type="button" @click="openNewPlanModal">Create A Plan</button>
						<hr class="divider lg">
					</div>

					<div class="col-xs-12">
						<template v-if="plans.length">
							<accordion :one-at-atime="true" type="info">
								<panel :is-open="currentPlan && currentPlan.id === plan.id" :header="plan.name" v-for="plan in plans" @click="currentPlan = plan">
									<tabs>
										<tab header="Rooms">
											&lt;!&ndash; Search and Filter &ndash;&gt;
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
												</div>&lt;!&ndash; end col &ndash;&gt;
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
														&lt;!&ndash; List group List&ndash;&gt;
														<div class="list-group">
															<div @click="setActiveRoom(room)" class="list-group-item" :class="{ 'active': currentRoom && currentRoom.id === room.id}" v-for="room in currentPlan.rooms" style="cursor: pointer;">
																<h4 class="list-group-item-heading" v-text="(room.label ? (room.label + ' - ' + room.type.data.name) : room.type.data.name) | capitalize"></h4>
																<div class="list-group-item-text">
																	<div class="row">
																		<div class="col-sm-6">
																			<label>Occupancy Limit</label>
																			<p class="small">{{room.type.data.rules.occupancy_limit}}</p>
																			<label>Limited to Gender</label>
																			<p class="small">{{room.type.data.rules.gender | capitalize}}</p>
																			<label>Limited to Status</label>
																			<p class="small">{{room.type.data.rules.status | capitalize}}</p>
																		</div>&lt;!&ndash; end col &ndash;&gt;
																		<div class="col-sm-6">
																			<label>Current Number of Occupants</label>
																			<p class="small">{{room.occupants_count}}</p>
																			<label>Room Leader</label>
																			<p class="small">{{room.leader}}</p>
																		</div>&lt;!&ndash; end col &ndash;&gt;
																	</div>&lt;!&ndash; end row &ndash;&gt;
																</div>
															</div>
														</div>

														&lt;!&ndash; Panel List &ndash;&gt;
														&lt;!&ndash;<div class="panel panel-default" v-for="room in currentPlan.rooms">
															<div class="panel-heading">
																<h3 class="panel-title" v-text="(room.label ? (room.label + ' - ' + room.type.data.name) : room.type.data.name) | capitalize"></h3>
															</div>
															<div class="panel-body">
																<div class="row">
																	<div class="col-sm-6">
																		<label>Occupancy Limit</label>
																		<p class="small">{{room.type.data.rules.occupancy_limit}}</p>
																		<label>Limit ed to Gender</label>
																		<p class="small">{{room.type.data.rules.gender | capitalize}}</p>
																		<label>Limited to Status</label>
																		<p class="small">{{room.type.data.rules.status | capitalize}}</p>
																	</div>&lt;!&ndash; end col &ndash;&gt;
																	<div class="col-sm-6">
																		<label>Current Number of Occupants</label>
																		<p class="small">{{room.occupants_count}}</p>
																		<label>Room Leader</label>
																		<p class="small">{{room.leader}}</p>
																	</div>&lt;!&ndash; end col &ndash;&gt;
																</div>&lt;!&ndash; end row &ndash;&gt;
															</div>
														</div>&ndash;&gt;
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
							<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewPlanModal">Create A Plan</a></p>
						</template>

					</div>

				</div>-->
			</div>

			<!-- Teams Select & Members List -->
			<div class="col-sm-4">
				<select class="form-control input-sm" v-model="currentTeam">
					<option :value="" v-if="!currentTeam">Select Squad</option>
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
							<div class="panel panel-default" v-for="member in currentTeamMembers | orderBy 'surname'" v-show="true">
								<div class="panel-heading" role="tab" id="headingOne">
									<h5 class="panel-title">
										<div class="row">
											<div class="col-xs-9">
												<div class="media">
													<div class="media-left" style="padding-right:0;">
														<a role="button" data-toggle="collapse" :data-parent="'#membersAccordion' + tgIndex" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
															<img :src="member.avatar" class="media-object img-circle img-xs av-left"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member.leader">GL</span>
														</a>
													</div>
													<div class="media-body" style="vertical-align:middle;">
														<h6 class="media-heading text-capitalize" style="margin-bottom:3px;"><a role="button" data-toggle="collapse" :data-parent="'#membersAccordion' + tgIndex" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}</a></h6>
														<p class="text-muted" style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }}</p>
													</div><!-- end media-body -->
												</div><!-- end media -->
												<!-- <a role="button" data-toggle="collapse" :data-parent="'#membersAccordion' + tgIndex" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
													<img :src="member.avatar" class="img-circle img-xs av-left">
													{{ member.surname | capitalize }}, {{ member.given_names | capitalize }} <span class="small" v-if="member.leader">&middot; GL</span><br>
													<label>{{ member.desired_role.name }}</label>
												</a> -->
											</div>
											<div class="col-xs-3 text-right action-buttons">
												<dropdown type="default">
													<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
														<span class="fa fa-ellipsis-h"></span>
													</button>
													<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
														<li class="dropdown-header">Assign To Room</li>
														<li role="separator" class="divider"></li>
														<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(member, true, currentRoom)">{{(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize}} as leader</a></li>
														<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(member, false, currentRoom)" v-text="(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize"></a></li>
														<li v-if="!currentRoom"><a @click=""><em>Please select a room first.</em></a></li>
													</ul>
												</dropdown>
												<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#membersAccordion" :href="'#memberItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-angle-down"></i>
												</a>
											</div>
										</div>
									</h5>
								</div>
								<div :id="'memberItem' + tgIndex + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
												<label>Gender</label>
											</div><!-- end col -->
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{member.gender | capitalize}}</p>
											</div><!-- end col -->
										</div><!-- end row -->
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Marital Status</label>
											</div><!-- end col -->
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{member.status | capitalize}}</p>
											</div><!-- end col -->
										</div><!-- end row -->
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Age</label>
											</div><!-- end col -->
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{member.age}}</p>
											</div><!-- end col -->
										</div><!-- end row -->
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Travel Group</label>
											</div><!-- end col -->
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{member.trip.data.group.data.name}}</p>
											</div><!-- end col -->
										</div><!-- end row -->
									</div><!-- end panel-body -->
								</div>
								<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions && member.companions.data.length">
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
					<p class="text-center text-italic text-muted"><em>Select a squad to begin.</em></p>
				</template>
			</div>
		</template>
		<template v-else>
			<p style="margin-top:8px;" class="text-center text-muted"><em>Please select a rooming plan to get started</em></p>
		</template>

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
	            currentRooms: [],
				currentRoom: null,
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
                storageName: 'TravelGroupRooming',
            }
        },
	    watch: {
            currentPlan(val) {
                val.rooms = val.rooms || [];
                //this.$nextTick(function () {
                this.updateConfig();
                this.currentRoom = null;
                this.getRooms(val);
	            this.getTeams();
                //});
            },
            currentRoom: {
                handler(val, oldVal) {
                    //val.rooms = val.rooms || [];
                    if (val && (!oldVal || val.occupants_count !== oldVal.occupants_count))
                        this.getOccupants();
                    this.getTeams();
                    this.updateConfig();
                },
	            deep: true

            }
	    },
	    computed: {
            planOccupants() {
                let excludedIDs = [];
                if (_.isObject(this.currentPlan) && this.currentRooms) {
                    _.each(this.currentRooms, function (room) {
                        let arr = room.occupants.hasOwnProperty('data') ? room.occupants.data : room.occupants;
                        excludedIDs = _.union(excludedIDs, _.pluck(arr, 'id'));
                    });
                    excludedIDs = _.uniq(excludedIDs);
                }
                return excludedIDs;
            },
            currentTeamMembers() {
                let members = [];
                let self = this;
                if (_.isObject(this.currentTeam))
	                _.each(this.currentTeam.squads.data, function (squad) {
						_.each(squad.members.data, function (member) {
						    if (!_.contains(self.planOccupants, member.id))
								members.push(member);
	                    });
	                });

                return members;
            },
		    currentRoomHasLeader() {
                if (this.currentRoom && this.roomHasLeader(this.currentRoom)) {
                    let leader = _.findWhere(this.currentRoom.occupants, { room_leader: true });
                    return leader || false;
                } else return false;
		    }
	    },
        methods: {
            updateConfig(){
                localStorage[this.storageName] = JSON.stringify({
                    currentPlan: this.currentPlan.id,
                });
            },
            promoteToLeader(occupant) {
                let data = {
                    reservation_id: occupant.id,
                    room_leader: true,
                };
                this.$http.put('rooming/rooms/' + this.currentRoom.id + '/occupants/' + occupant.id, data)
                    .then(function (response) {
						return occupant.room_leader = response.body.data.room_leader;
                    })
            },
            demoteToOccupant(occupant) {
                let data = {
                    reservation_id: occupant.id,
                    room_leader: false,
                };
                this.$http.put('rooming/rooms/' + this.currentRoom.id + '/occupants/' + occupant.id, data)
	                .then(function (response) {
                        return occupant.room_leader = response.body.data.room_leader;
                    });
            },
            setActiveRoom(room) {
                this.currentRoom = room;
            },
            roomHasLeader(room) {
                return _.some(room.occupants, function (occupant) {
	                return occupant.room_leader;
                });
            },
            companionsPresentRoom(member, room) {
                let memberIds = _.filter(_.pluck(room.occupants, 'id'), function (id) { return id !== member.id; });
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
                //_.each(this.currentSquadGroups, function (squad) {
                    memberIds = _.pluck(this.currentTeamMembers, 'id');
                //});
                memberIds = _.filter(memberIds, function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, function (id) {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                return member.present_companions_team = companionIds.length - presentIds.length;
            },
            removeFromRoom(occupant, room) {
                return this.$http.delete('rooming/rooms/' + room.id + '/occupants/' + occupant.id).then(function (response) {
                    room.occupants = _.reject(room.occupants, function (member) {
                        return member.id === occupant.id;
                    });
                    room.occupants_count--;
                    this.currentPlan.occupants_count--;
                    return response.body;
                });
            },
            addToRoom(occupant, leader, room) {
                if (leader && this.roomHasLeader(room)) {
                    this.$root.$emit('showInfo', 'This room already has a leader');
                    return;
                }

                if (room.occupants_count >= room.type.data.rules.occupancy_limit) {
                    this.$root.$emit('showInfo', room.label +' currently has the max number of occupants');
                    return;
                }

                let data = {
                    reservation_id: occupant.id,
                    room_leader: leader,
                };

                return this.$http.post('rooming/rooms/' + room.id + '/occupants', data,  { params: { } }).then(function (response) {
	                let occupants = response.body.data;
	                room.occupants = occupants;
                    room.occupants_count = occupants.length;
                    this.currentPlan.occupants_count++;
                }, function (response) {
	                this.$root.$emit('showError', response.body.message)
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
                    page: this.plansPagination.current_page,
                };
	                return this.$http.get('rooming/plans', { params: params }).then(function (response) {
	                    _.each(response.body.data, function (plan) {
		                    plan.rooms = [];
	                    });
                        this.plansPagination = response.body.meta.pagination;
                        return this.plans = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getAllRooms(plans){
                let params = {
                    plans: plans,
	                include: 'type',
                    // page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/rooms', { params: params }).then(function (response) {
                        console.log(response.body.data)
//                        this.plansPagination = response.body.meta.pagination;
//                        return this.plans = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getRooms(plan){
                plan = plan || this.currentPlan;
                let params = {
                    plans: new Array(plan.id),
	                include: 'type,occupants',
                    // page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/rooms', { params: params })
	                .then(function (response) {
                        plan.rooms = response.body.data;
		                if (plan.id === this.currentPlan.id)
		                    return this.currentRooms = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },
            getOccupants(){
                let params = {
//                    plans: new Array(this.currentPlan.id),
//	                include: 'type',
                    // page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/rooms/' + this.currentRoom.id + '/occupants', { params: params }).then(function (response) {
                        this.currentRoom.occupants = response.body.data
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

                if (_.isObject(this.currentPlan) && this.currentPlan.id) {
                    params.include = 'squads.members:noRoom(plans|' + this.currentPlan.id + '),squads.members.companions,squads.members.trip.group,type';
                }

                return this.$http.get('teams', { params: params }).then(function (response) {
                        this.teamsPagination = response.body.meta.pagination;
                        return this.teams = response.body.data;
                    }, function (response) {
                        console.log(response);
                        return response.body.data;
                    });
            },

	        // Modal Functions
            openNewPlanModal(){
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
                if (this.$PlanCreate.valid) {
                    return this.$http.post('rooming/plans', this.selectedPlan).then(function (response) {
                        let plan = response.body.data;
                        plan.rooms = [];
                        this.plans.push(plan);
                        this.showPlanModal = false;
                        this.$root.$emit('select-options:update', plan.id, 'id');
                        return this.currentPlan = plan;
                    }, function (response) {
                        console.log(response);
                        this.$root.$emit('showError', response.body.message);
                        return response.body.data;
                    });
                } else {
                    this.$root.$emit('showError', 'Please provide a name for the new plan');
                }
            },
            openNewRoomModel(){
                if (!this.currentPlan) {
                    this.$root.$emit('showInfo', 'Please select a plan first.');
                    return;
                }

                this.showRoomModal = true;
                this.selectedRoom = {
                    room_type_id: null,
                    type: null,
                    label: '',
                    occupants: [],
                };
            },
	        newRoom() {
                if (this.$RoomCreate.valid) {
                    return this.$http.post('rooming/plans/' + this.currentPlan.id + '/rooms', this.selectedRoom, {params: {include: 'type,occupants'}}).then(function (response) {
                        let room = response.body.data;
                        this.showRoomModal = false;
                        return this.getRooms().then(function (rooms) {
                            return this.currentRoom = _.findWhere(this.currentRooms, { id: room.id})
                        });
                    }, function (response) {
                        console.log(response);
                        this.$root.$emit('showError', response.body.message);
                        return response.body.data;
                    });
                } else {
                    this.$root.$emit('showError', 'Please select a room type');
                }
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

            }

            promises.push(this.getPlans().then(function (plans) {
                //let pIds = _.pluck(plans, 'id');
	            //this.getAllRooms(pIds);
            }));
            promises.push(this.getRoomTypes());
            promises.push(this.getTeams());
//            promises.push(this.getRoles());
            Promise.all(promises).then(function (values) {
                this.startUp = false;

                // load view state
                if (localStorage[this.storageName]) {
                    let config = JSON.parse(localStorage[this.storageName]);
                    if (config.currentPlan) {
                        let plan = _.findWhere(this.plans, { id: config.currentPlan});
                        this.currentPlan = plan;
                        this.$root.$emit('select-options:select', plan.id, 'id');
                    }
                }
            }.bind(this));

            this.$root.$on('campaign-scope', function (val) {
                this.campaignId = val ? val.id : '';
                this.$root.$emit('update-title', val ? val.name : '');
            }.bind(this));

            this.$root.$on('plan-scope', function (val) {
                val.rooms = val.rooms || [];
                this.currentPlan = val || null;
//                this.$root.$emit('update-title', val || null);
            }.bind(this));

            this.$root.$on('create-plan', function (val) {
                this.openNewPlanModal();
            }.bind(this));

        }
    }
</script>