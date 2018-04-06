<template>
	<div class="panel panel-default">

		<div class="panel-heading">
			<h3>Start New Campaign</h3>
		</div>

		<form @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)" class="form-horizontal">

			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4">
						<h5>Campaign Details</h5>
						<p class="text-muted">These details appear to the end-user in the <a href="/campaigns" target="_blank">campaign cards</a>.</p>
					</div>
					<div class="col-sm-8">
						<div class="form-group" :class="{'has-error' : form.errors.has('name')}">
							<div class="col-xs-12">
								<label for="name">Name</label>
								<input type="text"
									   class="form-control"
									   name="name"
									   id="name"
									   v-model="form.name"
									   debounce="250"
									   placeholder="Campaign Name"
									   maxlength="100"
									   minlength="1"
									   required>
								<span class="help-block" v-text="form.errors.get('name')" v-if="form.errors.has('name')"></span>
							</div>
						</div>
						<div class="form-group" :class="{'has-error' : form.errors.has('country_code')}">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="country_code">Country</label>
								<v-select @keydown.enter.prevent=""
										  class="form-control"
										  name="country_code"
										  id="country_code"
										  v-model="countryCodeObj"
										  :options="UTILITIES.countries"
										  label="name"
										  v-validate="'required'">
								</v-select>
								<span class="help-block" v-text="form.errors.get('country_code')" v-if="form.errors.has('country_code')"></span>
							</div>
						</div>
						<div class="form-group" :class="{'has-error' : form.errors.has('short_desc')}">
							<div class="col-xs-12">
								<label for="description">Short Description (optional)</label>
								<textarea id="description"
										  rows="5"
										  v-model="form.short_desc"
										  class="form-control"
										  name="description"
										  maxlength="225"
										  minlength="1">
								</textarea>
                        		<div v-if="form.short_desc" class="help-block">{{form.short_desc.length}}/255 characters remaining</div>
								<span class="help-block" v-text="form.errors.get('short_desc')" v-if="form.errors.has('short_desc')"></span>
							</div>
						</div>
					</div>
				</div>
				<hr class="divider">
				<div class="row">
					<div class="col-sm-4">
						<h5>Campaign Dates</h5>
						<p class="text-muted">The start and end of the campaign. These dates are visible to the end-user.</p>
					</div>
					<div class="col-sm-8">
						<div class="form-group" :class="{'has-error' : form.errors.has('started_at')}">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="started_at">Start Date</label>
								<input type="text" class="form-control" v-model="startDate" v-mask="'##/##/####'" placeholder="mm/dd/yyyy">
								<span class="help-block" v-text="form.errors.get('started_at')" v-if="form.errors.has('started_at')"></span>
							</div>
						</div>
						<div class="form-group" :class="{'has-error' : form.errors.has('ended_at')}">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="started_at">End Date</label>
								<input type="text" class="form-control" v-model="endDate" v-mask="'##/##/####'" placeholder="mm/dd/yyyy">
								<span class="help-block" v-text="form.errors.get('ended_at')" v-if="form.errors.has('ended_at')"></span>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end panel-body -->
			<div class="panel-footer">
				<div class="form-group">
					<hr class="divider inv md">
					<div class="col-xs-12 text-right">
						<a href="/admin/campaigns" class="btn btn-link">Cancel</a>
						<button type="submit" class="btn btn-primary">Start Campaign</button>
					</div>
				</div>
			</div>

		</form>

	</div><!-- end panel-->
</template>
<script type="text/javascript">
    import _ from 'underscore';
	import vSelect from "vue-select";
    import utilities from'../utilities.mixin';

	export default{
		name: 'campaign-create',
		components: {vSelect},
        mixins: [utilities],
        data(){
			return {
				form: new Form({
					name: null,
					country_code: null,
					short_desc: null,
					started_at: null,
					ended_at: null
				}),
				countryCodeObj: null,
				startDate: null,
				endDate: null
            }
		},
		computed: {
			country: {
			    get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                },
				set() {},
			},
		},
		watch: {
			// 'name'(val, oldVal) {
			// 	if (typeof val === 'string') {
			// 		// pre-populate slug
			// 		this.$http.get('utilities/make-slug/' + val, { params: { hideLoader: true } })
			// 			.then((response) => {
			// 				this.page_url = response.data.slug;
			// 			});
			// 	}
			// },
			'country'(val) {
				this.form.country_code = val;
			},
			'startDate'(val) {
				this.form.started_at = moment(val).format('YYYY-MM-DD');
			},
			'endDate'(val) {
				this.form.ended_at = moment(val).format('YYYY-MM-DD');
			}
		},
		methods: {
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
			},
			onSubmit(){
                this.form.submit('post', '/campaigns')
					.then(data => {
						swal("Good job!", "Campaign has been created.", "success", {
							button: true,
							timer: 3000
						}).then((value) => {
							window.location.href = `/admin/campaigns/${data.data.id}`;
						});
						
					}).catch(error => {
						swal("Oops!", "Unable to create campaign. Please make sure everything has been entered correctly.", "error", {
							button: true,
							timer: 3000
						});
					});
			}
		},
		mounted(){
			this.getCountries();
		}
	}
</script>