<template>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h5>{{ trip.country_name }} Trip Registration</h5>
		</div>
		<div class="panel-body">
			<div class="row visible-xs-block">
				<div class="col-xs-12">
					<div class="btn-group btn-group-justified btn-group-xs" style="display:block;" role="group" aria-label="...">
						<a @click="backStep" class="btn btn-default" :class="{'disabled': currentStep.view === 'step1' }" role="button">
							<i class="fa fa-chevron-left"></i>
						</a>
						<div class="btn-group" role="group">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"
							   aria-haspopup="true" aria-expanded="false">
								{{ currentStep.name }} <span class="caret"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
									<a @click="toStep(step)">
										<span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
										{{step.name}}
									</a>
								</li>
							</ul>
						</div>
						<!--<a class="btn btn-default" v-if="!wizardComplete" :class="{'disabled': !canContinue }" @click="nextStep()">
							<i class="fa fa-chevron-right"></i>
						</a>
						<a class="btn btn-primary" v-if="wizardComplete" @click="finish()">
							<i class="fa fa-check"></i>
						</a>-->
					</div>
				</div>
			</div>
			<hr class="divider visible-xs">
			<div class="row">
				<div class="col-sm-5 col-md-4 hidden-xs">
					<ul class="nav nav-pills nav-stacked">
						<li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
							<a @click="toStep(step)">
								<span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
								{{step.name}}
							</a>
						</li>

					</ul>
				</div>
				<div class="col-sm-7 col-md-8" :class="currentStep.view">
					<spinner ref="validationSpinner" size="xl" :fixed="false" text="Validating"></spinner>
					<spinner ref="reservationspinner" size="xl" :fixed="true" text="Creating Reservation"></spinner>
					<component :is="currentStep.view" transition="fade" transition-mode="out-in" keep-alive>

					</component>
				</div>

			</div>
		</div>
		<div class="panel-footer text-right">
			<div class="btn-group btn-group" role="group" aria-label="...">
				<!--<a class="btn btn-link" data-dismiss="modal">Cancel</a>-->
				<a class="btn btn-default" @click="backStep()" :class="{'disabled': currentStep.view === 'step1' }">Back</a>
				<a class="btn btn-primary" v-if="!wizardComplete" :class="{'disabled': !canContinue }" @click="nextStep()">Continue</a>
				<a class="btn btn-primary" v-if="wizardComplete" @click="finish()">Finish</a>
			</div>
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

	.step1 {}
