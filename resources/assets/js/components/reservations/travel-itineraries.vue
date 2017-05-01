<template>
	<div style="position:relative;">
		<template v-if="itinerary && itinerary.id">
			<h6 class="text-uppercase">
				<i class="fa fa-plane"></i> {{itinerary.name}}
				<button class="btn btn-xs btn-default-hollow pull-right" @click="toggleEditMode" v-if="! editMode"><i class="fa fa-pencil"></i> Change</button>
			</h6>
			<hr class="divider lg">
		</template>

		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<div v-if="itinerary">
					<div class="checkbox" v-if="editMode">
						<label for="returningOnOwn">
							<input type="checkbox" id="returningOnOwn" name="returningOnOwn" :checked="!departurePresent" @change="toggleDeparture">I don't need a returning international flight.
						</label>
						<div class="alert alert-warning" v-show="returningOnOwn"><strong>NOTICE:</strong> By selecting this option, I am acknowledging that I will be arranging my own transportation home from the destination country.</div>
					</div>
					<div class="checkbox" v-if="editMode">
						<label for="connectionFlight">
							<input id="connectionFlight" type="checkbox" :checked="connectionPresent" name="connectionFlight" @change="toggleConnection"> I have a prior travel connection.
						</label>
					</div>
					<accordion :one-at-atime="true" v-if="itinerary.items">
						<panel :is-open.once="!editMode" :header="item.activity.name" v-for="item in itinerary.items" v-ref:items>
							<div class="checkbox">
								<label for="noTravelTo" v-if="isArrival(item)">
									<input type="checkbox" id="noTravelTo" name="noTravelTo" :checked="!item.transport.domestic" @change="item.transport.domestic = !item.transport.domestic">I don't need international transportation to the destination.
								</label>
								<div class="alert alert-warning" v-show="!item.transport.domestic"><strong>NOTICE:</strong> By selecting this option, I am arranging my own transportation to the destination country. Please provide those details below.</div>
							</div>

							<travel-transport v-ref:transport :edit-mode="editMode" :reservation-id="reservationId" :transport.sync="item.transport"></travel-transport>

							<travel-hub v-ref:hub :edit-mode="editMode" :hub="item.hub" :transport-type="item.transport.type"></travel-hub>

							<travel-activity v-ref:activity :edit-mode="editMode" :activity="item.activity" :simple="true"></travel-activity>

						</panel>
					</accordion>
					<hr class="divider sm">

					<!--<button v-if="itinerary.id" class="btn btn-xs btn-default" @click="deleteItinerary(itinerary)">Cancel</button>-->
					<button v-if="!itinerary.id && editMode" class="btn btn-sm btn-primary pull-right" type="button" @click="saveItinerary(itinerary)">Save Itinerary</button>
					<button v-else="" class="btn btn-sm btn-primary pull-right" type="button" @click="updateItinerary(itinerary)">Update Itinerary</button>
				</div>

				<!--<hr class="divider">-->
				<!--<button type="button" class="btn btn-xs btn-primary" @click="newItinerary">-->
				<!--New Itinerary-->
				<!--</button>-->
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
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
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'TravelItineraries',

                editMode: true,
                itinerary: null,
                activityTypes: [],
                returningOnOwn: false
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
            connectionPresent() {
                return _.some(this.itinerary.items, function (item) {
					return item.activity.activity_type_id === _.findWhere(this.activityTypes, { name: 'connection'}).id;
                }.bind(this));
			},
			departurePresent() {
                return _.some(this.itinerary.items, function (item) {
                    return item.activity.activity_type_id === _.findWhere(this.activityTypes, { name: 'departure'}).id;
                }.bind(this));

            },
			domesticArrivalPresent() {
                return _.some(this.itinerary.items, function (item) {
                    return item.activity.activity_type_id === _.findWhere(this.activityTypes, { name: 'arrival'}).id && !!item.transport.domestic;
                }.bind(this));

            },
		},
        methods: {
            isArrival(item) {
                let arrivalTypeId = _.findWhere(this.activityTypes, { name: 'arrival' });
				return item.activity.activity_type_id === arrivalTypeId.id;
            },
            setupItinerary(itineraryObj) {
                let itinerary = _.extend(itineraryObj, {
                    items: [],
                });
                _.each(itineraryObj.activities.data, function (activity) {
                    itinerary.items.push({
                        transport: {
                            id: activity.transports.data[0].id,
                            type: activity.transports.data[0].type,
                            vessel_no: activity.transports.data[0].vessel_no,
                            name: activity.transports.data[0].name,
                            call_sign: activity.transports.data[0].call_sign,
                            domestic: activity.transports.data[0].domestic
                        },
                        activity: {
                            id: activity.id,
                            activity_type_id: activity.activity_type_id,
                            name: activity.name,
                            description: activity.description,
                            occurred_at: activity.occured_at
                        },
                        hub: {
                            id: activity.hubs.data[0].id,
                            name: activity.hubs.data[0].name,
                            address: activity.hubs.data[0].address,
                            call_sign: activity.hubs.data[0].call_sign,
                            city: activity.hubs.data[0].city,
                            state: activity.hubs.data[0].state,
                            zip: activity.hubs.data[0].zip,
                            country_code: activity.hubs.data[0].country,
                        },
                    });
                });

	            /*if (this.itinerary.items === 1) {
	             itinerary.items.push(this.newItineraryItem('Depart from Training Location'));

	             }*/

                this.itinerary = itinerary;

                if (this.itinerary === null)
                    this.newItinerary();
            },
            getItinerary(){
                let doc_id = this.document ? this.document.id : this.$parent.requirement.document_id;
                this.$http.get('itineraries/' + doc_id, { params: { 'include': 'activities.hubs,activities.transports'}}).then(function (response) {
                    this.setupItinerary(response.body.data);
                });
            },
            deleteItinerary(itinerary){
                this.$http.delete('itineraries/travel', itinerary).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Deleted');
                });
            },
            saveItinerary(itinerary){
                // trigger validation styles
                this.$broadcast('validate-itinerary');
                let isInvalid = false;
                // iterate through each itinerary item and check validation status of each child form
                isInvalid = _.some(this.$refs.items, function (item) {
                    return _.some(this.$refs.items[0].$children, function (child) {
                        let vState = child.$TravelTransport || child.$TravelHub || child.$TravelActivity;
                        console.log(child.validatorHandle + ' invalid: ' + vState.invalid);
                        return vState.invalid;
                    }.bind(this));
                }.bind(this));

                if (isInvalid) {
                    this.$root.$emit('showError', 'Please check your itinerary form.');
                    return;
                }

                // if (this.returningOnOwn)
                //    itinerary.items.pop();

                this.$http.post('itineraries/travel', itinerary, { params: { 'include': 'activities.hubs,activities.transports'}}).then(function (response) {
                    //this.itinerary = response.body.data;
                    this.setupItinerary(response.body.data);
                    this.editMode = false;
                    this.setDesignation(response.body.data);
                    this.$emit('showSuccess', 'Itinerary Saved');
                });
            },
            updateItinerary(itinerary){
                // trigger validation styles
                this.$broadcast('validate-itinerary');
                let isInvalid = false;
                // iterate through each itinerary item and check validation status of each child form
                isInvalid = _.some(this.$refs.items, function (item) {
                    return _.some(this.$refs.items[0].$children, function (child) {
                        let vState = child.$TravelTransport || child.$TravelHub || child.$TravelActivity;
                        console.log(child.validatorHandle + ' invalid: ' + vState.invalid);
                        return vState.invalid;
                    }.bind(this));
                }.bind(this));

                if (isInvalid) {
                    this.$root.$emit('showError', 'Please check your itinerary form.');
                    return;
                }

//                if (this.returningOnOwn)
//                    itinerary.items.pop();

                this.$http.put('itineraries/travel/' + itinerary.id, itinerary, { params: { 'include': 'activities.hubs,activities.transports'}}).then(function (response) {
                    this.setupItinerary(response.body.data);
                    this.editMode = false;
                    this.setDesignation(response.body.data);
                    this.$emit('showSuccess', 'Itinerary Saved');
                });
            },
            newItinerary(itineraryName){
                let itinerary = {
                    name: itineraryName || 'Itinerary',
                    reservation_id: this.reservationId,
                    items: []
                };
                let arrivalTypeId = _.findWhere(this.activityTypes, { name: 'arrival' });
                itinerary.items.push(this.newItineraryItem('Arrive at Training Location', arrivalTypeId.id ));
                let departureTypeId = _.findWhere(this.activityTypes, { name: 'departure' });
                itinerary.items.push(this.newItineraryItem('Depart from Training Location', departureTypeId.id));
	            this.itinerary = itinerary;
            },
	        newItineraryItem(name, activityID){
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
                        country: '',
                    },
                }
	        },
            toggleConnection() {
	            if (this.connectionPresent) {
                    this.itinerary.items.splice(0, 1);
                } else {
                    let connectionTypeId = _.findWhere(this.activityTypes, {name: 'connection'});
                    this.itinerary.items.splice(0, 0, this.newItineraryItem('Travel Connection', connectionTypeId.id));
                }
            },
            toggleDeparture() {
	            if (this.departurePresent) {
                    this.itinerary.items.pop();
                } else {
                    let departureTypeId = _.findWhere(this.activityTypes, {name: 'departure'});
                    this.itinerary.items.push(this.newItineraryItem('Depart from Training Location', departureTypeId.id));
                }
            },
	        toggleEditMode() {
	            this.editMode = true;
	            this.$nextTick(function () {
                    if ( _.isFunction(this.$validate) )
                        this.$validate(true);
                }.bind(this));

	        },
            setDesignation(designation) {
                this.$dispatch('set-document', designation);
            },

        },
        ready(){
            let promises = [];
            // Get activity types
            promises.push(this.$http.get('utilities/activities/types').then(function (response) {
                this.activityTypes = response.body;
            }));

            let self = this;
            Promise.all(promises).then(function (values) {
                if (self.document || (self.$parent && self.$parent.requirement && self.$parent.requirement.document_id)) {
                    self.editMode = false;
                    self.getItinerary();
                } else {
                    self.newItinerary();
                }
            });


        }
    }
</script>