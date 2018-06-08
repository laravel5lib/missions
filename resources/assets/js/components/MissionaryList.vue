<template>
    <fetch-json :url="`reservations?filter[campaign]=${campaignId}&include=trip.group`" 
                ref="list" 
                :parameters="{filter: {}}" 
                @filter:removed="removeActiveFilter"
    >
        <div class="panel panel-default" 
             slot-scope="{ json:reservations, pagination, changePage, loading, addFilter, removeFilter, filters, sort }" style="border-top: 5px solid #f6323e"
        >
            <div class="panel-heading">
                <ul class="nav nav-pills nav-justified">
                    <li class="active">
                        <a role="button">
                            All <span class="badge badge-default">0</span>
                        </a>
                    </li>
                    <li>
                        <a role="button">
                            Deposit Only <span class="badge badge-default">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            In Process <span class="badge badge-default">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Fully Funded <span class="badge badge-default">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Travel Ready <span class="badge badge-default">0</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <form class="form-inline" style="display: inline">
                    <!-- <label>Layout:</label>
                    <a type="button" class="btn btn-xs btn-link"><i class="fa fa-th-list"></i></a>
                    <a type="button" class="btn btn-xs btn-link"><i class="fa fa-list"></i></a> -->
                    <label>Per Page:</label>
                    <select class="form-control input-sm" v-model="pagination.per_page">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                    <label>View:</label>
                    <select class="form-control input-sm" v-model="view">
                        <option value="missionary">Missionary</option>
                        <option value="fundraising">Fundraising</option>
                        <option value="mailing">Mailing</option>
                    </select>
                </form>
                <span v-for="(label, index) in activeFilters" 
                    :key="index" 
                    class="label label-filter">
                    {{ label.text | capitalize }}: "{{ label.value | capitalize}}" <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
                </span>
                <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                    <a role="button" @click="ui.allFilters = true">+ Add a filter</a>

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
            <div class="panel-body" v-if="ui.allFilters">
                <div class="row text-right">
                    <div class="col-xs-12">
                        <strong><a type="button" @click="ui.allFilters = false">x</a></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Filter By</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.surname)"
                                >+ Surname</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.given_names)"
                                >+ Given Names</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.group)"
                                >+ Group</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.trip_type)"
                                >+ Trip</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.desired_role)"
                                >+ Role</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.gender)"
                                >+ Gender</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.age_range)"
                                >+ Age Range</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.status)"
                                >+ Marital Status</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.shirt_size)"
                                >+ Shirt Size</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <label>Filter By Contact</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.email)"
                                >+ Email</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.phone_one)"
                                >+ Primary Phone</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.phone_two)"
                                >+ Secondary Phone</a>
                            </li>
                        </ul>
                        <label>Filter By Location</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.country_code)"
                                >+ Country</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.state)"
                                >+ State</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.zip)"
                                >+ Zip Code</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.city)"
                                >+ City</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.address)"
                                >+ Address</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <label>Filter By Fundraising</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.current_rate)"
                                >+ Current Rate</a>
                            </li>
                            <li>
                                <a type="button" 
                                @click="openFilterModal(filterConfiguration.percent_raised)"
                                >+ Percentage Raised</a>
                            </li>
                        </ul>
                        <label>Filter By Date</label>
                        <ul class="list-unstyled">
                            <li>
                                <a type="button" 
                                @click="openFilterModal(registeredBetween)"
                                >+ Registered</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Surname</th>
                        <th>Given Names</th>
                        <th v-if="!filters.filter.group">Group</th>
                        <th v-if="!filters.filter.trip_type">Trip</th>
                        <template v-if="view === 'missionary'">
                            <th v-if="!filters.filter.marital_status">Role</th>
                            <th>Age</th>
                            <th v-if="!filters.filter.gender">Gender</th>
                            <th v-if="!filters.filter.status">Status</th>
                            <th>Email</th>
                            <th>Primary Phone</th>
                            <th>Secondary Phone</th>
                            <th>Registered</th>
                        </template>
                        <template v-if="view === 'fundraising'">
                            <th>% Raised</th>
                            <th>Current Rate</th>
                        </template>
                        <template v-if="view === 'mailing'">
                            <th>Shirt Size</th>
                            <th>Country</th>
                            <th>Zip Code</th>
                            <th>State/Providence</th>
                            <th>City</th>
                            <th>Address</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                        <td>{{ index+1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/reservations/${reservation.id}`">{{ reservation.surname }}</a>
                            </strong>
                        </td>
                        <td>{{ reservation.given_names }}</td>
                        <td v-if="!filters.filter.group">{{ reservation.trip.group.name }}</td>
                        <td v-if="!filters.filter.trip_type">{{ reservation.trip.type }}</td>
                        <template v-if="view === 'missionary'">
                            <td v-if="!filters.filter.desired_role">{{ reservation.desired_role.name }}</td>
                            <td>{{ reservation.age }}</td>
                            <td v-if="!filters.filter.gender">{{ reservation.gender }}</td>
                            <td v-if="!filters.filter.status">{{ reservation.status }}</td>
                            <td>{{ reservation.email }}</td>
                            <td>{{ reservation.phone_one }}</td>
                            <td>{{ reservation.phone_two }}</td>
                            <td>{{ reservation.created_at | moment('ll') }}</td>
                        </template>
                        <template v-if="view === 'fundraising'">
                            <td>{{ reservation.percent_raised }}%</td>
                            <td>{{ reservation.current_rate }}</td>
                        </template>
                        <template v-if="view === 'mailing'">
                            <td>{{ reservation.shirt_size }}</td>
                            <td>{{ reservation.country_name }}</td>
                            <td>{{ reservation.zip }}</td>
                            <td>{{ reservation.state }}</td>
                            <td>{{ reservation.city }}</td>
                            <td>{{ reservation.address }}</td>
                        </template>
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
import FilterSearch from '../components/FilterSearch';
import FilterRadio from '../components/FilterRadio';
import FilterSelect from '../components/FilterSelect';
export default {
    props: ['campaignId'],

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio,
        'filter-select': FilterSelect
    },

    data() {
        return {
            ui: {
                allFilters: false
            },
            view: 'missionary',
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
                status: {
                    component: 'filter-radio',
                    title: 'Marital Status', 
                    field: 'status',
                    options: [
                        {value: 'single', label: 'Single'},
                        {value: 'engaged', label: 'Engaged'},
                        {value: 'married', label: 'Married'},
                        {value: 'divorced', label: 'Divorced'},
                        {value: 'widowed', label: 'Widowed'},
                    ]
                },
                age_range: {
                    component: 'filter-radio',
                    title: 'Age Range', 
                    field: 'age_range',
                    options: [
                        { value: '8,12', label: '8-12' },
                        { value: '13,17', label: '13-17' },
                        { value: '18,30', label: '18-30' },
                        { value: '30,40', label: '30-40' },
                        { value: '40,50', label: '40-50' },
                        { value: '50', label: '50+' }
                    ]
                },
                shirt_size: {
                    component: 'filter-radio',
                    title: 'Shirt Size', 
                    field: 'shirt_size',
                    options: [
                        {value: 'XS', label: '(XS) Extra Small'},
                        {value: 'S', label: '(S) Small'},
                        {value: 'M', label: '(M) Medium'},
                        {value: 'L', label: '(L) Large'},
                        {value: 'XL', label: '(XL) Extra Large'},
                        {value: 'XXL', label: '(XXL) Extra Large x2'},
                        {value: 'XXXL', label: '(XXXL) Extra Large x3'},
                    ]
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
                        url: `/campaigns/${this.campaignId}/groups?per_page=100`,
                        value: 'id',
                        label: 'name'
                    }
                },
                desired_role: {
                    component: 'filter-select',
                    title: 'Role',
                    field: 'desired_role',
                    ajax: {
                        url: `/team-roles`,
                        value: '',
                        lavel: ''
                    }
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
                percent_raised: {
                    component: 'filter-radio',
                    title: 'Percentage Raised', 
                    field: 'percent_raised_range',
                    options: [
                        // <5% / 6-20% / 21-49% / 50-75% / 76-99% / 100%
                        { value: '0,5', label: '<5%' },
                        { value: '6,20', label: '6-20%' },
                        { value: '21,49', label: '21-49%' },
                        { value: '50,75', label: '50-75%' },
                        { value: '76,99', label: '76-99%' },
                        { value: '100', label: '100%' },
                    ]
                },
                email: {
                    component: 'filter-search', 
                    title: 'Email', 
                    field: 'email',
                },
                phone_one: {
                    component: 'filter-search', 
                    title: 'Primary Phone', 
                    field: 'phone_one',
                },
                phone_two: {
                    component: 'filter-search', 
                    title: 'Secondary Phone', 
                    field: 'phone_two',
                },
                city: {
                    component: 'filter-search', 
                    title: 'City', 
                    field: 'city',
                },
                state: {
                    component: 'filter-search', 
                    title: 'State/Providence', 
                    field: 'state',
                },
                address: {
                    component: 'filter-search', 
                    title: 'Address', 
                    field: 'address',
                },
                zip: {
                    component: 'filter-search', 
                    title: 'Zip Code', 
                    field: 'zip',
                }
            }
        }
    },

    computed: {
        startOfToday() {
            return moment().startOf('day').format();
        },
        endOfToday() {
            var time = moment().startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfYesterday() {
            return moment().subtract(1, 'day').startOf('day').format();
        },
        endOfYesterday() {
            var time = moment().subtract(1, 'day').startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfWeek() {
            return moment().startOf('week').format();
        },
        endOfWeek() {
            var time = moment().startOf('week');
            return time.clone().endOf('week').format();
        },
        startOfMonth() {
            return moment().startOf('month').format();
        },
        endOfMonth() {
            var time = moment().startOf('month');
            return time.clone().endOf('month').format();
        },
        startOfLastMonth() {
            return moment().subtract(1, 'month').startOf('month').format();
        },
        endOfLastMonth() {
            var time = moment().subtract(1, 'month').startOf('month');
            return time.clone().endOf('month').format();
        },
        registeredBetween() {
            return {
                component: 'filter-radio',
                title: 'Registered', 
                field: 'registered_between',
                options: [
                    {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                    {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                    {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                    {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                    {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
                ]
            }
        }
    },

    methods: {
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
