<template>
<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                Donations <br />
                <small v-cloak>
                    <span class="text-success">{{ '$' + fund.balance }}</span> 
                    <template v-if="fundraiser"> of {{ '$' + goalAmount }} raised</template>
                </small>
            </h3>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills">
                <li role="presentation" :class="{'active': activeView === 'donor'}">
                    <a type="button" @click="toggleView('donor')">Donors</a>
                </li>
                <li role="presentation" :class="{'active': activeView === 'transaction'}">
                    <a type="button" @click="toggleView('transaction')">Transactions</a>
                </li>
            </ul>
        </div>
        <template v-if="activeView === 'donor'">
            <table class="table table-hover table-responsive" v-if="donors.length">
                <thead>
                    <tr>
                        <th class="col-sm-4">Donor</th>
                        <th class="col-sm-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="donor in donors">
                        <td class="col-sm-4">{{ donor.name }}</td>
                        <td class="col-sm-2 text-right text-success">{{ currency(donor.total_donated) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="panel-body text-center" v-else>
                <p>You haven't recieved any donations yet.</p>
            </div>
        </template>
        <template v-if="activeView !== 'donor'">
            <table class="table table-hover table-responsive" v-if="transactions.length">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactions">
                        <td class="col-sm-3 col-xs-4">
                            {{ transaction.created_at|moment('ll')}}
                        </td>
                        <td class="col-sm-6 col-xs-4">
                            <h5 style="margin-top: 0">{{ transaction.type.toUpperCase() }}
                            <small v-if="contains(['donation'], transaction.type)" class="small">
                                from
                                <span v-if="!transaction.anonymous">{{ transaction.donor.data.name }}</span>
                                <span v-else>an anonymous donor</span>
                            </small>
                            </h5>
                            <p class="small" v-if="transaction.details">
                                <em>
                                <span v-if="transaction.details.comment">{{ transaction.details.comment }}</span>
                                <span v-if="transaction.details.reason">{{ transaction.details.reason }}</span>
                                </em>
                            </p>
                        </td>
                        <td class="col-sm-3 col-xs-4 text-right">
                            <span :class="{'text-success': transaction.amount > 0, 'text-danger': transaction.amount < 0}">
                                {{ currency(transaction.amount) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="panel-body text-center" v-else>
                <p>No transactions were found.</p>
            </div>
        </template>
        <div class="panel-footer text-right" v-if="fund && firstUrlSegment === 'admin'">
            <a :href="'/admin/funds/' + fund.id" class="btn btn-link">
               Go to Fund
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <nav>
                <ul class="pagination pagination-sm">
                    <li>
                        <a>{{ donorPagination.total }} {{ activeView|capitalize }}s</a>
                    </li>
                    <li :class="{ 'disabled': donorPagination.current_page == 1 }">
                        <a aria-label="Previous" @click="donorPaginateBack">
                            <span aria-hidden="true">&laquo; Back</span>
                        </a>
                    </li>
                    <li :class="{ 'disabled': donorPagination.current_page === donorPagination.total_pages && donorPagination.total_pages > donorPagination.current_page+1 }">
                        <a aria-label="Next" @click="donorPaginateNext">
                            <span aria-hidden="true">Next &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name: 'funding',
        props: {
            fundraiser: {
                type: Object,
                default: null
            },
            fundId: {
                type: String,
                default: null
            }
        },
        data(){
            return {
                fund: {},
                donors: [],
                transactions: [],
                type: '',
                display: true,
                activeView: 'donor',

                // pagination vars
                page: 1,
                per_page: 10,
                donorPagination: {current_page: 1},
                pagination: {current_page: 1},
            }
        },
        filters: {
            marked: marked,
        },
        computed:{
            id: function() {
                return this.fundraiser ? this.fundraiser.fund_id : this.fundId;
            },
            goalAmount: function() {
                if (this.fundraiser) {
                    return (this.fundraiser.goal_amount/100).toFixed(2)
                }
            },
            action: () =>  {
                switch (this.type) {
                    case 'donation':
                    default:
                        return 'donated';
                    case 'transfer':
                        return 'transferred';
                    case 'credit':
                        return 'recieved';
                    case 'refund':
                        return 'refunded';
                }
            }
        },
        watch: {
            'donorPagination.current_page'(val, oldVal) {
                this.searchDonors();
            },
            'pagination.current_page'(val, oldVal) {
                this.searchTransactions(this.type);
            },
        },
        methods: {
            contains(list, type){
                return _.contains(list, type)
            },
            toggleView(view){
                this.activeView = this.type = view;
                if (view != 'donor') {
                    this.type = view;
                    this.searchTransactions(view);
                }
            },
            searchDonors(){
                this.$http.get('funds/'+ this.id +'/donors', { params: {page: this.donorPagination.current_page} }).then((response) => {
                    this.donors = _.toArray(response.data.data);
                    this.donorPagination = response.data.meta.pagination;
                });
            },
            searchTransactions(type){
                this.$http.get('transactions', { params: {include: 'donor', fund: this.id, page: this.pagination.current_page, per_page: this.per_page} }).then((response) => {
                    this.transactions = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            donorPaginateBack() {
                if (this.donorPagination.current_page !== 1) {
                    this.donorPagination.current_page -= 1;
                }
            },
            donorPaginateNext() {
                if (this.donorPagination.current_page !== this.donorPagination.total_pages && this.donorPagination.total_pages > this.donorPagination.current_page+1) {
                    this.donorPagination.current_page += 1;
                }
            },
            paginateBack() {
                if (this.pagination.current_page !== 1) {
                    this.pagination.current_page -= 1;
                }
            },
            paginateNext() {
                if (this.pagination.current_page !== this.pagination.total_pages && this.pagination.total_pages > this.pagination.current_page+1) {
                    this.pagination.current_page += 1;
                }
            },
        },
        mounted(){
            this.$http.get('funds/' + this.id).then((response) => {
                this.fund = response.data.data;
            });


            if (this.display) {
                this.searchDonors();
            }
        }
    }
</script>
