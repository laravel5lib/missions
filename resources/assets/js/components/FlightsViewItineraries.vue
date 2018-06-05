<template>
<fetch-json :url="`campaigns/${campaignId}/flights/itineraries`" ref="itinerariesList">
<div slot-scope="{ json:itineraries, pagination, changePage, loading }">
    <div class="panel-body" v-if="loading">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading">
        <table class="table" v-if="itineraries && itineraries.length">
            <thead>
                <tr class="active">
                    <th><input type="checkbox"></th>
                    <th>Record Locator</th>
                    <th>Type</th>
                    <th>Last Updated</th>
                    <th class="text-right">Flights</th>
                    <th class="text-right">Passengers</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="itinerary in itineraries" :key="itinerary.id">
                    <td><input type="checkbox"></td>
                    <td><strong><a href="#">{{ itinerary.record_locator }}</a></strong></td>
                    <td><em>{{ itinerary.type | capitalize }}</em></td>
                    <td>{{ itinerary.updated_at | moment('lll') }}</td>
                    <td class="text-right"><code>{{ itinerary.flight_count }}</code></td>
                    <td class="text-right"><code>{{ itinerary.passenger_count }}</code></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-body text-center" v-if="!itineraries.length && !loading">
        <span class="lead">No Itineraries</span>
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
    props: ['campaignId', 'segmentId']
}
</script>
