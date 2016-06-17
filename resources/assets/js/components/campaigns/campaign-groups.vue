<template>
	<div class="container">
		<div class="col-sm-12">
			<input type="text" class="form-control" v-model="searchText" debounce="500"
				   placeholder="Search for a group">
			<br>
		</div>

		<div class="col-sm-4 col-md-3"
			 v-for="group in groups">
			<div class="thumbnail">
				<img :src="'http://lorempixel.com/242/200/people/' + $index" :alt="group.name">
				<div class="caption">
					<h4>{{group.name}}</h4>
					<p>
						<a href="#" class="btn btn-primary btn-block" role="button">Select</a>
					</p>
				</div>
			</div>
		</div>

		<div class="col-sm-12 text-center">
			<nav>
				<ul class="pagination">
					<li :class="{ 'disabled': pagination.current_page == 1 }">
						<a aria-label="Previous" @click="page=pagination.current_page-1">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
					<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
					<li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
						<a aria-label="Next" @click="page=pagination.current_page+1">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'campaign-groups',
		props: ['id'],
		data(){
			return {
				groups: [],
				page: 1,
				pagination: {},
				searchText: ''
			}
		},
		watch: {
			'searchText': function (val, oldVal) {
				this.page = 1;
				this.searchGroups();
			},
			'page': function (val, oldVal) {
				this.searchGroups();
			}
		},
		methods: {
			searchGroups() {
				var resource = this.$resource('trips', {
					include: "group",
					campaign: this.id,
					per_page: 8,
					search: this.searchText,
					page: this.page
				});

				resource.query().then(function (trips) {
					this.pagination = trips.data.meta.pagination;
					var arr = [];
					for (var i in trips.data.data) {
						arr.push(trips.data.data[i].group.data)
					}
					this.groups = arr;
				})
			}
		},
		ready(){
			this.searchGroups();
		}
	}
</script>
