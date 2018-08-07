<template>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>{{ currentStep.name }}</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12" :class="currentStep.view">
					<spinner ref="validationSpinner" size="xl" :fixed="false" text="Validating"></spinner>
					<spinner ref="reservationspinner" size="xl" :fixed="true" text="Creating Reservation"></spinner>
					<keep-alive>
						<component :is="currentStep.view"  transition="fade" transition-mode="out-in" @step-completion="stepCompleted"></component>
					</keep-alive>

				</div>

			</div>
		</div>
		<div class="panel-footer text-right">
				<hr class="divider inv">
				<a class="btn btn-link pull-left" @click="backStep" :class="{'disabled': currentStep.view === 'step2' }">
					<i class="fa fa-angle-double-left"></i> Back
				</a>
				<a class="btn btn-primary" v-if="this.currentStep.view === 'payment'" @click="finish">Make Payment and Finish</a>
				<a class="btn btn-primary" v-else :class="{'disabled': !canContinue }" @click="nextStep">
					Next <i class="fa fa-angle-double-right"></i>
				</a>
				<hr class="divider inv">
		</div>
	</div>
</template>
<style>
	.fade-transition {
		transition: opacity .3s ease;
	}

	.fade-enter, .fade-leave {
		opacity: 0;
	}
</style>
<script type="text/javascript">
	import tos from './registration/tos.vue';
	import roca from './registration/roca.vue';
	import basicInfo from './registration/basic-info.vue';
	import additionalOptions from './registration/additional-trip-options.vue';
	import paymentAgreement from './registration/payment-agreement.vue';
	import payment from './registration/payment.vue';
	import deadlineAgreement from './registration/deadline-agreement.vue';
	import review from './registration/review.vue';
	export default{
		name: 'trip-registration-wizard',
		props: ['tripId', 'stripeKey'],
        components: {
            'step2': tos,
            'step3': roca,
            'step4': basicInfo,
            'step5': additionalOptions,
            'requirements': deadlineAgreement,
            'paymentAgreement': paymentAgreement,
            'payment': payment,
        },
        data(){
			return {
				stepList:[
					{name: 'Terms of Service Agreement', view: 'step2', complete:false},
					{name: 'Rules of Conduct Agreement', view: 'step3', complete:false},
					{name: 'Traveler Information', view: 'step4', complete:false},
					{name: 'Rooming Options', view: 'step5', complete:false},
					{name: 'Financial Agreement', view: 'paymentAgreement', complete:false},
					{name: 'Travel Requirements', view: 'requirements', complete:false},
                    {name: 'Review & Deposit', view: 'payment', complete:false},
                ],
				currentStep: null,
				trip: {},
				tripCosts: {},
				deadlines:[],
				requirements:[],
				wizardComplete: false,

				// user generated data
				selectedOptions: null,
				userInfo: {},
				paymentInfo: {},
				upfrontTotal: 0,
				fundraisingGoal: 0,
				paymentErrors:[],
                detailsConfirmed: false,
                rocaAgree: false,
                promocode: null,
			}
		},
		computed: {
			canContinue(){
				return this.currentStep.complete;
			},
            userData:{
			  get(val) {
                return this.$root.user;
			  },
			  set(val) {}
            }
		},
		methods: {
			toStep(step){
				if (step.complete) {
					this.currentStep = step;
				}
			},
			fallbackStep(step){
			    // negate last step completion
                this.wizardComplete = false;
                this.stepList[5].complete = false;

                // negate second last step completion
                this.stepList[4].complete = false;
                this.rocaAgree = false;

                // negate step completion
                this.currentStep = step;
                if (step.view === 'payment') {
                    this.detailsConfirmed = false;
                    this.$nextTick(() =>  {
                        this.currentStep.complete = false;
                        this.$emit('payment-complete', false);
                    });
                }

                this.$emit('review', false);
            },
			backStep(){
				this.stepList.some(function(step, i, list) {
                    if (this.currentStep.view === 'payment')
                        this.wizardComplete = false;

					if (this.currentStep.view === step.view){
						return this.currentStep = list[i-1];
					}
				}, this);
			},
			nextStep(){
				let thisChild;
				switch (this.currentStep.view) {
					case 'step4':
						// find child
						this.$children.forEach(function (child) {
							if (child.hasOwnProperty('handle') && child.handle === 'BasicInfo')
								thisChild = child;
						});

						// if form is invalid do not continue
                        thisChild.$validator.validateAll().then(result => {
                            if (!result) {
                                thisChild.attemptedContinue = true;
                                return false;
                            }
                            this.userInfo = thisChild.userInfo;
                            this.nextStepCallback();
                        });

						break;
					default:
						this.nextStepCallback();
				}
			},
			nextStepCallback(){
				this.stepList.some(function(step, i, list) {
					if (this.currentStep.view === step.view){
						list[i].complete = this.currentStep.complete;
						return this.currentStep = list[i+1];
					}
				}, this);
			},
            finish() {
              let thisChild;
              // find child
              if (this.upfrontTotal > 0) {
                this.$children.forEach(function (child) {
                  if (child.hasOwnProperty('handle') && child.handle === 'PaymentDetails')
                    thisChild = child;
                });
                // promise needed to wait for async response from stripe
                $.when(thisChild.createToken())
                  .done(success => {
                    this.$refs.validationSpinner.hide();
                    this.finishConfirmed();
                  });
              } else {
                this.finishConfirmed();
              }
            },
			finishConfirmed(){
                let data = {
                    // reservation data
                    height_a: this.userInfo.heightA,
                    height_b: this.userInfo.heightB,
                    weight: this.userInfo.weight,
                    desired_role: this.userInfo.desired_role.value,
                    given_names: this.userInfo.firstName,
                    surname: this.userInfo.lastName,
                    gender: this.userInfo.gender,
                    status: this.userInfo.relationshipStatus,
                    shirt_size: this.userInfo.size,
                    birthday: moment(this.userInfo.dobMonth + '-' + this.userInfo.dobDay + '-' + this.userInfo.dobYear, 'MM-DD-YYYY').format('YYYY-MM-DD'),
                    address: this.userInfo.address,
                    city: this.userInfo.city,
                    state: this.userInfo.state,
                    zip: this.userInfo.zipCode,
                    country_code: this.userInfo.country,
                    email: this.userInfo.email,
                    phone_one: this.userInfo.phone,
                    phone_two: this.userInfo.mobile,
                    user_id: this.userData.id,
                    avatar_upload_id: this.userInfo.avatar_upload_id,
//					trip_id: this.tripId,
                    companion_limit: this.companion_limit,
                    costs: this.prepareFinalCosts(),

                    // payment data
                    amount: this.upfrontTotal,
                    description: 'Reservation payment',
                    currency: 'USD', // determined from card token,

                    promocode: this.promocode,

                };
                this.$refs.reservationspinner.show();

				if (this.upfrontTotal > 0) {
				    _.extend(data, {
                        token: this.paymentInfo.token,
                        payment: {
                            type: 'card',
                            brand: this.paymentInfo.card.brand || 'visa',
                            last_four: this.paymentInfo.card.last4,
                            cardholder: this.paymentInfo.card.name,
                        }
				    });
				}

				if (this.userData && this.userData.donor_id) {
					data.donor_id = this.donor_id;
				} else {
					data.donor = {
						first_name: this.userInfo.firstName,
						last_name: this.userInfo.lastName,
						email: this.userInfo.email,
						phone: this.userInfo.phone,
						zip: this.userInfo.zipCode,
						country_code: this.userInfo.country || 'us'
					}
				}


				this.$http.post('trips/' + this.tripId + '/register', data).then((response) => {
//					this.stripeDeferred.resolve(true);
					this.$refs.reservationspinner.hide();

					window.location.href = '/dashboard/reservations/' + response.data.data.id;
					this.$refs.reservationspinner.hide();
				}, (error) =>  {
                    this.$refs.reservationspinner.hide();
                    console.log(error.response);
					this.$root.$emit('showError', error.response.data.message);
                    //this.fallbackStep(this.stepList[4]); // return to payment details step
					this.paymentErrors.push(error.response.data.message);
				});


			},
			prepareFinalCosts(){
			    let finalCosts = [];
			    let unionCosts = _.union(this.tripCosts.incremental, [this.selectedOptions], this.tripCosts.static, this.tripCosts.upfront);
			    _.each(unionCosts, (cost) => {
				    if (_.isObject(cost))
                        finalCosts.push(cost);
                });
			    return finalCosts;
			},
			stepCompleted(val) {
                this.currentStep.complete = val;
                if (this.currentStep.view === 'payment')
                    this.wizardComplete = val;
			},
		},
		created(){
			// set starting step
			this.currentStep = this.stepList[0];
		},
		mounted(){
            let self = this;
            //get trip costs
			let resource = this.$resource('trips{/id}', { include: 'priceables.cost,priceables.payments,requireables' });
			resource.query({id: this.tripId}).then((trip) => {
				this.trip = trip.data.data;
				// requirements, and companion_limit
				this.requirements =  trip.data.data.requirements;
				this.companion_limit = trip.data.data.companion_limit;

				// filter costs by type
				let optionalArr = [], staticArr = [], upfrontArr = [];
				this.trip.prices.forEach(function (price) {
					switch (price.cost.type) {
						case 'Upfront':
							upfrontArr.push(price);
							break;
						case 'Additional':
							staticArr.push(price);
							break;
						case 'Rooming':
							optionalArr.push(price);
							break;
					}
				});
				this.tripCosts = {
					static: staticArr,
					incremental: this.trip.current_rate,
					optional: optionalArr,
					upfront: upfrontArr,
				}
			});

			this.$root.$on('userHasLoggedIn', (val) =>  {
				// expecting userData object
				self.userData = val;
				self.currentStep.complete = !!val;
				// force next step
				self.nextStep();
			})
		},
	}
</script>