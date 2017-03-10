<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripDetails" @valid="onValid">
				<form id="TripDetailsForm" class="form-horizontal" novalidate>
					<div class="form-group">
						<label class="col-sm-2 control-label">Campaign</label>
						<div class="col-sm-10">
							<p>{{$parent.trip.campaign.name|capitalize}}</p>
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

					<div class="form-group" :class="{ 'has-error': checkForError('description') }">
						<label for="description" class="col-sm-2 control-label">
							Description
							<button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#markdownPrev" aria-expanded="false" aria-controls="markdownPrev">
								Preview
							</button>
						</label>
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-12">
									<textarea name="description" id="description" rows="5" v-model="description"
											  class="form-control"
											  v-validate:description="{ required: true}"></textarea>
								</div>
								<div class="col-sm-12 collapse" id="markdownPrev">
									<br>
									<div class="well" v-html="description | marked"></div>
								</div>
							</div>

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
								<option value="media">Media</option>
								<option value="medical">Medical</option>
								<option value="international">International</option>
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
										<date-picker class="form-control input-sms" :time.sync="started_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
										<input type="datetime" class="form-control hidden" v-model="started_at" id="started_at"
											   v-validate:start="{ required: true }" required>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group input-group-sm"
										 :class="{ 'has-error': checkForError('end') }">
										<span class="input-group-addon">End</span>
										<date-picker class="form-control input-sms" :time.sync="ended_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
										<input type="datetime" class="form-control hidden" v-model="ended_at" id="ended_at"
											   v-validate:end="{ required: true }" required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('rep') }">
						<label class="col-sm-2 control-label">Trip Rep.</label>
						<div class="col-sm-10">
							<v-select multiple class="form-control" id="rep" :value.sync="repObj" :options="reps"
									  label="name"></v-select>
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
					this.$validate(true);
				$.extend(this.$parent.wizardData, {
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
			getGroups(search, loading){
				loading(true);
				this.$http.get('groups', { params: { search: search } }).then(function (response) {
					this.groups = response.body.data;
					loading(false);
				});
			},
			onValid(){
				this.populateWizardData(true);
				this.$dispatch('details', true);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripDetails[field.toLowerCase()].invalid && this.attemptedContinue
			},
		},
		activate(done){
			$('html, body').animate({scrollTop: 0}, 300);

			// get some groups
			this.$http.get('groups').then(function (response) {
				this.groups = response.body.data;
			});
			done();
		},
		events: {
			'trip'(val){
				this.$http.get('groups/' + val.group_id).then(function (response) {
					this.groupObj = response.body.data;
				});
				var arr = [];
				_.forEach(this.prospectsList, function (prospect) {
					if ( _.contains(val.prospects, prospect.value))
						arr.push(prospect);
				});
				this.prospectsObj = arr;
				$.extend(this, {
					group_id: val.group_id,
					description: val.description,
					type: val.type,
					difficulty: val.difficulty.split(' ')[0] + '_' + val.difficulty.split(' ')[1],
					companion_limit: val.companion_limit,
					prospects: val.prospects,
					started_at: val.started_at,
					ended_at: val.ended_at,
					rep_id: val.rep_id,

				});
			}
		}

	}
</script>
