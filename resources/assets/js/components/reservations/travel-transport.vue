<template>
	<div>
		<validator name="TravelTransport">
				<form id="TravelTransportForm" novalidate >
					<section>
						<!--<button class="btn btn-xs btn-default-hollow small pull-right"><i class="fa fa-trash"></i> Delete</button>-->
						<div class="form-group" v-error-handler="{ value: transport, client: 'transporttype' }">
							<label for="travel_methodA">Travel Method</label>
							<template v-if="editMode">
								<select class="form-control" name="travel_method" id="travel_method"
								        v-validate:transporttype="['required']" v-model="transport.type">
									<option value="">-- Select--</option>
									<option :value="option" v-for="option in travelTypeOptions">{{option | capitalize}}</option>
								</select>
							</template>

							<p v-else>{{ transport.type | uppercase }}</p>
						</div>

						<template v-if="transport && transport.type === 'flight'">
							<div class="form-group" v-error-handler="{ value: transport.name, client: 'airline' }">
								<label for="travel_methodA">Airline</label>
								<v-select @keydown.enter.prevent=""  class="form-control" id="airlineFilter" :debounce="250" :on-search="getAirlines"
								          :value.sync="selectedAirlineObj" :options="airlinesOptions" label="name"
								          placeholder="Select Airline" v-if="editMode"></v-select>
								<p v-else>{{ transport.name | uppercase }}</p>
								<select class="form-control hidden" name="airline" id="airline" v-validate:airline="['required']"
								        v-model="transport.name">
									<option :value="airline.name" v-for="airline in airlinesOptions">
										{{airline.name | capitalize}}
									</option>
									<option value="other">Other</option>
								</select>
								<template v-if="selectedAirlineObj && selectedAirlineObj.name === 'Other'">
									<div class="form-group">
										<label for="">Airline</label>
										<input type="text" class="form-control" v-model="transport.name" v-if="editMode">
										<p v-else>{{ transport.name | uppercase }}</p>
									</div>
								</template>
								<div class="form-group">
									<label for="">Flight No.</label>
									<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
									<p v-else>{{ transport.vessel_no | uppercase }}</p>
								</div>
							</div>
						</template>

						<template v-if="transport && transport.type === 'bus'">
							<div class="form-group">
								<label for="">Company</label>
								<input type="text" class="form-control" v-model="transport.name" v-if="editMode">
								<p v-else>{{ transport.name | uppercase }}</p>
							</div>
							<div class="form-group">
								<label for="">Schedule/Route No.</label>
								<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
								<p v-else>{{ transport.vessel_no | uppercase }}</p>
							</div>
						</template>

						<template v-if="transport && transport.type === 'train'" v-if="editMode">
							<div class="form-group">
								<label for="travel_methodB">Company</label>
								<template v-if="editMode">
									<select class="form-control" name="travel_methodB" id="train"
									        v-validate:train="['required']" v-model="transport.name">
										<option :value="option" v-for="option in trainOptions">{{option | capitalize}}</option>
									</select>
								</template>
								<p v-else>{{ transport.name | uppercase }}</p>
							</div>
							<div class="form-group">
								<label for="">Train No.</label>
								<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
								<p v-else>{{ transport.vessel_no | uppercase }}</p>
							</div>
						</template>

						<template v-if="transport && transport.type === 'vehicle'">
							<div class="form-group">
								<label for="travel_methodB">Company</label>
								<template v-if="editMode">
									<select class="form-control" name="travel_methodB" id="train"
									        v-validate:train="['required']" v-model="transport.name">
										<option :value="option" v-for="option in vehicleOptions">{{option | capitalize}}
										</option>
									</select>
								</template>

								<p v-else>{{ transport.name | uppercase }}</p>
							</div>
							<div class="form-group">
								<label for="">Vehicle Color (if known)</label>
								<input type="text" class="form-control" v-model="transport.vessel_no" v-if="editMode">
								<p v-else>{{ transport.vessel_no | uppercase }}</p>
							</div>
						</template>
					</section>
					<!--<template v-if="isUpdate && editMode">
						<button class="btn btn-xs btn-primary" type="button" @click="update">Update Travel Method</button>
					</template>-->
				</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    export default{
        name: 'travel-transport',
        mixins: [errorHandler],
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
                airlinesOptions: [],
                trainOptions: [
                    'Amtrak', 'BNSF Railway', 'Canadian National Railway', 'Canadian Pacific Railway',
                    'CSX Transportation', 'Kansas City Southern Railway', 'Norfolk Southern Railway',
                    'Union Pacific Railroad'
                ],
                vehicleOptions: ['Taxi', 'Uber', 'Metro Car', 'Personal', 'Other'],
                selectedAirlineObj: null,
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
                this.$nextTick(function () {
                    this.$validate(true);
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
                this.resetErrors();
            }
        },
        methods: {
            getAirlines(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('utilities/airlines', { params: {search: search, sort: 'name'} }).then(function (response) {
                    this.airlinesOptions = response.body.data;
                    this.airlinesOptions.push({
	                    name: 'Other',
	                    call_sign: ''
                    });
                    if (loading) {
                        loading(false);
                    } else {
                        return this.airlinesOptions;
                    }
                });
            },
            getAirline(reference){
                return this.$http.get('utilities/airlines/' + reference).then(function (response) {
                    return response.body.data;
                });
            },
            update(){
                this.$http.put('transports/' + this.transport.id, this.transport).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Travel Details Updated');
                });
            }
        },
        ready(){
            let self = this;

            let promises = [];
            if (self.isUpdate) {
                let airlinesPromise = this.getAirlines(this.transport.name, false);
                promises.push(airlinesPromise);
            }

            Promise.all(promises).then(function (values) {
                // Update state data
                if (self.isUpdate) {
                    // select airline
                    self.selectedAirlineObj = _.findWhere(self.airlinesOptions, { name: self.transport.name });
                    console.log(self.selectedAirlineObj);
                }
                self.$nextTick(function () {
                    self.$validate(true);
                });

            });

            this.itinerant_id = this.reservationId;
        }
    }
</script>