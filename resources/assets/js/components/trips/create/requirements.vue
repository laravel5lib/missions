<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripReqs">
				<form id="TripReqs" class="form-horizontal" novalidate>

					<div class="form-group" :class="{ 'has-error': checkForError('rep') }">
						<label for="rep" class="col-sm-2 control-label">Trip Rep.</label>
						<div class="col-sm-10">
							<select id="rep" class="form-control-input-sm" v-model="rep_id">
								<option value="">-- select --</option>
								<option v-for="rep in reps" :value="rep">{{rep}}</option>
							</select>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('requirements') }">
						<label for="requirements" class="col-sm-2 control-label">Requirements</label>
						<div class="col-sm-10">
							<!--<input type="date" class="form-control input-sm" v-model="closed_at" v-validate:closed="{ required: true }" id="closed_at">-->
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
				rep_id: null,
				requirements:[],
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				this.$dispatch('options', true);
				//this.$parent.details = this.details;
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripReqs[field.toLowerCase()].invalid && this.attemptedContinue
			}
		},
		activate(done){
			//$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>
