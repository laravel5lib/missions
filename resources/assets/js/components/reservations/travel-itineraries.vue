<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<legend>Arrival in Miami</legend>
				<div v-for="transport in arrivals">
					<travel-transport :reservation-id="reservationId" :campaign-id="transport.campaignId" :transport="transport"></travel-transport>
				</div>
				<hr class="divider inv sm">
				<button type="button" class="btn btn-xs btn-primary" @click="addNewTransport" v-if="arrivals <= 2">Have a prior connection?</button>
				<br>
				<br>
				<legend>Returning Home</legend>
				<div v-for="transport in returnings">
					<travel-transport :reservation-id="reservationId" :campaign-id="transport.campaignId" :transport="transport" :returning="true"></travel-transport>
				</div>
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    import travelTransport from './travel-transport.vue';
    export default{
        name: 'travel-itineraries',
        mixins: [errorHandler],
        components: {vSelect, travelTransport},
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
                itinerant_id: '',
                itinerant_type: 'reservations',
                item: 'Arrive in Miami',
                date_time: '',

                travelTypeOptions: ['flight', 'bus', 'vehicle', 'train'],
	            airlinesOptions: [],
                trainOptions: [
                    'Amtrak', 'BNSF Railway', 'Canadian National Railway', 'Canadian Pacific Railway',
	                'CSX Transportation', 'Kansas City Southern Railway', 'Norfolk Southern Railway',
	                'Union Pacific Railroad',
                ],
                vehicleOptions: ['Taxi', 'Uber', 'Metro Car', 'Personal', 'Other',],
                selectedAirline: '',
	            arrivals: [{
                    "id": "06237610-58b3-4bdb-8f75-434705398537",
                    "campaign_id": "80ea6760-719c-46c7-8b0e-cf2ddc76fd60",
                    "type": "flight",
                    "vessel_no": "AA101",
                    "name": "American Airlines",
                    "domestic": true,
                    "capacity": 0,
                    "call_sign": null,
                    "created_at": "2017-04-19 14:54:33",
                    "updated_at": "2017-04-19 14:57:54",
                    "links": [
                        {
                            "rel": "self",
                            "uri": "/api/transports/06237610-58b3-4bdb-8f75-434705398537"
                        }
                    ]
                }],
                returnings: [{
                    "id": "06237610-58b3-4bdb-8f75-434705398537",
                    "campaign_id": "80ea6760-719c-46c7-8b0e-cf2ddc76fd60",
                    "type": "flight",
                    "vessel_no": "AA101",
                    "name": "American Airlines",
                    "domestic": true,
                    "capacity": 0,
                    "call_sign": null,
                    "created_at": "2017-04-19 14:54:33",
                    "updated_at": "2017-04-19 14:57:54",
                    "links": [
                        {
                            "rel": "self",
                            "uri": "/api/transports/06237610-58b3-4bdb-8f75-434705398537"
                        }
                    ]
                }],
                transports: [],
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
	        getTransports(){
                this.$http.get('transports', { params: { include: '', } }).then(function (response) {
                    let transports = response.body.data;
                });
	        },
	        saveItinerary(){

	        },
	        updateItinerary(){

	        },
            addNewTransport(){
				this.arrivals.push({
                    type: '',
                    vessel_no: '',
                    name: '',
                    call_sign: '',
                    domestic: '',
                    capacity: '',
                    campaign_id: '80ea6760-719c-46c7-8b0e-cf2ddc76fd60',
                    passengers: '',
                });
	        },
        },
        ready(){
//			this.getTransports();
        }
    }
</script>