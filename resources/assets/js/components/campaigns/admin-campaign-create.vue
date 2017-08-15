<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div>

			<spinner ref="spinner" size="sm" text="Loading"></spinner>
			<form id="CreateCampaignForm" class="form-horizontal" novalidate>
				<div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
					<div class="col-sm-12">
						<label for="name">Name</label>
						<input type="text" class="form-control" name="name" id="name" v-model="name" debounce="250"
						       placeholder="Campaign Name" name="name" v-validate="'required|min:1|max:100'"
						       maxlength="100" minlength="1" required>
					</div>
				</div>
				<div class="form-group" v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
					<div class="col-sm-12">
						<label for="country">Country</label>
						<v-select @keydown.enter.prevent=""  class="form-control" id="country" :value="countryCodeObj" :options="countries"
						          label="name"></v-select>
						<select hidden name="country" id="country" class="hidden" v-model="country_code"
						        name="country" v-validate="'required'">
							<option :value="country.code" v-for="country in countries">{{country.name}}</option>
						</select>

					</div>
				</div>
				<div class="form-group" v-error-handler="{ value: description, handle: 'description' }">
					<div class="col-sm-12">
						<label for="description">Description</label>
						<textarea name="short_desc" id="description" rows="2" v-model="short_desc" class="form-control"
						          name="description="'required|min:1|max:120'" maxlength" v-validate="120"
						          minlength="1"></textarea>
						<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
					</div>
				</div>

				<div class="form-group" :class="{ 'has-error': (errors.has('start') || errors.has('end')) }">
					<div class="col-sm-12">
						<label for="started_at">Dates</label>
						<div class="row">
							<div class="col-sm-6">
								<date-picker addon="Start" :model="started_at|moment('MM-DD-YYYY HH:mm:ss')" v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }"></date-picker>
								<input type="datetime" class="form-control hidden" v-model="started_at|moment('MM-DD-YYYY HH:mm:ss')" id="started_at"
								       name="start" v-validate="'required'" required>
								<!--<div class="input-group" v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }">
									<span class="input-group-addon">Start</span>
									<date-picker class="form-control" :model="started_at|moment('MM-DD-YYYY HH:mm:ss')"></date-picker>
								</div>-->
								<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
							</div>
							<div class="col-sm-6">
								<date-picker :model="ended_at|moment('MM-DD-YYYY HH:mm:ss')" addon="End" v-error-handler="{ value: ended_at, client: 'end', server: 'ednded_at' }"></date-picker>
								<input type="datetime" class="form-control hidden" v-model="ended_at|moment('MM-DD-YYYY HH:mm:ss')" id="ended_at"
								       :min="started_at"
								       name="end" v-validate="'required'" required>
								<!--<div class="input-group" v-error-handler="{ value: ended_at, client: 'end', server: 'ednded_at' }">
															<span class="input-group-addon">End</span>
								</div>-->
								<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<label for="published_at">Published</label>
						<date-picker :model="published_at|moment('MM-DD-YYYY HH:mm:ss')"></date-picker>
						<!--<div class="input-group">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" @click="published_at = ''"><i class="fa fa-close"></i></button>
							</span>
						</div>-->
						<input type="datetime" class="form-control hidden" v-model="published_at|moment('MM-DD-YYYY HH:mm:ss')" id="published_at">
					</div>
				</div>

				<template v-if="published_at">
					<div class="form-group" v-error-handler="{ value: page_url, client: 'url', server: 'page_url' }">
						<div class="col-sm-12">
							<label for="description">Page Url</label>
							<div class="input-group">
								<span class="input-group-addon">www.missions.me/campaigns/</span>
								<input type="text" id="page_url" v-model="page_url" class="form-control"
								       name="url" v-validate="{ required: false }"/>
							</div>
							<!--<div v-show="errors.page_url" class="help-block">{{errors.page_url}}</div>-->
						</div>
					</div>

					<div class="form-group" v-error-handler="{ value: page_src, client: 'src', server: 'page_src' }"r33>
						<div class="col-sm-12">
							<label for="description">Page Source</label>
							<div class="input-group">
								<span class="input-group-addon">/resources/views/sites/campaigns/partials/</span>
								<input type="text" id="page_src" v-model="page_src" class="form-control"
								       name="src" v-validate="{ required: false,  }"/>
								<span class="input-group-addon">.blade.php</span>
							</div>
						</div>
					</div>
				</template>
				<div class="row">
					<div class="col-sm-6">
						<h5>
							<img class="av-left img-circle img-md"
							     :src="selectedAvatar ? (selectedAvatar.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'" width="100"
							     :alt="selectedAvatar ? selectedAvatar.name : ''">
							<button class="btn btn-primary btn-sm" type="button" data-toggle="collapse"
							        data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse">
								<i class="fa fa-camera icon-left"></i> Set Avatar
							</button>
						</h5>
					</div><!-- end col -->
					<div class="col-sm-6">
						<h5>
							<img class="av-left img-rounded img-md"
							     :src="selectedBanner ? (selectedBanner.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'" width="100"
							     :alt="selectedBanner ? selectedBanner.name : ''">
							<button class="btn btn-primary btn-sm" type="button" data-toggle="collapse"
							        data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse">
								<i class="fa fa-camera icon-left"></i> Set Banner
							</button>
						</h5>
					</div><!-- end col -->
					<div class="col-xs-12">
						<div class="collapse" id="avatarCollapse">
							<div class="well">
								<upload-create-update type="avatar" :lock-type="true" :is-child="true"
								                      :tags="['Campaign']"></upload-create-update>
							</div>
						</div><!-- end collapse -->
						<div class="collapse" id="bannerCollapse">
							<div class="well">
								<upload-create-update type="banner" :lock-type="true" :is-child="true"
								                      :tags="['Campaign']"></upload-create-update>
							</div>
						</div><!-- end collapse -->
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv lg">
				<div class="form-group">
					<div class="text-center">
						<a href="/admin/campaigns" class="btn btn-default">Cancel</a>
						<a @click="submit()" class="btn btn-primary">Create</a>
					</div>
				</div>
			</form>
			<alert :show="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
				<span class="icon-info-circled alert-icon-float-left"></span>
				<strong>Oh No!</strong>
				<p>There are errors on the form.</p>
			</alert>


	</div>

