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
		<div class="col-sm-8 col-md-9 {{currentStep.view}}">
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
					{name: 'Details', view: 'step1', complete:false},
					{name: 'Registration Settings', view: 'step2', complete:false},
					{name: 'Options', view: 'step3', complete:false},
					{name: 'Referral Campaigns', view: 'step4', complete:false},
					{name: 'Resources', view: 'step5', complete:false},
					{name: 'Pricing', view: 'step6', complete:false},
					{name: 'Default Support Message', view: 'step7', complete:false},
					{name: 'Trip HQ', view: 'step8', complete:false},
					{name: 'Suggested Parking', view: 'step9', complete:false},
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
			checkForError(field){
				// if user clicked submit button while the field is invalid trigger error styles 
				return this.$CreateCampaign[field].invalid && this.attemptSubmit;
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
				switch (this.currentStep.view) {
					case 'step1':
						// find child
						this.$children.forEach(function (child) {
							if (child.hasOwnProperty('$TripDetails'))
								thisChild = child;
						});

						// if form is invalid do not continue
						if (thisChild.$TripDetails.invalid) {
							thisChild.attemptedContinue = true;
							return false;
						}
						this.nextStepCallback();
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

			},
			submit(){
				this.attemptSubmit = true;
				if (this.$CreateCampaign.valid) {
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