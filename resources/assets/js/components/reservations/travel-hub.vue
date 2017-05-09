<template>
	<div>
		<validator name="TravelHub" v-if="hub">
			<form id="TravelHubForm" novalidate>
				<template v-if="transportType === 'flight' || transportType === ''">
					<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
						<label for="travel_methodA">{{ LABELS[(transportType||'flight')] }}</label>
						<template v-if="editMode">
							<v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
							          :value.sync="selectedAirportObj" :options="airportsOptions" label="name"
							          placeholder="Select Airport"></v-select>
							<select class="form-control hidden" name="airport" id="airport" v-validate:hubname="['required']"
							        v-model="hub.name">
								<option :value="airport.name" v-for="airport in airportsOptions">
									{{airport.name | capitalize}}
								</option>
								<option value="other">Other</option>
							</select>
							<div class="errors-block"></div>
						</template>

						<template v-if="selectedAirportObj && selectedAirportObj.name === 'Other'">
							<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
								<label for="">{{ LABELS[(transportType||'flight')] }}</label>
								<template v-if="editMode">
									<input type="text" class="form-control" v-model="hub.name" v-validate:hubname="['required']">
								</template>
							</div>
							<div  class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
								<label for="">IATA Code</label>
								<template v-if="editMode">
									<input type="text" class="form-control" v-model="hub.call_sign" v-validate:callsign="['required']">
								</template>
							</div>
						</template>
						<template v-if="!editMode">
							<p>{{ hub.name | uppercase }}</p>
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
							<input type="text" class="form-control" v-model="hub.name" v-validate:hubname="['required']">
						</template>
						<p v-else>{{ hub.name | uppercase }}</p>
					</div>
					<div class="form-group" v-error-handler="{ value: hub.city, client: 'city' }">
						<label for="">City</label>
						<template v-if="editMode">
							<input type="text" class="form-control" v-model="hub.city" v-validate:city="['required']">
						</template>
						<p v-else>{{ hub.city | uppercase }}</p>
					</div>
					<div class="form-group" v-error-handler="{ value: hub.country_code, client: 'country' }">
						<label for="">Country</label>
						<template v-if="editMode">
							<v-select @keydown.enter.prevent="" class="form-control" :debounce="250" :on-search="getCountries"
							          :value.sync="countryObj" :options="countriesOptions" label="name"
							          placeholder="Select Country"></v-select>
							<select class="form-control hidden" name="country" id="country" v-validate:country="['required']"
							        v-model="hub.country_code">
								<option :value="country.code" v-for="country in countriesOptions">
									{{country.name | capitalize}}
								</option>
							</select>
							<div class="errors-block"></div>
						</template>
						<p v-else>{{ hub.country_code | uppercase }}</p>
					</div>
					<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
						<label for="">CallSign</label>
						<template v-if="editMode">
							<input type="text" class="form-control" v-model="hub.call_sign" v-validate:callsign="['required']" v-if="editMode">
						</template>
						<p v-else>{{ hub.call_sign | uppercase }}</p>
					</div>
				</template>
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    export default{
        name: 'travel-hub',
        mixins: [errorHandler],
        components: {vSelect},
	    props: {
            hub: {
                type: Object,
	            default: {
                    name: '',
                    address: '',
                    call_sign: '', // required
                    city: '',
                    state: '',
                    zip: '',
                    country_code: '',
	            }
            },
		    transportType: {
                type: String,
		    },
            isUpdate: {
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
                // mixin settings
                validatorHandle: 'TravelHub',

                airportsOptions: [],
                countriesOptions: [],
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
            'isUpdate': function() {
                return this && this.hub.hasOwnProperty('id') && _.isString(this.hub.id);
            }
        },
        events: {
            'validate-itinerary'() {
                this.resetErrors();
            }
        },
        watch: {
            selectedAirportObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.hub.name = val.name;
                    this.hub.city = val.city;
                    let country = _.findWhere(this.countriesOptions, { name: val.country });
                    this.hub.country_code = country.code;
                    this.hub.call_sign = val.iata;
                }
            },
            'countryObj':function (val, oldVal) {
                this.hub.country_code = _.isObject(val) ? val.code : null;
                this.$nextTick(function () {
                    this.$validate(true);
                });
            },
	        transportType(val){
                if (val !== 'flight')
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
	            this.$nextTick(function () {
		            this.$validate(true);
                });
	        }
        },
        methods: {
            getAirports(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('utilities/airports', { params: {search: search, sort: 'name'} }).then(function (response) {
                    this.airportsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.airportsOptions;
                    }
                });
            },
            getAirport(reference){
                return this.$http.get('utilities/airports/' + reference).then(function (response) {
                    return response.body.data;
                });
            },
            getCountries(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('utilities/countries', { params: { search: search} }).then(function (response) {
                    this.countriesOptions = response.body.countries;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.countriesOptions;
                    }
                })
            },
            update(){
                this.$http.put('hubs/' + this.hub.id, this.hub).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Station Updated');
                });
            }
        },
        ready(){
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
            promises.push(this.getAirports(this.hub.name, false));
            promises.push(this.getCountries(this.hub.country_code, false));

            Promise.all(promises).then(function (values) {
				// Update state data
                // select airline
                self.selectedAirportObj = _.findWhere(self.airportsOptions, { name: self.hub.name });
                self.countryObj = _.findWhere(self.countriesOptions, { code: self.hub.country_code });
                //console.log(self.selectedAirportObj);

            });
//            this.attemptSubmit = true;
        }
    }
</script>