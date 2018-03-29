<style scoped>
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
<template>
    <div class="panel panel-default">
        <spinner ref="loadingSpinner" size="xl" :fixed="true" text="Loading..."></spinner>
        <div class="panel-heading">
            <h3 v-if="isUpdate">
                Edit Trip <br />
                <small>for {{ campaign.name|capitalize }}</small>
            </h3>
            <h3 v-else>
                Create New Trip <br />
                <small>for {{ campaign.name|capitalize }}</small>
            </h3>
        </div>

        <form id="TripDetailsForm" class="form-horizontal" novalidate>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Trip Details</h5>
                        <p class="text-muted">These details appear to the end-user on the trip details page.</p>
                    </div>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group" :class="{ 'has-error': errors.has('group') }">
                            <div class="col-sm-8">
                                <label class="control-label">Group</label>
                                <v-select @keydown.enter.prevent=""
                                          class="form-control"
                                          id="group"
                                          v-model="groupObj"
                                          :options="groups"
                                          :on-search="getGroups"
                                          label="name"
                                          name="group"
                                          v-validate="'required'">
                                </v-select>
                                <span class="help-block">Search for a group by entering a name. Select a group to assign it.</span>
                            </div>
                        </div>

                        <div class="form-group" :class="{ 'has-error': errors.has('type') }">
                            <div class="col-sm-6">
                                <label for="type" class="control-label">Type</label>
                                <select id="type" class="form-control" v-model="type"
                                        name="type" v-validate="'required'" required>
                                    <option value="">-- select --</option>
                                    <option value="ministry">Ministry</option>
                                    <option value="family">Family</option>
                                    <option value="international">International</option>
                                    <option value="media">Media</option>
                                    <option value="medical">Medical</option>
                                    <option value="leader">Leader</option>
                                    <option value="sports">Sports</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" :class="{ 'has-error': errors.has('difficulty') }">
                            <div class="col-sm-6">
                                <label for="difficulty" class="control-label">Difficulty</label>
                                <select id="difficulty" class="form-control" v-model="difficulty"
                                        name="difficulty" v-validate="'required'" required>
                                    <option value="">-- select --</option>
                                    <option value="level_1">Level 1</option>
                                    <option value="level_2">Level 2</option>
                                    <option value="level_3">Level 3</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Start Date</label>
                                <date-picker :has-error= "errors.has('start')"
                                             v-model="started_at"
                                             :view-format="['YYYY-MM-DD', false, true]"
                                             type="date">
                                </date-picker>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>End Date</label>
                                <date-picker :has-error= "errors.has('end')"
                                             v-model="ended_at"
                                             :view-format="['YYYY-MM-DD', false, true]"
                                             type="date">
                                </date-picker>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Registration Settings</h5>
                        <p class="text-muted">Registration will close or remain open based on these settings.</p>
                    </div>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Visibility</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" v-model="public" :value="false"> Private
                                    </label>
                                    <label>
                                        <input type="radio" v-model="public" :value="true"> Public
                                    </label>
                                </div>
                                <span class="help-block">Private trips are not publicly available. Registration must be managed by an admin.</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.has('spots') }">
                            <div class="col-sm-6">
                                <label for="spots" class="control-label">Spots Available</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <input type="number"
                                           id="spots"
                                           v-model="spots"
                                           class="form-control"
                                           name="spots" v-validate="'required|min:0'"/>
                                </div>
                                <span class="help-block">When spots left reaches 0, registration will automatically close.</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.has('closed') }">
                            <div class="col-sm-6">
                                <label for="closed_at" class="control-label">Registration Closes</label>
                                <date-picker :has-error= "errors.has('closed')"
                                             v-model="closed_at"
                                             :view-format="['YYYY-MM-DD HH:mm:ss']" >
                                </date-picker>
                                <span class="help-block">Registration will automatically close at this date and time.</span>
                            </div>
                        </div>
                        <div class="form-group" v-if="isUpdate">
                            <div class="col-sm-6">
                                <label for="published_at" class="control-label">
                                    Publish On
                                </label>
                                <!-- <label class="control-label pull-right"><input type="checkbox" v-model="toggleDraft"> Save as Draft</label> -->
                                <date-picker v-model="published_at" :view-format="['YYYY-MM-DD HH:mm:ss']" v-if="!toggleDraft"></date-picker>
                                <span class="help-block">Set the date and time this trip becomes available to the public.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Trip Rep</h5>
                        <p class="text-muted">This is the represenative that will be automatically assigned to any reservations created under this trip. The trip rep can be overridden for individual reservations.</p>

                        <p class="text-muted">The trip rep's contact information will be shown to all registered user.</p>
                    </div>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Assign Default Trip Rep</label>
                                <v-select @keydown.enter.prevent=""
                                          class="form-control"
                                          id="rep"
                                          v-model="repObj"
                                          :on-search="getReps"
                                          :options="reps"
                                          label="email">
                                </v-select>
                                <span class="help-block">Search for a trip rep by entering an email. Select the rep to assign them.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Additional Configuration</h5>
                        <p class="text-muted">Other details and configurations for the trip.</p>
                    </div>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group" :class="{ 'has-error': errors.has('prospects') }">
                            <div class="col-sm-12">
                                <label class="control-label">Perfect For</label>
                                <v-select @keydown.enter.prevent=""  multiple class="form-control" id="group" v-model="prospectsObj"
                                          :options="prospectsList" label="name" placeholder="Select Prospects" name="prospects" v-validate="'required'"></v-select>
                                <span class="help-block">Helps users find trips right for them. Select all that apply.</span>
                            </div>
                        </div>

                        <div class="form-group" :class="{ 'has-error': errors.has('teamroles') }">
                            <div class="col-sm-12">
                                <label class="control-label">Available Roles</label>
                                <v-select @keydown.enter.prevent=""  multiple class="form-control" id="group" v-model="rolesObj"
                                          :options="teamRolesList" label="name" placeholder="Select Team Roles" name="teamroles" v-validate="'required'"></v-select>
                                <span class="help-block">The team roles a user can select from during registration. Select all that apply.</span>
                            </div>
                        </div>

                        <div class="form-group" :class="{ 'has-error': errors.has('companions') }">
                            <div class="col-sm-6">
                                <label for="companion_limit" class="control-label">Companion Limit</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <input type="number" id="companion_limit" v-model="companion_limit" class="form-control"
                                           name="companions" v-validate="'required|min:0'"/>
                                </div>
                                <div class="help-block">Default number of companions a reservation can have. Leave at 0 to disable
                                    companions.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer text-right">
                <div class="form-group">
                    <hr class="divider inv md">
                    <div class="col-xs-12 text-right">
                        <a :href="'/admin/campaigns/' + campaign.id + '/trips'" class="btn btn-link">Cancel</a>
                        <a class="btn btn-primary" v-if="isUpdate" @click.prevent="finish">Save Changes</a>
                        <a @click.prevent="finish" class="btn btn-primary" v-else>Create &amp; Continue</a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</template>
