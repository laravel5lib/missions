<template>
    <div style="position:relative;">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <div id="toggleFields" class="form-toggle-menu dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Fields
                            <span class="caret"></span>
                        </button>
                        <ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')"> Group Name
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="location" :disabled="maxCheck('location')"> Location
                                </label>
                            </li>
                            <template v-if="pending">
                                <li>
                                    <label class="small" style="margin-bottom: 0px;">
                                        <input type="checkbox" v-model="activeFields" value="phone_one" :disabled="maxCheck('phone_one')"> Phone
                                    </label>
                                </li>
                                <li>
                                    <label class="small" style="margin-bottom: 0px;">
                                        <input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
                                    </label>
                                </li>
                            </template>
                            <template v-else>
                                <li>
                                    <label class="small" style="margin-bottom: 0px;">
                                        <input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
                                    </label>
                                </li>
                                <li>
                                    <label class="small" style="margin-bottom: 0px;">
                                        <input type="checkbox" v-model="activeFields" value="trips" :disabled="maxCheck('trips')"> Active Trips
                                    </label>
                                </li>
                                <li>
                                    <label class="small" style="margin-bottom: 0px;">
                                        <input type="checkbox" v-model="activeFields" value="trips" :disabled="maxCheck('reservations_count')"> Active Reservations
                                    </label>
                                </li>
                            </template>
                            <li role="separator" class="divider"></li>
                            <li>
                                <div style="margin-bottom: 0px;" class="input-group input-group-sm">
                                    <label>Max Visible Fields</label>
                                    <select class="form-control" v-model="maxActiveFields">
                                        <option v-for="option in maxActiveFieldsOptions" :value="option">{{option}}</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Filter
                            <span class="caret"></span>
                        </button>
                        <ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.status" value=""> Any Status
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.status" value="yes"> Public Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.status" value="no"> Private only
                                </label>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value=""> Any Type
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value="church"> Church Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value="business"> Business only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value="nonprofit"> Non-Profit Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value="youth"> Private only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="filters.type" value="other"> Other only
                                </label>
                            </li>
                        </ul>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter">Reset Filters</button>
                    <export-utility url="groups/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                    <!-- <import-utility title="Import Groups List"
                              url="groups/import"
                              :required-fields="importRequiredFields"
                              :optional-fields="importOptionalFields">
                    </import-utility> -->
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
                    Group
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <template v-if="pending">
                    <th v-if="isActive('location')" :class="{'text-primary': orderByField === 'country_name'}">
                        Location
                        <!--<i @click="setOrderByField('notes.data[0].content')" v-if="orderByField !== 'notes.data[0].content'" class="fa fa-sort pull-right"></i>-->
                        <!--<i @click="direction=direction*-1" v-if="orderByField === 'notes.data[0].content'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>-->
                    </th>
                    <th v-if="isActive('phone_one')" :class="{'text-primary': orderByField === 'phone_one'}">
                        Phone One
                        <i @click="setOrderByField('public')" v-if="orderByField !== 'public'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'public'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('email')" :class="{'text-primary': orderByField === 'email'}">
                        Email
                        <i @click="setOrderByField('email')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                </template>
                <template v-else>
                    <th v-if="isActive('location')" :class="{'text-primary': orderByField === 'country_name'}">
                        Location
                        <!--<i @click="setOrderByField('campaign.data.name')" v-if="orderByField !== 'campaign.data.name'" class="fa fa-sort pull-right"></i>-->
                        <!--<i @click="direction=direction*-1" v-if="orderByField === 'campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>-->
                    </th>
                    <th v-if="isActive('status')" :class="{'text-primary': orderByField === 'public'}">
                        Status
                        <i @click="setOrderByField('public')" v-if="orderByField !== 'public'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'public'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('trips')">Active Trips</th>
                    <th v-if="isActive('reservations_count')">Active Reservations</th>
                </template>

                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="group in groups">
                <td v-if="isActive('name')">{{group.name}}</td>
                <td v-if="isActive('type')">{{ group.type|capitalize }}</td>
                <td v-if="isActive('location')">{{ group.state|capitalize }}, {{ group.country_name|capitalize }}</td>
                <template v-if="pending">
                    <td v-if="isActive('phone_one')">{{group.phone_one}}</td>
                    <td v-if="isActive('email')">{{group.email}}</td>
                </template>
                <template v-else>
                    <td v-if="isActive('status')">{{group.public ? 'Public' : 'Private'}}</td>
                    <td v-if="isActive('trips')">{{group.trips.data.length}}</td>
                    <td v-if="isActive('reservations_count')">{{ group.reservations_count }}</td>
                </template>
                <td>
                    <a data-toggle="tooltip" data-placement="top" title="Manage" :href="`/admin/groups/${ group.id }`"><i class="fa fa-gear"></i></a>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination="pagination" pagination-key="pagination" :callback="searchGroups"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import exportUtility from '../export-utility.vue';
    import importUtility from '../import-utility.vue';
    export default{
        name: 'admin-groups',
        components: {exportUtility, importUtility},
        props: {
            pending: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return {
                groups: [],
                orderByField: 'name',
                direction: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {current_page: 1},
                search: '',
                filters: {
                    status: '',
                    type: ''
                },
                activeFields: this.pending ? ['name', 'type', 'location', 'phone_one', 'email'] : ['name', 'type', 'location', 'status', 'trips', 'reservations_count'],
                maxActiveFields: 6,
                maxActiveFieldsOptions: [2, 3, 4, 5, 6],
                exportOptions: {
                  name: 'Name',
                  email: 'Email',
                  type: 'Type',
                  status: 'Status',
                  phone_one: 'Primary Phone',
                  phone_two: 'Secondary Phone',
                  address: 'Street Address',
                  city: 'City',
                  state: 'State/Providence',
                  country: 'Country',
                  country_code: 'Country Code',
                  timezone: 'Timezone',
                  description: 'Description',
                  visibility: 'Visibility',
                  url: 'Profile URL',
                  avatar_source: 'Logo Source',
                  banner_source: 'Banner Source',
                  created: 'Created On',
                  updated: 'Last Updated'
                },
                exportFilters: {},
                importRequiredFields: [
                  'name', 'type', 'country_code', 'timezone',
                ],
                importOptionalFields: [
                  'email', 'status', 'url', 'created_at', 'updated_at',
                  'phone_one', 'phone_two', 'address', 'city', 'state', 'zip',
                  'description', 'visibility', 'logo_source', 'banner_source',
                  'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url',
                  'pinterest_url', 'website_url', 'youtube_url', 'vimeo_url', 'google_url'
                ],
            }
        },
        watch: {
            'search'(val, oldVal) {
                this.pagination.current_page = 1;
                this.searchGroups();
            },
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    // console.log(val);
                    this.exportFilters = val;
                    this.pagination.current_page = 1;
                    this.searchGroups();
                },
                deep: true
            },
            'activeFields'(val, oldVal) {
                // if the orderBy field is removed from view
                if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                // this.updateConfig();
            },'orderByField'(val, oldVal) {
                this.searchGroups();
            },
            'direction'(val, oldVal) {
                this.searchGroups();
            },
            'per_page'(val, oldVal) {
                this.searchGroups();
            }
        },

        methods: {
            isActive(field){
                return _.contains(this.activeFields, field);
            },
            maxCheck(field){
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'name';
                this.direction = 1;
                this.search = '';
                this.status = '';
                this.type = '';
            },
            debouncedSearch: _.debounce(function() { this.searchgroups() }, 250),
            searchGroups(){
                this.$http.get('groups', { params: {
                    include: 'trips:status(active),notes',
                    sort: this.orderByField + (this.direction ? ' ASC' : ' DESC'),
                    isPublic: this.filters.status,
                    type: this.filters.type,
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    pending: this.pending ? true : null
                }}).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.groups = response.data.data;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Something went wrong!')
                })
            }
        },
        mounted(){
            this.searchGroups();
            $('[data-toggle="tooltip"]').tooltip();

            //Manually handle dropdown functionality to keep dropdown open until finished
            $('.form-toggle-menu .dropdown-menu').on('click', function(event){
                let events = $._data(document, 'events') || {};
                events = events.click || [];
                for(let i = 0; i < events.length; i++) {
                    if(events[i].selector) {

                        //Check if the clicked element matches the event selector
                        if($(event.target).is(events[i].selector)) {
                            events[i].handler.call(event.target, event);
                        }

                        // Check if any of the clicked element parents matches the
                        // delegated event selector (Emulating propagation)
                        $(event.target).parents(events[i].selector).each(function(){
                            events[i].handler.call(this, event);
                        });
                    }
                }
                event.stopPropagation(); //Always stop propagation
            });

        }
    }
</script>
