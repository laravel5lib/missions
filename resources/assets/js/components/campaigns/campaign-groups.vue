<template>
	<div>
		<div class="dark-bg-primary">
			<div class="container">
				<hr class="divider inv xlg">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
						<hr class="divider inv">
						<h3 class="text-center">First, find a group to travel with.</h3>
						<p class="small">If you don't have a group, choose Missions.Me and we'll help place you on a team.</p>
						<input type="text" class="form-control" v-model="searchText"
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
			<div class="container flex-container">
				<!--<spinner ref="spinner" size="sm" text="Loading"></spinner>-->
					<div v-for="group in groups" class="flex-row">
						<div class="flex-col">
							<div class="panel panel-default flex-item">
								<div class="panel-body">
								<a role="button" @click="selectGroup(group)">
									<h5 style="margin:0px;">
									<img :src="group.avatar" :alt="group.name" class="av-left img-circle img-xs">
									{{group.name | truncate(30, '...')}}
									</h5>
								</a>
								</div><!-- end panel-body -->
							</div><!-- end panel -->
						</div>
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
				<pagination :pagination="pagination" pagination-key="pagination" :callback="searchGroups"></pagination>
			</div>
		</div><!-- end container -->
	</div>
</template>
<script type="text/javascript">
	import _ from 'underscore';
	export default {
		name: 'campaign-groups',
		props: ['campaign'],
		data(){
			return {
				groups: [],
				page: 1,
				pagination: {current_page: 1},
				searchText: '',
			}
		},
		watch: {
			'searchText'(val, oldVal) {
				this.pagination.current_page = 1;
				this.page = 1;
				this.debouncedSearchGroups();
			},
			'page'(val, oldVal) {
				this.searchGroups();
			}
		},
		methods: {
		    debouncedSearchGroups: _.debounce(function () {
                this.searchGroups();
            }, 500),
			searchGroups() {
				// search public groups with public published trips belongs to the given campaign id
				// search by trip type, trip prospects, group type, and group name
				let resource = this.$resource('trips', {
					include: "group",
					onlyPublished: true,
					onlyPublic: true,
					campaign: this.campaign,
					per_page: 12,
					search: this.searchText,
					page: this.pagination.current_page
				});

				resource.query().then((trips) => {
					this.pagination = trips.data.meta.pagination;
					let arr = [];
					for (let i in trips.data.data) {
						arr.push(trips.data.data[i].group.data)
					}

					arr = _.uniq(arr, function(item, key, id) { 
    					return item.id;
					});

					this.groups = _.filter(arr, function(group) {
						return group['public'] == true;
					});
				}, this.$root.handleApiError);
			},
			selectGroup(group) {
				if (this.$parent.currentView) {
					this.$parent.groupId = group.id;
					this.$parent.currentView = 'tripSelection';
				}
			}
		},
		mounted(){
			this.searchGroups();
		},
		activated(){
//			this.campaign = this.$parent.campaignId;
		}
	}
</script>
