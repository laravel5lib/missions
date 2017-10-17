<template>
	<div>
		<form name="SponsorProjectForm" class="form-horizontal" novalidate>
				<div class="form-group">
					<div class="col-sm-6" v-error-handler="{ value: causeIdentifier, handle: 'cause' }">
						<label for="cause">Choose a cause to support</label>
						<select class="form-control" v-model="causeIdentifier" v-validate="'required'" name="cause" id="cause">
							<option value="">Select a Cause</option>
							<option :value="cause.id" v-for="cause in causes" v-text="cause.name"></option>
						</select>
					</div>
					<div class="col-sm-6" v-error-handler="{ value: country_code, handle: 'country' }">
						<label for="country">Select a location</label>
						<!--<v-select @keydown.enter.prevent=""  class="form-control" id="country" v-model="countryCodeObj" :options="countries"
								  label="name"></v-select>-->
						<select name="country" id="country" class="form-control" v-model="country_code"
								v-validate="'required'">
							<option value="">Select a Country</option>
							<option :value="country.code" v-for="country in availableCountries">{{country.name}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6" v-error-handler="{ value: initiativeIdentifier, handle: 'initiative' }">
						<label for="initiative">Select a type</label>
						<select class="form-control" v-model="initiativeIdentifier" v-validate="'required'" name="initiative">
							<option value="">Select a type</option>
							<option :value="initiative.id" v-for="initiative in availableInitiatives">{{initiative.type|capitalize}}</option>
						</select>
					</div>
					<div class="col-sm-6" v-error-handler="{ value: complete_at, handle: 'end' }">
						<label for="name">Desired Completion Date</label>
						<select class="form-control" v-validate="'required'" name="end" v-model="complete_at" required>
							<option value="Dec. 2017">December 2017</option>
							<option value="June 2018">June 2018</option>
							<option value="Dec. 2018">December 2018</option>
						</select>
					</div>
				</div>
				<div class="form-group" v-if="initiativeIdentifier">
					<div class="col-sm-12">
						<label for="name">Project Description</label>
						<p v-text="initiative.short_desc"></p>
					</div>
					<!-- <div class="col-sm-6 text-center">
						<label for="name">Cost Starting At</label>
						<h1 class="text-success" v-text="currency(total)"></h1>
					</div> -->
				</div>

				<div class="form-group">
					<div class="col-sm-6" v-error-handler="{ value: project_name, handle: 'projectname' }">
						<label for="project_name">Project Name</label>
						<input type="text" class="form-control" id="project_name"
							   v-model="project_name"
							   placeholder="Annie's Water Well"
							   name="projectname" v-validate="'required|min:1|max:100'"
							   maxlength="100" minlength="1" required>
					</div>
					<div class="col-sm-6" v-error-handler="{ value: name, handle: 'name' }">
						<label for="name">Your Name</label>
						<input type="text" class="form-control" name="name" id="name" v-model="name"
							   placeholder="Annie Smith" v-validate="'required|alpha_spaces|min:1|max:100'"
							   maxlength="100" minlength="1" required>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6" v-error-handler="{ value: email, handle: 'email' }" >
						<label for="name">Your Email</label>
						<input type="email" class="form-control" name="email" id="email" v-model="email" v-validate="'required|email'">
					</div>
					<div class="col-sm-6" v-error-handler="{ value: phone_one, handle: 'phone' }" >
						<phone-input v-model="phone_one" label="Your Phone" v-validate="'required'" data-vv-value-path="value" name="phone"></phone-input>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12 text-center">
						<a @click="submit" class="btn btn-primary">Send Request</a>
					</div>
				</div>
			</form>
	</div>
</template>
<script type="text/javascript">
	import $ from 'jquery';
	import _ from 'underscore';
	import vSelect from "vue-select";
	import errorHandler from './error-handler.mixin'
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
		mixins: [errorHandler],
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
				availableCountries: [],
				countries: [],
				causes: [],
				initiatives: [],
				availableInitiatives: [],
				countryCodeObj: null,
				causeIdentifier: '',
                initiativeIdentifier: '',
			}
		},
		computed: {
			country(){
				return _.findWhere(this.causes, {code: this.country_code});
			},
			cause(){
				return _.findWhere(this.causes, {id: this.causeIdentifier});
			},
			initiative(){
				return _.findWhere(this.initiatives, {id: this.initiativeIdentifier});
			},
			filteredCountries(){
			    let cause = this.cause;
			    return _.filter(this.availableCountries, (country) => {
				    return _.contains(cause.countries, country.code.toLocaleLowerCase());
                });
			}
		},
		watch: {
			'causeIdentifier'(val) {
				// Update Country List based on Cause selected
				// Get all available country codes for the selected cause
				let allCodes = _.findWhere(this.causes, { id: val }).countries;
				// filter selectable countries
				this.availableCountries = [];
				_.each(allCodes, (country) => {
					let test = _.findWhere(this.countries, { code: country.code.toLowerCase()});
					if (test) this.availableCountries.push(test);
				});
				this.getInitiatives();
			},
			'country_code'(val) {
				// Update Initiative/Project List based on Country selected
                // this.availableInitiatives = _.where(this.initiatives, { country: { code: val } });
                this.availableInitiatives = _.filter(this.initiatives, (ini) => {
					return ini.country.code === val;
				});
			},
			'initiativeIdentifier'(val) {
				this.$nextTick(() =>  {
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
			},
			getCauses() {
				this.$http
						.get(`causes`)
						.then((response) => {
							this.causes = response.data.data;
						}, (error) =>  {
							console.log(error);
						});
			},
			getInitiatives() {
				this.$http
						.get(`causes/${this.causeIdentifier}/initiatives`, { params: { current: true } })
						.then((response) => {
							this.initiatives = response.data.data;
						}, (error) =>  {
							console.log(error);
						});
			},
			calculateTotal(){
				let total = 0;
				_.each(this.initiative.costs.data, (cost) => {
					if (cost.type === 'static') {
						total += parseFloat(cost.amount);
					}
				});
				this.total = total;
			},
			submit(){
                this.$validator.validateAll().then(result => {
                    let data;
                    if (!result) {
                        this.$root.$emit('showError', 'Please check that the form is complete');
                        return false;
                    }

                    data = {
                        project_name: this.project_name,
                        name: this.name,
                        phone_one: this.phone_one,
                        email: this.email,
                        complete_at: this.complete_at,
                        total: this.total,
                        causeId: this.causeIdentifier,
                        initiativeId: this.initiativeIdentifier,
                    };

					this.$http.post('sponsor-project', data)
							.then((response) => {
								console.log(response);
								this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
								this.reset();
							}, (error) =>  {
								console.log(error);
								this.$root.$emit('showError', 'Something went wrong...');
							});
				})
			}
		},
		mounted(){
		    this.causeIdentifier = this.causeId;
		    this.initiativeIdentifier = this.initiativeId;
			// Get Countries
			this.$http.get('utilities/countries').then((response) => {
				this.countries = response.data.countries;
			}, (error) =>  {
				console.log(error);
			});

			// Get Causes
			this.getCauses();

			// Get Initiatives
		}
	}
</script>