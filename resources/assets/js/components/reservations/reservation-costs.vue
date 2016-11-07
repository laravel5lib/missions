<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <div class="row">
            <div class="col-sm-4">
                <button class="btn btn-primary btn-xs" @click="add">
                    <span class="fa fa-plus"></span> Add Optional Costs
                </button>
            </div>
            <div class="col-sm-8 text-right" v-if="reservation && listedCosts !== reservation.costs.data">
                <button class="btn btn-default btn-xs" @click="revert">
                    <span class="fa fa-refresh"></span> Revert Changes
                </button>
                <button class="btn btn-primary btn-xs" @click="doUpdate">
                    <span class="fa fa-save"></span> Save Changes
                </button>
            </div>
        </div>

        <hr class="divider inv sm">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Costs</h5>
            </div><!-- end panel-heading -->

            <div class="list-group">
            <div class="list-group-item" v-for="cost in listedCosts" :class="{'list-group-item-info': cost.unsaved}">
                <div class="row" v-if="cost.type === 'optional'">
                    <div class="col-md-6">
                        <button class="btn btn-xs btn-danger" @click="confirmRemove(cost)">
                            <span><i class="fa fa-trash"></i> Remove</span>
                        </button>

                    </div>
                    <div class="col-xs-6 text-right" v-if="cost.unsaved">
                        <span class="label label-info">Unsaved</span>
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
                        <p>{{ cost.amount|currency }}</p>
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

        <modal title="Add Costs" :show.sync="showAddModal" effect="fade" width="800" :callback="addCosts">
            <div slot="modal-body" class="modal-body">
                <validator name="AddCost">
                    <form class="for" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('costs') }">
                            <label class="control-label">Available Costs</label>
                            <v-select class="form-control" id="user" multiple :value.sync="selectedCosts" :options="availableCosts"
                                      label="name"></v-select>
                            <select hidden="" v-model="user_id" v-validate:costs="{ required: true }" multiple>
                                <option :value="cost.id" v-for="cost in costs">{{cost.name}}</option>
                            </select>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal class="text-center" :show.sync="deleteModal" title="Delete Cost" small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedCost">Are you sure you want to delete {{ selectedCost.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Confirm</button>
            </div>
        </modal>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{successMessage}}</p>
        </alert>
    </div>
</template>

<script type="text/javascript">
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
            'temporaryCosts': function (val) {
                //debugger;
            }
        },
        methods: {
            dateIsBetween(a, b){
                var start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                var stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddCost[field].invalid && this.attemptSubmit;
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
                var temporaryDues = [];
                var res = jQuery.extend(true, {}, this.reservation);

                // remove cost from temporary array
                if (this.selectedCost.unsaved) {
                    this.temporaryCosts = _.reject(this.temporaryCosts, function (tempCost) {
                        if (tempCost === this.selectedCost) {
                            return true;
                        } else {
                            // generate due based on added costs' payments
                            temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(this.selectedCost));
                        }
                    }.bind(this));

                    temporaryDues = _.uniq(temporaryDues);
                    this.temporaryCosts = _.uniq(this.temporaryCosts);
                } else {
                    this.reservation.costs.data = _.reject(this.reservation.costs.data, function (tempCost) {
                        if (tempCost === this.selectedCost) {
                            res.dues.data = _.reject(res.dues.data, function (due) {
                                return due.cost === tempCost.name;
                            })
                            return true;
                        }
                    }.bind(this));

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
                var temporaryDues = [];
                var res = jQuery.extend(true, {}, this.reservation);

                // Add selected costs to temporary list
                _.each(this.selectedCosts, function (cost) {
                    cost.unsaved = true;
                    this.temporaryCosts.push(cost);
                    // generate due based on added costs' payments
                    temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(cost));
                }.bind(this));
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
                var temporaryDues = [];
                // generate due based on added costs' payments
                _.each(cost.payments.data, function (payment) {
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
                var costIds = [];

                _.each(this.listedCosts, function (cost) {
                    costIds.push({id: cost.id || cost.cost_id, locked: cost.locked})
                });

                var res = jQuery.extend(true, {}, this.reservation);
                res.costs = costIds

                return this.resource.update(res).then(function (response) {
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
                    user_id: this.reservation.user_id,
                    trip_id: this.reservation.trip_id,
                };

                this.getAvailableCosts();
                this.listedCosts = reservation.costs.data;
                this.originalCosts = jQuery.extend(true, {}, this.listedCosts);
            },
            getAvailableCosts(){
                this.selectedCosts = [];

                // get available optional costs intersect with current
                this.availableCosts = _.filter(this.reservation.trip.data.costs.data, function (cost) {
                    if (!_.findWhere(this.reservation.costs.data, {cost_id: cost.id}) && !_.findWhere(this.temporaryCosts, {id: cost.id}) && cost.type === 'optional') {
                        cost.removal = false;
                        return true;
                    }
                    ;
                }.bind(this));
            },
            revert(){
                this.temporaryCosts = [];
                this.resource.get().then(function (response) {
                    this.setReservationData(response.data.data);
                });
            },
        },
        ready(){
            this.resource.get().then(function (response) {
                this.setReservationData(response.data.data);
            });
        }
    }
</script>