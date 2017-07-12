<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
                <aside :show.sync="showFilters" placement="left" header="Transport Filters" :width="375">
                    <transports-filters :filters.sync="filters" :reset-callback="resetFilters" :pagination="pagination" :callback="fetch"></transports-filters>
                </aside>

                <div class="row">
                    <div class="col-xs-12">
                        <h4>Transports</h4>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row">
                    <form class="form-inline">
                        <div class="form-group col-lg-6 col-sm-12 col-md-4">
                            <div class="input-group input-group-sm col-xs-12">
                                <input type="text" class="form-control" v-model="options.params.search" debounce="300" placeholder="Search">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12 col-md-8 text-right">
                            <button type="button" class="btn btn-sm btn-default" @click="showFilters = !showFilters">Filters</button>
                            <button type="button" class="btn btn-sm btn-default" @click="expandAll">Expand All</button>
                            <transport-reports :filters="filters" :search="search"></transport-reports>
                            <button type="button" class="btn btn-primary btn-sm" @click="openTransportModal()">Create a Transport</button>
                        </div>
                        <div class="col-xs-12">
                            <hr class="divider inv">
                        </div>
                    </form>
                </div>

                <ul class="nav nav-tabs">
                    <li :class="isInternationalSet" @click="options.params.isDomestic = 'no'"><a>International</a></li>
                    <li :class="isDomesticSet" @click="options.params.isDomestic = 'yes'"><a>Domestic</a></li>
                </ul>


                <div class="panel-group" id="transportsAccordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default" v-for="transport in transports">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <div class="panel-title">
                                <div class="row">
                                    <div class="col-xs-9">
                                        <div class="media">
                                            <div class="media-body" style="vertical-align:middle;">
                                                <h4 class="media-heading text-capitalize" style="margin-bottom:3px;">
                                                    <a :href="'transports/' + transport.id">
                                                        {{ transport.name }}
                                                        <small> &middot; {{transport.vessel_no}}</small>
                                                    </a>
                                                    <br />
                                                    <small><i class="fa" :class="{ 'fa-bus': transport.type === 'bus', 'fa-plane': transport.type === 'flight', 'fa-car': transport.type === 'vehicle', 'fa-train': transport.type === 'train'}"></i>
                                                        {{ transport.type | capitalize }}
                                                        <span class="label label-default" v-text="transport.domestic ? 'Domestic' : 'International'"></span>
                                                    </small>
                                                </h4>
                                            </div><!-- end media-body -->
                                        </div><!-- end media -->
                                    </div>
                                    <div class="col-xs-3 text-right action-buttons">
                                        <a :href="'transports/' + transport.id" class="btn btn-xs btn-primary">View</a>
                                        <dropdown type="default">
                                            <button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
                                                <span class="fa fa-ellipsis-h"></span>
                                            </button>
                                            <ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
                                                <li><a @click="openTransportModal(transport)"><i class="fa fa-cog"></i> Edit</a></li>
                                                <li><a @click="openTransportDeleteModal(transport)"><i class="fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </dropdown>
                                        <a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" :href="'#transportItem' + $index" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <hr class="divider sm">
                                <div class="row">
                                    <div class="col-sm-3"><label>Depart At</label>
                                        {{transport.depart_at | moment 'lll' false true}}
                                    </div>
                                    <div class="col-sm-3"><label>Depart From</label> {{ transport.departureHub.data.call_sign }}</div>
                                    <div class="col-sm-3"><label>Arrive At</label>
                                        {{transport.arrive_at | moment 'lll' false true}}
                                    </div>
                                    <div class="col-sm-3"><label>Arrive In</label> {{ transport.arrivalHub.data.call_sign }}</div>
                                </div>
                                <hr class="divider sm">
                                <div class="row">
                                    <div class="col-sm-3"><label>Vessel No.</label> {{transport.vessel_no}}</div>
                                    <div class="col-sm-3"><label>Call Sign</label> {{transport.call_sign}}</div>
                                    <div class="col-sm-3"><label>Passengers</label> {{transport.passengers}}</div>
                                    <div class="col-sm-3"><label>Seats Left</label> {{transport.seats_left}}</div>
                                </div>

                            </div>
                        </div>
                        <div :id="'transportItem' + $index" class="panel-collapse collapse transport-item" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Travel Groups</label>
                                        <p class="small" v-if="transport.groups.length > 0">
                                            <template v-for="group in transport.groups">
                                                {{ group.name }}
                                                <template v-if="($index + 1) < transport.groups.length">&middot;</template>
                                            </template>
                                        </p>
                                        <p class="small" v-else>
                                            Add passengers to see groups
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Designations</label>
                                        <p class="small" v-if="transport.designations.length > 0">
                                            <template v-for="designation in transport.designations">
                                                {{ designation.content }}
                                                <template v-if="($index + 1) < transport.designations.length">&middot;</template>
                                            </template>
                                        </p>
                                        <p class="small" v-else>
                                            Add passengers to see designations
                                        </p>

                                    </div>
                                </div><!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <pagination :pagination.sync="pagination" :callback="fetch"></pagination>
                </div>
            </div>
        </div>

        <modal :title="transportsModalEdit? 'Edit Transport' : 'Create Transport'" :ok-text="transportsModalEdit? 'Update' : 'Create'" :callback="handleTransport" :show.sync="showTransportsModal">
            <div slot="modal-body" class="modal-body" v-if="selectedTransport">
                <validator name="TransportsModal">
                    <form id="TransportsModalForm">
                        <div class="form-group">
                            <div class="checkbox">
                            	<label>
                            		<input type="checkbox" v-model="selectedTransport.domestic">
                                    This is a domestic transport
                            	</label>
                            </div>
                        </div>
                        <div class="form-group" v-error-handler="{ value: selectedTransport.type, client: 'type' }">
                            <label for="transportType" class="control-label">Type</label>
                            <select class="form-control" id="transportType"
                                    v-validate:type="['required']" v-model="selectedTransport.type">
                                <option value="">-- Select--</option>
                                <option :value="option" v-for="option in travelTypeOptions">{{option | capitalize}}</option>
                            </select>
                        </div>

                        <template v-if="selectedTransport.type === 'flight'">
                            <div class="form-group" v-error-handler="{ value: selectedTransport.name, client: 'airline' }">
                                <label v-if="!manualAirlineData" for="travel_methodA">Airline</label>
                                <v-select v-if="!manualAirlineData" @keydown.enter.prevent=""  class="form-control" id="airlineFilter" :debounce="250" :on-search="getAirlines"
                                          :value.sync="selectedAirlineObj" :options="UTILITIES.airlines" label="extended_name"
                                          placeholder="Select Airline"></v-select>
                                <select  v-if="manualAirlineData" class="form-control hidden" name="airline" id="airline" v-validate:airline="['required']"
                                        v-model="selectedTransport.name">
                                    <option :value="airline.name" v-for="airline in UTILITIES.airlines">
                                        {{airline.extended_name | capitalize}}
                                    </option>
                                </select>
	                            <label><input type="checkbox" v-model="manualAirlineData"> This is a chartered flight</label>
                                <template v-if="manualAirlineData">
                                    <div class="form-group">
                                        <label for="">Airline Name</label>
                                        <input type="text" class="form-control" v-model="selectedTransport.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Callsign</label>
                                        <input type="text" class="form-control" v-model="selectedTransport.call_sign">
                                    </div>
                                </template>
                            </div>

                            <div class="form-group">
                                <label for="">Flight No.</label>
                                <input type="text" class="form-control" v-model="selectedTransport.vessel_no">
                            </div>
                        </template>

                        <template v-if="selectedTransport.type === 'bus'">
                            <div class="form-group">
                                <label for="">Company</label>
                                <input type="text" class="form-control" v-model="selectedTransport.name">
                            </div>

                            <div class="form-group">
                                <label for="">Callsign</label>
                                <input type="text" class="form-control" v-model="selectedTransport.call_sign">
                            </div>

                            <div class="form-group">
                                <label for="">Schedule/Route No.</label>
                                <input type="text" class="form-control" v-model="selectedTransport.vessel_no">
                            </div>
                        </template>

                        <template v-if="selectedTransport.type === 'train'">
                            <div class="form-group">
                                <label for="travel_methodB">Company</label>
                                    <select class="form-control" name="travel_methodB" id="train"
                                            v-validate:train="['required']" v-model="selectedTransport.name">
                                        <option :value="option" v-for="option in trainOptions">{{option | capitalize}}</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="">Callsign</label>
                                <input type="text" class="form-control" v-model="selectedTransport.call_sign">
                            </div>

                            <div class="form-group">
                                <label for="">Train No.</label>
                                <input type="text" class="form-control" v-model="selectedTransport.vessel_no">
                            </div>
                        </template>

                        <template v-if="selectedTransport.type === 'vehicle'">
                            <div class="form-group">
                                <label for="travel_methodB">Company</label>
                                    <select class="form-control" name="travel_methodB" id="train"
                                            v-validate:train="['required']" v-model="selectedTransport.name">
                                        <option :value="option" v-for="option in vehicleOptions">{{option | capitalize}}
                                        </option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="">Callsign</label>
                                <input type="text" class="form-control" v-model="selectedTransport.call_sign">
                            </div>
                        </template>

                        <div class="form-group">
                            <label for="transportCapacity">Capacity</label>
                            <input id="transportCapacity" v-model="selectedTransport.capacity" type="number" number class="form-control" min="0">
                        </div>
                        <hr class="divider">
                        <h4>Departure</h4>
                        <travel-hub :hub="selectedTransport.departure" :activity-types="UTILITIES.activityTypes"
                                    :activity-type="departureType.id" edit-mode transports
                                    :transport-type="selectedTransport.type"></travel-hub>
                        <div class="form-group" v-error-handler="{ value: selectedTransport.depart_at, client: 'depart_at', messages: {req: 'Please set a date and time', datetime: 'Please set a date and time'} }">
                            <label for="">Departing at Date & Time</label>
                            <date-picker :model.sync="selectedTransport.depart_at | moment 'YYYY-MM-DD HH:mm:ss' false true"></date-picker>
                            <input type="text" class="form-control hidden" v-model="selectedTransport.depart_at | moment 'YYYY-MM-DD HH:mm:ss' false true"
                                   id="depart_at" v-validate:occurred="['required', 'datetime']">
                        </div>
                        <h4>Arrival</h4>
                        <travel-hub :hub="selectedTransport.arrival" :activity-types="UTILITIES.activityTypes"
                                    :activity-type="arrivalType.id" edit-mode transports
                                    :transport-type="selectedTransport.type"></travel-hub>
                        <div class="form-group" v-error-handler="{ value: selectedTransport.arrive_at, client: 'arrive_at', messages: {req: 'Please set a date and time', datetime: 'Please set a date and time'} }">
                            <label for="">Arriving at Date & Time</label>
                            <date-picker :model.sync="selectedTransport.arrive_at | moment 'YYYY-MM-DD HH:mm:ss' false true"></date-picker>
                            <input type="text" class="form-control hidden" v-model="selectedTransport.arrive_at | moment 'YYYY-MM-DD HH:mm:ss' false true"
                                   id="arrive_at" v-validate:occurred="['required', 'datetime']">
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal title="Delete Transport" small ok-text="Delete" :callback="deleteTransport" :show.sync="showTransportDeleteModal">
            <div slot="modal-body" class="modal-body">
                <p v-if="selectedTransport">
                    Are you sure you want to delete the transport: "{{selectedTransport.name}} {{selectedTransport.vessel_no}}" ?
                </p>
            </div>
        </modal>

    </div>
