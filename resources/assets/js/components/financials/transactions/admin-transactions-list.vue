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
                    <label>Donor</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="donorFilter" :debounce="250" :on-search="getDonors"
                              :value.sync="donorObj" :options="donorsOptions" label="name"
                              placeholder="Filter by Donor"></v-select>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select class="form-control" v-model="filters.payment">
                        <option value="">All Methods</option>
                        <option value="card">Credit Card</option>
                        <option value="check">Check</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>From Date</label>
                    <date-picker :model.sync="filters.minDate|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                </div>

                <div class="form-group">
                    <label>To Date</label>
                    <date-picker :model.sync="filters.maxDate|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
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
                <hr class="divider inv">
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
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="donor" :disabled="maxCheck('donor')"> Donor
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="class" :disabled="maxCheck('class')"> Class
                                </label>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="item" :disabled="maxCheck('item')"> Item
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="last_four" :disabled="maxCheck('last_four')"> Card Last Four
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="cardholder" :disabled="maxCheck('cardholder')"> Cardholder
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="donor_phone" :disabled="maxCheck('donor_phone')"> Donor Phone
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="donor_email" :disabled="maxCheck('donor_email')"> Donor Email
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="fund_name" :disabled="maxCheck('fund_name')"> Fund Name
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
                    <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                        Filters
                        <span class="caret"></span>
                    </button>
                    <export-utility url="transactions/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
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
            <span style="margin-right:2px;" class="label label-default" v-show="filters.payment" @click="filters.payment = ''" >
                Payment Method
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.minDate" @click="filters.minDate = null" >
                From Date
                <i class="fa fa-close"></i>
            </span>
            <span style="margin-right:2px;" class="label label-default" v-show="filters.maxDate" @click="filters.maxDate = null" >
                To Date
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
        <div style="position: relative">
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <table class="table table-hover table-striped">
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
                    <th v-if="isActive('donor')" :class="{'text-primary': orderByField === 'donor'}">
                        Donor
                        <i @click="setOrderByField('donor')" v-if="orderByField !== 'donor'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'donor'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('class')" :class="{'text-primary': orderByField === 'class'}">
                        Class
                        <i @click="setOrderByField('class')" v-if="orderByField !== 'class'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'class'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('item')" :class="{'text-primary': orderByField === 'item'}">
                        Item
                        <i @click="setOrderByField('item')" v-if="orderByField !== 'item'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'item'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('last_four')" :class="{'text-primary': orderByField === 'last_four'}">
                        Card Last Four
                        <i @click="setOrderByField('last_four')" v-if="orderByField !== 'last_four'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'last_four'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('card_brand')" :class="{'text-primary': orderByField === 'card_brand'}">
                        Card Brand
                        <i @click="setOrderByField('card_brand')" v-if="orderByField !== 'card_brand'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'card_brand'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('cardholder')" :class="{'text-primary': orderByField === 'cardholder'}">
                        Card Holder
                        <i @click="setOrderByField('cardholder')" v-if="orderByField !== 'cardholder'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'cardholder'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('donor_phone')" :class="{'text-primary': orderByField === 'donor_phone'}">
                        Donor phone
                        <i @click="setOrderByField('donor_phone')" v-if="orderByField !== 'donor_phone'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'donor_phone'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('donor_email')" :class="{'text-primary': orderByField === 'donor_email'}">
                        Donor Email
                        <i @click="setOrderByField('donor_email')" v-if="orderByField !== 'donor_email'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'donor_email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('fund_name')" :class="{'text-primary': orderByField === 'fund_name'}">
                        Fund Name
                        <i @click="setOrderByField('fund_name')" v-if="orderByField !== 'fund_name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'fund_name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th v-if="isActive('created_at')" :class="{'text-primary': orderByField === 'created_at'}">
                        Created
                        <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
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
                    <td v-if="isActive('donor')" v-text="transaction.donor.data.name"></td>
                    <td v-if="isActive('class')" v-text="transaction.fund.data.class"></td>
                    <td v-if="isActive('item')" v-text="transaction.fund.data.item"></td>
                    <td v-if="isActive('last_four')" v-text="transaction.details.last_four"></td>
                    <td v-if="isActive('card_brand')" v-text="transaction.details.brand"></td>
                    <td v-if="isActive('cardholder')" v-text="transaction.details.cardholder"></td>
                    <td v-if="isActive('donor_phone')" v-text="transaction.donor.data.phone"></td>
                    <td v-if="isActive('donor_email')" v-text="transaction.donor.data.email"></td>
                    <td v-if="isActive('fund_name')" v-text="transaction.fund.data.name"></td>
                    <td v-if="isActive('created_at')" v-text="transaction.created_at|moment 'lll'"></td>
                    <td><a :href="'/admin/transactions/' + transaction.id"><i class="fa fa-cog"></i></a></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="col-sm-12 text-center">
                            <pagination :pagination.sync="pagination" size="small" :callback="searchTransactions"></pagination>
                        </div>
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
    import exportUtility from '../../export-utility.vue';
    export default{
        name: 'admin-transactions-list',
        components: {vSelect, exportUtility},
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
                orderByField: 'created_at',
                direction: 0,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {
                    current_page: 1
                },
                search: '',
                activeFields: ['description', 'type', 'amount', 'donor', 'created_at'],
                maxActiveFields: 6,
                maxActiveFieldsOptions: [3, 4, 5, 6, 7, 8],
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
                    maxDate: null,
                    minDate: null,
                    payment: null
                },
                showFilters: false,
                exportOptions: {
                    description: 'Description',
                    amount: 'Amount',
                    anonymous: 'Is Anonymous',
                    payment_type: 'Payment Type',
                    transaction_type: 'Transaction Type',
                    date: 'Transaction Date',
                    class: 'QuickBooks Class',
                    item: 'QuickBooks Item',
                    type: 'QuickBooks Type',
                    account: 'QuickBooks Account',
                    fund_name: 'Fund Name',
                    donor_name: 'Donor Name',
                    donor_email: 'Donor Email',
                    donor_phone: 'Donor Phone',
                    donor_address_one: 'Donor Address',
                    donor_address_two: 'Donor City, State & Zip',
                    donor_country: 'Donor Country'
                },
                exportFilters: {},
                showSuccess: false,
                showError: false,
                message: null
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler (val) {
//                    console.log(val);
                    this.pagination.current_page = 1;
                    this.searchTransactions();
                },
                deep: true
            },
            'donorObj' (val) {
                this.filters.donor = val ? val.id : '';
                this.searchTransactions();
            },
            'direction' (val) {
                this.searchTransactions();
            },
            'tagsString' (val) {
                let tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchTransactions();
            },
            'activeFields' (val, oldVal) {
                // if the orderBy field is removed from view
                if(!_.contains(val, this.orderByField) && _.contains(oldVal, this.orderByField)) {
                    // default to first visible field
                    this.orderByField = val[0];
                }
                this.updateConfig();
            },
            'search' (val, oldVal) {
                this.pagination.current_page = 1;
                this.page = 1;
                this.searchTransactions();
            },
            'page' (val, oldVal) {
                this.searchTransactions();
            },
            'per_page' (val, oldVal) {
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
                        minDate: this.filters.minDate,
                        maxDate: this.filters.maxDate,
                        type: this.filters.type,
                    }
                });
