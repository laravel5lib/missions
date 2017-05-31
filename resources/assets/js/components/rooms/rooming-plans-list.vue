<template>
	<div>
		<aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
					          :value.sync="filters.group" :options="groupsOptions" label="name"
					          placeholder="Filter by Group"></v-select>
				</div>

				<div class="form-group" v-if="!tripId">
					<label>Campaign</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
					          :value.sync="filters.campaign" :options="campaignsOptions" label="name"
					          placeholder="Filter by Campaign"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>

		<div class="row">
			<div class="col-sm-12">
				<form class="form-inline" novalidate>
					<div class="form-inline" style="display: inline-block;">
						<div class="form-group">
							<label>Show</label>
							<select class="form-control  input-sm" v-model="per_page">
								<option v-for="option in perPageOptions" :value="option">{{option}}</option>
							</select>
						</div>
					</div>
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="search" debounce="500" placeholder="Search for anything">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
					<button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
						Filters
						<i class="fa fa-filter"></i>
					</button>
					<button class="btn btn-primary btn-sm" type="button" @click="openNewPlanModal">
						Create a plan
					</button>
				</form>
			</div>
		</div>
		<hr class="divider sm">
		<div>
			<label>Active Filters</label>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.group != null" @click="filters.groups = null" >
				Travel Group
				<i class="fa fa-close"></i>
			</span>
			<span style="margin-right:2px;" class="label label-default" v-show="filters.campaign != null" @click="filters.campaign = null" >
				Campaign
				<i class="fa fa-close"></i>
			</span>
		</div>
		<hr class="divider sm">
		<div style="position:relative;" class="panel panel-default">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Name</th>
					<th>Rooms</th>
					<th>Occupants</th>
					<th><i class="fa fa-cog"></i></th>
				</tr>
				</thead>
				<tbody v-if="plans.length">
				<tr v-for="plan in plans|orderBy 'name'">
					<td style="cursor: pointer;" v-text="plan.name" @click="loadManager(plan)"></td>
					<td style="cursor: pointer;" @click="loadManager(plan)">
						<span v-for="(key, val) in plan.rooms_count">
							<p style="line-height:1;font-size:11px;margin-bottom:2px;display:inline-block;"><span v-if="$index != 0"> &middot; </span>{{key | capitalize}}: <strong>{{val}}</strong></p>
						</span>
					</td>
					<td style="cursor: pointer;" @click="loadManager(plan)" v-text="plan.occupants_count"></td>
					<td>
						<button type="button" class="btn btn-default-hollow btn-xs" @click="openDeletePlanModal(plan)">
							<i class="fa fa-trash"></i>
						</button>
					</td>
				</tr>
				</tbody>
				<tbody v-else>
				<tr>
					<td colspan="4">
						<p class="text-center text-italic text-muted"><em>
						No Rooming Plans yet. Create a new Rooming Plan.
						</em></p>
					</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="4" class="text-center">
						<pagination :pagination.sync="pagination"
						            :callback="getRoomingPlans"
						            size="small">
						</pagination>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>

		<!-- Modals -->
		<modal title="Create a new Plan" small ok-text="Create" :callback="newPlan" :show.sync="showPlanModal">
			<div slot="modal-body" class="modal-body">
				<validator name="PlanCreate">
					<form id="PlanCreateForm">
						<div class="form-group" :class="{'has-error': $PlanCreate.planname.invalid}">
							<label for="createPlanCallsign" class="control-label">Plan Name</label>
							<input @keydown.enter.prevent="newPlan" type="text" class="form-control" id="createPlanCallsign" placeholder="" v-validate:planname="['required']" v-model="selectedNewPlan.name">
						</div>
					</form>
				</validator>
			</div>
		</modal><modal title="Delete Rooming Plan" small ok-text="Delete" :callback="deletePlan" :show.sync="showPlanDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedDeletePlan">
					Are you sure you want to delete plan: "{{selectedDeletePlan.name}}" ?
				</p>
			</div>
		</modal>

	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    export default{
        name: 'rooming-plans-list',
	    components: {vSelect},
        data(){
            return {
                plans: [],
                showFilters: false,
	            search: '',
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                groupsOptions: [],
                campaignsOptions: [],
	            filters: {
                    campaign: null,
		            group: null,
	            },
                pagination: {current_page: 1},
                showPlanDeleteModal: false,
                showPlanModal: false,
                selectedDeletePlan: null,
                selectedNewPlan: {
                    name: '',
                    short_desc: '',
                    rooms: [],
                    locked: false,
                    campaign_id: this.$parent.campaignId,
                    group_id: this.$parent.groupId,
                },
	            PlansResource: this.$resource('rooming/plans{/plan}')
            }
        },
	    watch: {
            filters: {
                handler(val) {
                    this.getRoomingPlans();
                },
	            deep: true
            },
		    search() {
                this.getRoomingPlans();
		    },
            per_page() {
                this.getRoomingPlans();
		    },
	    },
        methods: {
            resetFilters(){
                this.filters = {};
            },
            loadManager(plan) {
                this.$dispatch('rooming-wizard:plan-selected', plan);
            },
	        getRoomingPlans(){
                let params = {
                    include: '',
                };
                params = _.extend(params, {
                    campaign: this.filters.campaign ? this.filters.campaign.id : null,
                    group: this.filters.group ? this.filters.group.id : null,
	                search: this.search,
	                per_page: this.per_page,
                });

                return this.PlansResource.get(params).then(function (response) {
	                this.pagination = response.body.meta.pagination;
	                this.plans = response.body.data;
                })
	        },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('groups', { params: {search: search} }).then(function (response) {
                    this.groupsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                });
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('campaigns', { params: {search: search} }).then(function (response) {
                    this.campaignsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                });
            },
            openDeletePlanModal(plan) {
                this.showPlanDeleteModal = true;
                this.selectedDeletePlan = plan;
            },
            deletePlan() {
                let plan = _.extend({}, this.selectedDeletePlan);
                this.PlansResource.delete({ plan: plan.id}).then(function (response) {
                    this.showPlanDeleteModal = false;
                    this.$root.$emit('showInfo', plan.name + ' Deleted!');
                    this.plans = _.reject(this.plans, function (obj) {
                        return plan.id === obj.id;
                    });
                });
            },
            openNewPlanModal(){
                this.showPlanModal = true;
                this.selectedNewPlan = {
                    name: '',
                    short_desc: '',
                    rooms: [],
                    locked: false,
                    campaign_id: this.$parent.campaignId,
                    group_id: this.$parent.groupId,
                };
            },
            newPlan() {
                if (this.$PlanCreate.valid) {
                    return this.PlansResource.save(this.selectedNewPlan).then(function (response) {
                        let plan = response.body.data;
                        this.plans.push(plan);
                        this.showPlanModal = false;
                        this.loadManager(plan);
                    }, function (response) {
                        console.log(response);
                        this.$root.$emit('showError', response.body.message);
                        return response.body.data;
                    });
                } else {
                    this.$root.$emit('showError', 'Please provide a name for the new plan');
                }
            },
        },
        ready(){
			this.getRoomingPlans();
        }
    }
</script>