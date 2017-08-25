<template >
	<div class="row">
		<div class="col-sm-12">

				<form id="TripDetailsForm" class="form-horizontal" novalidate>
					<div class="form-group">
						<label class="col-sm-2 control-label">Campaign</label>
						<div class="col-sm-10">
							<p>{{$parent.campaign.name | capitalize}}</p>
						</div>
					</div>
					<div class="form-group" :class="{ 'has-error': errors.has('group') }">
						<label class="col-sm-2 control-label">Group</label>
						<div class="col-sm-10">
							<v-select @keydown.enter.prevent=""  class="form-control" id="group" v-model="groupObj" :options="groups" :on-search="getGroups"
									  label="name" name="group" v-validate="'required'"></v-select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('type') }">
						<label for="type" class="col-sm-2 control-label">Type</label>
						<div class="col-sm-10">
							<select id="type" class="form-control input-sm" v-model="type"
									name="type" v-validate="'required'" required>
								<option value="">-- select --</option>
								<option value="full">Full</option>
								<option value="media">Media</option>
								<option value="medical">Medical</option>
								<option value="short">Short</option>
							</select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('prospects') }">
						<label class="col-sm-2 control-label">Perfect For</label>
						<div class="col-sm-10">
							<v-select @keydown.enter.prevent=""  multiple class="form-control" id="group" v-model="prospectsObj"
									  :options="prospectsList" label="name" placeholder="Select Prospects" name="prospects" v-validate="'required'"></v-select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('difficulty') }">
						<label for="difficulty" class="col-sm-2 control-label">Difficulty</label>
						<div class="col-sm-10">
							<select id="difficulty" class="form-control input-sm" v-model="difficulty"
									name="difficulty" v-validate="'required'" required>
								<option value="">-- select --</option>
								<option value="level_1">Level 1</option>
								<option value="level_2">Level 2</option>
								<option value="level_3">Level 3</option>
							</select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('companions') }">
						<label for="companion_limit" class="col-sm-2 control-label">Companion Limit</label>
						<div class="col-sm-10">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><i class="fa fa-users"></i></span>
								<input type="number" id="companion_limit" v-model="companion_limit" class="form-control"
									   name="companions" v-validate="'required|min:0'"/>
							</div>
							<div class="help-block">Number of companions a user can have. Leave at 0 to disable
								companions.
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': (errors.has('start') || errors.has('end')) }">
						<label for="started_at" class="col-sm-2 control-label">Dates</label>
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-6">
									<date-picker addon="Start" :has-error= "errors.has('start')" v-model="started_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="start" v-validate="'required'"></date-picker>
									<!--<input type="datetime" class="form-control hidden" v-model="started_at" id="started_at"
									       name="start" v-validate="'required'" required>-->
									<!--<div class="input-group input-group-sm"
										 :class="{ 'has-error': errors.has('start') }">
										<span class="input-group-addon">Start</span>
									</div>-->
								</div>
								<div class="col-sm-6">
									<date-picker addon="End" :has-error= "errors.has('end')" v-model="ended_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="end" v-validate="'required'"></date-picker>
									<!--<input type="datetime" class="form-control hidden" v-model="ended_at" id="ended_at"
									       name="end" v-validate="'required'" required>-->
									<!--<div class="input-group input-group-sm"
										 :class="{ 'has-error': errors.has('end') }">
										<span class="input-group-addon">End</span>
									</div>-->
								</div>
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('rep') }">
						<label class="col-sm-2 control-label">Trip Rep.</label>
						<div class="col-sm-10">
							<v-select @keydown.enter.prevent=""  multiple class="form-control" id="rep" v-model="repObj" :options="reps"
									  label="name"></v-select>
							<!--name="rep" v-validate="{ required: false}"-->
							<!--<select hidden v-model="rep_id">
								<option v-for="rep in reps" :value="rep">{{rep}}</option>
							</select>-->
						</div>
					</div>

				</form>

		</div>
	</div>
</template>
<style>
	#TripDetailsForm .form-horizontal .radio, .form-horizontal .checkbox {
		min-height: 24px;
		padding-top: 0;
	}
</style>
<script type="text/javascript">
	var marked = require('marked');
	import vSelect from "vue-select"
	export default{
		name: 'trip-details',
		components: {vSelect},
		data(){
			return {
				reps: [],
				//campaigns: [],
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
				started_at: null,
				ended_at: null,
				repObj: null,
				rep_id: '',
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
			populateWizardData(onValid){
				if (!onValid)

				$.extend(this.$parent.wizardData, {
					group_id: this.group_id,
					description: this.description,
					type: this.type,
					difficulty: this.difficulty,
					companion_limit: this.companion_limit,
					prospects: this.prospects,
					started_at: this.started_at,
					ended_at: this.ended_at,
					//rep_id: this.rep_id,
				});
			},
			getGroups(search, loading){
				loading(true);
				this.$http.get('groups', { params: { search: search } }).then((response) => {
					this.groups = response.data.data;
					loading(false);
				});
			},
			onValid(){
				this.populateWizardData(true);
				this.$emit('details', true);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripDetails[field.toLowerCase()].invalid && this.attemptedContinue
			},
		},
		activated(){
			$('html, body').animate({scrollTop: 0}, 300);

			// get some groups
			this.$http.get('groups').then((response) => {
				this.groups = response.data.data;
			});
		}

	}
</script>
