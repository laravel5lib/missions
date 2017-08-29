<template >
	<div>

		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<form id="UpdateCampaignForm" class="form-horizontal" novalidate>
			<div class="row">
				<div class="col-sm-12">
					<a class="pull-right" data-toggle="modal" data-target="#deleteConfirmationModal"><h6><i
							class="fa fa-trash"></i> Delete</h6></a>
				</div>
			</div>
			<div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
				<div class="col-sm-12">
					<label for="name">Name</label>
					<input type="text" class="form-control" name="name" id="name" v-model="name"
						   placeholder="Campaign Name" v-validate="'required|min:1|max:100'"
						   maxlength="100" minlength="1">
				</div>
			</div>
			<div class="form-group" v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
				<div class="col-sm-12">
					<label for="country">Country</label>
					<v-select @keydown.enter.prevent=""  class="form-control" name="country" v-validate="'required'" id="country" v-model="countryCodeObj" :options="UTILITIES.countries"
							  label="name"></v-select>
				</div>
			</div>
			<div class="form-group" v-error-handler="{ value: short_desc, handle: 'description' }">
				<div class="col-sm-12">
					<label for="description">Description</label>
					<textarea id="description" rows="2" v-model="short_desc" class="form-control"
							  name="description" maxlength="255" v-validate="'required|min:1|max:255'"
							  minlength="1"></textarea>
					<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': (errors.has('start') || errors.has('end')) }">
				<div class="col-sm-12">
					<label for="started_at">Dates</label>
					<div class="row">
						<div class="col-sm-6">
							<date-picker addon="Start" v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }" v-model="started_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="start" v-validate="'required'"></date-picker>
							<!--<input type="datetime" class="form-control hidden" v-model="started_at" id="started_at"
							       name="start" v-validate="'required'" required>-->
							<!--<div class="input-group" v-error-handler="{ value: started_at, client: 'start', server: 'started_at' }">
								<span class="input-group-addon">Start</span>
							</div>-->
							<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
						</div>
						<div class="col-sm-6">
							<date-picker addon="End" v-error-handler="{ value: ended_at, client: 'end', server: 'ended_at' }" v-model="ended_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="end" v-validate="'required'"></date-picker>
							<!--<input type="datetime" class="form-control hidden" v-model="ended_at" id="ended_at"
							       name="end" v-validate="'required'" required>-->
							<!--<div class="input-group" v-error-handler="{ value: ended_at, client: 'end', server: 'ended_at' }">
								<span class="input-group-addon">End</span>
								<date-picker v-error-handler="{ value: ended_at, client: 'end', server: 'ended_at' }" v-model="ended_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
							</div>-->
							<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<label for="published_at">Published Date</label>
					<date-picker v-model="published_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
					<!--<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default" @click="published_at = ''"><i class="fa fa-close"></i></button>
						</span>
					</div>-->
					<!--<input type="datetime" class="form-control hidden"
						   v-model="published_at|moment('YYYY-MM-DD HH:mm:ss')" id="published_at">-->
				</div>
			</div>

			<template v-if="published_at">
				<div class="form-group" v-error-handler="{ value: page_url, client: 'url', server: 'page_url' }">
					<div class="col-sm-12">
						<label for="url">Page Url</label>
						<div class="input-group">
							<span class="input-group-addon">www.missions.me/campaigns/</span>
							<input type="text" id="page_url" v-model="page_url" class="form-control"
								   name="url" v-validate="''"/>
						</div>
						<!--<div v-if="errors.page_url" class="help-block">{{errors.page_url.toString()}}</div>-->
					</div>
				</div>

				<div class="form-group" v-error-handler="{ value: page_src, client: 'src', server: 'page_src' }">
					<div class="col-sm-12">
						<label for="src">Page Source</label>
						<div class="input-group">
							<span class="input-group-addon">/resources/views/sites/campaigns/partials/</span>
							<input type="text" id="page_src" v-model="page_src" class="form-control"
								   name="src" v-validate="''"/>
							<span class="input-group-addon">.blade.php</span>
						</div>
					</div>
				</div>
			</template>

			<div class="row">
				<div class="col-sm-6">
					<h5>
						<img class="av-left img-circle img-md"
							:src="selectedAvatar.source ? (selectedAvatar.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'" width="100">
						<button class="btn btn-primary btn-sm" type="button" data-toggle="collapse"
							data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse">
							<i class="fa fa-camera icon-left"></i> Set Avatar
						</button>
					</h5>
					<div class="collapse" id="avatarCollapse">
						<div class="well">
							<upload-create-update type="avatar" lock-type is-child
												  :tags="['campaign']"  @uploads-complete="uploadsComplete"></upload-create-update>
						</div>
					</div>
				</div><!-- end col -->
				<div class="col-sm-6">
					<h5>
						<img class="av-left img-rounded img-md"
							:src="selectedBanner.source ? (selectedBanner.source + '?w=100&q=50') : '/images/placeholders/campaign-placeholder.png'" width="100">
						<button class="btn btn-primary btn-sm" type="button" data-toggle="collapse"
							data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse">
								<i class="fa fa-camera icon-left"></i> Set Banner
						</button>
					</h5>

					<div class="collapse" id="bannerCollapse">
						<div class="well">
							<upload-create-update type="banner" lock-type is-child
												  :tags="['campaign']"  @uploads-complete="uploadsComplete"></upload-create-update>
						</div>
					</div>
				</div><!-- end col -->
			</div><!-- end row -->
			<hr class="divider inv lg">

			<div class="form-group">
				<div class="col-sm-12 text-center">
					<!--<a :href="'/admin/campaigns/' + campaignId + '" class='"btn btn-default">Cancel</a>-->
					<a @click="back()" class="btn btn-default">Cancel</a>
					<a @click="update()" class="btn btn-primary">Update</a>
				</div>
			</div>
		</form>

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
								<a class="btn btn-sm btn-default" data-dismiss="modal">No</a>
								<a @click="deleteCampaign()" class="btn btn-sm btn-primary">Yes</a>
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
		watch:{
			'name'(val, oldVal) {
				if(typeof val === 'string') {
					this.page_url = this.convertToSlug(val);
				}
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
				window.location.href = '/admin/campaigns/';
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
//						window.location.href = '/admin/campaigns/'
                            this.showSuccess = true;
                            this.hasChanged = false;
                            // this.$refs.spinner.hide();
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
				// delete campaign
				// this.$refs.spinner.show();
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
			// this.$refs.spinner.show();
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
				// this.$refs.spinner.hide();
			}, (response) =>  {
                console.log(response);
                return response
            });
		}
	}

</script> 