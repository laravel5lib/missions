<template>
    <div class="list-group">
        <data-list :url="'trips/' + tripId + '/costs'">
            <template slot="empty">
                <hr class="divider inv">
                <p class="text-center text-muted lead">No costs found.</p>
                <p class="text-center">
                    <button class="btn btn-default-hollow btn-sm" @click="$emit('open:add-cost-modal')">Add Cost</button>
                </p>
            </template>
            <template slot-scope="props" slot="item">
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-sm-2 col-xs-12">
                            <h4 class="text-primary">{{ currency(props.item.amount) }}</h4>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <h5>{{ props.item.name|capitalize }}</h5>
                        </div>
                        <div class="col-sm-4 col-xs-6 text-right">
                            <div style="padding: 0;">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-link dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <template v-if="props.item.type == 'incremental'">
                                            <li><a>Add New Payment</a></li>
                                            <li class="divider"></li>
                                        </template>
                                        <li><a>Edit</a></li>
                                        <li><a @click="destroy(props.item.id)">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <p class="text-muted small">{{ props.item.type|capitalize }}</p>
                        </div>
                        <div class="col-sm-10">
                            <p class="small" v-if="isActive(props.item.active_at)"><i class="fa fa-check-circle text-success"></i> Active</p>
                            <p class="small" v-else><i class="fa fa-calendar-o"></i> Effective Date: <em class="text-primary">{{ props.item.active_at|mFormat('ll') }}</em></p>
                        </div>
                    </div>

                    <div class="list-group small" v-if="props.item.type == 'incremental'">
                        <div class="list-group-item" v-for="payment in props.item.payments" :key="payment.id">
                            <div class="row">
                                <div class="col-xs-2">
                                    <strong>{{ payment.percent_owed.toFixed(2) }}%</strong> Due
                                </div>
                                <div class="col-xs-4">
                                    {{ payment.due_at|mFormat('ll') }}
                                </div>
                                <div class="col-xs-4">
                                    <strong>{{ payment.grace_period }} day</strong> grace period
                                </div>
                                <div class="col-xs-2 text-right">
                                    <a href="#"><i class="fa fa-pencil"></i></a>
                                    <a href="#"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <p class="small">{{ props.item.description }}</p>
                        </div>
                    </div>

                </div>
            </template>
        </data-list>

    </div>
</template>
<script>
export default {
    name: 'trip-price-list',

    props: ['tripId'],

    methods: {
        isActive(date) {
            return moment(date).isAfter(moment()) ? false : true;
        },
        destroy(costId) {
            swal('WARNING!', 'This action will remove this cost from any reservations using it. This action cannot be undone!', 'warning', {
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
                        closeModal: false
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http.delete('/trips/' + this.tripId + '/costs/' + costId)
                        .then((response) => {
                            console.log('deleted');
                            this.$root.$emit('list:refresh');
                        });
                }
            })
        }
    }
}
</script>