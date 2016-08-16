<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<validator name="CreateCampaign">
		<form id="CreateCampaignForm" class="form-horizontal" novalidate>
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
							  v-validate:description="{ required: true, minlength:1, maxlength:120 }" maxlength="120"
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
								<input type="date" class="form-control" v-model="ended_at" id="ended_at" :min="started_at"
									   v-validate:end="{ required: true }" required>
							</div>
							<div v-if="errors.ended_at" class="help-block">{{errors.ended_at.toString()}}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="published_at" class="col-sm-2 control-label">Published</label>
				<div class="col-sm-10">
						<input type="datetime-local" class="form-control" v-model="published_at" id="published_at">
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': checkForError('url') }" v-if="published_at">
				<label for="description" class="col-sm-2 control-label">Page Url</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">www.missions.me/campaigns/</span>
						<input type="text" id="page_url" v-model="page_url" class="form-control"
							   v-validate:url="{ required: false }" />
					</div>
					<div v-show="errors.page_url" class="help-block">{{errors.page_url}}</div>
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': checkForError('src') }" v-if="published_at">
				<label for="description" class="col-sm-2 control-label">Page Source</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">/resources/views/sites/campaigns/partials/</span>
						<input type="text" id="page_src" v-model="page_src" class="form-control"
							   v-validate:src="{ required: false,  }" />
						<span class="input-group-addon">.blade.php</span>
					</div>
				</div>
			</div>
			
			<accordion :one-at-atime="true">
				<panel header="Avatar" :is-open.sync="avatarPanelOpen">
					<div class="media" v-if="selectedAvatar">
						<div class="media-left">
							<a href="#">
								<img class="media-object" :src="selectedAvatar.source + '?w=100&q=50'" width="100" :alt="selectedAvatar.name">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">{{selectedAvatar.name}}</h4>
						</div>
					</div>
					<upload-create-update type="avatar" :lock-type="true" :is-child="true" :tags="['campaign']"></upload-create-update>
				</panel>
				<panel header="Banner" :is-open.sync="bannerPanelOpen">
					<div class="media" v-if="selectedBanner">
						<div class="media-left">
							<a href="#">
								<img class="media-object" :src="selectedBanner.source + '?w=100&q=50'" width="100" :alt="selectedBanner.name">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">{{selectedBanner.name}}</h4>
						</div>
					</div>
					<upload-create-update type="banner" :lock-type="true" :is-child="true" :tags="['campaign']"></upload-create-update>
				</panel>
			</accordion>

			<div class="form-group">
				<div class="text-center">
					<a href="/admin/campaigns" class="btn btn-default">Cancel</a>
					<a @click="submit()" class="btn btn-primary">Create</a>
				</div>
			</div>
		</form>
	</validator>
</template>
<script>
	import vSelect from "vue-select";
	import VueStrap from 'vue-strap/dist/vue-strap.min';
	import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';

	export default{
		name: 'campaign-create',
		components: {vSelect, 'upload-create-update': adminUploadCreateUpdate, 'accordion': VueStrap.accordion, 'panel': VueStrap.panel},
		data(){
			return {
				countries: [],
				countryCodeObj: null,
				errors: [],

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
				avatarPanelOpen:false,
				bannerPanelOpen:false,
				selectedAvatar: null,
				avatar_upload_id: null,
				selectedBanner: null,
				banner_upload_id: null,
			}
		},
		computed:{
			country_code(){
				return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
			},
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
				return this.$CreateCampaign[field].invalid && this.attemptSubmit;
			},
			convertToSlug(text){
				return text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
			},
			submit(){
				this.attemptSubmit = true;
				if (this.$CreateCampaign.valid) {
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
						self.errors = error.data.errors;
					});
				}
			}
		},
		events:{
			'uploads-complete'(data){
				switch(data.type){
					case 'avatar':
						this.selectedAvatar = data;
						this.avatar_upload_id = data.id;
						this.avatarPanelOpen = false;
						break;
					case 'banner':
						this.selectedBanner = data;
						this.banner_upload_id = data.id;
						this.bannerPanelOpen = false;
						break;
				}
			}
		},
		ready(){
			this.$http.get('utilities/countries').then(function (response) {
				this.countries = response.data.countries;
			});
		}
	}
</script> 