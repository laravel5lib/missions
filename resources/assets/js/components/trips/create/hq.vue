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
<script>
	export default{
		name: 'trip-hq',
		data(){
			return {

				// hq data
				hq_name: null,
				hq_location: null,
				hq_start_date: null,
				hq_latest_arrival_date: null,
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				this.$dispatch('hq', true);
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
