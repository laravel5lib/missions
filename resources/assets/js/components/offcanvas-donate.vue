<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <mm-aside :show="showRight" @open="showRight=true" @close="showRight=false" placement="right" header="Donate" :width="275">

            <form class="form-horizontal" name="DonationForm" novalidate v-show="donationState === 'form'">
                <div class="row">
                    <div class="col-sm-12 text-center">
                            <label>Recipient</label>
                            <h4>{{ recipient }}</h4>
                    </div>
                </div>
                <hr class="divider inv sm">
                <div class="row">
                    <div class="col-sm-12" :class="{ 'has-error': errors.has('amount')}">
                        <label>Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="number" class="form-control" v-model="amount" min="1" name="amount" v-validate="{required: true, min: 1}">
                        </div>
                    </div>
                </div>
                <hr class="divider inv sm">
                <div class="row">
                    <div class="col-sm-12" :class="{ 'has-error': errors.has('donor')}">
                            <label>Donor Or Company Name</label>
                            <input type="text" class="form-control" v-model="donor" name="donor" v-validate="'required'">
                    </div>
                </div>
                <hr class="divider inv sm">
                <!-- Credit Card -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-groups" :class="{ 'has-error': errors.has('cardholdername') }">
                                <label for="cardHolderName">Card Holder's Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon input"><span class="fa fa-user"></span></span>
                                    <input type="text" class="form-control input" id="cardHolderName" placeholder="Name on card"
                                           v-model="cardHolderName" name="cardHolderName" v-validate="'required'" autofocus/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-groups" :class="{ 'has-error': errors.has('cardnumber') || validationErrors.cardNumber }">
                                <label for="cardNumber">Card Number</label>
                                <div class="input-group">
                                    <span class="input-group-addon input"><span class="fa fa-lock"></span></span>
                                    <input type="text" class="form-control input" id="cardNumber" placeholder="Valid Card Number"
                                           v-model="cardNumber" name="cardNumber" v-validate="{ required: true, maxlength: 19 }"
                                           @keyup="formatCard($event)" maxlength="19"/>
                                </div>
                                <span class="help-block" v-if="validationErrors.cardNumber=='error'">{{stripeError.message}}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <label style="display:block;margin-left: 10px;" for="expiryMonth">EXPIRY DATE</label>
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('month') || validationErrors.cardMonth }">
                                <select v-model="cardMonth" class="form-control input" id="expiryMonth" name="month" v-validate="'required'">
                                    <option v-for="month in monthList" :value="month">{{month}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('year') || validationErrors.cardYear }">
                                <select v-model="cardYear" class="form-control input" id="expiryYear" name="year" v-validate="'required'">
                                    <option v-for="year in yearList" :value="year">{{year}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('code') || validationErrors.cardCVC }">
                                <label for="cvCode">
                                    CV CODE</label>
                                <input type="text" class="form-control input" id="cvCode" maxlength="3" v-model="cardCVC"
                                       placeholder="CV" name="code" v-validate="{ required: true, minlength: 3, maxlength: 3 }"/>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12">
                            <div :class="{ 'has-error': errors.has('email') }">
                                <label for="infoEmailAddress">Billing Email</label>
                                <input type="text" class="form-control input" v-model="cardEmail" name="email="['oneOrOther']" id" v-validate="infoEmailAddress">
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': errors.has('phone') }">
                                <label for="infoPhone">Billing Phone</label>
                                <phone-input v-model="cardPhone" id="infoPhone" name="phone" validation="'required|oneOrOther'"></phone-input>
                                <!--<input type="tel" class="form-control input" v-model="cardPhone | phone" name="phone="['oneOrOther']" id" v-validate="infoPhone">-->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': errors.has('zip') }">
                                <label for="infoZip">ZIP</label>
                                <input type="text" class="form-control input" v-model="cardZip" name="zip="'required'" id="infoZip" placeholder" v-validate="12345">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        <hr class="divider inv sm">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="cardSave">Save payment details for next time
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="col-sm-12 text-center">
                        <div class="form-group">
                            <div class="">
                                <!--<a @click="goToState('form')" class="btn btn-default">Reset</a>-->
                                <a @click="goToState('review')" class="btn btn-primary">Review Donation</a>
                            </div>
                        </div>
                    </div>
            </form>
            <div class="panel panel-primary" v-show="donationState === 'review'">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h5>Donation Details</h5>
                    </div>
                </div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Card Holder Name</dt>
                        <dd>{{cardHolderName}}</dd>
                        <dt>Card Number</dt>
                        <dd>{{cardNumber}}</dd>
                        <dt>Card Expiration</dt>
                        <dd>{{cardMonth}}/{{cardYear}}</dd>
                        <dt>Billing Email</dt>
                        <dd>{{cardEmail}}</dd>
                        <dt>Billing Zip</dt>
                        <dd>{{cardZip}}</dd>
                        <dt>Save Payment Method</dt>
                        <dd>{{cardSave ? 'Yes' : 'No'}}</dd>
                    </dl>
                    <hr>
                    <p class="list-group-item-text">Recipient: {{recipient}}</p>
                    <p class="list-group-item-text">Amount to be charged immediately: {{amount|currency}}</p>
                </div>
                <div class="panel-footer">
                    <a @click="goToState('form')" class="btn btn-default">Reset</a>
                    <a @click="createToken" class="btn btn-primary">Donate</a>
                </div>
            </div>

        </mm-aside>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    export default{
        name: 'donate',
        components:{ vSelect },
        props: {
            type: {
                type: String,
                default: null
            },
            typeId: {
                type: String,
                default: null
            },
            recipient: {
                type: String,
                default: 'Missions.Me'
            },
            stripeKey: {
                type: String,
                default: null
            },
            auth: {
                type:String,
                default: '0'
            }
        },
        data(){
            return{
                donor: '',
                amount: 1,
                validateWith: 'email',

                //card vars
                card: null,
                cardHolderName: null,
                cardNumber: '',
                cardMonth: '',
                cardYear: '',
                cardCVC: '',
                cardEmail: null,
                cardPhone: null,
                cardZip: null,
                cardSave: false,

                // stripe vars
                stripeError: null,
                monthList: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                placeholders: {
                    year: 'Year',
                    month: 'Month',
                    cvc: 'CVC',
                    number: 'Card Number'
                },
                validationErrors: {
                    cardNumber: '',
                    cardCVC: '',
                    cardYear: '',
                    cardMonth: ''
                },
                showButton: true,
                showLabels: true,
                devMode: true,
                // deferred variable used for card validation
                // needs to be on `this` scope to access in response callback
                stripeDeferred: {},
                attemptedCreateToken: false,
                attemptSubmit: false,
                donationState: 'form',
                showRight: false,
            }
        },
        validators: {
            oneOrOther(val){
                return val === this.vm.cardEmail
                        ? (!val.length && this.vm.cardPhone && this.vm.cardPhone.length > 0) || (val.length > 0 && /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(val))
                        : (!val.length && this.vm.cardEmail && this.vm.cardEmail.length > 0) || val.length > 0;
            }
        },
        watch: {
            'paymentComplete'(val, oldVal) {
                this.$dispatch('payment-complete', val)
            }
        },
        computed:{
            yearList() {
                var num, today, years, yyyy;
                today = new Date;
                yyyy = today.getFullYear();
                years = (() =>  {
                    var i, ref, ref1, results;
                    results = [];
                    for (num = i = ref = yyyy, ref1 = yyyy + 10; ref <= ref1 ? i <= ref1 : i >= ref1; num = ref <= ref1 ? ++i : --i) {
                        results.push(num.toString().substr(2, 2));
                    }
                    return results;
                })();
                return years;
            },
            cardParams() {
                return {
                    name: this.cardHolderName,
                    number: this.cardNumber,
                    expMonth: this.cardMonth,
                    expYear: this.cardYear,
                    cvc: this.cardCVC,
                    zip: this.cardZip,
                };
            }
        },
        methods: {
            errors.has(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$Donation[field].invalid && this.attemptSubmit;
            },
            resetCaching() {
                console.log('resetting');
                this.cardMonth = '';
                this.cardCVC = '';
                this.cardYear = '';
                this.cardNumber = '';
                return this.card = null;
            },
            formatCard(event) {
                var output;
                output = this.cardNumber.split('-').join('');
                if (output.length > 0) {
                    output = output.replace(/[^\d]+/g, '');
                    output = output.match(new RegExp('.{1,4}', 'g'));
                    if (output) {
                        return this.cardNumber = output.join('-');
                    } else {
                        return this.cardNumber = '';
                    }
                }
            },
            createToken() {
                this.stripeDeferred = $.Deferred();

                if (this.$Donation.invalid) {
                    this.attemptedCreateToken = true;
                    this.stripeDeferred.reject(false);
                } else {
                    Stripe.setPublishableKey(this.stripeKey);
                    Stripe.card.createToken(this.cardParams, this.createTokenCallback);
                }
                return this.stripeDeferred.promise();
            },
            createTokenCallback(status, resp) {
                console.log(status);
                console.log(resp);
                this.validationErrors = {
                    cardNumber: '',
                    cardCVC: '',
                    cardYear: '',
                    cardMonth: ''
                };
                this.stripeError = resp.error;
                if (this.stripeError) {
                    if (this.stripeError.param === 'number') {
                        this.validationErrors.cardNumber = 'error';
                    }
                    if (this.stripeError.param === 'exp_year') {
                        this.validationErrors.cardYear = 'error';
                    }
                    if (this.stripeError.param === 'exp_month') {
                        this.validationErrors.cardMonth = 'error';
                    }
                    if (this.stripeError.param === 'cvc') {
                        this.validationErrors.cardCVC = 'error';
                    }
                    this.stripeDeferred.reject(false);
                }
                if (status === 200) {
                    this.card = resp;
                    // send payment data to parent
                    this.paymentInfo = {
                        token: resp,
                        save: this.cardSave,
                        email: this.cardEmail
                    };
//                    this.$parent.upfrontTotal = this.upfrontTotal;
//                    this.$parent.fundraisingGoal = this.fundraisingGoal;
                    this.stripeDeferred.resolve(true);
                }
            },
            goToState(state){
                switch (state) {
                    case 'form':
                        this.donationState = state;

                        break;
                    case 'review':
                        this.attemptSubmit = true;
                        if (this.$Donation.valid) {
                            this.donationState = state;
                        }
                        break;
                }
            },
            submit(){

            }
        },
        events: {
            'VueStripe::create-card-token': () =>  {
                return this.createToken();
            },
            'VueStripe::reset-form': () =>  {
                return this.resetCaching();
            }
        },
        mounted() {
            this.$dispatch('payment-complete', true);
            if (this.devMode) {
                this.cardNumber = '4242424242424242';
                this.cardCVC = '123';
                this.cardYear = '19';
                this.cardMonth = '1';
            }

            if (parseInt(this.auth)) {
                this.$http.get('users/me').then((response) => {
                    this.donor = response.data.data.name
                    this.cardHolderName = response.data.data.name
                    this.cardEmail = response.data.data.email
                    this.cardPhone = response.data.data.phone_one
                    this.cardZip = response.data.data.zip
                });
            }
        },
    }
</script>
