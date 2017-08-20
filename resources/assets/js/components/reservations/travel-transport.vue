<template>
	<div>
		<div v-if="transport">

				<form id="TravelTransportForm" novalidate >
					<section>
						<div class="form-group" v-error-handler="{ value: transport, client: 'transporttype' }">
							<label for="transporttype">Travel Method</label>
							<template v-if="editMode">
								<select class="form-control" id="travel_method"
								        name="transporttype=" v-validate="'required'" v-model="transport.type">
									<option value="">-- Select--</option>
									<option :value="option" v-for="option in travelTypeOptions">{{ option|capitalize }}</option>
								</select>
							</template>

							<p v-else>{{ transport.type.toUpperCase() }}</p>
						</div>

						<template v-if="transport && transport.type === 'flight'">
							<div class="form-group" v-error-handler="{ value: transport.name, client: 'airline' }">
								<label v-if="!manualAirlineData" for="travel_methodA">Airline</label>
								<template v-if="editMode">
									<v-select v-if="!manualAirlineData" @keydown.enter.prevent=""  class="form-control" id="airlineFilter" :debounce="250" :on-search="getAirlines"
									          v-model="selectedAirlineObj" :options="UTILITIES.airlines" label="extended_name"
									          placeholder="Select Airline"></v-select>
									<select v-if="!manualAirlineData" class="form-control hidden" name="airline" id="airline" v-validate="'required'"
									        v-model="transport.name">
										<option :value="airline.name" v-for="airline in UTILITIES.airlines">
											{{ airline.extended_name|capitalize }}
										</option>
									</select>
									<label><input type="checkbox" v-model="manualAirlineData"> Airline not listed</label>
								</template>
								<p v-else>{{ transport.name.toUpperCase() }}</p>
								<template v-if="manualAirlineData && editMode">
									<div class="form-group">
										<label for="">Airline</label>
										<input type="text" class="form-control" v-model="transport.name" v-if="editMode">
										<p v-else>{{ transport.name.toUpperCase() }}</p>
									</div>
									<div class="form-group">
										<label for="">Callsign</label>
										<input type="text" class="form-control" v-model="transport.call_sign" v-if="editMode">
										<p v-else>{{ transport.name.toUpperCase() }}</p>
									</div>
								</template>
								<div class="form-group">
									<label for="">Flight No.</label>
									<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
									<p v-else>{{ transport.vessel_no.toUpperCase() }}</p>
								</div>
							</div>
						</template>

						<template v-if="transport && transport.type === 'bus'">
							<div class="form-group">
								<label for="">Company</label>
								<input type="text" class="form-control" v-model="transport.name" v-if="editMode">
								<p v-else>{{ transport.name.toUpperCase() }}</p>
							</div>
							<div class="form-group">
								<label for="">Schedule/Route No.</label>
								<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
								<p v-else>{{ transport.vessel_no.toUpperCase() }}</p>
							</div>
						</template>

						<template v-if="transport && transport.type === 'train'">
							<div class="form-group">
								<label for="travel_methodB">Company</label>
								<template v-if="editMode">
									<select class="form-control" name="travel_methodB" id="train"
									        v-model="transport.name" v-validate="'required'">
										<option :value="option" v-for="option in trainOptions">{{ option|capitalize }}</option>
									</select>
								</template>
								<p v-else>{{ transport.name.toUpperCase() }}</p>
							</div>
							<div class="form-group">
								<label for="">Train No.</label>
								<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
								<p v-else>{{ transport.vessel_no.toUpperCase() }}</p>
							</div>
						</template>

						<template v-if="transport && transport.type === 'vehicle'">
							<div class="form-group">
								<label for="travel_methodB">Company</label>
								<template v-if="editMode">
									<select class="form-control" name="travel_methodB" id="train"
									        v-validate="'required'" v-model="transport.name">
										<option :value="option" v-for="option in vehicleOptions">{{ option|capitalize }}
										</option>
									</select>
								</template>

								<p v-else>{{ transport.name.toUpperCase() }}</p>
							</div>
						</template>
					</section>
				</form>

		</div>

	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import errorHandler from'../error-handler.mixin';
    import utilities from'../utilities.mixin';
    import vSelect from 'vue-select';
    export default{
        name: 'travel-transport',
        mixins: [utilities, errorHandler],
        components: {vSelect},
        props: {
            reservationId: {
                type: String,
            },
	        transport: {
                type: Object,
		        default: {
                    type: '',
                    vessel_no: '',
                    name: '',
                    call_sign: '',
                    domestic: true
		        }
	        },
            editMode: {
                type: Boolean,
                default: true
            },
            activityTypes: {
                type: Array
            },
            activityType: {
                type: String
            }
        },
        data(){
            return {
                validatorHandle: 'TravelTransport',

                //logic variables
                travelTypeOptions: ['flight', 'bus', 'vehicle', 'train'],
                trainOptions: [
                    'Amtrak', 'BNSF Railway', 'Canadian National Railway', 'Canadian Pacific Railway',
                    'CSX Transportation', 'Kansas City Southern Railway', 'Norfolk Southern Railway',
                    'Union Pacific Railroad'
                ],
                vehicleOptions: ['Taxi', 'Uber', 'Metro Car', 'Personal', 'Other'],
                selectedAirlineObj: null,
                manualAirlineData: false,
	            LABELS: {
                    method: '',
		            vehicle: '',
		            bus: '',
		            train: '',
	            }
            }
        },
        watch: {
            selectedAirlineObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.transport.name = val.name;
                    this.transport.call_sign = val.call_sign;
                }
            },
            noFlightNeeded(val) {
                this.transport.domestic = false;
            },
            'transport.name'(val) {
                this.$nextTick(() =>  {


                });
            },
	        'transport.type'(val, oldVal) {
                if (_.contains(this.travelTypeOptions, oldVal)) {
                    this.transport.name = '';
                    this.transport.vessel_no = '';
                    this.transport.call_sign = '';
                }
	        }

        },
	    computed: {
            'isUpdate': function() {
                return this && this.transport.hasOwnProperty('id') && _.isString(this.transport.id);
		    }
	    },
        events: {
            'validate-itinerary'() {

            }
        },
        methods: {
            getAirline(reference){
                return this.$http.get('utilities/airlines/' + reference).then((response) => {
                    return response.data.data;
                },
                    (response) =>  {
                        console.log(response);
                    });
            },
            update(){
                this.$http.put('transports/' + this.transport.id, this.transport).then((response) => {
                    this.$emit('showSuccess', 'Itinerary Travel Details Updated');
                },
                    (response) =>  {
                        console.log(response);
                    });
            }
        },
        mounted(){
            let self = this;
            let promises = [];
            if (this.transport.type === 'flight')
	            promises.push(this.getAirlines(this.transport.name, false));

            Promise.all(promises).then((values) => {
                // Update state data
                if (self.isUpdate) {
                    // select airline
                    let airline = _.findWhere(self.UTILITIES.airports, { name: self.transport.name });
                    if (airline) {
                        self.selectedAirportObj = airline
                    } else {
                        if (self.editMode && self.transport.name !== '')
                            self.manualAirlineData = true;
                    }
	                //console.log(self.selectedAirlineObj);
                }
                self.$nextTick(() =>  {
                    if (_.isFunction(self.$validate))
                        self.$validate(true);
                });

            });

            this.itinerant_id = this.reservationId;
        }
    }
</script>