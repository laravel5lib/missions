<template>
	<div class="white-header-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-8">
					<h3 class="text-capitalize">
						<a href="#">
							<img :src="campaign.avatar" alt="{{campaign.name}}" class="img-circle av-left img-sm">
						</a>
						{{campaign.name}}
						<small>&middot; Campaign</small>
					</h3>
				</div>
				<div class="col-sm-4">
					<div class="pull-right">
						<hr class="divider inv sm">
						<hr class="divider inv">
						<div class="btn-group" role="group">
							<a onclick="window.history.back()" class="btn btn-primary-darker"><span
									class="fa fa-chevron-left icon-left"></span></a>
							<a class="btn btn-primary" href="/admin/campaigns/{{campaignId}}/edit">Edit</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr class="divider inv lg">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
					<ul class="nav nav-tabs" role="tablist">
						<li :class="{'active': currentView === 'details'}">
							<a @click="toView('details')">Details</a>
						</li>
						<li :class="{'active': currentView === 'trips'}">
							<a @click="toView('trips')">Trips</a>
						</li>
					</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
						<spinner v-ref:spinner size="sm" text="Loading"></spinner>

						<component :is="currentView" transition="fade" transition-mode="out-in">

						</component>
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
<script type="text/javascript">
	import details from './details/details.vue';
	import trips from './details/trips.vue';
	import regions from './details/regions.vue';
	import transports from './details/transports.vue';
	export default{
		name: 'admin-campaign-details',
		props: ['campaignId'],
		data(){
			return {
				currentView: null,
				campaign: {}
			}
		},
		methods: {
			toView(view){
				this.currentView = view;
			}
		},
		created(){
			this.currentView = 'details';
			// this.$refs.spinner.show();
			// get campaign data
			let resource = this.$resource('campaigns{/id}', {'include': 'trips.group'});
			resource.get({id: this.campaignId}).then(function(response) {
				this.campaign = response.data.data;
				// this.$refs.spinner.hide();
			});
		},
		components: {
			'details': details,
			'trips': trips,
			'regions': regions,
			'transports': transports
		}
	}

</script>