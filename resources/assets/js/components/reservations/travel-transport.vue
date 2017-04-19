<template>
	<div>
		<validator name="TravelTransport">
			<form id="TravelTransportForm" novalidate>
				<legend></legend>
				<section>
					<button class="btn btn-xs btn-default-hollow small pull-right"><i class="fa fa-trash"></i> Delete</button>
					<div class="form-group">
						<label for="travel_methodA">Travel Method</label>
						<select class="form-control" name="travel_methodA" id="travel_methodA"
						        v-validate:transporttype="['required']" v-model="transport.type">
							<option value="">-- Select--</option>
							<option :value="option" v-for="option in travelTypeOptions">{{option | capitalize}}</option>
						</select>
					</div>

					<template v-if="transport && transport.type === 'flight'">
						<div class="form-group">
							<label for="travel_methodA">Airline</label>
							<select class="form-control" name="airline" id="airline" v-validate:airline="['required']"
							        v-model="selectedAirline">
								<option :value="airline.name" v-for="airline in airlinesOptions">
									{{airline.name | capitalize}}
								</option>
								<option value="other">Other</option>
							</select>
							<template v-if="selectedAirline === 'other'">
								<div class="form-group">
									<label for="">Airline</label>
									<input type="text" class="form-control" v-model="airline.name">
								</div>
							</template>
							<div class="form-group">
								<label for="">Flight No.</label>
								<input type="text" class="form-control" v-model="transport.vessel_no">
							</div>
							<div class="form-group">
								<label for="">Airport Code (3 letter IATA)</label>
								<input type="text" class="form-control" v-model="">
							</div>

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
							<select class="form-control" name="travel_methodB" id="train"
							        v-validate:train="['required']" v-model="transport.name">
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
							<select class="form-control" name="travel_methodB" id="train"
							        v-validate:train="['required']" v-model="transport.name">
								<option :value="option" v-for="option in vehicleOptions">{{option | capitalize}}
								</option>
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
							<date-picker :model.sync="date_time|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
							<input type="datetime" class="form-control hidden" v-model="date_time | moment 'LLLL'"
							       id="started_at" required>
						</div>
					</template>
					<hr class="divider inv">
					<button class="btn btn-default" @click="cancel()">Cancel</button>
					<button class="btn btn-primary" @click="save()">Update</button>

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
        name: 'travel-transport',
        mixins: [errorHandler],
        components: {vSelect},
        props: {
            item: {
                type: String,
                required: true
            },
            reservationId: {
                type: String,
//                required: true,
            },
            campaignId: {
                type: String,
//                required: true,
            },
        },
        data(){
            return {
                validatorHandle: 'TravelTransport',
//                item: 'Arrive in Miami',
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

                //logic variables
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
        methods: {
            getAirlines(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('utilities/airlines', { params: {search: search} }).then(function (response) {
                    this.airlinesOptions = response.body.airlines;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.airlinesOptions;
                    }
                })
            },
	        cancel() {},
	        save() {},
        },
        ready(){
            let airlinesPromise = this.getAirlines('', false);

            Promise.all([airlinesPromise]).then(function (values) {
                // Update state data
            });

            this.itinerant_id = this.reservationId;
//			this.transport.campaign_id = this.campaignId;
        }
    }
</script>