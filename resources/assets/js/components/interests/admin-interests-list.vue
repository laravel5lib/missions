<template>
    <div>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
                              :value="groupObj" :options="groupsOptions" label="name"
                              placeholder="Filter Group"></v-select>
                </div>
                <div class="form-group">
                    <select id="type" class="form-control input-sm" v-model="filters.status" >
                        <option value="">Any Status</option>
                        <option value="undecided">Undecided</option>
                        <option value="converted">Converted</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
                <div class="form-group">
                    <select id="type" class="form-control input-sm" v-model="filters.trip_type" >
                        <option value="">Any Trip Type</option>
                        <option value="full">Full</option>
                        <option value="media">Media</option>
                        <option value="medical">Medical</option>
                        <option value="short">Short</option>
                    </select>
                </div>
                <div class="form-group" v-if="!tripId">
                    <v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
                              :value="campaignObj" :options="campaignOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </mm-aside>

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
                                    <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')"> Name
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="email" :disabled="maxCheck('email')"> Email
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="status" :disabled="maxCheck('status')"> Status
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="phone" :disabled="maxCheck('phone')"> Phone
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="campaign" :disabled="maxCheck('campaign')"> Campaign
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Trip Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="group" :disabled="maxCheck('group')"> Group
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="created_at" :disabled="maxCheck('created_at')"> Created
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
                    <button class="btn btn-default btn-sm " type="button" @click="showFilters=!showFilters">
                        Filters
                        <i class="fa fa-filter"></i>
                    </button>
                    <export-utility url="interests/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                </form>
            </div>
        </div>
        <hr class="divider sm">
        <div>
            <label>Active Filters</label>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.group" @click="filters.group = ''" >
                Group
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.status.length" @click="filters.status = ''" >
                Status
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.campaign.length" @click="filters.campaign = ''" >
                Campaign
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.trip_type.length" @click="filters.campaign = ''" >
                Trip Type
                <i class="fa fa-close"></i>
            </span>
        </div>
        <hr class="divider sm">
        <div>
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
                        Name
                        <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('email')" :class="{'text-primary': orderByField === 'email'}">
                        Email
                        <i @click="setOrderByField('type')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('phone')" :class="{'text-primary': orderByField === 'phone'}">
                        Phone
                        <i @click="setOrderByField('phone')" v-if="orderByField !== 'phone'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'phone'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('status')" :class="{'text-primary': orderByField === 'status'}">
                        Status
                        <i @click="setOrderByField('status')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'status'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('campaign')" :class="{'text-primary': orderByField === 'trip.data.campaign.data.name'}">
                        Campaign
                        <i @click="setOrderByField('trip.data.campaign.data.name')" v-if="orderByField !== 'trip.data.campaign.data.name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'trip.data.type'}">
                        Trip Type
                        <i @click="setOrderByField('trip.data.type')" v-if="orderByField !== 'trip.data.type'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('group')" :class="{'text-primary': orderByField === 'trip.data.group.data.name'}">
                        Group
                        <i @click="setOrderByField('trip.data.group.data.name')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('created_at')" :class="{'text-primary': orderByField === 'created_at'}">
                        Created
                        <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody v-if="interests.length > 0">
                <tr v-for="interest in orderByProp(interests, orderByField, direction)">
                    <td v-if="isActive('name')">{{interest.name ? interest.name[0].toUpperCase() + interest.name.slice(1) : ''}}</td>
                    <td v-if="isActive('email')">{{interest.email}}</td>
                    <td v-if="isActive('phone')">{{interest.phone}}</td>
                    <td v-if="isActive('status')">
                    <span class="label"
                          :class="{ 'label-success' : interest.status == 'converted',
                                    'label-default' : interest.status == 'declined',
                                    'label-danger' : interest.status == 'undecided'}">
                        {{interest.status}}
                    </span>
                    </td>
                    <td v-if="isActive('campaign')">{{interest.trip.data.campaign.data.name}}</td>
                    <td v-if="isActive('type')">{{interest.trip.data.type}}</td>
                    <td v-if="isActive('group')">{{interest.trip.data.group.data.name}}</td>
                    <td v-if="isActive('created_at')">{{interest.created_at | moment('lll')}}</td>
                    <td>
                        <a href="/admin/interests/{{ interest.id }}"><i class="fa fa-cog"></i></a>
                    </td>
                </tr>
                </tbody>
                <tbody v-else>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No interests found.
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="col-sm-12 text-center">
                            <pagination :pagination="pagination"
                                        :callback="searchInterests"
                                        size="small">
                            </pagination>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from "../export-utility.vue";
    export default{
        name: 'admin-interests-list',
        components: {vSelect, exportUtility},
        data(){
            return{
                interests: [],
                orderByField: 'name',
                direction: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                activeFields: ['name', 'status', 'campaign', 'group', 'type'],
                maxActiveFields: 7,
                maxActiveFieldsOptions: [2, 3, 4, 5, 6, 7, 8, 9],
                search: '',
                groupObj: null,
                groupsOptions: [],
                campaignObj: null,
                campaignOptions: [],
                tripObj: null,
                tripOptions: [],
                // filter vars
                filters: {
                    group: '',
                    campaign: '',
                    trip: '',
                    trip_type: '',
                    status: ''
                },
                showFilters: false,
                exportOptions: {
                    name: 'Name',
                    email: 'Email',
                    phone: 'Phone',
                    communication_preferences: 'Communication Preferences',
                    status: 'Status',
                    trip_type: 'Trip Type',
                    campaign: 'Campaign',
                    group: 'Group',
                    created_at: 'Created On'
                },
                exportFilters: {}
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.searchInterests();
                },
                deep: true
            },
            'groupObj': function (val) {
                this.filters.group = val ? val.id : '';
            },
            'tripObj': function (val) {
                this.filters.trip = val ? val.id : '';
            },
            'campaignObj': function (val) {
                this.filters.campaign = val ? val.id : '';
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.page = 1;
                this.searchInterests();
            },
            'direction': function (val) {
                this.searchInterests();
            },
            'page': function (val, oldVal) {
                this.searchInterests();
            },
            'per_page': function (val, oldVal) {
                this.searchInterests();
            }
        },

        methods:{
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
                this.search = null;
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { params: { search: search} }).then((response) => {
                    this.groupsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('campaigns', { params: { search: search} }).then((response) => {
                    this.campaignOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getTrips(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('trips', { params: { search: search} }).then((response) => {
                    this.tripOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            searchInterests(){
                let params = {
                    include:'trip.group,trip.campaign',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.pagination.current_page
                };
                this.exportFilters = params;
                $.extend(params, this.filters);

                // this.$refs.spinner.show();
                this.$http.get('interests', { params: params }).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.interests = response.data.data;
                    // this.$refs.spinner.hide();
                })
            }
        },
        mounted(){
            // populate
            // this.$refs.spinner.show();
            this.getGroups();
            this.getCampaigns();
            this.getTrips();
            this.searchInterests();

            //Manually handle dropdown functionality to keep dropdown open until finished
            $('.form-toggle-menu .dropdown-menu').on('click', function(event){
                var events = $._data(document, 'events') || {};
                events = events.click || [];
                for(var i = 0; i < events.length; i++) {
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
