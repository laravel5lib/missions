<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<validator name="UpdateCampaign">
		<form id="UpdateCampaignForm" class="form-horizontal" novalidate>
			<div class="row">
				<div class="col-sm-12">
					<a class="pull-right" data-toggle="modal" data-target="#deleteConfirmationModal"><h6><i class="fa fa-trash"></i> Delete</h6></a>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('name') }">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" id="name" v-model="name"
						   placeholder="Campaign Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
						   maxlength="100" minlength="1" required>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('country') }">
				<label for="country" class="col-sm-2 control-label">Country</label>
				<div class="col-sm-10">
					<v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
					<select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }">
						<option :value="country.code" v-for="country in countries">{{country.name}}</option>
					</select>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('description') }">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<textarea name="short_desc" id="description" rows="2" v-model="short_desc" class="form-control"
							  v-validate:description="{ required: true, minlength:1, maxlength:255 }" maxlength="255"
							  minlength="1"></textarea>
					<div v-if="short_desc" class="help-block">{{short_desc.length}}/255 characters remaining</div>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': (checkForError('start') || checkForError('end')) }">
				<label for="started_at" class="col-sm-2 control-label">Dates</label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('start') }">
								<span class="input-group-addon">Start</span>
								<input type="date" class="form-control" v-model="started_at" id="started_at"
									   v-validate:start="{ required: true }" required>
							</div>
							<div v-if="errors.started_at" class="help-block">{{errors.started_at.toString()}}</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('end') }">
								<span class="input-group-addon">End</span>
								<input type="date" class="form-control" v-model="ended_at" id="ended_at"
									   v-validate:end="{ required: true }" required>
							</div>
							<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="published_at" class="col-sm-2 control-label">Published Date</label>
				<div class="col-sm-10">
					<input type="datetime-local" class="form-control" v-model="published_at" id="published_at">
				</div>

			</div>

			<template v-if="published_at">
				<div class="form-group" :class="{ 'has-error': checkForError('url') || errors.page_url }">
					<label for="description" class="col-sm-2 control-label">Page Url</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">www.missions.me/campaigns/</span>
							<input type="text" id="page_url" v-model="page_url" class="form-control"
								   v-validate:url="{ required: false }" />
						</div>
						<div v-if="errors.page_url" class="help-block">{{errors.page_url.toString()}}</div>
					</div>
				</div>

				<div class="form-group" :class="{ 'has-error': checkForError('src') }">
					<label for="description" class="col-sm-2 control-label">Page Source</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">/resources/views/sites/campaigns/partials/</span>
							<input type="text" id="page_src" v-model="page_src" class="form-control"
								   v-validate:src="{ required: false }" />
							<span class="input-group-addon">.blade.php</span>
						</div>
					</div>
				</div>
			</template>

			<div class="media">
				<div class="media-left">
					<a href="#">
						<img class="media-object" :src="selectedAvatar ? (selectedAvatar.source + '?w=100&q=50') : ''" width="100" :alt="selectedAvatar ? selectedAvatar.name : ''">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading">{{selectedAvatar ? selectedAvatar.name : ''}}</h4>
					<button class="btn btn-block btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse">
						Set Avatar
					</button>
				</div>
			</div>
			<div class="collapse" id="avatarCollapse">
				<div class="well">
					<upload-create-update type="avatar" :lock-type="true" :is-child="true" :tags="['campaign']"></upload-create-update>
				</div>
			</div>

			<br>

			<div class="media">
				<div class="media-left">
					<a href="#">
						<img class="media-object" :src="selectedBanner ? (selectedBanner.source + '?w=100&q=50') : ''" width="100" :alt="selectedBanner ? selectedBanner.name : ''">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading">{{selectedBanner ? selectedBanner.name : ''}}</h4>
					<button class="btn btn-block btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse">
						Set Banner
					</button>
				</div>
			</div>

			<div class="collapse" id="bannerCollapse">
				<div class="well">
					<upload-create-update type="banner" :lock-type="true" :is-child="true" :tags="['campaign']"></upload-create-update>
				</div>
			</div>

			<br>

			<div class="form-group">
				<div class="col-sm-12 text-center">
					<a href="/admin/campaigns/{{campaignId}}" class="btn btn-default">Cancel</a>
					<a @click="update()" class="btn btn-primary">Update</a>
				</div>
			</div>
		</form>

		<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center" id="myModalLabel">Are You Sure?</h4>
					</div>
					<div class="modal-body">
						<p class="text-center">Are you sure you want to delete this campaign?</p>
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

	</validator>
</template>
<script>
	import vSelect from "vue-select";
	import VueStrap from 'vue-strap/dist/vue-strap.min';
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
	export default{
		name: 'campaign-edit',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate, 'accordion': VueStrap.accordion, 'panel': VueStrap.panel},
		props: ['campaignId'],
		data(){
			return {
				countries: [],
				countryCodeObj: null,
				errors: [],

				name: null,
				country_code: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				published_at_date: null,
				published_at_time: null,
				page_url: null,
				page_src: null,
				attemptSubmit: false,
				selectedAvatar: null,
				avatar_upload_id: null,
				selectedBanner: null,
				banner_upload_id: null,
				resource: this.$resource('campaigns{/id}')
			}
		},
		computed:{
			country_code(){
				return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
			}
		},
		watch:{
			'name': function (val) {
				if(typeof val === 'string') {
					this.page_url = this.convertToSlug(val);
				}
			}
		},
		methods: {
			checkForError(field){
				// if user clicked submit button while the field is invalid trigger error styles 
				return this.$UpdateCampaign[field].invalid && this.attemptSubmit;
			},
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
			},
			update(){
				// Touch fields for proper validation
				if ( _.isFunction(this.$validate) )
					this.$validate(true);

				this.attemptSubmit = true;
				if (this.$UpdateCampaign.valid) {
					this.resource.update({id: this.campaignId}, {
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
						resp.data.data.published_at = moment(resp.data.data.published_at).format('YYYY-MM-DDTHH:mm:ss.SSS')
						$.extend(this, resp.data.data);
						window.location.href = '/admin/campaigns/'
					}, function (error) {
						self.errors = error.data.errors;
					});
				}
			},
			deleteCampaign(){
				// delete campaign
				this.resource.delete({id: this.campaignId}).then(function(response) {
					window.location.href = '/admin/campaigns/'
				});
			}
		},
		events:{
			'uploads-complete'(data){
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
			this.$http.get('utilities/countries').then(function (response) {
				this.countries = response.data.countries;
			});

			// get campaign data
			this.resource.get({id: this.campaignId}).then(function(response) {
				var campaign = response.data.data;
				this.name = campaign.name;
				this.short_desc = campaign.description;
				this.started_at = campaign.started_at;
				this.ended_at = campaign.ended_at;
				this.published_at = moment(campaign.published_at).format('YYYY-MM-DDTHH:mm:ss.SSS');
				this.page_url = campaign.page_url;
				this.page_src = campaign.page_src;
				this.countryCodeObj = _.findWhere(this.countries, { name: campaign.country });
				this.country_code = this.countryCodeObj.code;
			});

		}
	}
</script> 