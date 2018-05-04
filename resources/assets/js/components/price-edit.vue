<template>
<ajax-form method="put" :action="'/' + priceableType + '/' + priceableId + '/prices/' + price.uuid" :initial="initialize" ref="ajax">

    <template slot-scope="{ form }">
        <div class="row">
            <div class="col-md-6">
                <input-currency name="amount" v-model="form.amount">
                    <label slot="label">Amount</label>
                </input-currency>
            </div>
            <div class="col-md-6" v-if="ui.showActiveDate">
                <input-date name="active_at" v-model="form.active_at">
                    <label slot="label">Start Date</label>
                    <span class="help-block" slot="help-text">When does this cost go into effect?</span>
                </input-date>
            </div>
        </div>
        <div class="row" v-if="ui.showPayments" :class="{'has-error' : form.errors.has('payments')}">
            <div class="col-md-12">
                <hr class="divider">
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">The percentages due are for the cumulative total of all costs that apply, not this price alone (i.e. registration + room + flight, etc.).</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Payments</label>
                            <span class="help-block" 
                                v-text="form.errors.get('payments')" 
                                v-if="form.errors.has('payments')">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row" v-for="(payment, index) in form.payments" :key="index">
                    <div class="col-xs-4 col-md-3">
                        <input-number :name="'payments.'+index+'.percentage_due'" v-model="payment.percentage_due" :placeholder="50">
                            <span class="help-block" slot="help-text">Percentage due</span>
                            <span class="input-group-addon" slot="suffix">%</span>
                        </input-number>
                    </div>
                    <div class="col-xs-4 col-md-4">
                        <input-date :name="'payments.'+index+'.due_at'" v-model="payment.due_at">
                            <span class="help-block" slot="help-text">Due Date</span>
                        </input-date>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <input-number :name="'payments.'+index+'.grace_days'" :placeholder="3" v-model="payment.grace_days">
                            <span class="help-block" slot="help-text">Days Grace</span>
                        </input-number>
                    </div>
                    <div class="col-xs-1 col-md-2">
                        <p class="form-control-static">
                            <a class="small" role="button" @click.prevent="removePayment(index)">
                                <i class="fa fa-times"></i>
                            </a>
                        </p>
                    </div>
                </div>

                <a role="button" class="small" @click.prevent="addPayment"><i class="fa fa-plus-circle"></i> Add another payment</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <hr class="divider">
                <button type="submit" class="btn btn-md btn-primary">Save Changes</button>
            </div>
        </div>
    </template>
</ajax-form>
</template>
<script>
export default {
    name: 'price-edit',

    props: ['priceableType', 'priceableId', 'price'],

    data() {
        return {
            initialize: {
                amount: this.price.amount,
            },
            ui: {
                showActiveDate: false,
                showPayments: false
            }
        }
    },

    methods: {
        addPayment() {
            this.$refs.ajax.form.payments.push({
                percentage_due: 0,
                due_at: null,
                grace_days: 0
            });
            this.$forceUpdate()
        },
        removePayment(index) {
            this.$refs.ajax.form.payments.splice(index, 1);
            this.$forceUpdate()
        }
    },

    mounted() {
        if (this.price.payments) {

            this.$refs.ajax.form['active_at'] = this.price.active_at;
            this.$refs.ajax.form.set('active_at', this.price.active_at);

            this.$refs.ajax.form['payments'] = this.price.payments;
            this.$refs.ajax.form.set('payments', this.price.payments);

            this.ui.showPayments = true;
            this.ui.showActiveDate = true;
        }
    }
}
</script>