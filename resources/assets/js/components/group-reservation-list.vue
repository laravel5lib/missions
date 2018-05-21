<template>
    <fetch-json :url="url" ref="tripList">
        <div class="panel panel-default" 
                style="border-top: 5px solid #f6323e" 
                slot-scope="{ json: reservations, loading, pagination, changePage }"
        >
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Reservations <span class="badge badge-default">{{ pagination.pagination.total }}</span>
                            <span class="pull-right text-muted" v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</span>
                        </h5>
                    </div>
                    <div class="col-xs-6 text-right">
                       <hr class="divider inv sm">
                       <a role="button" class="text-muted"><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Funded</th>
                        <th>Requirements</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                        <td>{{ index+1 }}</td>
                        <td class="col-sm-4">
                            <strong><a :href="'/dashboard/reservations/' + reservation.id">{{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}</a></strong>
                        </td>
                        <td>
                            {{ reservation.desired_role.name }}
                        </td>
                        <td class="col-sm-1 text-right">
                            <strong>{{ reservation.percent_raised }}%</strong>
                        </td>
                        <td>
                            <span class="label label-success">{{ complete(reservation) }}</span>
                            <span class="label label-info">{{ attention(reservation) }}</span>
                            <span class="label label-default">{{ reviewing(reservation) }}</span>
                            <span class="label label-danger">{{ getIncomplete(reservation) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="panel-body text-center" v-else>
                <span class="lead">No reservations yet.</span>
                <p>Make sure your trip's registration form is public and accessible. Be sure to get the word out!</p>
            </div>
            </div>
            <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
                <pager :pagination="pagination.pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>
</template>
<script>
export default {
    props: ['url'],

    methods: {
        getIncomplete(reservation) {
            return _.where(reservation.requirements.data, {status: 'incomplete'}).length;
        },
        complete(reservation) {
            return _.where(reservation.requirements.data, {status: 'complete'}).length;
        },
        attention(reservation) {
            return _.where(reservation.requirements.data, {status: 'attention'}).length;
        },
        reviewing(reservation) {
            return _.where(reservation.requirements.data, {status: 'reviewing'}).length;
        }
    }
}
</script>
