<template>
    <ajax-form method="post" :action="`campaigns/${campaignId}/flights/itineraries`" :initial="itinerary" :horizontal="true" ref="ajax" @form:success="resetAndCloseForm">
        <template slot-scope="{ form }">
            <div class="alert alert-warning">Booking these flights will create an itinerary for <strong>{{ reservations.length }}</strong> selected reservations.</div>

            <h5>Itinerary Details</h5>
            <input-select name="type" v-model="form.type" :horizontal="true" classes="col-sm-4" :options="types">
                <label slot="label" class="control-label col-sm-4">Type</label>
            </input-select>

            <input-text name="record_locator" v-model="form.record_locator" :horizontal="true" classes="col-sm-4" @input="lookupRecord">
                <label slot="label" class="control-label col-sm-4">Record Locator / Contract #</label>
            </input-text>

            <div class="alert alert-warning" v-if="lockFields"><strong>Itineraries with the same record locator cannot be modified here</strong>. To make changes you'll need to edit the itinerary from the itineraries list.</div>

            <div v-for="(flight, index) in form.flights" :key="index">
                <hr class="divider">
                <h5>{{ getSegmentName(flight.flight_segment_id) }} <a role="button" class="pull-right" @click="removeFlight(index)">X</a></h5>
                <input-text :readonly="lockFields" :name="'flight.' + index + '.type'" v-model="flight.flight_no" :horizontal="true" classes="col-md-2 col-sm-4">
                    <label slot="label" class="control-label col-sm-4">Flight Number</label>
                </input-text>

                <input-text :readonly="lockFields" :name="'flight.' + index + '.iata_code'" v-model="flight.iata_code" :horizontal="true" classes="col-md-2 col-sm-4">
                    <label slot="label" class="control-label col-sm-4">City</label>
                </input-text>

                <input-date :readonly="lockFields" :name="'flight.' + index + '.date'" v-model="flight.date" :horizontal="true" classes="col-md-2 col-sm-4">
                    <label slot="label" class="control-label col-sm-4">Date</label>
                </input-date>

                <input-time :readonly="lockFields" :name="'flight.' + index + '.time'" v-model="flight.time" :horizontal="true" classes="col-md-2 col-sm-4">
                    <label slot="label" class="control-label col-sm-4">24-Hour Time</label>
                </input-time>
            </div>

            <hr class="divider">
            <form class="form-inline">
                <select class="form-control input-sm" v-model="selectedSegment">
                    <option v-for="option in availableSegments" v-bind:value="option.value" :key="option.value">
                        {{ option.text }}
                    </option>
                </select>
                <button class="btn btn-link btn-sm" type="button" @click.prevent="addFlight" :disabled="lockFields">Add Flight</button>
            </form>

            <hr class="divider">
            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-link" @click.prevent="resetAndCloseForm">Cancel</button>
                    <button type="submit" class="btn btn-primary">Book Flights</button>
                </div>
            </div>
        </template>
    </ajax-form>
</template>
<script>
export default {
    props: ['campaignId', 'reservations'],
    data() {
        return {
            segments: [],
            selectedSegment: null,
            itinerary: {
                record_locator: null,
                type: 'individual',
                reservations: _.pluck(this.reservations, 'id'),
                flights: []
            },
            types: {
                'individual': 'Individual',
                'group': 'Group'
            },
            lockFields: false
        }
    },
    computed: {
        availableSegments() {
            let that = this;
            return _.map(this.segments, function(segment) {
                return {value: segment.id, text: segment.name}
            });
        }
    },
    methods: {
        getSegments() {
            this.$http.get(`/campaigns/${this.campaignId}/flights/segments`).then(response => {
                this.segments = response.data.data;
            });
        },
        addFlight() {
            this.$refs.ajax.form.flights.push({
                flight_segment_id: this.selectedSegment,
                flight_no: null,
                iata_code: null,
                date: null,
                time: null
            });
            this.$forceUpdate()
        },
        removeFlight(index) {
            this.$refs.ajax.form.flights.splice(index, 1);
            this.$forceUpdate()
        },
        getSegmentName(segment) {
            return _.findWhere(this.segments, {id: segment}).name;
        },
        resetAndCloseForm() {
            this.$emit('close:form')
        },
        lookupRecord : _.debounce(function (value) {
            // if (value) {
                this.$http.get(`campaigns/${this.campaignId}/flights/itineraries?filter[record_locator]=${value}&include=flights.flight-segment`)
                      .then(response => {
                          
                          let itineraries = response.data.data;

                          if (itineraries.length === 1) {

                              let itinerary = _.first(itineraries);
                              
                              let flights = _.map(itinerary.flights, function (flight) {
                                return {
                                    flight_segment_id: flight.segment.id,
                                    flight_no: flight.flight_no,
                                    iata_code: flight.iata_code,
                                    date: flight.date,
                                    time: flight.time
                                }
                              })

                              this.$refs.ajax.form.record_locator = itinerary.record_locator;
                              this.$refs.ajax.form.type = itinerary.type;
                              this.$refs.ajax.form.flights = flights;

                              this.lockFields = true;

                          } else {
                                this.$refs.ajax.form.type = 'individual';
                                this.$refs.ajax.form.flights = [];

                                this.lockFields = false;
                          }

                          this.$forceUpdate();
                      });
            // }
        }, 1000)
    },
    mounted() {
        return this.getSegments();
    }
}
</script>