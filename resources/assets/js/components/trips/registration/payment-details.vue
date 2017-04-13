<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
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
								<span class="pull-right">{{cost.amount | currency}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{p.amount_owed | currency}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item" v-for="cost in incrementalCosts">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{cost.amount | currency}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{p.amount_owed | currency}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item" v-for="cost in selectedOptions">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{cost.amount | currency}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{p.amount_owed | currency}}</td>
									</tr>
								</tbody>
							</table>
						</li>
					</ul>

					<table class="table table-hover">
						<tfoot>
							<tr>
								<td style="border-top:2px solid #000000;">Fundraising Goal</td>
								<td style="border-top:2px solid #000000;" class="text-right">{{totalCosts | currency}}</td>
							</tr>
							<tr>
								<td>Up-front Charges</td>
								<td class="text-danger text-right">{{-upfrontTotal | currency}}</td>
							</tr>
							<tr v-if="promoValid">
								<td>Promo Credit</td>
								<td class="text-danger text-right">{{-promoValid | currency}}</td>
							</tr>
							<tr>
								<td style="border-top:2px solid #000000;"><h5>Total to Raise</h5></td>
								<td style="border-top:2px solid #000000;"><h5 class="text-success text-right">{{ promoValid ? fundraisingGoal - promoValid : fundraisingGoal | currency}}</h5></td>
							</tr>

						</tfoot>
					</table>
				</div>
				<hr class="divider" />
				<div class="col-md-12">
					<div class="form-group" :class="{ 'has-error': promoValid === false && promoError, 'has-success': promoValid > 0 }">
						<label for="cardHolderName">Promo Code</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="promo" placeholder=""
						       v-model="promo" debounce="250" />
							<span class="input-group-addon input-sm"><span class="fa" :class="{'fa-check' : promoValid, 'fa-times' : promo !== '' && !promoValid, 'fa-gift': promo === ''}"></span></span>
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
						<validator name="PaymentDetails">
							<form novalidate role="form">
								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group" :class="{ 'has-error': checkForError('cardholdername') }">
											<label for="cardHolderName">Card Holder's Name</label>
											<div class="input-group">
												<span class="input-group-addon input-sm"><span class="fa fa-user"></span></span>
												<input type="text" class="form-control input-sm" id="cardHolderName" placeholder="Name on card"
													   v-model="cardHolderName" v-validate:cardHolderName="{ required: true }" autofocus/>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group" :class="{ 'has-error': checkForError('cardnumber') || validationErrors.cardNumber }">
											<label for="cardNumber">Card Number</label>
											<div class="input-group">
												<span class="input-group-addon input-sm"><span class="fa fa-lock"></span></span>
												<input type="text" class="form-control input-sm" id="cardNumber" placeholder="Valid Card Number"
													   v-model="cardNumber" v-validate:cardNumber="{ required: true, maxlength: 19 }"
													   @keyup="formatCard($event)" maxlength="19"/>
											</div>
											<span class="help-block" v-if="validationErrors.cardNumber=='error'">{{stripeError.message}}</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-7 col-md-7">
										<label for="expiryMonth">EXPIRY DATE</label>
										<div class="row">
											<div class="col-xs-6 col-lg-6">
												<div class="form-group" :class="{ 'has-error': checkForError('month') || validationErrors.cardMonth }">
													<select v-model="cardMonth" class="form-control input-sm" id="expiryMonth" v-validate:month="{ required: true }">
														<option v-for="month in monthList" value="{{month}}">{{month}}</option>
													</select>
												</div>
											</div>
											<div class="col-xs-6 col-lg-6">
												<div class="form-group" :class="{ 'has-error': checkForError('year') || validationErrors.cardYear }">
													<select v-model="cardYear" class="form-control input-sm" id="expiryYear" v-validate:year="{ required: true }">
														<option v-for="year in yearList" value="{{year}}">{{year}}</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-5 col-md-5 pull-right">
										<div class="form-group" :class="{ 'has-error': checkForError('code') || validationErrors.cardCVC }">
											<label for="cvCode">
												CV CODE</label>
											<input type="text" class="form-control input-sm" id="cvCode" maxlength="4" v-model="cardCVC"
												   placeholder="CV" v-validate:code="{ required: true, minlength: 3, maxlength: 4 }"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-7">
										<div class="form-group" :class="{ 'has-error': checkForError('email') }">
											<label for="infoEmailAddress">Billing Email Address</label>
											<input type="text" class="form-control input-sm" v-model="cardEmail" v-validate:email="['email']" id="infoEmailAddress">
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group" :class="{ 'has-error': checkForError('zip') }">
											<label for="infoZip">Billing ZIP/Postal Code</label>
											<input type="text" class="form-control input-sm" v-model="cardZip" v-validate:zip="{ required: true }" id="infoZip" placeholder="12345">
										</div>
									</div>
									<!--<div class="col-sm-12">-->
										<!--<div class="checkbox">-->
											<!--<label>-->
												<!--<input type="checkbox" v-model="cardSave">Save payment details for next time.-->
											<!--</label>-->
										<!--</div>-->
									<!--</div>-->
								</div>

								<p class="help-block text-success">Your card will be charged for the upfront fees
									immediately after your trip registration process is complete to secure your spot on this trip.</p>

								<div class="alert" :class="{ 'alert-warning': !$parent.detailsConfirmed, 'alert-success': $parent.detailsConfirmed }" v-if="$parent.paymentErrors.length > 0">
									<label for="errorConfirm">
										<input id="errorConfirm" type="checkbox" v-model="$parent.detailsConfirmed"> I have confirmed my payment details
									</label>
								</div>
							</form>
						</validator>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'payment-details',
		data(){
			return {
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
				stripeKey: null,
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
				stripeDeferred: {},
			}
		},
		watch: {
			'paymentComplete'(val, oldVal) {
				this.$dispatch('payment-complete', val)
			},
			'$parent.detailsConfirmed'(val, oldVal) {
			    if (val !== oldVal && this.$parent.paymentErrors.length > 0) {
                    this.$dispatch('payment-complete', val);
			    }
			},
			'promo' (val, oldVal) {
                this.promoError = '';
                this.$http.post('trips/'+ this.$parent.tripId +'/promo', {promocode: val} ).then(function (response) {
	                this.promoValid = parseInt(response.body.replace(/,+/, ''));
                }, function(error) {
                    this.promoError = error.body.message;
                    this.promoValid = false;
                })
            }
		},
		events: {
			'VueStripe::create-card-token': function () {
				return this.createToken();
			},
			'VueStripe::reset-form': function () {
				return this.resetCaching();
			},
            'payment-complete': function (val) {
			    if (this.$parent.paymentErrors.length > 0) {
                    this.$parent.detailsConfirmed = val;
//                this.$dispatch('payment-complete', val)
                }

            },
            '$parent.paymentErrors'(val) {
                if (val.length > 0) {
                    this.$parent.detailsConfirmed = false;
                }
            }
		},
		ready: function () {
			this.$dispatch('payment-complete', true);
			if (this.devMode) {
				this.cardNumber = '';
				this.cardCVC = '';
				this.cardYear = '2019';
				return this.cardMonth = '1';
			}
		},
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
		computed: {
			stripeKey() {
				return this.$parent.stripeKey || null;
			},
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
				return this.$parent.selectedOptions || [];
			},
			totalCosts(){
				var amount = 0;
				// add static costs if they exists
				if(this.staticCosts && this.staticCosts.constructor === Array) {
					this.staticCosts.forEach(function (cost) {
						amount += parseFloat(cost.amount);
					});
				}
				// add optional costs if they exists
				if (this.selectedOptions && this.selectedOptions.constructor === Array) {
					this.selectedOptions.forEach(function (cost) {
						amount += parseFloat(cost.amount);
					});
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
				var amount = 0;
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
				if (this.selectedOptions && this.selectedOptions.constructor === Array) {
					this.selectedOptions.forEach(function (cost) {
						cost.payments.data.forEach(function (payment) {
							if (payment.upfront) {
								amount += parseFloat(payment.amount_owed);
							}
						});
					});
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
			},
		},
		methods: {
			onValid(){
				//this.$dispatch('payment-complete', true)
			},
			onInvalid(){
				// for now allow to continue
				//this.$dispatch('payment-complete', true)
			},
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
			checkForError(field){
				return this.$PaymentDetails[field.toLowerCase()].invalid && this.attemptedCreateToken
			},
			createToken() {
				this.stripeDeferred = $.Deferred();

				if (this.$PaymentDetails.invalid) {
					this.attemptedCreateToken = true;
					this.stripeDeferred.reject(false);
				} else {
//					Stripe.setPublishableKey(this.stripeKey);
//					Stripe.card.createToken(this.cardParams, this.createTokenCallback);
					this.$parent.$refs.validationspinner.show();

					this.$http.post('donations/authorize', this.cardParams)
							.then(this.createTokenCallback,
									function (error) {
										this.$dispatch('showError', error.data.message);
										this.$parent.$refs.validationspinner.hide();
									});
				}
				return this.stripeDeferred.promise();
			},
			createTokenCallback(resp) {
				//console.log(status);
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
				if (resp.status === 200) {
					this.card = this.cardParams;
					// send payment data to parent
					this.$parent.paymentInfo = {
						token: resp.data,
						card: this.cardParams,
						save: this.cardSave,
						email: this.cardEmail,
						address_zip: this.cardZip
					};
					this.$parent.upfrontTotal = this.upfrontTotal;
					this.$parent.fundraisingGoal = this.fundraisingGoal;
					this.$parent.promocode = this.promoValid ? this.promo : null;
					this.stripeDeferred.resolve(true);
				}
			}
		},
		activate(done){
			$('html, body').animate({scrollTop : 200},300);
			done();
		}
	}
</script>
