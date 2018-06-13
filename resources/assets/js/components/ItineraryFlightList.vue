<template>
    <fetch-json :url="`flights?filter[itinerary]=${itineraryId}&include=flight-segment`" 
                ref="flightList"
                :parameters="{filter: {}, sort: 'flight_no'}"
                @filter:removed="removeActiveFilter"
    >
        <div class="panel panel-default"
             style="border-top: 5px solid #f6323e"
             slot-scope="{ json:flights, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
            <div class="panel-heading">
                <h5>Flights <span class="badge">{{ pagination.total }}</span></h5>
            </div>
            <div class="panel-heading">
                <span v-for="(label, index) in activeFilters" 
                    :key="index" 
                    class="label label-filter">
                    {{ label.text | capitalize }}: "{{ label.value | capitalize}}" <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
                </span>
                <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                    <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Filter By</li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.flight_no)"
                            >Flight No.</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.city)"
                            >City</a>
                        </li>
                    </ul>

                    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                                </div>
                                <div class="modal-body">
                                <component :is="filterModal.component" 
                                            :callback="addFilter" 
                                            :config="filterModal"
                                            @apply:filter="closeFilterModal"
                                    ></component>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <em class="text-muted small">
                    Showing {{ flights.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
                <table class="table">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th>Flight No.</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>City</th>
                            <th>Segment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(flight, index) in flights" :key="flight.id">
                            <td>{{ index+1 }}</td>
                            <td>
                                <strong>
                                    <a :href="`/admin/flights/${flight.id}`">
                                        {{ flight.flight_no }}
                                    </a>
                                </strong>
                            </td>
                            <td>{{ flight.date | moment('ll') }}</td>
                            <td>{{ flight.time }}</td>
                            <td>{{ flight.iata_code }}</td>
                            <td>{{ flight.segment.name }}</td>
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
import FilterSearch from '../components/FilterSearch';
export default {
    props: ['itineraryId'],
    components: {
        'filter-search': FilterSearch,
    },
    data() {
        return {
            activeFilters: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                flight_no: {
                    component: 'filter-search',
                    title: 'Flight No.', 
                    field: 'flight_no',
                },
                city: {
                    component: 'filter-search',
                    title: 'City', 
                    field: 'iata_code',
                }
            }
        }
    },
    methods: {
        openFilterModal(filter) {
            this.filterModal = filter;
            $('#filterModal').modal('show');
        },
        closeFilterModal(data) {
            this.activeFilters.push(data);
            this.filterModal = {
                component: null,
                title: null
            }
            $('#filterModal').modal('hide');
        },
        removeActiveFilter(key) {
            if (_.findWhere(this.activeFilters, {key: key})) {
                this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
            }
        },
    }
}
</script>
