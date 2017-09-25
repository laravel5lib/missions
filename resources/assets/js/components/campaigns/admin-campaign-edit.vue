<template>
	<div class="panel panel-default">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>

		<div class="panel-heading">
			<h3>Edit Campaign</h3>
		</div>

		<form id="UpdateCampaignForm" class="form-horizontal" novalidate>

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
								   placeholder="Campaign Name"
								   v-validate="'required|min:1|max:100'"
								   maxlength="100"
								   minlength="1">
					    </div>
					</div>
					<div class="form-group" v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
						<div class="col-sm-12 col-md-8 col-lg-6">
							<label for="country">Country</label>
                    		<v-select @keydown.enter.prevent=""
                    				  class="form-control"
                    				  name="country"
                    				  v-validate="'required'"
                    				  id="country"
                    				  v-model="countryCodeObj"
                    				  :options="UTILITIES.countries"
                    				  label="name">
                    		</v-select>
						</div>
					</div>
					<div class="form-group" v-error-handler="{ value: short_desc, handle: 'description' }">
						<div class="col-xs-12">
							<label for="description">Description</label>
							<textarea id="description"
									  rows="2"
									  v-model="short_desc"
									  class="form-control"
									  name="description"
									  maxlength="255"
									  v-validate="'required|min:1|max:255'"
									  minlength="1">
							</textarea>
                    		<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<h5>
								<img class="av-left img-md"
									:src="selectedAvatar.source ? (selectedAvatar.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'"
									width="100">
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
														  lock-type
														  is-child
														  :tags="['campaign']"
														  @uploads-complete="uploadsComplete">
									</upload-create-update>
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
							<date-picker v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }"
										 v-model="started_at"
										 :view-format="['YYYY-MM-DD HH:mm:ss']"
										 name="start"
										 v-validate="'required'">
							</date-picker>
							<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
						</div>
					</div>
					<div class="form-group" :class="{ 'has-error': errors.has('end') }">
						<div class="col-sm-12 col-md-8 col-lg-6">
							<label for="started_at">End</label>
							<date-picker v-error-handler="{ value: ended_at, client: 'end', server: 'ended_at' }"
										 v-model="ended_at"
										 :view-format="['YYYY-MM-DD HH:mm:ss']"
										 name="end"
										 v-validate="'required'">
						    </date-picker>
							<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-md-8 col-lg-6">
							<label for="published_at">Published</label>
							<date-picker v-model="published_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
						    <span class="help-block">Publish now, in the future, or leave blank to save it as a "draft".</span>
						</div>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row" v-if="published_at">
				<div class="col-sm-4">
					<h5>Campaign Page</h5>
					<p class="text-muted">The public-facing campaign landing page.</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" v-error-handler="{ value: page_url, client: 'url', server: 'page_url' }">
						<div class="col-sm-12">
							<label for="description">Page Url</label>
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
							<label for="description">Page Source</label>
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

		</div>
		<div class="panel-footer">
			<div class="form-group">
				<hr class="divider inv md">
				<div class="col-xs-4">
					<button @click.prevent="" class="btn btn-link"
					   data-toggle="modal"
					   data-target="#deleteConfirmationModal">
				   		<i class="fa fa-trash"></i> Delete
			   		</button>
				</div>
				<div class="col-xs-8 text-right">
					<a @click.prevent="back()" class="btn btn-default">Go Back</a>
					<a @click.prevent="update()" class="btn btn-primary">Save Changes</a>
				</div>
			</div>
		</div>
		</form>
		</validator>

		<alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
			<span class="icon-ok-circled alert-icon-float-left"></span>
			<strong>Well Done!</strong>
			<p>Profile updated</p>
		</alert>
		<alert v-model="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
			<span class="icon-ok-circled alert-icon-float-left"></span>
			<strong>Oh No!</strong>
			<p>There are errors on the form.</p>
		</alert>

		<modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel"
			   :callback="forceBack">
			<div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
		</modal>

		<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
			 aria-labelledby="deleteConfirmationModal">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center" id="myModalLabel">Delete Campaign</h4>
					</div>
					<div class="modal-body">
						<p class="text-center">Delete this campaign?</p>
						<div class="row">
							<div class="col-sm-12 text-center">
								<a class="btn btn-link" data-dismiss="modal">Keep</a>
								<a @click="deleteCampaign()" class="btn btn-primary">Delete</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from "vue-select";
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    import utilities from'../utilities.mixin';
    import errorHandler from'../error-handler.mixin';
	export default{
		name: 'campaign-edit',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate},
        mixins: [utilities, errorHandler],
		props: ['campaignId'],
		data(){
			return {
				countryCodeObj: null,
				name: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				published_at_date: null,
				published_at_time: null,
				page_url: null,
				page_src: null,
				selectedAvatar: { source: null },
				avatar_upload_id: null,
				selectedBanner: { source: null },
				banner_upload_id: null,
				resource: this.$resource('campaigns{/id}'),
				showSuccess: false,
				showError: false,
				showSaveAlert: false,
				hasChanged: false,
            }
		},
		computed:{
			country_code:{
			    get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                },
				set() {}
			}
		},
		methods: {
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
			},
			back(force){
				if (this.isFormDirty && !force ) {
					this.showSaveAlert = true;
					return false;
				}
				window.location.href = '/admin/campaigns/' + this.campaignId;
			},
			forceBack(){
				return this.back(true);
			},
			update(){
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.resource.put({id: this.campaignId}, {
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
                            resp.data.data.published_at = moment(resp.data.data.published_at).format('YYYY-MM-DDTHH:mm:ss.SSS');
                            $.extend(this, resp.data.data);
                            this.showSuccess = true;
                            this.hasChanged = false;
                        }, (error) => {
                            this.errors = error.data.errors;
                            this.showError = true;
                            console.log(response);
                            return error
                        });
                    } else {
                        this.showError = true;
                        return
                    }
                });
			},
			deleteCampaign(){
				this.resource.delete({id: this.campaignId}).then((response) => {
					window.location.href = '/admin/campaigns/'
				}, (response) =>  {
                    console.log(response);
                    return response
                });
			},
            uploadsComplete(data){
                switch(data.type){
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
		created(){
			this.getCountries();

			// get campaign data
			this.resource.get({id: this.campaignId}).then((response) => {
				let campaign = response.data.data;
				this.name = campaign.name;
				this.short_desc = campaign.description;
				this.started_at = campaign.started_at;
				this.ended_at = campaign.ended_at;
				this.published_at = campaign.published_at;
				this.page_url = campaign.page_url;
				this.page_src = campaign.page_src;
				this.countryCodeObj = _.findWhere(this.UTILITIES.countries, { name: campaign.country });
				this.country_code = this.countryCodeObj.code;
				this.avatar_upload_id = campaign.avatar_upload_id;
				this.banner_upload_id = campaign.banner_upload_id;
				this.selectedAvatar.source = campaign.avatar;
				this.selectedBanner.source = campaign.banner;
			}, (response) =>  {
                console.log(response);
                return response
            });
		}
	}

</script> 