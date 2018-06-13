<template>
<div class="panel panel-default">
    <fetch-json :url="`campaigns/${campaignId}/flights/passengers?segment=${selectedSegment}&include=flight-itinerary,passport&filter[group]=${groupId}&filter[published_itinerary]=true'`" 
                ref="passengersList" 
                :parameters="{filter: {}, sort: 'surname'}"
                @filter:removed="removeActiveFilter"
    >
    <div slot-scope="{ json:passengers, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
        <div class="panel-heading">

                    <form class="form-inline" style="display: inline-block">
                        <label class="control-label">View By Flight Segment:</label>
                        <select class="form-control input-sm" v-model="selectedSegment">
                            <option v-for="segment in segments" 
                                    :key="segment.id" 
                                    :value="segment.id"
                            >{{ segment.name }}
                            </option>
                        </select>
                    </form>

                    <span v-for="(label, index) in activeFilters" 
                        :key="index" 
                        class="label label-filter">
                        <a type="button" @click="openFilterModal(filterConfiguration[label.key])">{{ label.text | capitalize }}: "{{ label.value | capitalize}}"</a> 
                        <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
                    </span>
                    <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                        <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Filter By</li>
                            <li>
                                <a type="button" 
                                    @click="openFilterModal(filterConfiguration.surname)"
                                >Surname</a>
                            </li>
                            <li>
                                <a type="button" 
                                    @click="openFilterModal(filterConfiguration.given_names)"
                                >Given Names</a>
                            </li>
                            <li>
                                <a type="button" 
                                    @click="openFilterModal(filterConfiguration.record_locator)"
                                >Record Locator</a>
                            </li>
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
                            <li>
                                <a type="button" 
                                    @click="openFilterModal(filterConfiguration.trip_type)"
                                >Trip</a>
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
                        Showing {{ passengers.length || 0 }} of {{ pagination.total || 0 }} results
                    </em>

        </div>
        <div class="panel-body" v-if="loading">
            <hr class="divider inv xlg">
            <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            <hr class="divider inv xlg">
            <hr class="divider inv xlg">
            <hr class="divider inv xlg">
        </div>
        <div class="table-responsive" v-if="!loading">
                <table class="table" v-if="passengers && passengers.length">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th @click="sortBy('surname')" style="cursor: pointer; min-width: 150px">
                                Surname <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'surname', 'fa-sort-down': filters.sort === '-surname' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th @click="sortBy('given_names')" style="cursor: pointer; min-width: 200px">
                                Given Names <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'given_names', 'fa-sort-down': filters.sort === '-given_names' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th>Trip</th>
                            <th>Record Locator</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Flight</th>
                            <th>City</th>
                            <th>Passport #</th>
                            <th>Passport Exp.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(passenger, index) in passengers" :key="passenger.id">
                            <td>{{ index+1 }}</td>
                            <td>
                                <strong>
                                    <a :href="`/dashboard/reservations/${passenger.id}/squads`">
                                        {{ passenger.surname }}
                                    </a>
                                </strong>
                            </td>
                            <td>{{ passenger.given_names }}</td>
                            <td>{{ passenger.trip }}</td>
                            <td>{{ passenger.itinerary.record_locator }}</td>
                            <td>{{ passenger.flight.date | mFormat('ll') }}</td>
                            <td>{{ passenger.flight.time }}</td>
                            <td>{{ passenger.flight.flight_no }}</td>
                            <td>{{ passenger.flight.iata_code }}</td>
                            <td>{{ passenger.passport ? passenger.passport.number : null }}</td>
                            <td><template v-if="passenger.passport">
                                {{ passenger.passport.expires_at | mFormat('ll') }}
                            </template></td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div class="panel-body text-center" v-if="!passengers.length && !loading">
            <hr class="divider inv xlg">
            <span class="lead">No Flights</span>
            <p class="text-muted">Try adjusting the filters. Flights may not have been published yet my Missions.Me staff.</p>
            <hr class="divider inv xlg">
            <hr class="divider inv xlg">
            <hr class="divider inv xlg">
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination" :callback="changePage"></pager>
        </div>
    </div>
    </fetch-json>
</div>
</template>
<script>
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    props: ['campaignId', 'groupId'],
    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },
    data() {
        return {
            activeFilters: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                surname: {
                    component: 'filter-search', 
                    title: 'Surname', 
                    field: 'surname',
                },
                given_names: {
                    component: 'filter-search',
                    title: 'Given Names', 
                    field: 'given_names',
                },
                record_locator: {
                    component: 'filter-search',
                    title: 'Record Locator', 
                    field: 'record_locator',
                },
                flight_no: {
                    component: 'filter-search',
                    title: 'Flight No.', 
                    field: 'flight_no',
                },
                city: {
                    component: 'filter-search',
                    title: 'City', 
                    field: 'iata_code',
                },
                trip_type: {
                    component: 'filter-radio',
                    title: 'Trip', 
                    field: 'trip_type',
                    options: [
                        {value: 'family', label: 'Family'},
                        {value: 'international', label: 'International'},
                        {value: 'media', label: 'Media'},
                        {value: 'medical', label: 'Medical'},
                        {value: 'ministry', label: 'Ministry'},
                        {value: 'leader', label: 'Leader'},
                        {value: 'sports', label: 'Sports'},
                        {value: 'water', label: 'Water'},
                    ]
                }
            },
            segments: [],
            selectedSegment: null,
            ui: {
                showMenu: true
            }
        }
    },
    watch: {
        'selectedSegment'(val) {
           this.$refs.passengersList.fetch();
        }
    },
    methods: {
        openFilterModal(filter) {
            this.filterModal = filter;
            $('#filterModal').modal('show');
        },
        closeFilterModal(data) {
            this.removeActiveFilter(data.key);
            this.activeFilters.push(data);
            this.filterModal = {
                component: null,
                title: null
            }
            $('#filterModal').modal('hide');
        },
        removeActiveFilter(key) {
            this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
        },
        getSegments() {
            this.$http.get(`/campaigns/${this.campaignId}/flights/segments`).then(response => {
                this.segments = response.data.data;
                this.selectedSegment = _.first(this.segments).id;
            });
        }
    },
    mounted() {
        this.getSegments();
    }
}
</script>
<style>
    tr:hover {
        background-color: #fcf8e3;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>
