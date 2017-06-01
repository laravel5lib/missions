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

		<div class="col-sm-8"></div>
		<!-- Regions Select & Squads List -->
		<div class="col-sm-4">
			<!-- Search and Filter -->
			<form class="form-inline row" @submit.prevent>
				<div class="form-group col-xs-8">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="regionsSearch" debounce="300" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div>
				<div class="form-group col-xs-4">
					<button class="btn btn-default btn-sm btn-block" type="button" @click="showRegionsFilters = true;">
						<i class="fa fa-filter"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider sm">
				</div>
			</form>
			<div class="row">
				<div class="col-xs-12">
					<template v-if="regions.length">
						<div class="list-group">
							<a @click="currentRegion = region" class="list-group-item" v-for="region in regions">
								{{ region.name | capitalize }} <span v-if="region.callsign"> &middot; <span class="small">{{ region.callsign | capitalize }}</span></span>
								<span class="badge label label-info" v-text="region.country.name|capitalize"></span>
							</a>
						</div>
						<div class="col-xs-12 text-center">
							<pagination :pagination.sync="regionsPagination" :callback="getRegions"></pagination>
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
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import utilities from'../utilities.mixin';
    export default{
        name: 'regions-accommodations',
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

                regions: [],
                regionsPagination: { current_page: 1 },
                accommodations: [],
                accommodationsPagination: { current_page: 1 },
                currentRegion: null,
                currentAccommodation: null,
                RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}'),
                AccommodationsResource: this.$resource('regions{/region}/accommodations{/accommodation}'),
            }
        },
        watch: {
            regionsSearch() {
                this.regionsPagination.current_page = 1;
                this.getRegions();
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

                return this.RegionsResource.get(params).then(function (response) {
                    this.regionsPagination = response.body.meta.pagination;
                    return this.regions = response.body.data;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
        },
        ready(){
			let promises = [];
            promises.push(this.getCountries());
            promises.push(this.getRegions());
			Promise.all(promises).then(function (values) {

            });
        }
    }
</script>