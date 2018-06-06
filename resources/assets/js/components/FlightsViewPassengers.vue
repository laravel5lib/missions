<template>
<fetch-json :url="`campaigns/${campaignId}/flights/passengers?segment=${segmentId}`" 
            ref="passengersList" 
            :parameters="{filter: {}}"
>
<div slot-scope="{ json:passengers, pagination, changePage, loading, addFilter, removeFilter, filters, sortBy }">
    <div class="panel-heading" v-if="!ui.edit">
        <div class="btn-group btn-group-sm" style="margin-right: 1em;">
            <button class="btn btn-primary" 
                    :disabled="! selected.length"
                    @click="ui.edit = true, $emit('mode:edit')"
            >
                Edit Passengers 
                <span class="badge" 
                        style="margin-left: 1em;"
                >
                    {{ selected.length }}
                </span>
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="! selected.length">
                <i class="fa fa-caret-down" style="padding-bottom: 4px"></i>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Edit Passengers</a></li>
                <li><a href="#">Remove Passengers</a></li>
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
                <li class="dropdown-header">Filter By</li>
                <li><a type="button" @click="openFilterModal('surname', 'Surname')">Surname</a></li>
                <li><a type="button" @click="openFilterModal('given_names', 'Given Names')">Given Names</a></li>
                <li><a type="button" @click="openFilterModal('record_locator', 'Record Locator')">Record Locator</a></li>
                <li><a type="button" @click="openFilterModal('flight_no', 'Flight No.')">Flight No.</a></li>
                <li><a type="button" @click="openFilterModal('iata_code', 'City')">City</a></li>
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
    </div>
    <div class="panel-body" v-if="ui.edit">
        <flight-passenger-form :campaign-id="campaignId"            
                               :passengers="selected"
        ></flight-passenger-form>
    </div>
    <div class="panel-body" v-if="loading && !ui.edit">
        <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
    </div>
    <div class="table-responsive" v-if="!loading && !ui.edit">
            <table class="table" v-if="passengers && passengers.length">
                <thead>
                    <tr class="active">
                        <th>
                            <input type="checkbox" 
                                @change="selectAll(passengers, $event.target.checked)"
                                :checked="selected.length === passengers.length"
                            >
                        </th>
                        <th @click="sortBy('surname')" style="cursor: pointer; min-width: 150px">
                            Surname <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'surname', 'fa-sort-down': filters.sort === '-surname' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th @click="sortBy('given_names')" style="cursor: pointer; min-width: 200px">
                            Given Names <i class="pull-right fa" 
                                    :class="[{ 'fa-sort-up': filters.sort === 'given_names', 'fa-sort-down': filters.sort === '-given_names' }, 'fa-sort']"
                                ></i>
                        </th>
                        <th>Group</th>
                        <th>Trip</th>
                        <th>Type</th>
                        <th>Record Locator</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Flight</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="passenger in passengers" :key="passenger.id">
                        <td><input type="checkbox" :checked="isSelected(passenger)" @change="select(passenger, $event.target.checked)"></td>
                        <td>
                            <strong>
                                <a :href="`/admin/reservations/${passenger.id}`">
                                    {{ passenger.surname }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ passenger.given_names }}</td>
                        <td>{{ passenger.group }}</td>
                        <td>{{ passenger.trip }}</td>
                        <td><em>{{ passenger.type }}</em></td>
                        <td>
                            <strong>
                                <a :href="`/admin/itineraries/${passenger.flight_itinerary_id}`">
                                    {{ passenger.record_locator }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ passenger.flight.date | mFormat('ll') }}</td>
                        <td>{{ passenger.flight.time }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/flights/${passenger.flight.id}`">
                                    {{ passenger.flight.flight_no }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ passenger.flight.iata_code }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="panel-body text-center" v-if="!passengers.length && !loading && !ui.edit">
        <span class="lead">No Passengers</span>
        <p>Try adjusting the filters, or book some flights.</p>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page && !ui.edit">
        <pager :pagination="pagination" :callback="changePage"></pager>
    </div>
</div>
</fetch-json>
</template>
<script>
import FlightPassengerForm from '../components/FlightPassengerForm';
export default {
    props: ['campaignId', 'segmentId'],
    components: {
        'flight-passenger-form': FlightPassengerForm
    },
    watch: {
        'segmentId'(val) {
            this.$refs.passengersList.fetch();
        }
    },
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
<style>
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>