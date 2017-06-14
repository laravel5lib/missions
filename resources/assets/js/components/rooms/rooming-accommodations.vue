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
			<div class="col-sm-7">
				<template v-if="currentAccommodation">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								{{ currentAccommodation.name | capitalize }} &middot; <span>Details</span>
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
				<template v-if="selectRegionView">
					<h4>Regions</h4>
					<hr class="divider sm">
					<table class="table table-condensed table-hover">
						<thead>
						<tr>
							<th>Name</th>
							<th>Teams</th>
							<th>Accommodations</th>
							<th><i class="fa fa-cog"></i></th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="region in regions | orderBy 'name'">
							<td>{{region.name}} ({{region.callsign}})</td>
							<td v-text="region.teams_count"></td>
							<td v-text="region.accommodations_count"></td>
							<td>
								<button class="btn btn-xs btn-default-hollow" type="button" @click="selectRegion(region)">
									Select
								</button>
							</td>
						</tr>
						</tbody>
					</table>
				</template>
				<template v-else>
					<div class="form-group">
						<button class="btn btn-default btn-sm" type="button" @click="selectRegionView = true;">Change Region</button>
						<hr class="divider">
					</div>
					<template v-if="accommodations.length">
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
				</template>

			</div>
			<div class="col-sm-5">
				<div class="col-xs-12">
					<h4>Plans</h4>
					<hr class="divider sm">
					<template v-if="plans.length">
						<div class="panel-group" id="plansAccordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default" v-for="plan in plans">
								  <div class="panel-heading">
									  <h3 class="panel-title" slot="header">
										  <div class="row">
											  <div class="col-xs-9" role="button" data-toggle="collapse" data-parent="#plansAccordion" :href="'#planItem' + $index" aria-expanded="true" aria-controls="collapseOne">
												  <h4>{{plan.name}}</h4>
											  </div>
											  <div class="col-xs-3 text-right">
												  <button :disabled="!currentAccommodation" class="btn btn-xs btn-primary-hollow" type="button" @click="addPlanToAccommodation(plan, currentAccommodation)">
													  <i class="fa fa-plus"></i>
												  </button>
												  <a :class="{ 'disabled': plan.rooms.data.length === 0 }" class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#plansAccordion" :href="'#planItem' + $index" aria-expanded="true" aria-controls="collapseOne">
													  <i class="fa fa-angle-down"></i>
												  </a>
											  </div>
										  </div>
									  </h3>
								  </div>
								<div :id="'planItem' + $index" class="panel-collapse collapse">
									<div class="list-group">
										<div class="list-group-item"  v-for="room in plan.rooms.data" >
											<h5 class="list-group-item-heading">
												{{ (room.label ? (room.label + ' - ' + room.type) : room.type) | capitalize }}

												<button :disabled="!currentAccommodation" class="btn btn-xs btn-primary-hollow pull-right" type="button" @click="addRoomToAccommodation(room, currentAccommodation)">
													<i class="fa fa-plus"></i>
												</button>
											</h5>
											<!--<div class="list-group-item-text">
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
											</div>-->
										</div>
									</div>
								</div>

							</div>
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
	        selectRegion(region) {
                this.currentRegion = region;
                this.selectRegionView = false;
	        },
            addPlanToAccommodation(plan, accommodation) {
	            this.$http.post('rooming/accommodations/' + accommodation.id + '/plans', { plan_ids: [plan.id] })
		            .then(function (response) {
		                this.$root.$emit('showSuccess', plan.name + ' successfully added to accommodation.');
                    });
            },
            addRoomToAccommodation(room, accommodation) {
                console.log('should add individual room to accomodation');
                /*this.$http.post('rooming/accommodations/' + accommodation.id + '/plans').then(function (response) {

                });*/
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