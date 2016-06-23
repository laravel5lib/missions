<template>
	<div class="row">
		<h4>{{ group.name }}</h4>
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Trip Type</th>
				<th>Trip Starting Cost</th>
				<th>Spots Available</th>
				<th>Ideal For</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<tr v-for="trip in trips">
				<td style="text-transform: capitalize;">{{ trip.type }}</td>
				<td>{{ trip.lowest | currency }}</td>
				<td>{{ trip.spots }}</td>
				<td>
					<span v-for="prospect in trip.prospects">
						{{ prospect | capitalize }}<span v-show="$index + 1 != trip.prospects.length">, </span> 
					</span>
				</td>
				<td><a class="btn btn-primary btn-sm">Join Group</a></td>
			</tr>
			</tbody>
		</table>
	</div>
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
