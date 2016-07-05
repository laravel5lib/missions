<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripDetails" @valid="onValid">
				<form id="TripDetailsForm" class="form-horizontal" novalidate>
					<div class="form-group" :class="{ 'has-error': checkForError('campaign') }">
						<label for="campaign" class="col-sm-2 control-label">Campaign</label>
						<div class="col-sm-10">
							<v-select class="form-controls" id="campaign" :value.sync="campaignObj" :options="campaigns" label="name"></v-select>
							<select hidden v-model="campaign_id" v-validate:campaign="{ required: true}">
								<option :value="campaign.id" v-for="campaign in campaigns">{{campaign.name}}</option>
							</select>
						</div>
					</div>
					<div class="form-group" :class="{ 'has-error': checkForError('group') }">
						<label for="group" class="col-sm-2 control-label">Group</label>
						<div class="col-sm-10">
							<v-select class="form-controls" id="group" :value.sync="groupObj" :options="groups" label="name"></v-select>
							<select hidden v-model="group_id" v-validate:group="{ required: true}">
								<option :value="group.id" v-for="group in groups">{{group.name}}</option>
							</select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('description') }">
						<label for="description" class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10">
							<textarea name="description" id="description" rows="2" v-model="description" class="form-control"
									  v-validate:description="{ required: true, minlength:1, maxlength:120 }" maxlength="255"
									  minlength="1"></textarea>
							<div class="help-block">{{description.length}}/255</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('type') }">
						<label for="type" class="col-sm-2 control-label">Type</label>
						<div class="col-sm-10">
							<select id="type" class="form-control input-sm" v-model="type"
									v-validate:type="{ required: true }" required>
								<option value="">-- select --</option>
								<option value="full">Full</option>
								<option value="media">Media</option>
								<option value="medical">Medical</option>
								<option value="short">Short</option>
							</select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('prospects') }">
						<label class="col-sm-2 control-label">Perfect For</label>
						<div class="col-sm-10">
							<v-select multiple class="form-controls" id="group" :value.sync="prospectsObj"
									  :options="prospectsList" label="name" placeholder="Select Prospects"></v-select>
							<select hidden multiple v-model="prospects" v-validate:prospects="{ required: true}">
								<option :value="prospect.value" v-for="prospect in prospectsList">{{prospect.name}}</option>
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
									<div class="input-group input-group-sm" :class="{ 'has-error': checkForError('start') }">
										<span class="input-group-addon">Start</span>
										<input type="date" class="form-control" v-model="started_at" id="started_at"
											   v-validate:start="{ required: true }" required>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group input-group-sm" :class="{ 'has-error': checkForError('end') }">
										<span class="input-group-addon">End</span>
										<input type="date" class="form-control" v-model="ended_at" id="ended_at"
											   v-validate:end="{ required: true }" required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('rep') }">
						<label for="rep" class="col-sm-2 control-label">Trip Rep.</label>
						<div class="col-sm-10">
							<v-select multiple class="form-controls" id="rep" :value.sync="repObj" :options="reps" label="name"></v-select>
							<!--v-validate:rep="{ required: false}"-->
							<select hidden v-model="rep_id">
								<option v-for="rep in reps" :value="rep">{{rep}}</option>
							</select>
						</div>
					</div>

				</form>
			</validator>
		</div>
	</div>
</template>
<style>
	#TripDetailsForm .form-horizontal .radio, .form-horizontal .checkbox {
		min-height: 24px;
		padding-top: 0;
	}
</style>
<script>
	import vSelect from "vue-select"
	export default{
		name: 'trip-details',
		components: {vSelect},
		data(){
			return {
				reps: [],
				campaigns: [],
				groups: [],
				prospectsList: [
					{value: "ADLT", name: "Adults"},
					{value: "YNGA", name: "Young Adults (18-29)"},
					{value: "TEEN", name: "Teens (13+)"},
					{value: "FAMS", name: "Families"},
					{value: "MMEN", name: "Men"},
					{value: "WMEN", name: "Women"},
					{value: "MEDI", name: "Media Professionals"},
					{value: "PSTR", name: "Pastors"},
					{value: "BUSL", name: "Business Leaders"},
					{value: "MDPF", name: "Medical Professionals"},
					{value: "PHYS", name: "Physicians"},
					{value: "SURG", name: "Surgeons"},
					{value: "REGN", name: "Registered Nurses"},
					{value: "DENT", name: "Dentists"},
					{value: "HYGN", name: "Hygienists"},
					{value: "DENA", name: "Dental Assistants"},
					{value: "PHYA", name: "Physician Assistants"},
					{value: "NURP", name: "Nurse Practitioners"},
					{value: "PHAR", name: "Pharmacists"},
					{value: "PHYT", name: "Physical Therapists"},
					{value: "CHRO", name: "Chiropractors"},
					{value: "MSTU", name: "Medical Students"},
					{value: "DSTU", name: "Dental Students"},
					{value: "NSTU", name: "Nursing Students"}
				],
				attemptedContinue: false,

				// details data
				campaign_id: this.$parent.campaignId,
				campaignObj: _.findWhere(this.campaigns, {campaign_id: this.campaign_id}),
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
			campaign_id(){
				return _.isObject(this.campaignObj) ? this.campaignObj.id : null;
			},
			group_id(){
				return _.isObject(this.groupObj) ? this.groupObj.id : null;
			},
			rep_id(){
				return _.isObject(this.repObj) ? this.repObj.id : null;
			},
			prospects(){
				return _.pluck(this.prospectsObj, 'value');
			},
			campaigns(){
				return this.$parent.campaigns;
			},
			groups(){
				return this.$parent.groups;
			}
		},
		methods: {
			populateWizardData(){
				$.extend(this.$parent.wizardData, {
					campaign_id: this.campaign_id,
					group_id: this.group_id,
					description: this.description,
					type: this.type,
					difficulty: this.difficulty,
					companion_limit: this.companion_limit,
					prospects: this.prospects,
					started_at: this.started_at,
					ended_at: this.ended_at,
					rep_id: this.rep_id,

				});
			},
			onValid(){
				this.populateWizardData()
				this.$dispatch('details', true);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripDetails[field.toLowerCase()].invalid && this.attemptedContinue
			},
			activate(done){
				$('html, body').animate({scrollTop: 0}, 300);
				done();
			}

		}
	}
</script>
