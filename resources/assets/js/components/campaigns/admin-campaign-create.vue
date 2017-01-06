<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<validator name="CreateCampaign">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<form id="CreateCampaignForm" class="form-horizontal" novalidate>
			<div class="form-group" :class="{ 'has-error': checkForError('name') }">
				<div class="col-sm-12">
					<label for="name">Name</label>
					<input type="text" class="form-control" name="name" id="name" v-model="name"
						   placeholder="Campaign Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
						   maxlength="100" minlength="1" required>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('country') }">
				<div class="col-sm-12">
					<label for="country">Country</label>
					<v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries"
							  label="name"></v-select>
					<select hidden name="country" id="country" class="hidden" v-model="country_code"
							v-validate:country="{ required: true }">
						<option :value="country.code" v-for="country in countries">{{country.name}}</option>
					</select>

				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('description') }">
				<div class="col-sm-12">
					<label for="description">Description</label>
					<textarea name="short_desc" id="description" rows="2" v-model="short_desc" class="form-control"
							  v-validate:description="{ required: true, minlength:1, maxlength:120 }" maxlength="120"
							  minlength="1"></textarea>
					<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': (checkForError('start') || checkForError('end')) }">
				<div class="col-sm-12">
					<label for="started_at">Dates</label>
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('start') }">
								<span class="input-group-addon">Start</span>
								<date-picker class="form-control" :time.sync="started_at|moment 'MM-DD-YYYY HH:mm:ss'"></date-picker>
								<input type="datetime" class="form-control hidden" v-model="started_at|moment 'MM-DD-YYYY HH:mm:ss'" id="started_at"
									   v-validate:start="{ required: true }" required>
							</div>
							<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('end') }">
								<span class="input-group-addon">End</span>
								<date-picker class="form-control" :time.sync="ended_at|moment 'MM-DD-YYYY HH:mm:ss'"></date-picker>
								<input type="datetime" class="form-control hidden" v-model="ended_at|moment 'MM-DD-YYYY HH:mm:ss'" id="ended_at"
									   :min="started_at"
									   v-validate:end="{ required: true }" required>
							</div>
							<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<label for="published_at">Published</label>
					<date-picker class="form-control" :time.sync="published_at|moment 'MM-DD-YYYY HH:mm:ss'"></date-picker>
					<input type="datetime" class="form-control hidden" v-model="published_at|moment 'MM-DD-YYYY HH:mm:ss'" id="published_at">
				</div>
			</div>

			<template v-if="published_at">
				<div class="form-group" :class="{ 'has-error': checkForError('url') || errors.page_url }">
					<div class="col-sm-12">
						<label for="description">Page Url</label>
						<div class="input-group">
							<span class="input-group-addon">www.missions.me/campaigns/</span>
							<input type="text" id="page_url" v-model="page_url" class="form-control"
								   v-validate:url="{ required: false }"/>
						</div>
						<div v-show="errors.page_url" class="help-block">{{errors.page_url}}</div>
					</div>
				</div>

				<div class="form-group" :class="{ 'has-error': checkForError('src') }">
					<div class="col-sm-12">
						<label for="description">Page Source</label>
						<div class="input-group">
							<span class="input-group-addon">/resources/views/sites/campaigns/partials/</span>
							<input type="text" id="page_src" v-model="page_src" class="form-control"
								   v-validate:src="{ required: false,  }"/>
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
													  :tags="['campaign']"></upload-create-update>
						</div>
					</div><!-- end collapse -->
					<div class="collapse" id="bannerCollapse">
						<div class="well">
							<upload-create-update type="banner" :lock-type="true" :is-child="true"
												  :tags="['campaign']"></upload-create-update>
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
		<alert :show.sync="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
			<span class="icon-info-circled alert-icon-float-left"></span>
			<strong>Oh No!</strong>
			<p>There are errors on the form.</p>
		</alert>

	</validator>
</template>
<script type="text/javascript">
	import vSelect from "vue-select";
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';

	export default{
		name: 'campaign-create',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate},
		data(){
			return {
				countries: [],
				countryCodeObj: null,
				errors: {},

				name: null,
				country: null,
				country_code: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				page_url: null,
				page_src: null,
				attemptSubmit: false,
				selectedAvatar: null,
				avatar_upload_id: null,
				selectedBanner: null,
				banner_upload_id: null,
				showError: false
			}
		},
		computed: {
			country_code(){
				return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
			},
		},
		watch: {
			'name': function (val) {
				if (typeof val === 'string') {
					this.page_url = this.convertToSlug(val);
				}
			}
		},
		methods: {
			checkForError(field){
				// if user clicked submit button while the field is invalid trigger error styles 
				return this.$CreateCampaign[field].invalid && this.attemptSubmit;
			},
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
			},
			submit(){
				this.attemptSubmit = true;
				if (this.$CreateCampaign.valid) {
					// this.$refs.spinner.show();
					var resource = this.$resource('campaigns');
					resource.save(null, {
						name: this.name,
						country_code: this.country_code,
						short_desc: this.short_desc,
						started_at: this.started_at,
						ended_at: this.ended_at,
						published_at: moment(this.published_at).format('YYYY-MM-DD HH:mm:ss'),
						page_url: this.page_url,
						page_src: this.page_src,
						avatar_upload_id: this.avatar_upload_id,
						banner_upload_id: this.banner_upload_id,

					}).then(function (resp) {
						window.location.href = '/admin' + resp.data.data.links[0].uri;
					}, function (error) {
						this.errors = error.data.errors;
						this.showError = true;
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
		ready(){
			// this.$refs.spinner.show();
			this.$http.get('utilities/countries').then(function (response) {
				this.countries = response.data.countries;
				// this.$refs.spinner.hide();
			});
		}
	}
</script> 