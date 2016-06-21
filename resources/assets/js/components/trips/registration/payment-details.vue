<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<h4>Payment Details</h4>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<table class="table table-hover">
						<caption>Cost Summary</caption>
						<thead>
						<tr>
							<th>Item</th>
							<th>Cost</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="op in selectedOptions">
							<td>{{op.name}}</td>
							<td>{{op.amount | currency}}</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<validator name="PaymentDetails">
						<form novalidate role="form">
							<div class="form-group">
								<label for="cardHolderName">Card Holder's Name</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="fa fa-user"></span></span>
									<input type="text" class="form-control" id="cardHolderName" placeholder="Name on card"
										   v-model="cardHolderName" v-validate:cardHolderName="{ required: true }" autofocus/>
								</div>
							</div>
							<div class="form-group">
								<label for="cardNumber">Card Number</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="fa fa-lock"></span></span>
									<input type="text" class="form-control" id="cardNumber" placeholder="Valid Card Number"
										   v-model="cardNumber" v-validate:cardNumber="{ required: true, maxlength: 16 }"/>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-7 col-md-7">
									<div class="form-group">
										<label for="expiryMonth">EXPIRY DATE</label>
										<div class="row">
											<div class="col-xs-6 col-lg-6">
												<input type="text" class="form-control" id="expiryMonth" placeholder="MM"
													   v-model="cardMonth" v-validate:month="{ required: true, maxlength: 2 }"/>
											</div>
											<div class="col-xs-6 col-lg-6">
												<input type="text" class="form-control" id="expiryYear" placeholder="YY"
													   v-model="cardYear" v-validate:year="{ required: true, maxlength: 2 }"/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-5 col-md-5 pull-right">
									<div class="form-group">
										<label for="cvCode">
											CV CODE</label>
										<input type="password" class="form-control" id="cvCode" maxlength="2" v-model="cardCode"
											   placeholder="CV" v-validate:code="{ required: true, maxlength: 4 }"/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="infoEmailAddress">Billing Email Address</label>
								<input type="text" class="form-control input-sm" v-model="cardEmail" v-validate:email="['email']" id="infoEmailAddress">
							</div>

							<div class="form-group">
								<label for="infoZip">Billing ZIP/Postal Code</label>
								<input type="text" class="form-control input-sm" v-model="cardZip" v-validate:zipCode="{ required: true }" id="infoZip" placeholder="12345">
							</div>
						</form>

					</validator>
				</div>
			</div>



		</div>
	</div>
</template>
<script>
	export default{
		name: 'payment-details',
		data(){
			return {
				title: 'Payment Details',
				paymentComplete: false,
				selectedOptions: this.$parent.selectedOptions,
				//card vars
				cardHolderName: null,
				cardNumber: null,
				cardMonth: null,
				cardYear: null,
				cardCode: null,
				cardEmail: null,
				cardZip: null,

				// stripe vars
				stripeError: null,
				number: "",
				cvc: "",
				expYear: "",
				expMonth: "",
				monthList: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
				placeholders: {
					year: 'Year',
					month: 'Month',
					cvc: 'CVC',
					number: "Card Number"
				},
				validationErrors: {
					number: "",
					cvc: "",
					expYear: "",
					expMonth: ""
				}
			}
		},
		watch: {
			'paymentComplete'(val, oldVal) {
				this.$dispatch('payment-complete', val)
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
			if (this.devMode) {
				this.number = "4242424242424242";
				this.cvc = "123";
				this.expYear = "19";
				return this.expMonth = '1';
			}
		},
		props: {
			showButton: {
				"default": true
			},
			callback: {
				required: false
			},
			showLabels: {
				"default": false
			},
			devMode: {
				"default": false
			},
			shopUri: {
				required: false
			},
			card: {
				required: true,
				twoWay: true
			},
			stripeKey: {
				required: true
			}
		},
		computed: {
			yearIsPlaceholder: function () {
				return this.expYear.length === 0;
			},
			monthIsPlaceholder: function () {
				return this.expMonth.length === 0;
			},
			yearList: function () {
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
			cardParams: function () {
				return {
					number: this.number,
					expMonth: this.expMonth,
					expYear: this.expYear,
					cvc: this.cvc
				};
			}
		},
		methods: {
			onValid(){
				this.$dispatch('payment-complete', true)
			},
			onInvalid(){
				// for now allow to continue
				this.$dispatch('payment-complete', true)
			},
			resetCaching: function () {
				console.log("resetting ");
				this.expMonth = "";
				this.cvc = "";
				this.expYear = "";
				this.number = "";
				return this.card = null;
			},
			formatCard: function (event) {
				var output;
				output = this.number.split("-").join("");
				if (output.length > 0) {
					output = output.replace(/[^\d]+/g, '');
					output = output.match(new RegExp('.{1,4}', 'g'));
					if (output) {
						return this.number = output.join("-");
					} else {
						return this.number = "";
					}
				}
			},
			createToken: function () {
				Stripe.setPublishableKey(this.stripeKey);
				return Stripe.card.createToken(this.cardParams, this.createTokenCallback);
			},
			createTokenCallback: function (status, resp) {
				var uploadReq;
				this.validationErrors = {
					number: "",
					cvc: "",
					expYear: "",
					expMonth: ""
				};
				this.stripeError = resp.error;
				if (this.stripeError) {
					if (this.stripeError.param === "number") {
						this.validationErrors.number = "error";
					}
					if (this.stripeError.param === "exp_year") {
						this.validationErrors.expYear = "error";
					}
					if (this.stripeError.param === "exp_month") {
						this.validationErrors.expMonth = "error";
					}
					if (this.stripeError.param === "cvc") {
						this.validationErrors.cvc = "error";
					}
				}
				if (status === 200) {
					this.card = resp;
					if (this.callback) {
						this.callback();
					}
					if (this.shopUri) {
						return uploadReq = $.ajax({
							method: 'post',
							url: this.shopUri,
							contentType: 'application/json; charset=utf-8',
							dataType: "json",
							data: JSON.stringify({
								card: this.resp
							})
						});
					}
				}
			}
		}


	}
</script>
