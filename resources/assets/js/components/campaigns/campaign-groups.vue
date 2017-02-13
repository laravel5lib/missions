<template>
	<div>
		<div class="dark-bg-primary">
			<div class="container">
				<hr class="divider inv xlg">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
						<hr class="divider inv">
						<h3 class="text-center">First, find a group to travel with.</h3>
						<input type="text" class="form-control" v-model="searchText" debounce="500"
							   placeholder="Search anything (i.e. medical, teens, church name)">
						<hr class="divider inv sm">
						<p class="small text-center">Next, you'll pick your trip type.</p>
						<hr class="divider inv">
					</div>
				</div>
				<hr class="divider inv xlg">
				<h3 class="text-center"><i class="fa fa-chevron-down"></i></h3>
			</div>
		</div>
		<hr class="divider inv xlg">

		<template v-if="groups.length > 0">
			<div class="container">
				<!--<spinner v-ref:spinner size="sm" text="Loading"></spinner>-->
				<div class="col-xs-12 col-sm-6 col-md-4" v-for="group in groups">
					<div class="panel panel-default">
						<div class="panel-body">
						<a role="button" @click="selectGroup(group)">
							<h5 style="margin:0px;">
							<img :src="group.avatar" :alt="group.name" class="av-left img-circle img-xs">
							{{group.name | truncate 30 '...'}}
							</h5>
						</a>
						</div><!-- end panel-body -->
					</div><!-- end panel -->
				</div><!-- end col -->
			</div>
		</template>
		<template v-else>
			<div class="container text-center">
				<p class="lead text-muted">
				<em>We did not find any participating groups.</em>
				<br />
				<a class="text-primary small" href="/groups">
					<i class="fa fa-arrow-circle-o-right"></i>  Consider taking your own group.
				</a>
				</p>
			</div>
		</template>

		<div class="container">
			<div class="col-sm-12 text-center">
				<pagination :pagination.sync="pagination" :callback="searchGroups"></pagination>
			</div>
		</div><!-- end container -->
	</div>
</template>
<script type="text/javascript">
	export default {
		name: 'campaign-groups',
		props: ['id'],
		data(){
			return {
				groups: [],
				page: 1,
				pagination: {current_page: 1},
				searchText: ''
			}
		},
		watch: {
			'searchText': function (val, oldVal) {
				this.pagination.current_page = 1;
				this.page = 1;
				this.searchGroups();
			},
			'page': function (val, oldVal) {
				this.searchGroups();
			}
		},
		methods: {
			searchGroups() {
				// search public groups with public published trips belongs to the given campaign id
				// search by trip type, trip prospects, group type, and group name
				let resource = this.$resource('trips', {
					include: "group",
					onlyPublished: true,
					onlyPublic: true,
					campaign: this.id,
					per_page: 12,
					search: this.searchText,
					page: this.pagination.current_page
				});

				resource.query().then(function (trips) {
					this.pagination = trips.data.meta.pagination;
					let arr = [];
					for (let i in trips.data.data) {
						arr.push(trips.data.data[i].group.data)
					}
					this.groups = _.filter(arr, function(group) {
						return group['public'] == true;
					});
				}, function (error) {
					//TODO error alert message
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
