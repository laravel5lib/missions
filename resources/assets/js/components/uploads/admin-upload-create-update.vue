<template xmlns:v-validate="http://www.w3.org/1999/xhtml" xmlns:v-crop="http://www.w3.org/1999/xhtml">
	<div>
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
			<form class="form-inline text-right" novalidate>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</form>
			<br>
			<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
				<div class="col-sm-2 col-md-2" v-for="upload in uploads" style="display:flex">
					<div class="panel panel-default">

							<a @click="selectExisting(upload)" role="button">
								<tooltip effect="scale" placement="top" :content="upload.name">
									<img :src="upload.source + '?w=100&q=50'" :alt="upload.name" class="img-responsive">
								</tooltip>
							</a>

						<!--<div class="panel-body">
							<h6 class="text-uppercase">{{upload.name}}</h6>
						</div>--><!-- end panel-body -->
						<div class="panel-footer">
							<button type="button" class="btn btn-xs btn-block btn-primary" @click="selectExisting(upload)">Select</button>
						</div>
					</div><!-- end panel -->
				</div><!-- end col -->
				<div class="col-xs-12 text-center">
					<nav>
						<ul class="pagination pagination-sm">
							<li :class="{ 'disabled': pagination.current_page == 1 }">
								<a aria-label="Previous" @click="page=pagination.current_page-1">
									<span aria-hidden="true">&laquo;</span>
								</a>
							</li>
							<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
							<li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
								<a aria-label="Next" @click="page=pagination.current_page+1">
									<span aria-hidden="true">&raquo;</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<validator v-if="!isChild||uiSelector===2" name="CreateUpload">
			<form id="CreateUploadForm" class="form-horizontal" novalidate @submit="prevent">
				<div class="form-group" :class="{ 'has-error': checkForError('name') }">
					<label for="name" class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="name" id="name" v-model="name"
							   placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
							   maxlength="100" minlength="1" required>
					</div>
				</div>
				<div class="form-group" :class="{ 'has-error': checkForError('tags') }" v-show="!uiLocked">
					<label for="tags" class="col-sm-2 control-label">Tags</label>
					<div class="col-sm-10">
						<v-select id="tags" class="form-control" multiple :value.sync="tags" :options="tagOptions"></v-select>
						<select hidden id="tags" name="tags" v-model="tags" multiple v-validate:tags="{ required:true }">
							<option v-for="tag in tagOptions" :value="tag">{{tag}}</option>
						</select>
					</div>
				</div>
				<div class="form-group" :class="{ 'has-error': checkForError('type') }" v-show="!uiLocked">
					<label for="type" class="col-sm-2 control-label">Type</label>
					<div class="col-sm-10">
						<select class="form-control" id="type" v-model="type" v-validate:type="{ required: true }" :disabled="lockType">
							<option :value="">-- select type --</option>
							<option value="avatar">Image (Avatar) - 1280 x 1280</option>
							<option value="banner">Image (Banner) - 1300 x 500</option>
							<option value="other">Image (other) - no set dimensions</option>
							<option value="file">File</option>
						</select>
					</div>
				</div>

				<div class="row col-sm-offset-2" v-if="type && type === 'other'">
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="constrained">
							Lock Proportions
						</label>
					</div>
					<div class="" :class="{'col-sm-4': !constrained, 'col-sm-8': constrained}">
						<div class="input-group">
							<span class="input-group-addon" v-if="!constrained" id="basic-addon3">Width(px)</span>
							<span class="input-group-addon" v-if="constrained" id="basic-addon3">Width/Height(px)</span>
							<input type="number" number class="form-control" v-model="scaledWidth" id="height" min="100" aria-describedby="basic-addon3"
								   placeholder="300">
						</div>
						<br>
					</div>
					<div class="col-sm-4" v-if="!constrained">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Height(px)</span>
							<input type="number" number class="form-control" v-model="scaledHeight" id="width" min="100" aria-describedby="basic-addon1"
								   placeholder="300">
						</div>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-default" type="button" @click="adjustSelect">Set</button>
					</div>
				</div>

				<div class="form-group">
					<label for="file" class="col-sm-2 control-label">File</label>
					<div class="col-sm-10">
						<input type="file" id="file" v-model="fileA" @change="handleImage" class="form-control">
						<!--<h5>Coords: {{coords|json}}</h5>-->
					</div>
				</div>

				<div class="row col-sm-offset-2" v-if="type && type !== 'file' && file && isSmall()">
					<div class="alert alert-warning" role="alert">
						The recommended dimensions are <b>{{typeObj.width}}x{{typeObj.height}}</b> for best quality. <br>
						The current size is <b>{{coords.w / this.imageAspectRatio}}x{{coords.h / this.imageAspectRatio}}</b>.
					</div>
				</div>

				<div class="form-group" v-if="file" v-show="type !== 'file'">
					<label for="file" class="col-sm-2 control-label">Crop Image</label>
					<div id="crop-wrapper" class="col-sm-10">
						<img :src="file" :width="imageWidth" :height="imageHeight" :style="'max-width:'+imageMaxWidth+'px;max-height:'+imageMaxHeight+'px;'"
							 v-crop:create="test" v-crop:start="test" v-crop:move="test" v-crop:end="test"/>
						<!--<hr>-->
						<!--<img :src="resultImage" v-if="resultImage">-->
					</div>
				</div>

				<br>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<a href="/admin/uploads" class="btn btn-default">Cancel</a>
						<a @click="submit()" v-if="!isUpdate" class="btn btn-primary">Create</a>
						<a @click="update()" v-if="isUpdate" class="btn btn-primary">Update</a>
					</div>
				</div>

			</form>
		</validator>

	</div>
