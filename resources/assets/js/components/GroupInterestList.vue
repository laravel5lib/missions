<template>
    <fetch-json :url="url" 
                ref="list"
                :parameters="{filter: { status: 'undecided' }, sort: '-created_at'}"
                @filter:removed="removeActiveFilter"
                :cache-key="`interestList.dashboard.group.${groupId}.campaign.${campaignId}`"
    >
        <div class="panel panel-default" 
             slot-scope="{ json:interests, addFilter, removeFilter, filters, sort, changePage, pagination, loading }"
        >
            <div class="panel-heading">
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
                            @click="openFilterModal(filterConfiguration.name)"
                            >Name</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.trip_type)"
                            >Trip</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.trip_tags)"
                            >Trip Tags</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.received_between)"
                            >Received</a>
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
                    Showing {{ interests.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

                <export-list type="interests" 
                            :params="`filter[campaign]=${campaignId}&filter[group]=${groupId}&include=trip.group,trip.campaign`"
                            :filters="filters"
                ></export-list>

            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table table-striped" v-if="interests && interests.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Name</th>
                        <th v-if="!filters.filter.trip_type">Trip</th>
                        <th>Tags</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Preference</th>
                        <th>Recieved</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(interest, index) in interests" :key="interest.id">
                        <td>{{ index+1 }}</td>
                        <td>{{ interest.name }}</td>
                        <td v-if="!filters.filter.trip_type">{{ interest.trip.type | capitalize }}</td>
                        <td>
                            <span class="label label-default" 
                                  style="margin-right: 1em;" 
                                  v-for="tag in interest.trip.tags"
                            >
                                {{ tag.name }}
                            </span>
                        </td>
                        <td><strong><a :href="`mailto:${interest.email}`">{{ interest.email }}</a></strong></td>
                        <td>{{ interest.phone }}</td>
                        <td>
                            <span v-for="(preference, index) in interest.communication_preferences" 
                                  :key="index"
                                  class="label label-default"
                                  style="margin-right: 0.5em;"
                            >
                                {{ preference }}
                            </span>
                        </td>
                        <td>{{ interest.created_at | moment('lll') }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="panel-body text-center" v-if="!interests.length && !loading">
                <hr class="divider inv xlg">
                <span class="lead">No Interests</span>
                <p>Try adjusting the filters.</p>
                <hr class="divider inv xlg">
                <hr class="divider inv xlg">
                <hr class="divider inv xlg">
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
import FilterCheckbox from '../components/FilterCheckbox';
export default {
    props: {
        campaignId: String,
        groupId: String,
        totals: Object
    },

    mixins: [state, dates, activeFilter],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect,
        'filter-checkbox': FilterCheckbox,
    },

    data() {
        return {
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                name: {
                    component: 'filter-search',
                    title: 'Name',
                    field: 'name',
                },
                group: {
                    component: 'filter-select',
                    title: 'Group',
                    field: 'group',
                    ajax: {
                        url: `/campaigns/${this.campaignId}/groups?per_page=200`,
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
                received_between: {
                    component: 'filter-radio',
                    title: 'Received',
                    field: 'received_between',
                    options: []
                }
            }
        }
    },

    computed: {
        url() {
            return `interests?filter[campaign]=${this.campaignId}&filter[group]=${this.groupId}&include=trip.group,trip.tags`;
        },
        receivedBetween() {
            return [
                {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
            ];
        }
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters']);
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
        this.filterConfiguration.received_between.options = this.receivedBetween;

        var previousState = this.restoreState();
        if (previousState) {
            this.activeFilters = previousState.activeFilters;
        }
    }
}
</script>

<style>
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>