<script type="text/javascript">
	let marked = require('marked');
	import vSelect from "vue-select";

	export default{
		name: 'admin-trip-create-update',
		props: {
			campaign: {
				type: Object,
                required: true
			},
			trip: {
				type: Object
			},
			isUpdate: {
				type: Boolean,
				default: false
			}
		},
		components: {vSelect},
		data: function () {
			return {
				// parent data
				wizardData: {
					campaign_id: this.campaign.id,
					country_code: this.campaign.country_code
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
				groupObj: null,
				type: '',
				difficulty: '',
				companion_limit: 0,
				prospectsObj: null,
				started_at: '',
				ended_at: '',
				repObj: null,
				rolesObj: null,
				spots: null,
				closed_at: moment().format('YYYY-MM-DD HH:mm:ss'),
				published_at: null,
				public: true,
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
				this.$http.get('groups', { params: {search: search} }).then((response) => {
					this.groups = response.data.data;
					loading(false);
				});
			},
			getReps(search, loading){
				loading(true);
				this.$http.get('representatives', { params: {search: search} }).then((response) => {
					this.reps = response.data.data;
					loading(false);
				});
			},
			onValid(){
				this.populateWizardData(true);
			},
			finish(){
				// if details form is incomplete
				this.attemptedContinue = true;
				this.populateWizardData(false);
                this.$validator.validateAll().then(result => {
                    if (result) {
                        let resource = this.$resource('trips{/id}');
                        if (this.isUpdate) {
                            resource.put({id: this.trip.id}, this.wizardData).then((response) => {
                                $.extend(this, response.data.data);
                                this.difficulty = response.data.data.difficulty.toLowerCase().replace(' ', '_');
                                this.attemptedContinue = false;

                                swal("Good job!", "The trip was updated.", "success", {
                                        buttons: ["Make Changes", "Done"]
                                    })
                                    .then((value) => {
                                        if (value) {
                                            window.location.href = '/admin/trips/' + this.trip.id;
                                        }
                                    });

                            }, (error) => {
                                swal("Oops!", "Something doesn't look right. Please check the form.", "error");
                                console.log(error);
                            });
                        } else {
                            resource.post({}, this.wizardData).then((resp) => {
                                window.location.href = '/admin/trips/' + resp.data.data.id + '/pricing';
                            }, (error) => {
                                this.$root.$emit('showError', 'Unable to create the trip.');
                                console.log(error);
                            });
                        }
                    }
                });
			}
		},
		mounted(){

            if (this.isUpdate) {
                this.$refs.loadingSpinner.show();

                this.type = this.trip.type;
                this.companion_limit = this.trip.companion_limit;
                this.spots = this.trip.spots;
                this.started_at = this.trip.started_at;
                this.ended_at = this.trip.ended_at;
                this.closed_at = this.trip.closed_at
                this.difficulty = this.trip.difficulty.toLowerCase().replace(' ', '_');
                this.published_at = this.trip.published_at;
            }

            // get some groups
            let groupsPromise = this.$http.get('groups').then((response) => {
                this.groups = response.data.data;
            });

			// get team roles list
			let teamRolesPromise = this.$http.get('utilities/team-roles').then((response) => {
			    _.each(response.data.roles, (name, key) => {
					this.teamRolesList.push({ value: key, name: name});
				});
			});

			Promise.all([groupsPromise, teamRolesPromise]).then((values) => {
                if (this.isUpdate) {

                    this.prospectsObj = _.filter(this.prospectsList, (p) => {
                        return _.some(this.trip.prospects, (prospect) => {
                            return prospect.toLowerCase() === p.value.toLowerCase();
                        });
                    });

                    this.rolesObj = _.filter(this.teamRolesList, (p) => {
                        return _.some(this.trip.team_roles, function (prospect) {
                            return prospect.toLowerCase() === p.value.toLowerCase();
                        });
                    });

                    this.$http.get('groups/' + this.trip.group_id).then((response) => {
                        this.groupObj = response.data.data;
                    });

                    this.$http.get('representatives/' + this.trip.rep_id).then((response) => {
                        this.repObj = response.data.data;
                    });

                    this.$refs.loadingSpinner.hide();
                }
            })
		}
	}
</script>