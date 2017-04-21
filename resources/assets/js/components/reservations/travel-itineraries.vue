<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<div v-for="itinerary in itineraries">

					<div class="checkbox">
						<label for="returningOnOwn">
							<input type="checkbox" id="returningOnOwn" name="returningOnOwn" v-model="returningOnOwn">I don't need a returning international flight.
						</label>
					</div>
					<div class="checkbox">
						<label for="connectionFlight">
							<input id="connectionFlight" type="checkbox" :checked="itinerary.items.length === 3" name="connectionFlight" @change="toggleConnectionItem(itinerary.items)"> Do you have a prior travel connection?
						</label>
					</div>
					<accordion :one-at-atime="true" type="info">
						<panel is-open="false" :header="item.activity.name" v-for="item in itinerary.items">
							<div class="checkbox">
								<label for="noTravelTo" v-if="itinerary.items.length === ($index + 2)">
									<input type="checkbox" id="noTravelTo" name="noTravelTo" :checked="!item.transport.domestic" @change="item.transport.domestic = !item.transport.domestic">I don't need international transport to the destination.
								</label>
							</div>

							<div v-if="itinerary.items.length === ($index + 1) && returningOnOwn">
								<p>Returning on own</p>
							</div>
							<div v-else>
								<travel-transport v-ref:transport :reservation-id="reservationId" :transport.sync="item.transport"></travel-transport>

								<travel-hub v-ref:hub :hub="item.hub" :transport-type="item.transport.type"></travel-hub>

								<travel-activity v-ref:activity :activity="item.activity" :simple="true"></travel-activity>
							</div>

						</panel>
					</accordion>
					<hr class="divider sm">

					<!--<button v-if="itinerary.id" class="btn btn-xs btn-default" @click="deleteItinerary(itinerary)">Cancel</button>-->
					<button v-if="!itinerary.id" class="btn btn-xs btn-primary" type="button" @click="saveItinerary(itinerary)">Save Itinerary</button>
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

                itineraries: [],
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
        methods: {
            getItineraries(){
                this.$http.get('itineraries/' + this.document.id, { params: { 'include': 'activities.hubs,activities.transports'}}).then(function (response) {
                    let itinerary = {
                        id: response.body.data.id,
                        reservation_id: response.body.data.curator_id,
                        items: [],
                    };
                    _.each(response.body.data.activities.data, function (activity) {
	                    itinerary.items.push({
                            transport: {
                                id: activity.transports.data.id,
                                type: activity.transports.data.type,
                                vessel_no: activity.transports.data.vessel_no,
                                name: activity.transports.data.name,
                                call_sign: activity.transports.data.call_sign,
                                domestic: activity.transports.data.domestic,
                                capacity: activity.transports.data.capacity,
                            },
                            activity: {
                                id: activity.id,
                                name: activity.name,
	                            description: activity.description,
                                occurred_at: activity.occured_at
                            },
                            hub: {
                                id: activity.hubs.data.id,
                                name: activity.hubs.data.name,
                                address: activity.hubs.data.address,
                                call_sign: activity.hubs.data.call_sign,
                                city: activity.hubs.data.city,
                                state: activity.hubs.data.state,
                                zip: activity.hubs.data.zip,
                                country: activity.hubs.data.country,
                            },
                        });
                    });

                    this.itineraries.push(itinerary);

                    if (!this.itineraries.length)
                        this.newItinerary();
                });
            },
            deleteItinerary(itinerary){
                this.$http.delete('itineraries/travel', itinerary).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Deleted');
                });
            },
            saveItinerary(itinerary){
                if (this.returningOnOwn)
                    itinerary.items.pop();

                this.$http.post('itineraries/travel', itinerary).then(function (response) {

                    this.itineraries.push(response.body.data);
                    this.$emit('showSuccess', 'Itinerary Saved');
                });
            },
            newItinerary(){
                let itinerary = {
                    reservation_id: this.reservationId,
                    items: []
                };
                itinerary.items.push(this.newItineraryItem('Arrival in Miami'));
                itinerary.items.push(this.newItineraryItem('Depart Miami'));
	            this.itineraries.push(itinerary);
            },
	        newItineraryItem(name){
                return {
                    transport: {
                        type: '',
                        vessel_no: '',
                        name: '',
                        call_sign: '',
                        domestic: true,
                        capacity: '',
                    },
                    activity: {
                        name: name || 'Arrive in Miami',
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
            toggleConnectionItem(items) {
	            if (items.length < 3) {
                    items.splice(0, 0, this.newItineraryItem('Travel Connection'));
                } else {
	                items.splice(0, 1);
	            }
            }
        },
        ready(){
            if (this.document) {
                this.getItineraries();
            } else {
                this.newItinerary();
            }

        }
    }
</script>