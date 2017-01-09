<template xmlns:v-validate="http://www.w3.org/1999/xhtml" xmlns:v-crop="http://www.w3.org/1999/xhtml">
	<div style="position:relative">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<form class="form-inline" v-if="isChild && !uiLocked" novalidate>
			<div class="row">
				<div class="col-sm-offset-2 col-sm-10">
					<label class="radio-inline">
						<input type="radio" name="uiSelector" id="uiSelector1" v-model="uiSelector" :value="1"> Select file
					</label>
					<label class="radio-inline">
						<input type="radio" name="uiSelector" id="uiSelector2" v-model="uiSelector" :value="2"> Upload file
					</label>
				</div>
			</div>
			<hr v-if="uiSelector!==0">
		</form>
		<div v-if="isChild && uiSelector==1">
			<!--<form class="form-inline text-right" novalidate>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</form>-->
			<br>
			<div class="" style="display:flex; flex-wrap: wrap; flex-direction: row;">
				<div class="" :class="columnCLasses" v-for="upload in uploads" style="display:flex;flex: 1">
					<div class="panel panel-default">

							<a @click="preview(upload)" role="button">
								<tooltip effect="scale" placement="top" content="Preview">
									<img :src="upload.source + '?w=100&h=100&fit=crop-center&q=50'" :alt="upload.name" class="img-responsive">
								</tooltip>
							</a>
						<div class="panel-footer">
							<button type="button" class="btn btn-xs btn-block btn-primary" @click="selectExisting(upload)">Select</button>
						</div>
					</div><!-- end panel -->
				</div><!-- end col -->
				<div class="col-xs-12 text-center">
					<pagination :pagination.sync="pagination" :callback="searchUploads"></pagination>
				</div>
			</div>
		</div>
		<validator v-if="!isChild||uiSelector===2" name="CreateUpload">
			<form id="CreateUploadForm" class="form" novalidate @submit="prevent">
				<div class="form-group" :class="{ 'has-error': checkForError('name') }" v-show="!uiLocked">
					<label for="name" class="control-label">Name</label>
						<input type="text" class="form-control" name="name" id="name" v-model="name"
							   placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
							   maxlength="100" minlength="1" required>
				</div>
				<div class="form-group" :class="{ 'has-error': checkForError('tags') }" v-show="!uiLocked" >
					<label for="tags" class="control-label">Tags</label>
						<v-select id="tags" class="form-control" multiple :value.sync="tags" :options="tagOptions"></v-select>
						<select hidden id="tags" name="tags" v-model="tags" multiple v-validate:tags="{ required:true }">
							<option v-for="tag in tagOptions" :value="tag">{{tag}}</option>
						</select>
				</div>
				<div class="form-group" :class="{ 'has-error': checkForError('type') }" v-show="!uiLocked" >
					<label for="type" class="control-label">Type</label>
					<select class="form-control" id="type" v-model="type" v-validate:type="{ required: true }" :disabled="lockType">
						<option :value="">-- select type --</option>
						<option value="avatar">Image (Avatar) - 1280 x 1280</option>
						<option value="banner">Image (Banner) - 1300 x 500</option>
						<option value="other">Image (other) - no set dimensions</option>
						<option value="video">Video</option>
						<option value="file">File</option>
					</select>
				</div>

				<div class="row" v-if="type && type === 'other' && !uiLocked">
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="constrained">
							Lock Proportions (px)
						</label>
					</div>
					<div class="" :class="{'col-sm-4': !constrained, 'col-sm-8': constrained}">
						<div class="input-group">
							<span class="input-group-addon" v-if="!constrained" id="basic-addon3">Width</span>
							<span class="input-group-addon" v-if="constrained" id="basic-addon3">Width/Height</span>
							<input type="number" number class="form-control" v-model="scaledWidth" id="height" min="100" aria-describedby="basic-addon3"
								   placeholder="300">
						</div>
						<br>
					</div>
					<div class="col-sm-4" v-if="!constrained">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Height</span>
							<input type="number" number class="form-control" v-model="scaledHeight" id="width" min="100" aria-describedby="basic-addon1"
								   placeholder="300">
						</div>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-default" type="button" @click="adjustSelect">Set</button>
					</div>
				</div>

				<div class="row" v-if="type && type === 'video'">
					<div class="col-xs-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-play-circle"></i></span>
							<input type="url" class="form-control" v-model="url" placeholder="https://vimeo.com/168118606">
						</div>
						<span class="help-block">Copy &amp; Paste a YouTube or Vimeo URL.</span>
					</div>
				</div>

				<div class="form-group" v-if="type && type !== 'video'">
					<label for="file" class="control-label">File</label>
						<input type="file" id="file" :accept="allowedTypes" v-model="fileA" @change="handleImage" class="form-control">
						<!--<h5>Coords: {{coords|json}}</h5>-->
				</div>

				<div class="row2" v-if="type && type !== 'file' && file && isSmall()">
					<div class="alert alert-warning" role="alert">
						The recommended dimensions are <b>{{typeObj.width}}x{{typeObj.height}}</b> for best quality. <br>
						The current size is <b>{{coords.w / this.imageAspectRatio}}x{{coords.h / this.imageAspectRatio}}</b>.
					</div>
				</div>

				<div class="form-group" v-if="file" v-show="type !== 'file'">
					<label for="file" class="control-label">Crop Image</label>
					<div id="crop-wrapper" class="col-sm-12">
						<img :src="file" :width="imageWidth" :height="imageHeight" :style="'max-width:'+imageMaxWidth+'px;max-height:'+imageMaxHeight+'px;'"
							 v-crop:create="test" v-crop:start="test" v-crop:move="test" v-crop:end="test"/>
						<!--<hr>-->
						<!--<img :src="resultImage" v-if="resultImage">-->
					</div>
				</div>

				<br>

				<div class="form-group">
						<a v-if="!isChild" href="/admin/uploads" class="btn btn-default">Cancel</a>
						<a @click="submit()" v-if="!isUpdate" class="btn btn-primary">Save</a>
						<a @click="update()" v-if="isUpdate" class="btn btn-primary">Save</a>
				</div>

			</form>
		</validator>

		<modal title="Preview" :show.sync="previewModal" effect="zoom" width="400" ok-text="Select" :callback="selectExistingPreview">
			<div slot="modal-body" class="modal-body" v-if="previewUpload">
				<h5 class="text-center">{{previewUpload.name}}</h5>
				<img :src="previewUpload.source + '?w=720&fit=max&q=65'" :alt="previewUpload.name" class="img-responsive">
			</div>
		</modal>

	</div>
