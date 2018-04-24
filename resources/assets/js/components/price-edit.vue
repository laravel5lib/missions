<template>
<ajax-form method="put" :action="'/' + priceableType + '/' + priceableId + '/prices/' + id">

    <template slot-scope="props">
        <div class="row">
            <div class="col-md-6">

                <select-cost name="cost_id" :value="price.cost.id">
                    <label slot="label">Select a Cost</label>
                    <span class="help-block" slot="help-text"><a href="">Manage these options &raquo;</a></span>
                </select-cost>

            </div>
            <div class="col-md-6">
                <input-currency name="amount" :value="price.amount">
                    <label slot="label">Amount</label>
                </input-currency>
            </div>
        </div>
        <div class="row" v-if="ui.showActiveDate">
            <div class="col-md-6">

                <input-date name="active_at" :value="price.active_at">
                    <label slot="label">Start Date</label>
                    <span class="help-block" slot="help-text">When does this cost go into effect?</span>
                </input-date>

            </div>
        </div>
        <div class="row" v-if="ui.showPayments">
            <div class="col-md-12">
                <hr class="divider">
                <label>Payments</label>
                <div class="row">
                    <div class="col-xs-3 col-md-2">
                        <input-number name="payments.percent" :placeholder="50">
                            <span class="help-block" slot="help-text">Percentage due</span>
                        </input-number>
                    </div>
                    <div class="col-xs-6 col-md-4">
                        <input-date name="due_at">
                            <span class="help-block" slot="help-text">Due Date</span>
                        </input-date>
                    </div>
                    <div class="col-xs-3 col-md-2">
                        <input-number name="payments.grace" :placeholder="3">
                            <span class="help-block" slot="help-text">Days Grace</span>
                        </input-number>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-md btn-primary">Save Changes</button>
            </div>
        </div>
    </template>

</ajax-form>
</template>
<script>
export default {
    name: 'price-edit',

    props: ['priceableType', 'priceableId', 'id'],

    data() {
        return {
            price: null,
            ui: {
                showActiveDate: false,
                showPayments: false
            }
        }
    },

    mounted() {

        this.$http
            .get('/' + this.priceableType + '/' + this.priceableId + '/prices/' + this.id)
            .then(response => {
                this.price = response.data.data;
            })
            .catch(error => {
                console.log(error);
            });

        this.$root.$on('cost-change', (cost) => {
            if (cost.type == 'incremental') {
                this.ui.showActiveDate = true;
                this.ui.showPayments = true;
            } else {
                this.ui.showActiveDate = false;
                this.ui.showPayments = false;
            }
        });
    }
}
</script>