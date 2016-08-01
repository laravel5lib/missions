<template>
<div>
	<div class="dark-bg-primary">
		<div class="container">
			<hr class="divider inv xlg">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-1">
					<hr class="divider inv">
					<h6 class="text-center text-uppercase">Which group would you like to travel with?</h6>
					<input type="text" class="form-control" v-model="searchText" debounce="500" placeholder="Search for a group">
					<hr class="divider inv sm">
					<p class="small text-center">Don't See Your Group?</p>
					<hr class="divider inv">
				</div>
			</div>
			<hr class="divider inv xlg">
		</div>
	</div>
	<hr class="divider inv xlg">
	<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
			<div class="col-xs-6 col-sm-4 col-md-3" v-for="group in groups" style="display:flex">
				<div class="panel panel-default">
					<a role="button" @click="selectGroup(group)">
						<img :src="'http://lorempixel.com/242/200/people/' + $index" :alt="group.name" class="img-responsive">
					<div class="panel-body">
						<h5 class="text-center">{{group.name}}</h5>
					</div>
					</a>
				</div>
			</div>
	</div>
	<div class="container">
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
	</div><!-- end container -->
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
				});
			},
			selectGroup(group) {
				if (this.$parent.currentView) {
					this.$parent.groupId = group.id;
					this.$parent.currentView = 'tripSelection';
				}
			}
		},
		ready(){
			this.searchGroups();
		},
		activate(done){
			this.id = this.$parent.campaignId;
			done();
		}
	}
</script>
