<template>
    <div>
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

            <tr v-for="payment in orderByProp(payments, 'due_at')">
                <td>{{ currency(payment.amount_owed) }}</td>
                <td>{{ payment.percent_owed.toFixed(2) }}%</td>
                <td v-if="payment.due_at">{{ payment.due_at|moment('lll') }}</td>
                <td v-else>Upfront</td>
                <td>{{ payment.upfront ? 'N/A' : payment.grace_period }} {{ payment.upfront ? '' : (payment.grace_period > 1 ? 'days' : 'day') }}</td>
                <td>
                    <template v-if="app.user.can.create_costs || app.user.can.update_costs">
                        <a class="btn btn-default btn-xs" @click="editPayment(payment)">
                           <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-danger btn-xs" @click="confirmRemove(payment)">
                            <i class="fa fa-times"></i>
                        </a>
                    </template>
                </td>
            </tr>
            </tbody>
        </table>
        <modal title="New Payment" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">

                <form class="form-inline" data-vv-scope="payment-add">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="amountOwed">Owed</label>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm" :class="{'has-error': errors.has('amount', 'payment-add') }">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                <input id="amountOwed" class="form-control" type="number" :max="calculateMaxAmount(newPayment)" number v-model="newPayment.amount_owed"
                                       name="amount" @change="modifyPercentOwed(newPayment)" v-validate="'required|min_value:0'">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm" :class="{'has-error': errors.has('percent', 'payment-add') }">
                                <input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(newPayment)" v-model="newPayment.percent_owed"
                                       name="percent" @change="modifyAmountOwed(newPayment)" v-validate="'required|min_value:0'">
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
                                <label for="dueAt">Due</label><br />
                                <date-picker input-sm v-model="newPayment.due_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" :class="{'has-error': errors.has('grace', 'payment-add') }">
                                <label for="grace_period">Grace Period</label>
                                <div class="input-group input-group-sm" :class="{'has-error': errors.has('grace', 'payment-add') }">
                                    <input id="grace_period" type="number" class="form-control" number v-model="newPayment.grace_period"
                                           name="grace" v-validate="'required|min_value:0'">
                                    <span class="input-group-addon">Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetPayment()'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='addPayment'>Add</button>
            </div>

        </modal>
        <modal title="Edit Payment" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <template v-if="selectedPayment">

                    <form class="form-inline" data-vv-scope="payment-edit">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="amountOwed">Owed</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': errors.has('amount', 'payment-edit') }">
                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                    <input id="amountOwed" class="form-control" type="number" number :max="calculateMaxAmount(selectedPayment)" v-model="selectedPayment.amount_owed"
                                           name="amount" v-validate="'required|min_value:0'" @change="modifyPercentOwed(selectedPayment)" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm" :class="{'has-error': errors.has('percent', 'payment-edit') }">
                                    <input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(selectedPayment)" v-model="selectedPayment.percent_owed"
                                           name="percent" v-validate="'required|min_value:0'" @change="modifyAmountOwed(selectedPayment)">
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
                                    <label for="dueAt">Due</label><br />
                                    <date-picker input-sm v-model="selectedPayment.due_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" :class="{'has-error': errors.has('grace', 'payment-edit') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': errors.has('grace', 'payment-edit') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="selectedPayment.grace_period"
                                               name="grace" v-validate="'required|min_value:0'">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </template>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, selectedPayment = null'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='updatePayment'>Update</button>
            </div>

        </modal>
        <modal class="text-center" :value="deletePaymentModal" @closed="deletePaymentModal=false" title="Delete Payment" :small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedPayment">Delete {{ selectedPayment.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deletePaymentModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deletePaymentModal = false,remove(selectedPayment)'>Delete</button>
            </div>
        </modal>
    </div>

