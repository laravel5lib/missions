<template>
    <fetch-json :url="url" 
                ref="list" 
                :parameters="{filter: {}}" 
                @filter:removed="removeActiveFilter"
    >
        <div class="panel panel-default" 
             slot-scope="{ json:reservations, pagination, changePage, loading, addFilter, removeFilter, filters, sort }" style="border-top: 5px solid #f6323e"
        >
            <div class="panel-heading">
                <ul class="nav nav-pills nav-justified">
                    <li :class="{ 'active' : ! filters.filter.funnel }">
                        <a role="button" @click="removeFilter('funnel')">
                            All <span class="badge badge-default">{{ totals.all }}</span> 
                        </a>
                    </li>
                    <li :class="{ 'active' : filters.filter.funnel === 'deposited' }">
                        <a role="button" @click="addFilter('funnel', 'deposited')">
                            Deposit Only <span class="badge badge-default">{{ totals.deposited }}</span>
                            <i class="fa fa-arrow-right text-muted"></i>
                        </a>
                    </li>
                    <li :class="{ 'active' : filters.filter.funnel === 'in_process' }">
                        <a role="button" @click="addFilter('funnel', 'in_process')">
                            In Process <span class="badge badge-default">{{ totals.process }}</span>
                            <i class="fa fa-arrow-right text-muted"></i>
                        </a>
                    </li>
                    <li :class="{ 'active' : filters.filter.funnel === 'funded' }">
                        <a role="button" @click="addFilter('funnel', 'funded')">
                            Fully Funded <span class="badge badge-default">{{ totals.funded }}</span>
                            <i class="fa fa-arrow-right text-muted"></i>
                        </a>
                    </li>
                    <li :class="{ 'active' : filters.filter.funnel === 'ready' }">
                        <a role="button" @click="addFilter('funnel', 'ready')">
                            Travel Ready <span class="badge badge-default">{{ totals.ready }}</span>
                        </a>
                    </li>
                </ul>
            </div>
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
                    <label>View:</label>
                    <select class="form-control input-sm" v-model="view">
                        <option value="missionary">Missionary</option>
                        <option value="fundraising">Fundraising</option>
                        <option value="mailing">Mailing</option>
                    </select>
                </form>
                <span v-for="(label, index) in activeFilters" 
                    :key="index" 
                    class="label label-filter">
                    <a type="button" @click="openFilterModal(filterConfiguration[label.key])">{{ label.text | capitalize }}: "{{ label.value | capitalize}}"</a>
                    <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
                </span>
                <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                    <a role="button" @click="ui.allFilters = true">+ Add a filter</a>

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

                <export-list type="reservations" 
                            :params="`filter[campaign]=${campaignId}&filter[group]=${groupId}&include=trip.group`"
                            :filters="filters"
                ></export-list>

            </div>
            <div class="panel-body" v-if="ui.allFilters">
                <div class="row text-right">
                    <div class="col-xs-12">
                        <strong><a type="button" @click="ui.allFilters = false">x</a></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Filter By</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.surname)"
                                >+ Surname</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.given_names)"
                                >+ Given Names</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.trip_type)"
                                >+ Trip</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.trip_tags)"
                                >+ Trip Tags</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.desired_role)"
                                >+ Role</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.gender)"
                                >+ Gender</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.age_range)"
                                >+ Age Range</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.status)"
                                >+ Marital Status</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.shirt_size)"
                                >+ Shirt Size</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <label>Filter By Contact</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.email)"
                                >+ Email</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.phone_one)"
                                >+ Primary Phone</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.phone_two)"
                                >+ Secondary Phone</a>
                            </li>
                        </ul>
                        <label>Filter By Location</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.country_code)"
                                >+ Country</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.state)"
                                >+ State</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.zip)"
                                >+ Zip Code</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.city)"
                                >+ City</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.address)"
                                >+ Address</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <label>Filter By Fundraising</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.current_rate)"
                                >+ Current Rate</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.percent_raised)"
                                >+ Percentage Raised</a>
                            </li>
                        </ul>
                        <label>Filter By Date</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.registered_between)"
                                >+ Registered</a>
                            </li>
                        </ul>
                    </div>
                </div>

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
                        <th v-if="!filters.filter.trip_type">Trip</th>
                        <th>Tags</th>
                        <template v-if="view === 'missionary'">
                            <th v-if="!filters.filter.desired_role">Role</th>
                            <th>Age</th>
                            <th v-if="!filters.filter.gender">Gender</th>
                            <th v-if="!filters.filter.status">Status</th>
                            <th>Email</th>
                            <th>Primary Phone</th>
                            <th>Secondary Phone</th>
                            <th>Registered</th>
                        </template>
                        <template v-if="view === 'fundraising'">
                            <th>% Raised</th>
                            <th v-if="!filters.filter.cost">Current Rate</th>
                        </template>
                        <template v-if="view === 'mailing'">
                            <th v-if="!filters.filter.shirt_size">Shirt Size</th>
                            <th>Country</th>
                            <th>Zip Code</th>
                            <th>State/Providence</th>
                            <th>City</th>
                            <th>Address</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                        <td>{{ index+1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/dashboard/reservations/${reservation.id}`">{{ reservation.surname }}</a>
                            </strong>
                        </td>
                        <td>{{ reservation.given_names }}</td>
                        <td v-if="!filters.filter.trip_type">{{ reservation.trip.type }}</td>
                        <td>
                            <span class="label label-default" 
                                  style="margin-right: 1em;" 
                                  v-for="tag in reservation.trip.tags"
                            >
                                {{ tag.name }}
                            </span>
                        </td>
                        <template v-if="view === 'missionary'">
                            <td v-if="!filters.filter.desired_role">{{ reservation.desired_role.name }}</td>
                            <td>{{ reservation.age }}</td>
                            <td v-if="!filters.filter.gender">{{ reservation.gender }}</td>
                            <td v-if="!filters.filter.status">{{ reservation.status }}</td>
                            <td>{{ reservation.email }}</td>
                            <td>{{ reservation.phone_one }}</td>
                            <td>{{ reservation.phone_two }}</td>
                            <td>{{ reservation.created_at | moment('ll') }}</td>
                        </template>
                        <template v-if="view === 'fundraising'">
                            <td>{{ reservation.percent_raised }}%</td>
                            <td v-if="!filters.filter.cost">{{ reservation.current_rate }}</td>
                        </template>
                        <template v-if="view === 'mailing'">
                            <td v-if="!filters.filter.shirt_size">{{ reservation.shirt_size }}</td>
                            <td>{{ reservation.country_name }}</td>
                            <td>{{ reservation.zip }}</td>
                            <td>{{ reservation.state }}</td>
                            <td>{{ reservation.city }}</td>
                            <td>{{ reservation.address }}</td>
                        </template>
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
import genders from '../data/genders.json';
import countries from '../data/countries.json';
import teamRoles from '../data/team_roles.json';
import tripTypes from '../data/trip_types.json';
import ageRanges from '../data/age_ranges.json';
import shirtSizes from '../data/shirt_sizes.json';
import maritalStatuses from '../data/marital_statuses.json';
import percentageRanges from '../data/percentage_ranges.json';
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
import FilterCheckbox from '../components/FilterCheckbox';
export default {
    props: {
        totals: Object, 
        campaignId: String,
        groupId: String,
    },

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect,
        'filter-checkbox': FilterCheckbox
    },

    mixins: [state, dates, activeFilter],

    data() {
        return {
            ui: {
                allFilters: false
            },
            view: 'missionary',
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
                    options: tripTypes
                },
                trip_tags: {
                    component: 'filter-checkbox',
                    title: 'Trip Tags',
                    field: 'trip_tags',
                    ajax: {
                        url: `tags?filter[type]=trip&per_page=999`,
                        value: 'name',
                        label: 'name'
                    }
                },
                gender: {
                    component: 'filter-radio',
                    title: 'Gender', 
                    field: 'gender',
                    options: genders
                },
                status: {
                    component: 'filter-radio',
                    title: 'Marital Status', 
                    field: 'status',
                    options: maritalStatuses
                },
                age_range: {
                    component: 'filter-radio',
                    title: 'Age Range', 
                    field: 'age_range',
                    options: ageRanges
                },
                shirt_size: {
                    component: 'filter-radio',
                    title: 'Shirt Size', 
                    field: 'shirt_size',
                    options: shirtSizes
                },
                current_rate: {
                    component: 'filter-select',
                    title: 'Current Rate',
                    field: 'cost',
                    ajax: {
                        url: `campaigns/${this.campaignId}/costs?filter[type]=incremental`,
                        value: 'id',
                        label: 'name'
                    }
                },
                desired_role: {
                    component: 'filter-select',
                    title: 'Role',
                    field: 'desired_role',
                    options: teamRoles
                },
                current_rate: {
                    component: 'filter-select',
                    title: 'Current Rate',
                    field: 'cost',
                    ajax: {
                        url: `campaigns/${this.campaignId}/costs?filter[type]=incremental`,
                        value: 'id',
                        label: 'name'
                    }
                },
                percent_raised: {
                    component: 'filter-radio',
                    title: 'Percentage Raised', 
                    field: 'percent_raised_range',
                    options: percentageRanges
                },
                email: {
                    component: 'filter-search', 
                    title: 'Email', 
                    field: 'email',
                },
                phone_one: {
                    component: 'filter-search', 
                    title: 'Primary Phone', 
                    field: 'phone_one',
                },
                phone_two: {
                    component: 'filter-search', 
                    title: 'Secondary Phone', 
                    field: 'phone_two',
                },
                city: {
                    component: 'filter-search', 
                    title: 'City', 
                    field: 'city',
                },
                state: {
                    component: 'filter-search', 
                    title: 'State/Providence', 
                    field: 'state',
                },
                address: {
                    component: 'filter-search', 
                    title: 'Address', 
                    field: 'address',
                },
                zip: {
                    component: 'filter-search', 
                    title: 'Zip Code', 
                    field: 'zip',
                },
                country_code: {
                    component: 'filter-select',
                    title: 'Country',
                    field: 'country_code',
                    options: countries
                },
                registered_between: {
                    component: 'filter-radio',
                    title: 'Registered', 
                    field: 'registered_between',
                    options: []
                }
            }
        }
    },

    computed: {
        url() {
            return `/reservations?filter[campaign]=${this.campaignId}&filter[group]=${this.groupId}&include=trip.group,trip.tags`
        },
        registeredBetween() {
            return [
                    {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                    {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                    {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                    {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                    {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
                ];
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
        },
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters', 'view']);
        },
        view() {
            this.saveState(['activeFilters', 'view']);
        }
    },
    
    mounted() {
        this.filterConfiguration.registered_between.options = this.registeredBetween;
        
        var previousState = this.restoreState();
        if (previousState) {
            this.activeFilters = previousState.activeFilters;
            this.view = previousState.view;
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
