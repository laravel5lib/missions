<template>
<fetch-json :url="`flights?filter[segment]=${segmentId}&include=flight-itinerary`" 
            ref="flightsList" 
            :parameters="{filter: {}, sort: 'date'}"
>
<div slot-scope="{ json:flights, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
    <div class="panel-heading">
        <span v-for="(value, key, index) in filters.filter" 
              :key="index" 
              class="label label-filter">
              {{ key | capitalize }}: "{{ value | capitalize}}" <a type="button" @click="removeFilter(key)"><i class="fa fa-times"></i></a>
        </span>
        <div class="dropdown" style="display: inline-block; margin-left: 1em;">
            <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Filter By</li>
                <li><a type="button" @click="openFilterModal('flight_no', 'Flight No.')">Flight No.</a></li>
                <li><a type="button" @click="openFilterModal('iata_code', 'City')">City</a></li>
            </ul>

            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                    </div>
                    <div class="modal-body">
                       <input class="form-control" name="search" v-model="searchFor" :placeholder="`Enter a ${filterModal.title} ...`">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addFilter(searchBy, searchFor), searchBy = null, searchFor = null" data-dismiss="modal">Apply</button>
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="panel-body" v-if="loading">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="flights && flights.length">
                <thead>
                    <tr class="active">
                        <th @click="sortBy('flight_no')" style="cursor: pointer">
                            Flight <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'flight_no', 'fa-sort-down': filters.sort === '-flight_no' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('iata_code')" style="cursor: pointer">
                            City <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'iata_code', 'fa-sort-down': filters.sort === '-iata_code' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('date')" style="cursor: pointer">
                            Date <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'date', 'fa-sort-down': filters.sort === '-date' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('time')" style="cursor: pointer">
                            Time <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'time', 'fa-sort-down': filters.sort === '-time' }, 'fa-sort']"
                                ></i>
                        </th>

                        <th>Record Locator</th>
                        <th class="text-right">Passengers</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="flight in flights" :key="flight.id">
                        <td>
                            <strong>
                                <a :href="`/admin/flights/${flight.id}`">
                                    {{ flight.flight_no }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ flight.iata_code }}</td>
                        <td>{{ flight.date | mFormat('ll') }}</td>
                        <td>{{ flight.time }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/campaigns/${campaignId}/itineraries/${flight.itinerary.id}`">
                                    {{ flight.itinerary.record_locator }}
                                </a>
                            </strong>
                        </td>
                        <td class="text-right">
                            <code>{{ flight.itinerary.passenger_count }}</code>
                        </td>
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
    data() {
        return {
            searchBy: null,
            searchFor: null,
            filterModal: {
                title: null
            }
        }
    },
    watch: {
        'segmentId'(val) {
            this.$refs.flightsList.fetch();
        }
    },
    methods: {
        openFilterModal(filter, title) {
            this.searchBy = filter;
            this.filterModal.title = title;
            $('#filterModal').modal('show');
        }
    }
}
</script>