</template>
<script type="text/javascript">
    import $ from 'jquery';
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import errorHandler from '../error-handler.mixin';
    import utilities from '../utilities.mixin';
    import travelHub from '../reservations/travel-hub.vue';
    import transportsFilters from '../filters/transports-filters.vue';
    import transportReports from '../admin/reporting/transportation-reports.vue';
    export default {
        name: 'transports',
        mixins: [errorHandler, utilities],
        components: {vSelect, transportsFilters, travelHub, transportReports},
        props: {
          campaignId: {
            type: String,
            required: true
          }
        },
        data() {
            return {
                validatorHandle: 'TransportsModal',

                transports: [],
                showFilters: false,
                filters: {
                    groups: null,
                    designation: '',
                    trip_type: '',
                    type: '',
                    max_passengers: 9999,
                    min_passengers: 0,
                    include: 'departureHub,arrivalHub'
                },
                options: {
                    params: {
                        isDomestic: 'no',
                        campaign: this.campaignId,
                        per_page: 10,
                        search: '',
                        with: ['groups','designations']
                    }
                },
                pagination: { current_page: 1},

                // type vars
                travelTypeOptions: ['flight', 'bus', 'vehicle', 'train'],
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
                },

                // modal vars
                showTransportsModal: false,
                manualAirlineData: false,
                showTransportDeleteModal: false,
                transportsModalEdit: false,
                selectedTransport: null,

                TransportsResource: this.$resource('campaigns{/campaign}/transports{/transport}', { campaign: this.campaignId})
            }
        },
        watch: {
            showTransportsModal(val) {
                if (!val)
                    this.manualAirlineData = false;
            },
            'options.params.isDomestic'(val){
                this.fetch();
            },
            'options.params.search'(val){
                this.fetch();
            },
            selectedAirlineObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.selectedTransport.name = val.name;
                    this.selectedTransport.call_sign = val.iata;
                }
            },
            selectedTransport: {
                handler(val, oldVal) {
                    if (val && val.name) {
                        this.$nextTick(function () {
                            if (_.isFunction(this.$validate))
                                this.$validate(true);
                        });
                    }

                    if(val && val.type && (!oldVal || val.type !== oldVal.type))
                    if (_.contains(this.travelTypeOptions, oldVal)) {
                        val.name = '';
                        val.vessel_no = '';
                        val.call_sign = '';
                    }
                },
                deep: true
            }
        },
        computed: {
            isDomesticSet() {
                return this.options.params.isDomestic === 'yes' ? 'active' : ''
            },
            isInternationalSet() {
                return this.options.params.isDomestic === 'no' ? 'active' : ''
            },
            arrivalType(){
                return _.findWhere(this.UTILITIES.activityTypes, { name: 'arrival'});
            },
            departureType(){
                return _.findWhere(this.UTILITIES.activityTypes, { name: 'departure'});
            },

        },
        methods: {
            TransportFactory(){
                return {
                    name: '',
                    arrive_at: null,
                    depart_at: null,
                    call_sign: '',
                    type: '',
                    vessel_no: '',
                    domestic: false,
                    capacity: 0,
                    campaign_id: this.campaignId,
                    departure: {
                        name: '',
                        address: '',
                        call_sign: '', // required
                        city: '',
                        state: '',
                        zip: '',
                        country_code: '',
                    },
                    arrival: {
                        name: '',
                        address: '',
                        call_sign: '', // required
                        city: '',
                        state: '',
                        zip: '',
                        country_code: '',
                    },

                };
            },
            changeList(tab) {
                if (tab == 'domestic') {
                    this.options.params.isDomestic = 'yes';
                    this.fetch();
                } else {
                    this.options.params.isDomestic = 'no';
                    this.fetch();
                }
            },
            resetFilters(){
                this.filters = {
                    groups: null,
                    designation: '',
                    trip_type: '',
                    type: '',
                    max_passengers: 9999,
                    min_passengers: 0,
                    include: 'departureHub,arrivalHub'
                };
            },
            fetch() {
                let params = _.extend({}, this.options.params);
                params = _.extend(params, this.filters);
                params.page = _.isObject(this.pagination) ? this.pagination.page : 1;
                this.TransportsResource.get(params).then(function (response) {
                    this.transports = response.body.data;
                    this.pagination = response.body.meta.pagination;
                }, function (error) {
                    this.$dispatch('showError', 'Unable to get data from server.');
                });
            },
            openTransportModal(transport) {
                let thisTransport = _.extend(transport, {
                        departure: {
                            name: '',
                            address: '',
                            call_sign: '', // required
                            city: '',
                            state: '',
                            zip: '',
                            country_code: '',
                        },
                        arrival: {
                            name: '',
                            address: '',
                            call_sign: '', // required
                            city: '',
                            state: '',
                            zip: '',
                            country_code: '',
                        }
                    }) || _.extend({}, this.TransportFactory());
                this.transportsModalEdit = !!transport;

                if (transport) {
                    // if has arrivalHub
                    if (transport.arrivalHub && _.isObject(transport.arrivalHub.data)) {
                        _.extend(thisTransport.arrival, transport.arrivalHub.data)
                    }
                    if (transport.departureHub && _.isObject(transport.departureHub.data)) {
                        _.extend(thisTransport.departure, transport.departureHub.data)
                    }

                    this.selectedTransport = thisTransport;

                    this.getAirlines(transport.name).then(function (obj) {
                        let airline = _.findWhere(this.UTILITIES.airlines, { name: transport.name });
                        if (airline) {
                            this.selectedAirlineObj = airline;
                        } else this.manualAirlineData = true;
                    });

                    this.showTransportsModal = true;
                    this.$nextTick(function () {
                        if (_.isFunction(this.$validate))
                            this.$validate(true);
                    });
                } else {
                    this.selectedTransport = thisTransport;
                    this.showTransportsModal = true;
                }
            },
            openTransportDeleteModal(transport) {
                this.selectedTransport = transport;
                this.showTransportDeleteModal = true;
            },
            handleTransport() {
                this.resetErrors();
                if (this.$TransportsModal.valid && _.isObject(this.selectedTransport)) {
                    let promise;
                    if (this.transportsModalEdit) {
                        promise = this.TransportsResource
                            .update({ transport: this.selectedTransport.id}, this.selectedTransport)
                            .then(function (response) {
                                this.$root.$emit('showSuccess', (this.selectedTransport.domestic ? 'Domestic' : 'International') + ' transport updated successfully');
                            });
                    } else {
                        promise = this.TransportsResource
                            .save(this.selectedTransport)
                            .then(function (response) {
                                this.$root.$emit('showSuccess', (this.selectedTransport.domestic ? 'Domestic' : 'International') + ' transport created successfully');
                            });
                    }

                    promise.then(function () {
                        this.fetch();
                        this.showTransportsModal = false;
                        this.transportsModalEdit = false;
                        this.selectedTransport = null;
                    }, this.$root.handleApiError)
                } else {
                    console.log(this.$TransportsModal);
                    this.$root.$emit('showError', 'Please check the form.');
                }
            },
            deleteTransport(){
                this.TransportsResource.delete({ transport: this.selectedTransport.id}).then(function (response) {
                    this.$root.$emit('showSuccess', 'Transport deleted');
                    this.fetch();
                    this.showTransportDeleteModal = false;
                    this.selectedTransport = null;
                });
            },
            expandAll(){
                if ($.fn.collapse)
                    $('.transport-item').collapse('show');
            },
        },
        ready() {
            this.getActivityTypes();
            this.fetch();
            this.getAirlines();

        }
    }
</script>