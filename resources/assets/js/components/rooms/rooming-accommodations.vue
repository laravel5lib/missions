<template>
	<div style="position: relative;">
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
		<aside :show.sync="showPlansFilters" placement="left" header="Plans Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
					          :value.sync="plansFilters.group" :options="groupsOptions" label="name"
					          placeholder="Filter by Group"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetPlansFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>
		<aside :show.sync="showRoomsFilters" placement="left" header="Rooms Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Rooming Plans</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="plansFilter" multiple :debounce="250" :on-search="getRoomingPlansFilter"
					          :value.sync="roomsFilters.plans" :options="plansOptions" label="name"
					          placeholder="Filter by Plans"></v-select>
				</div>
				<div class="form-group">
					<label>Room Type</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="typeFilter" :debounce="250" :on-search="getRoomTypes"
					          :value.sync="roomsFilters.type" :options="roomTypes" label="name"
					          placeholder="Filter by Type"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetRoomsFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>
		<div class="row">
			<div class="col-sm-7">
                <h4>Accommodations</h4>
                <hr class="divider sm">
				<template v-if="currentAccommodation">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h5>
								{{ currentAccommodation.name | capitalize }} <span> &middot; Rooms</span>
                                <span v-if="currentAccommodation.room_types.total === currentAccommodation.rooms_count.total" class="badge text-uppercase pull-right" style="padding:3px 10px;font-size:10px;line-height:1.4;">Full</span>
							</h5>
						</div>
						<div class="panel-body">
							<form class="form-inline row">
								<div class="form-group col-xs-8">
									<div class="input-group input-group-sm col-xs-12">
										<input type="text" class="form-control" v-model="roomsSearch" debounce="300" placeholder="Search rooms">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
									</div>
									<hr class="divider sm inv">
								</div><!-- end col -->
								<div class="col-xs-4">
									<button class="btn btn-default btn-sm btn-block" type="button" @click="showRoomsFilters=!showRoomsFilters">
										<i class="fa fa-filter"></i>
										Filter
									</button>
								</div>
							</form>
							<template v-if="currentAccommodationRooms.length">
								<div class="list-group">
									<div class="list-group-item"  v-for="room in currentAccommodationRooms" >
										<h5 class="list-group-item-heading">
											{{ (room.label ? (room.label + ' - ' + room.type) : room.type) | capitalize }} <span v-if="getRoomLeader(room)"> <small> ({{getRoomLeader(room).given_names}} {{getRoomLeader(room).surname}})</small></span>
											<span class="pull-right">
												<a @click="removeRoomFromAccommodation(room, currentAccommodation)" class="btn btn-xs btn-default-hollow">
													<i class="fa fa-minus"></i>
												</a>
                                            </span>

										</h5>

									</div>
								</div>
								<div class="text-center">
									<pagination :pagination.sync="currentAccommodationRoomsPagination"
									            :callback="getCurrentAccommodationRooms"
									            size="small">
									</pagination>
								</div>
							</template>
							<template v-else>
								<hr class="divider inv">
								<p class="text-center text-muted"><em>You can add plans and/rooms to this accommodation</em></p>
							</template>
						</div>
					</div>
				</template>
				<template v-else>
					<div class="well">
						<p style="margin-top:8px;" class="text-center text-muted"><em>Select an accommodation to view details.</em></p>
					</div>
				</template>

				<hr class="divider">
				<template v-if="selectRegionView">
					<div class="panel panel-default">
						<div class="panel-heading text-center"><h5>Choose a Region</h5></div>
                        <ul class="list-group">
                          <li class="list-group-item" v-for="region in regions | orderBy 'name'">
                            <span class="badge" style="background-color: white; border: 1px solid black; color: black">
                                {{region.teams_count}} Squads
                            </span>
                            <span class="badge" style="background-color: white; border: 1px solid black; color: black">
                                {{region.accommodations_count}} Accommodations
                            </span>
                            <h5>{{region.name}}</h5>
                            <span v-if="region.callsign">
                                <span class="label label-default" :style="'color: #FFF !important; background-color: ' + region.callsign" v-text="region.callsign|capitalize"></span>
                            </span>
                            <span class="small">{{ region.country.name | capitalize }}</span>
                            <button class="btn btn-xs btn-primary pull-right" type="button" @click="selectRegion(region)">
                                Select
                            </button>
                          </li>
                        </ul>
					</div>
					<div class="text-center">
						<pagination :pagination.sync="regionsPagination"
									:callback="getRegions"
									size="small">
						</pagination>
					</div>
				</template>
				<template v-else>
					<div class="form-group">
						<button class="btn btn-default-hollow btn-sm" type="button" @click="selectRegionView = true,currentAccommodation = null">
							<i class="fa fa-chevron-left"></i> Change Region
                        </button>
						<hr class="divider">
					</div>
					<template v-if="accommodations.length">
						<accordion :one-at-atime="true">
							<panel :type="currentAccommodation && currentAccommodation.id === accommodation.id ? 'danger' : ''" v-for="accommodation in accommodations">
								<div slot="header" class="row">
									<div class="col-xs-6">
                                        <strong>{{accommodation.name}}</strong>
                                        <span v-if="accommodation.room_types.total === accommodation.rooms_count.total" class="badge text-uppercase" style="padding:3px 10px;font-size:10px;line-height:1.4;">Full</span>
                                    </div>
									<div class="col-xs-4 text-right">
                                        {{accommodation.rooms_count.total}} <i class="fa fa-bed"></i>
									</div>
                                    <div class="col-xs-2 text-right">
                                        <button v-show="!currentAccommodation || (currentAccommodation && currentAccommodation.id !== accommodation.id)" class="btn btn-xs btn-default-hollow" @click="currentAccommodation = accommodation">
                                            Select
                                        </button>
                                    </div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<label>Address</label>
										<p class="small">
											<span v-if="accommodation.address_one">{{accommodation.address_one}}<br></span>
											<span v-if="accommodation.address_two">{{accommodation.address_two}}<br></span>
											<span v-if="accommodation.city && accommodation.state">{{accommodation.city}}, {{accommodation.state}} {{accommodation.zip}}<br></span>
											{{accommodation.country.name}}
										</p>
										<template v-if="accommodation.phone">
											<label>Phone</label>
											<p class="small">{{accommodation.phone|phone}}</p>
										</template>
										<template v-if="accommodation.email">
											<label>Email</label>
											<p class="small">{{accommodation.email}}</p>
										</template>

									</div><!-- end col -->
									<div class="col-sm-6">
										<label>Rooms</label>
										<div class="small">
											<span v-for="(key, val) in accommodation.rooms_count">
				                            <p style="line-height:1;font-size:11px;margin-bottom:2px;display:inline-block;"><span v-if="$index != 0"> &middot; </span>{{key | capitalize}}: <strong>{{val}}</strong></p>
				                        </span>
										</div>
										<label>Rooms Allowed</label>
										<div class="small">
										<span v-for="(key, val) in accommodation.room_types">
				                            <p style="line-height:1;font-size:11px;margin-bottom:2px;display:inline-block;"><span v-if="$index != 0"> &middot; </span>{{key | capitalize}}: <strong>{{val}}</strong></p>
				                        </span>
										</div>
									</div><!-- end col -->
								</div>
							</panel>
						</accordion>
						<div class="text-center">
							<pagination :pagination.sync="accommodationsPagination"
							            :callback="getAccommodations"
							            size="small">
							</pagination>
						</div>
					</template>
					<template v-else>
						<hr class="divider inv lg">
						<p style="margin-top:8px;" class="text-center text-muted">
							<em>Create an accommodation to get started.</em><br>
							<a href="region-accommodations" class="btn btn-sm btn-primary">Create Accommodations</a>
						</p>
					</template>
				</template>

			</div>
			<div class="col-sm-5">
				<div class="col-xs-12">
					<h4>Rooming Plans</h4>
					<hr class="divider sm">
					<form class="form-inline row">
						<div class="form-group col-xs-8">
							<div class="input-group input-group-sm col-xs-12">
								<input type="text" class="form-control" v-model="plansSearch" debounce="300" placeholder="Search">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
							</div>
							<hr class="divider sm inv">
						</div><!-- end col -->
						<div class="col-xs-4">
							<button class="btn btn-default btn-sm btn-block" type="button" @click="showPlansFilters=!showPlansFilters">
								<i class="fa fa-filter"></i>
								Filters
							</button>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-12">
							<template v-if="plans.length">
								<div class="panel-group" id="plansAccordion" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default" v-for="plan in plans">
										<div class="panel-heading">
											<div class="panel-title" slot="header">
												<div class="row">
													<a :href="'rooming-manager?plan=' + plan.id" target="_blank" class="col-xs-9" role="button">
														<h5>{{plan.name}}
                                                            <small>
                                                                {{ plan.rooms_count.total || 0 }} <i class="fa fa-bed"></i>
                                                            </small>
                                                        </h5>
													</a>
													<div class="col-xs-3 text-right">
														<button :disabled="!currentAccommodation" class="btn btn-xs btn-primary-hollow" type="button" @click="addPlanToAccommodation(plan, currentAccommodation)">
															<i class="fa fa-plus"></i>
														</button>
														<a :class="{ 'disabled': plan.rooms.data.length === 0 }" class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#plansAccordion" :href="'#planItem' + $index" aria-expanded="true" aria-controls="collapseOne">
															<i class="fa fa-angle-down"></i>
														</a>
													</div>
													<div class="col-xs-12">
														<label>Groups</label><br />
														<span v-for="group in plan.groups.data">
								                            <p class="small" style="line-height:1;margin-bottom:2px;display:inline-block;"><span v-if="$index != 0"> &middot; </span>{{group.name | capitalize}}</p>
								                        </span>
													</div>
													<!--<div class="col-xs-12">-->
														<!--<label>Rooms</label><br />-->
														<!--<span v-for="(key, val) in plan.rooms_count">-->
								                            <!--<p class="small" style="line-height:1;margin-bottom:2px;display:inline-block;"><span v-if="$index != 0"> &middot; </span>{{key | capitalize}}: <strong>{{val}}</strong> <span v-if="plan.rooms_count_remaining[key] && plan.rooms_count_remaining[key] !== val">({{plan.rooms_count_remaining[key]}} left)</span></p>-->
								                        <!--</span>-->
													<!--</div>-->
												</div>
											</div>
										</div>
										<div :id="'planItem' + $index" class="panel-collapse collapse">

											<div class="list-group">
												<div class="list-group-item"  v-for="room in plan.rooms.data" >
													<div class="list-group-item-heading">
														<strong>{{ (room.label ? (room.label + ' - ' + room.type) : room.type) | capitalize }}</strong> <span v-if="getRoomLeader(room)"> ({{getRoomLeader(room).given_names}} {{getRoomLeader(room).surname}})</span>
														<button :disabled="!currentAccommodation" class="btn btn-xs btn-primary-hollow pull-right" type="button" @click="addRoomToAccommodation(room, currentAccommodation)">
															<i class="fa fa-plus"></i>
														</button>
                                                        <p class="small" style="line-height:1;margin-bottom:2px;">
                                                            <span v-for="occupant in room.occupants.data">
                                                                <br /> &middot; {{ occupant.given_names }} {{ occupant.surname }}
                                                            </span>
                                                        </p>
													</div>

												</div>
											</div>
										</div>

									</div>
								</div>

								<div class="text-center">
									<pagination :pagination.sync="plansPagination" :callback="getRoomingPlans"
									            size="small">
									</pagination>
								</div>
							</template>
							<template v-else>
								<hr class="divider inv">
								<p class="text-center text-muted"><em>Create a new plan to get started!</em></p>
								<hr class="divider inv">
								<p class="text-center"><a class="btn btn-link btn-sm" href="rooming-manager">Create A Plan</a></p>
							</template>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import utilities from'../utilities.mixin';
    export default{
        name: 'rooming-accommodations',
        mixins: [utilities],
        components: {vSelect},
        props: {
            campaignId: {
                type: String,
	            required: true,
            }
	    },
        data(){
            return {
                selectRegionView: true,
                showRegionsFilters: false,
                showPlansFilters: false,
                showAccommodationsFilters: false,
                showRoomsFilters: false,

                regionsSearch: '',
                plansSearch: '',
                accommodationsSearch: '',
                currentAccommodationRoomsSearch: '',
                roomsSearch: '',

                regionsFilters: {
                    country: null
                },
                accommodationsFilters: {},

                plansFilters: {
                    campaign: null,
                    group: null,
                },
                roomsFilters: {
	                plans: [],
                    accommodations: [],
	                type: null
                },

                plans: [],
                plansOptions: [],
                groupsOptions: [],
                roomTypes: [],
                roomPlans: [],
                plansPagination: { current_page: 1 },
                regions: [],
                regionsPagination: { current_page: 1 },
                accommodations: [],
                accommodationsPagination: { current_page: 1 },
                currentRegion: null,
                currentAccommodation: null,
                currentAccommodationRooms: [],
                currentAccommodationRoomsPagination: { current_page: 1 },
                RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}'),
                RoomingAccommodationsResource: this.$resource('rooming/accommodations{/accommodation}{/path}{/pathId}'),
                AccommodationsResource: this.$resource('regions{/region}/accommodations{/accommodation}'),
                PlansResource: this.$resource('rooming/plans{/plan}'),
            }
        },
	    watch: {
            currentRegion(val) {
                if (val)
	                this.getAccommodations();
                else {
                    this.accommodations = [];
                    this.accommodationsPagination = { current_page: 1 };
                }

                this.getRoomingPlans();
            },
            currentAccommodation(val) {
                if (val) {
                    this.getCurrentAccommodationRooms();
                }
            },
            regionsFilters: {
                handler(){
                    this.regionsPagination.current_page = 1;
                    this.getRegions();
                },
	            deep: true
            },
            plansFilters: {
                handler(){
                    this.plansPagination.current_page = 1;
                    this.getRoomingPlans();
                },
	            deep: true
            },
            roomsFilters: {
                handler(){
                    this.currentAccommodationRoomsPagination.current_page = 1;
                    this.getCurrentAccommodationRooms();
                },
	            deep: true
            },
            plansSearch(val) {
                this.plansPagination.current_page = 1;
                this.getRoomingPlans();
            },
            roomsSearch(val) {
                this.currentAccommodationRoomsPagination.current_page = 1;
                this.getCurrentAccommodationRooms();
            },
        },
        methods: {
            resetRoomsFilter(){
                this.roomsFilters.plans = [];
                this.roomsFilters.type = null;
            },
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
            resetPlansFilter(){
                this.plansFilters = {
                    campaign: null,
                    group: null,
                };
            },
	        selectRegion(region) {
                this.currentRegion = region;
                this.selectRegionView = false;
	        },
            addPlanToAccommodation(plan, accommodation) {
                this.RoomingAccommodationsResource.save({ accommodation: accommodation.id, path: 'plans' }, { plan_ids: [plan.id] })
		            .then(function (response) {
                        this.AccommodationsResource
                            .get({ region: this.currentRegion.id, accommodation: this.currentAccommodation.id})
                            .then(function (response) {
								accommodation = response.body.data;
                                this.getCurrentAccommodationRooms();
                                this.getRoomingPlans();
                                this.$root.$emit('showSuccess', plan.name + ' successfully added to accommodation.');
                            }, this.$root.handleApiErro);

                    }, this.$root.handleApiError);
            },
            addRoomToAccommodation(room, accommodation) {
                this.RoomingAccommodationsResource.save({ accommodation: accommodation.id, path: 'rooms' }, { room_ids: [room.id] })
                    .then(function (response) {
                        accommodation.rooms_count[room.type]++;
                        accommodation.rooms_count.total++;
                        this.getCurrentAccommodationRooms();
                        this.getRoomingPlans();
                        this.$root.$emit('showSuccess', (room.label ? (room.label + ' - ' + room.type) : room.type) + ' successfully added to accommodation.');
                    }, this.$root.handleApiError)
            },
            removeRoomFromAccommodation(room, accommodation) {
                this.RoomingAccommodationsResource.delete({ accommodation: accommodation.id, path: 'rooms', pathId: room.id })
                    .then(function (response) {
                        accommodation.rooms_count[room.type]--;
                        accommodation.rooms_count.total--;
                        this.getCurrentAccommodationRooms();
                        this.getRoomingPlans();
                        this.$root.$emit('showSuccess', (room.label ? (room.label + ' - ' + room.type) : room.type) + ' successfully removed from accommodation.');
                    }, this.$root.handleApiError)
            },
            getAccommodations(){
                let params = {
                    region: this.currentRegion.id,
					page: this.accommodationsPagination.current_page,
                };

                return this.AccommodationsResource.get(params).then(function (response) {
                    this.accommodationsPagination = response.body.meta.pagination;
					return this.accommodations = response.body.data;
                }, this.$root.handleApiError);
            },
            getRegions(){
                let params = {
                    campaign: this.campaignId,
                    include: '',
                    page: this.regionsPagination.current_page,
                    search: this.regionsSearch,
	                country: _.isObject(this.regionsFilters.country) ? this.regionsFilters.country.code :    undefined
                };

                //params.country = this.regionsFilters.country;

                return this.RegionsResource.get(params).then(function (response) {
                    this.regionsPagination = response.body.meta.pagination;
                    return this.regions = response.body.data;
                }, this.$root.handleApiError);
            },
            getRoomingPlans(){
                let regionId = this.currentRegion ? this.currentRegion.id : '';
                let params = {
                    include: 'rooms:notInUse('+ regionId +'),rooms.occupants,groups',
                };
                params = _.extend(params, {
                    campaign: this.campaignId,
                    group: this.plansFilters.group ? this.plansFilters.group.id : null,
                    notInUse: this.currentRegion ? this.currentRegion.id : null,
                    search: this.plansSearch,
                    per_page: this.per_page,
	                page: this.plansPagination.current_page
                });

                return this.PlansResource.get(params).then(function (response) {
                    _.each(response.body.data, function (plan) {
                        plan.rooms_count_remaining = _.countBy(plan.rooms.data, 'type');
                    });
                    this.plansPagination = response.body.meta.pagination;
                    return this.plans = response.body.data;
                }, this.$root.handleApiError)
            },
            getRoomingPlansFilter(){
                let params = {
                    include: 'rooms.occupants',
                };
                params = _.extend(params, {
                    campaign: this.plansFilters.campaign ? this.plansFilters.campaign.id : null,
                    group: this.plansFilters.group ? this.plansFilters.group.id : null,
                    search: this.plansSearch,
                    per_page: this.per_page,
                });

                return this.PlansResource.get(params).then(function (response) {
                    this.pagination = response.body.meta.pagination;
                    this.plans = response.body.data;
                }, this.$root.handleApiError)
            },
	        getRoomLeader(room) {
                return _.findWhere(room.occupants.data, { room_leader: true });
	        },
            getGroups(search, loading){
                let promise;
                loading ? loading(true) : void 0;
                promise = this.$http.get('groups', { params: {search: search} }).then(function (response) {
                    this.groupsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }, this.$root.handleApiError);
            },
	        getAccommodationRooms(accommodation) {
                let params = {
                    accommodations: [accommodation.id],
	                page: accommodation.roomsPagination.current_page,
                };

                return this.$http.get('rooming/rooms', { params: params }).then(function (response) {
                    return {
                        rooms: response.body.data,
                        pagination: response.body.meta.pagination
                    }
                }, this.$root.handleApiError);
	        },
            getCurrentAccommodationRooms() {
                let params = {
                    accommodations: [this.currentAccommodation.id],
	                page: this.currentAccommodationRoomsPagination.current_page,
	                include: 'occupants',
	                search: this.roomsSearch,
                };

                params = _.extend(params, {
					plans: this.roomsFilters.plans.length ? _.pluck(this.roomsFilter.plans, 'id') : undefined,
	                type: this.roomsFilters.type ? this.roomsFilters.type.id : undefined,
                });

                return this.$http.get('rooming/rooms', { params: params }).then(function (response) {
                    this.currentAccommodationRooms = response.body.data;
                    this.currentAccommodationRoomsPagination = response.body.meta.pagination;
                }, this.$root.handleApiError);
	        },
            getRoomTypes(){
                return this.$http.get('rooming/types')
                    .then(function (response) {
                            return this.roomTypes = response.body.data;
                        }, this.$root.handleApiError);
            },
        },
        ready() {
            let promises = [];
            promises.push(this.getGroups());
            promises.push(this.getRoomTypes());
            promises.push(this.getRegions());
            promises.push(this.getRoomingPlans().then(function (plans) {
				this.plansOptions = plans;
            }));
            Promise.all(promises).then(function (values) {

            });
        }
    }
</script>