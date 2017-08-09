<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">

				<form id="TripSettings" class="form-horizontal" novalidate>

					<div class="form-group" :class="{ 'has-error': errors.has('spots') }">
						<label for="spots" class="col-sm-2 control-label">Spots Available</label>
						<div class="col-sm-10">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><i class="fa fa-users"></i></span>
								<input type="number" id="spots" v-model="spots" class="form-control"
									   name="spots" v-validate="{ required: true, min:0 }"/>
							</div>
							<div class="help-block">Number of companions a user can have. Leave at 0 to disable
								companions.
							</div>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': errors.has('closed') }">
						<label for="closed_at" class="col-sm-2 control-label">Registration Closes</label>
						<div class="col-sm-10">
							<date-picker :input-sm="true" :model.sync="closed_at|moment('YYYY-MM-DD HH:mm:ss')"></date-picker>
							<input type="datetime" class="form-control input-sm hidden" v-model="closed_at" name="closed="'required'" id" v-validate="closed_at">
						</div>
					</div>

					<div class="form-group">
						<label for="published_at" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<date-picker :input-sm="true" :model.sync="published_at|moment('YYYY-MM-DD HH:mm:ss')"></date-picker>
							<input type="datetime" class="form-control input-sm hidden" v-model="published_at" id="published_at">
						</div>
					</div>

				</form>

		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'trip-settings',
		data(){
			return {
				roles: [],
				attemptedContinue: false,

				// details data
				spots: null,
				closed_at: moment().toDate(),
				published_at: null,
			}
		},
		computed: {

		},
		methods: {
			populateWizardData(){
				$.extend(this.$parent.wizardData, {
					spots: this.spots,
					closed_at: this.closed_at,
					published_at: this.published_at
				});
			},
			onValid(){
				this.populateWizardData();
				this.$dispatch('settings', true);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripSettings[field.toLowerCase()].invalid && this.attemptedContinue
			}
		},
		activate(done){
			$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>
