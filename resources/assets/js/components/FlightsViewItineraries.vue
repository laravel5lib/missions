<template>
<fetch-json :url="`campaigns/${campaignId}/flights/itineraries`" ref="itinerariesList" :parameters="{filter: {}, sort: 'record_locator'}" @filter:removed="removeActiveFilter">
<div slot-scope="{ json:itineraries, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
    <div class="panel-heading" v-if="!ui.edit">
        <div class="btn-group btn-group-sm" style="margin-right: 1em;">
            <button class="btn btn-primary" 
                    :disabled="! selected.length"
                    @click="publish"
            >
                Publish Itineraries 
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
                <li><a type="button" @click="unpublish">Unpublish Itineraries</a></li>
            </ul>
        </div>

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
                <li><a type="button" @click="openFilterModal(filterConfiguration.type)">Type</a></li>
                <li><a type="button" @click="openFilterModal(filterConfiguration.published)">Status</a></li>
                <li><a type="button" @click="openFilterModal(filterConfiguration.record_locator)">Record Locator</a></li>
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
            Showing {{ itineraries.length || 0 }} of {{ pagination.total || 0 }} results
        </em>

    </div>
    <div class="panel-body" v-if="loading">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading && !ui.edit">
        <table class="table" v-if="itineraries && itineraries.length">
            <thead>
                <tr class="active">
                    <th><input type="checkbox" 
                            @change="selectAll(itineraries, $event.target.checked)"
                            :checked="selected.length === itineraries.length"
                        >
                    </th>
                    <th @click="sortBy('record_locator')" style="cursor: pointer">
                        Record Locator <i class="pull-right fa" 
                                :class="[{ 'fa-sort-up': filters.sort === 'record_locator', 'fa-sort-down': filters.sort === '-record_locator' }, 'fa-sort']"
                            ></i>
                    </th>
                    <th @click="sortBy('type')" style="cursor: pointer">
                        Type <i class="pull-right fa" 
                                :class="[{ 'fa-sort-up': filters.sort === 'type', 'fa-sort-down': filters.sort === '-type' }, 'fa-sort']"
                            ></i>
                    </th>
                    <th @click="sortBy('updated_at')" style="cursor: pointer">
                        Last Updated <i class="pull-right fa" 
                                :class="[{ 'fa-sort-up': filters.sort === 'updated_at', 'fa-sort-down': filters.sort === '-updated_at' }, 'fa-sort']"
                            ></i>
                    </th>
                    <th>Status</th>
                    <th class="text-right">Flights</th>
                    <th class="text-right">Passengers</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="itinerary in itineraries" :key="itinerary.id" :class="{ 'selected' : isSelected(itinerary) }">
                    <td><input type="checkbox" :checked="isSelected(itinerary)" @change="select(itinerary, $event.target.checked)"></td>
                    <td>
                        <strong>
                            <a :href="`/admin/campaigns/${campaignId}/itineraries/${itinerary.id}`">
                                {{ itinerary.record_locator }}
                            </a>
                        </strong>
                    </td>
                    <td><em>{{ itinerary.type | capitalize }}</em></td>
                    <td>{{ itinerary.updated_at | moment('lll') }}</td>
                    <td><em>{{ itinerary.published ? 'Published' : 'Draft' }}</em></td>
                    <td class="text-right"><code>{{ itinerary.flight_count }}</code></td>
                    <td class="text-right"><code>{{ itinerary.passenger_count }}</code></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-body text-center" v-if="!itineraries.length && !loading">
        <span class="lead">No Itineraries</span>
        <p>Try adjusting the filters, or book some flights.</p>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page && !ui.edit">
        <pager :pagination="pagination" :callback="changePage"></pager>
    </div>
</div>
</fetch-json>
</template>
<script>
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
export default {
    props: ['campaignId', 'segmentId'],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio
    },

    data() {
        return {
            selected: [],
            selectedItineraries: [],
            ui: {
                edit: false
            },
            activeFilters: [],
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                record_locator: {
                    component: 'filter-search', 
                    title: 'Record Locator', 
                    field: 'record_locator'
                },
                type: {
                    component: 'filter-radio', 
                    title: 'Type', 
                    field: 'type',
                    options: [
                        {value: 'individual', label: 'Individual'},
                        {value: 'group', label: 'Group'}
                    ]
                },
                published: {
                    component: 'filter-radio', 
                    title: 'Status', 
                    field: 'published',
                    options: [
                        {value: false, label: 'Draft'},
                        {value: true, label: 'Published'}
                    ]
                },
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
        isSelected(record)
        {
            return _.findWhere(this.selected, record)
        },
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
            if (_.findWhere(this.activeFilters, {key: key})) {
                this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, {key: key}));
            }
        },
        publish() {
            swal('WARNING!', `Are you sure you want to publish the ${this.selected.length} selected itinerarie(s)? They will be publicly visible and passengers will be notified.`, 'warning', {
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
                        .put(`flights/itineraries/published`, {itineraries: this.selected, published: true})
                        .then(response => {
                            swal("Nice Work!", "Itineraries published.", "success", {
                                buttons: false,
                                timer: 1000
                            });
                            this.selected = [];
                            this.$refs.itinerariesList.fetch();
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            })
        },
        unpublish() {
            swal('WARNING!', `Are you sure you want to unpublish the ${this.selected.length} selected itinerarie(s)? They will no longer be publicly visible.`, 'warning', {
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
                        .put(`flights/itineraries/published`, {itineraries: this.selected, published: false})
                        .then(response => {
                            swal("Nice Work!", "Itineraries unpublished.", "success", {
                                buttons: false,
                                timer: 1000
                            });
                            this.selected = [];
                            this.$refs.itinerariesList.fetch();
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