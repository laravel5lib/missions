<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripSettings">
				<form id="TripSettings" class="form-horizontal" novalidate>

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
							<input type="date" class="form-control input-sm" v-model="closed_at" v-validate:closed="{ required: true }" id="closed_at">
						</div>
					</div>

					<div class="form-group">
						<label for="published_at" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<input type="date" class="form-control input-sm" v-model="published_at" id="published_at">
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
			onValid(){
				this.$dispatch('settings', true);
				//this.$parent.details = this.details;
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
