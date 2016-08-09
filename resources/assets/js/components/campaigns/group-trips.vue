<template>
	<div class="dark-bg-primary">
	<div class="container">
		<hr class="divider inv xlg">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0 text-center">
				<img class="img-circle img-lg" src="{{ group.avatar }}">
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
			<div class="col-xs-12">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Type</th>
					<th>Dates</th>
					<th>Starting Cost</th>
					<th>Spots Available</th>
					<th>Ideal For</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="trip in trips" style="border-bottom: 1px solid #e6e6e6">
					<td style="text-transform: capitalize;vertical-align:middle;">{{ trip.type }}</td>
					<td style="vertical-align:middle;">{{ trip.started_at | moment 'll'}} - {{ trip.ended_at | moment 'll'}}</td>
					<td style="vertical-align:middle;">{{ trip.lowest | currency }}</td>
					<td style="vertical-align:middle;">{{ trip.spots }}</td>
					<td style="vertical-align:middle;">
						<span v-for="prospect in trip.prospects">
							{{ prospect | capitalize }}<span v-show="$index + 1 != trip.prospects.length">, </span> 
						</span>
					</td>
					<td class="text-right"><a href="/trips/{{ trip.id }}" class="btn btn-primary-hollow btn-sm">Select</a></td>
				</tr>
				</tbody>
			</table>
			</div>
		</div><!-- end row -->
		<hr class="divider inv xlg">
	</div><!-- end container -->
</template>
<script>
	export default{
		name: 'group-trips',
		props: ['id', 'campaignId'],
		data(){
			return {
				group:{},
				trips: [],
				page: 1
			}
		},
		methods: {
			getTrips(){
				var resource = this.$resource('groups{/id}', {
					include: "trips.costs",
					//campaign: this.id,
					//per_page: 8,
					//search: this.searchText,
					page: this.page
				});

				resource.query({id: this.id}).then(function (group) {
					this.group = group.data.data;
					var t = this.group.trips.data, cId = this.campaignId, arr = [], calcLowest = this.calcStartingCost;
					t.forEach(function (val, i) {
						if( val.campaign_id === cId) {
							val.lowest = calcLowest(val.costs.data);
							arr.push(val);
						}

					});
					this.trips = arr;
				});
			},
			calcStartingCost(costs) {
				var lowest;
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
			this.getTrips();
			done();
		}
	}
</script>
