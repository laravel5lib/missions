<template>
	<div>
		<aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Groups</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
					          :value.sync="groupsArr" :options="groupsOptions" label="name"
					          placeholder="Filter Groups"></v-select>
				</div>
				<div class="form-group">
					<label>Managing Users</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="userFilter" multiple :debounce="250" :on-search="getUsers"
					          :value.sync="usersArr" :options="usersOptions" label="name"
					          placeholder="Filter Users"></v-select>
				</div>
				<div class="form-group" v-if="!tripId">
					<label>Campaign</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
					          :value.sync="campaignObj" :options="campaignOptions" label="name"
					          placeholder="Filter by Campaign"></v-select>
				</div>

				<div class="form-group">
					<label>Trip Type</label>
					<select  class="form-control input-sm" v-model="filters.type">
						<option value="">Any Type</option>
						<option value="ministry">Ministry</option>
						<option value="family">Family</option>
						<option value="international">International</option>
						<option value="media">Media</option>
						<option value="medical">Medical</option>
						<option value="leader">Leader</option>
					</select>
				</div>

				<div class="form-group">
					<label>Gender</label>
					<select class="form-control input-sm" v-model="filters.gender" style="width:100%;">
						<option value="">Any Genders</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select>
				</div>

				<div class="form-group">
					<label>Marital Status</label>
					<select class="form-control input-sm" v-model="filters.status" style="width:100%;">
						<option value="">Any Status</option>
						<option value="single">Single</option>
						<option value="married">Married</option>
					</select>
				</div>

				<!-- Cost/Payments -->
				<div class="form-group">
					<label>Applied Cost</label>
					<select class="form-control input-sm" v-model="filters.dueName" style="width:100%;">
						<option value="">Any Cost</option>
						<option v-for="option in dueOptions" v-bind:value="option">
							{{ option }}
						</option>
					</select>
				</div>
				<div class="form-group" v-if="filters.dueName">
					<label>Payment Status</label>
					<select class="form-control input-sm" v-model="filters.dueStatus" style="width:100%;">
						<option value="">Any Status</option>
						<option value="overdue">Overdue</option>
						<option value="late">Late</option>
						<option value="extended">Extended</option>
						<option value="paid">Paid</option>
						<option value="pending">Pending</option>
					</select>
				</div>
				<!-- end cost/payments -->

				<div class="form-group">
					<label>Arrival Designation</label>
					<select  class="form-control input-sm" v-model="filters.designation">
						<option value="">Any</option>
						<option value="eastern">Eastern</option>
						<option value="western">Western</option>
						<option value="international">International</option>
						<option value="none">None</option>
					</select>
				</div>

				<!-- Requirements -->
				<div class="form-group">
					<label>Requirements</label>
					<select class="form-control input-sm" v-model="filters.requirementName" style="width:100%;">
						<option value="">Any Requirement</option>
						<option v-for="option in requirementOptions" v-bind:value="option">
							{{ option }}
						</option>
					</select>
				</div>
				<div class="form-group" v-if="filters.requirementName">
					<select class="form-control input-sm" v-model="filters.requirementStatus" style="width:100%;">
						<option value="">Any Status</option>
						<option value="incomplete">Incomplete</option>
						<option value="reviewing">Reviewing</option>
						<option value="attention">Attention</option>
						<option value="complete">Complete</option>
					</select>
				</div>
				<!-- end requirements -->

				<!-- Todos -->
				<div class="form-group">
					<label>Todos</label>
					<select class="form-control input-sm" v-model="filters.todoName" style="width:100%;">
						<option value="">Any Todo</option>
						<option v-for="option in todoOptions" v-bind:value="option">
							{{ option }}
						</option>
					</select>
				</div>
				<div class="form-group" v-if="filters.todoName">
					<label class="radio-inline">
						<input type="radio" name="companions" id="companions1" v-model="filters.todoStatus" :value="null"> Any
					</label>
					<label class="radio-inline">
						<input type="radio" name="companions" id="companions2" v-model="filters.todoStatus" value="complete"> Complete
					</label>
					<label class="radio-inline">
						<input type="radio" name="companions" id="companions3" v-model="filters.todoStatus" value="incomplete"> Incomplete
					</label>
				</div>
				<!-- end todos -->

				<!-- Trip Rep -->
				<div class="form-group">
					<label>Trip Rep</label>
					<select class="form-control input-sm" v-model="filters.rep" style="width:100%;">
						<option value="">Any Rep</option>
						<option v-for="option in repOptions" v-bind:value="option.id">
							{{ option.name | capitalize }}
						</option>
					</select>
				</div>
				<!-- end trip rep -->

				<div class="form-group">
					<label>Shirt Size</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="ShirtSizeFilter" :value.sync="shirtSizeArr" multiple
					          :options="shirtSizeOptions" label="name" placeholder="Shirt Sizes"></v-select>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12">
							<label>Age Range</label>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Age Min</span>
								<input type="number" class="form-control" number v-model="ageMin" min="0">
							</div>
						</div>
						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">Max</span>
								<input type="number" class="form-control" number v-model="ageMax" max="120">
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>Travel Companions</label>
					<div>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions1" v-model="filters.hasCompanions" :value="null"> Any
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions2" v-model="filters.hasCompanions" value="yes"> Yes
						</label>
						<label class="radio-inline">
							<input type="radio" name="companions" id="companions3" v-model="filters.hasCompanions" value="no"> No
						</label>
					</div>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</aside>

	</div>
</template>
<style></style>
<script type="text/javascript">
    export default{
        name: 'rooming-plans-list',
        data(){
            return {
                showFilters: false,
	            filters: {
                    campaign: '',
		            group: '',
	            },
	            PlansResource: this.$resource('rooming/plans{/plan}')
            }
        },
        methods: {
            resetFilters(){
                this.filters = {};
            },
	        getRoomingPlans(){
                let params = {
                    include: ''
                };
                params = _.extend(params, this.filters);

                return this.PlansResource.get(params).then(function (response) {
	                this.plansPagination = response.body.meta.pagination;
	                this.plans = response.body.data;
                })
	        },
        },
        ready(){

        }
    }
</script>