<template>
	<div>
		<form id="StripeForm" class="" name="DonationForm" novalidate v-show="donationState === 'form'">
			<spinner ref="validationSpinner" size="xl" :fixed="false" text="Validating"></spinner>
			<template v-if="isState('form', 1)">
				<div class="row">
					<div class="col-sm-offset-2 col-sm-8 col-xs-12 text-center">
						<label>Your Donation will go to:</label>
						<h4 style="font-weight:400;line-height:1.4;margin-top:0px;" v-text="recipient"></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-offset-2 col-sm-8 col-xs-12"
					     :class="{ 'has-error': errors.has('amount', 'form-1')}">
						<div class="text-center">
							<div class="btn-group btn-group-justified" data-toggle="buttons" v-if="child">
								<label class="btn"
								       :class="[ presetAmount === 10 ? 'btn-success': 'btn-success-hollow' ]">
									<input type="radio" name="amounts" id="amount15" autocomplete="off"
									       @click="presetAmount = 10"> $10
								</label>
								<label class="btn"
								       :class="[ presetAmount === 25 ? 'btn-success': 'btn-success-hollow' ]">
									<input type="radio" name="amounts" id="amount35" autocomplete="off"
									       @click="presetAmount = 25"> $25
								</label>
								<label class="btn"
								       :class="[ presetAmount === 50 ? 'btn-success': 'btn-success-hollow' ]">
									<input type="radio" name="amounts" id="amount50" autocomplete="off"
									       @click="presetAmount = 50"> $50
								</label>
							</div>
							<hr class="divider lg inv" v-if="child">
							<currency-input label="Enter Donation Amount" v-model="amount" min="1" name="amount"
							                data-vv-scope="form-1" style="font-size:22px;color:#05ce7b;"
							                v-validate="presetAmount ? '' : 'required|min:1'"></currency-input>
						</div>
					</div>
				</div>

				<hr class="divider lg inv">
				<div class="row" v-if="!child">
					<div class="col-sm-offset-2 col-sm-8 col-xs-12 text-center">
						<div class="form-group" style="margin-bottom:0;">
							<div class="">
								<a @click="nextState()" class="btn btn-success"><i class="fa fa-credit-card"></i> Pay with Card <i
										style="margin-left:3px;font-size:.8em;vertical-align:middle;"
										class="fa fa-chevron-right"></i></a>
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
						<div style="margin-bottom:0;" class="form-group"
						     :class="{ 'has-error': errors.has('cardHolderName', 'form-2') }">
							<label for="cardHolderName">Name on Card</label>
							<div class="input-group">
								<span class="input-group-addon input"><span class="fa fa-user"></span></span>
								<input type="text" class="form-control input" id="cardHolderName"
								       placeholder="Name on card"
								       v-model="cardHolderName" name="cardHolderName" data-vv-scope="form-2"
								       v-validate="'required'" autofocus/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<label for="">Card</label>
						<div id="card-element" class="field form-control"></div>
						<hr class="sm inv divider">
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						<div :class="{ 'has-error': errors.has('email', 'form-2') }">
							<label for="infoEmailAddress">Billing Email</label>
							<input type="text" class="form-control input input-sm" v-model="cardEmail" name="email"
							       id="infoEmailAddress" data-vv-scope="form-2"
							       v-validate="'required|email'">
						</div>
					</div>
					<div class="col-sm-6">
						<div :class="{ 'has-error': errors.has('phone', 'form-2') }">
							<label for="infoPhone">Billing Phone</label>
							<phone-input v-model="cardPhone" classes="form-control input input-sm" name="phone"
							             id="infoPhone" data-vv-scope="form-2"
							             v-validate="'required'"></phone-input>
						</div>
					</div>

				</div>
				<hr class="divider inv">
				<div class="col-sm-12 text-center" v-if="!child">
					<div class="form-group" style="margin-bottom:0;">
						<div class="">
							<a @click="createToken" class="btn btn-success">Review Donation <i
									style="margin-left:3px;font-size:.8em;vertical-align:middle;"
									class="fa fa-chevron-right"></i></a>
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
					<h1 class="text-success" style="margin:0 0 15px 0;">{{currency(presetAmount || amount)}}</h1>
					<label>Who can see this?</label>
					<p style="margin-bottom:0;">{{anonymous ? 'Anonymous' : 'Public'}}</p>
					<label><input type="checkbox" v-model="anonymous"> I'd Like To Give Anonymously</label>
				</div>
			</div><!-- end row -->
			<hr class="divider inv">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color:#e6e6e6;">
					<h6 style="font-size:.6em;color:#808080;text-transform:uppercase;letter-spacing:1px;margin-top:0;margin-bottom:0;">
						<i class="fa fa-lock icon-left" style="font-size:1.3em;vertical-align:middle;"></i>
						Secure Information</h6>
				</div><!-- end panel-heading -->
				<div v-if="card" class="panel-body" style="padding:10px;background:#f7f7f7;border-radius:0 0 4px 4px;">
					<div class="row">
						<div class="col-sm-5">
							<label>Card Holder Name</label>
							<p class="small" style="margin-top:0px;margin-bottom:0;">{{card.name}}</p>
						</div><!-- end col -->
						<div class="col-sm-4">
							<label>Card Number</label>
							<p class="small" style="margin-top:0px;margin-bottom:0;">&middot;&middot;&middot;&middot;
								&middot;&middot;&middot;&middot; &middot;&middot;&middot;&middot; {{card.last4}}</p>
						</div><!-- end col -->
						<div class="col-sm-3">
							<label>Card Exp</label>
							<p class="small" style="margin-top:0px;margin-bottom:0;">
								{{card.exp_month}}/{{card.exp_year}}</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-7">
							<label>Billing Email</label>
							<p class="small" style="margin-top:0px;margin-bottom:0;">{{cardEmail}}</p>
						</div>
						<div class="col-sm-6 col-md-5">
							<label>Billing Zip</label>
							<p class="small" style="margin-top:0px;margin-bottom:0;">{{card.address_zip}}</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-12"
								     :class="{ 'has-error': errors.has('donorfirst', 'review') || errors.has('donorlast', 'review')}">
									<label>Donor's Name</label>
									<div class="row">
										<div class="col-sm-6">
											<input type="text" class="form-control" v-model="donor.first_name" maxlength="100"
											       name="donorfirst"
											       data-vv-scope="review"
											       v-validate="'required|alpha_spaces|min:1'" placeholder="First Name">
											<p class="help-block">First Name</p>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" v-model="donor.last_name" maxlength="100"
											       name="donorlast"
											       data-vv-scope="review"
											       v-validate="'required|alpha_spaces|min:1'" placeholder="Last Name">
											<p class="help-block">Last Name</p>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<label>Company Name</label>
										<input type="text" class="form-control" placeholder="optional"
										       v-model="donor.company" maxlength="120">
									</div>
									<hr class="divider sm inv">
									<div class="col-sm-12">
										<hr class="divider sm">
										<label for="comment">Leave a Comment</label>
										<textarea name="comment" id="comment" v-autosize="comment" class="form-control"
										          v-model="comment" style="resize: vertical" maxlength="120"></textarea>
									</div>
								</div><!-- end row -->
							</div><!-- end col -->
						</div>
					</div>
				</div><!-- end panel-body -->
			</div><!-- end panel -->
			<hr class="divider inv sm">
			<p style="color:#808080;font-size:9px;" class="list-group-item-text">
				<b>Disclaimer:</b>
				All Missions.Me donations and support are considered 501(c)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.
			</p>
			<hr class="divider inv sm">
			<div class="text-center" v-if="!child">
				<a @click="submit" class="btn btn-primary">Donate</a>
				<a @click="goToState('form')"><h6 class="text-uppercase" style="color:#808080;"><i
						class="fa fa-refresh icon-left"></i> Reset</h6></a>
			</div>
		</div>
		<div class="" v-show="donationState === 'confirmation'">
			<div class="text-center">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<img class="img-md" src="/images/donate/donation-check.png" alt="Donation Confirmed">
						<h6 class="text-uppercase text-muted">Donation Sent</h6>
						<h3 style="margin:0px 0 15px 0;">Thank you for your generosity!</h3>
						<p v-if="child">We encourage sharing! This greatly improves the success of the fundraiser.</p>
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv sm">
				<div class="row" v-if="child">
					<div class="col-sm-6">
						<a style="margin-bottom:5px;" data-network="facebook" class="btn btn-facebook btn-block"><i
								class="fa fa-facebook icon-left"></i> Share</a>
					</div><!-- end col -->
					<div class="col-sm-6">
						<a data-network="twitter" class="btn btn-twitter btn-block"><i
								class="fa fa-twitter icon-left"></i> Tweet</a>
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv">
			</div>
		</div>
	</div>
