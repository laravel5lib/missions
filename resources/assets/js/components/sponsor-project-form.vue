<template>
	<div>
		<validator name="SponsorProjectForm">
			<form class="form-horizontal" novalidate>
				<div class="form-group">
					<div class="col-sm-6" :class="{ 'has-error': errors.has('cause') }">
						<label for="name">Choose a cause to support</label>
						<select class="form-control" v-model="causeId" v-validate:cause="['required']">
							<option value="">Select a Cause</option>
							<option :value="cause.id" v-for="cause in causes" v-text="cause.name"></option>
						</select>
					</div>
					<div class="col-sm-6" :class="{ 'has-error': errors.has('country') }">
						<label for="name">Select a location</label>
						<!--<v-select @keydown.enter.prevent=""  class="form-control" id="country" :value.sync="countryCodeObj" :options="countries"
								  label="name"></v-select>-->
						<select name="country" id="country" class="form-control" v-model="country_code"
								v-validate:country="{ required: true }">
							<option value="">Select a Country</option>
							<option :value="country.code" v-for="country in availableCountries">{{country.name}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6" :class="{ 'has-error': errors.has('initiative') }">
						<label for="initiative">Select a type</label>
						<select class="form-control" v-model="initiativeId" v-validate:initiative="['required']">
							<option value="">Select a type</option>
							<option :value="initiative.id" v-for="initiative in availableInitiatives"
									v-text="initiative.type|capitalize"></option>
						</select>
					</div>
					<div class="col-sm-6" :class="{ 'has-error': errors.has('end') }">
						<label for="name">Desired Completion Date</label>
						<select class="form-control" v-validate:end="['required']" v-model="complete_at" required>
							<option value="June 2017">June 2017</option>
							<option value="Dec. 2017">December 2017</option>
							<option value="June 2018">June 2018</option>
							<option value="Dec. 2018">December 2018</option>
						</select>
					</div>
				</div>
				<div class="form-group" v-if="initiativeId">
					<div class="col-sm-12">
						<label for="name">Project Description</label>
						<p v-text="initiative.short_desc"></p>
					</div>
					<!-- <div class="col-sm-6 text-center">
						<label for="name">Cost Starting At</label>
						<h1 class="text-success" v-text="total|currency"></h1>
					</div> -->
				</div>

				<div class="form-group">
					<div class="col-sm-6" :class="{ 'has-error': errors.has('projectname') }">
						<label for="project_name">Project Name</label>
						<input type="text" class="form-control" name="project_name" id="project_name"
							   v-model="project_name"
							   placeholder="Annie's Water Well"
							   v-validate:projectname="{ required: true, minlength:1, maxlength:100 }"
							   maxlength="100" minlength="1" required>
					</div>
					<div class="col-sm-6" :class="{ 'has-error': errors.has('name') }">
						<label for="name">Your Name</label>
						<input type="text" class="form-control" name="name" id="name" v-model="name"
							   placeholder="Annie Smith"
							   v-validate:name="{ required: true, minlength:1, maxlength:100 }"
							   maxlength="100" minlength="1" required>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6" :class="{ 'has-error': errors.has('email') }">
						<label for="name">Your Email</label>
						<input type="text" class="form-control" name="email" id="email" v-model="email"
							   v-validate:email="['required']">
					</div>
					<div class="col-sm-6" :class="{ 'has-error': errors.has('phone') }">
						<label for="name">Your Phone</label>
						<input type="tel" class="form-control" v-model="phone_one | phone" id="infoPhone"
							   placeholder="123-456-7890" v-validate:phone="['required', 'phone']">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12 text-center">
						<a @click="submit()" class="btn btn-primary">Send Request</a>
					</div>
				</div>
			</form>
		</validator>
	</div>
</template>
<script type="text/javascript">
	import vSelect from "vue-select";
	export default{
		name: 'sponsor-project-form',
		props: {
			causeId: {
				type: String,
				default: ''
			},
			initiativeId: {
				type: String,
				default: ''
			},
		},
		components: {vSelect},
		data(){
			return {
				project_name: '',
				name: '',
				email: '',
				phone_one: '',
				country_code: '',
				complete_at: '',
				total: 0,

				// logic variables
				attemptSubmit: false,
				availableCountries: [],
				countries: [],
				causes: [],
				initiatives: [],
				availableInitiatives: [],
				countryCodeObj: null,
				country: null,
				causeResource: this.$resource('causes{/causeId}'),
				intiativeResource: this.$resource('causes{/causeId}/initiatives{/initiativeId}'),
			}
		},
		computed: {
			country(){
				return _.findWhere(this.causes, {code: this.country_code});
			},
			cause(){
				return _.findWhere(this.causes, {id: this.causeId});
			},
			initiative(){
				return _.findWhere(this.initiatives, {id: this.initiativeId});
			},
			filteredCountries(){
			    let cause = this.cause;
			    return _.filter(this.availableCountries, function (country) {
				    return _.contains(cause.countries, country.code.toLocaleLowerCase());
                });
			}
		},
		watch: {
			'causeId': function (val) {
				// Update Country List based on Cause selected
				// Get all available country codes for the selected cause
				let allCodes = _.findWhere(this.causes, { id: val }).countries;
				// filter selectable countries
				this.availableCountries = [];
				_.each(allCodes, function (country) {
					let test = undefined;
					if (test = _.findWhere(this.countries, { code: country.code.toLowerCase()}))
					    this.availableCountries.push(test);
				}.bind(this));
				this.getInitiatives();
			},
			'country_code': function (val) {
				// Update Initiative/Project List based on Country selected
                // this.availableInitiatives = _.where(this.initiatives, { country: { code: val } });
                this.availableInitiatives = _.filter(this.initiatives, function (ini) {
					return ini.country.code === val;
				});
			},
			'initiativeId': function (val) {
				this.$nextTick(function () {
					// this.complete_at = this.initiative.ended_at;
					// this.calculateTotal();
				})
			}
		},
		methods: {
			reset(){
				$.extend(this, {
					project_name: '',
					name: '',
					email: '',
					phone_one: '',
					complete_at: '',
				});
				this.attemptSubmit = false;
			},
			checkForError(field){
				return this.$SponsorProjectForm[field].invalid && this.attemptSubmit;
			},
			getCauses() {
				this.causeResource
						.get(null)
						.then(function (response) {
							this.causes = response.body.data;
						}, function (error) {
							console.log(error);
						});
			},
			getInitiatives() {
				this.intiativeResource
						.get({causeId: this.causeId, current: true})
						.then(function (response) {
							this.initiatives = response.body.data;
						}, function (error) {
							console.log(error);
						});
			},
			calculateTotal(){
				let total = 0;
				_.each(this.initiative.costs.data, function (cost) {
					if (cost.type === 'static') {
						total += parseFloat(cost.amount);
					}
				});
				this.total = total;
			},
			submit(){
				this.attemptSubmit = true;
				if (this.$SponsorProjectForm.valid) {
					let data = {
						project_name: this.project_name,
						name: this.name,
						phone_one: this.phone_one,
						email: this.email,
						complete_at: this.complete_at,
						total: this.total,
						causeId: this.causeId,
						initiativeId: this.initiativeId,
					};
					this.$http.post('sponsor-project', data)
							.then(function (response) {
								console.log(response);
								this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
								this.reset();
							}, function (error) {
								console.log(error);
								this.$root.$emit('showError', 'Something went wrong...');
							});
				} else {
					this.$root.$emit('showError', 'Please check that the form is complete');
				}
			}
		},
		mounted(){
			// Get Countries
			this.$http.get('utilities/countries').then(function (response) {
				this.countries = response.body.countries;
			}, function (error) {
				console.log(error);
			});

			// Get Causes
			this.getCauses();

			// Get Initiatives
		}
	}
</script>