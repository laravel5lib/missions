<template>
<fetch-json :url="`reservations?campaign=${campaignId}&include=trip.group&sort=surname`">
    <div slot-scope="{ json:reservations, pagination, changePage, loading }">
        <div class="panel-heading">
            <button class="btn btn-primary btn-sm" :disabled="! selectedReservations.length" style="margin-right: 2em;">Book Flights</button>
            <a role="button">+ Add a filter</a>
        </div>
        <div class="panel-body" v-if="loading">
            <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
        </div>
        <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th><input type="checkbox" @change="selectAllReservations(reservations)"></th>
                        <th>Surname</th>
                        <th>Given Names</th>
                        <th>Group</th>
                        <th>Trip Type</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Passport #</th>
                        <th>Passort Exp</th>
                        <th>Percent Raised</th>
                        <th>Current Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations" :key="reservation.id">
                        <td><input type="checkbox" @change="selectReservation(reservation, $event.target.checked)"></td>
                        <td><strong><a :href="`/admin/reservations/${reservation.id}`">{{ reservation.surname }}</a></strong></td>
                        <td>{{ reservation.given_names }}</td>
                        <td>{{ reservation.trip.data.group.data.name }}</td>
                        <td>{{ reservation.trip.data.type }}</td>
                        <td>{{ reservation.birthday | mFormat('ll') }}</td>
                        <td>{{ reservation.gender }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ reservation.percent_raised }}%</td>
                        <td>{{ reservation.current_rate }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-body text-center" v-if="!reservations.length && !loading">
            <span class="lead">No Reservations</span>
            <p>Try adjusting the filters, otherwide all flights have been booked!</p>
        </div>
        <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
            <pager :pagination="pagination.pagination" :callback="changePage"></pager>
        </div>
    </div>
</fetch-json>
</template>
<script>
export default {
    name: 'flights-not-booked',
    props: ['campaignId'],
    data() {
        return {
            selectedReservations: []
        }
    },
    methods: {
        selectReservation(reservation, value) {
            if (value) {
                this.selectedReservations.push(reservation);
            } else {
                console.log(value);
            }
        },
        selectAllReservations(reservations) {
            this.selectedReservations = reservations;
        }
    }
}
</script>
<style>
    th, td {
        white-space: nowrap;
    }
</style>

