<template>
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<ul class="nav nav-pills nav-stacked">
				<li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
					<a @click="toStep(step)">
						<span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
						{{step.name}}
					</a>
				</li>

			</ul>
		</div>
		<div class="col-sm-8 col-md-9">
			<component :is="currentStep.view" transition="fade" transition-mode="out-in" keep-alive>

			</component>
			<hr>
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
				<!--<a class="btn btn-link" data-dismiss="modal">Cancel</a>-->
				<a class="btn btn-default" @click="backStep()">Back</a>
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
					{name: 'Login/Register', view: 'step1', complete:true}, // login component skipped for now
					{name: 'Legal (Terms of Service)', view: 'step2', complete:false},
					{name: 'Rules of Conduct Agreement', view: 'step3', complete:false},
					{name: 'Basic Traveler Information', view: 'step4', complete:false},
					{name: 'Additional Trip Options', view: 'step5', complete:false},
					{name: 'Payment Details', view: 'step6', complete:false},
					{name: 'Deadline Agreements', view: 'step7', complete:false},
					{name: 'Review', view: 'step8', complete:false},
				],
				currentStep: null,
				canContinue: false,
				tripCosts: {},
				deadlines:[],
				requirements:[],
				wizardComplete: false,

				// user generated data
				selectedOptions: [],
				userInfo: {},
				paymentInfo: {},
				upfrontTotal: 0
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
				var cs = this.currentStep;
				var bs;
				this.stepList.forEach(function(val, i, list) {
					if (cs.view === val.view){
						bs = list[i-1];
					}
				});
				this.currentStep = bs;
			},
			nextStep(){
				var thisChild;
				switch (this.currentStep.view) {
					case 'step4':
						// find child
						this.$children.forEach(function (v) {
							if (v.hasOwnProperty('$BasicInfo'))
								thisChild = v;
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
						this.$children.forEach(function (v) {
							if (v.hasOwnProperty('$PaymentDetails'))
								thisChild = v;
						});
						var self = this;
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
				var cs = this.currentStep;
				var ns;
				this.stepList.forEach(function(val, i, list) {
					if (cs.view === val.view){
						ns = list[i+1];
						return list[i].complete = cs.complete;
					}
				});
				this.currentStep = ns;
			},
			finish(){
				// do something here

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
			this.currentStep = this.stepList[1];
		},
		ready(){
			//get trip costs
			var resource = this.$resource('trips{/id}', { include: 'costs:status(active),costs.payments,deadlines,requirements' });
			resource.query({id: this.tripId}).then(function (trip) {
				// deadlines
				this.deadlines =  trip.data.data.deadlines.data;
				this.requirements =  trip.data.data.requirements.data;

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
