<template >
	<div class="row">
		<div class="col-sm-12">
            <address>
                <strong>{{userInfo.firstName}} {{userInfo.lastName}}</strong><br>
                {{userInfo.address}}<br>
                {{userInfo.city}}, {{userInfo.state}} {{userInfo.zipCode}}<br>
                {{userInfo.country_name}}<br>
            </address>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Date of Birth</label><p>{{userInfo.dob}}</p>
                    </div><!-- end col -->
                    <div class="col-sm-3">
                        <label>Gender</label><p>{{ userInfo.gender|capitalize }}</p>
                    </div><!-- end col -->
                    <div class="col-sm-3">
                        <label>Relationship Status</label><p>{{ userInfo.relationshipStatus|capitalize }}</p>
                    </div><!-- end col -->
                    <div class="col-sm-3">
                        <label>Role</label><p>{{userInfo.desired_role.name}}</p>
                    </div><!-- end col -->
                </div><!-- end row -->
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <label><i class="fa fa-phone"></i> Phone</label><p>{{userInfo.phone}}</p>
                    </div>
                    <div class="col-sm-4">
                        <label><i class="fa fa-mobile"></i> Mobile</label><p>{{userInfo.mobile}}</p>
                    </div>
                    <div class="col-sm-4">
                        <label><i class="fa fa-envelope"></i> Email</label><p>{{userInfo.email}}</p>
                    </div><!-- end col -->
                </div><!-- end row -->

            <hr class="divider">
			<div class="row">
				<div class="col-md-12">
                    <h4>
                        Total Amount Due Now
                        <span class="pull-right text-primary">
                            {{currency(upfrontTotal, '$', 'USD')}}
                        </span>
                    </h4>
                    <p class="small text-muted">This amount is non-refundable and required to secure your spot on the trip.</p>
                    <hr class="divider">
				</div>
				<div class="col-md-12">
                    <h5>Payment Details</h5>
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
											<span class="input-group-addon"><span class="fa fa-user"></span></span>
											<input type="text" class="form-control" id="cardHolderName" placeholder="Name on card"
												   v-model="cardHolderName" name="cardholdername" v-validate="'required'" autofocus/>
										</div>
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
		height: 47px;
		padding: 12px 18px;
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
				attemptedCreateToken: false,

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
            userInfo(){
				return this.$parent.userInfo;
			},
            fundraisingGoal(){
                return this.totalCosts - this.upfrontTotal;
            },
            staticCosts(){
                return this.$parent.tripCosts.static || [];
            },
            incrementalCost(){
                return this.$parent.tripCosts.incremental || [];
            },
            upfrontCosts(){
                return this.$parent.tripCosts.upfront || [];
            },
            selectedOptions(){
                return this.$parent.selectedOptions || null;
            },
            totalCosts(){
                let amount = 0;
                // add upfront costs if they exists
				if(this.upfrontCosts && this.upfrontCosts.constructor === Array) {
                    this.upfrontCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
				// add additional costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
                    amount += parseFloat(this.selectedOptions.amount);
                }
                
				// add the registration cost
				amount += parseFloat(this.incrementalCost.amount);

                return amount;
            },
            upfrontTotal(){
                let amount = 0;
                if(this.upfrontCosts && this.upfrontCosts.constructor === Array) {
                    this.upfrontCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
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
			this.$nextTick(function () {
				let elements = this.stripe.elements();
				this.cardElement = elements.create('card', {
					style: {

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
			});
        },
		activated(){
			$('html, body').animate({scrollTop : 0},300);
		}
	}
</script>
