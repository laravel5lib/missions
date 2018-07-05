<template>
    <fetch-json 
        :url="`regions?filter[campaign_id]=${campaignId}`" 
        :parameters="{filter: {}, sort: 'name'}"
        ref="list"
        @filter:removed="removeActiveFilter"
        :cache-key="`admin.campaign.${campaignId}.regions.fetchJson`"
    >
        <div slot-scope="{ json:regions, pagination, changePage, changePerPage, loading, addFilter, removeFilter, filters, sortBy }">
            <div class="panel-heading">

                <a :href="`/admin/campaigns/${campaignId}/regions/create`" 
                   class="btn btn-primary btn-sm" 
                   style="margin-right: 1em;"
                >Add New Region</a>
                
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
                            @click="openFilterModal(filterConfiguration.name)"
                            >Name</a>
                        </li>
                    </ul>
                </div>

                <em class="text-muted small">
                    Showing {{ regions.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

                <export-list type="regions" 
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
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table table-condensed table-striped" v-if="regions && regions.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th @click="sortBy('name')" style="cursor: pointer; min-width: 150px">
                            Name <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'name', 'fa-sort-down': filters.sort === '-name' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th class="text-right">Squads</th>
                        <th class="text-right">Missionaries</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(region, index) in regions" :key="region.id">
                        <td class="text-muted">{{ index + 1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/campaigns/${campaignId}/regions/${region.id}`">{{ region.name }}</a>
                            </strong>
                        </td>
                        <td class="text-right"><strong>{{ region.squads_count }}</strong></td>
                        <td class="text-right"><strong>{{ region.missionaires_count }}</strong></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="panel-body" 
                 style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                 v-if="!regions.length && !loading"
            >
                <div class="text-center">
                    <span class="lead">No Regions</span>
                    <p>Try adjusting the filters, or create a new region to get started.</p>
                    <p>
                        <a :href="`/admin/campaigns/${campaignId}/regions/create`" 
                           class="btn btn-primary btn-sm"
                        >
                            Add New Region
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
            default: `${window.location.host}${window.location.pathname}.admin.regionList`
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
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                name: {
                    component: 'filter-search', 
                    title: 'Region Name', 
                    field: 'name'
                }
            }
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
        var previousState = this.restoreState();
        if (previousState) {
            this.activeFilters = previousState.activeFilters;
        }
    }
}
</script>
