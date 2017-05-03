<template>
	<div style="position:relative;">
		<template v-if="itinerary && itinerary.id">
			<h6 class="text-uppercase">
				<i class="fa fa-plane"></i> {{itinerary.name}}
				<button class="btn btn-xs btn-default-hollow pull-right" @click="toggleEditMode" v-if="! editMode"><i class="fa fa-pencil"></i> Change</button>
			</h6>
			<hr class="divider lg">
		</template>

		<validator name="TravelItineraries">
				<form id="TravelItinerariesForm" novalidate>
					<spinner v-ref:spinner size="sm" text="Loading"></spinner>

					<div v-if="itinerary">

						<div class="checkbox" v-if="editMode">
							<label for="returningOnOwn">
								<input type="checkbox" id="returningOnOwn" name="returningOnOwn" v-model="returningOnOwn">I don't need a returning international flight.
							</label>
							<div class="alert alert-warning" v-show="returningOnOwn"><strong>NOTICE:</strong> By selecting this option, I am ackowledging that I will be arranging my own transportation home from the destination country.</div>
						</div>
						<div class="checkbox" v-if="editMode">
							<label for="connectionFlight">
								<input id="connectionFlight" type="checkbox" :checked="itinerary.items && itinerary.items.length === 3" name="connectionFlight" @change="toggleConnectionItem(itinerary.items)"> I have a prior travel connection.
							</label>
						</div>
						<accordion :one-at-atime="true" v-if="itinerary.items">
							<panel is-open="false" :header="item.activity.name" v-for="item in itinerary.items">
								<div class="checkbox">
									<label for="noTravelTo" v-if="itinerary.items.length === ($index + 2)">
										<input type="checkbox" id="noTravelTo" name="noTravelTo" :checked="!item.transport.domestic" @change="item.transport.domestic = !item.transport.domestic">I don't need international transportation to the destination.
									</label>
									<div class="alert alert-warning" v-show="!item.transport.domestic"><strong>NOTICE:</strong> By selecting this option, I am arranging my own transportation to the destination country. Please provide those details below.</div>
								</div>

								<div v-if="itinerary.items.length === ($index + 1) && returningOnOwn">
									<p>Returning on own</p>
								</div>
								<div v-else>
									<travel-transport v-ref:transport :edit-mode="editMode" :reservation-id="reservationId" :transport.sync="item.transport"></travel-transport>

									<travel-hub v-ref:hub :edit-mode="editMode" :hub="item.hub" :transport-type="item.transport.type"></travel-hub>

									<travel-activity v-ref:activity :edit-mode="editMode" :activity="item.activity" :simple="true"></travel-activity>
								</div>

							</panel>
						</accordion>
						<hr class="divider sm">

						<!--<button v-if="itinerary.id" class="btn btn-xs btn-default" @click="deleteItinerary(itinerary)">Cancel</button>-->
						<button v-if="!itinerary.id && editMode" class="btn btn-sm btn-primary pull-right" type="button" @click="saveItinerary(itinerary)">Save Itinerary</button>
					</div>

					<!--<hr class="divider">-->
					<!--<button type="button" class="btn btn-xs btn-primary" @click="newItinerary">-->
					<!--New Itinerary-->
					<!--</button>-->
				</form>
			</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    import vSelect from 'vue-select';
    import travelTransport from './travel-transport.vue';
    import travelHub from './travel-hub.vue';
    import travelActivity from './travel-activity.vue';
    export default{
        name: 'travel-itineraries',
        mixins: [errorHandler],
        components: {vSelect, travelTransport, travelHub, travelActivity},
        props: {
            reservationId: {
                type: String,
                required: true,
            },
            document: {
                type: Object,
            },
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'TravelItineraries',

                editMode: true,
                itinerary: null,
                itineraries: [],
                returningOnOwn: false
            }
        },
        watch: {
            selectedAirline(val){
                if (val && val !== 'other') {
                    this.transport.name = val;
                }
            }
        },
        methods: {
            getItinerary(){
                let doc_id = this.document ? this.document.id : this.$parent.requirement.document_id;
                this.$http.get('itineraries/' + doc_id, { params: { 'include': 'activities.hubs,activities.transports'}}).then(function (response) {
                    let itinerary = _.extend(response.body.data, {
                        items: [],
                    });
                    _.each(response.body.data.activities.data, function (activity) {
	                    itinerary.items.push({
                            transport: {
                                id: activity.transports.data[0].id,
                                type: activity.transports.data[0].type,
                                vessel_no: activity.transports.data[0].vessel_no,
                                name: activity.transports.data[0].name,
                                call_sign: activity.transports.data[0].call_sign,
                                domestic: activity.transports.data[0].domestic,
                                capacity: activity.transports.data[0].capacity,
                            },
                            activity: {
                                id: activity.id,
                                name: activity.name,
	                            description: activity.description,
                                occurred_at: activity.occured_at
                            },
                            hub: {
                                id: activity.hubs.data[0].id,
                                name: activity.hubs.data[0].name,
                                address: activity.hubs.data[0].address,
                                call_sign: activity.hubs.data[0].call_sign,
                                city: activity.hubs.data[0].city,
                                state: activity.hubs.data[0].state,
                                zip: activity.hubs.data[0].zip,
                                country: activity.hubs.data[0].country,
                            },
                        });
                    });

                    this.itinerary = itinerary;

                    if (this.itinerary === null)
                        this.newItinerary();
                });
            },
            deleteItinerary(itinerary){
                this.$http.delete('itineraries/travel', itinerary).then(function (response) {
                    this.$emit('showSuccess', 'Itinerary Deleted');
                });
            },
            saveItinerary(itinerary){
                if (this.returningOnOwn)
                    itinerary.items.pop();

                this.$http.post('itineraries/travel', itinerary).then(function (response) {
                    this.itinerary = response.body.data;
                    this.editMode = false;
                    this.setDesignation(response.body.data);
                    this.$emit('showSuccess', 'Itinerary Saved');
                });
            },
            newItinerary(itineraryName){
                let itinerary = {
                    name: itineraryName || 'Itinerary',
                    reservation_id: this.reservationId,
                    items: []
                };
                itinerary.items.push(this.newItineraryItem('Arrive at Training Location'));
                itinerary.items.push(this.newItineraryItem('Depart from Training Location'));
	            this.itinerary = itinerary;
            },
	        newItineraryItem(name){
                return {
                    transport: {
                        type: '',
                        vessel_no: '',
                        name: '',
                        call_sign: '',
                        domestic: true,
                        capacity: '',
                    },
                    activity: {
                        name: name || 'Arrive at Training Location',
                        // description: '',
                        occurred_at: ''
                    },
                    hub: {
                        name: '',
                        address: '',
                        call_sign: '',
                        city: '',
                        state: '',
                        zip: '',
                        country: '',
                    },
                }
	        },
            toggleConnectionItem(items) {
	            if (items.length < 3) {
                    items.splice(0, 0, this.newItineraryItem('Travel Connection'));
                } else {
	                items.splice(0, 1);
	            }
            },
	        toggleEditMode() {
	            this.editMode = true;
	            this.$nextTick(function () {
                    if ( _.isFunction(this.$validate) )
                        this.$validate(true);
                }.bind(this));

	        },
            setDesignation(designation) {
                this.$dispatch('set-document', designation);
            },

        },
        ready(){
            if (this.document || (this.$parent.requirement && this.$parent.requirement.document_id)) {
                this.editMode = false;
                this.getItinerary();
            } else {
                this.newItinerary();
            }

        }
    }
</script>