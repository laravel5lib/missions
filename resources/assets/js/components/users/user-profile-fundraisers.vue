<template>
	<div style="position:relative;">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="text-muted text-center" v-if="fundraisers.length < 1">
			<template v-if="isUser">
				<em><h4>Start Fundraising</h4>
					<p>Join a trip to start fundraising.</p></em>
				<p class="text-center"><a class="btn btn-primary" href="/campaigns">Go On A Trip</a></p>
			</template>
			<template v-else>
				<em><h4>Currently No Fundraisers</h4>
					<p>This person hasn't added any fundraisers yet.</p>
				</em>
			</template>
		</div>
		<div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers" style="min-height: 350px;">
			<div class="panel panel-default" v-if="isVisible(fundraiser)">
				<div class="panel-heading">
					<img :src="fundraiser.featured_image" :alt="fundraiser.name" class="img-responsive">
					<h6 class="text-center">{{ fundraiser.name }}</h6>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6 col-sm-12 col-md-6">
							<label>Raised</label>
							<h4 class="text-success" style="margin-top:0;">
							${{ fundraiser.raised_amount }}</h4>
						</div>
						<div class="col-xs-6 col-sm-12 col-md-6">
							<label>Expires</label>
							<p class="small">
								{{ fundraiser.ended_at | moment('ll') }}
							</p>
						</div>
					</div><!-- end row -->
					<label><span>{{ fundraiser.raised_percent }}</span>%
						<small>Funded</small>
						/ <span>{{ fundraiser.donors_count }}</span>
						<small>Donors</small>
					</label>
					<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
							 aria-valuemin="0" aria-valuemax="100"
							 :style="{ width: fundraiser.raised_percent + '%'}">
							<span class="sr-only">{{ fundraiser.raised_percent }}% Complete (success)</span>
						</div>
					</div>
					<p class="small text-center text-info" v-if="!fundraiser.public">Private
						<tooltip effect="scale"
								 placement="top"
								 content="Only you can see this">
							<i class="fa fa-question-circle-o text-muted"></i>
						</tooltip>
					</p>
					<p><a class="btn btn-primary btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
				</div><!-- end panel-body -->
			</div><!-- end panel -->
		</div><!-- end col -->
		<div v-if="oldFundraisers.length > 0">
			<div class="col-xs-12">
				<hr class="divider xlg inv">
				<h5 class="text-muted">Past Fundraisers</h5>
				<hr class="divider lg">
			</div>
		</div>
		<div v-if="oldFundraisers.length > 0">
			<div class="col-xs-12 col-sm-6 col-md-4" v-for="fundraiser in oldFundraisers" style="min-height: 300px;">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<h6>{{ fundraiser.name }}</h6>
					</div>
					<div class="panel-body text-center">
							<label>Closed</label>
							<p class="small">{{ fundraiser.ended_at | moment('ll') }}</p>
							<label><span class="text-success">${{ fundraiser.raised_amount }}</span>
								<small>Raised / {{ fundraiser.donors_count }} Donors</small>
							</label>
						<p class="smalltext-info" v-if="!fundraiser.public">Private</p>
						<p><a class="btn btn-default btn-sm" :href="pathName + '/' + fundraiser.url">Details</a></p>
					</div><!-- end panel-body -->
				</div><!-- end panel -->
			</div><!-- end col -->
		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'user-profile-fundraisers',
		props: ['id', 'userUrl'],
		data(){
			return {
				fundraisers: [],
				oldFundraisers: [],

				pathName: window.location.pathname
			}
		},
		computed: {
            isUser() {
                return this.$root.user && this.id === this.$root.user.id;
            },
		},
		methods: {
            isVisible(fundraiser) {
				return !fundraiser.public && this.$root.user.id === fundraiser.sponsor_id ? true : !!fundraiser.public;
            }
		},
		mounted(){
			this.$http.get('fundraisers?active=true', { params: {
				sponsorId: this.id,
				sponsorType: 'user',
				per_page: 100
			}}).then((response) => {
				this.fundraisers = response.data.data;
			}).catch(this.$root.handleApiError);

			this.$http.get('fundraisers?archived=true', { params: {
				sponsorId: this.id,
				sponsorType: 'user',
				per_page: 100
			}}).then((response) => {
				this.oldFundraisers = response.data.data;
			}).catch(this.$root.handleApiError)
		}

	}
</script>
