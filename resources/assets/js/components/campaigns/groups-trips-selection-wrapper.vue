<template>
	<div>
		<div class="container">
			<h5>{{ campaignName }}</h5>
		</div>
		<div class="gray-light-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<a v-show="currentView!='groupSelection'" @click="restartView()" class="btn btn-default btn-sm">Start Over</a>
					</div>
				</div>
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
<script>
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
