<template>
<ajax-form method="post" :action="'/' + priceableType + '/' + priceableId + '/prices'">

    <template slot-scope="props">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Campaign prices will be set as the default pricing for it's trips.</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                <select-cost name="cost_id">
                    <label slot="label">Select a Cost</label>
                    <span class="help-block" slot="help-text"><a href="">Manage these options &raquo;</a></span>
                </select-cost>

            </div>
            <div class="col-md-6">
                <input-currency name="amount" :value="0">
                    <label slot="label">Amount</label>
                </input-currency>
            </div>
        </div>
        <div class="row" v-if="ui.showActiveDate">
            <div class="col-md-6">

                <input-date name="active_at">
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
                    <div class="col-xs-4 col-md-2">
                        <input-number name="payments.percent" :placeholder="50">
                            <span class="help-block" slot="help-text">Percentage due</span>
                        </input-number>
                    </div>
                    <div class="col-xs-8 col-md-4">
                        <input-date name="due_at">
                            <span class="help-block" slot="help-text">Due Date</span>
                        </input-date>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-md btn-primary">Add</button>
            </div>
        </div>
    </template>

</ajax-form>
</template>
<script>
export default {
    name: 'price-add-new',

    props: ['priceableType', 'priceableId'],

    data() {
        return {
            ui: {
                showActiveDate: false,
                showPayments: false
            }
        }
    },

    mounted() {
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