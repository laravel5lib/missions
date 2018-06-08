<template>
<fetch-json :url="`campaigns/${campaignId}/flights/passengers?segment=${segmentId}&include=flight-itinerary`" 
            ref="passengersList" 
            :parameters="{filter: {}, sort: 'surname'}"
            @filter:removed="removeActiveFilter"
>
<div slot-scope="{ json:passengers, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
    <div class="panel-heading">
        <button class="btn btn-primary btn-sm" 
                style="margin-right: 1em;"
                :disabled="! selected.length"
                @click="removePassengers"
        >
            Remove Passengers 
            <span class="badge" 
                  style="margin-left: 1em;"
            >{{ selected.length }}
            </span>
        </button>

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
                <li>
                    <a type="button" 
                        @click="openFilterModal(filterConfiguration.group)"
                    >Group</a>
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
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="passengers && passengers.length">
                <thead>
                    <tr class="active">
                        <th>
                            <input type="checkbox" 
                                @change="selectAll(passengers, $event.target.checked)"
                                :checked="selected.length === passengers.length"
                            >
                        </th>
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
                    <tr v-for="passenger in passengers" :key="passenger.id" :class="{ 'selected' : isSelected(passenger) }">
                        <td><input type="checkbox" :checked="isSelected(passenger)" @change="select(passenger, $event.target.checked)"></td>
                        <td>
                            <strong>
                                <a :href="`/admin/reservations/${passenger.id}`">
                                    {{ passenger.surname }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ passenger.given_names }}</td>
                        <td>{{ passenger.group }}</td>
                        <td>{{ passenger.trip }}</td>
                        <td><em>{{ passenger.itinerary.type }}</em></td>
                        <td>
                            <strong>
                                <a :href="`/admin/campaigns/${campaignId}/itineraries/${passenger.itinerary.id}`">
                                    {{ passenger.itinerary.record_locator }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ passenger.flight.date | mFormat('ll') }}</td>
                        <td>{{ passenger.flight.time }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/flights/${passenger.flight.id}`">
                                    {{ passenger.flight.flight_no }}
                                </a>
                            </strong>
                        </td>
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
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    props: ['campaignId', 'segmentId'],
    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },
    watch: {
        'segmentId'(val) {
            this.$refs.passengersList.fetch();
        }
    },
    data() {
        return {
            activeFilters: [],
            selected: [],
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
                },
                group: {
                    component: 'filter-select',
                    title: 'Group',
                    field: 'group',
                    ajax: {
                        url: `/campaigns/${this.campaignId}/groups`,
                        value: 'id',
                        label: 'name'
                    }
                }
            }
        }
    },
    methods: {
        select(record, value) {
            if (value) {
                this.selected.push(record);
            } else {
                this.selected = _.without(this.selected, record);
            }
        },
        selectAll(records, value) {
            if (value) {
                this.selected = records;
            } else {
                this.selected = [];
            }
        },
        isSelected(record) {
            return _.findWhere(this.selected, record)
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
            this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
        },
        removePassengers() {
            swal('WARNING!', `Are you sure you want to remove the ${this.selected.length} selected passenger(s)? This will remove them from all itineraries. To undo this action, flights must be booked again.`, 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Keep",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Remove",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http
                        .patch(`campaigns/${this.campaignId}/flights/passengers`, {reservations: this.selected})
                        .then(response => {
                            swal("Nice Work!", "Passengers removed.", "success", {
                                buttons: false,
                                timer: 1000
                            });
                            this.$emit('update:bookedTotal', -this.selected.length);
                            this.selected = [];
                            this.$refs.passengersList.fetch();
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            })
        }
    }
}
</script>
<style>
    tr.selected {
        background-color: #fcf8e3;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>