<template>
<fetch-json :url="'/' + priceableType + '/' + priceableId+ '/prices'" ref="priceList">
<div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json: prices, loading, pagination }">
     <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h5>Current Pricing</h5>
            </div>
            <div class="col-xs-6 text-right text-muted">
                <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
            </div>
        </div>
    </div>
    <table class="table" v-if="prices && prices.length">
        <thead>
            <tr class="active">
                <th>#</th>
                <th>Cost</th>
                <th>Amount</th>
                <th>Reservations</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(price, index) in prices">
            <tr :key="price.id">
                <td class="text-muted">{{ index+1 }}</td>
                <td>
                    <strong><a :href="'/admin/' + priceableType + '/' + priceableId + '/prices/' + price.id">
                        {{ price.cost.name | capitalize }}
                    </a></strong>
                    <br /><em class="text-muted">{{ price.cost.type | capitalize}}</em>
                </td>
                <td class="col-sm-1 text-right">
                    <strong class="text-primary">{{ currency(price.amount) }}</strong>
                    <br /><span class="label" style="background:#eee; color:#777" v-if="price.custom">Custom</span>
                </td>
                <td class="col-sm-1 text-right">
                    <strong>{{ price.reservations_count }}</strong>
                </td>
                <td>
                    <span v-if="isActive(price.active_at)"><i class="fa fa-check-circle text-success"></i> Active</span>
                    <span v-else><i class="fa fa-calendar-o"></i> Effective Date: <em class="text-primary">{{ price.active_at|mFormat('ll') }}</em></span>
                </td>
            </tr>
            <tr :key="price.id" v-if="price.payments.length > 0">
                <td colspan="5">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th class="col-xs-4">Percent</th>
                                <th class="col-xs-4">Due</th>
                                <th class="col-xs-4">Grace</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="payment in price.payments" :key="payment.id">
                                <td class="col-xs-4">{{ payment.percentage_due.toFixed(0) }}%</td>
                                <td class="col-xs-4">{{ payment.due_at|mFormat('ll') }}</td>
                                <td class="col-xs-4">{{ payment.grace_days}} days</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </template>
        </tbody>
    </table>
    <div class="panel-body text-center" v-else>
        <span class="lead">No Prices</span>
        <p>Add a price to get started.</p>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
        <pager :pagination="pagination"></pager>
    </div>
</div>
</fetch-json>
</template>
<script>
export default {
    name: 'price-list',

    props: ['priceableType', 'priceableId'],

    methods: {
        isActive(date) {
            return moment(date).isAfter(moment()) ? false : true;
        }
    },

    mounted() {
        this.$root.$on('form:success', () => {
            this.$refs.priceList.fetch();
        });
        this.$root.$on('page:change', (pageNumber) => {
            this.$refs.priceList.fetch({page: pageNumber});
        });
    }
}
</script>