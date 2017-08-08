<template>
    <div class="row">
        <div class="col-sm-12">

            <validator name="TripCreateUpdate" @valid="onValid">
                <form id="TripDetailsForm" class="form-horizontal" novalidate>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                        	<label class="control-label">Campaign</label>
                            <h3>{{campaign.name|capitalize}}</h3>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'has-error': errors.has('group') }">
                        <div class="col-sm-12">	
                        	<label class="control-label">Group</label>
                            <v-select @keydown.enter.prevent=""  class="form-control" id="group" :value.sync="groupObj" :options="groups" :on-search="getGroups" label="name"></v-select>
                            <select hidden v-model="group_id" v-validate:group="{ required: true}">
                                <option :value="group.id" v-for="group in groups">{{ group.name }}</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
						<div class="col-sm-6">
							<label>Visibility</label>
							<div class="radios">
								<label>
									<input type="radio" v-model="public" :value="false"> Private
								</label>
								<label>
									<input type="radio" v-model="public" :value="true"> Public
								</label>
							</div>
						</div>
						<div class="col-sm-6">
							<div :class="{ 'has-error': errors.has('type') }">
								<label for="type" class="control-label">Type</label>
								<select id="type" class="form-control" v-model="type"
										v-validate:type="{ required: true }" required>
									<option value="">-- select --</option>
									<option value="ministry">Ministry</option>
									<option value="family">Family</option>
									<option value="international">International</option>
									<option value="media">Media</option>
									<option value="medical">Medical</option>
									<option value="leader">Leader</option>
								</select>
							</div>
						</div><!-- end col -->

					</div>
                    <div class="row">
	                    <div class="col-sm-6">
	                    	<div :class="{ 'has-error': errors.has('prospects') }">
	                        	<label class="control-label">Perfect For</label>
	                            <v-select @keydown.enter.prevent=""  multiple class="form-control" id="group" :value.sync="prospectsObj"
	                                      :options="prospectsList" label="name" placeholder="Select Prospects"></v-select>
	                            <select hidden multiple v-model="prospects" v-validate:prospects="{ required: true}">
	                                <option :value="prospect.value" v-for="prospect in prospectsList">{{prospect.name}}
	                                </option>
	                            </select>
	                        </div>
	                    </div><!-- end col -->
	                    <div class="col-sm-6">
	                    	<div :class="{ 'has-error': errors.has('teamroles') }">
	                        	<label class="control-label">Available Roles</label>
	                            <v-select @keydown.enter.prevent=""  multiple class="form-control" id="group" :value.sync="rolesObj"
	                                      :options="teamRolesList" label="name" placeholder="Select Team Roles"></v-select>
	                            <select hidden multiple v-model="team_roles" v-validate:teamroles="{ required: true}">
	                                <option :value="role.value" v-for="role in teamRolesList">{{role.name}}</option>
	                            </select>
	                        </div>
	                    </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="row">
	                    <div class="col-sm-6">
	                    		<div :class="{ 'has-error': errors.has('difficulty') }">
		                        	<label for="difficulty" class="control-label">Difficulty</label>
		                            <select id="difficulty" class="form-control" v-model="difficulty"
		                                    v-validate:difficulty="{ required: true }" required>
		                                <option value="">-- select --</option>
		                                <option value="level_1">Level 1</option>
		                                <option value="level_2">Level 2</option>
		                                <option value="level_3">Level 3</option>
		                            </select>
		                        </div>
	                    </div><!-- end col -->
	                    <div class="col-sm-6">
		                    <div :class="{ 'has-error': errors.has('companions') }">
		                        	<label for="companion_limit" class="control-label">Companion Limit</label>
		                            <div class="input-group">
		                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
		                                <input type="number" id="companion_limit" v-model="companion_limit" class="form-control"
		                                       v-validate:companions="{ required: true, min:0 }"/>
		                            </div>
		                            <div class="help-block">Number of companions a user can have. Leave at 0 to disable
		                                companions.
		                            </div>
		                    </div>
	                    </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="form-group" :class="{ 'has-error': (errors.has('start') || errors.has('end')) }">
                        <div class="col-sm-12">
                        	<label for="started_at" class="control-label">Dates</label>
                            <div class="row">
                                <div class="col-sm-6">
	                                <date-picker :has-error= "errors.has('start')" :model.sync="started_at|moment('YYYY-MM-DD', false, true)" type="date" addon="Start" ></date-picker>
	                                <input type="datetime" class="form-control hidden" v-model="started_at | moment('LLLL')" id="started_at"
	                                       v-validate:start="['required']" required>
	                                <!--<div class="input-group" :class="{ 'has-error': errors.has('start') }">
										<span class="input-group-addon">Start</span>
										<input type="datetime" class="form-control hidden" v-model="started_at | moment('LLLL')" id="started_at"
											   v-validate:start="['required']" required>
									</div>-->
                                </div>
                                <div class="col-sm-6">
	                                <date-picker :has-error= "errors.has('end')" :model.sync="ended_at|moment('YYYY-MM-DD', false, true)" type="date" addon="End" ></date-picker>
	                                <input type="datetime" class="form-control hidden" v-model="ended_at | moment('LLLL')" id="ended_at"
	                                       v-validate:end="['required']" required>
	                                <!--<div class="input-group"
                                         :class="{ 'has-error': errors.has('end') }">
                                        <span class="input-group-addon">End</span>
										<date-picker class="form-control" :model.sync="ended_at|moment 'YYYY-MM-DD HH:mm:ss'" type="date"></date-picker>
										<input type="datetime" class="form-control hidden" v-model="ended_at | moment('LLLL')" id="ended_at"
                                               v-validate:end="['required']" required>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"	>
                        <div class="col-sm-12">
                        	<label class="control-label">Trip Rep.</label>
                            <v-select @keydown.enter.prevent="" class="form-control" id="rep" :value.sync="repObj" :on-search="getReps" :options="reps"
                                      label="name"></v-select>
                            <!--v-validate:rep="{ required: false}"-->
                            <select hidden v-model="rep_id">
                                <option v-for="rep in reps" :value="rep">{{rep}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
	                    <div class="col-sm-6">
							<div :class="{ 'has-error': errors.has('spots') }">
								<label for="spots" class="control-label">Spots Available</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users"></i></span>
									<input type="number" id="spots" v-model="spots" class="form-control"
										   v-validate:spots="{ required: true, min:0 }"/>
								</div>
								<div class="help-block">Number of companions a user can have. Leave at 0 to disable
									companions.
								</div>
							</div>
						</div><!-- end col -->
						<div class="col-sm-6">
							<div :class="{ 'has-error': errors.has('closed') }">
								<label for="closed_at" class="control-label">Registration Closes</label>
								<date-picker :has-error= "errors.has('closed')" :model.sync="closed_at|moment 'YYYY-MM-DD HH:mm:ss'" ></date-picker>
								<!--<date-picker class="form-control" :model.sync="closed_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>-->
								<input type="datetime" class="form-control hidden" v-model="closed_at | moment('LLLL')" v-validate:closed="{ required: true }" id="closed_at">
							</div>
						</div><!-- end col -->
					</div><!-- end row -->
					<div class="form-group">
						<div class="col-sm-12">
							<label for="published_at" class="control-label">
								Publish
							</label>
							<label class="control-label pull-right"><input type="checkbox" v-model="toggleDraft"> Save as Draft</label>
							<date-picker :model.sync="published_at|moment 'YYYY-MM-DD HH:mm:ss'" v-if="!toggleDraft"></date-picker>
							<!--<date-picker class="form-control" :model.sync="published_at|moment 'YYYY-MM-DD HH:mm:ss'" v-if="!toggleDraft"></date-picker>-->
							<input type="datetime" class="form-control" :class="{ 'hidden': !toggleDraft}" v-model="published_at | moment('LLLL')" id="published_at" :disabled="toggleDraft">
						</div>
					</div>

					<div class="col-sm-12 text-center">
						<div class="form-group">
							<a class="btn btn-default" @click="back()">Done</a>
							<a class="btn btn-success" v-if="isUpdate" @click="finish()">Update</a>
							<a class="btn btn-success" v-if="!isUpdate" @click="finish()">Create</a>
						</div>
					</div>

				</form>

			</validator>
			
				</div>
			</div>
		</div>
    </div>
</template>
<style>
	#TripDetailsForm .form-horizontal .radio, .form-horizontal .checkbox {
		min-height: 24px;
		padding-top: 0;
	}

	input.cov-datepicker {
		border: none !important;
		box-shadow: none !important;
		padding: 0 !important;
		width: 100%;
	}
