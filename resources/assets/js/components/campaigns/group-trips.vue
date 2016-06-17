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
				<td>{{ trip.costs.data[0].amount | currency }}</td>
				<td>{{ trip.spots }}</td>
				<td>{ list of prospects }</td>
				<td><a class="btn btn-primary btn-sm">Join Group</a></td>
			</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	export default{
		name: 'group-trips',
		props: ['id'],
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
					this.trips = this.group.trips.data
				});
			}
		},
		ready(){
			if (this.id && this.id.length>0 && !this.$parent.currentView) {
				this.getTrips();
			}
		},
		activate(done){
			this.id = this.$parent.groupId;
			this.getTrips();
			done();
		}
	}
</script>
