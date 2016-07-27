<template>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h5>{{ trip.country_name }} Trip Registration</h5>
		</div>
		<div class="panel-body">
			<div class="row visible-xs-block">
				<div class="col-xs-12">
					<div class="btn-group btn-group-justified btn-group-xs" style="display:block;" role="group" aria-label="...">
						<a @click="backStep()" class="btn btn-default" :class="{'disabled': currentStep.view === 'step1' }" role="button">
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
				<div class="col-sm-7 col-md-8 {{currentStep.view}}">
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
<script>
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
					{name: 'Additional Trip Options', view: 'step5', complete:false},
					{name: 'Payment Details', view: 'step6', complete:false},
					{name: 'Deadline Agreements', view: 'step7', complete:false},
					{name: 'Review', view: 'step8', complete:false}
				],
				currentStep: null,
				canContinue: false,
				trip: {},
				tripCosts: {},
				deadlines:[],
				requirements:[],
				wizardComplete: false,

				// user generated data
				userData: null,
				selectedOptions: [],
				userInfo: {},
				paymentInfo: {},
				upfrontTotal: 0,
				fundraisingGoal: 0
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
						this.$children.forEach(function (child) {
							if (child.hasOwnProperty('$PaymentDetails'))
								thisChild = child;
						});
						var self = this;
							// promise needed to wait for async response from stripe
						$.when(thisChild.createToken())
								.done(function (success) {
									self.nextStepCallback();
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
			finish(){
				// 1. Create customer with stripe
				// 2. if they chose to save data, save card to customer
				// 3. charge card
				// 4. update user account with customer id
				// 5. create reservation
				this.$http.post('reservations', {
					given_names: this.userInfo.firstName + ' ' + this.userInfo.middleName,
					surname: this.userInfo.lastName,
					gender: this.userInfo.gender,
					status: this.userInfo.relationshipStatus,
					shirt_size: this.userInfo.size,
					birthday: moment().set({year: this.userInfo.dobYear, month: this.userInfo.dobMonth, day: this.userInfo.dobDay}).format('YYYY-MM-DD'),
					amount: this.fundraisingGoal,
					user_id: this.userData.id,
					trip_id: this.tripId,
					companion_limit: this.companion_limit
				}).then(function (response) {
					window.location.href = 'dashboard/' + response.data.data.links[0].uri;
				}, function (response) {
					console.log(response);
				});


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
			'step8': review

		},
		created(){
			// login component skipped for now
			this.currentStep = this.stepList[0];
		},
		ready(){
			//get trip costs
			var resource = this.$resource('trips{/id}', { include: 'costs:status(active),costs.payments,deadlines,requirements' });
			resource.query({id: this.tripId}).then(function (trip) {
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
		},
		events: {
			'userHasLoggedIn'(val){
				// expecting userData object
				this.userData = val;
				this.currentStep.complete = !!val;
				// force next step
				this.nextStep();
				window.location.reload();
			},
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
