<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" v-model="filters.type">
                        <option value="">All Types</option>
                        <option value="donation">Donation</option>
                        <option value="transfer">Transfer</option>
                        <option value="refund">Refund</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
                <div class="form-group" v-if="!donor">
                    <v-select class="form-control" id="donorFilter" :debounce="250" :on-search="getDonors"
                              :value.sync="donorObj" :options="donorsOptions" label="name"
                              placeholder="Filter by Donor"></v-select>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Amount</label>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">Min</span>
                                <input type="number" class="form-control" v-model="filters.minAmount"/>
                            </div>
                            <br>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">Max</span>
                                <input type="number" class="form-control" v-model="filters.maxAmount"/>
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
                                    <input type="checkbox" v-model="activeFields" value="description" :disabled="maxCheck('description')"> Description
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="amount" :disabled="maxCheck('amount')"> Amount
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
                    <button class="btn btn-default btn-sm" type="button" @click="showExportModal=true">
                        Export
                        <span class="fa fa-download"></span>
                    </button>
                    <!--<a class="btn btn-primary btn-sm" href="transactions/create">New <i class="fa fa-plus"></i> </a>-->
                </form>
            </div>
        </div>
        <hr class="divider sm">
        <div>
            <label>Active Filters</label>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.donor" @click="filters.donor = ''" >
                Donor
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.minAmount" @click="filters.minAmount = ''" >
                Min Amount
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.maxAmount" @click="filters.maxAmount = ''" >
                Max Amount
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
                <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('description')" :class="{'text-primary': orderByField === 'description'}">
                    Description
                    <i @click="setOrderByField('description')" v-if="orderByField !== 'description'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'description'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>

                <th v-if="isActive('amount')" :class="{'text-primary': orderByField === 'amount'}">
                    Amount
                    <i @click="setOrderByField('amount')" v-if="orderByField !== 'amount'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'amount'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="transaction in transactions|filterBy search|orderBy orderByField direction">
                <td v-if="isActive('type')">
                    <span class="label label-default" v-text="transaction.type|capitalize"></span>
                </td>
                <td v-if="isActive('description')" v-text="transaction.description"></td>
                <td v-if="isActive('amount')">
                    <span v-text="transaction.amount|currency" :class="{'text-success': transaction.amount > 0, 'text-danger': transaction.amount < 0}"></span>
                </td>
                <td><a href="/admin/transactions/{{ transaction.id }}"><i class="fa fa-cog"></i></a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination.sync="pagination" :callback="searchTransactions"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
        <modal title="Export Transactions List" :show.sync="showExportModal" effect="zoom" width="400" ok-text="Export" :callback="exportList">
            <div slot="modal-body" class="modal-body">
                <ul class="list-unstyled">
                    <li>
                        <label class="small" style="margin-bottom: 0px;">
                            <input type="checkbox" v-model="exportSettings.fields" value="description"> Description
                        </label>
                    </li>
                    <li>
                        <label class="small" style="margin-bottom: 0px;">
                            <input type="checkbox" v-model="exportSettings.fields" value="type"> Type
                        </label>
                    </li>
                    <li>
                        <label class="small" style="margin-bottom: 0px;">
                            <input type="checkbox" v-model="exportSettings.fields" value="amount"> Amount
                        </label>
                    </li>
                </ul>
            </div>
        </modal>
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
    export default{
        name: 'admin-transactions-list',
        components: {vSelect},
        props:{
            fund: {
                type: String,
                default: null
            },
            donor: {
                type: String,
                default: null
            },
            storageName: {
                type: String,
                default: 'AdminTransactionsListConfig'
            },
        },
        data(){
            return {
                transactions: [],
                orderByField: 'description',
                direction: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {
                    current_page: 1
                },
                search: '',
                activeFields: ['description', 'type', 'amount'],
                maxActiveFields: 3,

                // filter vars
                donorsOptions: [],
                donorObj: null,
                filters: {
                    tags: [],
                    fund:'',
                    donor: '',
                    minAmount: null,
                    maxAmount: null,
                    type: null,
                },
                showFilters: false,
                showExportModal: false,
                exportSettings: {
                    fields: [],
                }

            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    console.log(val);
                    this.searchTransactions();
                },
                deep: true
            },
            'donorObj': function (val) {
                this.filters.donor = val ? val.id : '';
                this.searchTransactions();
            },
            'direction': function (val) {
                this.searchTransactions();
            },
            'tagsString': function (val) {
                var tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchTransactions();
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
                this.searchTransactions();
            },
            'page': function (val, oldVal) {
                this.searchTransactions();
            },
            'per_page': function (val, oldVal) {
                this.searchTransactions();
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
                        fund: this.filters.fund,
                        donor: this.filters.donor,
                        minAmount: this.filters.minAmount,
                        maxAmount: this.filters.maxAmount,
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
                this.searchTransactions();
            },
            resetFilter(){
                $.extend(this, {
                    orderByField: 'description',
                    direction: 1,
                    per_page: 10,
                    perPageOptions: [5, 10, 25, 50, 100],
                    pagination: {
                        current_page: 1
                    },
                    search: '',
                    activeFields: ['description', 'type', 'amount'],
                    maxActiveFields: 8,
                    filters: {
                        tags: [],
                        fund:'',
                        donor: '',
                        minAmount: null,
                        maxAmount: null,
                        type: '',
                    }
                });
            },
            getListSettings(){
                var params = {
                    include: '',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
                };


                $.extend(params, this.filters);

                return params;
            },
            getDonors(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('donors', { search: search}).then(function (response) {
                    this.donorsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            searchTransactions(){
                var params = this.getListSettings();
                this.$http.get('transactions', params).then(function (response) {
                    var self = this;
                    this.transactions = response.data.data;
                    this.pagination = response.data.meta.pagination;
                }).then(function () {
                    this.updateConfig();
                });
            },
            exportList(){
                var params = this.getListSettings();
                $.extend(params, this.exportSettings);
                // Send to api route

                this.$http.post('transactions/export', params).then(function (response) {
                    console.log(response);
                }, function (error) {
                    console.log(error);
                })
            }
        },
        ready() {
            // load view state
            if (localStorage[this.storageName]) {
                var config = JSON.parse(localStorage[this.storageName]);
                this.filters = config.filters;
            }

            // populate
            this.getDonors();
            if(this.fund || this.donor) {
                this.filters.fund = this.fund;
                this.filters.donor = this.donor

            } else {
                this.searchTransactions();
            }

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