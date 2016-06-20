<template>
	<div class="row">
		<div class="col-sm-4">
			<ul class="nav nav-pills nav-stacked">
				<li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
					<a>
						<span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
						{{step.name}}
					</a>
				</li>

			</ul>
		</div>
		<div class="col-sm-8">
			<component :is="currentStep.view" transition="fade" transition-mode="out-in" keep-alive>

			</component>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<a class="btn btn-default" @click="backStep()">Back</a>
				<a class="btn btn-primary" :class="{'disabled': !canContinue }" @click="nextStep()">Continue</a>
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
	import tos from './registration/tos.vue';
	import roca from './registration/roca.vue';
	import basicInfo from './registration/basic-info.vue';
	// import groupTrips from './group-trips.vue';
	export default{
		name: 'trip-registration-wizard',
		props: ["tripId"],
		data(){
			return {
				stepList:[
					{name: 'Login/Register', view: 'step1', complete:true},
					{name: 'Legal (Terms of Service)', view: 'step2', complete:false},
					{name: 'Rules of Conduct Agreement', view: 'step3', complete:false},
					{name: 'Basic Traveler Information', view: 'step4', complete:false},
					//{name: 'Legal (Terms of Service)', view: 'step2', order:5, complete:false},
					//{name: 'Legal (Terms of Service)', view: 'step2', order:6, complete:false},
				],
				currentStep: null,
				canContinue: false,
				tosAgree: false,
				rocaAgree: false,
			}
		},
		computed: {
			canContinue(){
				switch (this.currentStep.view) {
					case 'step2':
						return this.tosAgree;
					case 'step3':
						return this.rocaAgree;
				}
			}
		},
		methods: {
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
				var cs = this.currentStep;
				var ns;
				this.stepList.forEach(function(val, i, list) {
					if (cs.view === val.view){
						ns = list[i+1];
						return list[i].complete = cs.complete;
					}
				});
				this.currentStep = ns;
			}
		},
		components: {
			'step2': tos,
			'step3': roca,
			'step4': basicInfo,
			//'tripSelection': groupTrips
		},
		created(){
			this.currentStep = this.stepList[1];
		},
		events: {
			'tos-agree'(val){
				this.currentStep.complete = true;
				this.tosAgree = val;
			},
			'roca-agree'(val){
				this.currentStep.complete = true;
				this.rocaAgree = val;
			}
		}
	}
</script>
