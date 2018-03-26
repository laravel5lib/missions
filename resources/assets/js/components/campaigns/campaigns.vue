<template>
<div>
	<div class="container" v-if="campaigns.length < 1">
			<div class="col-sm-8 col-sm-offset-2 text-center">
				<h3>Looking for 2018 trips?</h3>
				<hr class="divider inv sm">
				<p>Be the first on the list to know about 2018 trips.</p>
				<hr class="divider inv">
				<a href="http://eepurl.com/cZnArz" target="_blank" class="btn btn-primary">Notify Me!</a>
			</div><!-- end col -->
	</div><!-- end container -->

	<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;" v-if="campaigns.length > 0">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="col-xs-12">
			<h4>Current Trips</h4>
			<hr class="divider">
		</div>
		<div class="col-xs-6 text-right">
			<a v-if="campaigns.length > 3" @click="seeAll" class="btn btn-primary btn-sm">See All</a>
		</div>
	</div>
	<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="col-xs-12 col-sm-6 col-md-4" v-for="campaign in limitBy(campaigns, campaignsLimit)" style="display:flex">
			<div class="panel panel-default">
				<a class="hidden-xs hidden-sm" :href="campaign.page_url" role="button">
					<img :src="campaign.avatar+'?w=400&h=400&fit=stretch'" :alt="campaign.name" class="img-responsive">
				</a>
				<div style="min-height:220px;" class="panel-body">
					<h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;"><i
							class="fa fa-map-marker"></i> {{campaign.country}}</h6>
					<a :href="campaign.page_url" role="button">
						<h5 style="text-transform:capitalize;" class="text-primary">{{campaign.name}}</h5>
					</a>
					<h6 style="font-size:12px;">
						{{campaign.started_at | moment('ll')}} -
						{{campaign.ended_at | moment('ll')}}
					</h6>
					<hr class="divider lg"/>
					<p style="font-size:12px;" class="small">{{campaign.description}}</p>
				</div><!-- end panel-body -->
			</div><!-- end panel -->
		</div><!-- end col -->
	</div>
</div>
</template>
<script type="text/javascript">
	import $ from 'jquery';
	export default{
		name: 'campaigns',
		data(){
			return {
				campaigns: [],
				campaignsLimit: 3,
				resource: this.$resource('campaigns', { published: true, current: true})
			}
		},
		methods: {
			seeAll(){
				this.campaignsLimit = this.campaigns.length
			}
		},
		mounted(){
			// this.$refs.spinner.show();
			this.resource.get().then((campaigns) => {
				this.campaigns = campaigns.data.data;
				// this.$refs.spinner.hide();
			}, this.$root.handleApiError);
		}
	}
</script>

