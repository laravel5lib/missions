<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<accordion :one-at-atime="true" type="info">
					<panel is-open="false" :header="itinerary.name" v-for="itinerary in itineraries" v-ref:iList>
						<div v-for="item in itinerary.items">
							<h5>Activty</h5>
							<travel-activity v-ref:activity :activity="item.activity"></travel-activity>

							<h5>Transport</h5>
							<travel-transport v-ref:transport :reservation-id="reservationId" :transport="item.transport"></travel-transport>

							<h5>Hub</h5>
							<travel-hub v-ref:hub :hub="item.hub"></travel-hub>

							<hr class="divider sm">

							<button class="btn btn-xs btn-default" @click="deleteItinerary(itinerary)">Cancel</button>
							<button v-if="!!itinerary.id" class="btn btn-xs btn-primary" type="button" @click="updateItinerary(itinerary)">Update Transport</button>
							<button v-else class="btn btn-xs btn-primary" type="button" @click="saveItinerary(itinerary)">Save Transport</button>

						</div>
					</panel>
				</accordion>

				<hr class="divider">
				<button type="button" class="btn btn-xs btn-primary" @click="newItinerary">
					New Itinerary
				</button>
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

                // Itinerary
                itineraryObj: {
                    reservation_id: this.reservationId,
	                name: 'New Itinerary',
                    items: [
                        {
                            transport: {
                                type: '',
                                vessel_no: '',
                                name: '',
                                call_sign: '',
                                domestic: true,
                                capacity: '',
                            },
                            activity: {
                                name: '',
                                description: '',
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
                    ]
                },
                itineraries: [
                    {
                        "id": "e0f78803-61d6-473d-8a63-f1b5ca0ff919",
                        "name": "Kyle Annabelle Bins's Travel Itinerary",
                        "curator_id": "f094201d-94f6-384b-81c9-689bff73f5d5",
                        "curator_type": "reservations",
                        "activities": 1,
                        "updated_at": "2017-04-21 04:59:01",
                        "created_at": "2017-04-21 04:59:01",
                        "deleted_at": null,
                        "links": [{"rel": "self", "uri": "\/api\/itineraries\/e0f78803-61d6-473d-8a63-f1b5ca0ff919"}],
                        items: [
                            {
                                transport: {
                                    "id": "06237610-58b3-4bdb-8f75-434705398537",
                                    "type": "flight",
                                    "vessel_no": "AA101",
                                    "name": "American Airlines",
                                    "domestic": true,
                                    "capacity": 200,
                                    "call_sign": 'AA',
                                    "created_at": "2017-04-19 14:54:33",
                                    "updated_at": "2017-04-19 14:57:54",
                                    "links": [
                                        {
                                            "rel": "self",
                                            "uri": "/api/transports/06237610-58b3-4bdb-8f75-434705398537"
                                        }
                                    ]
                                },
                                activity: {
                                    name: 'Test Activity',
                                    description: 'Test Activity for Itineraries',
                                    occurred_at: '2017-04-19 14:54:33'
                                },
                                hub: {
                                    name: 'Lester B. Pearson International Airport',
                                    address: '',
                                    call_sign: 'YYZ',
                                    city: 'Toronto',
                                    state: 'Ontario',
                                    zip: '',
                                    country: 'Canada',
                                },
                            }
                        ],
                    }
                ],
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
                this.itineraries.push(this.itineraryObj);
            },
        },
        ready(){
//            this.getItineraries();

//            this.saveItinerary();
        }
    }
</script>