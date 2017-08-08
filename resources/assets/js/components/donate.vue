<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <validator name="Donation">
            <form class="" name="DonationForm" novalidate v-show="donationState === 'form'">
                <spinner ref="validationSpinner" size="xl" :fixed="false" text="Validating"></spinner>
                <template v-if="isState('form', 1)">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <label>Your Donation will go to:</label>
                            <h4 class="text-primary" style="margin-top:0px;">{{ recipient }}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': errors.has('amount')}">
                            <label>Enter Donation Amount</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input style="font-size:22px;color:#05ce7b;" type="text" class="form-control" v-model="amount" min="1" v-validate:amount="{required: true, min: 1}">
                                <span class="input-group-addon">USD</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': errors.has('donor')}">
                            <label>Donor Name</label>
                            <input type="text" class="form-control" v-model="donor" v-validate:donor="{required: true}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" :class="{ 'has-error': errors.has('donor')}">
                            <label>Company Name</label>
                            <input type="text" class="form-control" v-model="company_name">
                        </div>
                    </div>
                    <hr class="divider sm inv">
                    <div class="row">
                        <div class="col-sm-12">
                            <label><input type="checkbox" v-model="anonymous"> Give Anonymously</label>
                        </div>
                    </div>
                    <hr class="divider inv sm">
                    <div class="row" v-if="!child">
                        <div class="col-sm-12 text-center">
                            <div class="form-group" style="margin-bottom:0;">
                                <div class="">
                                    <!--<a @click="goToState('form')" class="btn btn-default">Reset</a>-->
                                    <a @click="nextState()" class="btn btn-primary">Next <i style="margin-left:3px;font-size:.8em;vertical-align:middle;" class="fa fa-chevron-right"></i></a>
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
                            <div style="margin-bottom:0;" class="form-group" :class="{ 'has-error': errors.has('cardholdername') }">
                                <label for="cardHolderName">Card Holder's Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon input input-sm"><span class="fa fa-user"></span></span>
                                    <input type="text" class="form-control input input-sm" id="cardHolderName" placeholder="Name on card"
                                               v-model="cardHolderName" v-validate:cardHolderName="{ required: true }" autofocus/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div style="margin-bottom:0;" class="form-group" :class="{ 'has-error': errors.has('cardnumber') || validationErrors.cardNumber }">
                                <label for="cardNumber">Card Number</label>
                                <div class="input-group">
                                    <span class="input-group-addon input input-sm"><span class="fa fa-lock"></span></span>
                                    <input type="text" class="form-control input input-sm" id="cardNumber" placeholder="Valid Card Number"
                                           v-model="cardNumber" v-validate:cardNumber="{ required: true, maxlength: 19 }"
                                           @keyup="formatCard($event)" maxlength="19"/>
                                </div>
                                <span class="help-block" v-if="validationErrors.cardNumber=='error'">{{stripeError.message}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label style="display:block;margin-left: 10px;margin-top:6px;" for="expiryMonth">Exp Date</label>
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('month') || validationErrors.cardMonth }">
                                <select v-model="cardMonth" class="form-control input input-sm" id="expiryMonth" v-validate:month="{ required: true }">
                                    <option v-for="month in monthList" :value="month">{{month}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('year') || validationErrors.cardYear }">
                                <select v-model="cardYear" class="form-control input input-sm" id="expiryYear" v-validate:year="{ required: true }">
                                    <option v-for="year in yearList" :value="year">{{year}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div :class="{ 'has-error': errors.has('code') || validationErrors.cardCVC }">
                                <label for="cvCode">
                                    CV CODE</label>
                                <input type="text" class="form-control input input-sm" id="cvCode" maxlength="4" v-model="cardCVC"
                                       placeholder="CV" v-validate:code="{ required: true, minlength: 3, maxlength: 4 }"/>
                                <span class="help-block" v-if= "errors.has('code') || validationErrors.cardCVC">{{stripeError ? stripeError.message : 'Invalid CVC number'}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div :class="{ 'has-error': errors.has('email') }">
                                <label for="infoEmailAddress">Billing Email</label>
                                <input type="text" class="form-control input input-sm" v-model="cardEmail" v-validate:email="['oneOrOther']" id="infoEmailAddress">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': errors.has('phone') }">
                                <label for="infoPhone">Billing Phone</label>
                                <input type="tel" class="form-control input input-sm" v-model="cardPhone | phone" v-validate:phone="['oneOrOther']" id="infoPhone">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': errors.has('zip') }">
                                <label for="infoZip">ZIP</label>
                                <input type="text" class="form-control input input-sm" v-model="cardZip" v-validate:zip="{ required: true }" id="infoZip" placeholder="12345">
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv">
                    <div class="col-sm-12 text-center" v-if="!child">
                        <div class="form-group" style="margin-bottom:0;">
                            <div class="">
                                <!--<a @click="goToState('form')" class="btn btn-default">Reset</a>-->
                                <a @click="createToken" class="btn btn-primary">Review Donation <i style="margin-left:3px;font-size:.8em;vertical-align:middle;" class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </template>
            </form>
            <div v-show="donationState === 'review'">
                <spinner ref="donationSpinner" size="xl" :fixed="false" text="Processing Donation"></spinner>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <label>Donation Amount</label>
                        <h3 class="text-success" style="margin-top:0px;">{{amount|currency}}</h3>
                        <label>Donation will go to</label>
                        <p>{{recipient}}</p>
                        <label>Who can see this?</label>
                        <p style="margin-bottom:0;">{{anonymous ? 'This donation is anonymous.' : 'This donation is public.'}}</p>
                    </div>
                    <div class="col-sm-12">
                        <hr class="divider inv">
                        <div class="panel panel-default" style="border-color:#05ce7b;">
                            <div class="panel-heading" style="background-color:#05ce7b;border-color:#05ce7b;">
                                <h6 style="font-size:.6em;color:#3c763d;text-transform:uppercase;letter-spacing:1px;margin-top:0;margin-bottom:0;"><i class="fa fa-lock icon-left" style="font-size:1.3em;vertical-align:middle;"></i> Secure Information</h6>
                            </div><!-- end panel-heading -->
                            <div class="panel-body" style="padding:10px;background:#f7f7f7;border-radius:0 0 4px 4px;">
                                <label>Card Holder Name</label>
                                <p class="small" style="margin-top:0px;margin-bottom:0;">{{cardHolderName}}</p>
                                <label>Card Number</label>
                                <p class="small" style="margin-top:0px;margin-bottom:0;">{{cardNumber}}</p>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Card Exp</label>
                                        <p class="small" style="margin-top:0px;margin-bottom:0;">{{cardMonth}}/{{cardYear}}</p>
                                    </div>
                                    <div class="col-xs-8">
                                        <label>Billing Email</label>
                                        <p class="small" style="margin-top:0px;margin-bottom:0;">{{cardEmail}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Billing Zip</label>
                                        <p class="small" style="margin-top:0px;margin-bottom:0;">{{cardZip}}</p>
                                    </div>
                                </div>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                        <p style="color:#808080;font-size:9px;" class="list-group-item-text">
                            <b>Disclaimer:</b>
                            All Missions.Me donations and support are considered 501(c)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.
                        </p>
                        <hr class="divider inv sm">
                    </div>
                </div>
                <div class="text-center" v-if="!child">
                    <a @click="submit" class="btn btn-primary">Donate</a>
                    <a @click="goToState('form')"><h6 class="text-uppercase" style="color:#808080;"><i class="fa fa-refresh icon-left"></i> Reset</h6></a>
                </div>
            </div>
            <div class="" v-show="donationState === 'confirmation'">
                <div class="text-center">
                    <img class="img-md" src="/images/donate/donation-check.png" alt="Donation Confirmed">
                    <h3 style="color:#808080;margin-bottom:0;">Donation Sent</h3>
                    <h5 style="color:#808080;margin-bottom:25px;">Thank you for your generosity!</h5>
                </div>
                <!--<div class="panel-footer" v-if="!child">
                    <a @click="done" class="btn btn-success">Close</a>
                </div>-->
            </div>
        </validator>
    </div>
</template>
<script type="text/javascript">
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
                anonymous: false,

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
                    zip: this.cardZip
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
                    this.attemptSubmit = !0;
                    this.attemptedCreateToken = !0;
                    this.stripeDeferred.reject(false);
                } else {
                    // reset errors
                    this.cardError = null;
                    this.validationErrors = {
                        cardNumber: '',
                        cardCVC: '',
                        cardYear: '',
                        cardMonth: '',
                        cardZip: ''
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
                    anonymous: this.anonymous,
                    currency: 'USD', // determined from card token
                    description: 'Donation to ' + this.title,
                    comment: null,
                    fund_id: this.fundId,
                    token: this.token,
                    details: {
                        type: 'card'
                    },
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
                                this.attemptedCreateToken = !0;
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
        mounted() {
            this.$dispatch('payment-complete', true);
            if (this.devMode) {
                this.cardNumber = '';
                this.cardCVC = '';
                this.cardYear = '2019';
                this.cardMonth = '01';
            }

            if (parseInt(this.auth)) {
                this.$http.get('users/me').then(function (response) {
                    this.donor = response.body.data.name;
                    this.donor_id = response.body.data.donor_id || null;
                    this.cardHolderName = response.body.data.name;
                    this.cardEmail = response.body.data.email;
                    this.cardPhone = response.body.data.phone_one;
                    this.cardZip = response.body.data.zip
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