</template>
<script>
	import vSelect from 'vue-select'
	import VueStrap from 'vue-strap/dist/vue-strap.min'
	export default{
        name: 'upload-create-update',
		components: {vSelect, 'tooltip': VueStrap.tooltip},
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
			}
		},
        data(){
            return {
//				showRight: false,

                name: '',
//                type: null,
                path: '',
                file: null,
                x_axis: null,
                y_axis: null,
                width: 100,
				height: 100,

				// logic variables
				attemptSubmit: false,
				coords: 'Try to move/resize the selection',
				constrained: true,
				vueCropApi: null,
				scaledWidth: 600,
				scaledHeight: 600,
				imageMaxWidth: 600,
				imageMaxHeight: 600,
				imageWidth: 600,
				imageHeight: 600,
				imageAspectRatio: null,
				aspectRatio: this.width/this.height,
				fileA: null,
				resultImage: null,
				typePaths: [
					{type: 'avatar', path: 'images/avatars', width: 1280, height: 1280},
					{type: 'banner', path: 'images/banners', width: 1300, height: 500},
					{type: 'other', path: 'images/other'},
					{type: 'file', path: 'resources/documents'},
				],
				typeObj: null,
				resource: this.$resource('uploads{/id}'),
				uploads: [],
				page: 1,
				per_page: 6,
				search: '',
				pagination: {},
            }
        },
		watch:{
        	'type': function (val, oldVal) {
        		this.typeObj = _.findWhere(this.typePaths, {type: val});
				if (this.typeObj) {
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
			// Search Functionality
			'search': function (val, oldVal) {
				this.page = 1;
				this.searchUploads();
			},
			'orderByField': function (val, oldVal) {
				this.searchUploads();
			},
			'direction': function (val, oldVal) {
				this.searchUploads();
			},
			'page': function (val, oldVal) {
				this.searchUploads();
			},
			'per_page': function (val, oldVal) {
				this.searchUploads();
			},


		},
		events:{
			'vueCrop-api':function (api) {
				// make api available on scope
				window.vueCropApi = this.vueCropApi = api;
			}
		},
        methods: {
			isSmall(){
				return (parseInt(this.coords.w / this.imageAspectRatio) < this.scaledWidth && parseInt(this.coords.h / this.imageAspectRatio) < this.scaledHeight);
			},
			adjustSelectByType(){
				if (this.vueCropApi && _.contains(['banner', 'avatar'], this.typeObj.type)) {
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
                    this.resource.save(null, {
                        name: this.name,
						tags: this.tags,
						type: this.type,
                        path: this.path,
						file: this.file,
                        x_axis: parseInt(this.x_axis / this.imageAspectRatio),
                        y_axis: parseInt(this.y_axis / this.imageAspectRatio),
                        width: parseInt(this.coords.w / this.imageAspectRatio),
                        height: parseInt(this.coords.h / this.imageAspectRatio),
                    }).then(function (resp) {
						console.log(resp);
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
						console.log(resp);
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
						if (self.typeObj && _.contains(['banner', 'avatar'], self.typeObj.type) ) {
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
				var params = {
					include: '',
					search: this.search,
					per_page: this.per_page,
					page: this.page,
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

        }
    }
</script> 