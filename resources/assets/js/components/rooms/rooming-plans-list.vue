<template>
	<div>
		<mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Travel Group</label>
					<v-select @keydown.enter.prevent=""  class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
					          v-model="filters.groups" :options="groupsOptions" label="name"
					          placeholder="Filter by Groups"></v-select>
				</div>

				<hr class="divider inv sm">
				<button class="btn btn-default btn-sm btn-block" type="button" @click="resetPlansFilter()"><i class="fa fa-times"></i> Reset Filters</button>
			</form>
		</mm-aside>

		<template v-if="isAdminRoute">
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
							<input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for anything">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
						</div>
						<button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
							Filters
							<i class="fa fa-filter"></i>
						</button>
						<button class="btn btn-primary btn-sm" type="button" @click="openNewPlanModal">
							Create a plan
						</button>
						<rooming-reports :filters="filters" :search="search" :campaign="campaignId"></rooming-reports>
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
		</template>
		<div style="position:relative;" class="panel panel-default">

            <div class="list-group" v-if="plans.length">
              <div class="list-group-item" v-for="(plan, planIndex) in plans">

                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="list-group-item-heading">
                            <i class="fa fa-lock text-muted" v-if="plan.locked"></i> <a @click="loadManager(plan)">{{ plan.name }}</a>
                            <span class="badge">{{ plan.occupants_count }} occupants</span>
                            <br />
	                        <small>
		                        <template v-for="(group, groupIndex) in plan.groups.data">
			                        {{ group.name }}
			                        <template v-if="(groupIndex + 1) < plan.groups.data.length">&middot;</template>
		                        </template>
	                        </small>
                        </h4>
                        <p class="list-group-item-text">{{ plan.short_desc }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Rooms</label><br />
                        <span v-for="(val, key, index) in plan.rooms_count">
                            <p style="line-height:1;font-size:11px;margin-bottom:2px;display:inline-block;"><span v-if="index != 0"> &middot; </span>{{ key|capitalize }}: <strong>{{val}}</strong></p>
                        </span>
                        <br />
                        <label>Rooms Allowed</label><br />
                        <span v-for="(val, key, index) in plan.room_types">
                            <p style="line-height:1;font-size:11px;margin-bottom:2px;display:inline-block;"><span v-if="index != 0"> &middot; </span>{{ key|capitalize }}: <strong>{{val}}</strong></p>
                        </span>
                        <p>
                            <hr class="divider sm">
                            <button type="button" class="btn btn-primary btn-xs" @click="loadManager(plan)">Select Plan</button>
                            <button type="button" class="btn btn-default-hollow btn-xs" @click="openPlanSettingsModal(plan)" v-if="isAdminRoute">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-default-hollow btn-xs" @click="openDeletePlanModal(plan)" v-if="isAdminRoute">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </p>
                    </div>
                </div>
              </div>
              <div class="text-center">
                  <pagination :pagination="pagination" pagination-key="pagination"
                              :callback="getRoomingPlans"
                              size="small">
                  </pagination>
              </div>
            </div>
            <p class="text-center text-italic text-muted" v-else><em>
            No Rooming Plans yet. Create a new Rooming Plan.
            </em></p>
		</div>

		<!-- Modals -->
		<modal title="Create a new Plan" small ok-text="Create" :callback="newPlan" :value="showPlanModal" @closed="showPlanModal=false">
			<div slot="modal-body" class="modal-body">

					<form id="PlanCreateForm" data-vv-scope="plan-create">
						<div class="form-group" :class="{'has-error': errors.has('planname', 'plan-create')}">
							<label for="createPlanCallsign" class="control-label">Plan Name</label>
							<input @keydown.enter.prevent="" type="text" class="form-control" id="createPlanCallsign" placeholder="" name="planname" v-validate="'required'" v-model="selectedNewPlan.name">
						</div>

						<div class="form-group" :class="{'has-error': errors.has('plandesc', 'plan-create')}">
							<label for="createPlanDesc" class="control-label">Short Description</label>
							<textarea autosize="selectedNewPlan.short_desc" class="form-control" id="createPlanDesc" v-model="selectedNewPlan.short_desc" name="plandesc" v-validate="'required'"></textarea>
						</div>

						<div class="form-group" :class="{'has-error': errors.has('teamgroup', 'plan-create')}" v-if="isAdminRoute">
							<label>Travel Groups</label>
							<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
							          v-model="selectedNewPlan.groups" :options="groupsOptions" label="name" data-vv-name="teamgroup" v-validate="'required|min:1'" data-vv-scope="plan-create" data-vv-value-path="value"
							          placeholder="Assign Travel Groups"></v-select>
							<!--<select class="hidden" v-model="selectedNewPlan.groups" multiple name="teamgroup" v-validate="'required'">
								<option :value="group" v-for="group in groupsOptions">{{ group.name|capitalize }}</option>
							</select>-->
						</div>

						<div class="row">
							<div class="form-group" v-for="type in roomTypes">
							<label :for="'settingsType-' + type.id" class="col-sm-6 control-label" v-text="type.name"></label>
								<div class="col-sm-6">
									<input type="number" number class="form-control" :id="'settingsType-' + type.id" v-model="selectedNewPlan.room_types_settings[type.id]" min="0">
								</div>
							</div>
						</div>
					</form>

			</div>
		</modal>
		<modal title="Plan Settings" ok-text="Update" :callback="updatePlanSettings" :value="showPlanSettingsModal" @closed="showPlanSettingsModal=false">
			<div slot="modal-body" class="modal-body" v-if="selectedPlan && selectedPlanSettings">

					<form id="PlanCreateForm" class="form-horizontal" data-vv-scope="plan-settings">
						<div class="form-group" :class="{'has-error': errors.has('planname', 'plan-settings')}">
							<label for="updatePlanCallsign" class="control-label">Plan Name</label>
							<input @keydown.enter.prevent="" type="text" class="form-control" id="updatePlanCallsign" placeholder="" name="planname" v-validate="'required'" v-model="selectedPlanSettings.name">
						</div>

						<div class="form-group" :class="{'has-error': errors.has('plandesc', 'plan-settings')}">
							<label for="updatePlanDesc" class="control-label">Short Description</label>
							<textarea autosize="selectedPlanSettings.short_desc" class="form-control" id="updatePlanDesc" v-model="selectedPlanSettings.short_desc" name="plandesc" v-validate="'required'"></textarea>
						</div>

						<div class="form-group" :class="{'has-error': errors.has('teamgroup', 'plan-settings')}" v-if="isAdminRoute">
							<label>Travel Groups</label>
							<v-select @keydown.enter.prevent="" class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
							          v-model="selectedPlanSettings.groups" :options="groupsOptions" label="name" name="teamgroup" v-validate="'required'"
							          placeholder="Assign Travel Groups"></v-select>
						</div>

						<div class="form-group" v-for="type in roomTypes">
							<label :for="'settingsType-' + type.id" class="col-sm-6 control-label" v-text="type.name"></label>
							<div class="col-sm-6">
								<input type="number" number class="form-control" :id="'settingsType-' + type.id" v-model="selectedPlanSettings[type.id]" min="0">
							</div>
						</div>

						<div class="form-group">
							<label class="">Locked</label>
							<select v-if="isAdminRoute" class="form-control" v-model="selectedPlanSettings.locked">
								<option :value="true">Yes</option>
								<option :value="false">No</option>
							</select>
							<!--<p v-else v-text="selectedPlanSettings.locked ? 'Yes' : 'No'"></p>-->
						</div>
					</form>


			</div>
		</modal>
		<modal title="Delete Rooming Plan" small ok-text="Delete" :callback="deletePlan" :value="showPlanDeleteModal" @closed="showPlanDeleteModal=false">
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
    import exportUtility from '../export-utility.vue';
    import roomingReports from '../admin/reporting/rooming-reports.vue';
    export default{
        name: 'rooming-plans-list',
	    components: {vSelect, exportUtility, roomingReports},
        props: {
            userId: {
                type: String,
                required: false
            },
            groupId: {
                type: String,
                required: false
            },
            campaignId: {
                type: String,
                required: false
            }
        },
        data(){
            return {
                plans: [],
                showFilters: false,
	            search: '',
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                groupsOptions: [],
                campaignsOptions: [],
                roomTypes: [],
	            filters: {
		            groups: null,
	            },
                pagination: {current_page: 1},
                showPlanSettingsModal: false,
                showPlanDeleteModal: false,
                showPlanModal: false,
                selectedDeletePlan: null,
                selectedNewPlan: {
                    name: '',
                    short_desc: '',
                    rooms: [],
                    locked: false,
                    campaign_id: this.$parent.campaignId,
                    groups: [],
                    group_ids: [this.$parent.groupId],
                    room_types_settings: {},
                },
	            selectedPlan: null,
                selectedPlanSettings: null,
	            PlansResource: this.$resource('rooming/plans{/plan}{/path}{/pathId}'),
                exportOptions: {
                    name: 'Plan Name',
                    group: 'Group',
                    occupants: 'Occupant Count',
                    room_count: 'Room Count',
                    created_at: 'Created At',
                    updated_at: 'Updated At'
                },
                exportFilters: {},

            }
        },
	    watch: {
            filters: {
                handler(val) {
                    this.getRoomingPlans();
                },
	            deep: true
            },

            per_page() {
                this.getRoomingPlans();
		    },
	    },
        methods: {
            resetPlansFilter(){
                this.filters = {
                    group: null,
                };
            },
            loadManager(plan) {
                this.$emit('plan-selected', plan);
            },
            getRoomTypes(){
                return this.$http.get('rooming/types', { params: { campaign: this.campaignId, per_page: 100 }})
	                .then((response) => {
                        return this.roomTypes = response.data.data;
                    },
                    (response) =>  {
                        console.log(response);
                        return response.data.data;
                    });
            },
            debouncedSearch: _.debounce(function() { this.getRoomingPlans(); }, 500),
            getRoomingPlans(){
                let params = {
                    include: '',
                };
                params = _.extend(params, {
                    groups: _.pluck(this.filters.groups, 'id'),
	                search: this.search,
	                per_page: this.per_page,
					page: this.pagination.current_page,
					sort: 'created_at|desc',
                    include: 'groups'
                });
                
                if (this.isAdminRoute) {
					params.campaign = this.campaignId;
                } else {
                    params.campaign = this.campaignId;
                    params.groups = [this.groupId];

                }
                this.exportFilters = params;

                return this.PlansResource.get(params).then((response) => {
	                this.pagination = response.data.meta.pagination;
	                this.plans = response.data.data;
	                return this.plans;
                }, this.$root.handleApiError)
	        },
            getGroups(search, loading){
                let promise = this.$http.get('groups', { params: {search: search} });
	            loading ? loading(true) : void 0;
                promise.then((response) => {
                    this.groupsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    }
                }, this.$root.handleApiError);
                if (!loading)
                    return promise;
            },
            getCampaigns(search, loading){
                let promise = this.$http.get('campaigns', { params: {search: search} });
                loading ? loading(true) : void 0;
                promise.then((response) => {
                    this.campaignsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    }
                }, this.$root.handleApiError);
                if (!loading)
                    return promise;
            },
            openPlanSettingsModal(plan) {
                this.showPlanSettingsModal = true;
                this.selectedPlan = plan;
                let settings = {
                    short_desc: plan.short_desc,
                    name: plan.name,
	                locked: plan.locked,
	                groups: plan.groups.data,
                };

                // We need to loop through each room type to create an object to reference the plan types present
                _.each(this.roomTypes, (type) => {
                    // the settings will be traced by the type ids
	                // then we will attempt to find the assignment in the current plan's settings (expecting a number) or set to 0
	                let assignment = plan.room_types.hasOwnProperty(type.name) ? plan.room_types[type.name] : 0;
                    settings[type.id] = assignment;
                    // we are using method to track which room types need to be updated or posted
                    settings[type.id + '_method'] = assignment > 0 ? 'PUT' :'POST';
                });
                this.selectedPlanSettings = settings;
            },
            openDeletePlanModal(plan) {
                this.showPlanDeleteModal = true;
                this.selectedDeletePlan = plan;
            },
	        handleRoomTypeSettings(plan, settings, promises) {
                _.each(settings, (val, property) => {
                    let promise;
                    if (property.indexOf('_method') === -1 && !_.contains(['short_desc', 'name', 'groups', 'group_ids', 'locked'], property)) {
                        if (settings[property + '_method'] === 'PUT') {
                            if (val > 0) {
                                promise = this.PlansResource.put({
                                    plan: plan.id, path: 'types', pathId: property
                                }, { available_rooms: val });
                            } else {
                                // if val is 0 we should simply delete the room type setting
                                promise = this.PlansResource.delete({
                                    plan: plan.id, path: 'types', pathId: property
                                });
                            }

                        } else {
                            // only create setting if val > 0
                            if (val > 0)
                                promise = this.PlansResource.post({ plan: plan.id, path: 'types'}, {
                                    room_type_id: property,
                                    available_rooms: val
                                });
                        }
                        if (promise) {
                            // we only need to catch errors here
                            promise.catch((response) =>  {
                                console.log(response.data.message);
                            });
                            promises.push(promise);
                        }
                    }

                });
	        },
            updatePlanSettings() {
                this.$validator.validateAll('plan-settings').then(result => {
	                if(result) {
                        let promises = [];
                        let settingsData = _.extend({}, this.selectedPlanSettings);
                        settingsData.group_ids = _.pluck(settingsData.groups, 'id');
                        delete settingsData.groups;

                        // update name and short_desc properties
                        promises.push(this.PlansResource.put({ plan: this.selectedPlan.id},
                            { name: settingsData.name, short_desc: settingsData.short_desc, group_ids: settingsData.group_ids, locked: settingsData.locked}));

                        this.handleRoomTypeSettings(this.selectedPlan, settingsData, promises);

                        Promise.all(promises).then(() => {
                            this.showPlanSettingsModal = false;
                            this.$root.$emit('showSuccess', this.selectedPlan.name + ' settings updated successfully.');
                            this.getRoomingPlans().then(() => {
                                this.selectedPlan = null;
                                this.selectedPlanSettings = null;
                            });
                        });

                    }
                })
                
            },
            deletePlan() {
                let plan = _.extend({}, this.selectedDeletePlan);
                this.PlansResource.delete({ plan: plan.id}).then((response) => {
                    this.showPlanDeleteModal = false;
                    this.$root.$emit('showInfo', plan.name + ' Deleted!');
                    this.plans = _.reject(this.plans, (obj) => {
                        return plan.id === obj.id;
                    });
                });
            },
            openNewPlanModal(){
                this.showPlanModal = true;
                 let obj = {
                    name: '',
                    short_desc: '',
                    rooms: [],
                    locked: false,
                    campaign_id: this.$parent.campaignId,
                    group_id: this.$parent.groupId,
                    room_types_settings:{}

                };

                // We need to loop through each room type to create an object to reference the plan types present
                _.each(this.roomTypes, (type) => {
                    // the settings will be traced by the type ids
                    // then we will attempt to find the assignment in the current plan's settings (expecting a number) or set to 0
                    obj.room_types_settings[type.id] = 0;
                    // we are using method to track which room types need to be updated or posted
                    obj.room_types_settings[type.id + '_method'] = 'POST';
                });
                this.selectedNewPlan = obj;
            },
            newPlan() {
                if (this.isAdminRoute && _.isObject(this.selectedNewPlan.group)) {
                    this.selectedNewPlan.group_id = this.selectedNewPlan.group.id;
                }
                let data = _.extend({}, this.selectedNewPlan);
                let room_types_settings = _.extend({}, data.room_types_settings);
                delete data.room_types_settings;
                data.group_ids = _.pluck(data.groups, 'id');
                delete data.groups;

                this.$validator.validateAll('plan-create').then(result => {
                    if (result) {
                        return this.PlansResource.post(data, { include: 'group' }).then((response) => {
                            let plan = response.data.data;

                            // update created plan with
                            let promises = [];
                            this.handleRoomTypeSettings(plan, room_types_settings, promises);
                            Promise.all(promises).then(() => {
                                this.getRoomingPlans();
                                this.$root.$emit('showSuccess', data.name + ' created successfully');
                                this.showPlanModal = false;
                            });
                        }, (response) =>  {
                            console.log(response);
                            this.$root.$emit('showError', response.data.message);
                            return response.data.data;
                        });
                    } else {
                        this.$root.$emit('showError', 'Please provide a name for the new plan');
                    }
                });

            },
	        handlePlanGroupAssociations(){

	        },
        },
        mounted(){
            if (this.isAdminRoute) {
                this.getGroups();
            }
			this.getRoomingPlans().then((plans) => {
				if (this.isAdminRoute && location.search.length > 1) {
                    let plan;
                    let searchObj = this.$root.convertSearchToObject();
                    // https://stackoverflow.com/questions/8648892/convert-url-parameters-to-a-javascript-object
                    if (searchObj.hasOwnProperty('plan')) {
                        plan = _.findWhere(plans, {id: searchObj.plan});
                        if (plan)
                            this.loadManager(plan);
                    }
                }

            });
			this.getRoomTypes();

            this.$root.$on('campaign-scope', (val) =>  {
                this.campaignId = val ? val.id : '';
                if (val && val.id) {
                    this.getRoomingPlans();
                    this.getRoomTypes();
                }
            });
        }
    }
</script>