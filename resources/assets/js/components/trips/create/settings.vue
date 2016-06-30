<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripSettings">
				<form id="TripSettings" class="form-horizontal" novalidate>


					<div class="form-group" :class="{ 'has-error': checkForError('url') }">
						<label for="page_url" class="col-sm-2 control-label">Page Url</label>
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
				roles: [],
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