//                console.log(JSON.parse(localStorage[this.storageName]));
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
                        maxDate: null,
                        minDate: null,
                    }
                });
            },
            getListSettings(){
                let params = {
                    include: 'donor,fund',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
                };

                $.extend(params, this.filters);
//                params.minDate = moment(params.minDate).format('YYYY-MM-DD HH:mm:ss');
//                params.maxDate = moment(params.maxDate).format('YYYY-MM-DD HH:mm:ss');
                this.exportFilters = params;

                return params;
            },
            getDonors(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('donors', { params: { search: search} }).then(function (response) {
                    this.donorsOptions = response.body.data;
                    loading ? loading(false) : void 0;
                })
            },
            searchTransactions(){
                let params = this.getListSettings();
                // this.$refs.spinner.show();
                this.$http.get('transactions', { params: params }).then(function (response) {
                    this.transactions = response.body.data;
                    this.pagination = response.body.meta.pagination;
                    // this.$refs.spinner.hide();
                }, function (error) {
                    // this.$refs.spinner.hide();
                    // TODO add error alert
                }).then(function () {
                    this.updateConfig();
                });
            }
        },
        events: {
            'refreshTransactions'() {
                this.searchTransactions();
            }
        },
        ready() {
            // load view state
            if (localStorage[this.storageName]) {
                this.filters.minDate = null;
                this.filters.maxDate = null;

                let config = JSON.parse(localStorage[this.storageName]);
                this.activeFields = config.activeFields;
                this.maxActiveFields = config.maxActiveFields;
                this.filters = config.filters;
            }

            // populate
            // this.$refs.spinner.show();
            this.getDonors();
            if(this.fund || this.donor) {
                this.filters.fund = this.fund;
                this.filters.donor = this.donor

            } else {
                this.searchTransactions();
            }

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
<style scoped>
.aside {
    overflow: visible;
}
</style>