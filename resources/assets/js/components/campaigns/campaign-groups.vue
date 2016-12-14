<template>
	<div>
		<div class="dark-bg-primary">
			<div class="container">
				<hr class="divider inv xlg">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
						<hr class="divider inv">
						<h6 class="text-center text-uppercase">Find the trip that's right for you.</h6>
						<!--<h6 class="text-center text-uppercase">Which group would you like to travel with?</h6>-->
						<input type="text" class="form-control" v-model="searchText" debounce="500"
							   placeholder="Search anything .. (i.e. medical, teens, oakland church)">
						<hr class="divider inv sm">
						<p class="small text-center">Then select a participating travel group below.</p>
						<hr class="divider inv">
					</div>
				</div>
				<hr class="divider inv xlg">
			</div>
		</div>
		<hr class="divider inv xlg">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>

		<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;" v-if="groups.length > 0">
			<div class="col-xs-6 col-sm-4 col-md-3" v-for="group in groups" style="display:flex"
				 v-if="groups.length > 0">
				<div class="panel panel-default">
					<a role="button" @click="selectGroup(group)">
						<img :src="group.avatar" :alt="group.name" class="img-responsive">
						<div class="panel-body">
							<h5 class="text-center">{{group.name}}</h5>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="container text-center" v-else>
			<p class="lead">Sorry, we couldn't find any participating groups.</p>
			<p><a href="#">Don't see your group?</a></p>
		</div>
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
				this.page = 1;
				this.searchGroups();
			},
			'page': function (val, oldVal) {
				this.searchGroups();
			}
		},
		methods: {
			searchGroups() {
				let resource = this.$resource('trips', {
					include: "group",
					onlyPublished: true,
					campaign: this.id,
					per_page: 8,
					search: this.searchText,
					page: this.pagination.current_page
				});

				this.$refs.spinner.show();
				resource.query().then(function (trips) {
					this.pagination = trips.data.meta.pagination;
					let arr = [];
					for (let i in trips.data.data) {
						arr.push(trips.data.data[i].group.data)
					}
					this.groups = arr;
					this.$refs.spinner.hide();
				}, function (error) {
					this.$refs.spinner.hide();
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
