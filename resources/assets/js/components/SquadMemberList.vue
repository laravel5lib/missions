<template>
    <fetch-json 
        :url="`squad-members?filter[campaign_id]=${campaignId}`" 
        :parameters="{filter: {}, sort: 'surname'}"
        ref="list"
        @filter:removed="removeActiveFilter"
        :cache-key="`admin.campaign.${campaignId}.squad-members.fetchJson`"
    >
        <div slot-scope="{ json:members, pagination, changePage, changePerPage, loading, addFilter, removeFilter, filters, sortBy }">
            <div class="panel-heading">

                <div class="btn-group btn-group-sm">
                    <button type="button" 
                            class="btn btn-primary dropdown-toggle" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false" 
                            :disabled="! selected.length"
                    >
                        Manage Members 
                        <span class="badge" 
                              style="margin-left: 1em; margin-right: 1em;"
                        >{{ selected.length }}
                        </span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a @click.prevent="editMembers">Edit Members</a></li>
                        <li><a @click.prevent="removeMembers">Remove from Squad</a></li>
                    </ul>
                </div>
                
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
                            @click="openFilterModal(filterConfiguration.role)"
                            >Role</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.group)"
                            >Group</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.squad_callsign)"
                            >Squad</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.region_id)"
                            >Region</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.organization_name)"
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
                    </ul>
                </div>

                <span class="text-muted small" v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</span>
                <em class="text-muted small" v-else>
                    Showing {{ members.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

                <export-list type="squad-members" 
                            :params="`filter[campaign_id]=${campaignId}`"
                            :filters="filters"
                ></export-list>

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
            <table class="table table-condensed table-striped" v-if="members && members.length">
                <thead>
                    <tr class="active">
                        <th>
                            <input type="checkbox" 
                                   @change="selectAll(members, $event.target.checked)"
                                   :checked="selected.length === members.length"
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
                        <th @click="sortBy('role')" style="cursor: pointer; min-width: 150px">
                            Role <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'role', 'fa-sort-down': filters.sort === '-role' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('group')" style="cursor: pointer; min-width: 150px">
                            Group <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'group', 'fa-sort-down': filters.sort === '-group' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('callsign')" style="cursor: pointer; min-width: 150px">
                            Squad <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'callsign', 'fa-sort-down': filters.sort === '-callsign' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('region_name')" style="cursor: pointer; min-width: 150px">
                            Region <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'region_name', 'fa-sort-down': filters.sort === '-region_name' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('organization_name')" style="cursor: pointer; min-width: 150px">
                            Organization <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'organization_name', 'fa-sort-down': filters.sort === '-organization_name' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('trip_type')" style="cursor: pointer; min-width: 150px">
                            Trip Type <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'trip_type', 'fa-sort-down': filters.sort === '-trip_type' }, 'fa-sort']"
                                ></i>
                        </th>
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(member, index) in members" 
                        :key="member.id" 
                        :class="{ 'selected' : isSelected(member) }"
                    >
                        <td>
                            <input 
                                type="checkbox" 
                                :checked="isSelected(member)" 
                                @change="select(member, $event.target.checked)"
                            >
                        </td>
                        <td class="text-muted">{{ index + 1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/reservations/${member.reservation_id}/squad`">
                                    {{ member.surname }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ member.given_names }}</td>
                        <td>{{ member.role.name }}</td>
                        <td>{{ member.squad_group }}</td>
                        <td>{{ member.squad_callsign }}</td>
                        <td>{{ member.region_name }}</td>
                        <td>{{ member.organization_name }}</td>
                        <td>{{ member.trip_type }}</td>
                        <td>{{ member.gender }}</td>
                        <td>{{ member.age }}</td>
                        <td>{{ member.status }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="panel-body" 
                 style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                 v-if="!members.length && !loading"
            >
                <div class="text-center">
                    <span class="lead">No Squad Members</span>
                    <p>Try adjusting the filters, or add someone to a squad to get started.</p>
                    <p>
                        <button class="btn btn-sm btn-primary" @click="$emit('tab:change', 'needs-squad-list')">
                            View Unassigned Missionaries
                        </button>
                    </p>
                </div>
            </div>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>
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
export default {
    props: {
        campaignId: {
            type: String,
            required: true
        },
        cacheKey: {
            type: String,
            default: `${window.location.host}${window.location.pathname}.admin.squadMemberList`
        }
    },

    mixins: [state, activeFilter],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters']);
        }
    },

    data() {
        return {
            selected: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                organization_name: {
                    component: 'filter-search', 
                    title: 'Organization', 
                    field: 'organization_name'
                },
                squad_callsign: {
                    component: 'filter-search', 
                    title: 'Callsign', 
                    field: 'squad_callsign'
                },
                group: {
                    component: 'filter-search', 
                    title: 'Group', 
                    field: 'group'
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
                region_id: {
                    component: 'filter-select', 
                    title: 'Region', 
                    field: 'region_id',
                    ajax: {
                        url: `regions?filter[campaign_id]=${this.campaignId}`,
                        value: 'id',
                        label: 'name'
                    }
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
                role: {
                    component: 'filter-select',
                    title: 'Role',
                    field: 'role',
                    options: teamRoles
                }
            }
        }
    },

    methods: {
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
        },
        editMembers() {
            swal("Update Group:", {
                content: "input",
                button: 'Save Changes',
            })
            .then((value) => {
                if (value) {
                    let ids = _.pluck(this.selected, 'id').join();
                    this.$http
                        .put(`squad-members/${ids}`, {group: value})
                        .then(response => {
                            swal("Nice Work!", "Squad members updated.", "success", {
                                buttons: false,
                                timer: 1000
                            });
                            this.selected = [];
                            this.$refs.list.fetch();
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            });
        },
        removeMembers() {
            swal('WARNING!', `Are you sure you want to remove the ${this.selected.length} selected squad member(s)? This will remove them from their squads. To undo this action, members must be added to squads again.`, 'warning', {
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
                    let ids = _.pluck(this.selected, 'id').join();
                    this.$http
                        .delete(`squad-members/${ids}`)
                        .then(response => {
                            swal("Nice Work!", "Squad members removed.", "success", {
                                buttons: false,
                                timer: 1000
                            });
                            this.$emit('update:membersTotal', -this.selected.length);
                            this.$emit('update:unassignedTotal', this.selected.length);
                            this.selected = [];
                            this.$refs.list.fetch();
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            })
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
