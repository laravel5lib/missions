<template>
    <div>
        <div class="alert alert-info" v-if="editingCreditCard">
            <h5>Edits to credit card transactions are limited.</h5>
            <p>You should refund the transaction and start over.</p>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <spinner ref="transactionspinner" size="xl" :fixed="false" :text="spinnerText"></spinner>
                <div class="row" v-if="editing">
                    <div class="col-xs-12">
                        <label>Designated Fund</label>
                        <template v-if="!editing">
                            <v-select @keydown.enter.prevent=""  class="form-control" id="designatedFund" :debounce="250" :on-search="getFunds"
                                      v-model="designatedFund" :options="funds" label="name"
                                      placeholder="Select a fund"></v-select>
                        </template>
                        <template v-else>
                            <p>{{ designatedFund.name }}</p>
                        </template>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label>Transaction Type</label>
                        <select v-model="transaction.type" class="form-control" :disabled="editing">
                            <option value="donation">Donation</option>
                            <option value="transfer" v-if="app.user.can.add_transfer_transactions">Transfer</option>
                            <option value="credit" v-if="app.user.can.add_credit_transactions">Credit</option>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <label>Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text"
                                   class="form-control"
                                   placeholder="0.00"
                                   v-model="transaction.amount"
                                   :disabled="editingCreditCard">
                        </div>
                    </div>
                </div>
                <div class="row" v-if="transaction.type == 'credit'">
                    <div class="col-xs-12">
                        <label>Reason for Credit</label>
                        <textarea class="form-control" v-model="transaction.details.reason"></textarea>
                    </div>
                </div>
                <div class="row" v-if="transaction.type == 'transfer'">
                    <div class="col-xs-12"><label>Related Fund</label></div>
                    <div class="col-xs-4">
                        <select class="form-control" v-model="transfer_type">
                            <option value="to">Transfer to</option>
                            <option value="from" >Transfer from</option>
                        </select>
                    </div>
                    <div class="col-xs-8">
                        <v-select @keydown.enter.prevent=""  class="form-control" id="selectedFund" :debounce="250" :on-search="getFunds"
                                  v-model="selectedFund" :options="funds" label="name"
                                  placeholder="Select a fund"></v-select>
                        <span class="help-block small" v-show="selectedFund">
                            <a href="#" @click="fundInfoMode"><i class="fa fa-question-circle"></i> View Fund Details</a>
                        </span>
                    </div>
                </div>
                <div v-if="transaction.type == 'donation'">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Donor</label>
                            <v-select @keydown.enter.prevent=""  class="form-control" id="selectedFund" :debounce="250" :on-search="getDonors"
                                      v-model="selectedDonor" :options="donors" label="name"
                                      placeholder="Select a donor"></v-select>
                            <span class="help-block small">
                                <a href="#" v-show="selectedDonor" @click="donorInfoMode"><i class="fa fa-info-circle"></i> View Donor Details</a>
                                <a href="#" v-show="!selectedDonor" @click="newDonorMode" v-if="app.user.can.create_donors"><i class="fa fa-plus-circle"></i> Create New Donor</a>
                            </span>
                        </div>
                        <div class="col-xs-6">
                            <label>Payment Method</label>
                            <select class="form-control" v-model="transaction.details.type" :disabled="editingCreditCard">
                                <option value="card">Credit Card</option>
                                <option value="check" v-if="app.user.can.add_check_transactions">Check</option>
                                <option value="cash" v-if="app.user.can.add_cash_transactions">Cash</option>
                            </select>
                        </div>
                        <div class="col-xs-12">
                            <label><input type="checkbox" v-model="transaction.anonymous"> Give Anonymously</label>
                        </div>
                        <div class="col-xs-12">
                            <label>Comment</label>
                            <textarea class="form-control" v-model="transaction.comment"></textarea>
                        </div>
                    </div>
                    <div class="row" v-if="transaction.details.type == 'card'">
                        <div class="col-xs-6">
                            <label>Card Holder's Name</label>
                            <input class="form-control" type="text" v-model="card.cardholder" />
                        </div>
                        <div class="col-xs-6">
                            <label>Card Number</label>
                            <input class="form-control" type="text" v-model="card.number" :disabled="editingCreditCard" />
                        </div>
                        <div class="col-xs-4">
                            <label>CVC Code</label>
                            <input class="form-control" type="text" v-model="card.cvc" maxlength="4" :disabled="editingCreditCard" />
                        </div>
                        <div class="col-xs-4">
                            <label>Exp. Month</label>
                            <select class="form-control" v-model="card.exp_month" :disabled="editingCreditCard">
                                <option v-for="month in monthList" :value="month">{{month}}</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <label>Exp. Year</label>
                            <select class="form-control" v-model="card.exp_year" :disabled="editingCreditCard">
                                <option v-for="year in yearList" :value="year">{{year}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="transaction.details.type == 'check'">
                    <div class="col-xs-12">
                        <label>Check Number</label>
                        <input class="form-control" type="text" v-model="transaction.details.number" />
                    </div>
                </div>
            </div>
            <div class="panel-body text-right">
                <button class="btn btn-default btn-md" @click="cancel" v-if="editing">Cancel</button>
                <button class="btn btn-default btn-md" @click="reset" v-else>Reset</button>
                <button class="btn btn-primary btn-md" @click="update" v-if="editing">Update</button>
                <button class="btn btn-primary btn-md" @click="create" v-else>Create</button>
            </div>
        </div>

        <!-- New Donor Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="newDonor">
            <div class="modal-dialog" role="document">
                <donor-form @donor-created="donorCreated" @cancel="newDonorMode"></donor-form>
            </div>
        </div>

        <!-- Donor Info Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="donorInfo" v-if="selectedDonor">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="donorInfoMode"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{ selectedDonor && selectedDonor.name ? selectedDonor.name : '--' }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6" v-if="selectedDonor && selectedDonor.company">
                                <label>Company</label>
                                <p>{{ selectedDonor && selectedDonor.company ? selectedDonor.company : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Email</label>
                                <p>{{ selectedDonor && selectedDonor.email ? selectedDonor.email : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Phone</label>
                                <p>{{ selectedDonor && selectedDonor.phone ? selectedDonor.phone : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Address</label>
                                <p>{{ selectedDonor && selectedDonor.address ? selectedDonor.address : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>City</label>
                                <p>{{ selectedDonor && selectedDonor.city ? selectedDonor.city : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>State/Providence</label>
                                <p>{{ selectedDonor && selectedDonor.state ? selectedDonor.state : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Postal/Zip Code</label>
                                <p>{{ selectedDonor && selectedDonor.zip ? selectedDonor.zip : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Country</label>
                                <p>{{ selectedDonor && selectedDonor.country ? selectedDonor.country.name : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Account Type</label>
                                <p>{{ selectedDonor && selectedDonor.account_type ? selectedDonor.account_type : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Account Name</label>
                                <p>{{ selectedDonor && selectedDonor.account_name ? selectedDonor.account_name : 'n/a' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Stripe Customer ID</label>
                                <p>{{ selectedDonor && selectedDonor.customer_id ? selectedDonor.customer_id : 'n/a' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fund Info Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="fundInfo" v-if="selectedFund">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="fundInfoMode"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Fund Details</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Name</label>
                                <p>{{ selectedFund.name }}</p>
                            </div>
                            <div class="col-xs-12">
                                <label>Balance</label>
                                <h4 :class="{'text-success' : selectedFund.balance > 0, 'text-danger' : selectedFund.balance < 0}">
                                    {{ currency(selectedFund.balance) }}
                                </h4>
                            </div>
                            <div class="col-xs-12">
                                <label>Type</label>
                                <p>{{ selectedFund.type }}</p>
                            </div>
                            <div class="col-xs-12">
                                <label>QuickBooks Class</label>
                                <p>{{ selectedFund.class }}</p>
                            </div>
                            <div class="col-xs-12">
                                <label>QuickBooks Item</label>
                                <p>{{ selectedFund.item }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script type="text/javascript">
    import $ from 'jquery';
    import vSelect from "vue-select";
    import donorForm from '../donors/donor-form.vue';
    export default{
        name: 'transaction-form',
        props: {
            'fundId': {
                type: String,
                required: true
            },
            'id': {
                type: String,
                default: null
            },
            'editing': {
                type: Boolean,
                default: false
            }
        },
        components: {
            vSelect,
            'donor-form': donorForm
        },
        data() {
            return {
                app: MissionsMe,
                transaction: {
                    type: 'donation',
                    anonymous: false,
                    details: {
                        type: "card",
                        reason: null
                    }
                },
                transactions: null,
                funds: null,
                toFund: null,
                fromFund: null,
                selectedFund: null,
                designatedFund: {
                    name: null
                },
                donors: null,
                selectedDonor: null,
                card: {
                    cardholder: null,
                    number: null,
                    exp_year: null,
                    exp_month: null,
                    zip: null
                },
                monthList: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
                transfer_type: 'to',
                spinnerText: 'Loading...'
            }
        },
        computed: {
            editingCreditCard() {
                if (this.editing && this.transaction.details.type == 'card') {
                    return true;
                }
                return false;
            },
            yearList() {
                var num, today, years, yyyy;
                today = new Date;
                yyyy = today.getFullYear();
                years = (() =>  {
                    var i, ref, ref1, results;
                    results = [];
                    for (num = i = ref = yyyy, ref1 = yyyy + 10; ref <= ref1 ? i <= ref1 : i >= ref1; num = ref <= ref1 ? ++i : --i) {
                        results.push(num);
                    }
                    return results;
                })();
                return years;
            }
        },
        methods: {
            newDonorMode() {
                $('#newDonor').modal('toggle')
            },
            donorInfoMode() {
                $('#donorInfo').modal('toggle')
            },
            fundInfoMode() {
                $('#fundInfo').modal('toggle')
            },
            getFunds(search) {
                this.$http.get('funds?per_page=10', { params: {search: search} }).then((response) => {
                    this.funds = response.data.data;
                });
            },
            getDonors(search) {
                this.$http.get('donors?per_page=10', { params: {search: search} }).then((response) => {
                    this.donors = response.data.data;
                });
            },
            donorCreated(donor) {
                $('#newDonor').modal('hide');
                this.$http.get('donors/' + donor).then((response) => {
                    this.selectedDonor = response.data.data;
                });
            },
            reset() {
                this.transaction = {
                    type: 'donation',
                    details: {
                        type: "card",
                        reason: null
                    }
                };
                this.transactions = null;
                this.funds = null;
                this.toFund = null;
                this.fromFund = null;
                this.selectedFund = null;
                this.donors = null;
                this.selectedDonor = null;
                this.card = {
                    cardholder: null,
                    number: null,
                    cvc: null,
                    exp_month: null,
                    exp_year: null,
                    zip: null
                };
            },
            cancel() {
                window.location = '/admin/transactions/' + this.id;
            },
            fetch() {
                this.$refs.transactionspinner.show();

                this.$http.get('transactions/' + this.id, { params: {include: 'donor,fund'} }).then((response) => {
                    this.$refs.transactionspinner.hide();
                    this.transaction = response.data.data;
                    this.designatedFund = this.transaction.fund.data;

                    if (this.transaction.type == 'transfer') {
                        this.transaction.amount < 0 ? this.transfer_type = 'from' : this.transfer_type = 'to';
                        this.selectedFund.id = this.transaction.details.fund_id;
                    }

                    if (this.transaction.type == 'donation') {

                        this.selectedDonor = this.transaction.donor.data;

                        if (this.transaction.details.type == 'card') {
                            this.card.cardholder = this.transaction.details.cardholder;
                            this.card.number = this.transaction.details.last_four;
                        }
                    }

                },(response) =>  {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showError', 'Unable to retrieve transaction.');
                });
            },
            create() {
                this.$refs.transactionspinner.show();

                var data = this.prepareData();

                this.$http.post('transactions', data).then((response) => {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showSuccess', 'Transaction successfully created.');
                    this.$emit('transactionCreated');
                    this.reset();
                },(response) =>  {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            update() {
                this.$refs.transactionspinner.show();

                var data = this.prepareData();

                this.$http.put('transactions/' + this.id, data).then((response) => {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showSuccess', 'Transaction updated successfully.');
                    this.reset();
                },(response) =>  {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            prepareData() {
                var data = {
                    details: {}
                };

                if (this.transaction.type == 'transfer') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.from_fund_id = this.transfer_type == 'to' ? this.fundId : this.selectedFund.id;
                    data.to_fund_id = this.transfer_type == 'from' ? this.fundId : this.selectedFund.id;
                }

                if (this.transaction.type == 'credit') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.fund_id = this.fundId;
                    data.reason = this.transaction.details.reason;
                }

                if (this.transaction.type == 'donation') {
                    data.type = this.transaction.type;
                    data.amount = this.transaction.amount;
                    data.comment = this.transaction.comment;
                    data.anonymous = this.transaction.anonymous;
                    data.fund_id = this.fundId;
                    if (this.selectedDonor && this.selectedDonor.id) {
                        data.donor_id = this.selectedDonor.id;
                    } else {
                        data.donor = this.selectedDonor;
                    }
                    data.details.type = this.transaction.details.type;
                    if (this.transaction.details.type == 'check') {
                        data.details.number = this.transaction.details.number;
                        data.details.comment = this.transaction.comment;
                    }
                    if (this.transaction.details.type == 'card') {
                        data.card = this.card;
                        data.card.zip = this.selectedDonor.zip
                    }
                }

                return data;
            }
        },
        mounted() {
            if (this.editing) { this.fetch() };
        }
    }
</script>