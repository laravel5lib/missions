<template>
	<div class="container">
		<div class="col-sm-12">
			<input type="text" class="form-control" v-model="searchText" placeholder="Search for a group">
			<br>
		</div>

		<div class="col-sm-4 col-md-3" v-for="group in groups | filterBy searchText in 'name' 'description' 'email' 'state'">
			<div class="thumbnail">
				<img v-bind:src="'http://lorempixel.com/242/200/people/' + $index" v-bind:alt="group.name">
				<div class="caption">
					<h4>{{group.name}}</h4>
					<p>
						<a href="#" class="btn btn-primary btn-block" role="button">Select</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    export default{
        name: 'campaign-groups',
		props: ['id'],
        data(){
            return{
                groups:[],
				searchText: ''
            }
        },
        ready(){
            var resource = this.$resource('campaigns{/id}', { include: "trips.group" });

            resource.query({id: this.id}).then(function(campaign){
				for (var i in campaign.data.data.trips.data) {
					this.groups.push(campaign.data.data.trips.data[i].group.data)
				}
            })
        }
    }
</script>
