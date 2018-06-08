<template>
<fetch-json :url="`reservations?filter[campaign]=${campaignId}&filter[has_flight]=${false}&include=trip.group,passport&sort=surname`" ref="list" :parameters="{filter: {}}" @filter:removed="removeActiveFilter">
    <div slot-scope="{ json:reservations, pagination, changePage, loading, addFilter, removeFilter, filters, sort }">
        <div class="panel-heading" v-if="!ui.booking">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-primary" 
                        :disabled="! selectedReservations.length"
                        @click="ui.booking = true"
                >
                    Book Flights 
                    <span class="badge" 
                            style="margin-left: 1em;"
                    >{{ selectedReservations.length }}
                    </span>
                </button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="! selectedReservations.length">
                    <i class="fa fa-caret-down" style="padding-bottom: 4px"></i>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Add to No Flight</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Download</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-heading" v-if="!ui.booking">
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
                    <!-- <li><a type="button" @click="openFilterModal('filter-age', 'Age')">Age</a></li> -->
                    <li>
                        <a type="button" 
                           @click="openFilterModal(filterConfiguration.gender)"
                        >Gender</a>
                    </li>
                    <!-- <li>
                        <a type="button" 
                           @click="openFilterModal(filterConfiguration.status)"
                        >Marital Status</a>
                    </li> -->
                    <li>
                        <a type="button" 
                           @click="openFilterModal(filterConfiguration.passport_number)"
                        >Passport</a>
                    </li>
                    <!-- <li><a type="button" @click="openFilterModal('filter-percent-raised', 'Percent Raised')">Percent Raised</a></li> -->
                    <li>
                        <a type="button" 
                           @click="openFilterModal(filterConfiguration.current_rate)"
                        >Current Rate</a>
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
        <div class="panel-body" v-if="ui.booking">
            <flight-booking-form :reservations="selectedReservations" 
                                 :campaign-id="campaignId"
                                 @close:form="closeFormAndReload"
            ></flight-booking-form>
        </div>
        <div class="panel-body" v-if="loading">
            <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
        </div>
        <div class="table-responsive" v-if="!loading && !ui.booking">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th>
                            <input type="checkbox" 
                                   @change="selectAllReservations(reservations, $event.target.checked)"
                                   :checked="selectedReservations.length === reservations.length"
                            >
                        </th>
                        <th>Surname</th>
                        <th>Given Names</th>
                        <th>Group</th>
                        <th>Trip Type</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Passport #</th>
                        <th>Passort Exp</th>
                        <th>Percent Raised</th>
                        <th>Current Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations" 
                        :key="reservation.id" 
                        :class="{ 'selected' : isSelected(reservation) }"
                    >
                        <td><input type="checkbox" :checked="isSelected(reservation)" @change="selectReservation(reservation, $event.target.checked)"></td>
                        <td><strong><a :href="`/admin/reservations/${reservation.id}`">{{ reservation.surname }}</a></strong></td>
                        <td>{{ reservation.given_names }}</td>
                        <td>{{ reservation.trip.group.name }}</td>
                        <td>{{ reservation.trip.type }}</td>
                        <td>{{ reservation.birthday | mFormat('ll') }}</td>
                        <td>{{ reservation.gender }}</td>
                        <td>{{ reservation.passport ? reservation.passport.number : null }}</td>
                        <td><template v-if="reservation.passport">
                            {{ reservation.passport.expires_at | mFormat('ll') }}
                        </template></td>
                        <td>{{ reservation.percent_raised }}%</td>
                        <td>{{ reservation.current_rate }}</td>
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
import FlightBookingForm from '../components/FlightBookingForm';
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    name: 'flights-not-booked',
    props: ['campaignId'],
    components: {
        'flight-booking-form': FlightBookingForm,
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },
    data() {
        return {
            selectedReservations: [],
            ui: {
                booking: false
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
                passport_number: {
                    component: 'filter-search',
                    title: 'Passport Number', 
                    field: 'passport_number',
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
                gender: {
                    component: 'filter-radio',
                    title: 'Gender', 
                    field: 'gender',
                    options: [
                        {value: 'male', label: 'Male'},
                        {value: 'female', label: 'Female'}
                    ]
                },
                // status: {
                //     component: 'filter-radio',
                //     title: 'Marital Status', 
                //     field: 'status',
                //     options: [
                //         {value: 'single', label: 'Single'},
                //         {value: 'engaged', label: 'Engaged'},
                //         {value: 'married', label: 'Married'},
                //         {value: 'divorced', label: 'Divorced'},
                //         {value: 'widowed', label: 'Widowed'},
                //     ]
                // },
                age: {
                    component: 'filter-radio',
                    title: 'Age Range', 
                    field: 'age',
                    options: []
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
                group: {
                    component: 'filter-select',
                    title: 'Group',
                    field: 'group',
                    ajax: {
                        url: `/campaigns/${this.campaignId}/groups`,
                        value: 'id',
                        label: 'name'
                    }
                }
            }
        }
    },
    methods: {
        selectReservation(reservation, value) {
            if (value) {
                this.selectedReservations.push(reservation);
            } else {
                this.selectedReservations = _.without(this.selectedReservations, reservation);
            }
        },
        selectAllReservations(reservations, value) {
            if (value) {
                this.selectedReservations = reservations;
            } else {
                this.selectedReservations = [];
            }
        },
        isSelected(reservation) {
            return _.findWhere(this.selectedReservations, reservation)
        },
        closeFormAndReload() {
            this.$emit('update:bookedTotal', this.selectedReservations.length);
            this.ui.booking = false;
            this.selectedReservations = [];
            this.$refs.list.fetch();
        },
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

