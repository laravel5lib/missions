<template>
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<ul class="lizt-group">
					<a @click="toStep(step)" role="step" v-for="step in stepList" class="list-group-item" :class="{'active': currentStep.view === step.view, 'list-group-item-danger' : step.valid === false}">
						<span class="fa" :class="{'fa-chevron-right':!step.complete && step.valid === null, 'fa-check': step.complete, 'fa-exclamation-triangle' : step.valid === false}"></span>
						{{step.name}}
					</a>
			</ul>
		</div>
		<div class="col-sm-8 col-md-9 {{currentStep.view}}">
			<component :is="currentStep.view" transition="fade" transition-mode="out-in" keep-alive>

			</component>
			<div class="alert alert-danger alert-dismissible" role="alert" v-if="!stepList[0].valid && !!$children[0].attemptedContinue">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Uh Oh!</strong> The Details form still contains errors. Please correct them before saving.
			</div>
			<hr>
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
				<a class="btn btn-link" @click="back">Cancel</a>
				<a class="btn btn-default" v-if="currentStep.view !== 'step1'" @click="backStep">Back</a>
				<a class="btn btn-primary" v-if="!wizardComplete" @click="nextStep">Continue</a>
				<a class="btn btn-primary" v-if="wizardComplete" @click="finish">Finish</a>
			</div>
		</div>
		<hr>
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
	import details from './create/details.vue';
	import settings from './create/settings.vue';
	// import pricing from './create/pricing.vue';
	// import reqs from './create/requirements.vue';
	// import deadlines from './create/deadlines.vue';

	export default{
		name: 'campaign-trip-create-wizard',
		props:['campaignId', 'countryCode'],
		data(){
			return {
				stepList:[
					{name: 'Details', view: 'step1', form:'$TripDetails', valid: null, complete:false},
					{name: 'Registration Settings', view: 'step2', form:'$TripSettings', valid: null, complete:false},
					// {name: 'Pricing', view: 'step3', form:'$TripPricing', valid: null, complete:false},
					// {name: 'Requirements', view: 'step4', form:'$TripReqs', valid: null, complete:false},
					// {name: 'Other Deadlines', view: 'step5', form:'$TripDeadlines', valid: null, complete:false},
				],
				currentStep: null,
				canContinue: false,
				wizardComplete: false,
				campaign: {},

				// admin generated data
				wizardData: {
					campaign_id: this.campaignId,
					country_code: [this.countryCode]
				},
			}
		},
		computed: {
			canContinue(){
				return this.currentStep.complete;
			}
		},
		components:{
			'step1': details,
			'step2': settings,
			// 'step3': pricing,
			// 'step4': reqs,
			// 'step5': deadlines
		},
		methods: {
			back(){
				window.location.href = window.location.href.split('/create')[0];
			},
			childValidationTrigger(){
				var self = this;
				// find child
				var thisChild = _.find(this.$children, function (child) {
					return child.hasOwnProperty(self.currentStep.form);
				});

				// if form is invalid mark as invalid step, but continue anyway
				this.currentStep.valid = !thisChild[this.currentStep.form].invalid;
				thisChild.attemptedContinue = true;
				thisChild.populateWizardData();
			},
			toStep(step){
				this.childValidationTrigger();
				this.currentStep = step;
			},
			backStep(){
				this.childValidationTrigger();
				this.stepList.some(function(step, i, list) {
					if (this.currentStep.view === step.view){
						return this.currentStep = list[i-1];
					}
				}, this);
			},
			nextStep(){
				this.childValidationTrigger();
				this.nextStepCallback();
			},
			nextStepCallback(){
				this.stepList.some(function(step, i, list) {
					if (this.currentStep.view === step.view){
						return this.currentStep = list[i+1];
					}
				}, this);
			},
			finish(){
				// if details form is incomplete
				if (!this.stepList[0].valid) {
					// show error and discontinue
					return false;
				}

				var resource = this.$resource('trips');
				resource.post(null, this.wizardData).then((resp) => {
					window.location.href = '/admin' + resp.data.data.links[0].uri;
				}, (error) =>  {
					console.log(error);
				});
			}
		},
		created(){
			this.currentStep = this.stepList[0];

			this.$http.get('campaigns/' + this.campaignId).then((response) => {
				this.campaign = response.data.data;
			});
		},
		events: {
			'details'(val){
				this.currentStep.complete = val;
			},
			'settings'(val){
				this.currentStep.complete = this.wizardComplete = val;
			},
			/*'pricing'(val){
				this.currentStep.complete = val;
			},
			'reqs'(val){
				this.currentStep.complete = val
			},
			'deadlines'(val){
				this.currentStep.complete = this.wizardComplete = val
			}*/
		}
	}
</script> 