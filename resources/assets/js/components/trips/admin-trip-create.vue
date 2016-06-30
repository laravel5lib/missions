<template>
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<ul class="nav nav-pills nav-stacked">
				<li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'text-danger' : step.valid === false}">
					<a @click="toStep(step)">
						<span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
						{{step.name}}
					</a>
				</li>

			</ul>
		</div>
		<div class="col-sm-8 col-md-9 {{currentStep.view}}">
			<component :is="currentStep.view" transition="fade" transition-mode="out-in" keep-alive>

			</component>
			<hr>
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
				<a class="btn btn-link" @click="back()">Cancel</a>
				<a class="btn btn-default" v-if="currentStep.view !== 'step1'" @click="backStep()">Back</a>
				<a class="btn btn-primary" v-if="!wizardComplete" :class="{'disabled': !canContinue }" @click="nextStep()">Continue</a>
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
	import hq from './create/details.vue';
	import parking from './create/details.vue';

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
					{name: 'Trip HQ', view: 'step8', form:'$TripDetails', valid: null, complete:false},
					{name: 'Suggested Parking', view: 'step9', form:'$TripDetails', valid: null, complete:false},
				],
				currentStep: null,
				canContinue: false,
				wizardComplete: false,
				campaigns: [],
				groups: [],

				// admin generated data
				details: null,
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
				// find child
				this.$children.forEach(function (child) {
					if (child.hasOwnProperty(this.currentStep.form))
						thisChild = child;
				});

				// if form is invalid mark as invalid step, but continue anyway
				this.currentStep.valid = !thisChild[this.currentStep.form].invalid;
				if (thisChild[this.currentStep.form].invalid) {
					thisChild.attemptedContinue = true;
				} else {
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
			'review'(val){
				this.currentStep.complete = this.wizardComplete = val;
			}
		}
	}
</script> 