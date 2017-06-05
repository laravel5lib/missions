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
		<div class="row">
			<div class="col-sm-8">
				<template v-if="currentAccommodation">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								{{ currentAccommodation.name | capitalize }} &midddot; <span>Details</span>
							</h3>
						</div>
						<div class="panel-body">
							Panel body
						</div>
					</div>
				</template>
				<template v-else>
					<div class="well">
						<p style="margin-top:8px;" class="text-center text-muted"><em>Select an accommodation to view details.</em></p>
					</div>
				</template>

				<hr class="divider">
				<!--<select class="form-control" v-model="currentRegion">
					<option :value=""> &#45;&#45; Select a Region &#45;&#45; </option>
					<option :value="region" v-for="region in regions">
						{{region.name}} <span class="small" v-if="region.callsign">({{region.callsign}})</span>
					</option>
				</select>-->
				<v-select @keydown.enter.prevent="" class="form-control" :debounce="300" :on-search="getRegions"
				          :value.sync="currentRegion" :options="regions" label="name"
				          placeholder="Select a Region"></v-select>
				<hr class="divider sm inv">
				<template v-if="currentRegion && accommodations.length">
					<accordion :one-at-atime="true">
						<panel :type="currentAccommodation && currentAccommodation.id === accommodation.id ? 'info' : ''" v-for="accommodation in accommodations">
							<div slot="header" class="row">
								<div class="col-xs-8" v-text="accommodation.name"></div>
								<div class="col-xs-4 text-right">
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
										<ul class="list-unstyled">
											<li v-for="(key, value) in accommodation.rooms_count">
												{{key|capitalize}}: {{value}}
											</li>
										</ul>
									</div>
									<label>Occupants</label>
									<p class="small">{{accommodation.occupants_count}}</p>
								</div><!-- end col -->
							</div>
						</panel>
					</accordion>
				</template>
				<template v-else>
					<hr class="divider inv lg">
					<p style="margin-top:8px;" class="text-center text-muted">
						<em>Create an accommodation to get started.</em><br>
						<a href="region-accommodations" class="btn btn-sm btn-primary">Create Accommodations</a>
					</p>
				</template>
			</div>
			<div class="col-sm-4">
				<div class="col-xs-12">
					<template v-if="plans.length">
						<accordion :one-at-atime="true">
							<panel :header="plan.name" v-for="plan in plans">
								<!-- Search and Filter -->
								<form class="form-inline row">
									<div class="form-group col-xs-8">
										<div class="input-group input-group-sm col-xs-12">
											<input type="text" class="form-control" v-model="roomsSearch" debounce="300" placeholder="Search">
											<span class="input-group-addon"><i class="fa fa-search"></i></span>
										</div>
									</div><!-- end col -->
									<div class="form-group col-xs-4">
										<button class="btn btn-default btn-sm btn-block" type="button" @click="showplansFilters=!showplansFilters">
											<i class="fa fa-filter"></i>
										</button>
									</div>
									<div class="col-xs-12">
										<hr class="divider inv">
									</div>
								</form>

								<div class="row">
									<div class="col-xs-12">
										<!-- List group List-->
										<div class="list-group">
											<div class="list-group-item"  v-for="room in plan.rooms" >
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
														</div><!-- end col -->
													</div><!-- end row -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</panel>
						</accordion>

						<div class="panel-group" id="plansAccordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default" v-for="plan in plans | orderBy 'name'">
								<div class="panel-heading" role="tab" id="headingOne">
									<h5 class="panel-title">
										<div class="row">
											<div class="col-xs-9">
												<div class="media">
													<!--<div class="media-left" style="padding-right:0;">
														<a role="button" data-toggle="collapse" :data-parent="'#plansAccordion' + tgIndex" :href="'#planItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
															<img :src="plan.avatar" class="media-object img-circle img-xs av-left"><span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="plan.leader">GL</span>
														</a>
													</div>-->
													<div class="media-body" style="vertical-align:middle;">
														<h6 class="media-heading text-capitalize" style="margin-bottom:3px;"><a role="button" data-toggle="collapse" data-parent="#plansAccordion" :href="'#planItem' + $index" aria-expanded="true" aria-controls="collapseOne">{{ plan.surname | capitalize }}, {{ plan.given_names | capitalize }}</a></h6>
														<!--<p class="text-muted" style="line-height:1;font-size:10px;margin-bottom:2px;">{{ plan.desired_role.name }}</p>-->
													</div><!-- end media-body -->
												</div><!-- end media -->
												<!-- <a role="button" data-toggle="collapse" :data-parent="'#plansAccordion' + tgIndex" :href="'#planItem' + tgIndex + $index" aria-expanded="true" aria-controls="collapseOne">
													<img :src="plan.avatar" class="img-circle img-xs av-left">
													{{ plan.surname | capitalize }}, {{ plan.given_names | capitalize }} <span class="small" v-if="plan.leader">&middot; GL</span><br>
													<label>{{ plan.desired_role.name }}</label>
												</a> -->
											</div>
											<div class="col-xs-3 text-right action-buttons">
												<dropdown type="default">
													<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
														<span class="fa fa-ellipsis-h"></span>
													</button>
													<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
														<!--<li class="dropdown-header">Assign To Room</li>-->
														<!--<li role="separator" class="divider"></li>-->
														<!--<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(plan, true, currentRoom)">{{(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize}} as leader</a></li>-->
														<!--<li :class="{'disabled': isLocked}" v-if="currentRoom"><a @click="addToRoom(plan, false, currentRoom)" v-text="(currentRoom.label ? (currentRoom.label + ' - ' + currentRoom.type.data.name) : currentRoom.type.data.name) | capitalize"></a></li>-->
														<!--<li v-if="!currentRoom"><a @click=""><em>Please select a room first.</em></a></li>-->
													</ul>
												</dropdown>
												<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#plansAccordion" :href="'#planItem' + $index" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-angle-down"></i>
												</a>
											</div>
										</div>
									</h5>
								</div>
								<!--<div :id="'planItem' + tgIndex + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
												<label>Gender</label>
											</div>&lt;!&ndash; end col &ndash;&gt;
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{plan.gender | capitalize}}</p>
											</div>&lt;!&ndash; end col &ndash;&gt;
										</div>&lt;!&ndash; end row &ndash;&gt;
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Marital Status</label>
											</div>&lt;!&ndash; end col &ndash;&gt;
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{plan.status | capitalize}}</p>
											</div>&lt;!&ndash; end col &ndash;&gt;
										</div>&lt;!&ndash; end row &ndash;&gt;
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Age</label>
											</div>&lt;!&ndash; end col &ndash;&gt;
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{plan.age}}</p>
											</div>&lt;!&ndash; end col &ndash;&gt;
										</div>&lt;!&ndash; end row &ndash;&gt;
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Travel Group</label>
											</div>&lt;!&ndash; end col &ndash;&gt;
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{plan.travel_group}}</p>
											</div>&lt;!&ndash; end col &ndash;&gt;
										</div>&lt;!&ndash; end row &ndash;&gt;
										<hr class="divider sm">
										<div class="row">
											<div class="col-sm-6">
												<label>Arrival Designation</label>
											</div>&lt;!&ndash; end col &ndash;&gt;
											<div class="col-sm-6">
												<p class="small" style="margin:3px 0;">{{plan.arrival_designation|capitalize}}</p>
											</div>&lt;!&ndash; end col &ndash;&gt;
										</div>&lt;!&ndash; end row &ndash;&gt;
									</div>&lt;!&ndash; end panel-body &ndash;&gt;

								</div>
								<div class="panel-footer" v-if="plan.companions && plan.companions.data.length">
									I have {{plan.companions.data.length}} companions.
								</div>-->
							</div>
						</div>

					</template>
					<template v-else>
						<hr class="divider inv">
						<p class="text-center text-muted"><em>Create a new plan to get started!</em></p>
						<hr class="divider inv">
						<p class="text-center"><a class="btn btn-link btn-sm" @click="openNewPlanModal">Create A Plan</a></p>
					</template>
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
                showRegionsFilters: false,

                regionsSearch: '',
                accommodationsSearch: '',

                regionsFilters: {
                    country: null
                },
                accommodationsFilters: {},

                plansFilters: {
                    campaign: null,
                    group: null,
                },

                plans: [],
                plansPagination: { current_page: 1 },
                regions: [],
                regionsPagination: { current_page: 1 },
                accommodations: [],
                accommodationsPagination: { current_page: 1 },
                currentRegion: null,
                currentAccommodation: null,
                RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}'),
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
            },
            regionsFilters: {
                handler(){
                    this.regionsPagination.current_page = 1;
                    this.getRegions();
                },
	            deep: true
            },
	    },
        methods: {
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
            getAccommodations(){
                let params = {
                    region: this.currentRegion.id,

                };

                return this.AccommodationsResource.get(params).then(function (response) {
                    this.accommodationsPagination = response.body.meta.pagination;
					return this.accommodations = response.body.data;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
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
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
            getRoomingPlans(){
                let params = {
                    include: 'rooms',
                };
                params = _.extend(params, {
                    campaign: this.plansFilters.campaign ? this.plansFilters.campaign.id : null,
                    group: this.plansFilters.group ? this.plansFilters.group.id : null,
                    search: this.search,
                    per_page: this.per_page,
                });

                return this.PlansResource.get(params).then(function (response) {
                    this.pagination = response.body.meta.pagination;
                    this.plans = response.body.data;
                })
            },
        },
        ready() {
            let promises = [];
//            promises.push(this.getCountries());
            promises.push(this.getRegions());
            promises.push(this.getRoomingPlans());
            Promise.all(promises).then(function (values) {

            });
        }
    }
</script>