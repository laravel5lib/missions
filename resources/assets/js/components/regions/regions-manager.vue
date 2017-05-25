<template>
	<div class="row" style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<aside :show.sync="showRegionsFilters" placement="left" header="Region Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">

				<div class="form-group" v-if="isAdminRoute">
					<label>Country</label>
					<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250" :on-search="getCountries"
					          :value.sync="regionsFilters.country" :options="UTILITIES.countries" label="name"
					          placeholder="Filter Countries"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetRegionFilter"><i class="fa fa-times"></i> Reset Region Filters</button>
			</form>
		</aside>
		<aside :show.sync="showSquadsFilters" placement="left" header="Squad Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">

				<div class="form-group">
					<label for="" class="control-label">Type</label>
					<select class="form-control" v-model="squadsFilters.type">
						<option :value="">-- Select --</option>
						<option :value="type.id" v-for="type in squadTypes">{{type.name | capitalize}}</option>
					</select>
				</div>

				<div class="form-group">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
					          :value.sync="squadsFilters.group" :options="groupsOptions" label="name"
					          placeholder="Filter by Group"></v-select>
				</div>
				<div class="form-group">
					<label>Region</label>
					<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250"
					          :value.sync="squadsFilters.region" :options="regions" label="name"
					          placeholder="Filter by Region"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetSquadFilter"><i class="fa fa-times"></i> Reset Squad Filters</button>
			</form>
		</aside>

		<div class="col-sm-8">

			<!-- Active Region -->
			<template v-if="currentRegion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5>{{currentRegion.name | capitalize}} <span class="small">&middot; Details</span></h5>
					</div><!-- end panel-heading -->
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-5">
								<div class="row">
									<div class="col-sm-8">
										<label>Country</label>
									</div><!-- end col -->
									<div class="col-sm-4 text-right">
										<p class="small" style="margin:3px 0;">{{currentRegion.country.name}}</p>
									</div><!-- end col -->
								</div><!-- end row -->
								<hr class="divider sm">
								<div class="row">
									<div class="col-sm-8">
										<label>Callsign</label>
									</div><!-- end col -->
									<div class="col-sm-4 text-right">
										<p class="small" style="margin:3px 0;">{{currentRegion.callsign || 'None Set'}}</p>
									</div><!-- end col -->
								</div><!-- end row -->

							</div><!-- end col -->
							<div class="col-sm-7">
								<label>Region Squads <span v-if="currentRegion.teams" class="badge badge-primary" v-text="currentRegion.teams.data.length"></span></label>
								<hr class="divider sm">
								<template v-if="currentRegion.teams.data.length">
									<div class="list-group">
										<div class="list-group-item" v-for="squad in currentRegion.teams.data">
											<div class="row">
												<div class="col-xs-9">
													{{ squad.callsign | capitalize }} &middot; <span class="small">Members: {{ squad.members_count || 0 }}</span><br>
													<span v-if="squad.type" class="label label-info" v-text="squad.type.data.name | capitalize"></span>
													<span v-if="squad.locked" class="label label-danger"><i class="fa fa-lock"></i> Locked</span>
												</div>
												<div class="col-xs-3 text-right">
													<tooltip effect="scale" placement="left" content="Remove from Region">
														<a class="btn btn-xs btn-primary-hollow" @click="removeFromRegion(squad)">
															<i class="fa fa-minus"></i>
														</a>
													</tooltip>
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
					<p style="margin-top:8px;" class="text-center text-muted"><em>Select a region to view details.</em></p>
				</div>

			</template>

			<div class="panel panel-default">
				<div class="panel-body">
					<!-- Search and Filter Regions -->
					<form class="form-inline row">
						<div class="form-group col-xs-8">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="regionsSearch" debounce="300" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-xs-4">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showRegionsFilters = true">
								<i class="fa fa-filter"></i>
							</button>
						</div>
						<div class="col-xs-12 text-right">
							<hr class="divider sm inv">
							<button class="btn btn-primary btn-sm" type="button" @click="openRegionModal">Create a Region</button>
						</div>
						<div class="col-xs-12">
							<hr class="divider inv">
						</div>
					</form>

					<template v-if="regions.length">
						<div class="panel-group" id="regionsAccordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default" v-for="region in regions | orderBy 'name'">
								<div class="panel-heading" role="tab" id="headingOne">
									<h5 class="panel-title">
										<div class="row">
											<div class="col-xs-9">
												<a role="button" @click="makeCurrentRegion(region)">
													{{ region.name | capitalize }} <span class="small">&middot; {{ region.teams && region.teams.data.length ? region.teams.data.length : 0 }} Squads</span><br>
													<label>{{ region.country.name }}</label>
												</a>
											</div>
											<div class="col-xs-3 text-right action-buttons">
												<dropdown type="default">
													<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
														<span class="fa fa-ellipsis-h"></span>
													</button>
													<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
														<li><a @click="openRegionEditModal(region)"><i class="fa fa-pencil"></i> Edit</a></li>
														<li role="separator" class="divider"></li>
														<li><a @click="openRegionDeleteModal(region)"><i class="fa fa-trash"></i> Delete</a></li>
													</ul>
												</dropdown>
												<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#regionAccordion" :href="'#regionItem' + $index" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-angle-down"></i>
												</a>
											</div>
										</div>
									</h5>
								</div>
								<div :id="'regionItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
												<label>Travel Groups</label>
											</div><!-- end col -->
											<div class="col-sm-6">
												<ul style="margin:3px 0;" v-if="region.teams && region.teams.data.length">
													<template v-for="team in region.teams.data">
														<template v-if="team.groups">
															<li class="small" v-for="group in team.groups.data">
																{{ group.name | capitalize }}
															</li>
														</template>

													</template>
												</ul>
												<p class="small" v-else>None</p>
											</div><!-- end col -->
										</div><!-- end row -->
										<hr class="divider sm">
									</div><!-- end panel-body -->
								</div>
							</div>
						</div>
					</template>
					<template v-else>
						<hr class="divider inv">
						<p class="text-center text-italic text-muted"><em>Create a region to begin.</em></p>
					</template>
				</div>
			</div>
		</div>

		<!-- Regions Select & Squads List -->
		<div class="col-sm-4">
			<!-- Search and Filter -->
			<form class="form-inline row" @submit.prevent>
				<div class="form-group col-xs-8">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="squadsSearch" debounce="300" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div>
				<div class="form-group col-xs-4">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="showSquadsFilters = true;">
						<i class="fa fa-filter"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider sm">
				</div>
			</form>
			<div class="row">
				<div class="col-xs-12">
					<template v-if="squads.length">
						<div class="list-group">
							<div class="list-group-item" v-for="squad in squads">
								<div class="row">
									<div class="col-xs-9">
										{{ squad.callsign | capitalize }} &middot; <span class="small">Members: {{ squad.members_count || 0 }}</span><br>
										<span class="label label-info" v-text="squad.type.data.name | capitalize"></span>
										<span v-if="squad.locked" class="label label-danger"><i class="fa fa-lock"></i> Locked</span>
									</div>
									<div class="col-xs-3 text-right">
										<tooltip effect="scale" placement="left" :content="!this.currentRegion ? 'Select a Region' : 'Add to Region'">
											<a class="btn btn-xs btn-primary-hollow" @click="addToRegion(squad)" :class="{ 'disabled': !this.currentRegion}">
												<i class="fa fa-plus"></i>
											</a>
										</tooltip>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 text-center">
							<pagination :pagination.sync="squadsPagination" :callback="getSquads"></pagination>
						</div>
					</template>
					<template v-else>
						<hr class="divider inv">
						<p class="text-center text-italic text-muted"><em>No Squads created yet. Create one to get started!</em></p>
						<!--<hr class="divider inv">-->
						<!--<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewSquadModel">Create A Squad</a></p>-->
					</template>
				</div>
			</div>
		</div>

		<!-- Modals -->
		<modal title="Create a Region" small :ok-text="editRegionModal?'Update' : 'Create'" :callback="createRegion" :show.sync="showRegionModal">
			<div slot="modal-body" class="modal-body" v-if="selectedRegion">
				<validator name="RegionCreate">
					<form id="RegionCreateForm">
						<div class="form-group" :class="{'has-error': $RegionCreate.name.invalid}">
							<label for="createPlanCallsign" class="control-label">Region Name</label>
							<input @keydown.enter.prevent="createRegion" type="text" class="form-control" id="createRegionName" placeholder="" v-validate:name="['required']" v-model="selectedRegion.name">
						</div>
						<div class="form-group">
							<label for="createPlanCallsign" class="control-label">Region Country</label>
							<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250" :on-search="getCountries"
							          :value.sync="selectedRegion.country" :options="UTILITIES.countries" label="name"
							          placeholder="Select a Country"></v-select>
						</div>
						<div class="form-group">
							<label for="createPlanCallsign" class="control-label">Region CallSign</label>
							<input  type="text" class="form-control" id="createRegionName" placeholder="" v-model="selectedRegion.call_sign">
						</div>
					</form>
				</validator>
			</div>
		</modal>
		<modal title="Delete Region" small ok-text="Delete" :callback="deleteRegion" :show.sync="showRegionDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedRegion">
					Are you sure you want to delete region: "{{selectedRegion.name}}" ?
				</p>
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
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    //import $ from 'jquery';
    import vSelect from 'vue-select';
    import utilities from '../utilities.mixin'
    export default{
        name: 'regions-manager',
        components: {vSelect},
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
                required: true
            },
	        campaignCountry: {
                type: Object,
		        required: true
	        }
        },
        data(){
            return {
                startUp: true,

                squads: [],
                squadsPagination: { current_page: 1 },
                squadsSearch: '',
                showSquadsFilters: false,
                squadsFilters: {
                    group: null,
//                    campaign: null,
                    region: null,
                },
                regions: [],
                regionsPagination: { current_page: 1 },
                regionsSearch: '',
                showRegionsFilters: false,
                regionsFilters: {
                    country: null
                },

                reservations: [],
                reservationsSearch: '',
                reservationsPerPage: 10,
                reservationsPagination: { current_page: 1 },

                currentRegion: null,
                roomTypes: [],

                // Filters vars
                roomsSearch: '',
                showReservationsFilters: false,
                groupsOptions: [],
                squadTypes: [],

                // modal vars
                showRegionModal: false,
                editRegionModal: false,
                showRegionDeleteModal: false,
                selectedRegion: null,

                showRoomModal: false,
                selectedRoom: {
                    room_type_id: null,
                    type: null,
                    label: '',
                    occupants: [],
                },

	            RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}')
            }
        },
        watch: {
            regionsSearch(val) {
                this.regionsPagination.current_page = 1;
                this.getRegions();
            },
            squadsSearch(val) {
                this.squadsPagination.current_page = 1;
                this.getSquads();
            },
            regionsFilters: {
                handler: function (val) {
                    this.regionsPagination.current_page = 1;
                    this.getRegions();
                },
                deep: true
            },
            squadsFilters: {
                handler: function (val) {
                    this.squadsPagination.current_page = 1;
                    this.getSquads();
                },
                deep: true
            },
            currentRegion(val) {
	            this.getSquads();
            }
        },
        computed: {

        },
        methods: {
            regionFactory() {
                return {
					name: '',
                    country: this.campaignCountry,
                    country_code: this.campaignCountry.code,
                    call_sign: '',
                }
            },
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
            resetSquadFilter(){
                this.squadsFilters = {
                    group: null,
                    region: null,
                };
            },
            getRegions(){
                let params = {
                    campaign: this.campaignId,
                    include: 'teams.groups',
                    page: this.regionsPagination.current_page,
	                search: this.regionsSearch,
	                country: this.regionsFilters.country,
                };

                return this.RegionsResource.get(params).then(function (response) {
                    this.regionsPagination = response.body.meta.pagination;
                    return this.regions = response.body.data;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
	        makeCurrentRegion(region) {
                this.currentRegion = region;
	        },
            addToRegion(squad, region) {
                region = region || this.currentRegion;
	            this.$http.post('regions/' + region.id + '/teams', { ids: [squad.id] }).then(function (response) {
	                region.teams.data.push(squad);
					this.getSquads();
                });
            },
            removeFromRegion(squad, region) {
                region = region || this.currentRegion;
                this.$http.delete('regions/' + region.id + '/teams/' + squad.id).then(function (response) {
                    region.teams.data = _.reject(region.teams.data, function (obj) {
	                    return obj.id === squad.id;
                    });
                    this.getSquads();
                });
            },
            getTeamTypes() {
                return this.$http.get('teams/types').then(function (response) {
                    return this.squadTypes = response.body.data;
                }, function (error) {
                    console.log(error);
                    return error;
                });
            },
            getSquads(){
                let params = {
                    include: 'type',
                    page: this.squadsPagination.current_page,
                    search: this.squadsSearch,
	                campaign: this.campaignId,
                };
                params.type = this.squadsFilters.type || null;
                params.group = _.isObject(this.squadsFilters.group) ? this.squadsFilters.group.id : null;

                if (_.isObject(this.squadsFilters.region)) {
                    params.region = this.squadsFilters.region.id
                } else {
                    params.unassigned = 'region';
                }

                return this.$http.get('teams', { params: params}).then(function (response) {
                        this.squadsPagination = response.body.meta.pagination;
                        return this.squads = response.body.data;
                    },
                    function (response) {
                        console.log(response);
                        return response.body.data;
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

            // Modal Functions
            openRegionModal(){
                this.showRegionModal = true;
                this.selectedRegion = this.regionFactory();
            },
            openRegionEditModal(region){
                this.showRegionModal = true;
                this.editRegionModal = true;
                this.selectedRegion = region;
            },
            openRegionDeleteModal(region) {
                this.showRegionDeleteModal = true;
                this.selectedRegion = region;
            },
            createRegion() {
                if (this.editRegionModal)
                    return this.updateRegion();

                this.selectedRegion.country_code = this.selectedRegion.country.code;
                delete this.selectedRegion.country;

                if (!this.selectedRegion.call_sign)
                    delete this.selectedRegion.call_sign;


                return this.RegionsResource.save({ campaign: this.campaignId, include: 'teams.groups', }, this.selectedRegion).then(function (response) {
                    let region = response.body.data;
                    this.regions.push(region);
                    this.showRegionModal = false;
                    this.$root.$emit('showSuccess', 'Region: ' + region.name + ', created successfully.');
                    return this.currentRegion = region;
                }, function (response) {
                    console.log(response);
                    this.$root.$emit('showError', response.body.message);
                    return response.body.data;
                });
            },
            updateRegion() {
                this.selectedRegion.country_code = this.selectedRegion.country.code;
                delete this.selectedRegion.country;

                if (!this.selectedRegion.call_sign)
                    delete this.selectedRegion.call_sign;

                let data = {
                    name: this.selectedRegion.name,
                    country_code: this.selectedRegion.country_code,
                    call_sign: this.selectedRegion.call_sign,
                };

                this.RegionsResource.update({ campaign: this.campaignId, region: this.selectedRegion.id, include: 'teams.groups', }, data).then(function (response) {
                    let region = response.body.data;
                    this.showRegionModal = false;
                    this.editRegionModal = false;
                    this.$root.$emit('showSuccess', 'Region: ' + region.name + ', created successfully.');
                    return region;
                }, function (response) {
                    console.log(response);
                    this.$root.$emit('showError', response.body.message);
                    return response.body.data;
                });
            },
            deleteRegion() {
                let region = _.extend({}, this.selectedRegion);
                this.RegionsResource.delete({ campaign: this.campaignId, region: this.selectedRegion.id}).then(function (response) {
                    this.showRegionDeleteModal = false;
                    this.$root.$emit('showInfo', region.name + ' Deleted!');
                    this.regions = _.reject(this.regions, function (obj) {
                        return region.id === obj.id;
                    });
                    this.currentRegion = this.region.length ? this.region[0] : null;
                }, function (response) {
                    this.$root.$emit('showError', response.body.message);
                }).then(function () {
                    this.selectedRegion = null;
                });

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
                return this.$http.post('rooming/plans/' + this.currentPlan.id + '/rooms' , this.selectedRoom, { params: { include: 'type'}}).then(function (response) {
                    let room = response.body.data;
                    this.showRoomModal = false;
                    //this.currentPlan.rooms.push(room);
                    this.currentRegions.push(room);
                    //_.some()
                    return this.currentRegion = room;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
        },
        ready(){
            let promises = [];
            if (this.isAdminRoute) {

            } else {

            }

            promises.push(this.getCountries());
            promises.push(this.getTeamTypes());
            promises.push(this.getRegions());
            promises.push(this.getSquads());
            promises.push(this.getGroups());
            Promise.all(promises).then(function (values) {
                this.startUp = false;
            }.bind(this));
        }
    }
</script>