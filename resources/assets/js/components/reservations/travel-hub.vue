<template>
	<div>
		<validator name="TravelHub">
			<form id="TravelHubForm" novalidate>
				<template v-if="transportType === 'flight' || transportType === ''">
					<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
						<label for="travel_methodA">Airport</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
						          :value.sync="selectedAirportObj" :options="airportsOptions" label="name"
						          placeholder="Select Airport" v-if="editMode"></v-select>
						<select class="form-control hidden" name="airport" id="airport" v-validate:hubname="['required']"
						        v-model="hub.name">
							<option :value="airport.name" v-for="airport in airportsOptions">
								{{airport.name | capitalize}}
							</option>
							<option value="other">Other</option>
						</select>
						<template v-if="selectedAirportObj && selectedAirportObj.name === 'Other'">
							<div class="form-group" v-error-handler="{ value: hub.name, client: 'hubname' }">
								<label for="">Airport</label>
								<input type="text" class="form-control" v-model="hub.name" v-if="editMode" v-validate:hubname="['required']">
							</div>
							<div  class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
								<label for="">IATA Code</label>
								<input type="text" class="form-control" v-model="hub.call_sign" v-validate:callsign="['required']" v-if="editMode">
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
						<label for="">{{ transportType === 'train' ? 'Station Name' : 'Drop off location' }}</label>
						<input type="text" class="form-control" v-model="hub.name" v-validate:hubname="['required']" v-if="editMode">
						<p v-else>{{ hub.name | uppercase }}</p>
					</div>
					<div class="form-group" v-error-handler="{ value: hub.city, client: 'city' }">
						<label for="">City</label>
						<input type="text" class="form-control" v-model="hub.city" v-validate:city="['required']" v-if="editMode">
						<p v-else>{{ hub.city | uppercase }}</p>
					</div>
					<div class="form-group" v-error-handler="{ value: hub.country, client: 'country' }">
						<label for="">Country</label>
						<input type="text" class="form-control" v-model="hub.country" v-validate:country="['required']" v-if="editMode">
						<p v-else>{{ hub.country | uppercase }}</p>
					</div>
					<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: hub.call_sign, client: 'callsign' }">
						<label for="">CallSign</label>
						<input type="text" class="form-control" v-model="hub.call_sign" v-validate:callsign="['required']" v-if="editMode">
						<p v-else>{{ hub.call_sign | uppercase }}</p>
					</div>
				</template>
				<template v-if="isUpdate && editMode">
					<button class="btn btn-xs btn-primary" type="button" @click="update">Update Location</button>
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
		    }
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'TravelHub',

                airportsOptions: [],
                selectedAirportObj: null,
            }
        },
        computed: {
            'isUpdate': function() {
                return this && this.hub.hasOwnProperty('id') && _.isString(this.hub.id);
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
            update(){
                this.$http.put('hubs/' + this.hub.id, this.hub).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Station Updated');
                });
            }
        },
        watch: {
            selectedAirportObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.hub.name = val.name;
                    this.hub.city = val.city;
                    this.hub.country = val.country;
                    this.hub.call_sign = val.iata;
                }
            },
	        transportType(val){
                if (val !== 'flight')
                switch (val) {
	                case 'train':
                        this.hub.call_sign = 'TRN';
	                    break;
	                case 'bus':
                        this.hub.call_sign = 'BUS';
	                    break;
	                case 'vehicle':
                        this.hub.call_sign = 'CAR';
	                    break;
                }
	        }
        },
	    ready(){
            let self = this;
            let promises = [];
            promises.push(this.getAirports(this.hub.name, false));

            Promise.all(promises).then(function (values) {
				// Update state data
                // select airline
                self.selectedAirportObj = _.findWhere(self.airportsOptions, { name: self.hub.name });
                console.log(self.selectedAirportObj);

            });
            this.attemptSubmit = true;
        }
    }
</script>