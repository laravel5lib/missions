<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripDetails">
				<form id="TripDetailsForm" class="form-horizontal" novalidate>
					<div class="form-group" :class="{ 'has-error': checkForError('campaign') }">
						<label for="campaign" class="col-sm-2 control-label">Campaign</label>
						<div class="col-sm-10">
							<select id="campaign" class="form-control input-sm" v-model="campaign_id"
									v-validate:campaign="{ required: true }" required>
								<option value="">-- select --</option>
								<option v-for="campaign in campaigns" :value="campaign.id">{{campaign.name}}</option>
							</select>
						</div>
					</div>
					<div class="form-group" :class="{ 'has-error': checkForError('group') }">
						<label for="group" class="col-sm-2 control-label">Group</label>
						<div class="col-sm-10">
							<select id="group" class="form-control input-sm" v-model="group_id"
									v-validate:group="{ required: true }" required>
								<option value="">-- select --</option>
								<option v-for="group in groups" :value="group.id">{{group.name}}</option>
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
						<label for="collapseOne" data-toggle="collapse" data-target="#collapseOne" class="col-sm-2 control-label">Perfect For</label>
						<div class="col-sm-10">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
								<div class="panel panel-default" :class="{ 'panel-danger': checkForError('prospects') }">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion"
											   href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
												Prospects: {{prospectsNames}}
											</a>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
										 aria-labelledby="headingOne">
										<div class="panel-body">
											<h6>General</h6>
											<div class="checkbox" v-for="(value, name) in prospectsList.general">
												<label>
													<input type="checkbox" :value="value" v-model="prospects" v-validate:prospects="{ required: true }">
													{{name}}
												</label>
											</div>
											<h6>Medical</h6>
											<div class="checkbox" v-for="(value, name) in prospectsList.medical">
												<label>
													<input type="checkbox" :value="value" v-model="prospects" v-validate:prospects>
													{{name}}
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
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
	export default{
		name: 'trip-details',
		data(){
			return {
				campaigns: [],
				groups: [],
				prospectsList: {
					general: {
						"ADLT": "Adults",
						"YNGA": "Young Adults (18-29)",
						"TEEN": "Teens (13+)",
						"FAMS": "Families",
						"MMEN": "Men",
						"WMEN": "Women",
						"MEDI": "Media Professionals",
						"PSTR": "Pastors",
						"BUSL": "Business Leaders",
						"MDPF": "Medical Professionals"
					},
					medical: {
						"MDPF": "Medical Professionals",
						"PHYS": "Physicians",
						"SURG": "Surgeons",
						"REGN": "Registered Nurses",
						"DENT": "Dentists",
						"HYGN": "Hygienists",
						"DENA": "Dental Assistants",
						"PHYA": "Physician Assistants",
						"NURP": "Nurse Practitioners",
						"PHAR": "Pharmacists",
						"PHYT": "Physical Therapists",
						"CHRO": "Chiropractors",
						"MSTU": "Medical Students",
						"DSTU": "Dental Students",
						"NSTU": "Nursing Students"
					},
					all: [
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
					]
				},
				attemptedContinue: false,

				// details data
				campaign_id: '',
				group_id: '',
				description: '',
				type: '',
				difficulty: '',
				companion_limit: 0,
				prospects: [],
				started_at: null,
				ended_at: null,
			}
		},
		computed: {
			campaigns(){
				return this.$parent.campaigns;
			},
			groups(){
				return this.$parent.groups;
			},
			prospectsNames(){
				var arr = this.prospects;
				var names = [];
				if (arr.length) {
					arr.forEach(function (prospect) {
						this.prospectsList.all.some(function (obj) {
							if (obj.value === prospect) {
								names.push(obj.name);
								return true;
							}
						}, this);
					}, this);
					return names.toString().replace(/,/g, ', ');
				} else {
					return 'None Selected';
				}
			}
		},
		methods: {
			onValid(){
				this.$parent.details = this.details;
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