</style>
<script type="text/javascript">
	let marked = require('marked');
	import vSelect from "vue-select";

	export default{
		name: 'admin-trip-create-update',
		props: {
			campaignId: {
				type: String,
			},
			tripId: {
				type: String,
			},
			countryCode: {
				type: String,
			},
			isUpdate: {
				type: Boolean,
				default: false,
			},
		},
		components: {vSelect},
		data(){
			return {
				// parent data
				campaign: {},
				trip: {},
				wizardData: {
					campaign_id: this.campaignId,
					country_code: this.countryCode
				},
				reps: [],
				groups: [],
				teamRolesList: [],
				prospectsList: [
					{value: "adults", name: "Adults"},
					{value: "young adults (18-29)", name: "Young Adults (18-29)"},
					{value: "teens (13+)", name: "Teens (13+)"},
					{value: "families", name: "Families"},
					{value: "men", name: "Men"},
					{value: "women", name: "Women"},
					{value: "media professionals", name: "Media Professionals"},
					{value: "pastors", name: "Pastors"},
					{value: "business leaders", name: "Business Leaders"},
					{value: "medical professionals", name: "Medical Professionals"},
					{value: "physicians", name: "Physicians"},
					{value: "surgeons", name: "Surgeons"},
					{value: "registered nurses", name: "Registered Nurses"},
					{value: "dentists", name: "Dentists"},
					{value: "hygienists", name: "Hygienists"},
					{value: "dental assistants", name: "Dental Assistants"},
					{value: "physician assistants", name: "Physician Assistants"},
					{value: "nurse practitioners", name: "Nurse Practitioners"},
					{value: "pharmacists", name: "Pharmacists"},
					{value: "physical therapists", name: "Physical Therapists"},
					{value: "chiropractors", name: "Chiropractors"},
					{value: "medical students", name: "Medical Students"},
					{value: "dental students", name: "Dental Students"},
					{value: "nursing students", name: "Nursing Students"}
				],
				attemptedContinue: false,
				// details data
				groupObj: null,
				//group_id: '',
				description: '',
				type: '',
				difficulty: '',
				companion_limit: 0,
				prospectsObj: null,
				//prospects: [],
				started_at: '',
				ended_at: '',
				repObj: null,
				//rep_id: '',
				rolesObj: null,
				//team_roles: [],
				// details data
				spots: null,
				closed_at: moment().format('YYYY-MM-DD HH:mm:ss'),
				published_at: null,
				public: false,
                toggleDraft: false,
			}
		},
		computed: {
			group_id(){
				return _.isObject(this.groupObj) ? this.groupObj.id : null;
			},
			rep_id(){
				return _.isObject(this.repObj) ? this.repObj.id : null;
			},
			prospects(){
				return _.pluck(this.prospectsObj, 'value') || [];
			},
			team_roles(){
				return _.pluck(this.rolesObj, 'value') || [];
			}
		},
		watch:{
		    'toggleDraft'(val){
		        if (val){
		            this.published_at = null;
		        }
		    }
		},
		filters: {
			marked: marked,
		},
		methods: {
			back(){
				window.location.href = window.location.href.replace('create', '').replace('edit', '');
			},
			populateWizardData(onValid){
				if (!onValid)
					this.$validate(true);
				$.extend(this.wizardData, {
					group_id: this.group_id,
					description: this.description,
					type: this.type,
					difficulty: this.difficulty,
					companion_limit: this.companion_limit,
					prospects: this.prospects,
					team_roles: this.team_roles,
					started_at: this.started_at,
					ended_at: this.ended_at,
					rep_id: this.rep_id,
					spots: this.spots,
					closed_at: this.closed_at,
					published_at: this.published_at,
					public: this.public
				});
			},
			getGroups(search, loading){
				loading(true);
				this.$http.get('groups', { params: {search: search} }).then(function (response) {
					this.groups = response.body.data;
					loading(false);
				});
			},
			getReps(search, loading){
				loading(true);
				this.$http.get('users', { params: {search: search} }).then(function (response) {
					this.reps = response.body.data;
					loading(false);
				});
			},
			onValid(){
				this.populateWizardData(true);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripCreateUpdate[field.toLowerCase()].invalid && this.attemptedContinue
			},
			finish(){
				// if details form is incomplete
				this.attemptedContinue = true;
				this.populateWizardData(false);
				if (this.$TripCreateUpdate.valid) {
					let resource = this.$resource('trips{/id}');
					if (this.isUpdate) {
						resource.update({id: this.tripId}, this.wizardData).then(function (response) {
							$.extend(this, response.body.data);
							this.difficulty = response.body.data.difficulty.toLowerCase().replace(' ', '_');
							this.attemptedContinue = false;
							this.$root.$emit('showSuccess', 'Trip Updated');
						}, function (error) {
							this.$root.$emit('showError', 'Please check the form.');
							console.log(error);
						});
					} else {
						resource.save(null, this.wizardData).then(function (resp) {
							window.location.href = '/admin' + resp.data.data.links[0].uri;
						}, function (error) {
							console.log(error);
						});
					}
				}
			}
		},
		mounted(){
            // get some groups
            let groupsPromise = this.$http.get('groups').then(function (response) {
                this.groups = response.body.data;
            });

			// get team roles list
			let teamRolesPromise = this.$http.get('utilities/team-roles').then(function (response) {
			    _.each(response.body.roles, function (name, key) {
					this.teamRolesList.push({ value: key, name: name});
				}.bind(this));
			});

			Promise.all([groupsPromise, teamRolesPromise]).then(function (values) {
                if (this.isUpdate) {
                    this.$http.get('trips/' + this.tripId, { params: {include: 'campaign,costs.payments,requirements,notes,deadlines'} }).then(function (response) {
                        let trip = response.body.data;
                        // trim campaign
                        $.extend(this, trip);
                        this.campaign = trip.campaign.data;
                        this.difficulty = trip.difficulty.toLowerCase().replace(' ', '_');
                        // this.prospects = trip.prospects;
                        this.prospectsObj = _.filter(this.prospectsList, function (p) {
                            return _.some(trip.prospects, function (prospect) {
	                            return prospect.toLowerCase() === p.value.toLowerCase();
                            });
                        });
                        this.rolesObj = _.filter(this.teamRolesList, function (p) {
                            return _.some(trip.team_roles, function (prospect) {
                                return prospect.toLowerCase() === p.value.toLowerCase();
                            });
                        });

                        this.trip = trip;
                        this.$http.get('groups/' + this.trip.group_id).then(function (response) {
                            this.groupObj = response.body.data;
                        });
						// this.wizardData.campaign_id = this.trip.campaign_id;
                         //this.trip.country_code = trip.country_code[0];

                        this.$http.get('users/' + this.trip.rep_id).then(function (response) {
                            this.repObj = response.body.data;
                        });

                    });
                } else {
                    this.$http.get('campaigns/' + this.campaignId).then(function (response) {
                        this.campaign = response.body.data;
                    });
                }
            }.bind(this))
		}
	}
</script>