<template>
	<div style="position:relative;">
		<template v-if="itinerary && itinerary.id">
			<h6 class="text-uppercase">
				<i class="fa fa-plane"></i> {{itinerary.name}}
				<button class="btn btn-xs btn-default-hollow pull-right" @click="toggleResetModal" v-if="! editMode && ! isLocked"><i class="fa fa-pencil"></i> Change</button>
			</h6>
			<hr class="divider lg">
		</template>


			<form id="TravelItinerariesForm" novalidate>
				<spinner ref="spinner" global size="sm" text="Loading"></spinner>

				<div v-if="itinerary">
					<p>Please provide details about each step of your trip below.</p>
					<div class="checkbox" v-if="editMode && !itinerary.id">
						<label for="connectionFlight">
							<input id="connectionFlight" type="checkbox" :checked="connectionPresent" name="connectionFlight" @change="toggleConnection"> I have a Travel Connection (i.e connecting flight, bus, etc.).
						</label>
					</div>
					<accordion :one-at-atime="true" v-if="itinerary.items">
						<panel :is-open.once="isArrival(item)" v-for="item in itinerary.items" :key="item.id" ref="items">

                            <strong slot="header">
                                <i class="fa fa-map-marker"></i> Step : {{ item.activity.name }}
                            </strong>

							<div class="checkbox" v-if="editMode && isArrival(item)">
								<label for="noTravelTo">
									<input type="checkbox" id="noTravelTo" name="noTravelTo" :checked="!item.transport.domestic" @change="toggleDomestic(item)">I am traveling directly to the destination country.
								</label>
								<div class="alert alert-warning" v-show="!item.transport.domestic"><strong>NOTICE:</strong> By selecting this option, I am arranging my own transportation to the destination country. Please provide those details below. Please check with your Trip Facilitator or Trip Rep to make sure this is a viable option for you.</div>
							</div>
							<div class="checkbox" v-if="editMode && !itinerary.id && isDeparture(item)">
								<label for="returningOnOwn">
									<input type="checkbox" id="returningOnOwn" name="returningOnOwn" :checked="returningOnOwn" @click="toggleReturningOnOwn(item)">I am arranging my own transportation out of the destination country.
								</label>
								<div class="alert alert-warning" v-show="returningOnOwn"><strong>NOTICE:</strong> By selecting this option, I am acknowledging that I will be arranging my own transportation home from the destination country.</div>
							</div>

							<travel-transport v-if="item.transport" ref="transport" :edit-mode="editMode" :reservation-id="reservationId" :transport="item.transport" :activity-types="activityTypes" :activity-type="item.activity.activity_type_id"></travel-transport>

							<travel-hub v-if="item.hub" ref="hub" :edit-mode="editMode" :hub="item.hub" :transport-type="item.transport ? item.transport.type : null" :activity-types="activityTypes" :activity-type="item.activity.activity_type_id"></travel-hub>

							<travel-activity ref="activity" :edit-mode="editMode" :activity="item.activity" :simple="true" :activity-types="activityTypes" :activity-type="item.activity.activity_type_id" :transport-domestic="item.transport && item.transport.domestic" @updated="updateActivity"></travel-activity>

						</panel>
					</accordion>

					<template v-if="editMode">
						<hr class="divider sm">
						<template v-if="!itinerary.id">
							<button class="btn btn-sm btn-default" type="button" @click="toggleResetModal">Start Over</button>
							<button class="btn btn-sm btn-primary pull-right" type="button" @click="saveItinerary(itinerary)">Save Itinerary</button>
						</template>
						<template v-else>
							<button class="btn btn-sm btn-default" type="button" @click="editMode = false">Cancel</button>
							<button class="btn btn-sm btn-default" type="button" @click="toggleResetModal">Start Over</button>
							<button class="btn btn-sm btn-primary pull-right" type="button" @click="updateItinerary(itinerary)">Update Itinerary</button>
						</template>
					</template>
				</div>

				<modal title="Make Changes" small :value="showResetModal" @closed="showResetModal=false" ok-text="Confirm" :callback="resetItinerary">
					<div slot="modal-body" class="modal-body">
						In order to make changes to a travel itinerary, you will need to start over and submit a new one. This action can't be undone. Do you want to continue making changes?
					</div>
				</modal>

			</form>


	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    import travelTransport from './travel-transport.vue';
    import travelHub from './travel-hub.vue';
    import travelActivity from './travel-activity.vue';
    export default{
        name: 'travel-itineraries',
        mixins: [errorHandler],
        components: {vSelect, travelTransport, travelHub, travelActivity},
        props: {
            reservationId: {
                type: String,
                required: true,
            },
            document: {
                type: Object,
            },
            locked: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return {

                editMode: true,
                itinerary: null,
                activityTypes: [],
//                returningOnOwn: false,
	            showResetModal: false,
            }
        },
        watch: {
            selectedAirline(val){
                if (val && val !== 'other') {
                    this.transport.name = val;
                }
            }
        },
        computed: {
            connectionType(){
                if (this)
                    return _.findWhere(this.activityTypes, { name: 'connection'});
            },
            arrivalType(){
                if (this)
                    return _.findWhere(this.activityTypes, { name: 'arrival'});
            },
            departureType(){
                if (this)
                    return _.findWhere(this.activityTypes, { name: 'departure'});
            },
            connectionPresent() {
                return this.itinerary.items && _.some(this.itinerary.items, (item) => {
					return item.activity.activity_type_id === this.connectionType.id;
                });
			},
			departurePresent() {
                return this.itinerary.items && _.some(this.itinerary.items, (item) => {
                    return item.activity.activity_type_id === this.departureType.id;
                });

            },
			domesticArrivalPresent() {
                return this.itinerary.items && _.some(this.itinerary.items, (item) => {
                    return item.activity.activity_type_id === this.arrivalType.id && !!item.transport.domestic;
                });

            },
            returningOnOwn: {
                get() {
                    return this.itinerary.items && _.some(this.itinerary.items, (item) => {
                        if (item.activity.activity_type_id === this.departureType.id) {
                            return !item.transport && !item.hub;
                        }
                        return false;
                    });
                },
	            set() {}
            },
			isLocked() {
				if (this.isAdminRoute)
					return false;

				return this.locked;
			}
		},
        methods: {
            isArrival(item) {
				return item.activity.activity_type_id === this.arrivalType.id;
            },
            isDeparture(item) {
				return item.activity.activity_type_id === this.departureType.id;
            },
            setupItinerary(itineraryObj) {
                let itinerary = _.extend(itineraryObj, {
                    items: [],
                });
                _.each(itineraryObj.activities.data, (activity) => {
                    // create item
                    let item = {
                        activity: {
                            id: activity.id,
                            activity_type_id: activity.type.id,
                            name: activity.name,
                            description: activity.description,
                            occurred_at: activity.occurred_at
                        },
                    };

                    // add transport if it exists
                    if (activity.transports && activity.transports.data.length) {
                        item.transport = {
                            id: activity.transports.data[0].id,
                            type: activity.transports.data[0].type,
                            vessel_no: activity.transports.data[0].vessel_no,
                            name: activity.transports.data[0].name,
                            call_sign: activity.transports.data[0].call_sign,
                            domestic: activity.transports.data[0].domestic
                        };
                    }

                    // add hub if it exists
                    if (activity.hubs && activity.hubs.data.length) {
                        item.hub = {
                            id: activity.hubs.data[0].id,
                            name: activity.hubs.data[0].name,
                            address: activity.hubs.data[0].address,
                            call_sign: activity.hubs.data[0].call_sign,
                            city: activity.hubs.data[0].city,
                            state: activity.hubs.data[0].state,
                            zip: activity.hubs.data[0].zip,
                            country_code: activity.hubs.data[0].country_code,
                        };
                    }

                    itinerary.items.push(item);
                });

                this.itinerary = itinerary;

                if (this.itinerary === null)
                    this.newItinerary();

                return itinerary;
            },
            getItinerary(){
                let doc_id = this.document ? this.document.id : this.$parent.requirement.document_id;
                return this.$http.get('itineraries/' + doc_id, { params: { 'include': 'activities.hubs,activities.transports'}})
	                .then((response) => {
	                    return this.setupItinerary(response.data.data);
	                },
                    (response) =>  {
                        console.log(response);
                    });
            },
            deleteItinerary(itinerary){
                this.$http.delete('itineraries/travel', itinerary).then((response) => {
                    this.$emit('showSuccess', 'Itinerary Deleted');
                });
            },
            saveItinerary(itinerary){
                // trigger validation styles
                this.$emit('validate-itinerary');

                // iterate through each itinerary item and check validation status of each child form
                let validations = [];
                _.each(this.$refs.items, item => {
                    _.each(item.$children, child => {
                        validations.push(child.$validator.validateAll());
                    });
                });

                Promise.all(validations).then(results => {
                    if (_.contains(results, false)) {
                        this.$root.$emit('showError', 'Please check your itinerary form.');
                        return;
                    }

                    return this.$http.post('itineraries/travel', itinerary, { params: { 'include': 'activities.hubs,activities.transports'}}).then((response) => {
                            //this.itinerary = response.data.data;
                            let it = this.setupItinerary(response.data.data);
                            this.editMode = false;
                            this.setItinerary(response.data.data);
                            this.$emit('showSuccess', 'Itinerary Saved');
                            return it
                        },
                        (response) =>  {
                            console.log(response);
//                        return response.data.data;
                        });
                })
            },
            updateItinerary(itinerary){
                // trigger validation styles
                this.$emit('validate-itinerary');
                let isInvalid = false;
                // iterate through each itinerary item and check validation status of each child form
                isInvalid = _.some(this.$refs.items, function (item) {
                    return _.some(this.$refs.items[0].$children, function (child) {
                        let vState = child.$TravelTransport || child.$TravelHub || child.$TravelActivity;
                        console.log(child.validatorHandle + ' invalid: ' + vState.invalid);
                        return vState.invalid;
                    });
                });

                if (isInvalid) {
                    this.$root.$emit('showError', 'Please check your itinerary form.');
                    return;
                }

                return this.$http.put('itineraries/travel/' + itinerary.id, itinerary, { params: { 'include': 'activities.hubs,activities.transports'}}).then((response) => {
                    let it = this.setupItinerary(response.data.data);
                    this.editMode = false;
                    this.setItinerary(response.data.data);
                    this.$emit('showSuccess', 'Itinerary Saved');
                    return it;
                },
                    (response) =>  {
                        console.log(response);
                    });
            },
            newItinerary(itineraryName){
                let itinerary = {
                    name: itineraryName || 'Itinerary',
                    reservation_id: this.reservationId,
                    items: []
                };
                console.info('Activity Types: ', this.activityTypes);
                let arrival = _.findWhere(this.activityTypes, { name: 'arrival'});
                console.info('Arrival: ', arrival);
                itinerary.items.push(this.newItineraryItem('Arrive at Training Location', arrival.id ));
                let departure = _.findWhere(this.activityTypes, { name: 'departure'});
                console.info('Departure: ', departure);
                itinerary.items.push(this.newItineraryItem('Return Home', departure.id));
	            return this.itinerary = itinerary;
            },
            resetItinerary(){
                if (this.itinerary.id) {
                    this.$http.delete('itineraries/travel/' + this.itinerary.id).then((response) => {
                        this.unsetItinerary(this.itinerary);
                        console.log('Itinerary deleted');
                    },
                        (response) =>  {
                            console.log(response);
                        });
                }

                this.editMode = true;
                let itinerary = {
                    name: 'Itinerary',
                    reservation_id: this.reservationId,
                    items: []
                };

                let arrival = this.arrivalType;
                itinerary.items.push(this.newItineraryItem('Arrive at Training Location', arrival.id ));
                let departure = this.departureType;
                itinerary.items.push(this.newItineraryItem('Return Home', departure.id));

                this.itinerary = itinerary;
                this.toggleResetModal();
            },
	        newItineraryItem(name, activityID){
//		        let type = _.findWhere(this.activityTypes, { id: activityID || this.activityTypes[0].id });
                return {
                    transport: {
                        type: '',
                        vessel_no: '',
                        name: '',
                        call_sign: '',
                        domestic: true
                    },
                    activity: {
                        name: name || 'Arrive at Training Location',
                        activity_type_id: activityID || this.activityTypes[0].id,
	                    //type: type,
                        // description: '',
                        occurred_at: ''
                    },
                    hub: {
                        name: '',
                        address: '',
                        call_sign: '',
                        city: '',
                        state: '',
                        zip: '',
                        country_code: '',
                    },
                }
	        },
            toggleConnection() {
	            if (this.connectionPresent) {
                    this.itinerary.items.splice(0, 1);
                } else {
                    this.itinerary.items.splice(0, 0, this.newItineraryItem('Travel Connection', this.connectionType.id));
                }
            },
            toggleDeparture() {
                if (this.departurePresent) {
                    this.itinerary.items.pop();
                } else {
                    this.itinerary.items.push(this.newItineraryItem('Return Home', this.departureType.id));
                }
            },
	        toggleReturningOnOwn(item) {
	            if (this.returningOnOwn) {
	                item.transport = {
                        type: '',
                        vessel_no: '',
                        name: '',
                        call_sign: '',
                        domestic: true
                    };
	                item.hub = {
                        name: '',
                        address: '',
                        call_sign: '',
                        city: '',
                        state: '',
                        zip: '',
                        country_code: '',
                    };
                } else {
                    item.transport = undefined;
                    item.hub = undefined;
                }
                return item;
            },
	        toggleDomestic(item) {
	            item.transport.domestic = !item.transport.domestic;
	            item.activity.name = item.transport.domestic ? 'Arrive at Training Location' : 'Arrive in Destination Country'
	        },
	        toggleEditMode() {
	            this.editMode = true;
	        },
            toggleResetModal(){
	            this.showResetModal = !this.showResetModal;
            },
            setItinerary(itinerary) {
                this.$emit('set-document', itinerary);
            },
            unsetItinerary(itinerary) {
                this.$emit('unset-document', itinerary);
            },
	        getTypes() {
	            return this.$http.get('utilities/activities/types').then((response) => {
                    return this.activityTypes = response.data;
                },
                    (response) =>  {
                        console.log(response);
                    });
	        },
	        updateActivity(activity) {
				debugger;
	        }

        },
        mounted(){
            let promises = [];
            // Get activity types
            promises.push(this.getTypes());

            let self = this;
            Promise.all(promises).then((values) => {
                // initiate computed types
                let arrival = self.arrivalType;
                let departure = self.departureType;
                let connection = self.connectionType;
                return [arrival, departure, connection];
            }).then(() => {
                self.$nextTick(() =>  {
                    if (self.document || (self.$parent && self.$parent.requirement && self.$parent.requirement.document_id)) {
                        self.editMode = false;
                        self.getItinerary();
                    } else {
                        self.newItinerary();
                    }
                });
            });
        }
    }
</script>