<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<validator name="UpdateCampaign">
		<form id="UpdateCampaignForm" class="form-horizontal" novalidate>
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
					<v-select class="form-controls" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
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
						</div>
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('end') }">
								<span class="input-group-addon">End</span>
								<input type="date" class="form-control" v-model="ended_at" id="ended_at"
									   v-validate:end="{ required: true }" required>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="published_at" class="col-sm-2 control-label">Published Date</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">Published</span>
						<input type="date" class="form-control" v-model="published_at" id="published_at">
					</div>
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': checkForError('url') }">
				<label for="description" class="col-sm-2 control-label">Page Url</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">www.missions.me/campaigns/</span>
						<input type="text" id="page_url" v-model="page_url" class="form-control"
							   v-validate:url="{ required: false,  }" />
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a href="/admin/campaigns/{{campaignId}}" class="btn btn-default btn-sm">Cancel</a>
					<a @click="update()" class="btn btn-primary btn-sm">Update</a>
					<a class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#deleteConfirmationModal"	>Delete</a>
				</div>
			</div>
		</form>

		<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Delete Campaign</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to delete this campaign?</p>
						<div class="row">
							<div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
							<div class="col-xs-6"><a @click="deleteCampaign()" class="btn btn-sm btn-block btn-primary">Yes</a></div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</validator>
</template>
<script>
	import vSelect from "vue-select";
	export default{
		name: 'campaign-edit',
		components: {vSelect},
		props: ['campaignId'],
		data(){
			return {
				countries: [],
				countryCodeObj: null,

				name: null,
				country_code: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				page_url: null,
				attemptSubmit: false,
				resource: this.$resource('campaigns{/id}')
			}
		},
		computed:{
			country_code(){
				return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
			},
		},
		methods: {
			checkForError(field){
				// if user clicked submit button while the field is invalid trigger error styles 
				return this.$UpdateCampaign[field].invalid && this.attemptSubmit;
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
						published_at: this.published_at,
						page_url: this.page_url

					}).then(function (resp) {
						$.extend(this, resp.data.data);
					}, function (error) {
						debugger;
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
				this.published_at = campaign.published_at;
				this.page_url = campaign.page_url;
				this.countryCodeObj = _.findWhere(this.countries, {name: campaign.country});
				this.country_code = this.countryCodeObj.code;
			});

		}
	}
</script> 