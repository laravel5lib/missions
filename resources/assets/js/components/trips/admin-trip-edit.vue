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
			<hr>
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
				<a class="btn btn-link" @click="back()">Cancel</a>
				<a class="btn btn-default" v-if="currentStep.view !== 'step1'" @click="backStep()">Back</a>
				<a class="btn btn-primary" v-if="!wizardComplete" @click="nextStep()">Continue</a>
				<a class="btn btn-primary" v-if="wizardComplete" @click="finish()">Finish</a>
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
<script>
	import details from './edit/details.vue';
	import settings from './edit/settings.vue';
	import pricing from './edit/pricing.vue';
	import reqs from './edit/requirements.vue';
	import deadlines from './edit/deadlines.vue';

	export default{
		name: 'campaign-trip-edit-wizard',
		props:['tripId'],
		data(){
			return {
				stepList:[
					{name: 'Details', view: 'step1', form:'$TripDetails', valid: null, complete:false},
					{name: 'Registration Settings', view: 'step2', form:'$TripSettings', valid: null, complete:false},
					{name: 'Pricing', view: 'step3', form:'$TripPricing', valid: null, complete:false},
					{name: 'Requirements', view: 'step4', form:'$TripReqs', valid: null, complete:false},
					{name: 'Other Deadlines', view: 'step5', form:'$TripDeadlines', valid: null, complete:false},
				],
				currentStep: null,
				canContinue: false,
				wizardComplete: false,
				trip: {},

				// admin generated data
				wizardData: {
					campaign_id: this.tripId,
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
			'step3': pricing,
			'step4': reqs,
			'step5': deadlines
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
				var resource = this.$resource('trips{/id}');
				resource.update({ id: this.tripId}, this.wizardData).then(function (resp) {
					window.location.href = '/admin/campaigns/' + this.wizardData.campaign_id + resp.data.data.links[0].uri;
				}, function (error) {
					console.log(error);
				});
			}
		},
		created(){
			this.currentStep = this.stepList[0];

			this.$http.get('trips/' + this.tripId, { include: 'campaign,costs.payments,requirements,notes,deadlines'}).then(function (trip) {
				var trip = trip.data.data;
				$.extend(trip, {
					type: this.type,
					group_id: this.group_id
				});
				// trim costs
				_.each(trip.costs.data, function (cost) {
					cost.payments = cost.payments.data;
				});
				trip.costs = trip.costs.data;
				// trim campaign
				trip.campaign = trip.campaign.data;
				// trim deadlines
				trip.deadlines = trip.deadlines.data;
				// trim requirements
				trip.requirements = trip.requirements.data;
				// trim notes
				trip.notes = trip.notes.data;
				// for now remove rep_id
				delete trip.rep_id;
				console.log(trip);
				this.trip = trip;
				this.wizardData.country_code = this.trip.country_code;

				this.$broadcast('trip', this.trip);
			});
		},
		events: {
			'details'(val){
				this.currentStep.complete = val;
			},
			'settings'(val){
				this.currentStep.complete = val;
			},
			'pricing'(val){
				this.currentStep.complete = val;
			},
			'reqs'(val){
				this.currentStep.complete = val
			},
			'deadlines'(val){
				this.currentStep.complete = this.wizardComplete = val
			}
		}
	}
</script> 