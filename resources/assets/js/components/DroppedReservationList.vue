<template>
    <fetch-json :url="`reservations?filter[dropped]=true&filter[campaign]=${campaignId}&include=trip.group`" 
                ref="list" 
                :parameters="{filter: {}}" 
                @filter:removed="removeActiveFilter"
                :cache-key="`admin.droppped.reservations.${campaignId}`"
    >
        <div class="panel panel-default" 
             slot-scope="{ json:reservations, pagination, changePage, loading, addFilter, removeFilter, filters, sort }" style="border-top: 5px solid #f6323e"
        >
            <div class="panel-heading">
                <form class="form-inline" style="display: inline">
                    <label>Per Page:</label>
                    <select class="form-control input-sm" v-model="pagination.per_page">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
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
                                @click="openFilterModal(filterConfiguration.group)"
                                >Group</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.trip_type)"
                                >Trip</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.dropped_between)"
                                >Dropped</a>
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
                    Showing {{ reservations.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Surname</th>
                        <th>Given Names</th>
                        <th>Group</th>
                        <th>Trip</th>
                        <th>Dropped</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                        <td>{{ index+1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/reservations/${reservation.id}`">{{ reservation.surname }}</a>
                            </strong>
                        </td>
                        <td>{{ reservation.given_names }}</td>
                        <td>{{ reservation.trip.group.name }}</td>
                        <td>{{ reservation.trip.type }}</td>
                        <td>{{ reservation.deleted_at | moment('ll') }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="panel-body text-center" v-if="!reservations.length && !loading">
                <span class="lead">No Reservations</span>
                <p>Try adjusting the filters, otherwide all flights have been booked!</p>
            </div>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>
</template>

<script>
import state from '../state.mixin';
import dates from '../dates.mixin';
import activeFilter from '../activeFilter.mixin';
import tripTypes from '../data/trip_types.json';
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    props: {
        campaignId: String
    },

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },

    mixins: [state, dates, activeFilter],

    data() {
        return {
            ui: {
                allFilters: false
            },
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
                trip_type: {
                    component: 'filter-radio',
                    title: 'Trip', 
                    field: 'trip_type',
                    options: tripTypes
                },
                group: {
                    component: 'filter-select',
                    title: 'Group',
                    field: 'group',
                    ajax: {
                        url: `/campaigns/${this.campaignId}/groups?per_page=100`,
                        value: 'id',
                        label: 'name'
                    }
                },
                dropped_between: {
                    component: 'filter-radio',
                    title: 'Dropped', 
                    field: 'dropped_between',
                    options: []
                }
            }
        }
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters']);
        }
    },

    computed: {
        droppedBetween() {
            return [
                    {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                    {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                    {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                    {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                    {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
                ]
        }
    },

    methods: {
        openFilterModal(filter) {
            this.filterModal = filter;
            $('#filterModal').modal('show');
        },
        closeFilterModal(data) {
            this.addActiveFilter(data);
            this.filterModal = {
                component: null,
                title: null
            }
            $('#filterModal').modal('hide');
        }
    },

    mounted() {
        this.filterConfiguration.dropped_between.options = this.droppedBetween;

        var previousState = this.restoreState();
        if (previousState) {
            this.activeFilters = previousState.activeFilters;
        }
    }
}
</script>

<style>
    tr.selected, tr:hover {
        background-color: #fcf8e3;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>
