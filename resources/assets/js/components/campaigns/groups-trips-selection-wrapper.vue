<template>
	<div>
		<div class="white-bg">
			<div class="container">
				<hr class="divider inv sm">
				<div class="row hidden-xs">
					<div class="col-sm-6">
						<h6 class="small text-uppercase">Campaign Chosen</h6>
						<h4 class="text-capitalize">{{ campaignName }}</h4>
					</div>
					<div class="col-sm-6 text-right">
						<hr class="divider inv sm">
						<hr class="divider inv sm">
						<a v-show="currentView!='groupSelection'" @click="restartView()" class="btn btn-default btn-sm">Start Over</a>
					</div>
				</div>
				<div class="row visible-xs">
					<div class="col-sm-6 text-center">
						<h6 class="small text-uppercase">Campaign Chosen</h6>
						<h5 class="text-capitalize">{{ campaignName }}</h5>
					</div>
					<div class="col-sm-6 text-center">
						<hr class="divider inv sm">
						<a v-show="currentView!='groupSelection'" @click="restartView()" class="btn btn-default btn-sm">Start Over</a>
					</div>
				</div>
				<hr class="divider inv sm">
			</div>
		</div>
		<component :is="currentView" transition="fade" transition-mode="out-in">>
			<!-- component changes when vm.currentview changes! -->
		</component>
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
	import campaignGroups from './campaign-groups.vue';
	import groupTrips from './group-trips.vue';
	export default{
		name: 'group-trip-wrapper',
		props: ["campaignId", "campaignName"],
		data(){
			return {
				currentView: 'groupSelection',
				groupId: null
			}
		},
		methods: {
			toggleView(){
				this.currentView = (this.currentView === 'groupSelection') ? 'tripSelection' : 'groupSelection'
			},
			restartView(){
				this.currentView = 'groupSelection'
			}
		},
		components: {
			'groupSelection': campaignGroups,
			'tripSelection': groupTrips
		}
	}
</script>
