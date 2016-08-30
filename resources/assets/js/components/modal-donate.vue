<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>


        <a class="btn btn-primary btn-justified" @click="showModal=!showModal">Donate</a>
        <a class="btn btn-primary btn-justified" @click="showRight=!showRight">Donate</a>
        <hr class="divider inv sm">
        <modal title="Donate" :show.sync="showModal" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <donate :donation-state="donationState" :sub-state="subState" :attempt-submit="attemptSubmit" :child="true"></donate>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" @click="nextState()">Next</button>
            </div>
        </modal>

        <aside :show.sync="showRight" placement="right" header="Donate" :width="375">
            <donate></donate>
        </aside>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import vSelect from 'vue-select';
    import donateComponent from './donate.vue';
    export default{
        name: 'modal-donate',
        components:{ donate: donateComponent, vSelect, 'aside': VueStrap.aside, 'modal': VueStrap.modal },
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
                donationState: 'form',
                subState: 1,

                donor: '',
                amount: 1,

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
                showModal: false,
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
                years = (function () {
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
                    address_zip: this.cardZip,
                };
            }
        },
        methods: {
            nextState(){
                switch (this.donationState){
                    case 'form':
                        switch (this.subState) {
                            case 1:
                                this.subState = 2;
                                break;
                            case 2:
                                this.donationState = 'review';
                                this.subState = 1;
                                break
                        }
                        break;
                    case 'review':
                        break;
                }
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
            'VueStripe::create-card-token': function () {
                return this.createToken();
            },
            'VueStripe::reset-form': function () {
                return this.resetCaching();
            }
        },
        ready: function () {
            this.$dispatch('payment-complete', true);
            if (this.devMode) {
                this.cardNumber = '4242424242424242';
                this.cardCVC = '123';
                this.cardYear = '19';
                this.cardMonth = '1';
            }

            if (parseInt(this.auth)) {
                this.$http.get('users/me').then(function (response) {
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
