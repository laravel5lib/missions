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
	import details from './create/details.vue';
	import settings from './create/settings.vue';
	import options from './create/details.vue';
	import referrals from './create/details.vue';
	import resources from './create/details.vue';
	import pricing from './create/details.vue';
	import support from './create/details.vue';
	import hq from './create/hq.vue';
	import parking from './create/parking.vue';

	export default{
		name: 'campaign-trip-create-wizard',
		props:['campaignId'],
		data(){
			return {
				stepList:[
					{name: 'Details', view: 'step1', form:'$TripDetails', valid: null, complete:false},
					{name: 'Registration Settings', view: 'step2', form:'$TripSettings', valid: null, complete:false},
					{name: 'Options', view: 'step3', form:'$TripOptions', valid: null, complete:false},
					{name: 'Referral Campaigns', view: 'step4', form:'$TripDetails', valid: null, complete:false},
					{name: 'Resources', view: 'step5', form:'$TripDetails', valid: null, complete:false},
					{name: 'Pricing', view: 'step6', form:'$TripDetails', valid: null, complete:false},
					{name: 'Default Support Message', view: 'step7', form:'$TripDetails', valid: null, complete:false},
					{name: 'Trip HQ', view: 'step8', form:'$TripHQ', valid: null, complete:false},
					{name: 'Suggested Parking', view: 'step9', form:'$TripParking', valid: null, complete:false},
				],
				currentStep: null,
				canContinue: false,
				wizardComplete: false,
				campaigns: [],
				groups: [],

				// admin generated data
				wizardData: null,
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
			'step3': options,
			'step4': referrals,
			'step5': resources,
			'step6': pricing,
			'step7': support,
			'step8': hq,
			'step9': parking
		},
		methods: {
			back(){
				window.location.href = window.location.href.split('/create')[0];
			},
			toStep(step){
				var thisChild;
				// find child
				this.$children.some(function (child) {
					if (child.hasOwnProperty(this.currentStep.form))
						return thisChild = child;
				}, this);

				// if form is invalid mark as invalid step, but continue anyway
				this.currentStep.valid = !thisChild[this.currentStep.form].invalid;
				if (thisChild[this.currentStep.form].invalid) {
					thisChild.attemptedContinue = true;
				}

				this.currentStep = step;
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
				// find child
				this.$children.some(function (child) {
					if (child.hasOwnProperty(this.currentStep.form))
						return thisChild = child;
				}, this);

				// if form is invalid mark as invalid step, but continue anyway
				this.currentStep.valid = !thisChild[this.currentStep.form].invalid;
				if (thisChild[this.currentStep.form].invalid) {
					thisChild.attemptedContinue = true;
				}
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

			},
			submit(){
				this.attemptSubmit = true;
				if (this.wizardComplete) {
					var resource = this.$resource('trips');
					resource.save(null, {
						name: this.name,
						country_code: this.country_code,
						short_desc: this.short_desc,
						started_at: this.started_at,
						ended_at: this.ended_at,
						published_at: this.published_at,
						page_url: this.page_url

					}).then(function (resp) {
						window.location.href = '/admin' + resp.data.data.links[0].uri;
					}, function (error) {
						debugger;
					});
				}
			}
		},
		created(){
			// login component skipped for now
			this.currentStep = this.stepList[0];

			this.$http.get('campaigns').then(function (response) {
				this.$set('campaigns', response.data.data);
			});

			this.$http.get('groups').then(function (response) {
				this.$set('groups', response.data.data);
			});
		},
		events: {
			'details'(val){
				this.currentStep.complete = val;
			},
			'settings'(val){
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
			'parking'(val){
				this.currentStep.complete = this.wizardComplete = val;
			},
			'hq'(val){
				this.currentStep.complete = this.wizardComplete = val;
			},
			'parking'(val){
				this.currentStep.complete = this.wizardComplete = val;
			},
		}
	}
</script> 