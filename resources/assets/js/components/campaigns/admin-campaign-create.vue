<template>
	<div class="panel panel-default">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>

		<div class="panel-heading">
			<h3>Create Campaign</h3>
		</div>

		<form id="CreateCampaignForm" class="form-horizontal" novalidate>

			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4">
						<h5>Campaign Details</h5>
						<p class="text-muted">These details appear to the end-user in the <a href="/campaigns" target="_blank">campaign cards</a>.</p>
					</div>
					<div class="col-sm-8">
						<div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
							<div class="col-xs-12">
								<label for="name">Name</label>
								<input type="text"
									   class="form-control"
									   name="name"
									   id="name"
									   v-model="name"
									   debounce="250"
									   placeholder="Campaign Name"
									   v-validate="'required|min:1|max:100'"
									   maxlength="100"
									   minlength="1"
									   required>
							</div>
						</div>
						<div class="form-group" v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="country">Country</label>
								<v-select @keydown.enter.prevent=""
										  class="form-control"
										  name="country"
										  id="country"
										  v-model="countryCodeObj"
										  :options="UTILITIES.countries"
										  label="name"
										  v-validate="'required'">
								</v-select>
							</div>
						</div>
						<div class="form-group" v-error-handler="{ value: short_desc, handle: 'description' }">
							<div class="col-xs-12">
								<label for="description">Description</label>
								<textarea id="description"
										  rows="5"
										  v-model="short_desc"
										  class="form-control"
										  name="description"
										  v-validate="'required|min:1|max:120'"
										  maxlength="225"
										  minlength="1">
								</textarea>
                        		<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<h5>
									<img class="av-left img-md"
									     :src="selectedAvatar ? (selectedAvatar.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'"
									     width="100"
									     :alt="selectedAvatar ? selectedAvatar.name : ''">
									<button class="btn btn-primary-hollow btn-sm"
											type="button"
											data-toggle="collapse"
									        data-target="#avatarCollapse"
									        aria-expanded="false"
									        aria-controls="avatarCollapse">
										<i class="fa fa-camera icon-left"></i> Set Thumbnail
									</button>
								</h5>
								<div class="collapse" id="avatarCollapse">
								    <div class="well">
								        <upload-create-update type="avatar"
								        					  lock-type is-child
								                              :tags="['campaign']"
								                              @uploads-complete="uploadsComplete">
								    	</upload-create-update>
								    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr class="divider">
				<div class="row">
					<div class="col-sm-4">
						<h5>Campaign Dates</h5>
						<p class="text-muted">The start and end of the campaign and when it should be publicly visable. <br />These dates are visible to the end-user.</p>
					</div>
					<div class="col-sm-8">
						<div class="form-group" :class="{ 'has-error': errors.has('start') }">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="started_at">Start</label>
								<date-picker v-model="started_at"
											 :view-format="['MM-DD-YYYY HH:mm:ss']"
											 v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }" name="start"
											 v-validate="'required'">
								</date-picker>
								<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
							</div>
						</div>
						<div class="form-group" :class="{ 'has-error': errors.has('end') }">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="started_at">End</label>
								<date-picker v-model="ended_at"
											 :view-format="['MM-DD-YYYY HH:mm:ss']"
											 v-error-handler="{ value: ended_at, client: 'end', server: 'ended_at' }"
											 name="end"
											 v-validate="'required'">
								</date-picker>
								<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12 col-md-8 col-lg-6">
								<label for="published_at">Published</label>
								<date-picker v-model="published_at" :view-format="['MM-DD-YYYY HH:mm:ss']"></date-picker>
								<span class="help-block">Publish now, in the future, or leave blank to save it as a "draft".</span>
							</div>
						</div>
					</div>
				</div>
				<hr class="divider" v-if="published_at">
				<div class="row" v-if="published_at">
					<div class="col-sm-4">
						<h5>Campaign Page</h5>
						<p class="text-muted">The public-facing campaign landing page.</p>
					</div>
					<div class="col-sm-8">
						<div class="form-group" v-error-handler="{ value: page_url, client: 'url', server: 'page_url' }">
							<div class="col-sm-12">
								<label for="page_url">Page Url</label>
								<div class="input-group">
									<span class="input-group-addon">www.missions.me/campaigns/</span>
									<input type="text"
										   id="page_url"
										   v-model="page_url"
										   class="form-control"
										   name="url"
										   v-validate="''"/>
								</div>
							</div>
						</div>
						<div class="form-group" v-error-handler="{ value: page_src, client: 'src', server: 'page_src' }">
							<div class="col-sm-12">
								<label for="page_src">Page Source</label>
								<div class="input-group">
									<input type="text"
										   id="page_src"
										   v-model="page_src"
										   class="form-control"
										   name="src"
										   v-validate="''"/>
									<span class="input-group-addon">.blade.php</span>
								</div>
								<span class="help-block">The filename located at:
									<code>/resources/views/sites/campaigns/partials/</code>
								</span>
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
						<a @click.prevent="submit" class="btn btn-primary">Create</a>
					</div>
				</div>
			</div>

		</form>

	</div><!-- end panel-->
</template>
<script type="text/javascript">
	import $ from 'jquery';
    import _ from 'underscore';
	import vSelect from "vue-select";
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    import utilities from'../utilities.mixin';
    import errorHandler from'../error-handler.mixin';

	export default{
		name: 'campaign-create',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate},
        mixins: [utilities, errorHandler],
        data(){
			return {
				countryCodeObj: null,
				name: null,
				country: null,
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
                resource: this.$resource('campaigns')
            }
		},
		computed: {
			country_code: {
			    get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                },
				set() {},
			},
		},
		watch: {
			'name'(val, oldVal) {
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
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.resource.post({}, {
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

                        }).then((resp) => {
                            window.location.href = '/admin' + resp.data.data.links[0].uri;
                        }, (error) => {
                            this.errors = error.data.errors;
                            this.showError = true;
                        });
                    } else {
                        this.showError = true;
                    }
                });
			},
            uploadsComplete(data) {
		        switch (data.type) {
		            case 'avatar':
		                this.selectedAvatar = data;
		                this.avatar_upload_id = data.id;
		                $('#avatarCollapse').collapse('hide');
		                break;
		            case 'banner':
		                this.selectedBanner = data;
		                this.banner_upload_id = data.id;
		                $('#bannerCollapse').collapse('hide');
		                break;
		        }
		    }
		},
		mounted(){
			this.getCountries();
		}
	}
</script> 