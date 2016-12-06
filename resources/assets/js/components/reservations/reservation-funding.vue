<!--<template>
    <div class="panel panel-default panel-primary" v-if="reservation">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>{{ reservation.fund.data.name }}</h5>
                </div>
                <div class="col-xs-4 text-right">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>Transaction Type</label>
                            <select class="form-control input-sm" v-model="type">
                                <option value=''>All</option>
                                <option value='donation'>Donation</option>
                                <option value='fee'>Fee</option>
                                <option value='payment'>Payment</option>
                                <option value='refund'>Refund</option>
                                <option value='transfer'>Transfer</option>
                            </select>
                        </div>&lt;!&ndash; form-group &ndash;&gt;
                    </div>&lt;!&ndash; form-inline &ndash;&gt;
                </div>
            </div>
        </div>
        <ul class="list-group">
            <li class="list-group-item" v-for="transaction in transactions|filterBy type">
                <hr class="divider inv sm">
                <h3 class="list-group-item-heading" :class="transaction.amount > 0 ? 'text-success' : 'text-danger'">{{ transaction.amount|currency }}</h3>
                <p class="text-muted small">{{ transaction.description }}<p>
                <template v-if="transaction.comment">
                    <hr class="divider inv sm"/>
                    <div class="well">
                        &lt;!&ndash;<div class="list-group-item-text">
                            <p class="small"><b>Description:</b> </p>
                        </div>&ndash;&gt;
                            &lt;!&ndash;<hr class="divider">&ndash;&gt;
                        <div class="list-group-item-text">
                            <p class="small"><b>Comment:</b> {{ transaction.comment }}</p>
                        </div>
                    </div>
                </template>
            </li>
        </ul>
    </div>
</template>-->
<template>
    <div v-if="display">
        <div class="panel-heading visible-xs">
            <div class="row">
                <div class="col-xs-8">
                    <h5 v-if="fund">{{ fund.name }}</h5>
                </div>
                <div class="col-xs-4 text-right">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>Transaction Type</label>
                            <select class="form-control input-sm" v-model="type">
                                <option value=''>All</option>
                                <option value='donation'>Donation</option>
                                <option value='fee'>Fee</option>
                                <option value='payment'>Payment</option>
                                <option value='refund'>Refund</option>
                                <option value='transfer'>Transfer</option>
                            </select>
                        </div><!-- form-group -->
                    </div><!-- form-inline -->
                </div>
            </div>
        </div>

        <div class="btn-group btn-group-sm btn-group-justified hidden-xs" role="group" aria-label="...">
            <!--<div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donors'}" @click="toggleView('donors')">Donors</button>
            </div>-->
            <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donation'}" @click="toggleView('donation')">Donations</a>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'fee'}" @click="toggleView('fee')">Fees</a>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'payment'}" @click="toggleView('payment')">Payment</a>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'refund'}" @click="toggleView('refund')">Refunds</a>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'transfer'}" @click="toggleView('transfer')">Transfers</a>
            </div>
        </div>

        <hr class="divider inv sm">
        <!--<template v-if="activeView==='donors'">
            <div class="panel panel-default" v-for="donor in donors">
                <div class="panel-heading" role="tab" id="heading-{{ donor.id }}">
                    <h5>
                        <a role="button">
                            {{ donor.name }} <span class="small">{{donor.total_donated|currency}}</span>
                        </a>
                    </h5>
                </div>
            </div>
        </template>-->

        <!--<template v-if="activeView === 'donations'">-->
            <div class="panel panel-default" v-for="transaction in transactions|filterBy type">
                <div class="panel-heading" role="tab" id="heading-{{ transaction.id }}">
                    <h5>
                        <span class="text-success">{{ transaction.amount|currency }}</span> was {{ action }}<br>
                        <small v-if="contains(['donation'], transaction.type)" class="small">by <a :href="'@' + transaction.donor.data.account_url">{{ transaction.donor.data.name || 'Anonymous' }}</a> on {{ transaction.created_at|moment 'll'}}</small>
                        <br />
                        <small v-if="transaction.details">{{ transaction.details.comment }}</small>
                    </h5>
                </div>
            </div>
        <!--</template>-->


        <div class="row">
            <div class="col-sm-12 text-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li>
                            <a>{{ pagination.total }} {{ activeView | capitalize }}s</a>
                        </li>
                        <li :class="{ 'disabled': pagination.current_page == 1 }">
                            <a aria-label="Previous" @click="page=pagination.current_page-1">
                                <span aria-hidden="true">&laquo; Back</span>
                            </a>
                        </li>
                        <!--<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>-->
                        <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                            <a aria-label="Next" @click="page=pagination.current_page+1">
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
        name: 'reservation-funding',
        props: ['id'],
        data(){
            return {
                fund: null,
                transactions: null,
                type: '',
                display: true,
                // donors: [],
                // donations: [],
                activeView: 'donation',

                // pagination vars
                page: 1,
                per_page: 10,
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
                    case 'payment':
                        return 'paid';
                    case 'fee':
                        return 'charged';
                    case 'refund':
                        return 'refunded';
                }
            }
        },
        watch: {
            'page': function (val, oldVal) {
                this.searchDonors();
            },
        },
        methods: {
            contains(list, type){
                return _.contains(list, type)
            },
            toggleView(view){
                this.activeView = this.type = view;

//                this.activeView = this.activeView === 'donors' ? 'donations' : 'donors';
            },
            /*searchDonors(){
             this.$http.get('fundraisers{/id}/donors', {id: this.id}).then(function (response) {
             this.donors = response.data.data;
             this.pagination = response.data.meta.pagination;
             });
             },
             searchDonations(){
             this.$http.get('fundraisers{/id}/donations', {id: this.id, include: 'donor'}).then(function (response) {
             this.donations = response.data.data;
             this.pagination = response.data.meta.pagination;
             });
             },*/
            searchTransactions(){
                this.$http.get('transactions', {include: 'donor', fund: this.id}).then(function (response) {
                    this.transactions = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready(){
            this.$http.get('funds/' + this.id).then(function (response) {
                this.fund = response.data.data;
            });

            this.$root.$on('Fundraiser:DisplayTransactions', function (display) {
                this.display = display;

                if (this.display) {
                    this.searchTransactions();
                }
            }.bind(this));

            if (this.display) {
                this.searchTransactions();
            }
        }
    }
</script>
