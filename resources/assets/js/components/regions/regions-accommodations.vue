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

		<div class="col-sm-8">
			<template v-if="currentRegion">
				<!-- Search and Filter -->
				<form class="form-inline row" @submit.prevent>
					<div class="col-xs-12 text-right">
						<button class="btn btn-primary btn-sm" type="button" @click="startNewAccommodation">
							Create an Accommodation
						</button>
						<hr class="divider sm inv">
					</div>
					<div class="form-group col-sm-8">
						<div class="input-group input-group-sm col-xs-12">
							<input type="text" class="form-control" v-model="accommodationsSearch" debounce="300" placeholder="Search">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
						</div>
					</div>
					<div class="form-group col-sm-4">
						<button class="btn btn-default btn-sm btn-block" type="button" @click="showAccommodationsFilters = true;">
							<i class="fa fa-filter"></i>
						</button>
					</div>
					<div class="col-xs-12">
						<hr class="divider sm">
					</div>
				</form>
				<div class="row">
					<div class="col-xs-12">
						<template v-if="accommodations.length">
							<accordion :one-at-atime="true" type="info">
								<panel type="primary" v-for="accommodation in accommodations">
									<div slot="header" class="row">
										<div class="col-xs-8" v-text="accommodation.name"></div>
										<div class="col-xs-4 text-right">
											<button class="btn btn-xs btn-default-hollow" @click="editAccommodation(accommodation)">
												<i class="fa fa-pencil"></i>
											</button>
											<button class="btn btn-xs btn-default-hollow" @click="openDeleteAccommodationModal(accommodation)">
												<i class="fa fa-trash"></i>
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
							<hr class="divider inv">
							<p class="text-center text-italic text-muted"><em>No accommodations created yet. Create one to get started!</em></p>
							<p class="text-center">
								<button class="btn btn-primary btn-sm" type="button" @click="startNewAccommodation">
									Create an Accommodation
								</button>
							</p>
						</template>

					</div>
				</div>
			</template>
			<template v-else>
				<hr class="divider inv">
				<p class="text-center text-italic text-muted"><em>Please select a region to begin</em></p>

			</template>

		</div>
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
							<a @click="currentRegion = region" class="list-group-item" :class="{ 'active': currentRegion && currentRegion.id === region.id}" v-for="region in regions">
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
						<p class="text-center text-italic text-muted"><em>No regions created yet. Create one to get started!</em></p>
					</template>
				</div>
			</div>
		</div>

		<!-- Modals -->
		<modal :title="editMode?'Update an Accommodation':'Create an Accommodation'" :ok-text="editMode?'Update' : 'Create'" :callback="manageAccommodation" :show.sync="showAccommodationManageModal">
			<div slot="modal-body" class="modal-body" v-if="currentAccommodation">
				<validator name="AccommodationForm">
					<form id="AccommodationForm">
						<div class="form-group" v-error-handler="{ value: currentAccommodation.name, handle: 'name' }">
							<label for="AccoName" class="control-label">Name</label>
							<input @keydown.enter.prevent="manageAccommodation" type="text" class="form-control" id="AccoName" placeholder="" v-validate:name="['required']" v-model="currentAccommodation.name">
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.description, handle: 'description' }">
							<label for="AccoDescription" class="control-label">Description</label>
							<textarea class="form-control" id="AccoDescription" v-validate:description="" v-model="currentAccommodation.description"></textarea>
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.address_one, handle: 'addressone' }">
							<label for="AccoAddressOne" class="control-label">Address 1</label>
							<input type="text" class="form-control" id="AccoAddressOne" placeholder="" v-validate:addressone="" v-model="currentAccommodation.address_one">
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.address_two, handle: 'addresstwo' }">
							<label for="AccoAddressTwo" class="control-label">Address 2</label>
							<input type="text" class="form-control" id="AccoAddressTwo" placeholder="" v-validate:addresstwo="" v-model="currentAccommodation.address_two">
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.phone, handle: 'phone' }">
							<label for="AccoPhone" class="control-label">Phone</label>
							<input type="text" class="form-control" id="AccoPhone" placeholder="" v-validate:phone="" v-model="currentAccommodation.phone">
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.email, handle: 'email' }">
							<label for="AccoEmail" class="control-label">Email</label>
							<input type="email" class="form-control" id="AccoEmail" placeholder="" v-validate:email="['email']" v-model="currentAccommodation.email">
						</div>
						<div class="form-group" v-error-handler="{ value: currentAccommodation.url, handle: 'url' }">
							<label for="AccoURL" class="control-label">URL</label>
							<input type="url" class="form-control" id="AccoURL" placeholder="" v-validate:url="" v-model="currentAccommodation.url">
						</div>

						<div class="checkbox">
							<label>
								<input type="checkbox" v-model="currentAccommodationDifferentCountry" >
								Is this Accommodation in another country?
							</label>
						</div>
						<div class="form-group" v-if="currentAccommodationDifferentCountry">

							<label for="createPlanCallsign" class="control-label">Region Country</label>
							<v-select @keydown.enter.prevent=""  class="form-control" :debounce="250" :on-search="getCountries"
							          :value.sync="currentAccommodation.country" :options="UTILITIES.countries" label="name"
							          placeholder="Select a Country"></v-select>
						</div>
					</form>
				</validator>
			</div>
		</modal>
		<modal title="Delete Accomodation" small ok-text="Delete" :callback="deleteAccommodation" :show.sync="showAccommodationDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="currentAccommodation">
					Are you sure you want to delete accommodation: "{{currentAccommodation.name}}" ?
				</p>
			</div>
		</modal>

	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import errorHandler from'../error-handler.mixin';
    import utilities from'../utilities.mixin';
    export default{
        name: 'regions-accommodations',
        mixins: [errorHandler, utilities],
	    components: {vSelect},
        props: {
            campaignId: {
                type: String,
	            required: true,
            }
	    },
        data(){
            return {
                validatorHandle: 'AccommodationForm',
                showRegionsFilters: false,
                showAccommodationsFilters: false,

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
                currentAccommodationDifferentCountry: false,
				editMode: false,
                newAccommodation: null,

                showAccommodationManageModal: false,
                showAccommodationDeleteModal: false,

                RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}'),
                AccommodationsResource: this.$resource('regions{/region}/accommodations{/accommodation}'),
            }
        },
        watch: {
            currentRegion() {
                this.accommodationsPagination.current_page = 1;
                this.getAccommodations();
            },
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
            AccommodationFactory() {
                return {
                    name: ''
                }
            },
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
	        startNewAccommodation() {
                this.currentAccommodation = _.extend({}, this.AccommodationFactory());
                this.showAccommodationManageModal = true;
	        },
	        editAccommodation(accommodation) {
                this.currentAccommodation = accommodation;
                this.editMode = true;
                this.showAccommodationManageModal = true;
	        },
            openDeleteAccommodationModal(accommodation){
                this.currentAccommodation = accommodation;
                this.showAccommodationDeleteModal = true;
            },
	        manageAccommodation() {
                this.resetErrors();
                if (this.$AccommodationForm.valid) {
                    return this.editMode ? this.updateAccommodation() : this.saveAccommodation();
                } else {
                    this.$root.$emit('showError');
                }

	        },
            saveAccommodation() {
                let data = _.extend({}, this.currentAccommodation);
                if (data.country && _.isObject(data.country)) {
                    data.country_code = data.country.code;
                    delete data.country;
                }

                return this.AccommodationsResource.save({ region: this.currentRegion.id }, data).then(function () {
                    this.currentAccommodation = null;
                    this.showAccommodationManageModal = false;
                    return this.getAccommodations();
                }, function (response) {
                    console.log(response);
                    return response;
                });
	        },
            updateAccommodation() {
                let data = _.extend({}, this.currentAccommodation);
                if (data.country && _.isObject(data.country)) {
                    data.country_code = data.country.code;
                    delete data.country;
                }

                return this.AccommodationsResource.update({ region: this.currentRegion.id, accommodation: this.currentAccommodation.id }, data).then(function () {
                    this.currentAccommodation = null;
                    this.showAccommodationManageModal = false;
                    this.editMode = false;
                    return this.getAccommodations();
                }, function (response) {
                    console.log(response);
                    return response;
                });
	        },
	        deleteAccommodation() {
                return this.AccommodationsResource.delete({ region: this.currentRegion.id, accommodation: this.currentAccommodation.id }).then(function () {
                    this.currentAccommodation = null;
                    this.showAccommodationDeleteModal = false;
                    return this.getAccommodations();
                }, function (response) {
                    console.log(response);
                    return response;
                });
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
                    return response.body.message;
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
                    return response.body.message;
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