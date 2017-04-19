<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<legend>Arrival in Miami</legend>
				<accordion :one-at-atime="false" type="info">
					<panel is-open type="primary" v-for="transport in transports">
						<strong slot="header"><u>Transport {{$index + 1}}</u></strong>
						<travel-transport item="Arrive in Miami" :reservation-id="reservationId" :campaign-id="transport.campaignId" :transport="transport"></travel-transport>
					</panel>
				</accordion>
				<button type="button" class="btn btn-xs btn-primary" @click="addNewTransport">Add Transport</button>
				<br>
				<legend>Returning Home</legend>
				<!--<section>
					<div class="form-group">
						<label for="travel_methodB">Travel Method</label>
						<select class="form-control" name="travel_methodB" id="travel_methodB" v-validate:travelmethodb="['required']" v-model="transport_return.type">
							<option :value="option" v-for="option in travelTypeOptions">{{option | capitalize}}</option>
						</select>
					</div>

					<template v-if="transport_return && transport_return.type === 'flight'">
						<div class="form-group">
							<label for="travel_methodA">Airline</label>
							<select class="form-control" name="airline" id="airline" v-validate:airline="['required']" v-model="selectedAirline">
								<option :value="airline.name" v-for="airline in airlinesOptions">{{airline.name | capitalize}}</option>
								<option value="other">Other</option>
							</select>
							<template v-if="selectedAirline === 'other'">
								<div class="form-group">
									<label for="">Flight No.</label>
									<input type="text" class="form-control" v-model="transport_return.vessel_no">
								</div>
								<div class="form-group">
									<label for="">Airport Code (3 letter IATA)</label>
									<input type="text" class="form-control" v-model="">
								</div>
							</template>
						</div>
					</template>

					<template v-if="transport_return && transport_return.type === 'bus'">
						<div class="form-group">
							<label for="">Company</label>
							<input type="text" class="form-control" v-model="transport_return.name">
						</div>
						<div class="form-group">
							<label for="">Schedule/Route No.</label>
							<input type="text" class="form-control" v-model="transport_return.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Arrival Location</label>
							<input type="text" class="form-control" v-model="">
						</div>
					</template>

					<template v-if="transport_return && transport_return.type === 'train'">
						<div class="form-group">
							<label for="travel_methodB">Company</label>
							<select class="form-control" name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport_return.name">
								<option :value="option" v-for="option in trainOptions">{{option | capitalize}}</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">Train No.</label>
							<input type="text" class="form-control" v-model="transport_return.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Station</label>
							<input type="text" class="form-control" v-model="location.name">
						</div>
					</template>

					<template v-if="transport_return && transport_return.type === 'vehicle'">
						<div class="form-group">
							<label for="travel_methodB">Company</label>
							<select class="form-control" name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport_return.name">
								<option :value="option" v-for="option in vehicleOptions">{{option | capitalize}}</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">Vehicle Color (if known)</label>
							<input type="text" class="form-control" v-model="transport_return.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Drop Off Location</label>
							<input type="text" class="form-control" v-model="location.name">
						</div>
					</template>

					<template v-if="transport_return && (transport_return.type !== 'flight' || selectedAirline === 'other')">
						<div class="form-group">
							<label for="">Arrival Date & Time</label>
							<date-picker :model.sync="date_time|moment 'YYYY-MM-DD HH:mm:ss'" ></date-picker>
							<input type="datetime" class="form-control hidden" v-model="date_time | moment 'LLLL'" id="started_at" required>
						</div>
					</template>
				</section>-->
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
                location: {
                    name: '',
                    address: '',
                    city: '',
                    state: '',
                    country_code: '',
                    phone: '',
                    fax: '',
                    email: '',
                    website: '',
                },
                transport: {
                    type: '',
                    vessel_no: '',
                    name: '',
                    call_sign: '',
                    domestic: '',
                    capacity: '',
                    campaign_id: '',
                    passengers: '',
                },

                // temporary?
                location_return: {
                    name: '',
                    address: '',
                    city: '',
                    state: '',
                    country_code: '',
                    phone: '',
                    fax: '',
                    email: '',
                    website: '',
                },
                transport_return: {
                    type: '',
                    vessel_no: '',
                    name: '',
                    call_sign: '',
                    domestic: '',
                    capacity: '',
                    campaign_id: '',
                    passengers: '',
                },

                travelTypeOptions: ['flight', 'bus', 'vehicle', 'train'],
	            airlinesOptions: [],
                trainOptions: [
                    'Amtrak', 'BNSF Railway', 'Canadian National Railway', 'Canadian Pacific Railway',
	                'CSX Transportation', 'Kansas City Southern Railway', 'Norfolk Southern Railway',
	                'Union Pacific Railroad',
                ],
                vehicleOptions: ['Taxi', 'Uber', 'Metro Car', 'Personal', 'Other',],
                selectedAirline: '',
                transports: [
                    {
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
                    },
                    {
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
                    }
                ],
            }
        },
        watch: {
            selectedAirline(val){
                if (val && val !== 'other') {
                    let airline = _.findWhere(this.airlinesOptions, {name: this.selectedAirline});
                    this.transport.name = airline.name;
                }
            }
        },
        methods: {
	        getTransports(){
                this.$http.get('transports', { params: { include: '', } }).then(function (response) {
                    this.transports = response.body.data;
                });
	        },
	        saveItinerary(){

	        },
	        updateItinerary(){

	        },
            addNewTransport(){
				this.transports.push({
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