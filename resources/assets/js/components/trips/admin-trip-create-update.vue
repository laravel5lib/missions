<template>
    <div class="row">
        <div class="col-sm-12">
            <validator name="TripCreateUpdate" @valid="onValid">
                <form id="TripDetailsForm" class="form-horizontal" novalidate>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Campaign</label>
                        <div class="col-sm-10">
                            <p>{{campaign.name|capitalize}}</p>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'has-error': checkForError('group') }">
                        <label class="col-sm-2 control-label">Group</label>
                        <div class="col-sm-10">
                            <v-select class="form-control" id="group" :value.sync="groupObj" :options="groups" :on-search="getGroups"
                                      label="name"></v-select>
                            <select hidden v-model="group_id" v-validate:group="{ required: true}">
                                <option :value="group.id" v-for="group in groups">{{group.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                        <label for="type" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-10">
                            <select id="type" class="form-control input-sm" v-model="type"
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
                    </div>

                    <div class="form-group" :class="{ 'has-error': checkForError('prospects') }">
                        <label class="col-sm-2 control-label">Perfect For</label>
                        <div class="col-sm-10">
                            <v-select multiple class="form-control" id="group" :value.sync="prospectsObj"
                                      :options="prospectsList" label="name" placeholder="Select Prospects"></v-select>
                            <select hidden multiple v-model="prospects" v-validate:prospects="{ required: true}">
                                <option :value="prospect.value" v-for="prospect in prospectsList">{{prospect.name}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" :class="{ 'has-error': checkForError('difficulty') }">
                        <label for="difficulty" class="col-sm-2 control-label">Difficulty</label>
                        <div class="col-sm-10">
                            <select id="difficulty" class="form-control input-sm" v-model="difficulty"
                                    v-validate:difficulty="{ required: true }" required>
                                <option value="">-- select --</option>
                                <option value="level_1">Level 1</option>
                                <option value="level_2">Level 2</option>
                                <option value="level_3">Level 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" :class="{ 'has-error': checkForError('companions') }">
                        <label for="companion_limit" class="col-sm-2 control-label">Companion Limit</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <input type="number" id="companion_limit" v-model="companion_limit" class="form-control"
                                       v-validate:companions="{ required: true, min:0 }"/>
                            </div>
                            <div class="help-block">Number of companions a user can have. Leave at 0 to disable
                                companions.
                            </div>
                        </div>
                    </div>

                    <div class="form-group" :class="{ 'has-error': (checkForError('start') || checkForError('end')) }">
                        <label for="started_at" class="col-sm-2 control-label">Dates</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm"
                                         :class="{ 'has-error': checkForError('start') }">
                                        <span class="input-group-addon">Start</span>
										<date-picker class="form-control" :time.sync="started_at"></date-picker>
										<input type="datetime" class="form-control hidden" v-model="started_at" id="started_at"
                                               v-validate:start="{ required: true }" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm"
                                         :class="{ 'has-error': checkForError('end') }">
                                        <span class="input-group-addon">End</span>
										<date-picker class="form-control" :time.sync="ended_at"></date-picker>
										<input type="datetime" class="form-control hidden" v-model="ended_at" id="ended_at"
                                               v-validate:end="{ required: true }" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"	>
                        <label class="col-sm-2 control-label">Trip Rep.</label>
                        <div class="col-sm-10">
                            <v-select multiple class="form-control" id="rep" :value.sync="repObj" :on-search="getReps" :options="reps"
                                      label="name"></v-select>
                            <!--v-validate:rep="{ required: false}"-->
                            <select hidden v-model="rep_id">
                                <option v-for="rep in reps" :value="rep">{{rep}}</option>
                            </select>
                        </div>
                    </div>


					<div class="form-group" :class="{ 'has-error': checkForError('spots') }">
						<label for="spots" class="col-sm-2 control-label">Spots Available</label>
						<div class="col-sm-10">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><i class="fa fa-users"></i></span>
								<input type="number" id="spots" v-model="spots" class="form-control"
									   v-validate:spots="{ required: true, min:0 }"/>
							</div>
							<div class="help-block">Number of companions a user can have. Leave at 0 to disable
								companions.
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('closed') }">
						<label for="closed_at" class="col-sm-2 control-label">Registration Closes</label>
						<div class="col-sm-10">
							<date-picker class="form-control input-sm" :time.sync="closed_at"></date-picker>
							<input type="datetime" class="form-control input-sm hidden" v-model="closed_at" v-validate:closed="{ required: true }" id="closed_at">
						</div>
					</div>

					<div class="form-group">
						<label for="published_at" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<date-picker class="form-control input-sm" :time.sync="published_at"></date-picker>
							<input type="datetime" class="form-control input-sm hidden" v-model="published_at" id="published_at">
						</div>
					</div>

					<div class="col-sm-12 text-center">
						<div class="form-group">
							<a class="btn btn-link" @click="back()">Back</a>
							<a class="btn btn-success" v-if="isUpdate" @click="finish()">Update</a>
							<a class="btn btn-success" v-if="!isUpdate" @click="finish()">Save</a>
						</div>
					</div>

				</form>

			</validator>
			<alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
				<span class="icon-ok-circled alert-icon-float-left"></span>
				<strong>Well Done!</strong>
				<p>Trip Updated!</p>
			</alert>

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
					country_code: [this.countryCode]
				},
				reps: [],
				groups: [],
				prospectsList: [
					{value: "adults", name: "Adults"},
					{value: "young adults", name: "Young Adults (18-29)"},
					{value: "teens", name: "Teens (13+)"},
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
				group_id: '',
				description: '',
				type: '',
				difficulty: '',
				companion_limit: 0,
				prospectsObj: null,
				prospects: [],
				started_at: '',
				ended_at: '',
				repObj: null,
				rep_id: '',
				roles: [],
				// details data
				spots: null,
				closed_at: moment().format('YYYY-MM-DD HH:mm:ss'),
				published_at: '',

				showSuccess: false,
				showError: false,


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
				return _.pluck(this.prospectsObj, 'value');
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
					started_at: this.started_at,
					ended_at: this.ended_at,
					rep_id: this.rep_id,
					spots: this.spots,
					closed_at: this.closed_at,
					published_at: this.published_at
				});
			},
			getGroups(search, loading){
				loading(true);
				this.$http.get('groups', {search: search}).then(function (response) {
					this.groups = response.data.data;
					loading(false);
				});
			},
			getReps(search, loading){
				loading(true);
				this.$http.get('users', {search: search}).then(function (response) {
					this.reps = response.data.data;
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
						resource.update({id: this.tripId}, this.wizardData).then(function (resp) {
							$.extend(this, response.data.data);
							this.attemptedContinue = false;
							this.showSuccess = true;
						}, function (error) {
							this.showError = true;
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
		ready(){
			if (this.isUpdate) {
				this.$http.get('trips/' + this.tripId, {include: 'campaign,costs.payments,requirements,notes,deadlines'}).then(function (response) {
					let trip = response.data.data;
					// trim campaign
					$.extend(this, trip);
					this.campaign = trip.campaign.data;
					this.difficulty = trip.difficulty.toLowerCase().replace(' ', '_');
					// this.prospects = trip.prospects;
					this.prospectsObj = _.filter(this.prospectsList, function(p) { return _.contains(trip.prospects, p.value);});

					this.trip = trip;
					// this.wizardData.campaign_id = this.trip.campaign_id;
					// this.wizardData.country_code = this.trip.country_code;

				});
			} else {
				this.$http.get('campaigns/' + this.campaignId).then(function (response) {
					this.campaign = response.data.data;
				});
			}

			// get some groups
			this.$http.get('groups').then(function (response) {
				this.groups = response.data.data;
				this.groupObj = _.findWhere(this.groups, { id: this.trip.group_id});
			});
		}
	}
</script>