</style>
<script type="text/javascript">
	import login from '../login.vue';
	import tos from './registration/tos.vue';
	import roca from './registration/roca.vue';
	import basicInfo from './registration/basic-info.vue';
	import additionalOptions from './registration/additional-trip-options.vue';
	import paymentDetails from './registration/payment-details.vue';
	import deadlineAgreement from './registration/deadline-agreement.vue';
	import review from './registration/review.vue';
	export default{
		name: 'trip-registration-wizard',
		props: ['tripId', 'stripeKey'],
		data(){
			return {
				stepList:[
					{name: 'Login/Register', view: 'step1', complete:false}, // login component skipped for now
					{name: 'Legal (Terms of Service)', view: 'step2', complete:false},
					{name: 'Rules of Conduct Agreement', view: 'step3', complete:false},
					{name: 'Basic Traveler Information', view: 'step4', complete:false},
					{name: 'Rooming Options', view: 'step5', complete:false},
					{name: 'Payment Details', view: 'step6', complete:false},
					{name: 'Deadline Agreements', view: 'step7', complete:false},
					{name: 'Review', view: 'step8', complete:false}
				],
				currentStep: null,
//				canContinue: false,
				trip: {},
				tripCosts: {},
				deadlines:[],
				requirements:[],
				wizardComplete: false,

				// user generated data
				userData: null,
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
                this.stepList[7].complete = false;

                // negate second last step completion
                this.stepList[6].complete = false;
                this.rocaAgree = false;

                // negate step completion
                this.currentStep = step;
                if (step.view === 'step6') {
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
					if (this.currentStep.view === step.view){
						return this.currentStep = list[i-1];
					}
				}, this);
			},
			nextStep(){
				var thisChild;
				switch (this.currentStep.view) {
					case 'step4':

						// find child
						this.$children.forEach(function (child) {
							if (child.hasOwnProperty('$BasicInfo'))
								thisChild = child;
						});

						// if form is invalid do not continue
						if (thisChild.$BasicInfo.invalid) {
							thisChild.attemptedContinue = true;
							return false;
						}
						this.nextStepCallback();
						break;
					case 'step6':
						// find child
						if (this.upfrontTotal > 0) {
                            this.$children.forEach(function (child) {
                                if (child.hasOwnProperty('$PaymentDetails'))
                                    thisChild = child;
                            });
                            // promise needed to wait for async response from stripe
                            $.when(thisChild.createToken())
	                                .done(function (success) {
	                                    this.$refs.validationspinner.hide();
	                                    this.nextStepCallback();
	                                });
                        } else {
                            this.nextStepCallback();
                        }
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
			finish(){
				this.$refs.reservationspinner.show();

				var data = {
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
				if (this.upfrontTotal > 0) {
				    _.extend(data, {
                        token: this.paymentInfo.token,
                        payment: {
                            type: 'card',
                            brand: this.detectCardType(this.paymentInfo.card.number) || 'visa',
                            last_four: this.paymentInfo.card.number.substr(-4),
                            cardholder: this.paymentInfo.card.cardholder,
                        }
				    });
				}

				if (this.userData && this.userData.donor_id) {
					data.donor_id = this.donor_id;
				} else {
					data.donor = {
						name: this.userInfo.firstName + ' ' + this.userInfo.lastName,
						company: '',
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
				}, (response) =>  {
                    this.$refs.reservationspinner.hide();
                    console.log(response);
					this.$root.$emit('showError', response.data.message);
                    this.fallbackStep(this.stepList[5]); // return to payment details step
					this.paymentErrors.push(response.data.message);
				});


			},
			prepareFinalCosts(){
			    let finalCosts = [];
			    let unionCosts = _.union(this.tripCosts.incremental, [this.selectedOptions], this.tripCosts.static);
			    _.each(unionCosts, (cost) => {
				    if (_.isObject(cost))
                        finalCosts.push(cost);
                });
			    return finalCosts;
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
		components: {
			'step1': login,
			'step2': tos,
			'step3': roca,
			'step4': basicInfo,
			'step5': additionalOptions,
			'step6': paymentDetails,
			'step7': deadlineAgreement,
			'step8': review,
		},
		created(){
			// login component skipped for now
			this.currentStep = this.stepList[0];
		},
		mounted(){
			//get trip costs
			var resource = this.$resource('trips{/id}', { include: 'costs:status(active),costs.payments,deadlines,requirements' });
			resource.query({id: this.tripId}).then((trip) => {
				this.trip = trip.data.data;
				// deadlines, requirements, and companion_limit
				this.deadlines =  trip.data.data.deadlines.data;
				this.requirements =  trip.data.data.requirements.data;
				this.companion_limit = trip.data.data.companion_limit;

				// filter costs by type
				var optionalArr = [], staticArr = [], incrementalArr = [];
				trip.data.data.costs.data.forEach(function (cost) {
					switch (cost.type) {
						case 'static':
							staticArr.push(cost);
							break;
						case 'incremental':
							incrementalArr.push(cost);
							break;
						case 'optional':
							optionalArr.push(cost);
							break;
					}
				});
				this.tripCosts = {
					static: staticArr,
					incremental: incrementalArr,
					optional: optionalArr,
				}
			});

			let self = this;
			this.$root.$on('userHasLoggedIn', (val) =>  {
				// expecting userData object
				self.userData = val;
				self.currentStep.complete = !!val;
				// force next step
				self.nextStep();
			})
		},
		events: {
			/*'userHasLoggedIn'(val){
			    debugger;
				// expecting userData object
				this.userData = val;
				this.currentStep.complete = !!val;
				// force next step
				this.nextStep();
			},*/
			'tos-agree'(val){
				this.currentStep.complete = val;
			},
			'roca-agree'(val){
				this.currentStep.complete = val;
			},
			'basic-info'(val){
				this.currentStep.complete = val
			},
			'ato-complete'(val){
				this.currentStep.complete = val;
			},
			'payment-complete'(val){
				this.currentStep.complete = val;
			},
			'deadline-agree'(val){
				this.currentStep.complete = val;
			},
			'review'(val){
				this.currentStep.complete = this.wizardComplete = val;
			}
		}
	}
</script>
