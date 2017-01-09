<template>
	<div>
		<div class="dark-bg-primary">
			<div class="container">
				<hr class="divider inv xlg">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0 text-center">
						<a :href="'/groups/' + group.url"><img class="img-circle img-lg" :src="group.avatar"></a>
						<h3>{{ group.name }}</h3>
					</div>
				</div>
				<hr class="divider inv lg">
			</div>
		</div>
		<div class="container">
			<hr class="divider inv lg">
			<div class="row">
				<div class="col-xs-12 text-center">
					<h2>Choose A Trip</h2>
					<hr class="divider red-small lg">
				</div>
			</div>
			<div class="row">
				<div v-for="trip in trips" class="col-xs-6 col-sm-3">
					<div class="panel panel-default">
						<div class="panel-heading" :class="'panel-' + trip.type">
							<h5 class="text-uppercase text-center">{{ trip.type | capitalize }}</h5>
						</div>
						<div class="panel-body text-center">
							<p class="badge">{{ trip.status | capitalize }}</p><br>
							<p class="small">{{ trip.started_at | moment 'll'}} - {{ trip.ended_at | moment 'll'}}</p>
							<label>Perfect For</label>
							<p class="small"><span v-for="prospect in trip.prospects">
								{{ prospect | capitalize }}<span v-show="$index + 1 != trip.prospects.length">, </span>
						</span></p>
							<label>Spots Available</label>
							<p>{{ trip.spots }}</p>
							<label>Starting At</label>
							<h3 style="margin-top:0px;" class="text-success">{{ trip.starting_cost | currency }}</h3>
							<a href="/trips/{{ trip.id }}" class="btn btn-primary-hollow btn-sm">Select</a>
						</div>
					</div>
				</div>
			</div><!-- end row -->

			<div class="container">
				<div class="col-sm-12 text-center">
					<pagination :pagination.sync="pagination" :callback="getTrips"></pagination>
				</div>
			</div><!-- end container -->
			<hr class="divider inv xlg">
		</div><!-- end container -->
	</div>

</template>
<script type="text/javascript">
	export default{
		name: 'group-trips',
		props: ['id', 'campaignId'],
		data(){
			return {
				group:{},
				trips: [],
				page: 1,
				pagination: { current_page: 1 }
			}
		},
		methods: {
			getTrips(){
				let resource = this.$resource('trips', {
					include: "costs",
					onlyPublished: true,
					groups: new Array(this.id),
					campaign: this.campaignId,
					//per_page: 8,
					//search: this.searchText,
					page: this.pagination.current_page,
				});
				// this.$refs.spinner.show();
				resource.query().then(function (response) {
					this.pagination = response.data.meta.pagination;
					this.trips = response.data.data;

					let cId = this.campaignId, calcLowest = this.calcStartingCost;
					_.each(this.trips, function (trip, index, list) {
						list[index].lowest = calcLowest(trip.costs.data);
					});
				}, function (error) {
					// this.$refs.spinner.hide();
				});
			},
			calcStartingCost(costs) {
				let lowest;
				costs.forEach(function(val, i) {
					if (val.amount < lowest || isNaN(lowest) ) {
						lowest = val.amount;
					}
				});
				return lowest;
			}
		},
		ready(){
			if (this.id && this.campaignId && this.id.length>0 && !this.$parent.currentView) {
				this.getTrips();
			}
		},
		activate(done){
			this.id = this.$parent.groupId;
			this.campaignId = this.$parent.campaignId;
			this.$http.get('groups/' + this.id).then(function (response) {
				this.group = response.data.data;
			});
			this.getTrips();
			done();
		}
	}
</script>
