<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <h5>Transports</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <button type="button" class="btn" :class="isInternationalSet" @click="options.params.isDomestic = 'no'">International</button>
                                    <button type="button" class="btn" :class="isDomesticSet" @click="options.params.isDomestic = 'yes'">Domestic</button>
                                </div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="button" class="btn btn-primary btn-sm" @click="openTransportModal()">Create a Transport</button>
                            </div>
                        </div>


                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transport in transports">
                                <td>{{ transport.type | capitalize }}</td>
                                <td>{{ transport.name }}</td>
                                <td>{{ transport.vessel_no }}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary-hollow" :href="'transports/' + transport.id">Select</a>
                                    <a @click="openTransportModal(transport)"><i class="fa fa-cog"></i></a>
                                    <a @click="openTransportDeleteModal(transport)"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

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
                                <label for="travel_methodA">Airline</label>
                                <v-select @keydown.enter.prevent=""  class="form-control" id="airlineFilter" :debounce="250" :on-search="getAirlines"
                                          :value.sync="selectedAirlineObj" :options="UTILITIES.airlines" label="extended_name"
                                          placeholder="Select Airline"></v-select>
                                <select class="form-control hidden" name="airline" id="airline" v-validate:airline="['required']"
                                        v-model="selectedTransport.name">
                                    <option :value="airline.name" v-for="airline in UTILITIES.airlines">
                                        {{airline.extended_name | capitalize}}
                                    </option>
                                    <option value="other">Other</option>
                                </select>
                                <template v-if="selectedAirlineObj && selectedAirlineObj.name === 'Other'">
                                    <div class="form-group">
                                        <label for="">Airline</label>
                                        <input type="text" class="form-control" v-model="selectedTransport.name">
                                    </div>
                                </template>
                                <div class="form-group">
                                    <label for="">Flight No.</label>
                                    <input type="text" class="form-control" v-model="selectedTransport.vessel_no">
                                </div>
                            </div>
                        </template>

                        <template v-if="selectedTransport.type === 'bus'">
                            <div class="form-group">
                                <label for="">Company</label>
                                <input type="text" class="form-control" v-model="selectedTransport.name">
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
                        </template>

                        <div class="form-group">
                            <label for="transportCapacity">Capacity</label>
                            <input id="transportCapacity" v-model="selectedTransport.capacity" type="number" number class="form-control" min="0">
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
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import errorHandler from '../error-handler.mixin';
    import utilities from '../utilities.mixin';
    export default {
        name: 'transports',
        mixins: [errorHandler, utilities],
        components: {vSelect},
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
                options: {
                    params: {
                        isDomestic: 'yes',
                        campaign: this.campaignId,
                        per_page: 10,
                        search: '',
                        filters: []
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
                showTransportDeleteModal: false,
                transportsModalEdit: false,
                selectedTransport: null,

                TransportsResource: this.$resource('transports{/transport}')
            }
        },
        watch: {
            'options.params.isDomestic'(val){
                this.fetch();
            },
            selectedAirlineObj(val, oldVal){
                if (val && val !== oldVal) {
                    this.selectedTransport.name = val.name;
                    this.selectedTransport.call_sign = val.call_sign;
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
                return this.options.params.isDomestic === 'yes' ? 'btn-default' : 'btn-default-hollow'
            },
            isInternationalSet() {
                return this.options.params.isDomestic === 'no' ? 'btn-default' : 'btn-default-hollow'
            },
        },
        methods: {
            TransportFactory(){
                return {
                    name: '',
                    call_sign: '',
                    type: '',
                    vessel_no: '',
                    domestic: false,
                    capacity: 0,
                    campaign_id: this.campaignId

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
            fetch() {
                this.TransportsResource.get(this.options.params).then(function (response) {
                    this.transports = response.body.data;
                    this.pagination = response.body.meta.pagination;
                }, function (error) {
                    this.$dispatch('showError', 'Unable to get data from server.');
                });
            },
            openTransportModal(transport) {
                this.transportsModalEdit = !!transport;
                this.selectedTransport = transport || _.extend({}, this.TransportFactory());
                if (transport) {
                    this.getAirlines(transport.name).then(function (obj) {
                        this.selectedAirlineObj = _.findWhere(this.UTILITIES.airlines, { name: transport.name });
                        this.showTransportsModal = true;
                        this.$nextTick(function () {
                            if (_.isFunction(this.$validate))
                                this.$validate(true);
                        });
                    });
                } else {
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
                    })
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
        },
        ready() {
            this.fetch();
            this.getAirlines();
        }
    }
</script>