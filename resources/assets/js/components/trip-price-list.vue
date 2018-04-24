<template>
<fetch-json :url="'/trips/' + tripId+ '/prices'" ref="priceList">
<div class="panel panel-default" slot-scope="{ json: prices, loading, pagination }">
     <div class="panel-heading">
        <h5>Current Pricing</h5>
    </div>
    <div class="list-group">
        <div class="list-group-item" v-if="loading">Loading...</div>
        <div class="list-group-item" v-for="price in prices" :key="price.id" v-else>
            <div class="row">
                <div class="col-sm-2 col-xs-12">
                    <h4 class="text-primary">{{ currency(price.amount) }}</h4>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <h5>{{ price.cost.name|capitalize }}</h5>
                </div>
                <div class="col-sm-4 col-xs-6 text-right">
                    <a class="btn btn-xs btn-default-hollow" :href="`/admin/trips/${tripId}/pricing/${price.id}`">Manage</a>
                    <!-- <div style="padding: 0;">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-link dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a>Edit</a></li>
                                <li><a @click="destroy(price.id)">Delete</a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <p class="text-muted small">{{ price.cost.type|capitalize }}</p>
                </div>
                <div class="col-sm-10">
                    <p class="small" v-if="isActive(price.active_at)"><i class="fa fa-check-circle text-success"></i> Active</p>
                    <p class="small" v-else><i class="fa fa-calendar-o"></i> Effective Date: <em class="text-primary">{{ price.active_at|mFormat('ll') }}</em></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="small">{{ price.cost.description }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
        <pager :pagination="pagination"></pager>
    </div>
</div>
</fetch-json>
</template>
<script>
export default {
    name: 'trip-price-list',

    props: ['tripId'],

    methods: {
        isActive(date) {
            return moment(date).isAfter(moment()) ? false : true;
        },
        destroy(priceId) {
            swal('WARNING!', 'This action will remove this price from any reservations using it. This action cannot be undone!', 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Keep",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http.delete('/trips/' + this.tripId + '/prices/' + priceId)
                        .then((response) => {
                            this.$refs.priceList.fetch();
                        });
                }
            })
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