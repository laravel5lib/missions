<template>
    <div>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <reservations-filters ref="filters" v-model="filters" :reset-callback="resetFilter" :pagination="pagination" pagination-key="pagination" :callback="searchReservations" :storage="storageName" :trip-specific="!!tripId"></reservations-filters>
            <hr class="divider inv sm">
        </mm-aside>

        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control  input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
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
                                    <input type="checkbox" v-model="activeFields" value="given_names" :disabled="maxCheck('given_names')"> Given Names
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="surname" :disabled="maxCheck('surname')"> Surname
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="desired_role" :disabled="maxCheck('desired_role')"> Role
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="group" :disabled="maxCheck('group')"> Group
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="campaign" :disabled="maxCheck('campaign')"> Campaign
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="total_raised" :disabled="maxCheck('total_raised')"> Amout Raised
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="percent_raised" :disabled="maxCheck('percent_failed')"> Percent Raised
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="registered" :disabled="maxCheck('registered')"> Registered On
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="gender" :disabled="maxCheck('gender')"> Gender
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="age" :disabled="maxCheck('age')"> Age
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="requirements" :disabled="maxCheck('requirements')"> Requirements
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="rep" :disabled="maxCheck('rep')"> Trip Rep
                                </label>
                            </li>
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
                    <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                        Filters
                        <i class="fa fa-filter"></i>
                    </button>
                    <export-utility url="reservations/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                </form>
            </div>
        </div>
        <hr class="divider sm">
	    <filters-indicator :filters="filters"></filters-indicator>

        <hr class="divider sm">
        <div style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th v-if="isActive('given_names')" :class="{'text-primary': orderByField === 'given_names'}">
                        Given Names
                        <i @click="setOrderByField('given_names')" v-if="orderByField !== 'given_names'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'given_names'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('surname')" :class="{'text-primary': orderByField === 'surname'}">
                        Surname
                        <i @click="setOrderByField('surname')" v-if="orderByField !== 'surname'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'surname'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('desired_role')" :class="{'text-primary': orderByField === 'desired_role'}">
                        Role
                        <i @click="setOrderByField('desired_role')" v-if="orderByField !== 'desired_role'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'desired_role'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('group')" :class="{'text-primary': orderByField === 'trip.data.group.data.name'}">
                        Group
                        <i @click="setOrderByField('trip.data.group.data.name')" v-if="orderByField !== 'trip.data.group.data.name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('campaign')" :class="{'text-primary': orderByField === 'trip.data.campaign.data.name'}">
                        Campaign
                        <i @click="setOrderByField('trip.data.campaign.data.name')" v-if="orderByField !== 'trip.data.campaign.data.name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'trip.data.type'}">
                        Type
                        <i @click="setOrderByField('trip.data.type')" v-if="orderByField !== 'trip.data.type'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('total_raised')" :class="{'text-primary': orderByField === 'total_raised'}">
                        $ Raised
                        <i @click="setOrderByField('total_raised')" v-if="orderByField !== 'total_raised'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'total_raised'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('percent_raised')" :class="{'text-primary': orderByField === 'percent_raised'}">
                        % Raised
                        <i @click="setOrderByField('percent_raised')" v-if="orderByField !== 'percent_raised'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'percent_raised'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('registered')">
                        Registered On
                        <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('gender')">
                        Gender
                    </th>
                    <th v-if="isActive('status')">
                        Status
                    </th>
                    <th v-if="isActive('age')">
                        Age
                    </th>
                    <th v-if="isActive('email')">
                        Email
                        <i @click="setOrderByField('email')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('requirements')">
                        Requirements
                    </th>
                    <th v-if="isActive('rep')">
                        Trip Rep
                    </th>
                    <th><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody v-if="reservations.length > 0">
                <tr v-for="reservation in orderByProp(reservations, orderByField, direction)">
                    <td v-if="isActive('given_names')" v-text="reservation.given_names"></td>
                    <td v-if="isActive('surname')" v-text="reservation.surname"></td>
                    <td v-if="isActive('desired_role')" v-text="reservation.desired_role.name"></td>
                    <td v-if="isActive('group')">{{reservation.trip.data.group.data.name|capitalize}}</td>
                    <td v-if="isActive('campaign')">{{reservation.trip.data.campaign.data.name|capitalize}}</td>
                    <td v-if="isActive('type')">{{reservation.trip.data.type|capitalize}}</td>
                    <td v-if="isActive('total_raised')">{{currency(reservation.total_raised)}}</td>
                    <td v-if="isActive('percent_raised')">{{reservation.percent_raised.toFixed(2)}}%</td>
                    <td v-if="isActive('registered')">{{reservation.created_at|moment('ll')}}</td>
                    <td v-if="isActive('gender')">{{reservation.gender|capitalize}}</td>
                    <td v-if="isActive('status')">{{reservation.status|capitalize}}</td>
                    <td v-if="isActive('age')" v-text="age(reservation.birthday)"></td>
                    <td v-if="isActive('email')">{{reservation.user.data.email|capitalize}}</td>
                    <td v-if="isActive('requirements')">
                        <div style="position:relative;">
                            <popover effect="fade" trigger="hover" placement="top" title="Complete" :content="complete(reservation).join('<br>')">
                                <a :href="'/admin/reservations/' +  reservation.id  + '/requirements'"><span class="label label-success">{{ complete(reservation).length }}</span></a>
                            </popover>
                            <popover effect="fade" trigger="hover" placement="top" title="Needs Attention" :content="attention(reservation).join('<br>')">
                                <a :href="'/admin/reservations/' +  reservation.id  + '/requirements'"><span class="label label-info">{{ attention(reservation).length }}</span></a>
                            </popover>
                            <popover effect="fade" trigger="hover" placement="top" title="Under Review" :content="reviewing(reservation).join('<br>')">
                                <a :href="'/admin/reservations/' +  reservation.id  + '/requirements'"><span class="label label-default">{{ reviewing(reservation).length }}</span></a>
                            </popover>
                            <popover effect="fade" trigger="hover" placement="top" title="Incomplete" :content="getIncomplete(reservation).join('<br>')">
                                <a :href="'/admin/reservations/' +  reservation.id  + '/requirements'"><span class="label label-danger" v-text="getIncomplete(reservation).length"></span></a>
                            </popover>
                        </div>
                    </td>
                    <td v-if="isActive('rep')">{{reservation.rep.data.name|capitalize}}</td>
                    <td><a :href="`/admin/reservations/${ reservation.id }`"><i class="fa fa-cog"></i></a></td>
                </tr>
                </tbody>
                <tbody v-else>
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        Sign missionaries up for trips and view their reservations here!
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="10" class="text-center">
                        <pagination :pagination="pagination" pagination-key="pagination"
                                    :callback="searchReservations"
                                    size="small">
                        </pagination>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<style>
    #toggleFilters li {
        margin-bottom: 3px;
    }

    @media (min-width: 991px) {
        .aside.left {
            left: 55px;
        }
    }
