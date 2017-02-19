<template>
    <div v-if="display">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <h5 v-if="fund">
                        <a :href="'/admin/funds/' + fund.id" v-if="firstUrlSegment == 'admin'">
                            {{ fund.name }}
                        </a>
                        <span v-else>
                            {{ fund.name }}
                        </span>
                    </h5>
                </div>
                <div class="col-xs-12 col-sm-4 visible-xs">
                    <div class="btn-group btn-group-sm btn-group-justified" role="group" aria-label="...">
                        <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donor'}" @click="toggleView('donor')">Donors</a>
                        <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donation'}" @click="toggleView('donation')">Transactions</a>
                    </div>
                </div>
                <div class="col-xs-12 visible-xs" v-if="activeView !== 'donor'">
                    <form class="form-inline">
                        <div class="form-group">
                            <label>Transaction Type</label>
                            <select class="form-control input-sm" v-model="type">
                                <option value=''>All</option>
                                <option value='donation'>Donation</option>
                                <option value='credit'>Credit</option>
                                <option value='refund'>Refund</option>
                                <option value='transfer'>Transfer</option>
                            </select>
                        </div><!-- form-group -->
                    </form><!-- form-inline -->
                </div>
            </div>
        </div>

        <div class="btn-group btn-group-sm btn-group-justified hidden-xs" role="group" aria-label="...">
            <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donor'}" @click="toggleView('donor')">Donors</a>
            <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donation'}" @click="toggleView('donation')">Donations</a>
            <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'credit'}" @click="toggleView('credit')">Credits</a>
            <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'refund'}" @click="toggleView('refund')">Refunds</a>
            <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'transfer'}" @click="toggleView('transfer')">Transfers</a>
        </div>

        <hr class="divider inv sm">
        <div style="position:relative">
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <template v-if="activeView === 'donor'">
                <div class="list-group">
                    <div class="list-group-item" role="tab" id="heading-{{ donor.id }}" v-for="donor in donors">
                        <h5>
                            {{ donor.name }} <span class="small">donated <span class="text-success">{{donor.total_donated|currency}}</span></span>
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li>
                                    <a>{{ donorPagination.total }} {{ activeView | capitalize }}s</a>
                                </li>
                                <li :class="{ 'disabled': donorPagination.current_page == 1 }">
                                    <a aria-label="Previous" @click="donorPagination.current_page = donorPagination.current_page-1">
                                        <span aria-hidden="true">&laquo; Back</span>
                                    </a>
                                </li>
                                <!--<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>-->
                                <li :class="{ 'disabled': donorPagination.current_page == donorPagination.total_pages }">
                                    <a aria-label="Next" @click="donorPagination.current_page = donorPagination.current_page+1">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </template>
            <template v-if="activeView !== 'donor'">
                <div class="list-group">
                    <div class="list-group-item text-center" v-if="pagination.total === 0">No {{activeView}}s found.</div>
                    <div class="list-group-item" role="tab" id="heading-{{ transaction.id }}" v-for="transaction in transactions">
                        <h5>
                            <span :class="{'text-success': transaction.amount > 0, 'text-danger': transaction.amount < 0}">{{ transaction.amount|currency }}</span> was {{ action }}<br>
                            <small v-if="contains(['donation'], transaction.type)" class="small">by 
                            <span v-if="!transaction.anonymous">{{ transaction.donor.data.name }}</span>
                            <span v-else>an anonymous donor</span>
                             on {{ transaction.created_at|moment 'll'}}</small>
                            <br />
                            <small v-if="transaction.details">
                                <span v-if="transaction.details.comment">{{ transaction.details.comment }} <br></span>
                                <span v-if="transaction.details.reason">{{ transaction.details.reason }} <br></span>
                            </small>
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li>
                                    <a>{{ pagination.total }} {{ activeView | capitalize }}s</a>
                                </li>
                                <li :class="{ 'disabled': pagination.current_page == 1 }">
                                    <a aria-label="Previous" @click="pagination.current_page = pagination.current_page-1">
                                        <span aria-hidden="true">&laquo; Back</span>
                                    </a>
                                </li>
                                <!--<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>-->
                                <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                                    <a aria-label="Next" @click="pagination.current_page = pagination.current_page+1">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name: 'funding',
        props: ['fundId'],
        data(){
            return {
                fund: null,
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
            action: function () {
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
            'donorPagination.current_page': function (val, oldVal) {
                this.searchDonors();
            },
            'pagination.current_page': function (val, oldVal) {
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
                // this.$refs.spinner.show();
                this.$http.get('funds/'+ this.fundId +'/donors', {page: this.donorPagination.current_page}).then(function (response) {
                    this.donors = _.toArray(response.data.data);
                    this.donorPagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                });
            },
            searchTransactions(type){
                // this.$refs.spinner.show();
                this.$http.get('transactions', {include: 'donor', type: type, fund: this.fundId, page: this.pagination.current_page, per_page: this.per_page}).then(function (response) {
                    this.transactions = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                });
            }
        },
        ready(){
            this.$http.get('funds/' + this.fundId).then(function (response) {
                this.fund = response.data.data;
            });


            if (this.display) {
                this.searchDonors();
            }
        }
    }
</script>
