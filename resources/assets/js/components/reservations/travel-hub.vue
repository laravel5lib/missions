<template>
	<div>
		<div v-if="hub">

				<form id="TravelHubForm" novalidate>
					<template v-if="transportType === 'flight' || transportType === ''">
						<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
							<label for="travel_methodA">{{ LABELS[(transportType||'flight')] }}</label>
							<template v-if="editMode">
								<v-select @keydown.enter.prevent=""  class="form-control" data-vv-name="hubname" id="airportFilter" :debounce="250" :on-search="getAirports"
								          v-model="selectedAirportObj" :options="UTILITIES.airports" data-vv-value-path="value" label="extended_name" v-validate="'required'"
								          placeholder="Select Airport"></v-select>
								<div class="errors-block"></div>
							</template>

							<template v-if="selectedAirportObj && selectedAirportObj.name === 'Other'">
								<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
									<label for="">{{ LABELS[(transportType||'flight')] }}</label>
									<template v-if="editMode">
										<input type="text" class="form-control" v-model="hub.name" name="hubname" v-validate="'required'">
									</template>
								</div>
								<div  class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
									<label for="">IATA Code</label>
									<template v-if="editMode">
										<input type="text" class="form-control" v-model="hub.call_sign" name="callsign" v-validate="'required'">
									</template>
								</div>
							</template>
							<template v-if="!editMode">
								<p>{{ hub.name.toUpperCase() }}</p>
							</template>
						</div>
						<div class="form-group" v-if="selectedAirportObj && isAdminRoute">
							<br>
							<div class="well well-sm">
								<dl class="dl-horizontal">
									<dt>Name</dt>
									<dd>{{ selectedAirportObj.name }}</dd>
									<dt>City</dt>
									<dd>{{ selectedAirportObj.city }}</dd>
									<dt>Country</dt>
									<dd>{{ selectedAirportObj.country }}</dd>
									<dt>IATA</dt>
									<dd>{{ selectedAirportObj.iata }}</dd>
								</dl>
							</div>
						</div>
					</template>
					<template v-else>
						<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
							<label for="">{{ LABELS[(transportType||'flight')] }}</label>
							<template v-if="editMode">
								<input type="text" class="form-control" v-model="hub.name" name="hubname" v-validate="'required'">
							</template>
							<p v-else>{{ hub.name.toUpperCase() }}</p>
						</div>
						<div class="form-group" v-error-handler="{ value: hub.city, client: 'city' }">
							<label for="">City</label>
							<template v-if="editMode">
								<input type="text" class="form-control" v-model="hub.city" name="city" v-validate="'required'">
							</template>
							<p v-else>{{ hub.city.toUpperCase() }}</p>
						</div>
						<div class="form-group" v-error-handler="{ value: hub.country_code, client: 'country' }">
							<label for="">Country</label>
							<template v-if="editMode">
								<v-select @keydown.enter.prevent="" class="form-control" :debounce="250" :on-search="getCountries"
								          v-model="countryObj" v-validate="'required'" :options="UTILITIES.countries" label="name" name="country"
								          placeholder="Select Country"></v-select>
								<div class="errors-block"></div>
							</template>
							<p v-else>{{ hub.country_code.toUpperCase() }}</p>
						</div>
						<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
							<label for="">CallSign</label>
							<template v-if="editMode">
								<input type="text" class="form-control" v-model="hub.call_sign" name="callsign" v-if="editMode" v-validate="'required'">
							</template>
							<p v-else>{{ hub.call_sign.toUpperCase() }}</p>
						</div>
					</template>
				</form>

		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import utilities from'../utilities.mixin';
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    export default{
        name: 'travel-hub',
        mixins: [utilities, errorHandler],
        components: {vSelect},
	    props: {
            hub: {
                type: Object,
	            default()  {
                    return {
                        name: '',
                        address: '',
                        call_sign: '', // required
                        city: '',
                        state: '',
                        zip: '',
                        country_code: '',
                    }
	            }
            },
		    transportType: {
                type: String,
		    },
            isUpdate: {
                type: Boolean,
                default: false
            },
            transports: {
                type: Boolean,
                default: false
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
                selectedAirportObj: null,
                countryObj: null,
                LABELS: {
                    method: '',
                    vehicle: '',
                    bus: '',
                    train: '',
                }
            }
        },
        computed: {
            /*'isUpdate': function() {
                return this && this.hub.hasOwnProperty('id') && _.isString(this.hub.id);
            }*/
        },
        watch: {
            selectedAirportObj(val, oldVal) {
                if (val && val !== oldVal) {
                    this.hub.name = val.name;
                    this.hub.city = val.city;
                    let country = _.findWhere(this.UTILITIES.countries, { name: val.country });
                    this.hub.country_code = country.code;
                    this.hub.call_sign = val.iata;
                }
            },
            countryObj(val, oldVal) {
                this.hub.country_code = _.isObject(val) ? val.code : null;
                this.$nextTick(() =>  {

                });
            },
	        transportType(val){
                if (val !== 'flight' && !this.transports)
                switch (val) {
	                case 'train':
	                    _.extend(this.hub, {
                            name: '',
                            address: '',
                            call_sign: 'TRN', // required
                            city: '',
                            state: '',
                            zip: '',
                            country_code: '',
                        });
	                    break;
	                case 'bus':
                        _.extend(this.hub, {
                            name: '',
                            address: '',
                            call_sign: 'BUS', // required
                            city: '',
                            state: '',
                            zip: '',
                            country_code: '',
                        });
	                    break;
	                case 'vehicle':
                        _.extend(this.hub, {
                            name: '',
                            address: '',
                            call_sign: 'CAR', // required
                            city: '',
                            state: '',
                            zip: '',
                            country_code: '',
                        });
	                    break;
                }
	        },
	        'hub.name'(val) {
	            this.$nextTick(() =>  {

                });
	        }
        },
        methods: {
            update(){
                this.$http.put('hubs/' + this.hub.id, this.hub).then((response) => {
                    this.$emit('showSuccess', 'Itinerary Station Updated');
                });
            }
        },
        mounted(){
            let self = this;
            let activityType = _.findWhere(this.activityTypes, { id: this.activityType});
            switch (activityType.name) {
                case 'arrival':
                    this.LABELS = {
                        flight: 'Arriving at Airport',
                        vehicle: 'Arriving at',
                        bus: 'Bus Stop Location',
                        train: 'Arriving at Station',
                    };
                    break;
                case 'departure':
                    this.LABELS = {
                        flight: 'Depart from Airport',
                        vehicle: 'Departing from',
                        bus: 'Bus Stop Location',
                        train: 'Depart from Station',
                    };
                    break;
                case 'connection':
                    this.LABELS = {
                        flight: 'Making Connection at Airport',
                        vehicle: 'Connection Location',
                        bus: 'Connecting Bus Stop',
                        train: 'Making Connection at Station',
                    };
                    break;
            }

            let promises = [];
            if (this.transportType === 'flight' ) {
                promises.push(this.getAirports(this.hub.name, false));
            }
            promises.push(this.getCountries(this.hub.country_code, false));

            Promise.all(promises).then((values) => {
				// Update state data
                // select airline
                self.selectedAirportObj = _.findWhere(self.UTILITIES.airports, { name: self.hub.name });
                self.countryObj = _.findWhere(self.UTILITIES.countries, { code: self.hub.country_code });
                //console.log(self.selectedAirportObj);
            });
//            this.attemptSubmit = true;
        }
    }
</script>