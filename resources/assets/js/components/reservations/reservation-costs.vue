<template >
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <!-- <div class="row">
            <div class="col-xs-4 tour-step-addons">
                <button class="btn btn-primary btn-sm" @click="add">
                    <span class="fa fa-plus"></span> Addons
                </button>
            </div>
            <div class="col-xs-8 text-right" v-if="reservation && listedCosts !== reservation.costs.data">
                <button class="btn btn-default btn-sm" @click="revert">
                    <i class="fa fa-refresh"></i> <span class="hidden-xs">Revert Changes</span>
                </button>
                <button class="btn btn-primary btn-sm" @click="doUpdate">
                    <i class="fa fa-save"></i> <span class="hidden-xs">Save Changes</span>
                </button>
            </div>
        </div>

        <hr class="divider inv sm"> -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Reservation Cost Breakdown</h5>
            </div><!-- end panel-heading -->

            <div class="list-group">
            <div class="list-group-item" v-for="cost in listedCosts" :class="{'list-group-item-default': cost.unsaved}">
                <div class="row" v-if="cost.type === 'optional'">
                    <div class="col-xs-12">
                        <!-- <a class="btn btn-xs btn-default-hollow pull-right" @click="confirmRemove(cost)"><i class="fa fa-trash"></i> <span class="hidden-xs">Remove</span></a> -->
                        <span v-if="cost.unsaved" class="label label-danger">Unsaved</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Name</label>
                        <p>{{ cost.name }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-3">
                        <label>Amount</label>
                        <p>{{ currency(cost.amount) }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-6">
                        <label>Description</label>
                        <p>{{ cost.description }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                </div>
            </div>
        </div>
        </div><!-- end panel -->

        <modal title="Add Costs" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800" :callback="addCosts" ok-text="Add">
            <div slot="modal-body" class="modal-body">

                    <form class="for" novalidate>
                        <div class="form-group" :class="{ 'has-error': errors.has('costs') }">
                            <label class="control-label">Available Addons or Requests</label>
                            <p class="help-block" v-for="cost in selectedCosts">
                                <strong>{{ cost.name }} - {{ currency(cost.amount) }}</strong>
                                <br />
                                {{ cost.description }}
                                <hr class="divider">
                            </p>
                            <v-select @keydown.enter.prevent=""  class="form-control" id="user" multiple :value="selectedCosts" :options="availableCosts"
                                      label="name"></v-select>
                            <select hidden="" v-model="user_id" name="costs" v-validate="'required'" multiple>
                                <option :value="cost.id" v-for="cost in costs">{{cost.name}}</option>
                            </select>
                        </div>
                    </form>

            </div>
        </modal>

        <modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Cost" :small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedCost">Delete {{ selectedCost.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Delete</button>
            </div>
        </modal>

        <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>{{successMessage}}</p>
        </alert>
    </div>
</template>

<script type="text/javascript">
    import $ from 'jquery';
    import vSelect from 'vue-select';
    export default{
        name: 'reservation-costs',
        props: ['id'],
        components: {vSelect},
        data(){
            return {
                reservation: null,
                reservationsCosts: [],
                costs: [],
                selectedCosts: [],
                availableCosts: [],
                selectedCost: null,
                resource: this.$resource('reservations/' + this.id, {include: 'dues,costs.payments,trip.costs.payments'}),
                showAddModal: false,
                deleteModal: false,
                showNewModal: false,
                attemptSubmit: false,
                showSuccess: false,
                successMessage: '',

                listedCosts: [],
                originalCosts: [],
                temporaryCosts: [],

                preppedReservation: {}
            }
        },
        watch: {
            'temporaryCosts'(val, oldVal) {
                //debugger;
            }
        },
        methods: {
            dateIsBetween(a, b){
                let start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                let stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            isPast(date){
                return moment().isAfter(date);
            },
            add(){
                this.attemptSubmit = false;
                this.showAddModal = true;
            },
            addNew(){
                this.attemptSubmit = false;
                this.showNewModal = true;

            },
            confirmRemove(cost) {
                this.selectedCost = cost;
                this.deleteModal = true;
            },
            remove(){
                let temporaryDues = [];
                let res = $.extend(true, {}, this.reservation);

                // remove cost from temporary array
                if (this.selectedCost.unsaved) {
                    this.temporaryCosts = _.reject(this.temporaryCosts, (tempCost) => {
                        if (tempCost === this.selectedCost) {
                            return true;
                        } else {
                            // generate due based on added costs' payments
                            temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(this.selectedCost));
                        }
                    });

                    temporaryDues = _.uniq(temporaryDues);
                    this.temporaryCosts = _.uniq(this.temporaryCosts);
                } else {
                    this.reservation.costs.data = _.reject(this.reservation.costs.data, (tempCost) => {
                        if (tempCost === this.selectedCost) {
                            res.dues.data = _.reject(res.dues.data, (due) => {
                                return due.cost === tempCost.name;
                            });
                            return true;
                        }
                    });

                    temporaryDues = _.uniq(temporaryDues);
                    this.temporaryCosts = _.uniq(this.temporaryCosts);
                }

                // Merge lists and display
                this.listedCosts = _.union(this.temporaryCosts, this.reservation.costs.data);
                this.listedCosts = _.uniq(this.listedCosts);

                // cleanup Add Costs Modal
                this.getAvailableCosts();

                //Add temporary dues
                res.dues.data = _.union(res.dues.data, temporaryDues);
                this.$root.$emit('Reservation:CostsUpdated', res);

            },
            addCosts(){
                let temporaryDues = [];
                let res = $.extend(true, {}, this.reservation);

                // Add selected costs to temporary list
                _.each(this.selectedCosts, (cost) => {
                    cost.unsaved = true;
                    this.temporaryCosts.push(cost);
                    // generate due based on added costs' payments
                    temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(cost));
                });
                temporaryDues = _.uniq(temporaryDues);
                this.temporaryCosts = _.uniq(this.temporaryCosts);

                // Merge lists and display
                this.listedCosts = _.union(this.temporaryCosts, this.reservation.costs.data);
                this.listedCosts = _.uniq(this.listedCosts);

                // cleanup Add Costs Modal
                this.getAvailableCosts();

                //Add temporary dues
                res.dues.data = _.union(res.dues.data, temporaryDues);
                this.$root.$emit('Reservation:CostsUpdated', res);
            },
            generatePaymentsFromCost(cost){
                let temporaryDues = [];
                // generate due based on added costs' payments
                _.each(cost.payments.data, (payment) => {
                    temporaryDues.push({
                        amount: payment.amount_owed,
                        balance: payment.balance_due,
                        cost: cost.name,
                        due_at: payment.due_at,
                        grace_period: payment.grace_period,
                        status: 'pending',
                        unsaved: true
                    });
                });

                return temporaryDues;
            },
            doUpdate(){
                let costIds = [];

                _.each(this.listedCosts, (cost) => {
                    costIds.push({id: cost.id || cost.cost_id, locked: cost.locked})
                });

                // let res = $.extend(true, {}, this.reservation);
                let res = {};
                res.costs = costIds;

                return this.resource.put(res).then((response) => {
                    this.setReservationData(response.data.data);
                    this.selectedCosts = [];
                    this.temporaryCosts = [];
                    this.successMessage = 'Costs updated Successfully';
                    this.showSuccess = true;
                });
            },
            setReservationData(reservation){
                this.reservation = reservation;
                this.$root.$emit('Reservation:CostsUpdated', reservation);
                this.preppedReservation = {
                    given_names: this.reservation.given_names,
                    surname: this.reservation.surname,
                    gender: this.reservation.gender,
                    status: this.reservation.status,
                    shirt_size: this.reservation.shirt_size,
                    birthday: this.reservation.birthday,
                    // user_id: this.reservation.user_id,
                    // trip_id: this.reservation.trip_id,
                };

                this.getAvailableCosts();
                this.listedCosts = reservation.costs.data;
                this.originalCosts = $.extend(true, {}, this.listedCosts);
            },
            getAvailableCosts(){
                this.selectedCosts = [];

                // get available optional costs intersect with current
                this.availableCosts = _.filter(this.reservation.trip.data.costs.data, (cost) => {
                    if (!_.findWhere(this.reservation.costs.data, {cost_id: cost.id}) && !_.findWhere(this.temporaryCosts, {id: cost.id}) && cost.type === 'optional') {
                        cost.removal = false;
                        return true;
                    }
                });
            },
            revert(){
                this.temporaryCosts = [];
                this.resource.get().then((response) => {
                    this.setReservationData(response.data.data);
                });
            },
        },
        mounted(){
            // this.$refs.spinner.show();
            this.resource.get().then((response) => {
                this.setReservationData(response.data.data);
                // this.$refs.spinner.hide();
            });
        }
    }
</script>