</template>
<script type="text/javascript">
	import vSelect from 'vue-select'
	export default{
        name: 'upload-create-update',
		components: {vSelect},
		props:{
			uploadId: {
				type: String,
				default: null
			},
			type: {
				type: String,
				default: null
			},
			tagOptions: {
				type: Array,
				default() { return ['Campaign', 'User', 'Group', 'Fundraiser'] }
			},
			tags: {
				type: Array,
				default() { return []}
			},
			lockType: {
				type: Boolean,
				default: false
			},
			isUpdate: {
				type: Boolean,
				default: false
			},
			isChild: {
				type: Boolean,
				default: false
			},
			uiSelector: {
				type: Number,
				default: 0
			},
			uiLocked: {
				type: Boolean,
				default: false
			},
			name: {
				type: String,
				default: ''
			},
			perPage: {
				type: Number,
				default: 6
			},
			width: {
				type: Number,
				default: 100
			},
			height: {
				type: Number,
				default: 100
			},
		},
        data(){
            return {
                url: '',
                path: '',
                file: null,
                x_axis: null,
                y_axis: null,

				// logic variables
				previewModal: false,
				previewUpload: null,
				attemptSubmit: false,
				coords: 'Try to move/resize the selection',
				constrained: true,
				vueCropApi: null,
				scaledWidth: 400,
				scaledHeight: 400,
				imageMaxWidth: 400,
				imageMaxHeight: 400,
				imageWidth: 400,
				imageHeight: 400,
				imageAspectRatio: null,
				aspectRatio: this.width/this.height,
				fileA: null,
				resultImage: null,
				typePaths: [
					{type: 'avatar', path: 'images/avatars', width: 1280, height: 1280},
					{type: 'banner', path: 'images/banners', width: 1300, height: 500},
					{type: 'video'},
					{type: 'other', path: 'images/other', width: this.width, height: this.height},
					{type: 'file', path: 'resources/documents'},
				],
				typeObj: null,
				resource: this.$resource('uploads{/id}'),
				uploads: [],
				page: 1,
				search: '',
				pagination: { current_page: 1 },
            }
        },
		computed:{
			allowedTypes() {
				if (_.contains(['avatar', 'banner', 'other'], this.type)) {
					return 'image/bmp, image/jpg, image/jpeg, image/png, image/gif';
				}

				if (this.type === 'file') {
					return 'application/pdf';
				}
			},
			columnClasses() {
			    if (this.isChild && this.perPage !== 6) {
			        let col = 12 / this.perPage;
			        return 'col-sm-' + col.toString() + ' col-md-' + col.toString()
				} else {
			        return 'col-sm-2 col-md-2';
				}
			}
		},
		watch:{
        	'type': function (val, oldVal) {
        		this.typeObj = _.findWhere(this.typePaths, {type: val});
				if (this.typeObj && val !== 'video') {
					this.path = this.typeObj.path;
					if (this.file)
						this.adjustSelectByType();
				}
			},
			'tags': function (val) {
				this.$validate('tags', true);
			},
			// Toggle ui states
			'uiSelector': function (val) {
				if (val === 1) {
					this.searchUploads();
				}
			},
			// Pagination Functionality
			'orderByField': function (val, oldVal) {
				this.searchUploads();
			},
			'direction': function (val, oldVal) {
				this.searchUploads();
			},
			'page': function (val, oldVal) {
				this.searchUploads();
			},
			'perPage': function (val, oldVal) {
				this.searchUploads();
			},
		},
		events:{
			'vueCrop-api':function (api) {
				// make api available on scope
				window.vueCropApi = this.vueCropApi = api;
			},
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
				this.reset();
			}
		},
        methods: {
			reset(){
				_.extend(this, {
					url: '',
					path: '',
					file: null,
					x_axis: null,
					y_axis: null,
					width: 100,
					height: 100,

					// logic variables
					previewModal: false,
					previewUpload: null,
					attemptSubmit: false,
					constrained: true,
					scaledWidth: 400,
					scaledHeight: 400,
					imageMaxWidth: 400,
					imageMaxHeight: 400,
					imageWidth: 400,
					imageHeight: 400,
					imageAspectRatio: null,
					aspectRatio: this.width/this.height,
					fileA: null,
					resultImage: null,
					page: 1,
					search: '',
				});
			},
			isSmall(){
				return (parseInt(this.coords.w / this.imageAspectRatio) < this.scaledWidth && parseInt(this.coords.h / this.imageAspectRatio) < this.scaledHeight);
			},
			adjustSelectByType(){
				if (this.vueCropApi && _.contains(['banner', 'avatar', 'other'], this.typeObj.type)) {
					// update dimensions
					this.scaledWidth = this.typeObj.width;
					this.scaledHeight = this.typeObj.height;
					this.width = this.scaledWidth * this.imageAspectRatio;
					this.height = this.scaledHeight * this.imageAspectRatio;
					// update jCrop
					this.vueCropApi.setOptions({aspectRatio: (this.typeObj.width/this.typeObj.height)});
					this.vueCropApi.setSelect([0, 0, this.width, this.height]);
				}
			},
			adjustSelect(){
				this.width = this.scaledWidth * this.imageAspectRatio;
				this.height = this.scaledHeight * this.imageAspectRatio;

				var w = this.width;
				var h = this.height;
				// always go with the width when constrained
				h = this.constrained ?  (this.height = this.width) : this.height;

				if (!this.constrained) {
					this.vueCropApi.setOptions({aspectRatio: (w/h)});
				}
				this.vueCropApi.setSelect([this.coords.x, this.coords.y, w, h]);
			},
			prevent(e){
				e.preventDefault();
			},
            checkForError(field){
                // if upload clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpload[field].invalid && this.attemptSubmit;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpload.valid) {
					let params = {
						name: this.name,
						tags: this.tags,
						type: this.type,
					};

					if (this.type === 'video') {
						params.url = this.url;
					} else {
						params.file = this.file;
						params.path = this.path;
						params.x_axis = parseInt(this.x_axis / this.imageAspectRatio);
						params.y_axis = parseInt(this.y_axis / this.imageAspectRatio);
						params.width = parseInt(this.coords.w / this.imageAspectRatio);
						params.height = parseInt(this.coords.h / this.imageAspectRatio);
					}

                    this.resource.save(null, params).then(function (resp) {
						this.handleSuccess(resp)
                    }, function (error) {
                        console.log(error);
                    });
                }
            },
            update(){
				this.attemptSubmit = true;
				if (this.$CreateUpload.valid) {
					this.resource.update({id:this.uploadId}, {
						name: this.name,
						tags: this.tags,
						type: this.type,
						path: this.path,
						file: this.file||undefined,
						x_axis: parseInt(this.x_axis / this.imageAspectRatio)||undefined,
						y_axis: parseInt(this.y_axis / this.imageAspectRatio)||undefined,
						width: parseInt(this.coords.w / this.imageAspectRatio)||undefined,
						height: parseInt(this.coords.h / this.imageAspectRatio)||undefined,
					}).then(function (resp) {
						this.handleSuccess(resp)
					}, function (error) {
						console.log(error);
					});
				}
            },
			handleSuccess(response){
				if(this.isChild) {
					// send data to parent componant
					this.$dispatch('uploads-complete', response.data.data);

				} else {
					window.location.href = '/admin/uploads';
					// window.location.href = '/admin' + response.data.data.links[0].uri;
				}
			},
			handleImage(e){
				var self = this;
				var reader = new FileReader();
				reader.onload = function(event){
					var img = new Image();
					img.onload = function(){
						self.imageAspectRatio = Math.min(self.imageMaxWidth / img.width, self.imageMaxHeight / img.height);
						self.imageWidth = img.width * self.imageAspectRatio;
						self.imageHeight = img.height * self.imageAspectRatio;

						// adjust container
						self.vueCropApi.resizeContainer(self.imageWidth, self.imageHeight);
						if (self.typeObj && _.contains(['banner', 'avatar', 'other'], self.typeObj.type) ) {
							self.adjustSelectByType()
						} else {
							self.vueCropApi.setSelect([(self.imageWidth / 2) - 50, (self.imageHeight / 2) - 50, self.width * self.imageAspectRatio, self.height * self.imageAspectRatio]);
						}
					};
					self.file = img.src = event.target.result;
				};
				reader.readAsDataURL(e.target.files[0]);
			},
			test: function(event, selection, coordinates) {
				this.coords = coordinates;
				if(coordinates) {
					this.x_axis = coordinates.x;
					this.y_axis = coordinates.y;
					this.width = coordinates.w;
					this.height = coordinates.h;
				}
			},
			searchUploads(){
				let params = {
					include: '',
					per_page: this.perPage,
					page: this.pagination.current_page,
					sort: this.orderByField + '|' + (this.direction?'asc':'desc'),
					type: this.type,
					tags: this.tags
				};

				this.$http.get('uploads', params).then(function (response) {
					this.uploads = response.data.data;
					this.pagination = response.data.meta.pagination;
				})
			},
			selectExisting(upload){
				// Assumes this is a child component
				this.$dispatch('uploads-complete', upload);
			},
			selectExistingPreview(){
				// Assumes this is a child component
				this.$dispatch('uploads-complete', this.previewUpload);
			},
			preview(upload){
			    this.previewModal = true;
			    this.previewUpload = upload;
			}
        },
		ready(){
			if (this.isUpdate) {
				this.resource.get({id: this.uploadId}).then(function (response) {
					var upload = response.data.data;
					this.name = upload.name;
					this.tags = upload.tags;
					this.type = upload.type;
				});
			}

			if(this.isChild){
				this.typeObj = _.findWhere(this.typePaths, {type: this.type});
				if (this.typeObj) {
					this.path = this.typeObj.path;
					if (this.file)
						this.adjustSelectByType();
				}
			}

			this.searchUploads();

        }
    }
</script> 