</template>
<script type="text/javascript">
	import jQuery from 'jquery';
    import _ from 'underscore';
	import vSelect from "vue-select";
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    import errorHandler from'../error-handler.mixin';

	export default{
		name: 'campaign-create',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate},
        mixins: [errorHandler],
        data(){
			return {
				countries: [],
				countryCodeObj: null,
				name: null,
				country: null,
				country_code: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				page_url: null,
				page_src: null,
				selectedAvatar: null,
				avatar_upload_id: null,
				selectedBanner: null,
				banner_upload_id: null,
				showError: false,
                // mixin settings
                validatorHandle: 'CreateCampaign',
            }
		},
		computed: {
			country_code(){
				return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
			},
		},
		watch: {
			'name': (val) =>  {
				if (typeof val === 'string') {
					// pre-populate slug
					this.$http.get('utilities/make-slug/' + val, { params: { hideLoader: true } })
						.then((response) => {
							this.page_url = response.data.slug;
						});
				}
			}
		},
		methods: {
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
			},
			submit(){
				this.resetErrors();
				if (this.$CreateCampaign.valid) {
					// this.$refs.spinner.show();
					var resource = this.$resource('campaigns');
					resource.post(null, {
						name: this.name,
						country_code: this.country_code,
						short_desc: this.short_desc,
						started_at: moment(this.started_at).isValid() ? moment(this.started_at).format('YYYY-MM-DD HH:mm:ss') : null,
						ended_at: moment(this.ended_at).isValid() ? moment(this.ended_at).format('YYYY-MM-DD HH:mm:ss') : null,
						published_at: moment(this.published_at).isValid() ? moment(this.published_at).format('YYYY-MM-DD HH:mm:ss') : null,
						page_url: this.page_url,
						page_src: this.page_src,
						avatar_upload_id: this.avatar_upload_id,
						banner_upload_id: this.banner_upload_id,

					}).then((resp) => {
						window.location.href = '/admin' + resp.data.data.links[0].uri;
					}, (error) =>  {
						this.errors = error.data.errors;
						this.showError = true;
						// TODO use global alert
						// this.$refs.spinner.hide();
					});
				} else {
					this.showError = true;
				}
			}
		},
		events: {
			'uploads-complete'(data){
				switch (data.type) {
					case 'avatar':
						this.selectedAvatar = data;
						this.avatar_upload_id = data.id;
						jQuery('#avatarCollapse').collapse('hide');
						break;
					case 'banner':
						this.selectedBanner = data;
						this.banner_upload_id = data.id;
						jQuery('#bannerCollapse').collapse('hide');
						break;
				}
			}
		},
		mounted(){
			// this.$refs.spinner.show();
			this.$http.get('utilities/countries').then((response) => {
				this.countries = response.data.countries;
				// this.$refs.spinner.hide();
			}, (response) =>  {
                console.log(response);
            });
		}
	}
</script> 