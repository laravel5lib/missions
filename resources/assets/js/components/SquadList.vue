<template>
    <fetch-json 
        :url="`squads?include=region&filter[campaign_id]=${campaignId}`" 
        :parameters="{filter: {}, sort: 'callsign'}"
        ref="list"
        @filter:removed="removeActiveFilter"
        :cache-key="`admin.campaign.${campaignId}.squads.fetchJson`"
    >
        <div slot-scope="{ json:squads, pagination, changePage, changePerPage, loading, addFilter, removeFilter, filters, sortBy }">
            <div class="panel-heading">
                
                <div class="btn-group btn-group-sm" style="margin-right: 1em;" v-if="selected.length">
                    <button class="btn btn-primary" 
                            :disabled="! selected.length"
                            @click="publish"
                    >
                        Publish Squads 
                        <span class="badge" 
                                style="margin-left: 1em;"
                        >{{ selected.length }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="! selected.length">
                        <i class="fa fa-caret-down" style="padding-bottom: 4px"></i>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a type="button" @click="unpublish">Unpublish Squads</a></li>
                    </ul>
                </div>
                <a :href="`/admin/campaigns/${campaignId}/reservations/squads/create`" 
                   class="btn btn-primary btn-sm" 
                   style="margin-right: 1em;"
                   v-else
                >Add New Squad</a>
                
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
                            @click="openFilterModal(filterConfiguration.callsign)"
                            >Callsign</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.region_id)"
                            >Region</a>
                        </li>
                        <li>
                            <a type="button" 
                            @click="openFilterModal(filterConfiguration.published)"
                            >Status</a>
                        </li>
                    </ul>
                </div>

                <em class="text-muted small">
                    Showing {{ squads.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

                <export-list type="squads" 
                            :params="`include=region&filter[campaign_id]=${campaignId}`"
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
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table table-condensed table-striped" v-if="squads && squads.length">
                <thead>
                    <tr class="active">
                        <th>
                            <input type="checkbox" 
                                   @change="selectAll(squads, $event.target.checked)"
                                   :checked="selected.length === squads.length"
                            >
                        </th>
                        <th>#</th>
                        <th @click="sortBy('callsign')" style="cursor: pointer; min-width: 150px">
                            Callsign <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'callsign', 'fa-sort-down': filters.sort === '-callsign' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th class="text-right">Members</th>
                        <th v-if="!filters.filter.region_id">Region</th>
                        <th v-if="!filters.filter.published">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(squad, index) in squads" :key="squad.id">
                        <td>
                            <input 
                                type="checkbox" 
                                :checked="isSelected(squad)" 
                                @change="select(squad, $event.target.checked)"
                            >
                        </td>
                        <td class="text-muted">{{ index + 1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/campaigns/${campaignId}/reservations/squads/${squad.id}`">{{ squad.callsign }}</a>
                            </strong>
                        </td>
                        <td class="text-right"><strong>{{ squad.members_count }}</strong></td>
                        <td v-if="!filters.filter.region_id">{{ squad.region.name }}</td>
                        <td v-if="!filters.filter.published">{{ squad.status | capitalize }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="panel-body" 
                 style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                 v-if="!squads.length && !loading"
            >
                <div class="text-center">
                    <span class="lead">No Squads</span>
                    <p>Try adjusting the filters, or add a squad to get started.</p>
                    <p>
                        <a :href="`/admin/campaigns/${campaignId}/reservations/squads/create`" 
                           class="btn btn-primary btn-sm"
                        >
                            Add New Squad
                        </a>
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
            default: `${window.location.host}${window.location.pathname}.admin.squadList`
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
                published: {
                    component: 'filter-radio', 
                    title: 'Status', 
                    field: 'published',
                    options: [
                        {value: false, label: 'Draft'},
                        {value: true, label: 'Published'}
                    ]
                },
                callsign: {
                    component: 'filter-search', 
                    title: 'Callsign', 
                    field: 'callsign'
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
        publish() {
            swal('WARNING!', `Are you sure you want to publish the ${this.selected.length} selected squad(s)? They will be publicly visible and members will be notified.`, 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Keep Private",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Publish",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http
                        .put(`squads/published`, {squads: this.selected, published: true})
                        .then(response => {
                            swal("Nice Work!", "Squads published.", "success", {
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
            })
        },
        unpublish() {
            swal('WARNING!', `Are you sure you want to unpublish the ${this.selected.length} selected squad(s)? They will no longer be publicly visible.`, 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Keep Public",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Unpublish",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http
                        .put(`squads/published`, {squads: this.selected, published: false})
                        .then(response => {
                            swal("Nice Work!", "Squads unpublished.", "success", {
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
