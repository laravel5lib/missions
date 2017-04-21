<template>
	<div>
		<validator name="TravelHub">
			<form id="TravelHubForm" novalidate>
				<div class="form-group">
					<label for="travel_methodA">Airport</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
					          :value.sync="selectedAirportObj" :options="airportsOptions" label="name"
					          placeholder="Select Airport"></v-select>
					<select class="form-control hidden" name="airport" id="airport" v-validate:airport="['required']"
					        v-model="name">
						<option :value="airport.name" v-for="airport in airportsOptions">
							{{airport.name | capitalize}}
						</option>
						<option value="other">Other</option>
					</select>
					<template v-if="selectedAirportObj && selectedAirportObj.name === 'Other'">
						<div class="form-group">
							<label for="">Airport</label>
							<input type="text" class="form-control" v-model="transport.name">
						</div>
					</template>
					<br>
					<div class="well well-sm" v-if="selectedAirportObj">
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
				<hr class="divider inv sm">
				<!--<button class="btn btn-xs btn-default" @click="cancel()">Cancel</button>-->
				<button class="btn btn-xs btn-primary" type="button" @click="confirm()">Confirm</button>
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
            }
	    },
        data(){
            return {
                // mixin settings
                validatorHandle: 'TravelHub',

                // Hub vars
	            name: '',
                address: '',
                call_sign: '',
                city: '',
                state: '',
                zip: '',
                country_code: '',

                airportsOptions: [],
                selectedAirportObj: null,
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
        },
        watch: {
            selectedAirportObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.name = val.name;
                    this.call_sign = val.iata;
                }
            },
        },
	    ready(){
            let self = this;
            let promises = [];
            promises.push(this.getAirports('', false));

            Promise.all(promises).then(function (values) {

            });
	    }
    }
</script>