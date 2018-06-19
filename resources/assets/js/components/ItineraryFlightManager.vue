<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading"><h5>New Flight</h5></div>
            <div class="panel-body">
                <ajax-form method="post" 
                           action="flights" 
                           ref="ajax" 
                           :horizontal="true" 
                           :initial="{
                               flight_segment_id: null,
                               flight_itinerary_id: itineraryId,
                               flight_no: null,
                               iata_code: null,
                               date: null,
                               time: null
                           }"
                           @form:success="updateList"
                >
                    <template slot-scope="{ form }">

                        <input-select name="flight_segment_id" v-model="form.flight_segment_id" :horizontal="true" classes="col-md-4 col-sm-6" :options="segments">
                            <label slot="label" class="control-label col-sm-4">Flight Segment</label>
                        </input-select>

                        <input-text name="flight_no" v-model="form.flight_no" :horizontal="true" classes="col-md-4 col-sm-6">
                            <label slot="label" class="control-label col-sm-4">Flight Number</label>
                        </input-text>

                        <input-text name="iata_code" v-model="form.iata_code" :horizontal="true" classes="col-md-4 col-sm-6">
                            <label slot="label" class="control-label col-sm-4">City</label>
                        </input-text>

                        <input-date name="date" v-model="form.date" :horizontal="true" classes="col-md-4 col-sm-6">
                            <label slot="label" class="control-label col-sm-4">Date</label>
                        </input-date>

                        <input-time name="time" v-model="form.time" :horizontal="true" classes="col-md-4 col-sm-6">
                            <label slot="label" class="control-label col-sm-4">24-Hour Time</label>
                        </input-time>
                        
                        <hr class="divider">
                        <button type="submit" class="btn btn-primary pull-right">Add Flight</button>
                    </template>
                </ajax-form>
            </div>
            </div>

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
                                    <td>{{ flight.date | mFormat('ll') }}</td>
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
    </div>
</template>

<script>
import FilterSearch from './FilterSearch';
export default {
    props: {
        itineraryId: {
            type: String,
            required: true
        },
        campaignId: {
            type: String,
            required: true
        }
    },

    components: {
        'filter-search': FilterSearch,
    },


    data() {
        return {
            segments: {},
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
        updateList(params) {
            this.$refs.flightList.fetch(params);
        },
        getSegments() {
            this.$http.get(`/campaigns/${this.campaignId}/flights/segments`).then(response => {
                let segments = {};
                _.each(response.data.data, function(segment) {
                    segments[segment.id] = segment.name;
                });

                this.segments = segments;
            });
        },
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
        }
    },

    mounted() {
        this.getSegments();
    }
}
</script>