</style>
<script type="text/javascript">
    import vSelect from "vue-select";
    import reservationsFilters from '../filters/reservations-filters.vue';
    import filtersIndicator from '../filters/filters-indicator.vue';
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'admin-trip-reservations',
        components: {vSelect, exportUtility, reservationsFilters, filtersIndicator},
        props: {
            tripId: {
                type: String,
                default: null
            },
            storageName: {
                type: String,
                default: 'AdminTripReservationsListConfig'
            },
            type: {
                type: String,
                default: 'current'
            }
        },
        data(){
            return {
                reservations: [],
                orderByField: 'surname',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {current_page: 1},
                search: '',
                activeFields: ['given_names', 'surname', 'group', 'campaign', 'type', 'percent_raised'],
                maxActiveFields: 6,
                maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],

                // filter vars
                filters: {
                    tags: [],
                    user: [],
                    groups: [],
                    campaign: '',
                    gender: '',
                    status: '',
                    shirtSize: [],
                    hasCompanions: null,
                    due: '',
                    todoName: '',
                    todoStatus: null,
                    requirementName: '',
                    requirementStatus: '',
                    dueName: '',
                    dueStatus: '',
                    rep: '',
	                age: [0, 120]
                },
                showFilters: false,
                exportOptions: {
                    managing_user: 'Managing User',
                    user_email: 'User Email',
                    user_primary_phone: 'User Primary Phone',
                    user_secondary_phone: 'User Secondary Phone',
                    trip_type: 'Trip Type',
                    campaign: 'Campaign',
                    group: 'Group',
                    country_located: 'Country Located',
                    start_date: 'Trip Start Date',
                    end_date: 'Trip End Date',
                    given_names: 'Given Names',
                    surname: 'Surname',
                    gender: 'Gender',
                    marital_status: 'Marital Status',
                    shirt_size: 'Shirt Size',
                    age: 'Age',
                    birthday: 'Birthday',
                    email: 'Email',
                    primary_phone: 'Primary Phone',
                    secondary_phone: 'Secondary Phone',
                    street_address: 'Street Address',
                    city: 'City',
                    state_providence: 'State/Providence',
                    zip_postal: 'Zip/Postal Code',
                    country: 'Country',
                    // payments: 'Payments Due',
                    // applied_costs: 'Applied Costs',
                    // requirements: 'Travel Requirements',
                    percent_raised: 'Percent Raised',
                    amount_raised: 'Amount Raised',
                    outstanding: 'Outstanding',
                    // deadlines: 'Other Deadlines'
                    desired_role: 'Role'
                },
                exportFilters: {}
            }
        },
        computed: {
            'todo'() {
                if (this.filters.todoStatus) {
                    return this.filters.todoName + '|' + this.filters.todoStatus;
                } else {
                    return this.filters.todoName;
                }
            },
            'requirement'() {
                if (this.filters.requirementStatus)
                    return this.filters.requirementName + '|' + this.filters.requirementStatus;

                return this.filters.requirementName;
            },
            'due'() {
                if (this.filters.dueStatus)
                    return this.filters.dueName + '|' + this.filters.dueStatus;

                return this.filters.dueName;
            }
            // 'rep': function() {
            // 	return this.reservation.rep.data.name;
            // 	// if (this.reservation.rep)
            // 	// 	return this.reservation.rep.data.name;

            // 	// return 'none';
            // }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.searchReservations();
                },
                deep: true
            },
            'reservations'(val, oldVal) {
                if (val.length) {
                    // use object/dictionary instead of array
                    let arr = {};
                    for (let index in val) {
                        // duplicate rep ids will be overwritten
                        if(val[index].rep)
                            arr[val[index].rep.data.id] = val[index].rep.data;
                    }
                    this.repOptions = arr;
                }
            },
            'tagsString'(val, oldVal) {
                let tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchReservations();
            },
            'direction'(val, oldVal) {
                this.searchReservations();
            },
            'activeFields'(val, oldVal) {
                // if the orderBy field is removed from view
                if (!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                this.updateConfig();
            },
            'search'(val, oldVal) {
                this.page = 1;
                this.pagination.current_page = 1;
                this.searchReservations();
            },
            'page'(val, oldVal) {
                this.searchReservations();
            },
            'per_page'(val, oldVal) {
                this.searchReservations();
            },
            /*'groups':() =>  {
             this.searchReservations();
             }*/
        },
        methods: {
            getIncomplete(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'incomplete'}), function(req) {
                    return '&middot; ' + req.name;
                });
            },
            complete(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'complete'}), function(req) {
                    return '&middot; ' + req.name;
                });
            },
            attention(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'attention'}), function(req) {
                    return '&middot; ' + req.name;
                });
            },
            reviewing(reservation) {
                return _.map(_.where(reservation.requirements.data, {status: 'reviewing'}), function(req) {
                    return '&middot; ' + req.name;
                });
            },
            consoleCallback (val) {
                console.dir(JSON.stringify(val))
            },
            updateConfig(){
                localStorage[this.storageName] = JSON.stringify({
                    activeFields: this.activeFields,
                    maxActiveFields: this.maxActiveFields,
                    per_page: this.per_page,
                    ageMin: this.ageMin,
                    ageMax: this.ageMax,
                    groupsArr: this.groupsArr,
                    tagsArr: this.tagsArr,
                    usersArr: this.usersArr,
                    campaignObj: this.campaignObj,
                    filters: {
                        tags: this.filters.tags,
                        user: this.filters.user,
                        groups: this.filters.groups,
                        campaign: this.filters.campaign,
                        gender: this.filters.gender,
                        status: this.filters.status,
                        shirtSize: this.filters.shirtSize,
                        hasCompanions: this.filters.hasCompanions,
                        todoName: this.filters.todoName,
                        todoStatus: this.filters.todoStatus,
                        requirementName: this.filters.requirementName,
                        requirementStatus: this.filters.requirementStatus,
                        dueName: this.filters.dueName,
                        dueStatus: this.filters.dueStatus,
                        rep: this.filters.rep,
                        age: this.filters.age,
                    }
                });

            },
            isActive(field){
                return _.contains(this.activeFields, field);
            },
            maxCheck(field){
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
            setOrderByField(field){
                this.orderByField = field;
                this.direction = 1;
                this.searchReservations();
            },
            resetFilter(){
                this.orderByField = 'surname';
                this.direction = 1;
                this.search = null;
                this.ageMin = 0;
                this.ageMax = 120;
                this.groupsArr = [];
                this.usersArr = [];
                this.campaignObj = null;
                this.filters = {
                    tags: [],
                    user: [],
                    groups: [],
                    campaign: '',
                    gender: '',
                    status: '',
                    shirtSize: [],
                    hasCompanions: null,
                    todoName: '',
                    todoStatus: null,
                    requirementName: '',
                    requirementStatus: '',
                    rep: '',
                    dueName: '',
                    dueStatus: '',
	                age: [0, 120]
                }


            },
            country(code){
                return code;
            },
            age(birthday){
                return moment().diff(birthday, 'years')
            },
            getListSettings(){
                let params = {
                    trip_id: this.tripId ? new Array(this.tripId) : undefined,
                    include: 'trip.campaign,trip.group,costs.payments,user,requirements,rep',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
                };

                switch (this.type) {
                    case 'current':
                        params.current = true;
                        break;
                    case 'archived':
                        params.archived = true;
                        break;
                    case 'dropped':
                        params.dropped = true;
                        break;
                }


                $.extend(params, this.filters);
                $.extend(params, {
                    //age: [this.ageMin, this.ageMax],
                    todo: this.todo,
                    requirement: this.requirement,
                    due: this.due
                });

                this.exportFilters = params;

                return params;
            },
            searchReservations(){
                let params = this.getListSettings();
                // this.$refs.spinner.show();
                this.$http.get('reservations', {params: params}).then((response) => {
                    let self = this;
                    _.each(response.data.data, (reservation) => {
                        reservation.percent_raised = reservation.total_raised / reservation.total_cost * 100
                    }, this);
                    this.reservations = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }).then(() => {
                    this.updateConfig();
                    // this.$refs.spinner.hide();
                })
            },
        },
        mounted(){
            // load view state
            if (localStorage[this.storageName]) {
                let config = JSON.parse(localStorage[this.storageName]);
                this.activeFields = config.activeFields;
                this.maxActiveFields = config.maxActiveFields;
                this.filters = _.extend(this.filters, config.filters);
            }

            // assign values from url search
            if (window.location.search !== '') {
                _.each(location.search.substr(1).split('&'), function (search) {
                    let arr = search.split('=');
                    switch (arr[0]) {
                        case 'campaign':
                            this.$http.get('campaigns/' + arr[1]).then((response) => {
                                this.campaignObj = response.data.data;
                            });
                        // this.campaignObj = _.findWhere(this.campaignOptions, {id: arr[1]})
                    }
                });
            }

            this.searchReservations();

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
<style type="text/scss">
    .popover {
        width: 200px !important;
        min-width: 200px !important;
        max-width: 400px !important;
    }
</style>
