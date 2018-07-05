<template>
<div>
    <div v-if="ui.showAssignmentForm">
        <squad-assignment-form 
            :reservations="selected" 
            :campaign-id="campaignId" 
            @cancelled:form="respondToCancelled"
            @successful:form="respondToSuccess"
        ></squad-assignment-form>
    </div>
    <div v-else>
        <fetch-json 
            :url="`reservations?filter[campaign]=${campaignId}&filter[squads_count]=0&include=trip.group`" 
            :parameters="{filter: {}, sort: 'surname'}"
            ref="list"
            @filter:removed="removeActiveFilter"
            :cache-key="`admin.campaign.${campaignId}.needs-squad-list.fetchJson`"
        >
            <div slot-scope="{ json:reservations, pagination, changePage, changePerPage, loading, addFilter, removeFilter, filters, sortBy }">
                <div class="panel-heading">

                    <button class="btn btn-sm btn-primary" 
                            :disabled="! selected.length"
                            @click="ui.showAssignmentForm = true"
                    >
                        Add to Squad 
                        <span class="badge" 
                                style="margin-left: 1em;"
                        >{{ selected.length }}
                        </span>
                    </button>
                    
                    <form class="form-inline" style="display: inline">
                        <label>Per Page:</label>
                        <select class="form-control input-sm" v-model="pagination.per_page" @change="changePerPage($event.target.value)">
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
                                @click="openFilterModal(filterConfiguration.desired_role)"
                                >Role</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.group)"
                                >Organization</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.trip_type)"
                                >Trip Type</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.gender)"
                                >Gender</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.age_range)"
                                >Age Range</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.status)"
                                >Marital Status</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.companions_count)"
                                >Companions</a>
                            </li>
                        </ul>
                    </div>

                    <span class="text-muted small" v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</span>
                    <em class="text-muted small" v-else>
                        Showing {{ reservations.length || 0 }} of {{ pagination.total || 0 }} results
                    </em>

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
                <div class="table-responsive">
                <table class="table table-condensed table-striped" v-if="reservations && reservations.length">
                    <thead>
                        <tr class="active">
                            <th>
                                <input type="checkbox" 
                                    @change="selectAll(reservations, $event.target.checked)"
                                    :checked="selected.length === reservations.length"
                                >
                            </th>
                            <th>#</th>
                            <th @click="sortBy('surname')" style="cursor: pointer; min-width: 150px">
                                Surname <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'surname', 'fa-sort-down': filters.sort === '-surname' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th @click="sortBy('given_names')" style="cursor: pointer; min-width: 150px">
                                Given Names <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'given_names', 'fa-sort-down': filters.sort === '-given_names' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th>Role</th>
                            <th>Organization</th>
                            <th>Trip Type</th>
                            <th @click="sortBy('gender')" style="cursor: pointer; min-width: 150px">
                                Gender <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'gender', 'fa-sort-down': filters.sort === '-gender' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th>Age</th>
                            <th @click="sortBy('status')" style="cursor: pointer; min-width: 150px">
                                Status <i class="pull-right fa" 
                                        :class="[{ 'fa-sort-up': filters.sort === 'status', 'fa-sort-down': filters.sort === '-status' }, 'fa-sort']"
                                    ></i>
                            </th>
                            <th>Companions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(reservation, index) in reservations" 
                            :key="reservation.id" 
                            :class="{ 'selected' : isSelected(reservation) }"
                        >
                            <td>
                                <input 
                                    type="checkbox" 
                                    :checked="isSelected(reservation)" 
                                    @change="select(reservation, $event.target.checked)"
                                >
                            </td>
                            <td class="text-muted">{{ index + 1 }}</td>
                            <td>
                                <strong>
                                    <a :href="`/admin/reservations/${reservation.id}`">
                                        {{ reservation.surname }}
                                    </a>
                                </strong>
                            </td>
                            <td>{{ reservation.given_names }}</td>
                            <td>{{ reservation.desired_role.name }}</td>
                            <td>{{ reservation.trip.group.name }}</td>
                            <td>{{ reservation.trip.type }}</td>
                            <td>{{ reservation.gender }}</td>
                            <td>{{ reservation.age }}</td>
                            <td>{{ reservation.status }}</td>
                            <td><strong>{{ reservation.companions_count }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="panel-body" 
                    style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                    v-if="!reservations.length"
                >
                    <div class="text-center">
                        <span class="lead">No Missionaries Left to Assign!</span>
                        <p>Or try adjusting the filters.</p>
                    </div>
                </div>
                <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                    <pager :pagination="pagination" :callback="changePage"></pager>
                </div>
            </div>
        </fetch-json>
    </div>
</div>
</template>

<script>
import state from '../state.mixin';
import activeFilter from '../activeFilter.mixin';
import genders from '../data/genders.json';
import teamRoles from '../data/team_roles.json';
import tripTypes from '../data/trip_types.json';
import ageRanges from '../data/age_ranges.json';
import martialStatuses from '../data/marital_statuses.json';
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
import SquadAssignmentForm from './SquadAssignmentForm';
export default {
    props: {
        campaignId: {
            type: String,
            required: true
        },
        cacheKey: {
            type: String,
            default: `${window.location.host}${window.location.pathname}.admin.needSquadList`
        }
    },

    mixins: [state, activeFilter],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect,
        'squad-assignment-form': SquadAssignmentForm
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters']);
        }
    },

    data() {
        return {
            ui: {
                showAssignmentForm: false
            },
            selected: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                group: {
                    component: 'filter-select',
                    title: 'Organization',
                    field: 'group',
                    ajax: {
                        url: `/campaigns/${this.campaignId}/groups?per_page=200`,
                        value: 'id',
                        label: 'name'
                    }
                },
                surname: {
                    component: 'filter-search', 
                    title: 'Surname', 
                    field: 'surname'
                },
                given_names: {
                    component: 'filter-search', 
                    title: 'Given Names', 
                    field: 'given_names'
                },
                trip_type: {
                    component: 'filter-radio',
                    title: 'Trip', 
                    field: 'trip_type',
                    options: tripTypes
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
                    options: martialStatuses
                },
                age_range: {
                    component: 'filter-radio',
                    title: 'Age Range', 
                    field: 'age_range',
                    options: ageRanges
                },
                desired_role: {
                    component: 'filter-select',
                    title: 'Role',
                    field: 'desired_role',
                    options: teamRoles
                },
                companions_count: {
                    component: 'filter-radio',
                    title: 'Companions',
                    field: 'companions_count',
                    options: [
                        {label: 'None', value: 0},
                        {label: 'At least 1', value: 1},
                        {label: '3 or more', value: 3}
                    ]
                }
            }
        }
    },

    methods: {
        respondToCancelled() {
            this.ui.showAssignmentForm = false;
            this.selected = [];
        },
        respondToSuccess() {
            this.$emit('update:membersTotal', this.selected.length);
            this.$emit('update:unassignedTotal', -this.selected.length);
            this.ui.showAssignmentForm = false;
            this.selected = [];
        },
        select(item, value) {
            if (value) {
                this.selected.push(item);
            } else {
                this.selected = _.without(this.selected, item);
            }
        },
        selectAll(items, value) {
            if (value) {
                this.selected = items;
            } else {
                this.selected = [];
            }
        },
        isSelected(item) {
            return _.findWhere(this.selected, item)
        },
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
