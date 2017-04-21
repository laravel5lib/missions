<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<div v-for="itinerary in itineraries">
					<accordion :one-at-atime="true" type="info">
						<panel is-open="false" :header="item.activity.name" v-for="item in itinerary.items">
							<travel-transport v-ref:transport :reservation-id="reservationId" :transport="item.transport"></travel-transport>

							<travel-hub v-ref:hub :hub="item.hub"></travel-hub>

							<travel-activity v-ref:activity :activity="item.activity" :simple="true"></travel-activity>

							<div class="checkbox" v-if="$index === 0 && itinerary.items.length < 3">
								<label for="connectionFLight">
									<input id="connectionFLight" name="connectionFLight" type="checkbox" @change="toggleConnectionItem(itinerary)"> Do you have a prior connecting flight?
								</label>
							</div>
						</panel>
					</accordion>
					<hr class="divider sm">

					<button class="btn btn-xs btn-default" @click="deleteItinerary(itinerary)">Cancel</button>
					<button v-if="!!itinerary.id" class="btn btn-xs btn-primary" type="button" @click="updateItinerary(itinerary)">Update Transport</button>
					<button v-else class="btn btn-xs btn-primary" type="button" @click="saveItinerary(itinerary)">Save Transport</button>
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
            campaignId: {
                type: String,
//                required: true,
            },
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'TravelItineraries',

                itineraries: [],
            }
        },
        watch: {
            selectedAirline(val){
                if (val && val !== 'other') {
//                    let airline = _.findWhere(this.airlinesOptions, {name: this.selectedAirline});
                    this.transport.name = val;
                }
            }
        },
        methods: {
            getItineraries(){
                this.$http.get('itineraries', {params: {include: '',}}).then(function (response) {
                    this.itineraries = response.body.data;
                });
            },
            deleteItinerary(itinerary){
                this.$http.delete('itineraries/travel', itinerary).then(function (response) {
                    //this.itineraries.push(response.body.data);
                });
            },
            saveItinerary(itinerary){
                this.$http.post('itineraries/travel', itinerary).then(function (response) {
                    this.itineraries.push(response.body.data);
                });
            },
            updateItinerary(itinerary){
                this.$http.put('itineraries/travel/' + itinerary.id, itinerary).then(function (response) {
                    return itinerary = response.body.data;
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
                    items.splice(1, 0, this.newItineraryItem('Connection Flight'));
                } else {
	                items.splice(1, 0);
	            }
            }
        },
        ready(){
            this.newItinerary();
//            this.getItineraries();
        }
    }
</script>