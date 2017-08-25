<template>
	<div class="row" style="position:relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<mm-aside :show="showRegionsFilters" @open="showRegionsFilters=true" @close="showRegionsFilters=false" placement="left" header="Region Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">

				<div class="form-group" v-if="isAdminRoute">
					<label>Country</label>
					<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250" :on-search="getCountries"
					          v-model="regionsFilters.country" :options="UTILITIES.countries" label="name"
					          placeholder="Filter Countries"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetRegionFilter"><i class="fa fa-times"></i> Reset Region Filters</button>
			</form>
		</mm-aside>
		<mm-aside :show="showSquadsFilters" @open="showSquadsFilters=true" @close="showSquadsFilters=false" placement="left" header="Squad Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">

				<div class="form-group">
					<label for="" class="control-label">Type</label>
					<select class="form-control" v-model="squadsFilters.type">
						<option value="">-- Select --</option>
						<option :value="type.name" v-for="type in squadTypes">{{ type.name|capitalize }}</option>
					</select>
				</div>

				<div class="form-group">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
					          v-model="squadsFilters.group" :options="groupsOptions" label="name"
					          placeholder="Filter by Group"></v-select>
				</div>
				<div class="form-group">
					<label>Region</label>
					<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250"
					          v-model="squadsFilters.region" :options="regions" label="name"
					          placeholder="Filter by Region"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetSquadFilter"><i class="fa fa-times"></i> Reset Squad Filters</button>
			</form>
		</mm-aside>

		<div class="col-sm-8">

			<!-- Active Region -->
			<template v-if="currentRegion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<h5>{{ currentRegion.name|capitalize }} <span v-if="currentRegion.callsign">({{currentRegion.callsign}})</span><span class="small">&middot; Details</span></h5>
							</div>
							<div class="col-sm-6 text-right">
								<h5>{{currentRegion.country.name}}</h5>
							</div>
						</div>

					</div><!-- end panel-heading -->
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12">
								<label>Region Squads <span v-if="currentRegion.teams" class="badge badge-primary" v-text="currentRegion.teams.data.length"></span></label>
								<hr class="divider sm">
								<template v-if="currentRegion.teams && currentRegion.teams.data.length">
									<div class="list-group">
										<div class="list-group-item" v-for="team in currentRegion.teams.data">
											<div class="row list-group-item-heading">
												<div class="col-xs-6">
													<a :href="'/admin/campaigns/' + campaignId + '/squads?squad=' + team.id" target="_blank">{{ team.callsign|capitalize }}</a>
													<span class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">{{team.type.data.name|capitalize}}</span>
													<span v-if="team.locked" style="padding:3px 10px;font-size:10px;line-height:1.4;" class="badge text-uppercase"><i class="fa fa-lock"></i> Locked</span>
												</div>
												<div class="col-xs-6 text-right">
													<i class="fa fa-users"></i> {{ team.members_count || 0 }}
													<a class="btn btn-xs btn-primary-hollow" @click="removeFromRegion(team)">
														<i class="fa fa-minus"></i>
													</a>
												</div>
											</div>
											<p class="list-group-item-text small" v-if="team.groups.data.length">
												<span v-for="group in team.groups.data">{{group.name}}<span v-if="!$last && team.groups.data.length > 1">, </span></span>
											</p>
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
								<input type="text" class="form-control" v-model="regionsSearch" @keyup="debouncedRegionsSearch" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
						</div><!-- end col -->
						<div class="form-group col-xs-4">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showRegionsFilters = true">
								<i class="fa fa-filter"></i> Filter
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
							<div class="panel panel-default" v-for="(region, regionIndex) in regionsOrdered">
								<div class="panel-heading" role="tab" id="headingOne">
									<h5 class="panel-title">
										<div class="row">
											<div class="col-xs-9">
												<a role="button" @click="makeCurrentRegion(region)">
													<h4>{{ region.name|capitalize }}</h4>
													<p>
					                                    <span v-if="region.callsign">
					                                        <span class="label label-default" :style="'color: #FFF !important; background-color: ' + region.callsign">{{region.callsign|capitalize}}</span>
					                                    </span>
														<span class="small">{{ region.country.name|capitalize }}</span>
													</p>
												</a>
											</div>
											<div class="col-xs-3 text-right action-buttons">
												<span class="badge badge-danger" style="background-color: #F6323E;">
													{{region.teams && region.teams.data.length ? region.teams.data.length : 0}}
												</span>
												<dropdown type="default" btn-classes="btn btn-xs btn-primary-hollow">
													<span slot="button" class="fa fa-ellipsis-h"></span>
													<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
														<li><a @click="openRegionEditModal(region)"><i class="fa fa-pencil"></i> Edit</a></li>
														<li role="separator" class="divider"></li>
														<li><a @click="openRegionDeleteModal(region)"><i class="fa fa-trash"></i> Delete</a></li>
													</ul>
												</dropdown>
												<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#regionAccordion" :href="'#regionItem' + regionIndex" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-angle-down"></i>
												</a>
											</div>
										</div>
									</h5>
								</div>
								<div :id="'regionItem' + regionIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<label>Travel Groups In this Region</label>
									</div>
									<ul class="list-group" style="margin:3px 0;" v-if="region.teams && region.teams.data.length">
										<template v-for="team in region.teams.data">
											<template v-if="team.groups">
												<li class="list-group-item" v-for="group in team.groups.data">
													{{ group.name|capitalize }}
												</li>
											</template>
										</template>
									</ul>
									<div class="panel-body text-center" v-else>
                                        No Groups Found
                                    </div>
								</div>
							</div>
							<div class="col-xs-12 text-center">
								<pagination :pagination="regionsPagination" :callback="getRegions"></pagination>
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

		<!-- Squads List -->
		<div class="col-sm-4">
			<!-- Search and Filter -->
			<form class="form-inline row" @submit.prevent>
				<div class="form-group col-xs-8">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="squadsSearch" @keyup="debouncedSquadsSearch" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div>
				<div class="form-group col-xs-4">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="showSquadsFilters = true;">
						<i class="fa fa-filter"></i> Filter
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
							<div class="list-group-item" v-for="team in squads">
								<div class="row list-group-item-heading">
									<div class="col-xs-6">
										<a :href="'/admin/campaigns/' + campaignId + '/squads?squad=' + team.id" target="_blank">{{ team.callsign|capitalize }}</a>
										<span class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">{{team.type.data.name|capitalize}}</span>
										<span v-if="team.locked" style="padding:3px 10px;font-size:10px;line-height:1.4;" class="badge text-uppercase"><i class="fa fa-lock"></i> Locked</span>
									</div>
									<div class="col-xs-6 text-right">
										<i class="fa fa-users"></i> {{ team.members_count || 0 }}
										<a class="btn btn-xs btn-primary-hollow" @click="addToRegion(team)" :class="{ 'disabled': !this.currentRegion}">
											<i class="fa fa-plus"></i>
										</a>
									</div>
								</div>
								<p class="list-group-item-text small" v-if="team.groups.data.length">
									<span v-for="group in team.groups.data">{{group.name}}<span v-if="!$last && team.groups.data.length > 1">, </span></span>
								</p>
							</div>
						</div>
						<div class="col-xs-12 text-center">
							<pagination :pagination="squadsPagination" :callback="getSquads"></pagination>
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
		<modal :title="editRegionModal?'Edit Region' : 'Create a Region'" small :ok-text="editRegionModal?'Update' : 'Create'" :callback="createRegion" :value="showRegionModal" @closed="showRegionModal=false">
			<div slot="modal-body" class="modal-body" v-if="selectedRegion">

					<form id="RegionCreateForm" data-vv-scope="region-create">
						<div class="form-group" :class="{'has-error': errors.has('name', 'region-create')}">
							<label for="createRegionName" class="control-label">Region Name</label>
							<input @keydown.enter.prevent="createRegion" type="text" class="form-control" id="createRegionName" placeholder="" name="name" v-model="selectedRegion.name" v-validate="'required'">
						</div>
						<div class="form-group">
							<label for="createRegionCountry" class="control-label">Region Country</label>
							<v-select @keydown.enter.prevent="" class="form-control" :debounce="250" :on-search="getCountries"
							          v-model="selectedRegion.country" :options="UTILITIES.countries" label="name"
							          placeholder="Select a Country"></v-select>
						</div>
						<div class="form-group">
							<label for="createRegionCallsign" class="control-label">Region CallSign</label>
							<input type="text" class="form-control" id="createRegionCallsign" placeholder="" v-model="selectedRegion.callsign">
						</div>
					</form>

			</div>
		</modal>
		<modal title="Delete Region" small ok-text="Delete" :callback="deleteRegion" :value="showRegionDeleteModal" @closed="showRegionDeleteModal=false">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedRegion">
					Are you sure you want to delete region: "{{selectedRegion.name}}" ?
				</p>
			</div>
		</modal>
		<modal title="Create a new Room" small ok-text="Create" :callback="newRoom" :value="showRoomModal" @closed="showRoomModal=false">
			<div slot="modal-body" class="modal-body" v-if="selectedRoom">

					<form id="RoomCreateForm" data-vv-scope="room-create">
						<div class="form-group" :class="{'has-error': errors.has('roomtype', 'room-create')}">
							<label for="" class="control-label">Type</label>
							<select class="form-control" v-model="selectedRoom.type" name="roomtype=" @change="selectedRoom.room_type_id = selectedRoom.type.id" v-validate="'required'">
								<option :value="type" v-for="type in roomTypes">{{ type.name|capitalize }}</option>
							</select>
							<hr class="divider sm">
							<div v-if="selectedRoom.type" class="">
								<template  v-for="(value, key) in selectedRoom.type.rules">
									<!--<label v-text="key ? (key[0].toUpperCase() + key.slice(1)) | underscoreToSpace : ''"></label>-->
									<p class="small">{{value|capitalize}}</p>
								</template>
							</div>
						</div>
						<div class="form-group" :class="{'has-error': errors.has('roomname', 'room-create')}">
							<label for="roomname" class="control-label">Room Name (Optional)</label>
							<input @keydown.enter.prevent="" type="text" class="form-control" id="roomname" v-model="selectedRoom.label">
						</div>
					</form>

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
            },
            squadsSearch(val) {
                this.squadsPagination.current_page = 1;
            },
            regionsFilters: {
                handler(val, oldVal) {
                    this.regionsPagination.current_page = 1;
                    this.getRegions();
                },
                deep: true
            },
            squadsFilters: {
                handler(val, oldVal) {
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
			regionsOrdered() {
			    return _.sortBy(this.regions, 'name')
			}
        },
        methods: {
            debouncedRegionsSearch: _.debounce(function() {
                this.getRegions();
            }, 250),
            debouncedSquadsSearch: _.debounce(function() {
                this.getSquads();
            }, 250),
            regionFactory() {
                return {
					name: '',
                    country: this.campaignCountry,
                    country_code: this.campaignCountry.code,
                    callsign: '',
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
                    include: 'teams.groups,teams.type',
                    page: this.regionsPagination.current_page,
	                search: this.regionsSearch,
	                country: this.regionsFilters.country,
                };

                return this.RegionsResource.get(params).then((response) => {
                    this.regionsPagination = response.data.meta.pagination;
                    return this.regions = response.data.data;
                }, (response) =>  {
                    console.log(response);
                    return response.data.data;
                });
            },
	        makeCurrentRegion(region) {
                this.currentRegion = region;
	        },
            addToRegion(squad, region) {
                region = region || this.currentRegion;
	            this.$http.post('regions/' + region.id + '/teams', { ids: [squad.id] }).then((response) => {
	                region.teams.data.push(squad);
					this.getSquads();
                });
            },
            removeFromRegion(squad, region) {
                region = region || this.currentRegion;
                this.$http.delete('regions/' + region.id + '/teams/' + squad.id).then((response) => {
                    region.teams.data = _.reject(region.teams.data, (obj) => {
	                    return obj.id === squad.id;
                    });
                    this.getSquads();
                });
            },
            getTeamTypes() {
                return this.$http.get('teams/types', { params: { campaign: this.campaignId } }).then((response) => {
                    return this.squadTypes = response.data.data;
                }, (error) =>  {
                    console.log(error);
                    return error;
                });
            },
            getSquads(){
                let params = {
                    include: 'type,groups',
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

                return this.$http.get('teams', { params: params}).then((response) => {
                        this.squadsPagination = response.data.meta.pagination;
                        return this.squads = response.data.data;
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
                this.$validator.validateAll('region-create').then(result => {
                    if (!result) {
                        return;
                    }
                    if (this.editRegionModal) {
                        return this.updateRegion();
                    }

	                this.selectedRegion.country_code = this.selectedRegion.country.code;
	                delete this.selectedRegion.country;

	                if (!this.selectedRegion.callsign)
	                    delete this.selectedRegion.callsign;


                    return this.RegionsResource.post({
                        campaign: this.campaignId,
                        include: 'teams.groups',
                    }, this.selectedRegion).then((response) => {
                        let region = response.data.data;
                        this.regions.push(region);
                        this.showRegionModal = false;
                        this.$root.$emit('showSuccess', 'Region: ' + region.name + ', created successfully.');
                        return this.currentRegion = region;
                    }, (response) => {
                        console.log(response);
                        this.$root.$emit('showError', response.data.message);
                        return response.data.data;
                    });
                });
            },
            updateRegion() {
                // already validated in previous function
                this.selectedRegion.country_code = this.selectedRegion.country.code;
                delete this.selectedRegion.country;

                if (!this.selectedRegion.callsign)
                    delete this.selectedRegion.callsign;

                let data = {
                    name: this.selectedRegion.name,
                    country_code: this.selectedRegion.country_code,
                    callsign: this.selectedRegion.callsign,
                };

                this.RegionsResource.put({
                    campaign: this.campaignId,
                    region: this.selectedRegion.id,
                    include: 'teams.groups, teams.type',
                }, data).then((response) => {
                    let region = response.data.data;
                    this.showRegionModal = false;
                    this.editRegionModal = false;
                    this.selectedRegion = null;
                    this.$root.$emit('showSuccess', 'Region: ' + region.name + ', created successfully.');
	                return region;
                }, (response) => {
                    console.log(response);
                    this.$root.$emit('showError', response.data.message);
                    return response.data.data;
                });
            },
            deleteRegion() {
                let region = _.extend({}, this.selectedRegion);
                this.RegionsResource.delete({ campaign: this.campaignId, region: this.selectedRegion.id}).then((response) => {
                    this.showRegionDeleteModal = false;
                    this.$root.$emit('showInfo', region.name + ' Deleted!');
                    this.regions = _.reject(this.regions, (obj) => {
                        return region.id === obj.id;
                    });
                    this.currentRegion = this.region.length ? this.region[0] : null;
                }, (response) =>  {
                    this.$root.$emit('showError', response.data.message);
                }).then(() => {
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
                this.$validator.validateAll('room-create').then(result => {
                    if (!result) {
                        return;
                    }
                    return this.$http.post('rooming/plans/' + this.currentPlan.id + '/rooms', this.selectedRoom, {params: {include: 'type'}}).then((response) => {
                        let room = response.data.data;
                        this.showRoomModal = false;
                        //this.currentPlan.rooms.push(room);
                        this.currentRegions.push(room);
                        //_.some()
                        return this.currentRegion = room;
                    }, (response) => {
                        console.log(response);
                        return response.data.data;
                    });
                });
            },
        },
        mounted(){
            let promises = [];
            promises.push(this.getCountries());
            promises.push(this.getTeamTypes());
            promises.push(this.getRegions());
            promises.push(this.getSquads());
            promises.push(this.getGroups());
            Promise.all(promises).then((values) => {
                this.startUp = false;
            });
        }
    }
</script>