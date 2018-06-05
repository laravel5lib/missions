<template>
<fetch-json :url="`campaigns/${campaignId}/flights/passengers?filter[segment]=${segmentId}`" ref="passengersList">
<div slot-scope="{ json:passengers, pagination, changePage, loading }">
    <div class="panel-body" v-if="loading">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="passengers && passengers.length">
                <thead>
                    <tr class="active">
                        <th><input type="checkbox"></th>
                        <th>Surname</th>
                        <th>Given Names</th>
                        <th>Group</th>
                        <th>Trip</th>
                        <th>Type</th>
                        <th>Record Locator</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Flight</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="passenger in passengers" :key="passenger.id">
                        <td><input type="checkbox"></td>
                        <td><strong><a href="#">{{ passenger.surname }}</a></strong></td>
                        <td>{{ passenger.given_names }}</td>
                        <td>{{ passenger.group }}</td>
                        <td>{{ passenger.trip }}</td>
                        <td><em>{{ passenger.type }}</em></td>
                        <td>{{ passenger.record_locator }}</td>
                        <td>{{ passenger.flight.date | mFormat('ll') }}</td>
                        <td>{{ passenger.flight.time }}</td>
                        <td>{{ passenger.flight.flight_no }}</td>
                        <td>{{ passenger.flight.iata_code }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="panel-body text-center" v-if="!passengers.length && !loading">
        <span class="lead">No Passengers</span>
        <p>Try adjusting the filters, or book some flights.</p>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
        <pager :pagination="pagination" :callback="changePage"></pager>
    </div>
</div>
</fetch-json>
</template>
<script>
export default {
    props: ['campaignId', 'segmentId'],
    watch: {
        'segmentId'(val) {
            this.$refs.passengersList.fetch();
        }
    }
}
</script>
