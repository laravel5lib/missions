<template>
    <table class="table">
        <thead>
        <tr>
            <th>Amount</th>
            <th>Percent</th>
            <th>Due</th>
            <th>Grace</th>
            <th><i class="fa fa-cog"></i></th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="payment in payments|orderBy 'due_at'">
            <td>{{ payment.amount_owed|currency }}</td>
            <td>{{ payment.percent_owed|number }}%</td>
            <td>{{ payment.upfront ? 'Upfront' : payment.due_at|moment 'll' }}</td>
            <td>{{ payment.upfront ? 'N/A' : payment.grace_period }} {{ payment.upfront ? '' : (payment.grace_period > 1 ? 'days' : 'day') }}</td>
            <td>
                <a class="btn btn-default btn-xs" @click="editPayment(payment)"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-danger btn-xs" @click="confirmRemove(payment)"><i class="fa fa-times"></i></a>
            </td>
        </tr>
        </tbody>

        <modal title="New Payment" :show.sync="showAddModal" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <validator name="TripPricingCostPaymentAdd">
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="amountOwed">Owed</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentAdd('amount') }">
                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                    <input id="amountOwed" class="form-control" type="number" :max="calculateMaxAmount(newPayment)" number v-model="newPayment.amount_owed"
                                           v-validate:amount="{required: true, min: 0.01}" debounce="100">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentAdd('percent') }">
                                    <input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(cost)" v-model="newPayment.percent_owed|number 2"
                                           v-validate:percent="{required: true, min: 0.01}" debounce="100">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="newPayment.upfront">
                                Due upfront?
                            </label>
                        </div>
                        <div class="row" v-if="!newPayment.upfront">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dueAt">Due</label>
                                    <input id="dueAt" class="form-control input-sm" type="date" v-model="newPayment.due_at" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" :class="{'has-error': checkForErrorPaymentAdd('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentAdd('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="newPayment.grace_period"
                                               v-validate:grace="{required: true, min:0}">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </validator>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetPayment()'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='addPayment'>Add</button>
            </div>

        </modal>
        <modal title="Edit Payment" :show.sync="showEditModal" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <validator name="TripPricingCostPaymentEdit" v-if="selectedPayment">
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="amountOwed">Owed</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentEdit('amount') }">
                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                    <input id="amountOwed" class="form-control" type="number" :max="calculateMaxAmount(selectedPayment)" number v-model="selectedPayment.amount_owed"
                                           v-validate:amount="{required: true, min: 0.01}" debounce="100">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentEdit('percent') }">
                                    <input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(cost)" v-model="selectedPayment.percent_owed|number 2"
                                           v-validate:percent="{required: true, min: 0.01}" debounce="100">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="selectedPayment.upfront">
                                Due upfront?
                            </label>
                        </div>
                        <div class="row" v-if="!selectedPayment.upfront">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dueAt">Due</label>
                                    <input id="dueAt" class="form-control input-sm" type="date" v-model="selectedPayment.due_at" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" :class="{'has-error': checkForErrorPaymentEdit('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForErrorPaymentEdit('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="selectedPayment.grace_period"
                                               v-validate:grace="{required: true, min:0}">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </validator>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, selectedPayment = null'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='updatePayment'>Update</button>
            </div>

        </modal>
        <modal class="text-center" :show.sync="deletePaymentModal" title="Delete Payment" small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedPayment">Are you sure you want to delete {{ selectedPayment.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deletePaymentModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deletePaymentModal = false,remove(selectedPayment)'>Confirm</button>
            </div>
        </modal>

    </table>
</template>
<script type="text/javascript">
    export default{
        props: ['id', 'payments', 'cost'],
    	name: 'admin-trip-costs-payments',
        data(){
            return{
                attemptedAddPayment: false,
                toggleNewPayment: false,
                showAddModal: false,
                showEditModal: false,
                selectedPayment: null,
                deletePaymentModal: false,
                newPayment: {
                    amount_owed: 0,
                    percent_owed: 0,
                    due_at: null,
                    upfront: false,
                    grace_period: 0,
                },
                resource: this.$resource('costs/' + this.id + '/payments{/payment_id}')
            }
        },
        watch:{
            'newPayment.amount_owed': function (val, oldVal) {
                var thePayment = !!this.showEditModal ? this.selectedPayment : this.newPayment;
                var max = this.calculateMaxAmount(thePayment);
                if (val > max)
                    thePayment.amount_owed = this.cost.amount;
                thePayment.percent_owed = (val / this.cost.amount) * 100;
                if ( _.isFunction(this.$validate) )
                    this.$validate('percent', true);
            },
            'newPayment.percent_owed': function (val, oldVal) {
                var thePayment = !!this.showEditModal ? this.selectedPayment : this.newPayment;
                var max = this.calculateMaxPercent(thePayment);
                if (val > max)
                    thePayment.percent_owed = max;
                thePayment.amount_owed = (val / 100) * this.cost.amount;
                if ( _.isFunction(this.$validate) )
                    this.$validate('amount', true);
            },
            /*'costs': function (val, oldVal) {
                this.checkCostsErrors();
            }*/
        },
        methods:{
            checkForErrorPaymentAdd(field){
                return this.$TripPricingCostPaymentAdd && this.$TripPricingCostPaymentAdd[field.toLowerCase()].invalid && this.attemptedAddPayment;
            },
            checkForErrorPaymentEdit(field){
                return this.$TripPricingCostPaymentEdit && this.$TripPricingCostPaymentEdit[field.toLowerCase()].invalid && this.attemptedAddPayment;
            },
            resetPayment(){
                this.newPayment = {
                    amount_owed: 0,
                    percent_owed: 0,
                    due_at: null,
                    upfront: false,
                    grace_period: 0,
                }
            },
            calculateMaxAmount(thePayment){
                var max = this.cost.amount;
                if (this.payments.length) {
                    this.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if(thePayment !== payment) {
                            max -= payment.amount_owed;
                        }
                    }, this);
                }
                return max;
            },
            calculateMaxPercent(thePayment){
                var max = 100;
                if (this.payments.length) {
                    this.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if(thePayment !== payment) {
                            max -= payment.percent_owed;
                        }
                    }, this);
                }
                return max;
            },
            checkCostsErrors(){
                var errors = [];

                if (!this.payments.length) {
                    errors.push('empty');
                } else {
                    // cost payments must total full amount owed and percent owed
                    var amount = 0;
                    this.payments.forEach(function (payment, index) {
                        amount += payment.amount_owed;
                    }, this);
                    // evaluate difference
                    if (amount != cost.amount) {
                        errors.push('incomplete');
                    }
                }

                // no errors
                errors.push(false);
                this.costsErrors = errors;
            },
            editPayment(payment){
                this.showEditModal = true;
                this.selectedPayment = payment;
                this.selectedPayment.due_at = moment(payment.due_at).format('YYYY-MM-DD');
            },
            addPayment(){
                this.attemptedAddPayment = true;
                if(this.$TripPricingCostPaymentAdd.valid) {
                    this.resource.save({}, this.newPayment).then(function (response) {
                        this.payments.push(this.newPayment);
                        this.resetPayment();
                        this.showAddModal = false;
                        this.attemptedAddPayment = false;
                    });

                }
                // this.checkCostsErrors();
            },
            updatePayment(){
                this.attemptedAddPayment = true;
                if(this.$TripPricingCostPaymentEdit.valid) {
                    this.resource.update({payment_id: this.selectedPayment.id}, this.selectedPayment).then(function (response) {
                        this.resetPayment();
                        this.showEditModal = false;
                        this.attemptedAddPayment = false;
                    });

                } else {
                    console.log('Errors');
                }
                this.checkCostsErrors();
            },
            confirmRemove(payment) {
                this.selectedPayment = payment;
                this.deletePaymentModal = true;
            },
            remove(payment){
                this.resource.delete({ payment_id: payment.id }).then(function (response) {
                    this.payments.$remove(payment);
                    this.selectedPayment = null;
                });
            },

        },
        ready(){
            var self = this;
            this.$root.$on('Cost:' + this.id + ':NewPayment', function (cost) {
                self.showAddModal = true;
            })
        }
    }
</script>