</template>
<style>
	#StripeForm .field {
		display: block;
		height: 46px;
		padding: 14px 6px;
		font-size: 14px;
		color: #555555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #e6e6e6;
		border-radius: 4px;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
		transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
	}
</style>

<script type="text/javascript">
  import $ from 'jquery';
  import vSelect from 'vue-select';

  export default {
    name: 'donate',
    components: {vSelect},
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
        type: String,
        default: '0'
      },
      /*attemptSubmit: {
		  type: Boolean,
		  default: false
	  },*/
      child: {
        type: Boolean,
        default: false
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
    data() {
      return {
        donor: {
          first_name: null,
          last_name: null,
          company: null,
        },
        donor_id: null,
        company_name: '',
        amount: null,
        presetAmount: null,
        validateWith: 'email',
        token: null,
        anonymous: false,
        comment: null,
        paymentType: null,

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
        stripe: null,
        cardElement: null,
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
        attemptSubmit: false,
        donationState: 'form',
        subState: 1,
        attemptedCreateToken: false,
      }
    },
    // TODO : convert validator
    /*validators: {
		oneOrOther(val){
			return val === this.vm.cardEmail
					? (!val.length && this.vm.cardPhone && this.vm.cardPhone.length > 0) || (val.length > 0 && /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(val))
					: (!val.length && this.vm.cardEmail && this.vm.cardEmail.length > 0) || val.length > 0;
		}
	},*/
    watch: {
      amount(val, oldVal) {
        this.presetAmount = null;
      },
      'paymentComplete'(val, oldVal) {
        this.$emit('payment-complete', val)
      },
      'donationState'(val) {
//                console.log(val);
      },
      'subState'(val) {
//                console.log(val);
      },
      presetAmount(val) {
        if (!isNaN(val)) {
          this.amount = null
        }
      }
    },
    computed: {},
    methods: {
      isState(major, minor) {
        return this.donationState === major && this.subState === minor
      },
      toState(major, minor) {
        this.donationState = major;
        this.subState = minor;
        this.$emit('state-changed', this.donationState, this.subState);
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
        this.$validator.validateAll('form-2').then(result => {
          if (!result) {
            this.attemptSubmit = !0;
            this.attemptedCreateToken = !0;
          } else {
            this.$refs.validationSpinner.show();
            let extraDetails = {
              name: this.cardHolderName,
            };
            this.stripe
              .createToken(this.cardElement, extraDetails)
              .then(this.createTokenCallback);
          }
        });
      },
      createTokenCallback(result) {
        // console.log(result);
        if (result.token) {
          this.card = result.token.card;
          this.token = result.token.id;
          //this.stripeDeferred.resolve(result.token);
          this.$refs.validationSpinner.hide();
          this.goToState('review');
        } else if (result.error) {
          this.$root.$emit('showError', result.error.message);
          this.$refs.validationSpinner.hide();
        }
      },
      setOutcome(result) {
        if (result.error) {
          this.$root.$emit('showError', result.error.message);
        }
      },
      submit() {
        this.$validator.validateAll('review').then(result => {
          if (result) {
            let data = {
              amount: this.presetAmount || this.amount,
              anonymous: this.anonymous,
              currency: 'USD', // determined from card token
              description: 'Donation to ' + this.title,
              comment: this.comment,
              fund_id: this.fundId,
              token: this.token,
              details: {
                type: 'card'
              },
              payment: {
                type: 'card',
                brand: this.card.brand,
                last_four: this.card.last4,
                cardholder: this.card.name,
              }
            };
            this.$refs.donationSpinner.show();

            if (parseInt(this.auth) && this.donor_id) {
              data.donor_id = this.donor_id;
            } else {
              data.donor = this.donor;
              _.extend(data.donor, {
                email: this.cardEmail,
                phone: this.cardPhone,
                zip: this.card.address_zip,
                country_code: this.card.country.toLowerCase()
              })
            }
            this.$http.post('donations', data)
              .then((response) => {
                  // this.stripeDeferred.resolve(true);
                  this.$refs.donationSpinner.hide();
                  this.donationState = 'confirmation';
                  this.$nextTick(function () {
                    // Initialize ShareThis Buttons after view loads
                    window.__sharethis__.initialize();
                  });
                  this.$root.$emit('DonateForm:complete');

                  if (this.child) {
                    this.$http.get(`fundraisers?fund=${this.fundId}`).then((response) => {
                      let fundraiser = response.data.data[0];
                      this.$root.$emit('Fundraiser::raised', `$${fundraiser.raised_amount}`);
                      this.$root.$emit('Fundraiser::percent', fundraiser.raised_percent);
                    });
                  }

                  this.$emit('state-changed', this.donationState, this.subState);
                },
                (error) => {
                  this.$refs.donationSpinner.hide();
                  this.$root.$emit('showError', error.response.data.message);
//                            this.cardError = error.data.message;
                  this.toState('form', 2);
                  // Stripe script
                  this.$nextTick(function () {
                    this.stripe = window.Stripe(this.stripeKey);
                    let elements = this.stripe.elements();
                    this.cardElement = elements.create('card', {
                      style: {}
                    });
                    this.cardElement.mount('#card-element');

                    this.cardElement.on('change', (event) => {
                      this.setOutcome(event);
                    });
                  });
                });
          } else {
            this.$root.$emit('showError', 'Please check the form.');
          }
        });
      },
      goToState(state) {
        switch (state) {
          case 'form':
            this.donationState = state;
            this.$emit('state-changed', this.donationState, this.subState);
            break;
          case 'review':
            this.attemptSubmit = true;
            this.$validator.validateAll('form-2').then(result => {
              if (result) this.donationState = state;
              this.$emit('state-changed', this.donationState, this.subState);

            });
            break;
        }
      },
      nextState() {
        this.attemptSubmit = !0;
        switch (this.donationState) {
          case 'form':
            switch (this.subState) {
              case 1:
                this.$validator.validateAll('form-1').then(result => {
                  if (result) {
                    this.subState = 2;
                    this.attemptSubmit = !1;
                    this.$emit('state-changed', this.donationState, this.subState);

                    // Stripe script
                    this.$nextTick(function () {
                      this.stripe = window.Stripe(this.stripeKey);
                      let elements = this.stripe.elements();
                      this.cardElement = elements.create('card', {
                        style: {}
                      });
                      this.cardElement.mount('#card-element');

                      this.cardElement.on('change', (event) => {
                        this.setOutcome(event);
                      });
                    });

                  } else {
                    this.$root.$emit('showError', 'Please select/enter a donation amount');
                  }
                });
                break;
              case 2:
                this.attemptedCreateToken = !0;
                this.$validator.validateAll('form-2').then(result => {
                  if (result) {
                    this.donationState = 'review';
                    this.subState = 1;
                    this.attemptSubmit = !1;
                    this.$emit('state-changed', this.donationState, this.subState);
                  } else {
                    this.$root.$emit('showError', 'Please check the form.');
                  }
                });
                break
            }
            break;
          case 'review':
            break;
        }
      },
      prevState() {
        switch (this.donationState) {
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
        this.$emit('state-changed', this.donationState, this.subState);
      },
    },
    mounted() {
      this.$root.$on('VueStripe::reset-form', () => {
        return this.resetCaching();
      });

      this.$emit('payment-complete', true);

      if (parseInt(this.auth)) {
        this.$http.get('users/me').then((response) => {
          this.donor.first_name = response.data.data.first_name;
          this.donor.last_name = response.data.data.last_name;
          this.donor_id = response.data.data.donor_id || null;
          this.cardHolderName = response.data.data.name;
          this.cardEmail = response.data.data.email;
          this.cardPhone = response.data.data.phone_one;
          this.cardZip = response.data.data.zip
        });
      }

      //Listen to Event Bus
      this.$root.$on('DonateForm:nextState', () => {
        this.nextState();
      });

      this.$root.$on('DonateForm:prevState', () => {
        this.prevState();
      });

      this.$root.$on('DonateForm:resetState', () => {
        this.toState('form', 1);
      });

      this.$root.$on('DonateForm:reviewDonation', (identifier) => {
        if (identifier !== this.identifier) {
          return false;
        }

        this.createToken();
      });

      this.$root.$on('DonateForm:donate', (identifier) => {
        if (identifier !== this.identifier) {
          return false;
        }

        this.submit();
      });

    },
  }
</script>