<template>
	<div style="position:relative;">
		<validator name="TravelItineraries">
			<form id="TravelItinerariesForm" novalidate>
				<spinner v-ref:spinner size="sm" text="Loading"></spinner>

				<legend>{{itinerary.item}}</legend>
				<section>
					<div class="form-group">
						<label for="travel_methodA">Travel Method</label>
						<select class="form-control" name="travel_methodA" id="travel_methodA" v-validate:transporttype="['required']" v-model="transport.type">
							<option :value="option" v-for="option in travelTypeOptions">{{option | capitalize}}</option>
						</select>
					</div>

					<template v-if="transport && transport.type === 'flight'">
						<div class="form-group">
							<label for="travel_methodA">Airline</label>
							<select class="form-control" name="airline" id="airline" v-validate:airline="['required']" v-model="selectedAirline">
								<option :value="airline.name" v-for="airline in airlinesOptions">{{airline.name | capitalize}}</option>
								<option value="other">Other</option>
							</select>
							<template v-if="selectedAirline === 'other'">
								<div class="form-group">
									<label for="">Flight No.</label>
									<input type="text" class="form-control" v-model="transport.vessel_no">
								</div>
								<div class="form-group">
									<label for="">Airport Code (3 letter IATA)</label>
									<input type="text" class="form-control" v-model="">
								</div>
							</template>
						</div>
					</template>

					<template v-if="transport && transport.type === 'bus'">
						<div class="form-group">
							<label for="">Company</label>
							<input type="text" class="form-control" v-model="transport.name">
						</div>
						<div class="form-group">
							<label for="">Schedule/Route No.</label>
							<input type="text" class="form-control" v-model="transport.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Arrival Location</label>
							<input type="text" class="form-control" v-model="">
						</div>
					</template>

					<template v-if="transport && transport.type === 'train'">
						<div class="form-group">
							<label for="travel_methodB">Company</label>
							<select name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport.name">
								<option :value="option" v-for="option in trainOptions">{{option | capitalize}}</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">Train No.</label>
							<input type="text" class="form-control" v-model="transport.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Station</label>
							<input type="text" class="form-control" v-model="location.name">
						</div>
					</template>

					<template v-if="transport && transport.type === 'vehicle'">
						<div class="form-group">
							<label for="travel_methodB">Company</label>
							<select name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport.name">
								<option :value="option" v-for="option in vehicleOptions">{{option | capitalize}}</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">Vehicle Color (if known)</label>
							<input type="text" class="form-control" v-model="transport.vessel_no">
						</div>
						<div class="form-group">
							<label for="">Drop Off Location</label>
							<input type="text" class="form-control" v-model="location.name">
						</div>
					</template>

					<template v-if="transport && (transport.type !== 'flight' || selectedAirline === 'other')">
						<div class="form-group">
							<label for="">Arrival Date & Time</label>
							<date-picker :model.sync="date_time|moment 'YYYY-MM-DD HH:mm:ss'" ></date-picker>
							<input type="datetime" class="form-control hidden" v-model="date_time | moment 'LLLL'" id="started_at" required>
						</div>
					</template>
				</section>

				<legend>Returning Home</legend>
				<section>
					<div class="form-group">
						<label for="travel_methodB">Travel Method</label>
						<select name="travel_methodB" id="travel_methodB" v-validate:travelmethodb="['required']" v-model="transport_return.type">
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
							<select name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport_return.name">
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
							<select name="travel_methodB" id="train" v-validate:train="['required']" v-model="transport_return.name">
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
				</section>
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    export default{
        name: 'travel-itineraries',
        mixins: [errorHandler],
        components: {vSelect},
        props: {
            reservationId: {
                type: String,
                required: true,
            },
            campaignId: {
                type: String,
                //required: true,
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
            }
        },
        watch: {
            selectedAirline(val){
                if (val && val !== 'other') {
                    let airline = _.findWhere(this.airlinesOptions, {iata: this.selectedAirline});
                    this.transport.name = airline.name;
                }
            }
        },
        methods: {
            getAirlines(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('utilities/airlines', { params: {search: search} }).then(function (response) {
                    this.airlinesOptions = response.body.data;
                    loading ? loading(false) : void 0;
                })
            },
	        saveItinerary(){

	        },
	        updateItinerary(){

	        },
        },
        ready(){
            let airlinesPromise = this.$http.get('utilities/airlines').then(function (response) {
                this.airlinesOptions = response.body.data;
            });

			this.itinerant_id = this.reservationId;
			this.transport.campaign_id = this.campaignId;

			Promise.all([airlinesPromise]).then(function (values) {
				// Update state data
            })
        }
    }
</script>