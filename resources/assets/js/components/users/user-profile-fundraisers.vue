<template>
	<div style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<p class="text-muted text-center" v-if="fundraisers.length < 1"><em>No fundraisers found.</em></p>
		<div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h6 class="text-center">{{ fundraiser.name }}</h6>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6 col-sm-12 col-md-6">
							<label>Raised</label>
							<h4 class="text-success" style="margin-top:0;">{{ fundraiser.raised_amount | currency
								}}</h4>
						</div>
						<div class="col-xs-6 col-sm-12 col-md-6">
							<label>Expires</label>
							<p class="small">
								{{ fundraiser.ended_at | moment 'll' }}
							</p>
						</div>
					</div><!-- end row -->
					<label><span>{{ (fundraiser.raised_amount/fundraiser.goal_amount * 100)|number 1 }}</span>%
						<small>Funded</small>
						/ <span>{{ fundraiser.donors_count }}</span>
						<small>Donors</small>
					</label>
					<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
							 aria-valuemin="0" aria-valuemax="100"
							 :style="{ width: (fundraiser.raised_amount/fundraiser.goal_amount * 100) + '%'}">
							<span class="sr-only">{{ (fundraiser.raised_amount/fundraiser.goal_amount * 100) }}% Complete (success)</span>
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
				<hr/>
				<h5 class="text-muted text-center">Previous Fundraisers</h5>
				<hr/>
			</div>
		</div>
		<div v-if="oldFundraisers.length > 0">
			<div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12"
				 v-for="fundraiser in oldFundraisers">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6>{{ fundraiser.name }}</h6>
					</div>
					<div class="panel-body">
						<label>Closed</label>
						<p class="small">{{ fundraiser.ended_at | moment 'll' }}</p>
						<label><span class="text-success">{{ fundraiser.raised_amount | currency }}</span>
							<small>Raised / {{ fundraiser.donors_count }} Donors</small>
						</label>
						<p class="small text-center text-info" v-if="!fundraiser.public">Private</p>
						<p><a class="btn btn-default btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
					</div><!-- end panel-body -->
				</div><!-- end panel -->
			</div><!-- end col -->
		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'user-profile-fundraisers',
		props: ['id', 'userUrl', 'authId'],
		data(){
			return {
				fundraisers: [],
				oldFundraisers: [],

				pathName: window.location.pathname
			}
		},
		ready(){
			this.$http.get('fundraisers?active=true', {
				sponsor: this.userUrl,
				per_page: 100
			}).then(function (response) {
				this.fundraisers = response.data.data;
			});

			this.$http.get('fundraisers?archived=true', {
				sponsor: this.userUrl,
				per_page: 100
			}).then(function (response) {
				this.oldFundraisers = response.data.data;
			})
		}

	}
</script>
