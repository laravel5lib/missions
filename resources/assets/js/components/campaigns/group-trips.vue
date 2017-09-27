<template>
	<div>
		<div class="white-header-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-8">
                		<h3 class="hidden-xs">
                			<a :href="'/'+group.url"><img class="img-circle img-sm av-left" :src="group.avatar">
							{{ group.name }}</a>
						</h3>
						<div class="visible-xs text-center">
							<hr class="divider inv">
							<a :href="group.url"><img class="img-circle img-md av-left" :src="group.avatar"></a>
							<h4 style="margin-bottom:0;">{{ group.name }}</h4>
						</div>
					</div><!-- end col -->
					<div class="col-sm-4 text-right hidden-xs">
						<hr class="divider inv">
                		<hr class="divider inv sm">
						<a v-show="currentView!='groupSelection'" @click="restartView"  class="btn btn-default"><span class="fa fa-chevron-left icon-left"></span> Change Group</a>
						<hr class="divider inv">
					</div><!-- end col -->
					<div class="col-xs-12 text-center visible-xs">
						<hr class="divider inv sm">
						<a v-show="currentView!='groupSelection'" @click="restartView"  class="btn btn-default"><span class="fa fa-chevron-left icon-left"></span> Change Group</a>
						<hr class="divider inv">
					</div><!-- end col -->
				</div>
			</div>
		</div>
		<div class="container">
			<hr class="divider inv lg">
			<div class="row">
				<div class="col-xs-12 text-center">
					<h2>Choose A Trip Type</h2>
					<p>If you don't see the trip type you desire, try choosing a different group to travel with.</p>
					<hr class="divider red-small lg">
					<hr class="divider inv">
				</div>
			</div>
			<div class="row flex-container">
				<div v-for="(trip, index) in trips" class="flex-row">
					<div class="flex-col">
						<div class="panel panel-default flex-item">
							<div class="panel-heading" :class="'panel-' + trip.type">
								<h5 class="text-uppercase text-center">{{ trip.type|capitalize }}</h5>
							</div>
							<div class="panel-body text-center">
								<p class="badge">{{ trip.status|capitalize }}</p><br>
								<p class="small">{{ trip.started_at | moment('ll', false, true)}} - {{ trip.ended_at | moment('ll', false, true)}}</p>
								<label>Perfect For</label>
								<p class="small"><span v-for="prospect in limitedProspects(trip.prospects, 3)">
									{{ prospect|capitalize }}<span v-show="index + 1 != trip.prospects.length">, </span>
							</span><span v-show="trip.prospects.length > 3">...</span></p>
								<label>Spots Available</label>
								<p>{{ trip.spots }}</p>
								<label>Starting At</label>
								<h3 style="margin-top:0px;" class="text-success">{{ currency(trip.starting_cost) }}</h3>
								<a :href="'/trips/' + trip.id" class="btn btn-primary-hollow btn-sm">Select</a>
							</div>
						</div>
					</div><!-- end flex-direction -->
				</div><!-- end flex -->
			</div><!-- end row -->

			<div class="container">
				<div class="col-sm-12 text-center">
					<pagination :pagination="pagination" pagination-key="pagination" :callback="getTrips"></pagination>
				</div>
			</div><!-- end container -->
			<hr class="divider inv xlg">
		</div><!-- end container -->
	</div>

</template>
<script type="text/javascript">
	export default{
		name: 'group-trips',
		props: ['groupId', 'campaign'],
		data(){
			return {
				group:{},
				trips: [],
				page: 1,
				pagination: { current_page: 1 },
			}
		},
		computed: {
            currentView() {
                return this.$parent.currentView;
            }
		},
		methods: {
            limitedProspects(propects, limit) {
                return propects.slice(0, limit);
            },
			getTrips(){
				let resource = this.$resource('trips', {
					include: "costs",
					onlyPublished: true,
					onlyPublic: true,
					groups: new Array(this.groupId),
					campaign: this.campaignId,
					sort: 'spots|desc',
					//per_page: 8,
					//search: this.searchText,
					page: this.pagination.current_page,
				});
				// this.$refs.spinner.show();
				resource.query().then((response) => {
					this.pagination = response.data.meta.pagination;
					this.trips = response.data.data;

					let cId = this.campaignId, calcLowest = this.calcStartingCost;
					_.each(this.trips, function (trip, index, list) {
						list[index].lowest = calcLowest(trip.costs.data);
					});
				}, (error) =>  {
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
			},
            restartView() {
			    this.$parent.restartView();
            }
		},
		mounted(){
            this.$http.get('groups/' + this.groupId).then((response) => {
                this.group = response.data.data;
            });
            this.getTrips();

			if (this.groupId && this.campaignId && this.groupId.length>0 && !this.$parent.currentView) {
				this.getTrips();
			}
		},
		activated(){
//			this.groupId = this.$parent.groupId;
//			this.campaign = this.$parent.campaignId;

		}
	}
</script>
