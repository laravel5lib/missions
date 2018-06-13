<template>
    <fetch-json :url="`reservations?filter[dropped]=true&filter[campaign]=${campaignId}&include=trip.group`" 
                ref="list" 
                :parameters="{filter: {}}" 
                @filter:removed="removeActiveFilter"
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
                                @click="openFilterModal(droppedBetween)"
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
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    props: ['campaignId'],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },

    data() {
        return {
            ui: {
                allFilters: false
            },
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
                    options: []
                },
                given_names: {
                    component: 'filter-search',
                    title: 'Given Names', 
                    field: 'given_names',
                    options: []
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
                        url: `/campaigns/${this.campaignId}/groups?per_page=100`,
                        value: 'id',
                        label: 'name'
                    }
                }
            }
        }
    },

    computed: {
        startOfToday() {
            return moment().startOf('day').format();
        },
        endOfToday() {
            var time = moment().startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfYesterday() {
            return moment().subtract(1, 'day').startOf('day').format();
        },
        endOfYesterday() {
            var time = moment().subtract(1, 'day').startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfWeek() {
            return moment().startOf('week').format();
        },
        endOfWeek() {
            var time = moment().startOf('week');
            return time.clone().endOf('week').format();
        },
        startOfMonth() {
            return moment().startOf('month').format();
        },
        endOfMonth() {
            var time = moment().startOf('month');
            return time.clone().endOf('month').format();
        },
        startOfLastMonth() {
            return moment().subtract(1, 'month').startOf('month').format();
        },
        endOfLastMonth() {
            var time = moment().subtract(1, 'month').startOf('month');
            return time.clone().endOf('month').format();
        },
        droppedBetween() {
            return {
                component: 'filter-radio',
                title: 'Dropped', 
                field: 'dropped_between',
                options: [
                    {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                    {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                    {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                    {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                    {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
                ]
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
            this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
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
