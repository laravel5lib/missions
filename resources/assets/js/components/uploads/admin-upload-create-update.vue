<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
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
			<form id="CreateUploadForm" class="form" novalidate @submit.prevent="">
				<div class="form-group" v-error-handler="{ value: name, handle: 'name' }" v-show="!uiLocked || allowName">
					<label for="name" class="control-label">Name</label>
						<input type="text" class="form-control" name="name" id="name" v-model="name"
							   placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
							   maxlength="100" minlength="1" required>
				</div>
				<div class="form-group" v-error-handler="{ value: tags, handle: 'tags' }" v-show="!uiLocked" >
					<label for="tags" class="control-label">Tags</label>
						<v-select @keydown.enter.prevent=""  id="tags" class="form-control" multiple :value.sync="tags" :options="tagOptions"></v-select>
						<select hidden id="tags" name="tags" v-model="tags" multiple v-validate:tags="{ required:true }">
							<option v-for="tag in tagOptions" :value="tag">{{tag}}</option>
						</select>
				</div>
				<div class="form-group" v-error-handler="{ value: type, handle: 'type' }" v-show="!uiLocked" >
					<label for="type" class="control-label">Type</label>
					<select class="form-control" id="type" v-model="type" v-validate:type="{ required: true }" :disabled="lockType">
						<option :value="">-- select type --</option>
						<option value="avatar">Image (Avatar) - 1280 x 1280</option>
						<option value="banner">Image (Banner) - 1300 x 500</option>
						<option value="other">Image (other) - no set dimensions</option>
						<option value="passport">Image (Passport/Visa) - no set dimensions</option>
						<option value="video">Video</option>
						<option value="file">File</option>
					</select>
				</div>

				<div class="row" v-if="isUpdate">
					<div class="col-xs-4" v-if="type === 'avatar' || type === 'banner' || type === 'other' || type === 'passport'">
						<div class="slim" data-instant-edit="true" data-did-confirm="slimConfirmed" v-if="src">
							<img :src="src" class="">
							<input type="file" id="file" :accept="allowedTypes" v-model="fileA" @change="handleImage" class="">
						</div>
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

				<div class="form-group" v-if="type && type === 'file'" :class="{ 'has-error': isFileSet}">
					<label for="file" class="control-label">File</label>
						<input type="file" id="file" :accept="allowedTypes" v-model="fileA" @change="handleImage" class="form-control">
						<span class="help-block"><i class="fa fa-file-pdf-o"></i> PDF format only</span>
						<!--<h5>Coords: {{coords|json}}</h5>-->
				</div>


				<div class="row2" v-if="type && type !== 'file' && file && isSmall()">
					<div class="alert alert-warning" role="alert">
						The recommended dimensions are <b>{{typeObj.width}}x{{typeObj.height}}</b> for best quality. <br>
						The current size is <b>{{(coords.w / imageAspectRatio).toFixed(0)}}x{{(coords.h / imageAspectRatio).toFixed(0)}}</b>.
					</div>
				</div>

				<div class="form-group" v-if="!isUpdate && type && type !== 'video' && type !== 'file'" :class="{ 'has-error': isFileSet}">
					<label for="file" class="control-label">File</label>
					<div class="slim" data-instant-edit="true" data-did-confirm="slimConfirmed">
						<input type="file" id="file" :accept="allowedTypes" v-model="fileA" @change="handleImage" class="">
					</div>
					<span class="help-block"><i class="fa fa-image"></i> Image formats only</span>
					<!--<h5>Coords: {{coords|json}}</h5>-->
				</div>

				<!--<div class="form-group" v-if="file" v-show="type !== 'file'">-->
					<!--<label for="file" class="control-label">Crop Image</label>-->
					<!--<div id="crop-wrapper" class="col-sm-12">-->
						<!--&lt;!&ndash;<img :src="file" :width="imageWidth" :height="imageHeight" :style="'max-width:'+imageMaxWidth+'px;max-height:'+imageMaxHeight+'px;'"-->
							 <!--v-crop:create="test" v-crop:start="test" v-crop:move="test" v-crop:end="test"/>&ndash;&gt;-->
						<!--&lt;!&ndash;<hr>&ndash;&gt;-->
						<!--&lt;!&ndash;<img :src="resultImage" v-if="resultImage">&ndash;&gt;-->
					<!--</div>-->
				<!--</div>-->

				<br>

				<div class="form-group" v-if="!hideSubmit">
					<a v-if="!isChild" href="/admin/uploads" class="btn btn-default">Cancel</a>
					<a @click="submit()" v-if="!isUpdate" class="btn btn-primary">{{buttonText}}</a>
					<a @click="update()" v-if="isUpdate" class="btn btn-primary">{{buttonText}}</a>
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
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'upload-create-update',
		components: {vSelect},
        mixins: [errorHandler],
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
            allowName: {
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
            constrained: {
				type: Boolean,
				default: true
			},
            buttonText: {
                type: String,
                default: 'Save'
            },
            submitEvent: {
                type: String,
                default: 'start-upload'
            },
	        hideSubmit: {
			    type: Boolean,
		        default: false
	        }
		},
        data(){
            return {
                url: '',
                path: '',
                src: '',
                file: null,
                x_axis: null,
                y_axis: null,

				// logic variables
				previewModal: false,
				previewUpload: null,
				attemptSubmit: false,
				coords: 'Try to move/resize the selection',
				// constrained: true,
				vueCropApi: null,
                slimAPI: null,
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
					{type: 'passport', path: 'images/other', width: 300, height: 400},
					{type: 'file', path: 'resources/documents'},
				],
				typeObj: null,
				resource: this.$resource('uploads{/id}'),
				uploads: [],
				page: 1,
				search: '',
				pagination: { current_page: 1 },
                // mixin settings
                validatorHandle: 'CreateUpload',
            }
        },
		computed:{
			allowedTypes() {
				if (_.contains(['avatar', 'banner', 'other', 'passport'], this.type)) {
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
			},
            isFileSet() {
			    return  !_.isNull(this.file) && !!this.attemptSubmit
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
				this.reset();
			}
		},
        methods: {
			reset(){
				_.extend(this, {
					url: '',
					path: '',
					src: '',
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

				if (this.isUpdate) {
					this.resource.get({id: this.uploadId}).then(function (response) {
						let upload = response.body.data;
						this.name = upload.name;
						this.tags = upload.tags;
						this.type = upload.type;
						this.src = upload.source;
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
			},
			isSmall(){
				return (parseInt(this.coords.w / this.imageAspectRatio) < this.scaledWidth && parseInt(this.coords.h / this.imageAspectRatio) < this.scaledHeight);
			},
			adjustSelectByType(){
				if (this.slimAPI && _.contains(['banner', 'avatar'], this.typeObj.type)) {
					// update dimensions
					this.scaledWidth = this.typeObj.width;
					this.scaledHeight = this.typeObj.height;
					this.width = this.scaledWidth * this.imageAspectRatio;
					this.height = this.scaledHeight * this.imageAspectRatio;
					// update slim editor ratio
					this.slimAPI[0].ratio = this.typeObj.width + ':' + this.typeObj.height;
				}
			},
			adjustSelect(){
				this.width = this.scaledWidth * this.imageAspectRatio;
				this.height = this.scaledHeight * this.imageAspectRatio;

				let w = this.width;
				let h = this.height;
				// always go with the width when constrained
				h = this.constrained ?  (this.height = this.width) : this.height;

				if (!this.constrained && this.slimAPI) {
                    this.slimAPI[0].ratio = w + ':' + h;
                }
//				this.vueCropApi.setSelect([this.coords.x, this.coords.y, w, h]);
			},
            /*checkForError(field){
                // if upload clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpload[field].invalid && this.attemptSubmit;
            },*/
            submit(){
				this.resetErrors();
                if (this.$CreateUpload.valid) {
					let params;
					if (this.type === 'video') {
						params = {
							name: this.name,
							tags: this.tags,
							type: this.type,
							url: this.url
						};
					} else {
						params = {
							name: this.name,
							tags: this.tags,
							type: this.type,
                            file: (this.slimAPI ? this.slimAPI[0].data.output.image.toDataURL("image/jpeg") : false)||this.file,
							path: this.path,
							width: parseInt(this.coords.w / this.imageAspectRatio),
							height: parseInt(this.coords.h / this.imageAspectRatio),
						};

						if(this.allowName) {
						    params.name = this.name + '_' + moment().unix();
						}
					}

					if (this.type === 'passport') {
					    params.type = 'other';
					}

                    this.resource.save(null, params).then(function (resp) {
						this.handleSuccess(resp)
                    }, function (error) {
                        this.errors = error.data.errors;
                        console.log(error);
                    });
                }
            },
            update(){
				this.resetErrors();
				if (this.$CreateUpload.valid) {
                    let params = {
                        name: this.name,
                        tags: this.tags,
                        type: this.type,
                        path: this.path,
                        file: (this.slimAPI ? this.slimAPI[0].data.output.image.toDataURL("image/jpeg") : false)||this.file||undefined,
                        width: parseInt(this.coords.w / this.imageAspectRatio)||undefined,
                        height: parseInt(this.coords.h / this.imageAspectRatio)||undefined
                    };

                    if (this.type === 'passport') {
                        params.type = 'other';
                    }

                    this.resource.update({id:this.uploadId}, params).then(function (resp) {
						this.handleSuccess(resp)
					}, function (error) {
                        this.errors = error.data.errors;
                        console.log(error);
					});
				}
            },
			handleSuccess(response){
				if(this.isChild) {
					// send data to parent componant
					this.$dispatch('uploads-complete', response.body.data);

				} else {
					window.location.href = '/admin/uploads';
					// window.location.href = '/admin' + response.body.data.links[0].uri;
				}
			},
			handleImage(e){
				let self = this;
				let reader = new FileReader();
				reader.onload = function(event){
					let img = new Image();
					img.onload = function(){
						self.imageAspectRatio = Math.min(self.imageMaxWidth / img.width, self.imageMaxHeight / img.height);
						self.imageWidth = img.width * self.imageAspectRatio;
						self.imageHeight = img.height * self.imageAspectRatio;

						// adjust container
                        if (self.slimAPI) {
//                            self.vueCropApi.resizeContainer(self.imageWidth, self.imageHeight);
                            if (self.typeObj && _.contains(['banner', 'avatar'], self.typeObj.type)) {
                                self.adjustSelectByType()
                            } else {
                                this.slimAPI[0].ratio = 'free';
                            }
                        }
					};
					self.file = img.src = event.target.result
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

				this.$http.get('uploads', { params: params }).then(function (response) {
					this.uploads = response.body.data;
					this.pagination = response.body.meta.pagination;
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
			},
	        slimConfirmed(data){
			    debugger;
	        },
	        loadCropper() {
                let self = this;
                if (_.contains(['avatar', 'other', 'passport'], this.type) || (this.type === 'banner' && this.uiSelector !== 1)) {
                    setTimeout(function () {
                        self.slimAPI = new Slim.parse(self.$el);
                        if (self.typeObj && _.contains(['banner', 'avatar'], self.typeObj.type)) {
                            self.adjustSelectByType()
                        } else {
                            self.slimAPI[0].ratio = 'free';
                        }
                    }, 1000);
                }
	        },
        },
		ready(){
            let self = this;
			if (this.isUpdate) {
				this.resource.get({id: this.uploadId}).then(function (response) {
					let upload = response.body.data;
					this.name = upload.name;
					// strictly verify tags
					switch (upload.type) {
						case 'avatar':
                            // if upload.tags is not an array or if it is empty
                            this.tags = !_.isArray(upload.tags) || !upload.tags.length
	                            // check if component prop `tags` is set, if not default to User, Group, Campaing tags
	                            ? !_.isArray(this.tags) ? ['User', 'Campaign', 'Group']: this.tags
	                            // else use tags from API data
	                            : upload.tags;
                            break;
                    }
                    this.type = upload.type;
                    this.src = upload.source;

                    this.loadCropper();
				});
			} else {
                this.loadCropper();
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

            this.$root.$on(self.submitEvent, function () {
                if (self.isUpdate) {
                    self.update();
                } else {
                    self.submit();
                }
            }.bind(this));

            let slimEvents = [
                'didInit',                  // Initialized
                'didLoad',                  // Image Loaded
                'didTransform',             // Image Transformed
                'didCancel',                // Image Editor Cancelled
                'didConfirm',               // Image Editor Confirmed
                'didSave',                  // Image Saved
                'didRemove',                // Image Removed
                'didUpload',                // Image Uploaded
                'didReceiveServerError',    // Error Received from Server During Upload
            ];
        }
    }
</script> 