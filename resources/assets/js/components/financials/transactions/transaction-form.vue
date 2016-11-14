<template>
    <div>

        <alert :show.sync="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="400px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{ message }}</p>
        </alert>

        <alert :show.sync="showError"
               placement="top-right"
               :duration="6000"
               type="danger"
               width="400px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>{{ message }}</p>
        </alert>

        <div class="panel panel-default">
            <div class="panel-body">
                <label>Transaction Type</label>
                <select v-model="transaction.type" class="form-control">
                    <option value="donation">Donation</option>
                    <option value="transfer">Transfer</option>
                    <option value="refund">Refund</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
        </div>

        <div class="row" v-if="transaction.type == 'donation'">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Donation</h5>
                    </div>
                    <div class="panel-body">
                        <label>Designated Fund</label>
                        <v-select class="form-control" id="selectedFund" :debounce="250" :on-search="getFunds"
                                  :value.sync="selectedFund" :options="funds" label="name"
                                  placeholder="Select a fund to transfer from"></v-select>
                        <span class="help-block">Fund Balance: {{ selectedFund ? selectedFund.balance : 0 | currency }}</span>
                        <label>Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" placeholder="0.00" v-model="transaction.amount">
                        </div>
                    </div>
                </div>
                <div class="panel panel-default" v-if="! newDonorMode">
                    <div class="panel-heading">
                        <h5>Donor</h5>
                    </div>
                    <div class="panel-body">
                        <label>Donor</label>
                        <v-select class="form-control" id="selectedFund" :debounce="250" :on-search="getDonors"
                                  :value.sync="selectedDonor" :options="donors" label="name"
                                  placeholder="Select a donor"></v-select>
                        <hr class="divider inv">
                        <div class="row" v-if="selectedDonor">
                            <div class="col-xs-6" v-if="selectedDonor.company">
                                <label>Company</label>
                                <p>{{ selectedDonor.company }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Email</label>
                                <p>{{ selectedDonor.email }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Phone</label>
                                <p>{{ selectedDonor.phone }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Postal/Zip Code</label>
                                <p>{{ selectedDonor.zip }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Country</label>
                                <p>{{ selectedDonor.country.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <donor-form></donor-form>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Payment Method</h5>
                    </div>
                    <div class="panel-body">
                        <label>Payment Type</label>
                        <select class="form-control" v-model="transaction.payment.type">
                            <option value="card">Credit Card</option>
                            <option value="check">Check</option>
                            <option value="cash">Cash</option>
                        </select>

                        <div class="row" v-if="transaction.payment.type == 'card'">
                            <div class="col-xs-12">
                                <label>Card Holder's Name</label>
                                <input class="form-control" type="text" v-model="card.cardholder" />
                            </div>
                            <div class="col-xs-8">
                                <label>Card Number</label>
                                <input class="form-control" type="text" v-model="card.number" />
                            </div>
                            <div class="col-xs-4">
                                <label>CVC Code</label>
                                <input class="form-control" type="text" v-model="card.cvc" maxlength="4" />
                            </div>
                            <div class="col-xs-6">
                                <label>Exp. Month</label>
                                <select class="form-control" v-model="card.exp_month"></select>
                            </div>
                            <div class="col-xs-6">
                                <label>Exp. Year</label>
                                <select class="form-control" v-model="card.exp_year"></select>
                            </div>
                        </div>

                        <div class="row" v-if="transaction.payment.type == 'check'">
                            <div class="col-xs-12">
                                <label>Check Number</label>
                                <input class="form-control" type="text" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" v-if="transaction.type == 'transfer'">
            <div class="panel-heading">
                <h5>Transfer</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Transfer from</label>
                        <v-select class="form-control" id="fromFund" :debounce="250" :on-search="getFunds"
                                  :value.sync="fromFund" :options="funds" label="name"
                                  placeholder="Select a fund to transfer from"></v-select>
                        <span class="help-block">Fund Balance: {{ fromFund ? fromFund.balance : 0 | currency }}</span>
                    </div>
                    <div class="col-sm-6">
                        <label>Transfer to</label>
                        <v-select class="form-control" id="toFund" :debounce="250" :on-search="getDonors"
                                  :value.sync="toFund" :options="funds" label="name"
                                  placeholder="Select a fund to transfer to"></v-select>
                        <span class="help-block">Fund Balance: {{ toFund ? toFund.balance : 0 | currency }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" placeholder="0.00" v-model="transaction.amount">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" v-if="transaction.type == 'refund'">
            <div class="panel-heading">
                <h5>Refund</h5>
            </div>
            <div class="panel-body">
                <label>Transaction to Refund</label>
                <v-select class="form-control" id="selectedTransaction" :debounce="250" :on-search="getTransactions"
                          :value.sync="selectedTransaction" :options="transactions" label="description"
                          placeholder="Select a transaction to refund"></v-select>
                <span class="help-block">Transaction Amount: {{ selectedTransaction ? selectedTransaction.amount : 0 | currency }}</span>
                <span class="help-block">Payment Method: {{ selectedTransaction ? selectedTransaction.payment.type : '--' | capitalize }}</span>
                <label>Refund Amount</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="0.00" v-model="transaction.amount">
                </div>
                <label>Reason for Refund</label>
                <textarea class="form-control" v-model="transaction.payment.reason"></textarea>
            </div>
        </div>

        <div class="panel panel-default" v-if="transaction.type == 'credit'">
            <div class="panel-heading">
                <h5>Credit</h5>
            </div>
            <div class="panel-body">
                <label>Designated Fund</label>
                <v-select class="form-control" id="selectedFund" :debounce="250" :on-search="getFunds"
                          :value.sync="selectedFund" :options="funds" label="name"
                          placeholder="Select a fund to transfer from"></v-select>
                <span class="help-block">Fund Balance: {{ selectedFund ? selectedFund.balance : 0 | currency }}</span>
                <label>Amount</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="0.00" v-model="transaction.amount">
                </div>
                <label>Reason for Credit</label>
                <textarea class="form-control" v-model="transaction.payment.reason"></textarea>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-default">Cancel</button>
                <button class="btn btn-primary" @click="create">Create</button>
            </div>
        </div>

    </div>
</template>
<script>
    import vSelect from "vue-select";
    import donorForm from '../donors/donor-form.vue';
    export default{
        name: 'transaction-form',
        components: {
            vSelect,
            'donor-form': donorForm
        },
        data() {
            return {
                transaction: {
                    type: 'donation',
                    payment: {
                        type: "card",
                        reason: null
                    }
                },
                transactions: null,
                funds: null,
                toFund: null,
                fromFund: null,
                selectedFund: null,
                selectedTransaction: null,
                donors: null,
                selectedDonor: null,
                card: null,
                message: '',
                showSuccess: false,
                showError: false,
                newDonorMode: false
            }
        },
        methods: {
            getFunds(search) {
                this.$http.get('funds?per_page=10', {search: search}).then(function (response) {
                    this.funds = response.data.data;
                });
            },
            getDonors(search) {
                this.$http.get('donors?per_page=10', {search: search}).then(function (response) {
                    this.donors = response.data.data;
                });
            },
            getTransactions(search) {
                this.$http.get('transactions?type=donation&per_page=10', {search: search}).then(function (response) {
                    this.transactions = response.data.data;
                });
            },
            reset() {
                this.transaction = {
                    type: 'donation',
                    payment: {
                        type: "card",
                        reason: null
                    }
                };
                this.transactions = null;
                this.funds = null;
                this.toFund = null;
                this.fromFund = null;
                this.selectedFund = null;
                this.selectedTransaction = null;
                this.donors = null;
                this.selectedDonor = null;
                this.card = {
                    cardholder: null,
                    number: null,
                    cvc: null,
                    exp_month: null,
                    exp_year: null
                };
            },
            create() {
                var data = {
                    payment: {}
                };

                if (this.transaction.type == 'transfer') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.from_fund_id = this.fromFund ? this.fromFund.id : null;
                    data.to_fund_id = this.toFund ? this.toFund.id : null;
                }

                if (this.transaction.type == 'credit') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.fund_id = this.selectedFund ? this.selectedFund.id : null;
                    data.reason = this.transaction.payment.reason;
                }

                if (this.transaction.type == 'refund') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.transaction_id = this.selectedTransaction ? this.selectedTransaction.id : null;
                    data.reason = this.transaction.payment.reason;
                }

                if (this.transaction.type == 'donation') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.fund_id = this.selectedFund ? this.selectedFund.id : null;
                    if (this.selectedDonor && this.selectedDonor.id) {
                        data.donor_id = this.selectedDonor.id;
                    } else {
                        data.donor = this.selectedDonor;
                    }
                    data.payment.type = this.transaction.payment.type;
                    data.card = this.card;
                }

                this.$http.post('transactions', data).then(function (response) {
                    this.message = 'Transaction successfully created.';
                    this.showSuccess = true;
                    this.reset();
                    console.log(response);
                });
            }
        },
        ready() {
        }
    }
</script>