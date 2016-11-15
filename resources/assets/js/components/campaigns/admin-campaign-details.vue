<template>
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="text-capitalize">{{campaign.name}} <small>&middot; Campaign</small></h3>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                	<hr class="divider inv sm">
					<a href="/admin/campaigns" class="btn btn-default"><i class="fa fa-chevron-left icon-left"></i> Back</a>
					<a class="btn btn-primary" href="/admin/campaigns/{{campaignId}}/edit"><i class="fa fa-pencil-square-o icon-left"></i> Edit</a>
				</div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<ul class="nav nav-pills nav-stacked">
					<li :class="{'active': currentView === 'details'}">
						<a @click="toView('details')">Details</a>
					</li>
					<li :class="{'active': currentView === 'trips'}">
						<a @click="toView('trips')">Trips</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="row">
				<div class="col-sm-12">
					<component :is="currentView" transition="fade" transition-mode="out-in">

					</component>
				</div>
			</div>
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

			// get campaign data
			var resource = this.$resource('campaigns{/id}', {'include': 'trips.group'});
			resource.get({id: this.campaignId}).then(function(response) {
				this.campaign = response.data.data;
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