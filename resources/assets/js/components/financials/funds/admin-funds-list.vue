<template>
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" v-model="filters.type">
                        <option value="">All Types</option>
                        <option value="campaign">Campaign</option>
                        <option value="group">Group</option>
                        <option value="trip">Trip</option>
                        <option value="reservation">Reservation</option>
                        <option value="project_cause">Project Cause</option>
                        <option value="project">Project</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Balance</label>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">Min</span>
                                <input type="number" class="form-control" v-model="filters.minBalance"/>
                            </div>
                            <br>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">Max</span>
                                <input type="number" class="form-control" v-model="filters.maxBalance"/>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>

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
                                    <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')"> Name
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="item" :disabled="maxCheck('item')"> Item
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="class" :disabled="maxCheck('class')"> Class
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="balance" :disabled="maxCheck('balance')"> Balance
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
                        <span class="caret"></span>
                    </button>
                    <export-utility url="funds/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                </form>
            </div>
        </div>
        <hr class="divider sm">
        <div>
            <label>Active Filters</label>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.minBalance" @click="filters.minBalance = ''" >
                Min Balance
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.maxBalance" @click="filters.maxBalance = ''" >
                Max Balance
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.type && filters.type.length" @click="filters.type = ''" >
                Type
                <i class="fa fa-close"></i>
            </span>
        </div>
        <hr class="divider sm">
        <table class="table table-hover">
            <thead>
            <tr>
                <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
                    Name
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('item')" :class="{'text-primary': orderByField === 'item'}">
                    Item
                    <i @click="setOrderByField('item')" v-if="orderByField !== 'item'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'item'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('class')" :class="{'text-primary': orderByField === 'class'}">
                    Class
                    <i @click="setOrderByField('class')" v-if="orderByField !== 'class'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'class'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('balance')" :class="{'text-primary': orderByField === 'balance'}">
                    Balance
                    <i @click="setOrderByField('balance')" v-if="orderByField !== 'balance'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'balance'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="fund in funds|filterBy search|orderBy orderByField direction">
                <td v-if="isActive('name')" v-text="fund.name"></td>
                <td v-if="isActive('type')">
                    <span class="label label-default" v-text="fund.type|capitalize"></span>
                </td>
                <td v-if="isActive('item')">
                    {{ fund.item|capitalize }}
                </td>
                <td v-if="isActive('class')">
                    {{ fund.class|capitalize }}
                </td>
                <td v-if="isActive('balance')">
                    <span v-text="fund.balance|currency" :class="{'text-success': fund.balance > 0, 'text-danger': fund.balance < 0}"></span>
                </td>
                <td><a href="/admin/funds/{{ fund.id }}"><i class="fa fa-cog"></i></a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination.sync="pagination" size="small" :callback="searchFunds"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
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
    import exportUtility from '../../export-utility.vue';
    export default{
        name: 'admin-funds-list',
        components: {vSelect, exportUtility},
        props:{
            storageName: {
                type: String,
                default: 'AdminFundsListConfig'
            },
        },
        data(){
            return {
                funds: [],
                orderByField: 'name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {
                    current_page: 1
                },
                search: '',
                activeFields: ['name', 'type', 'balance'],
                maxActiveFields: 3,

                // filter vars
                filters: {
                    tags: [],
                    minBalance: null,
                    maxBalance: null,
                    type: null,
                },
                showFilters: false,
                exportOptions: {
                    name: 'Name',
                    type: 'Type',
                    balance: 'Balance',
                    class: 'Account Class',
                    item: 'Account Item',
                    created: 'Created On'
                },
                exportFilters: {}
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    console.log(val);
                    this.searchFunds();
                },
                deep: true
            },
            'direction': function (val) {
                this.searchFunds();
            },
            'tagsString': function (val) {
                let tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchFunds();
            },
            'activeFields': function (val, oldVal) {
                // if the orderBy field is removed from view
                if(!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                this.updateConfig();
            },
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchFunds();
            },
            'per_page': function (val, oldVal) {
                this.searchFunds();
            },

        },
        methods: {
            updateConfig(){
                localStorage[this.storageName] = JSON.stringify({
                    activeFields: this.activeFields,
                    maxActiveFields: this.maxActiveFields,
                    per_page: this.per_page,
                    filters: {
                        tags: this.filters.tags,
                        minBalance: this.filters.minBalance,
                        maxBalance: this.filters.maxBalance,
                        type: this.filters.type,
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
                this.searchFunds();
            },
            resetFilter(){
                $.extend(this, {
                    orderByField: 'name',
                    direction: 1,
                    per_page: 10,
                    perPageOptions: [5, 10, 25, 50, 100],
                    pagination: {
                        current_page: 1
                    },
                    search: '',
                    activeFields: ['name', 'type', 'balance'],
                    maxActiveFields: 8,
                    filters: {
                        tags: [],
                        minBalance: null,
                        maxBalance: null,
                        type: '',
                    }
                });
            },
            getListSettings(){
                let params = {
                    include: '',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
                };


                $.extend(params, this.filters);

                this.exportFilters = params;

                return params;
            },
            searchFunds(){
                let params = this.getListSettings();
                this.$refs.spinner.show();
                this.$http.get('funds', params).then(function (response) {
                    let self = this;
                    this.funds = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.$refs.spinner.hide();
                }, function (error) {
                    this.$refs.spinner.hide();
                    //TODO add error alert
                }).then(function () {
                    this.updateConfig();
                });
            }
        },
        ready() {
            // load view state
            if (localStorage[this.storageName]) {
                let config = JSON.parse(localStorage[this.storageName]);
                this.filters = config.filters;
            }
            // populate
            this.searchFunds();

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