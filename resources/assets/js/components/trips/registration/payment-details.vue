<template >
	<div class="row">
		<div class="col-sm-12">
			<h4>Payment Details</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<ul class="list-group">
						<li class="list-group-item">
							Item
							<span class="pull-right">Cost</span>
						</li>
						<li class="list-group-item" v-for="cost in staticCosts">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{currency(cost.amount)}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed)}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item" v-for="cost in incrementalCosts">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{currency(cost.amount)}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed)}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item">
							<h5 class="list-group-item-heading">
								{{selectedOptions.name}}
								<span class="pull-right">{{currency(selectedOptions.amount)}}</span>
								<hr class="divider sm inv">
								<p class="small">{{selectedOptions.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in selectedOptions.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed)}}</td>
									</tr>
								</tbody>
							</table>
						</li>
					</ul>

					<table class="table table-hover">
						<tfoot>
							<tr>
								<td style="border-top:2px solid #000000;">Fundraising Goal</td>
								<td style="border-top:2px solid #000000;" class="text-right">{{currency(totalCosts)}}</td>
							</tr>
							<tr>
								<td>Up-front Charges</td>
								<td class="text-danger text-right">-{{currency(upfrontTotal)}}</td>
							</tr>
							<tr v-if="promoValid">
								<td>Promo Credit</td>
								<td class="text-danger text-right">{{-currency(promoValid)}}</td>
							</tr>
							<tr>
								<td style="border-top:2px solid #000000;"><h5>Total to Raise</h5></td>
								<td style="border-top:2px solid #000000;"><h5 class="text-success text-right">{{ promoValid ? fundraisingGoal - promoValid : currency(fundraisingGoal)}}</h5></td>
							</tr>

						</tfoot>
					</table>
				</div>
				<hr class="divider" />
				<div class="col-md-12">
					<div class="form-group" :class="{ 'has-error': promoValid === false && promoError, 'has-success': promoValid > 0 }">
						<label for="cardHolderName">Promo Code</label>
						<div class="input-group">
							<span class="input-group-addon input-sm"><span class="fa" :class="{'fa-check' : promoValid, 'fa-times' : promoError !== '' && !promoValid, 'fa-gift': !promoValid && promoError === ''}"></span></span>
							<input type="text" class="form-control input-sm" id="promo" placeholder=""
						       v-model="promo" />
							<span class="input-group-btn">
						        <button class="btn btn-default btn-sm" type="button" @click.prevent="checkPromo">Apply</button>
							</span>
						</div>
						<div class="help-block" v-if="promoError" v-text="promoError"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div id="paymentAlerts" v-if="$parent.paymentErrors.length > 0">
						<div v-for="error in $parent.paymentErrors" class="alert alert-danger alert-dismissible"
						     role="alert">
							{{ error }}
						</div>
					</div><!-- end alert -->
					<div class="well" v-if="upfrontTotal > 0">
						<form novalidate role="form" id="StripeForm">
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group" :class="{ 'has-error': errors.has('cardholdername') }">
										<label for="cardHolderName">Card Holder's Name</label>
										<div class="input-group">
											<span class="input-group-addon input-sm"><span class="fa fa-user"></span></span>
											<input type="text" class="form-control input-sm" id="cardHolderName" placeholder="Name on card"
												   v-model="cardHolderName" name="cardholdername" v-validate="'required'" autofocus/>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group" :class="{ 'has-error': errors.has('email') }">
										<label for="infoEmailAddress">Billing Email Address</label>
										<input type="text" class="form-control input-sm" v-model="cardEmail" name="email" id="infoEmailAddress" v-validate="'required|email'">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">
										<label for="">Card</label>
										<div id="card-element" class="field"></div>
									</div>
								</div>
							</div>

							<p class="help-block text-success">Your card will be charged for the upfront fees
								immediately after your trip registration process is complete to secure your spot on this trip.</p>

							<div class="alert" :class="{ 'alert-warning': !$parent.detailsConfirmed, 'alert-success': $parent.detailsConfirmed }" v-if="$parent.paymentErrors.length > 0">
								<label for="errorConfirm">
									<input id="errorConfirm" type="checkbox" v-model="$parent.detailsConfirmed"> I have confirmed my payment details
								</label>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style>
	#StripeForm .field {
		display: block;
		height: 34px;
		padding: 8px 6px;
		font-size: 14px;
		color: #555555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
		transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
	}
