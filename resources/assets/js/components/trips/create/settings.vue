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
				attemptedContinue: false,

				// details data
				spots: null
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				//this.$parent.details = this.details;
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
