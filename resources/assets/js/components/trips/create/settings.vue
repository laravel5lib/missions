<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripSettings">
				<form id="TripSettings" class="form-horizontal" novalidate>
					<div class="form-group" :class="{ 'has-error': checkForError('description') }">
						<label for="description" class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10">
					<textarea name="short_desc" id="description" rows="2" v-model="short_desc" class="form-control"
							  v-validate:description="{ required: true, minlength:1, maxlength:120 }" maxlength="120"
							  minlength="1"></textarea>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': (checkForError('start') || checkForError('end')) }">
						<label for="started_at" class="col-sm-2 control-label">Dates</label>
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-6">
									<div class="input-group" :class="{ 'has-error': checkForError('start') }">
										<span class="input-group-addon">Start</span>
										<input type="date" class="form-control" v-model="started_at" id="started_at"
											   v-validate:start="{ required: true }" required>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group" :class="{ 'has-error': checkForError('end') }">
										<span class="input-group-addon">End</span>
										<input type="date" class="form-control" v-model="ended_at" id="ended_at"
											   v-validate:end="{ required: true }" required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="published_at" class="col-sm-2 control-label">Published Date</label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon">Published</span>
								<input type="date" class="form-control" v-model="published_at" id="published_at">
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('url') }">
						<label for="description" class="col-sm-2 control-label">Page Url</label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon">www.missions.me/campaigns/</span>
								<input type="text" id="page_url" v-model="page_url" class="form-control"
									   v-validate:url="{ required: false,  }"/>
							</div>
						</div>
					</div>
				</form>
			</validator>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'trip-settings',
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
					}
				},
				attemptedContinue: false,

				// details data
				campaign_id: null,
				group_id: null,
				type: null,
				difficulty: null,
				companion_limit: 0,
				prospects: [],
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				this.$parent.details = this.details;
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripSettings[field.toLowerCase()].invalid && this.attemptedContinue
			}
		},
		activate(done){
			this.$dispatch('settings', true);
			//$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>
