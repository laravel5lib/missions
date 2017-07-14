<template>
	<div class="row" style="position:relative;">

        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

		<aside :show.sync="showRegionsFilters" placement="left" header="Region Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group" v-if="isAdminRoute">
					<label>Country</label>
					<v-select @keydown.enter.prevent=""
                              class="form-control"
                              :debounce="250"
                              :on-search="getCountries"
					          :value.sync="regionsFilters.country"
                              :options="UTILITIES.countries"
                              label="name"
					          placeholder="Filter Countries">
                    </v-select>
				</div>
				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block"
                        type="button"
                        @click="resetRegionFilter">
                    <i class="fa fa-times"></i> Reset Region Filters
                </button>
			</form>
		</aside>

		<div class="col-sm-8">
            <div class="row">
                <div class="col-xs-6">
    			     <h4>Accommodations</h4>
                </div>
                <div class="col-xs-6 text-right">
                    <button class="btn btn-primary btn-sm" type="button" @click="startNewAccommodation" >
                        <i class="fa fa-plus"></i> New Accommodation
                    </button>
                </div>
            </div>
			<hr class="divider sm">
			<template v-if="currentRegion">
				<!-- Search and Filter -->
				<form class="form-inline row" @submit.prevent>
					<div class="form-group col-sm-8">
						<div class="input-group input-group-sm col-xs-12">
							<input type="text"
                                   class="form-control"
                                   v-model="accommodationsSearch"
                                   debounce="300"
                                   placeholder="Search">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
						</div>
					</div>
					<div class="form-group col-sm-4">
						<button class="btn btn-default btn-sm btn-block"
                                type="button"
                                @click="showAccommodationsFilters = true;">
							<i class="fa fa-filter"></i> Filter
						</button>
					</div>
					<div class="col-xs-12">
						<hr class="divider sm">
					</div>
				</form>

				<div class="collapse" id="AccommodationModal">
					<div class="panel panel-default" v-if="showAccommodationManageModal">
							<validator name="AccommodationForm">
								<form id="AccommodationForm"
								      name="AccommodationForm"
								      @submit.prevent="manageAccommodation">
									<div class="panel-heading">
										<h5 v-text="editMode?'Update an Accommodation':'Create an Accommodation'"></h5>
									</div>
									<div class="panel-body" v-if="currentAccommodation">
										<div class="row">
											<div class="form-group col-sm-12"
											     v-error-handler="{ value: currentAccommodation.name, handle: 'name' }">
												<label for="AccoName" class="control-label">Name</label>
												<input @keydown.enter.prevent="manageAccommodation"
												       type="text"
												       class="form-control"
												       id="AccoName" placeholder=""
												       v-validate:name="['required']"
												       v-model="currentAccommodation.name">
											</div>
											<div class="form-group col-sm-12"
											     v-error-handler="{ value: currentAccommodation.short_desc, handle: 'shortdesc' }">
												<label for="AccoDescription" class="control-label">Description</label>
												<textarea class="form-control"
												          id="AccoDescription"
												          v-validate:shortdesc=""
												          v-model="currentAccommodation.short_desc">
                                            </textarea>
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.address_one, handle: 'addressone' }">
												<label for="AccoAddressOne" class="control-label">Address 1</label>
												<input type="text"
												       class="form-control"
												       id="AccoAddressOne"
												       placeholder=""
												       v-validate:addressone=""
												       v-model="currentAccommodation.address_one">
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.address_two, handle: 'addresstwo' }">
												<label for="AccoAddressTwo" class="control-label">Address 2</label>
												<input type="text"
												       class="form-control"
												       id="AccoAddressTwo"
												       placeholder=""
												       v-validate:addresstwo=""
												       v-model="currentAccommodation.address_two">
											</div>
											<div class="form-group col-sm-4"
											     v-error-handler="{ value: currentAccommodation.state, handle: 'city' }">
												<label for="AccoCity" class="control-label">City</label>
												<input type="text"
												       class="form-control"
												       id="AccoCity"
												       placeholder=""
												       v-validate:city=""
												       v-model="currentAccommodation.city">
											</div>
											<div class="form-group col-sm-4"
											     v-error-handler="{ value: currentAccommodation.state, handle: 'state' }">
												<label for="AccoState" class="control-label">State</label>
												<input type="text"
												       class="form-control"
												       id="AccoState"
												       placeholder=""
												       v-validate:state=""
												       v-model="currentAccommodation.state">
											</div>
											<div class="form-group col-sm-4"
											     v-error-handler="{ value: currentAccommodation.zip, handle: 'zip' }">
												<label for="AccoZip" class="control-label">Zip</label>
												<input type="text"
												       class="form-control"
												       id="AccoZip"
												       placeholder=""
												       v-validate:zip=""
												       v-model="currentAccommodation.zip">
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.phone, handle: 'phone' }">
												<label for="AccoPhone" class="control-label">Phone</label>
												<input type="text"
												       class="form-control"
												       id="AccoPhone"
												       placeholder=""
												       v-validate:phone=""
												       v-model="currentAccommodation.phone">
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.email, handle: 'email' }">
												<label for="AccoEmail" class="control-label">Email</label>
												<input type="email"
												       class="form-control"
												       id="AccoEmail"
												       placeholder=""
												       v-validate:email="['email']"
												       v-model="currentAccommodation.email">
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.fax, handle: 'fax' }">
												<label for="AccoFax" class="control-label">Fax</label>
												<input type="text"
												       class="form-control"
												       id="AccoFax"
												       placeholder=""
												       v-validate:fax=""
												       v-model="currentAccommodation.fax">
											</div>
											<div class="form-group col-sm-6"
											     v-error-handler="{ value: currentAccommodation.url, handle: 'url' }">
												<label for="AccoURL" class="control-label">URL</label>
												<input type="url"
												       class="form-control"
												       id="AccoURL"
												       placeholder=""
												       v-validate:url=""
												       v-model="currentAccommodation.url">
											</div>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" v-model="currentAccommodationDifferentCountry" >
												Is this Accommodation in another country?
											</label>
										</div>
										<div class="form-group" v-if="currentAccommodationDifferentCountry">
											<label for="createPlanCallsign" class="control-label">
												Region Country
											</label>
											<v-select @keydown.enter.prevent=""
											          class="form-control"
											          :debounce="250"
											          :on-search="getCountries"
											          :value.sync="currentAccommodation.country"
											          :options="UTILITIES.countries"
											          label="name"
											          placeholder="Select a Country">
											</v-select>
										</div>
									</div>
									<template v-if="currentAccommodation.room_types_settings">
										<div class="panel-heading">
											<h5>Rooms Allowed</h5>
										</div>
										<div class="panel-body">
											<div class="form-group col-sm-6" v-for="type in roomTypes">
												<label :for="'settingsType-' + type.id" class="" v-text="type.name"></label>
												<input type="number"
												       number
												       class="form-control"
												       :id="'settingsType-' + type.id"
												       v-model="currentAccommodation.room_types_settings[type.id]"
												       min="0">
											</div>
										</div>
									</template>
								</form>
							</validator>
					    <div class="panel-footer text-center">
						    <button type="button"
                                    class="btn btn-default btn-sm"
                                    @click="showAccommodationManageModal = false">
                                Cancel
                            </button>
							<button type="submit"
                                    form="AccommodationForm"
                                    class="btn btn-sm btn-primary"
                                    v-text="editMode?'Update' : 'Create'">
                            </button>
						</div>
					</div>
				</div>
                <!-- Accommodation list -->
				<div class="row">
					<div class="col-xs-12">
						<template v-if="accommodations.length">
							<accordion :one-at-atime="true" type="info">
								<panel type="primary" v-for="accommodation in accommodations">
									<div slot="header" class="row">
										<div class="col-xs-8">
											<h5>{{ accommodation.name }}</h5>
											<p>{{ accommodation.short_desc }}</p>
										</div>
										<div class="col-xs-4 text-right">
											<button class="btn btn-xs btn-default-hollow"
                                                    @click="editAccommodation(accommodation)">
												<i class="fa fa-pencil"></i>
											</button>
											<button class="btn btn-xs btn-default-hollow"
                                                    @click="openDeleteAccommodationModal(accommodation)">
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
												<span v-if="accommodation.city && accommodation.state">
                                                    {{accommodation.city}},
                                                    {{accommodation.state}} {{accommodation.zip}}
                                                    <br>
                                                </span>
												<span v-if="accommodation.country">{{accommodation.country.name}}</span>
											</p>
											<template v-if="accommodation.phone">
												<label>Phone</label>
												<p class="small">{{accommodation.phone|phone}}</p>
											</template>
											<template v-if="accommodation.fax">
												<label>Fax</label>
												<p class="small">{{accommodation.fax|phone}}</p>
											</template>
											<template v-if="accommodation.email">
												<label>Email</label>
												<p class="small">{{accommodation.email}}</p>
											</template>
                                            <template v-if="accommodation.url">
                                                <label>Website</label>
                                                <p class="small">
                                                    <a :href="accommodation.url" target="_blank">
                                                        {{accommodation.url}}
                                                    </a>
                                                </p>
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
											<label>Rooms Allowed</label>
											<div class="small">
												<ul class="list-unstyled">
													<li v-for="(key, value) in accommodation.room_types">
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
							<div class="col-xs-12 text-center">
								<pagination :pagination.sync="accommodationsPagination" :callback="getAccommodations"></pagination>
							</div>
						</template>
						<template v-else>
							<hr class="divider inv">
							<p class="text-center text-italic text-muted">
                                <em>No accommodations created yet. Create one to get started!</em>
                            </p>
							<p class="text-center">
								<button class="btn btn-primary btn-sm"
                                        type="button"
                                        @click="startNewAccommodation">
									Create an Accommodation
								</button>
							</p>
						</template>

					</div>
				</div>
			</template>
			<template v-else>
				<hr class="divider inv">
				<p class="text-center text-italic text-muted">
                    <em>Please select a region to begin</em>
                </p>
			</template>

		</div>
		<!-- Regions Select & Squads List -->
		<div class="col-sm-4">
			<h4>Regions</h4>
			<hr class="divider sm">
			<!-- Search and Filter -->
			<form class="form-inline row" @submit.prevent>
				<div class="form-group col-xs-8">
					<div class="input-group input-group-sm">
						<input type="text"
                               class="form-control"
                               v-model="regionsSearch"
                               debounce="300"
                               placeholder="Search">
						<span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
					</div>
				</div>
				<div class="form-group col-xs-4">
					<button class="btn btn-default btn-sm btn-block"
                            type="button"
                            @click="showRegionsFilters = true;">
						<i class="fa fa-filter"></i> Filter
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
							<a @click="currentRegion = region"
                               class="list-group-item"
                               :class="{ 'active': currentRegion && currentRegion.id === region.id}"
                               v-for="region in regions">
								<h4 class="list-group-item-heading">
									{{ region.name | capitalize }}
									<span class="badge pull-right" v-text="region.accommodations.data.length"></span>
								</h4>
                                <p>
                                    <span v-if="region.callsign">
                                        <span class="label label-default"
                                              :style="'color: #FFF !important; background-color: ' + region.callsign"
                                              v-text="region.callsign|capitalize">
                                        </span>
                                    </span>
                                    <span class="small">{{ region.country.name | capitalize }}</span>
                                </p>
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

		<modal title="Delete Accommodation"
               small
               ok-text="Delete"
               :callback="deleteAccommodation"
               :show.sync="showAccommodationDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="currentAccommodation">
					Are you sure you want to delete accommodation: "{{currentAccommodation.name}}" ?
				</p>
			</div>
		</modal>

	</div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import $ from 'jquery';
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
                AccommodationTypesResource: this.$resource('rooming/accommodations{/accommodation}/types{/type}'),
            }
        },
        watch: {
            showAccommodationManageModal(val) {
                this.$nextTick(function () {
                    if ($.fn.collapse)
                        $('#AccommodationModal').collapse(val ? 'show' : 'hide');
                });
            },
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
                    name: '',
                    room_types_settings: {},
                }
            },
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
	        startNewAccommodation() {
                if (this.currentRegion) {
                    let accommodation = _.extend({}, this.AccommodationFactory());
                    this.currentAccommodation = this.loadAccommodationRoomTypes(accommodation);
                    this.showAccommodationManageModal = true;
                } else {
                    this.$root.$emit('showInfo', 'Please select a region to begin.');
                }
	        },
	        editAccommodation(accommodation) {
                this.currentAccommodation = this.loadAccommodationRoomTypes(accommodation);
                this.editMode = true;
                this.showAccommodationManageModal = true;
	        },
	        loadAccommodationRoomTypes(accommodation) {
                accommodation.room_types_settings = accommodation.room_types_settings || {};
                // We need to loop through each room type to create an object to reference the plan types present
                _.each(this.roomTypes, function (type) {
                    // the settings will be traced by the type ids
                    // then we will attempt to find the assignment in the current plan's settings (expecting a number) or set to 0
                    let assignment = accommodation.room_types && accommodation.room_types.hasOwnProperty(type.name) ? accommodation.room_types[type.name] : 0;
                    accommodation.room_types_settings[type.id] = assignment;
                    // we are using method to track which room types need to be updated or posted
                    accommodation.room_types_settings[type.id + '_method'] = assignment > 0 ? 'PUT' :'POST';
                });
                return accommodation;
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
                    this.$root.$emit('showError', 'Please check the form.');
                    return false;
                }

	        },
            saveAccommodation() {
                let data = _.extend({}, this.currentAccommodation);
                if (data.country && _.isObject(data.country)) {
                    data.country_code = data.country.code;
                    delete data.country;
                }

                if (data.room_types_settings)
                    delete data.room_types_settings;

                return this.AccommodationsResource.save({ region: this.currentRegion.id }, data).then(function (response) {
	                let newAccommodation = response.body.data;
                    this.handleAccommodationRoomTypes(_.extend(this.currentAccommodation, newAccommodation));
                    this.currentRegion.accommodations.data.push(newAccommodation);
                    this.accommodations.push(newAccommodation);
                    this.showAccommodationManageModal = false;
                }, function (response) {
                    return response;
                });
	        },
            updateAccommodation() {
                let data = _.extend({}, this.currentAccommodation);
                if (data.country && _.isObject(data.country)) {
                    data.country_code = data.country.code;
                    delete data.country;
                }

                if (data.room_types_settings)
                    delete data.room_types_settings;

                return this.AccommodationsResource.update({ region: this.currentRegion.id, accommodation: this.currentAccommodation.id }, data).then(function (response) {
                    this.handleAccommodationRoomTypes(_.extend(this.currentAccommodation, response.body.data));
                    this.currentAccommodation = null;
                    this.showAccommodationManageModal = false;
                    this.editMode = false;
                }, function (response) {
                    return response;
                });
	        },
	        deleteAccommodation() {
                return this.AccommodationsResource.delete({ region: this.currentRegion.id, accommodation: this.currentAccommodation.id }).then(function () {
                    this.currentAccommodation = null;
                    this.showAccommodationDeleteModal = false;
                    return this.getAccommodations();
                }, function (response) {
                    return response;
                });
	        },
	        handleAccommodationRoomTypes(accommodation){
                let promises = [];
                _.each(accommodation.room_types_settings, function (val, property) {
                    let promise;
                    if (property.indexOf('_method') === -1 && !_.contains(['total'], property)) {
                        if (accommodation.room_types_settings[property + '_method'] === 'PUT') {
                            if (val > 0) {
                                promise = this.AccommodationTypesResource.update({
                                    accommodation: accommodation.id, type: property
                                }, { available_rooms: val });
                            } else {
                                // if val is 0 we should simply delete the room type setting
                                promise = this.AccommodationTypesResource.delete({
                                    accommodation: accommodation.id, type: property
                                });
                            }
                        } else {
                            // only create setting if val > 0
                            if (val > 0)
                                promise = this.AccommodationTypesResource.save({ accommodation: accommodation.id}, {
                                    room_type_id: property,
                                    available_rooms: val
                                });
                        }
                        if (promise) {
                            // we only need to catch errors here
                            promise.catch(function (response) {});
                            promises.push(promise);
                        }
                    }

                }.bind(this));

                Promise.all(promises).then(function () {
                    this.$root.$emit('showSuccess', accommodation.name + ' settings updated successfully.');
                    this.getAccommodations();
                    this.$nextTick(function () {
                        this.currentAccommodation = null;
                    });

                }.bind(this));
	        },
            getAccommodations(){
                let params = {
                    region: this.currentRegion.id,
                };

                return this.AccommodationsResource.get(params).then(function (response) {
                    this.accommodationsPagination = response.body.meta.pagination;
                    let accommodations = _.each(response.body.data, function (acc) {
	                    acc = _.extend(acc, this.loadAccommodationRoomTypes(acc));
                    }.bind(this));
					return this.accommodations = accommodations;
                }, function (response) {
                    return response.body.message;
                });
            },
            getRegions(){
                let params = {
                    campaign: this.campaignId,
                    include: 'accommodations',
                    page: this.regionsPagination.current_page,
                    search: this.regionsSearch,
                    country: _.isObject(this.regionsFilters.country) ? this.regionsFilters.country.code : undefined
                };

                return this.RegionsResource.get(params).then(function (response) {
                    this.regionsPagination = response.body.meta.pagination;
                    return this.regions = response.body.data;
                }, function (response) {
                    return response.body.message;
                });
            },
            getRoomTypes(){
                return this.$http.get('rooming/types', { params: { campaign: this.campaignId, per_page: 100 } })
                    .then(function (response) {
                            return this.roomTypes = response.body.data;
                        },
                        function (response) {
                            return response.body.data;
                        });
            },
        },
        ready(){
			let promises = [];
            promises.push(this.getCountries());
            promises.push(this.getRegions());
            promises.push(this.getRoomTypes());
			Promise.all(promises).then(function (values) {});
        }
    }
</script>