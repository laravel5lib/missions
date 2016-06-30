<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripParking">
				<form id="TripParking" class="form-horizontal" novalidate>

					<div class="form-group" :class="{ 'has-error': checkForError('name') }">
						<label for="name" class="col-sm-2 control-label">Location Name</label>
						<div class="col-sm-10">
							<input type="text" id="name" v-model="parking_name" class="form-control"
								   v-validate:name="{ required: false  }"/>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('address') }">
						<label for="address" class="col-sm-2 control-label">Address</label>
						<div class="col-sm-10">
							<input type="text" id="address" v-model="parking_location" class="form-control"
								   v-validate:address="{ required: false  }"/>
						</div>
					</div>

					<div class="form-group" :class="{ 'has-error': checkForError('rate') }">
						<label for="rate" class="col-sm-2 control-label">Daily Rate</label>
						<div class="col-sm-10">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><i class="fa fa-usd"></i></span>
								<input type="number" id="rate" v-model="parking_daily_rate" class="form-control"
									   v-validate:rate="{ required: false, min:0 }"/>
							</div>
							<div class="help-block">Number of companions a user can have. Leave at 0 to disable
								companions.
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
		name: 'trip-parking',
		data(){
			return {

				// details data
				parking_name: null,
				parking_location: null,
				parking_daily_rate: null
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				this.$dispatch('parking', true);
				$.extend(this.$parent.wizardData, this.$data);
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripParking[field.toLowerCase()].invalid && this.attemptedContinue
			}
		},
		activate(done){
			$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>
