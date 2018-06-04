<template>
<fetch-json :url="`flights?filter[segment]=${segmentId}`" ref="fetchJson">
<div slot-scope="{ json:flights, pagination, changePage, loading }">
    <div class="panel-body" v-if="loading">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="flights && flights.length">
                <thead>
                    <tr class="active">
                        <th><input type="checkbox"></th>
                        <th>Flight</th>
                        <th>City</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="flight in flights" :key="flight.id">
                        <td><input type="checkbox"></td>
                        <td><strong><a href="#">{{ flight.flight_no }}</a></strong></td>
                        <td>{{ flight.iata_code }}</td>
                        <td>{{ flight.date | mFormat('ll') }}</td>
                        <td>{{ flight.time }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="panel-body text-center" v-if="!flights.length && !loading">
        <span class="lead">No Flights</span>
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
            this.$refs.fetchJson.fetch();
        }
    }
}
</script>
