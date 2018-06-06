<template>
<fetch-json :url="`reservations?campaign=${campaignId}&notBooked=${true}&include=trip.group&sort=surname`" ref="list">
    <div slot-scope="{ json:reservations, pagination, changePage, loading }">
        <div class="panel-heading" v-if="!ui.booking">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-primary" 
                        :disabled="! selectedReservations.length"
                        @click="ui.booking = true"
                >
                    Book Flights 
                    <span class="badge" 
                            style="margin-left: 1em;"
                    >
                        {{ selectedReservations.length }}
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
            <div class="dropdown">
                <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">Filter By</li>
                    <li><a type="button" @click="openFilterModal('filter-fullname', 'Name')">Name</a></li>
                    <li><a type="button" @click="openFilterModal('filter-group', 'Group')">Group</a></li>
                    <li><a type="button" @click="openFilterModal('filter-trip-type', 'Trip')">Trip</a></li>
                    <li><a type="button" @click="openFilterModal('filter-age', 'Age')">Age</a></li>
                    <li><a type="button" @click="openFilterModal('filter-gender', 'Gender')">Gender</a></li>
                    <li><a type="button" @click="openFilterModal('filter-search', 'Passport')">Passport</a></li>
                    <li><a type="button" @click="openFilterModal('filter-percent-raised', 'Percent Raised')">Percent Raised</a></li>
                    <li><a type="button" @click="openFilterModal('filter-current-rate', 'Current Rate')">Current Rate</a></li>
                </ul>

                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                        </div>
                        <div class="modal-body">
                            <component :is="filterModal.component"></component>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Apply</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
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
                    <tr v-for="reservation in reservations" :key="reservation.id">
                        <td><input type="checkbox" :checked="isSelected(reservation)" @change="selectReservation(reservation, $event.target.checked)"></td>
                        <td><strong><a :href="`/admin/reservations/${reservation.id}`">{{ reservation.surname }}</a></strong></td>
                        <td>{{ reservation.given_names }}</td>
                        <td>{{ reservation.trip.data.group.data.name }}</td>
                        <td>{{ reservation.trip.data.type }}</td>
                        <td>{{ reservation.birthday | mFormat('ll') }}</td>
                        <td>{{ reservation.gender }}</td>
                        <td></td>
                        <td></td>
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
        <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
            <pager :pagination="pagination.pagination" :callback="changePage"></pager>
        </div>
    </div>
</fetch-json>
</template>
<script>
import FlightBookingForm from '../components/FlightBookingForm';
export default {
    name: 'flights-not-booked',
    props: ['campaignId'],
    components: {
        'flight-booking-form': FlightBookingForm
    },
    data() {
        return {
            selectedReservations: [],
            ui: {
                booking: false
            },
            filterModal: {
                component: null,
                title: null
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
        openFilterModal(component, title) {
            this.filterModal.component = component;
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