</style>
<script type="text/javascript">
	export default{
		name: 'payment-details',
        props: {
            showButton: {
                'default': true
            },
            callback: {
                required: false
            },
            showLabels: {
                'default': false
            },
            devMode: {
                'default': true
            }
        },
		data(){
			return {
			    handle: 'PaymentDetails',
				title: 'Payment Details',
				paymentComplete: false,
				//staticCosts: [],
				//incrementalCosts: [],
				//selectedOptions: [],
				//upfrontTotal:0,
				//totalCosts: 0,
				attemptedCreateToken: false,
				promo: '',
                promoValid: false,
                promoError: '',

				//card vars
				card: null,
				cardHolderName: null,
				cardNumber: '',
				cardMonth: '',
				cardYear: '',
				cardCVC: '',
				cardEmail: null,
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
				// deferred variable used for card validation
				// needs to be on `this` scope to access in response callback
				stripe: window.Stripe(this.$parent.stripeKey),
				stripeDeferred: {},
                cardElement: null,
			}
		},
        computed: {
            fundraisingGoal(){
                return this.totalCosts - this.upfrontTotal;
            },
            staticCosts(){
                return this.$parent.tripCosts.static || [];
            },
            incrementalCosts(){
                return this.$parent.tripCosts.incremental || [];
            },
            selectedOptions(){
                return this.$parent.selectedOptions || null;
            },
            totalCosts(){
                let amount = 0;
                // add static costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
//					this.selectedOptions.forEach(function (cost) {
                    amount += parseFloat(this.selectedOptions.amount);
//					});
                }

                // add incremental costs if they exists
                if (this.incrementalCosts && this.incrementalCosts.constructor === Array) {
                    this.incrementalCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }

                return amount;
            },
            upfrontTotal(){
                let amount = 0;
                // add static costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        cost.payments.data.forEach(function (payment) {
                            if (payment.upfront) {
                                amount += parseFloat(payment.amount_owed);
                            }
                        });

                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
                    //this.selectedOptions.forEach(function (cost) {
                    this.selectedOptions.payments.data.forEach(function (payment) {
                        if (payment.upfront) {
                            amount += parseFloat(payment.amount_owed);
                        }
                    });
                    //});
                }

                // add incremental costs if they exists
                if (this.incrementalCosts && this.incrementalCosts.constructor === Array) {
                    this.incrementalCosts.forEach(function (cost) {
                        cost.payments.data.forEach(function (payment) {
                            if (payment.upfront) {
                                amount += parseFloat(payment.amount_owed);
                            }
                        });
                    });
                }
                this.$parent.upfrontTotal = amount;
                return amount;
            },
            yearList() {
                let num, today, years, yyyy;
                today = new Date;
                yyyy = today.getFullYear();
                years = (() =>  {
                    let i, ref, ref1, results;
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
            },
        },
		watch: {
			'paymentComplete'(val, oldVal) {
                this.$emit('step-completion', val);
            },
			'$parent.detailsConfirmed'(val, oldVal) {
			    if (val !== oldVal && this.$parent.paymentErrors.length > 0) {
                    this.$emit('step-completion', val);
                }
			},
			'promo' (val, oldVal) {
                this.promoError = '';
            }
		},
		events: {
			'VueStripe::create-card-token'()  {
				return this.createToken();
			},
			'VueStripe::reset-form'()  {
				return this.resetCaching();
			},
            'payment-complete'(val, oldVal) {
			    if (this.$parent.paymentErrors.length > 0) {
                    this.$parent.detailsConfirmed = val;
                }

            },
            '$parent.paymentErrors'(val) {
                if (val.length > 0) {
                    this.$parent.detailsConfirmed = false;
                }
            }
		},
        methods: {
            toDate(date){
                if(date) {
                    return moment(date).format('LL');
                } else {
                    return 'Now';
                }
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
                let output;
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
            checkPromo(){
                this.$http.post('trips/'+ this.$parent.tripId +'/promo', {promocode: this.promo} ).then((response) => {
                    this.promoValid = parseInt(response.data.replace(/,+/, ''));
                }, function(error) {
                    this.promoError = error.data.message;
                    this.promoValid = false;
                });
            },
            createToken() {
                this.stripeDeferred = $.Deferred();
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.attemptedCreateToken = true;
                        this.stripeDeferred.reject(false);
                    } else {
                        this.$parent.$refs.validationSpinner.show();
                        let extraDetails = {
                            name: this.cardHolderName,
                        };
                        this.stripe
	                        .createToken(this.cardElement, extraDetails)
	                        .then(this.createTokenCallback);

                    }
                });

                return this.stripeDeferred.promise();
            },
            createTokenCallback(result) {
                //console.log(status);
                console.log(result);

                if (result.token) {
                    this.$parent.paymentInfo = {
                        token: result.token.id,
	                    card: result.token.card,
                        save: this.cardSave,
                        email: this.cardEmail,
                        address_zip: this.cardZip
                    };
                    this.$parent.upfrontTotal = this.upfrontTotal;
                    this.$parent.fundraisingGoal = this.fundraisingGoal;
                    this.$parent.promocode = this.promoValid ? this.promo : null;
                    this.stripeDeferred.resolve(true);
                } else if (result.error) {
                    this.$root.$emit('showError', result.error.message);
                    this.$parent.$refs.validationSpinner.hide();
                }
            },
            setOutcome(result) {
                if (result.error) {
                    this.$root.$emit('showError', result.error.message);
                }
            }
        },
        mounted() {
		    // Stripe script
            let elements = this.stripe.elements();
            this.cardElement = elements.create('card', {
                style: {
                    /*base: {
                        iconColor: '#666EE8',
                        color: '#31325F',
                        lineHeight: '40px',
                        fontWeight: 300,
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSize: '15px',

                        '::placeholder': {
                            color: '#CFD7E0',
                        },
                    },*/
                }
            });
            this.cardElement.mount('#card-element');

            this.cardElement.on('change', (event) => {
                this.setOutcome(event);
            });

            this.$emit('step-completion', true);

            if (this.devMode) {
				this.cardNumber = '';
				this.cardCVC = '';
				this.cardYear = '2019';
				this.cardMonth = '1';
			}


		},
		activated(){
			$('html, body').animate({scrollTop : 200},300);
		}
	}
</script>