</template>
<script type="text/javascript">
    export default{
        props: ['id', 'payments', 'cost'],
        name: 'payment-manager',
        data(){
            return {
                app: MissionsMe,
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
            }
        },
        watch: {
            'showEditModal'(val, oldVal) {
                this.$nextTick(() =>  {
                    // if edit modal closes, reset data
                    if (val !== oldVal && val === false) {
                        this.resetPayment();
                    }
                })
            },
            'showAddModal'(val, oldVal) {
                this.$nextTick(() =>  {
                    // if add modal closes, reset data
                    if (val !== oldVal && val === false) {
                        this.resetPayment();
                    }
                })
            }

        },
        methods: {
            resetPayment(){
                this.newPayment = {
                    amount_owed: 0,
                    percent_owed: 0,
                    due_at: null,
                    upfront: false,
                    grace_period: 0,
                };
                this.selectedPayment = null;
            },
            modifyAmountOwed(val){
                let max = this.calculateMaxAmount(val);
                if (val.amount_owed > max)
                    val.amount_owed = this.cost.amount;
                val.amount_owed = this.cost.amount * (val.percent_owed / 100);
            },
            modifyPercentOwed(val){
                let max = this.calculateMaxPercent(val);
                if (val.percent_owed > max)
                    val.percent_owed = max;
                val.percent_owed = val.amount_owed / this.cost.amount * 100;
            },
            calculateMaxAmount(thePayment){
                let max = this.cost.amount;
                if (this.payments.length) {
                    this.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if (thePayment !== payment) {
                            max -= payment.amount_owed;
                        }
                    }, this);
                }
                return max;
            },
            calculateMaxPercent(thePayment){
                let max = 100;
                if (this.payments.length) {
                    this.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if (thePayment !== payment) {
                            max -= payment.percent_owed;
                        }
                    }, this);
                }
                return max;
            },
            checkCostsErrors(){
                let errors = [];

                if (!this.payments.length) {
                    errors.push('empty');
                } else {
                    // cost payments must total full amount owed and percent owed
                    let amount = 0;
                    this.payments.forEach(function (payment, index) {
                        amount += payment.amount_owed;
                    }, this);
                    // evaluate difference
                    if (amount != this.cost.amount) {
                        errors.push('incomplete');
                    }
                }

                // no errors
                errors.push(false);
                this.costsErrors = errors;

                this.$root.$emit('CheckPaymentsSync');
            },
            editPayment(payment){
                this.showEditModal = true;
                this.selectedPayment = payment;
                this.selectedPayment.due_at = moment(payment.due_at).format('YYYY-MM-DD');
            },
            addPayment(){
                this.attemptedAddPayment = true;
                this.$validator.validateAll('payment-add').then(result => {
                    if (result) {
                        this.$root.$emit('SpinnerOn');
                        this.$http.post(`costs/${this.id}/payments`, this.newPayment).then((response) => {
                            this.payments.push(this.newPayment);
                            this.resetPayment();
                            this.showAddModal = false;
                            this.attemptedAddPayment = false;
                            this.$root.$emit('SpinnerOff');
                        }, (error) => {
                            console.log(error.data.errors);
                            this.$root.$emit('SpinnerOff');
                        });

                    }
                });
                this.checkCostsErrors();
            },
            updatePayment(){
                this.attemptedAddPayment = true;
                this.$validator.validateAll('payment-edit').then(result => {
                    if (result) {
                        if (this.selectedPayment.due_at === 'Invalid date') {
                            this.selectedPayment.due_at = null;
                        }
                        this.$root.$emit('SpinnerOn');
                        this.$http.put(`costs/${this.id}/payments/${this.selectedPayment.id}`, this.selectedPayment).then((response) => {
                            this.resetPayment();
                            this.showEditModal = false;
                            this.attemptedAddPayment = false;
                            this.$root.$emit('SpinnerOff');
                        }, (error) => {
                            console.log(error.data.errors);
                            this.$root.$emit('SpinnerOff');
                        });

                    } else {
                        console.log('Errors');
                    }
                });
                this.checkCostsErrors();
            },
            confirmRemove(payment) {
                this.selectedPayment = payment;
                this.deletePaymentModal = true;
            },
            remove(payment){
                this.$http.delete(`costs/${this.id}/payments/${payment.id}`).then((response) => {
                    this.payments.$remove(payment);
                    this.selectedPayment = null;
                });
            },

        },
        mounted(){
            let self = this;
            this.$root.$on('Cost:' + this.id + ':NewPayment', function (cost) {
                self.showAddModal = true;
            });
        }
    }
</script>