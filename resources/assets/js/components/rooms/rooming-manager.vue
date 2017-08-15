<template>
	<div>
		<div class="row" style="position:relative;">
			<mm-aside :show="showReservationsFilters" @open="showReservationsFilters=true" @close="showReservationsFilters=false" placement="left" header="Reservation Filters" :width="375">
				<reservations-filters ref="filters" :filters="reservationFilters" :reset-callback="resetReservationFilter" :pagination="reservationsPagination" :callback="searchReservations" storage="" :starter="startUp" rooms></reservations-filters>
			</mm-aside>

			<template v-if="currentPlan">
				<div class="col-xs-12" v-if="currentPlan">
					<h3>{{ currentPlan.name }} <button type="button" class="btn btn-xs btn-primary" @click="changePlan">Change Plan</button></h3>
					<h5>
						<template v-for="(group, groupIndex) in currentPlan.groups.data">
							{{ group.name }}
							<template v-if="(groupIndex + 1) < currentPlan.groups.data.length">&middot;</template>
						</template>
					</h5>
					<hr class="divider lg">
				</div>
				<div class="col-sm-8">
					<!-- Occupants List -->
					<template v-if="currentRoom">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-10">
										<h5>
											<template v-if="currentRoom.label">
												{{currentRoom.label ? currentRoom.label[0].toUpperCase() + currentRoom.label.slice(1) : ''}} &middot; {{currentRoom.type.data.name ? currentRoom.type.data.name[0].toUpperCase() + currentRoom.type.data.name.slice(1) : ''}}
											</template>
											<template v-else>
												{{currentRoom.type.data.name ? currentRoom.type.data.name[0].toUpperCase() + currentRoom.type.data.name.slice(1) : ''}}
											</template>
											<span v-if="currentRoomHasLeader"> ({{ currentRoomHasLeader.surname }}, {{ currentRoomHasLeader.given_names ? currentRoomHasLeader.given_names[0].toUpperCase() + currentRoomHasLeader.given_names.slice(1) : '' }}) </span>
											<span class="small">&middot; Details</span>

										</h5>
									</div>
									<div class="col-xs-2 text-right">
										<dropdown type="default">
											<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
												<span class="fa fa-ellipsis-h"></span>
											</button>
											<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
												<li :class="{'disabled': isLocked}"><a @click="editRoom(currentRoom)">Edit Room</a></li>
												<li :class="{'disabled': isLocked}"><a @click="openDeleteRoomModal(currentRoom)">Delete Room</a></li>
											</ul>
										</dropdown>
									</div>
								</div>

							</div><!-- end panel-heading -->
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="row">
											<div class="col-xs-6">
												<label>Room Occupants</label>
												<div class="label label-danger" v-if="currentRoom.type.data.rules.same_gender">Must be Same Gender</div>
												<div class="label label-danger" v-if="currentRoom.type.data.rules.married_only">Must be Married</div>
											</div>
											<div class="col-xs-6 text-right">
												<label>{{currentRoom.occupants_count}} of {{currentRoom.type.data.rules.occupancy_limit}} occupants</label>
											</div>
										</div>
										<hr class="divider sm">
										<template v-if="currentRoom.occupants && currentRoom.occupants.length">
											<div class="panel-group" id="occupantsAccordion" role="tablist" aria-multiselectable="true">
												<div class="row">
												<div class="col-sm-12" v-for="(member, memberIndex) in currentRoomOccupantsOrdered">
													<div class="panel panel-default" style="margin-bottom:8px;">
														<div class="panel-heading" role="tab" id="headingOne">
															<h5 class="panel-title">
																<div class="row">
																	<div class="col-xs-8">
																		<div class="media">
																			<div class="media-left" style="padding-right:0;">
																				<a :href="getReservationLink(member)" target="_blank">
																					<img :src="member.avatar" class="media-object img-circle img-xs av-left"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member.room_leader">RL</span>
																				</a>
																			</div>
																			<div class="media-body" style="vertical-align:middle;">
																				<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
																				<i :class="getGenderStatusIcon(member)"></i>
																				<a :href="getReservationLink(member)" target="_blank">{{ member.surname ? member.surname[0].toUpperCase() + member.surname.slice(1) : '' }}, {{ member.given_names ? member.given_names[0].toUpperCase() + member.given_names.slice(1) : '' }}</a></h6>
																				<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }} <span class="text-muted">&middot; {{ member.travel_group }}</span></p>
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
																				<li v-if="member.room_leader || (!member.room_leader && !currentRoomHasLeader)" role="separator" class="divider"></li>
																				<li :class="{'disabled': isLocked}"><a @click="removeFromRoom(member, this.currentRoom)">Remove</a></li>
																			</ul>
																		</dropdown>
																		<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#membersAccordion" :href="'#occupantItem' + memberIndex" aria-expanded="true" aria-controls="collapseOne">
																			<i class="fa fa-angle-down"></i>
																		</a>
																	</div>
																</div>
															</h5>
														</div>
														<div :id="'occupantItem' + memberIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
															<div class="panel-body">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Age</label>
																		<p class="small">{{member.age}}</p>
																	</div><!-- end col -->
																	<div class="col-sm-3">
																		<label>Gender</label>
																		<p class="small">{{member.gender ? member.gender[0].toUpperCase() + member.gender.slice(1) : ''}}</p>
																	</div><!-- end col -->
																	<div class="col-sm-6">
																		<label>Marital Status</label>
																		<p class="small">{{member.status ? member.status[0].toUpperCase() + member.status.slice(1) : ''}}</p>
																	</div>
																</div><!-- end row -->
																<div class="row">
																	<div class="col-sm-6">
																		<label>Travel Group</label>
																		<p class="small">{{member.travel_group}}</p>
																	</div>
																	<div class="col-sm-6">
																		<label>Designation</label>
																		<p class="small">{{member.arrival_designation ? member.arrival_designation[0].toUpperCase() + member.arrival_designation.slice(1) : ''}}</p>
																	</div>
																</div>
																<div class="row">
																	<div class="col-sm-6">
																		<label>Companions</label>
																		<ul class="list-unstyled small" v-if="member.companions.data.length">
																			<li v-for="companion in member.companions.data">
																				<i :class="getGenderStatusIcon(companion)"></i>
																				{{ companion.surname ? companion.surname[0].toUpperCase() + companion.surname.slice(1) : '' }}, {{ companion.given_names ? companion.given_names[0].toUpperCase() + companion.given_names.slice(1) : '' }}
																				<span class="text-muted">({{ companion.relationship }})</span>
																			</li>
																		</ul>
																		<p class="small" v-else>None</p>
																	</div><!-- end col -->
																	<div class="col-sm-6">
																		<label>Squad Groups</label>
																		<p class="small" v-if="member.squads.data.length">
																			<span v-for="squad in member.squads.data">{{squad.callsign}} <span v-if="squad.team">({{ squad.team.data.callsign }})</span><span v-if="!$last && member.squads.data.length > 1">, </span></span>
																		</p>
																		<p clas="small" v-else>
																			Unassigned
																		</p>
																	</div><!-- end col -->
																</div><!-- end row -->
																<div class="row">
																	<div class="col-sm-12">
																		<label>Rooming Cost</label>
																		<p class="small">
																			<span v-for="cost in member.costs.data">{{cost.name}}
																			<span v-if="!$last && member.costs.data.length > 1">, </span></span>
																		</p>
																	</div>
																</div>
															</div><!-- end panel-body -->
														</div>
														<!-- </div> -->
														<div class="panel-footer" style="background-color: #ffe000;" v-if="member.companions && member.companions.data.length && companionsPresentRoom(member, currentRoom)">
															<i class=" fa fa-info-circle"></i> I have {{member.present_companions}} companions not in this room.
															<button type="button" class="btn btn-xs btn-default-hollow pull-right" @click="addCompanionsToRoom(member, currentRoom)"><i class="fa fa-plus-circle"></i>
															<span class="hidden-xs">Companions</span></button>
														</div>
													</div>
												</div><!-- end row -->
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
									<button class="btn btn-primary btn-xs" :disabled="isLocked" type="button" @click="openNewRoomModel">Add Room</button>
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
								<div class="col-xs-12">
									<hr class="divider inv">
								</div>
							</form>

							<div class="row">
								<div class="col-xs-12" v-if="currentPlan">
									<template v-if="currentRooms.length">
										<!-- List group List-->
										<div class="list-group">
											<div @click="setActiveRoom(room)" class="list-group-item" :class="{ 'active': currentRoom && currentRoom.id === room.id}" v-for="room in currentRoomsOrdered" style="cursor: pointer;">
												{{(room.label ? (room.label + ' &middot; ' + room.type.data.name) : room.type.data.name) | capitalize}}
												<span v-if="getRoomLeader(room)"> ({{ getRoomLeader(room).surname }}, {{ getRoomLeader(room).given_names ? getRoomLeader(room).given_names[0].toUpperCase() + getRoomLeader(room).given_names.slice(1) : '' }}) </span>
												<span v-if="room.type.data.rules.occupancy_limit == room.occupants_count" class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">Full</span>
												<span v-if="room.type.data.rules.occupancy_limit > room.occupants_count" class="badge text-uppercase" style="font-size:10px;line-height:1.4;letter-spacing: 0;">{{room.occupants_count}}</span>
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<pagination :pagination="roomsPagination" :callback="getRooms"></pagination>
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
				</div>

				<!-- Teams Select & Members List -->
				<div class="col-sm-4">
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
								<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.role !== null" @click="reservationFilters.role = null" >
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
								<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.age[0] != 0" @click="reservationFilters.age[0] = 0" >
									Min. Age
									<i class="fa fa-close"></i>
								</span>
								<span style="margin-right:2px;" class="label label-default" v-show="reservationFilters.age[1] != 120" @click="reservationFilters.age[1] = 120" >
									Max. Age
									<i class="fa fa-close"></i>
								</span>
							</div>
						</div>
					</form>
					<hr class="divider sm inv">
					<div class="panel-group" id="reservationsAccordion" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default" v-for="(reservation, reservationIndex) in reservations">
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
															{{ reservation.surname ? reservation.surname[0].toUpperCase() + reservation.surname.slice(1) : '' }}, {{ reservation.given_names ? reservation.given_names[0].toUpperCase() + reservation.given_names.slice(1) : '' }}</a></h6>
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
													<li class="dropdown-header">Assign To Room</li>
													<li role="separator" class="divider"></li>
													<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(reservation, true, currentRoom)">{{(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize}} as leader</a></li>
													<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(reservation, false, currentRoom)" v-text="(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize"></a></li>
													<li v-if="!currentRoom"><a @click=""><em>Please select a room first.</em></a></li>
												</ul>
											</dropdown>
											<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#reservationsAccordion" :href="'#reservationItem' + reservationIndex" aria-expanded="true" aria-controls="collapseOne">
												<i class="fa fa-angle-down"></i>
											</a>
										</div>
									</div>
								</h4>
							</div>
							<div :id="'reservationItem' + reservationIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<label>Gender</label>
											<p class="small">{{reservation.gender ? reservation.gender[0].toUpperCase() + reservation.gender.slice(1) : ''}}</p>
										</div>
										<div class="col-sm-4">
											<label>Marital Status</label>
											<p class="small">{{reservation.status ? reservation.status[0].toUpperCase() + reservation.status.slice(1) : ''}}</p>
										</div>
										<div class="col-sm-4">
											<label>Age</label>
											<p class="small">{{reservation.age}}</p>
										</div>
										<div class="col-sm-6">
											<label>Travel Group</label>
											<p class="small">{{reservation.trip.data.group.data.name}}</p>
										</div><!-- end col -->
										<div class="col-sm-6">
											<label>Designation</label>
											<p class="small">
												{{ reservation.arrival_designation ? reservation.arrival_designation[0].toUpperCase() + reservation.arrival_designation.slice(1) : '' }}
											</p>
										</div>
										<div class="col-sm-12">
											<label>Rooming Cost</label>
											<p class="small">
												<span v-for="cost in reservation.costs.data">{{cost.name}}
												<span v-if="!$last && reservation.costs.data.length > 1">, </span></span>
											</p>
										</div>
										<div class="col-sm-12">
											<label>Companions</label>
											<ul class="list-unstyled" v-if="reservation.companions.data.length">
												<li v-for="companion in reservation.companions.data">
													<i :class="getGenderStatusIcon(companion)"></i>
													{{ companion.surname ? companion.surname[0].toUpperCase() + companion.surname.slice(1) : '' }}, {{ companion.given_names ? companion.given_names[0].toUpperCase() + companion.given_names.slice(1) : '' }} <span class="text-muted">({{ companion.relationship ? companion.relationship[0].toUpperCase() + companion.relationship.slice(1) : '' }})</span>
												</li>
											</ul>
											<p class="small" v-else>None</p>
										</div><!-- end col -->
										<div class="col-sm-6">
											<label>Squad Groups</label>
											<p class="small" v-if="reservation.squads.data.length">
												<span v-for="squad in reservation.squads.data">{{squad.callsign}} <span v-if="squad.team">({{ squad.team.data.callsign }})</span><span v-if="!$last && reservation.squads.data.length > 1">, </span></span>
											</p>
											<p clas="small" v-else>
												Unassigned
											</p>
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
						<pagination :pagination="reservationsPagination" :callback="searchReservations"></pagination>
					</div>
				</div>
			</template>
			<template v-else>
				<p style="margin-top:8px;" class="text-center text-muted"><em>Please select a rooming plan to get started</em></p>
			</template>

			<!-- Modals -->
			<modal title="Create a new Plan" small ok-text="Create" :callback="newPlan" :value="showPlanModal" @closed="showPlanModal=false">
				<div slot="modal-body" class="modal-body">

						<form id="PlanCreateForm">
							<div class="form-group" :class="{'has-error': $PlanCreate.planname.invalid}">
								<label for="createPlanCallsign" class="control-label">Plan Name</label>
								<input @keydown.enter.prevent="newPlan" type="text" class="form-control" id="createPlanCallsign" placeholder="Miami Rooming, etc." name="planname="['required']" v-model" v-validate="selectedPlan.name">
							</div>
						</form>

				</div>
			</modal>
			<modal :title="roomModalEditMode? 'Edit Room' : 'Create a new Room'" small :ok-text="roomModalEditMode?'Update':'Create'" :callback="newRoom" :value="showRoomModal" @closed="showRoomModal=false">
				<div slot="modal-body" class="modal-body" v-if="selectedRoom">

						<form id="RoomCreateForm">
							<div class="form-group" :class="{'has-error': $RoomCreate.roomtype.invalid}" v-if="!roomModalEditMode">
								<label for="" class="control-label">Type</label>
								<select class="form-control" v-model="selectedRoom.type" name="roomtype="['required']" @change" v-validate="selectedRoom.room_type_id = selectedRoom.type.id">
									<option :value="type" v-for="type in roomTypes">{{type.name ? type.name[0].toUpperCase() + type.name.slice(1) : ''}}</option>
								</select>
								<hr class="divider sm">
								<div v-if="selectedRoom.type" class="">
									<template  v-for="(key, value) in selectedRoom.type.rules">
										<label v-text="key | underscoreToSpace ? underscoreToSpace[0].toUpperCase() + underscoreToSpace.slice(1) : ''"></label>
										<p class="small" v-text="value ? value[0].toUpperCase() + value.slice(1) : ''"></p>
									</template>
								</div>
							</div>
							<div class="form-group">
								<label for="roomname" class="control-label">Room Name (Optional)</label>
								<input @keydown.enter.prevent="" type="text" class="form-control" id="roomname"
								       v-model="selectedRoom.label" placeholder="Men 1, Women 2, Name Family or Married 1">
							</div>
						</form>

				</div>
			</modal>
			<modal title="Delete Room" small ok-text="Delete" :callback="deleteRoom" :value="showRoomDeleteModal" @closed="showRoomDeleteModal=false">
				<div slot="modal-body" class="modal-body">
					<p v-if="selectedRoom">
						Are you sure you want to delete room: "{{selectedRoom.label}}" ?
					</p>
				</div>
			</modal>
			<modal title="Delete Rooming Plan" small ok-text="Delete" :callback="deletePlan" :value="showPlanDeleteModal" @closed="showPlanDeleteModal=false">
				<div slot="modal-body" class="modal-body">
					<p v-if="currentPlan">
						Are you sure you want to delete plan: "{{currentPlan.name}}" ?
					</p>
				</div>
			</modal>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import reservationsFilters from '../filters/reservations-filters.vue';
    import utilities from '../utilities.mixin';
    export default{
        name: 'rooming-manager',
        components: {vSelect, reservationsFilters},
	    mixins: [utilities],
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
                dueOptions: [],
                teamMembersSearch: '',
                roomsSearch: '',
                showMembersFilters: false,
                showReservationsFilters: false,
				membersFilters: {
                    gender: '',
                    hasCompanions: null,
                    status: '',
					role: null,
                    designation: '',
				},
                reservationFilters: {
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: null,
                    designation: '',
                    due: '',
                    dueName: '',
                    dueStatus: '',
                    age: [0, 120]
                },
                groupsOptions:[],

	            // modal vars
	            showPlanModal: false,
                showPlanDeleteModal: false,
                showRoomDeleteModal: false,
                selectedPlan: {
                    name: '',
	                rooms: []
                },
	            showRoomModal: false,
	            roomModalEditMode: false,
                selectedRoom: {
                    room_type_id: null,
                    type: null,
	                label: '',
                    occupants: [],
                },
                storageName: 'TravelGroupRooming',
	            RoomingPlansResource: this.$resource('rooming/plans{/plan}{/path}{/pathId}'),
	            RoomingRoomsResource: this.$resource('rooming/rooms{/room}{/path}{/pathId}'),
            }
        },
	    watch: {
            currentPlan(val) {
                this.updateConfig();
                this.currentRoom = null;
                this.getRooms(val);
                this.searchReservations();
                this.getTeams();
            },
            currentRoom: {
                handler(val, oldVal) {
                    if (val && (!oldVal || val.id !== oldVal.id))
                        this.getOccupants();
                    this.searchReservations();
                    this.getTeams();
                    this.updateConfig();
                },
	            deep: true
            },
            roomsSearch(val) {
                this.roomsPagination.current_page = 1;
                this.getRooms();
            },
            reservationsSearch(val) {
                this.reservationsPagination.current_page = 1;
                this.searchReservations();
            },
            /*reservationFilters: {
                handler: (val) =>  {
                    // using the handler instead of a separate watcher
                    val.due = this.reservationFilters.dueStatus ? (this.reservationFilters.dueName + '|' + this.reservationFilters.dueStatus) : this.reservationFilters.dueName;

                    this.reservationsPagination.current_page = 1;
                    this.searchReservations();
                },
                deep: true
            },*/
		    teamMembersSearch(val) {
                this.getTeams();
            },
            membersFilters: {
                handler() {
                    this.getTeams();
                },
	            deep: true
            },
            showRoomModal(val, oldVal) {
                if (!val && oldVal) {
                    this.roomModalEditMode = false;
                    this.selectedRoom = null;
                }
            }

        },
	    computed: {
            currentRoomOccupantsOrdered() {
                return _(this.currentRoom.occupants).chain().sortBy('surname').sortBy((occupant) => {
					return occupant.room_leader;
                }).value();
            },
            currentRoomsOrdered() {
                return _.sortBy(this.currentRooms, 'label');
            },
            isLocked(){
                return !this.isAdminRoute && this.currentPlan && this.currentPlan.locked;
            },
            planOccupants() {
                let excludedIDs = [];
                if (_.isObject(this.currentPlan) && this.currentRooms.length) {
                    _.each(this.currentRooms, (room) => {
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
	                _.each(this.currentTeam.squads.data, (squad) => {
						_.each(squad.members.data, (member) => {
						    if (!_.contains(self.planOccupants, member.id))
								members.push(member);
	                    });
	                });

                return members;
            },
		    currentRoomHasLeader() {
                if (this.currentRoom && this.roomHasLeader(this.currentRoom)) {
                    return this.getRoomLeader(this.currentRoom);
                } else return false;
		    },
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
            resetMembersFilter() {
                this.membersFilters = {
                    gender: '',
                    hasCompanions: null,
                    status: '',
                    role: null,
                    designation: '',
                };
            },
            resetReservationFilter() {
                this.$root.$emit('reservations-filters:reset');
                this.reservationFilters = {
                    type: '',
                    groups: [],
                    gender: '',
                    status: '',
                    hasCompanions: null,
                    role: null,
                    designation: ''
                };
            },
            getRoomLeader(room) {
                let occupants = room.occupants.data || room.occupants;
                let leader = _.findWhere(occupants, { room_leader: true });
                return leader || false;
            },
            updateConfig(){
                window.localStorage[this.storageName] = JSON.stringify({
                    currentPlan: this.currentPlan.id,
                });
            },
            promoteToLeader(occupant) {
                let data = {
                    reservation_id: occupant.id,
                    room_leader: true,
                };
                this.$http.put('rooming/rooms/' + this.currentRoom.id + '/occupants/' + occupant.id, data)
                    .then((response) => {
						return occupant.room_leader = response.data.data.room_leader;
                    })
            },
            demoteToOccupant(occupant) {
                let data = {
                    reservation_id: occupant.id,
                    room_leader: false,
                };
                this.$http.put('rooming/rooms/' + this.currentRoom.id + '/occupants/' + occupant.id, data)
	                .then((response) => {
                        return occupant.room_leader = response.data.data.room_leader;
                    });
            },
            setActiveRoom(room) {
                this.currentRoom = room;
            },
            roomHasLeader(room) {
                return _.some(room.occupants, (occupant) => {
	                return !!occupant.room_leader;
                });
            },
            companionsPresentRoom(member, room) {
                let memberIds = _.filter(_.pluck(room.occupants, 'id'), function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, (id) => {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                return member.present_companions = companionIds.length - presentIds.length;
            },
            addCompanionsToRoom(member, room) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This rooming plan is currently locked');
                    return;
                }

                let memberIds = _.filter(_.pluck(room.occupants, 'id'), function (id) { return id !== member.id; });
                let companionIds = _.pluck(member.companions.data, 'id');
                let presentIds = [];
                _.each(memberIds, (id) => {
                    if (_.contains(companionIds, id))
                        presentIds.push(id);
                });
                let notPresentIds = _.difference(companionIds, presentIds);

                // Check for limitations
                // Available Space
                let availableSpace = room.type.data.rules.occupancy_limit - room.occupants.length;
                if (availableSpace < companionIds.length) {
                    this.$root.$emit('showError', 'There isn\'t enough space in this room for all ' + (companionIds.length + 1) + ' companions.');
                    return;
                }

                // Detect companions in other groups and remove them
                if (member.present_companions_team) {
                    _.each(this.currentRooms, (roomA) => {
                        let companionObj;
                        _.each(notPresentIds, (companionId) => {
                            companionObj = _.findWhere(roomA.occupants, {id: companionId});
                            if (companionObj) {
                                this.removeFromRoom(companionObj, roomA);
                            }
                        });

                    });
                }

                // package for mass assignment
                _.each(companionIds, (compId) => {
                    this.addToRoom({ id: compId }, false, room)
                });
            },
            removeFromRoom(occupant, room) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This rooming plan is currently locked');
                    return;
                }

                return this.$http.delete('rooming/rooms/' + room.id + '/occupants/' + occupant.id).then((response) => {
                    room.occupants = _.reject(room.occupants, (member) => {
                        return member.id === occupant.id;
                    });
                    room.occupants_count--;
                    this.currentPlan.occupants_count--;
                    return response.data;
                });
            },
            addToRoom(occupant, leader, room) {
                if (this.isLocked) {
                    this.$root.$emit('showInfo', 'This rooming plan is currently locked');
                    return;
                }

                if (room.occupants_count >= room.type.data.rules.occupancy_limit) {
                    this.$root.$emit('showInfo', room.label +' currently has the max number of occupants');
                    return;
                }

                if (leader && this.roomHasLeader(room)) {
                    //this.$root.$emit('showInfo', 'This room already has a leader');
                    //return;
                    let oldLeader = this.getRoomLeader(room);
                    this.demoteToOccupant(oldLeader);
                }

                let data = {
                    reservation_id: occupant.id,
                    room_leader: leader,
                };

                return this.$http.post('rooming/rooms/' + room.id + '/occupants', data,  { params: { include: 'companions,squads.team,costs:type(optional)' } }).then((response) => {
	                let occupants = response.data.data;
	                room.occupants = occupants;
                    room.occupants_count = occupants.length;
                    this.currentPlan.occupants_count++;
                }, (response) =>  {
	                this.$root.$emit('showError', response.data.message)
                });
            },
            searchReservations(){
                let params = {
                    include: 'trip.campaign,trip.group,user,companions,squads.team,costs:type(optional)',
					campaign: this.campaignId,
                    current: true,
                    search: this.reservationsSearch,
					noRoom: 'plans|' + this.currentPlan.id,
					per_page: this.reservationsPerPage,
					page: this.reservationsPagination.current_page,
                };

                params = _.extend(params, this.reservationFilters);
                params = _.extend(params, {
                    age: [this.ageMin, this.ageMax]
                });

                if (_.isObject(this.reservationFilters.role)) {
                    params.role = this.reservationFilters.role.value;
                }
                params.groups = _.pluck(this.currentPlan.groups.data, 'id');
	            if (this.reservationFilters.groups.length)
                    params.groups = _.union(params.groups, _.pluck(this.reservationFilters.groups, 'id'));

	                // this.$refs.spinner.show();
                return this.$http.get('reservations', { params: params, before: function(xhr) {
                    if (this.lastReservationRequest) {
                        this.lastReservationRequest.abort();
                    }
                    this.lastReservationRequest = xhr;
                } }).then((response) => {
                    this.reservations = response.data.data;
                    this.reservationsPagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }, (error) =>  {
                    // this.$refs.spinner.hide();
                    //TODO add error alert
                });
            },
            getRoomTypes(){
                return this.$http.get('rooming/types?campaign='+this.campaignId).then((response) => {
                        return this.roomTypes = response.data.data;
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
            getPlans(){
                let params = {
                    page: this.plansPagination.current_page,
                };
	                return this.$http.get('rooming/plans', { params: params }).then((response) => {
                        this.plansPagination = response.data.meta.pagination;
                        return this.plans = response.data.data;
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
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
            getAllRooms(plans){
                let params = {
                    plans: plans,
	                include: 'type',
                    // page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/rooms', { params: params }).then((response) => {
                        console.log(response.data.data)
//                        this.plansPagination = response.data.meta.pagination;
//                        return this.plans = response.data.data;
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
            getRooms(plan){
                plan = plan || this.currentPlan;
                let params = {
                    plans: new Array(plan.id),
	                include: 'type,occupants.companions,occupants.squads.team',
	                search: this.roomsSearch,
	                page: this.roomsPagination.current_page,
	                per_page: 25,
                };
                return this.$http.get('rooming/rooms', { params: params })
	                .then((response) => {
		                if (plan.id === this.currentPlan.id) {
		                    this.roomsPagination = response.data.meta.pagination
                            return this.currentRooms = response.data.data;
                        }
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
            getOccupants(){
                let params = {
                    // plans: new Array(this.currentPlan.id),
	                 include: 'companions,squads.team,costs:type(optional)',
                    // page: this.plansPagination.current_page,
                };
                return this.$http.get('rooming/rooms/' + this.currentRoom.id + '/occupants', { params: params }).then((response) => {
                        this.currentRoom.occupants = response.data.data
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
            getCosts(){
                return this.$http.get('costs', { params: {
                    'assignment': 'trips',
                    'per_page': 100,
                    'unique': true
                }}).then((response) => {
                    this.dueOptions = _.uniq(_.pluck(response.data.data, 'name'));
                });
            },
            getTeams(){
                let currentSelection = _.extend({}, this.currentTeam);

                let params = {
                    include: 'squads.members.companions,type',
                    page: this.teamsPagination.current_page,
                };

                if (this.isAdminRoute) {
                    params.campaign = this.campaignId;
//                    params.group = this.groupId;
                } else {
                    params.campaign = this.campaignId;
                    params.group = this.groupId;
                }

                if (_.isObject(this.currentPlan) && this.currentPlan.id) {
                    params.include += ',squads.members:noRoom(plans|' + this.currentPlan.id + ')';
                }

                if (this.teamMembersSearch) {
                    params.include += ':search(' + this.teamMembersSearch + ')';
                }

                // membersFilters
	            if (this.membersFilters.gender)
                    params.include += ':gender(' + this.membersFilters.gender + ')';

	            if (this.membersFilters.hasCompanions)
                    params.include += ':hasCompanions(' + this.membersFilters.hasCompanions + ')';

	            if (this.membersFilters.status)
                    params.include += ':status(' + this.membersFilters.status + ')';

	            if (this.membersFilters.role)
                    params.include += ':role(' + this.membersFilters.role.value + ')';

	            if (this.membersFilters.designation)
                    params.include += ':designation(' + this.membersFilters.designation + ')';

                if (this.membersFilters.type)
                    params.include += ':type(' + this.membersFilters.type + ')';

                return this.$http.get('teams', { params: params }).then((response) => {
                        this.teamsPagination = response.data.meta.pagination;
                        this.teams = response.data.data;
                        if (currentSelection) {
                            this.currentTeam = _.findWhere(this.teams, { id: currentSelection.id});
                        }
                        return this.teams;
                    }, (response) =>  {
                        console.log(response);
                        return response.data.data;
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
                    return this.$http.post('rooming/plans', this.selectedPlan).then((response) => {
                        let plan = response.data.data;
                        this.plans.push(plan);
                        this.showPlanModal = false;
                        this.$root.$emit('select-options:update', plan.id, 'id');
                        return this.currentPlan = plan;
                    }, (response) =>  {
                        console.log(response);
                        this.$root.$emit('showError', response.data.message);
                        return response.data.data;
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
            editRoom(room) {
	            this.roomModalEditMode = true;
                this.selectedRoom = room;
                this.showRoomModal = true;
            },
	        newRoom() {
                if (this.$RoomCreate.valid) {
                    if (this.roomModalEditMode) {
                        return this.RoomingPlansResource.update({
	                        plan: this.currentPlan.id,
	                        path: 'rooms',
	                        pathId: this.selectedRoom.id,
	                        include: 'type,occupants'
                        }, this.selectedRoom).then((response) => {
                            let room = response.data.data;
                            this.showRoomModal = false;
                            this.currentRoom = _.extend(this.currentRoom, room)
                            this.getRooms();
                        });

                    } else {
                        return this.$http.post('rooming/plans/' + this.currentPlan.id + '/rooms', this.selectedRoom, {params: {include: 'type,occupants'}}).then((response) => {
                            let room = response.data.data;
                            this.showRoomModal = false;
                            return this.getRooms().then((rooms) => {
                                if (room)
                                    return this.currentRoom = _.findWhere(this.currentRooms, { id: room.id })
                            });
                        }, (response) =>  {
                            console.log(response);
                            this.$root.$emit('showError', response.data.message);
                            return response.data.data;
                        });
                    }
                } else {
                    this.$root.$emit('showError', 'Please select a room type');
                }
            },
            openDeleteRoomModal(room) {
                this.selectedRoom = room;
                this.showRoomDeleteModal = true;
            },
            openDeletePlanModal() {
                this.showPlanDeleteModal = true;
            },
            deletePlan() {
                let plan = _.extend({}, this.currentPlan);
                this.$http.delete('rooming/plans/' + plan.id).then((response) => {
                    this.showPlanDeleteModal = false;
                    this.$root.$emit('showInfo', plan.name + ' Deleted!');
                    this.plans = _.reject(this.plans, (obj) => {
	                    return plan.id === obj.id;
                    });
                    this.currentplan = this.plans.length ? this.plans[0] : null;
                });

            },
            deleteRoom() {
                let room = _.extend({}, this.selectedRoom);
                this.$http.delete('rooming/plans/' + this.currentPlan.id + '/rooms/' + room.id).then((response) => {
                    this.$root.$emit('showInfo', room.label + ' Deleted!');
                    this.showRoomDeleteModal = false;
                    if (this.currentRoom && room.id === this.currentRoom.id)
                        this.currentRoom = null;
                    this.selectedRoom = null;
					this.getRooms();
					this.searchReservations();
                })
            },
            changePlan() {
                this.$dispatch('rooming-wizard:plan-selection');
            }
        },
	    activate(done){
            this.currentPlan = this.$parent.currentPlan;
            done();
	    },
        mounted(){
            let promises = [];
            if (this.isAdminRoute) {
                this.searchReservations();
            } else {

            }

            /*promises.push(this.getPlans().then((plans) => {
                //let pIds = _.pluck(plans, 'id');
	            //this.getAllRooms(pIds);
            }));*/
            promises.push(this.getRoomTypes());
            promises.push(this.getTeams());
            promises.push(this.getRoles());
            promises.push(this.getCosts());
            Promise.all(promises).then((values) => {
                this.startUp = false;

                // load view state
                if (window.localStorage[this.storageName]) {
                    let config = JSON.parse(window.localStorage[this.storageName]);
                    if (config.currentPlan) {
                        let plan = _.findWhere(this.plans, { id: config.currentPlan});
                        if (plan) {
                            this.currentPlan = plan;
                            this.$root.$emit('select-options:select', plan.id, 'id');
                        }
                    }
                }
            });

            this.$root.$on('campaign-scope', (val) =>  {
                this.campaignId = val ? val.id : '';
                this.$dispatch('rooming-wizard:plan-selection');
            });

            this.$root.$on('plan-scope', (val) =>  {
                this.currentPlan = val || null;
            });

            this.$root.$on('create-plan', (val) =>  {
                this.openNewPlanModal();
            });

        }
    }
</script>