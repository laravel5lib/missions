<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <validator name="Donation">
            <form class="" name="DonationForm" novalidate v-show="donationState === 'form'">
                <spinner v-ref:validationSpinner size="xl" :fixed="false" text="Validating"></spinner>
                <template v-if="isState('form', 1)">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <label>Recipient</label>
                            <h4>{{ recipient }}</h4>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': checkForError('amount')}">
                            <label>Amount</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" v-model="amount" min="1" v-validate:amount="{required: true, min: 1}">
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': checkForError('donor')}">
                            <label>Donor Name</label>
                            <input type="text" class="form-control" v-model="donor" v-validate:donor="{required: true}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': checkForError('donor')}">
                            <label>Company Name</label>
                            <input type="text" class="form-control" v-model="company_name">
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row" v-if="!child">
                        <div class="col-sm-12 text-center">
                            <div class="form-group">
                                <div class="">
                                    <!--<a @click="goToState('form')" class="btn btn-default">Reset</a>-->
                                    <a @click="nextState()" class="btn btn-primary">Next</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
                <template v-if="isState('form', 2)">
                    <div class="alert alert-danger" role="alert" v-if="cardError" v-text="cardError"></div>
                    <!-- Credit Card -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" :class="{ 'has-error': checkForError('cardholdername') }">
                                <label for="cardHolderName">Card Holder's Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon input"><span class="fa fa-user"></span></span>
                                    <input type="text" class="form-control input" id="cardHolderName" placeholder="Name on card"
                                           v-model="cardHolderName" v-validate:cardHolderName="{ required: true }" autofocus/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" :class="{ 'has-error': checkForError('cardnumber') || validationErrors.cardNumber }">
                                <label for="cardNumber">Card Number</label>
                                <div class="input-group">
                                    <span class="input-group-addon input"><span class="fa fa-lock"></span></span>
                                    <input type="text" class="form-control input" id="cardNumber" placeholder="Valid Card Number"
                                           v-model="cardNumber" v-validate:cardNumber="{ required: true, maxlength: 19 }"
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
                            <div :class="{ 'has-error': checkForError('month') || validationErrors.cardMonth }">
                                <select v-model="cardMonth" class="form-control input" id="expiryMonth" v-validate:month="{ required: true }">
                                    <option v-for="month in monthList" value="{{month}}">{{month}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': checkForError('year') || validationErrors.cardYear }">
                                <select v-model="cardYear" class="form-control input" id="expiryYear" v-validate:year="{ required: true }">
                                    <option v-for="year in yearList" value="{{year}}">{{year}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': checkForError('code') || validationErrors.cardCVC }">
                                <label for="cvCode">
                                    CV CODE</label>
                                <input type="text" class="form-control input" id="cvCode" maxlength="3" v-model="cardCVC"
                                       placeholder="CV" v-validate:code="{ required: true, minlength: 3, maxlength: 3 }"/>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-12">
                            <div :class="{ 'has-error': checkForError('email') }">
                                <label for="infoEmailAddress">Billing Email</label>
                                <input type="text" class="form-control input" v-model="cardEmail" v-validate:email="['oneOrOther']" id="infoEmailAddress">
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row">
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': checkForError('phone') }">
                                <label for="infoPhone">Billing Phone</label>
                                <input type="tel" class="form-control input" v-model="cardPhone | phone" v-validate:phone="['oneOrOther']" id="infoPhone">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': checkForError('zip') }">
                                <label for="infoZip">ZIP</label>
                                <input type="text" class="form-control input" v-model="cardZip" v-validate:zip="{ required: true }" id="infoZip" placeholder="12345">
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
                    <div class="col-sm-12 text-center" v-if="!child">
                        <div class="form-group">
                            <div class="">
                                <!--<a @click="goToState('form')" class="btn btn-default">Reset</a>-->
                                <a @click="createToken" class="btn btn-primary">Review Donation</a>
                            </div>
                        </div>
                    </div>
                </template>
                <hr class="divider inv sm">
            </form>
            <div v-show="donationState === 'review'">
                <spinner v-ref:donationSpinner size="xl" :fixed="false" text="Processing Donation"></spinner>
                <div class="row">
                    <h4 class="text-center">Donation Details</h4>
                    <hr class="divider lg">
                    <div class="col-sm-12">
                        <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Card Holder Name</h6>
                        <h5>{{cardHolderName}}</h5>
                        <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Card Number</h6>
                        <h5>{{cardNumber}}</h5>
                        <div class="row">
                            <div class="col-xs-4">
                                <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Card Exp</h6>
                                <h5>{{cardMonth}}/{{cardYear}}</h5>
                            </div>
                            <div class="col-xs-8">
                                <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Billing Email</h6>
                                <h5>{{cardEmail}}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Billing Zip</h6>
                                <h5>{{cardZip}}</h5>
                            </div>
                            <div class="col-xs-8">
                                <h6 style="color:#808080;font-size:10px;letter-spacing:1px;" class="text-uppercase">Save Payment Method</h6>
                                <h5>{{cardSave ? 'Yes' : 'No'}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="well">
                            <p class="list-group-item-text"><span style="text-transform:uppercase;font-size:9px;font-weight:bold;display:block;letter-spacing:1px;">Recipient:</span> {{recipient}}</p>
                            <hr class="divider inv sm">
                            <p class="list-group-item-text"><span style="text-transform:uppercase;font-size:9px;font-weight:bold;display:block;letter-spacing:1px;">Designation:</span> {{title}}</p>
                            <hr class="divider inv sm">
                            <p class="list-group-item-text"><span style="text-transform:uppercase;font-size:9px;font-weight:bold;display:block;letter-spacing:1px;">Amount to be charged:</span> {{amount|currency}}</p>
                            <hr class="divider sm">
                            <p style="color:#808080;font-size:9px;" class="list-group-item-text">
                                <b>Disclaimer:</b>
                                All Missions.Me donations and support are considered 501(c)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-center" v-if="!child">
                    <a @click="goToState('form')" class="btn btn-default">Reset</a>
                    <a @click="submit" class="btn btn-primary">Donate</a>
                </div>
            </div>
            <div class="" v-show="donationState === 'confirmation'">
                <div class="text-center">
                    <h1><i class="fa fa-check-circle text-success success fa-3x"></i></h1>
                    <h2>Donation Confirmed</h2>
                    <h3>Thank you!</h3>
                </div>
                <!--<div class="panel-footer" v-if="!child">
                    <a @click="done" class="btn btn-success">Close</a>
                </div>-->
            </div>
        </validator>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'donate',
        components:{ vSelect, 'spinner': VueStrap.spinner },
        props: {
            type: {
                type: String,
                default: null
            },
            typeId: {
                type: String,
                default: null
            },
            fundId: {
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
            },
            attemptSubmit: {
                type: Boolean,
                default: false
            },
            child: {
                type: Boolean,
                default: false
            },
            donationState: {
                type: String,
                default: 'form'
            },
            subState: {
                type: Number,
                default: 1
            },
            title: {
                type: String,
                default: ''
            },
            identifier: {
                type: String,
                default: null
            }

        },
        data(){
            return{
                donor: '',
                donor_id: null,
                company_name: '',
                amount: 1,
                validateWith: 'email',
                token: null,

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
                cardError: null,

                // stripe vars
                stripeError: null,
                monthList: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
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
                stripeDeferred: {},
                attemptedCreateToken: false,
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
            },
            'donationState'(val) {
//                console.log(val);
            },
            'subState'(val) {
//                console.log(val);
            },
        },
        computed:{
            yearList() {
                var num, today, years, yyyy;
                today = new Date;
                yyyy = today.getFullYear();
                years = (function () {
                    var i, ref, ref1, results;
                    results = [];
                    for (num = i = ref = yyyy, ref1 = yyyy + 10; ref <= ref1 ? i <= ref1 : i >= ref1; num = ref <= ref1 ? ++i : --i) {
                        results.push(num);
                    }
                    return results;
                })();
                return years;
            },
            cardParams() {
                return {
                    cardholder: this.cardHolderName,
                    number: this.cardNumber,
                    exp_month: this.cardMonth,
                    exp_year: this.cardYear,
                    cvc: this.cardCVC,
                };
            }
        },
        methods: {
            isState(major, minor) {
                return this.donationState === major && this.subState === minor
            },
            toState(major, minor) {
                this.donationState = major;
                this.subState = minor
            },
            checkForError(field){
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
                    // reset errors
                    this.cardError = null;
                    this.validationErrors = {
                        cardNumber: '',
                        cardCVC: '',
                        cardYear: '',
                        cardMonth: ''
                    };

                    // authorize card data
                    console.log(this.cardParams);
                    this.$refs.validationspinner.show();
                    this.$http.post('donations/authorize', this.cardParams)
                            .then(this.createTokenCallback,
                                    function (error) {
                                        this.cardError = error.data.message;
                                        this.$refs.validationspinner.hide();


                                        switch (error.data.message) {
                                            case 'Your card number is incorrect.':
                                                this.stripeError = error.data;
                                                this.validationErrors.cardNumber = 'error';
                                                break;
                                        }

                                        this.stripeDeferred.reject(error.data.code);
                                    });
                }
                return this.stripeDeferred.promise();
            },
            createTokenCallback(resp) {
                console.log(resp);
                 /*
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
                }*/
                if (resp.status === 200) {
                    this.$refs.validationspinner.hide();
                    this.token = resp.data;
                    this.stripeDeferred.resolve(resp.data);
                }

                if (!this.child) {
                    this.goToState('review');
                }
            },
            submit(){
                this.$refs.donationspinner.show();
                var data = {
                    amount: this.amount,
                    currency: 'USD', // determined from card token
                    description: 'Donation to ' + this.title,
                    comment: null,
                    fund_id: this.fundId,
                    token: this.token,
                    payment: {
                        type: 'card',
                        brand: this.detectCardType(this.cardNumber),
                        last_four: this.cardNumber.substr(-4),
                        cardholder: this.cardHolderName,
                    }

                };
                if (parseInt(this.auth) && this.donor_id) {
                    data.donor_id = this.donor_id;
                } else {
                    data.donor = {
                        name: this.donor,
                        company: this.company_name,
                        email: this.cardEmail,
                        phone: this.cardPhone,
                        zip: this.cardZip,
                        country_code: 'us' // needs UI
                    }
                }
                this.$http.post('donations', data)
                        .then(function (response) {
                            this.stripeDeferred.resolve(true);
                            this.$refs.donationspinner.hide();
                            this.donationState = 'confirmation';
                        },
                        function (error) {
                            this.$refs.donationspinner.hide();
                            this.cardError = error.data.message;
                            this.toState('form', 2);
                        });
            },
            done(){
                if (this.child) {
                    this.$root.$emit('DonateForm:complete');
                }/* else {
                    window.location.href
                }*/
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
            nextState(){
                this.attemptSubmit = !0;
                switch (this.donationState){
                    case 'form':
                        switch (this.subState) {
                            case 1:
                                if (this.$Donation.invalid) {
                                    break;
                                }

                                this.subState = 2;
                                this.attemptSubmit = !1;
                                break;
                            case 2:
                                if (this.$Donation.invalid) {
                                    break;
                                }
                                this.donationState = 'review';
                                this.subState = 1;
                                this.attemptSubmit = !1;
                                break
                        }
                        break;
                    case 'review':
                        break;
                }
            },
            prevState(){
                switch (this.donationState){
                    case 'form':
                        switch (this.subState) {
                            case 1:
                                break;
                            case 2:
                                this.donationState = 'form';
                                this.subState = 1;
                                break
                        }
                        break;
                    case 'review':
                        this.donationState = 'form';
                        this.subState = 2;
                        break;
                }
            },
            detectCardType(number) {
                // http://stackoverflow.com/questions/72768/how-do-you-detect-credit-card-type-based-on-number
                var re = {
                    electron: /^(4026|417500|4405|4508|4844|4913|4917)\d+$/,
                    maestro: /^(5018|5020|5038|5612|5893|6304|6759|6761|6762|6763|0604|6390)\d+$/,
                    dankort: /^(5019)\d+$/,
                    interpayment: /^(636)\d+$/,
                    unionpay: /^(62|88)\d+$/,
                    visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
                    mastercard: /^5[1-5][0-9]{14}$/,
                    amex: /^3[47][0-9]{13}$/,
                    diners: /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/,
                    discover: /^6(?:011|5[0-9]{2})[0-9]{12}$/,
                    jcb: /^(?:2131|1800|35\d{3})\d{11}$/
                };

                for(var key in re) {
                    if(re[key].test(number)) {
                        return key
                    }
                }
            }
        },
        events: {
            'VueStripe::reset-form': function () {
                return this.resetCaching();
            }
        },
        ready: function () {
            this.$dispatch('payment-complete', true);
            if (this.devMode) {
                this.cardNumber = '4242424242424242';
                this.cardCVC = '123';
                this.cardYear = '2019';
                this.cardMonth = '01';
            }

            if (parseInt(this.auth)) {
                this.$http.get('users/me').then(function (response) {
                    this.donor = response.data.data.name;
                    this.donor_id = response.data.data.donor_id || null;
                    this.cardHolderName = response.data.data.name;
                    this.cardEmail = response.data.data.email;
                    this.cardPhone = response.data.data.phone_one;
                    this.cardZip = response.data.data.zip
                });
            }

            //Listen to Event Bus
            this.$root.$on('DonateForm:nextState', function () {
                this.nextState();
            }.bind(this));

            this.$root.$on('DonateForm:prevState', function () {
                this.prevState();
            }.bind(this));

            this.$root.$on('DonateForm:resetState', function () {
                this.toState('form', 1);
            }.bind(this));

            this.$root.$on('DonateForm:reviewDonation', function (identifier) {
                if (identifier !== this.identifier) {
                    return false;
                }

                $.when(this.createToken()).then(function (response) {
                    this.goToState('review');
                }.bind(this));
            }.bind(this));

            this.$root.$on('DonateForm:donate', function (identifier) {
                if (identifier !== this.identifier) {
                    return false;
                }

                this.submit();
            }.bind(this));

        },
    }
</script>