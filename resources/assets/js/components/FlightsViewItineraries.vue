<template>
<fetch-json :url="`campaigns/${campaignId}/flights/itineraries`" ref="itinerariesList" :parameters="{filter: {}, sort: 'record_locator'}">
<div slot-scope="{ json:itineraries, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
    <div class="panel-heading" v-if="!ui.edit">
        <div class="btn-group btn-group-sm" style="margin-right: 1em;">
            <button class="btn btn-primary" 
                    :disabled="! selected.length"
                    @click="ui.edit = true"
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
                <li><a href="#">Unpublish Itineraries</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Download</a></li>
            </ul>
        </div>

        <span v-for="(value, key, index) in filters.filter" 
              :key="index" 
              class="label label-filter">
              {{ key | capitalize }}: "{{ value | capitalize}}" <a type="button" @click="removeFilter(key)"><i class="fa fa-times"></i></a>
        </span>
        <div class="dropdown" style="display: inline-block; margin-left: 1em;">
            <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Filter By Type</li>
                <li><a type="button" @click="addFilter('type', 'group')">Group</a></li>
                <li><a type="button" @click="addFilter('type', 'individual')">Individual</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Filter By Status</li>
                <li><a type="button" @click="addFilter('published', false)">Draft</a></li>
                <li><a type="button" @click="addFilter('published', true)">Published</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Filter By</li>
                <li><a type="button" @click="openFilterModal('record_locator', 'Record Locator')">Record Locator</a></li>
            </ul>

            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                    </div>
                    <div class="modal-body">
                       <input class="form-control" name="search" v-model="searchFor" :placeholder="`Enter a ${filterModal.title} ...`">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addFilter(searchBy, searchFor), searchBy = null, searchFor = null" data-dismiss="modal">Apply</button>
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
export default {
    props: ['campaignId', 'segmentId'],
    data() {
        return {
            selected: [],
            selectedItineraries: [],
            ui: {
                edit: false
            },
            filterModal: {
                title: null
            },
            searchBy: null,
            searchFor: null
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
        openFilterModal(filter, title)
        {
            this.searchBy = filter;
            this.filterModal.title = title;
            $('#filterModal').modal('show');
        }
    }
}
</script>