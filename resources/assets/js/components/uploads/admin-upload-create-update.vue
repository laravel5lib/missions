<template>
	<div style="position:relative">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<!--<form class="form-inline" v-if="isChild && !selectorLocked" novalidate>
			<div class="row">
				<div class="col-sm-offset-2 col-sm-10">
					<label class="radio-inline">
						<input type="radio" name="selectedUI" id="selectedUI1" v-model="selectedUI" :value="1">
						Select file
					</label>
					<label class="radio-inline">
						<input type="radio" name="selectedUI" id="selectedUI2" v-model="selectedUI" :value="2">
						Upload file
					</label>
				</div>
			</div>
			<hr v-if="selectedUI!==0">
		</form>-->
		<div v-if="isChild && selectedUI==1">
			<!--<form class="form-inline text-right" novalidate>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</form>-->
			<br>
			<div class="" style="display:flex; flex-wrap: wrap; flex-direction: row;">
				<div class="" :class="columnClasses" v-for="upload in uploads" style="display:flex;flex: 1">
					<div class="panel panel-default">
						<a @click="preview(upload)" role="button">
							<tooltip effect="scale" placement="top" content="Preview">
								<img :src="upload.source + '?w=100&h=100&fit=crop-center&q=50'" :alt="upload.name"
								     class="img-responsive">
							</tooltip>
						</a>
						<div class="panel-footer">
							<button type="button" class="btn btn-xs btn-block btn-primary"
							        @click="selectExisting(upload)">Select
							</button>
						</div>
					</div><!-- end panel -->
				</div><!-- end col -->
				<div class="col-xs-12 text-center">
					<pagination :pagination="pagination" pagination-key="pagination"
					            :callback="searchUploads"></pagination>
				</div>
			</div>
		</div>

		<form id="CreateUploadForm" class="form" novalidate @submit.prevent="isUpdate ? update() : submit()">
			<div class="form-group" v-error-handler="{ value: currentName, handle: 'name' }"
			     v-show="!uiLocked || allowName">
				<label for="name" class="control-label">Name</label>
				<input type="text" class="form-control" name="name" id="name" v-model="currentName"
				       placeholder="Name" v-validate="'required|max:100'"
				       maxlength="100" minlength="1" required>
			</div>
			<div class="form-group" v-error-handler="{ value: tags, handle: 'tags' }" v-show="!uiLocked">
				<label for="tags" class="control-label">Tags</label>
				<v-select @keydown.enter.prevent="" id="tags" name="tags" v-validate="'required'" class="form-control"
				          multiple v-model="tags" :options="tagOptions"></v-select>

			</div>
			<div class="form-group" v-error-handler="{ value: type, handle: 'type' }" v-show="!uiLocked">
				<label for="type" class="control-label">Type</label>
				<select class="form-control" id="type" v-model="type" name="type" v-validate="'required'"
				        :disabled="lockType">
					<option value="">-- select type --</option>
					<option value="avatar">Image (Avatar) - 1:1</option>
					<option value="banner">Image (Banner) - 16:9</option>
					<option value="other">Image (other) - no set dimensions</option>
					<option value="passport">Image (Passport/Visa) - no set dimensions</option>
					<option value="video">Video</option>
					<option value="file">File</option>
				</select>
			</div>

			<template v-if="type">
				<div class="row" v-if="containedIn(['video'], type)">
					<div class="col-xs-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-play-circle"></i></span>

							<input type="url" class="form-control" v-model="uploadData.source"
							       placeholder="https://vimeo.com/168118606">

							<span class="input-group-btn">
								<button type="submit" class="btn btn-primary">{{buttonText || 'Attach'}}</button>
							</span>
						</div>
						<span class="help-block">Copy &amp; Paste a YouTube or Vimeo URL.</span>
					</div>
				</div>

				<div class="form-group" v-else-if="containedIn(['file'], type)">
					<label for="file" class="control-label">File</label>
					<input type="file" id="file" :accept="allowedTypes" :value="fileA" @change="handleImage"
					       class="form-control">
					<span class="help-block"><i class="fa fa-file-pdf-o"></i> PDF format only</span>
				</div>

				<div class="form-group" v-else>
					<slim-cropper v-if="loadedCropper" :options="slimOptions"/>
					<div v-if="uploads.length" class="text-center">
						<hr class="divider sm inv">
						<p class="text-center">
							<em class="text-muted">Or</em>
						</p>
						<hr class="divider sm inv">
						<!-- <a class="btn btn-sm btn-default-hollow" @click="previewModal = true">Choose an Image
						</a> -->
					</div>
				</div>
			</template>

			<template v-if="!hideSubmit">
				<br>

				<div class="form-group">
					<a v-if="!isChild" href="/admin/uploads" class="btn btn-default">Cancel</a>
					<button type="submit" class="btn btn-primary">{{buttonText}}</button>
				</div>
			</template>

		</form>

		<!--<modal title="Preview" :value="previewModal" @closed="previewModal=false" effect="zoom" width="400"
		       ok-text="Select" :callback="selectExistingPreview">
			<div slot="modal-body" class="modal-body" v-if="previewUpload">
				<h5 class="text-center">{{previewUpload.name}}</h5>
				<img :src="previewUpload.source + '?w=720&fit=max&q=65'" :alt="previewUpload.name"
				     class="img-responsive">
			</div>
		</modal>-->

		<modal title="Select an Image" :value="previewModal" @closed="previewModal=false" effect="zoom"
		       width="400" ok-text="Select" :callback="selectExistingPreview">
			<div slot="modal-body" class="modal-body">
				<div class="flex-container" style="justify-content: space-around;">
					<div class="" v-for="upload in uploads" style="width:100px;height:155px;">
						<div class="panel panel-default">
							<a @click="previewUpload = upload" role="button" data-toggle="tooltip"
							   data-placement="top" title="Preview">
								<!--<tooltip effect="scale" placement="top" content="Preview">-->
								<img :src="upload.source + '?w=100&h=100&fit=crop-center&q=50'"
								     :alt="upload.name" class="img-responsive">
								<!--</tooltip>-->
							</a>
							<div class="panel-footer">
								<button type="button" class="btn btn-xs btn-block btn-primary"
								        @click="selectExisting(upload)">Select
								</button>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-xs-12 text-center">
						<pagination :pagination="pagination" pagination-key="pagination"
						            :callback="searchUploads"></pagination>
					</div>
				</div>
				<hr class="divider">
				<template v-if="previewUpload">
					<h5 class="text-center">{{previewUpload.name}}</h5>
					<img :src="previewUpload.source + '?w=720&fit=max&q=65'" :alt="previewUpload.name"
					     class="img-responsive" style="margin: auto;">
				</template>
				<template v-else>
					<p class="well text-center text-muted">
						<em>Select an image to Preview</em>
					</p>
				</template>
			</div>
		</modal>

	</div>
</template>
<script type="text/javascript">
  import Slim from '../../vendors/slim.vue';
  import jQuery from 'jquery';
  import _ from 'underscore';
  import vSelect from 'vue-select'
  import errorHandler from '../error-handler.mixin';

  export default {
    name: 'upload-create-update',
    components: {'slim-cropper': Slim, vSelect},
    mixins: [errorHandler],
    // TODO: component needs to be redesigned to accept an object of properties matching the current properties.
    // TODO: This will eliminate the anti-pattern of changing props inside the component.
    props: {
      options: {
        type: Object,
        default() {
          return {
            type: null,
            tags: [],
          }
        }
      },
      uploadId: {
        type: String,
        default: null
      },
      initialImage: {
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
      selectorLocked: {
        type: Boolean,
        default: false
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
      },
      staticUploads: {
        type: [Array, null],
        default: null,
      },
    },
    data() {
      return {
        path: '',
        src: '',
        file: null,
        x_axis: null,
        y_axis: null,

        loadedCropper: false,
        slimOptions: {
          ratio: `free`,
          push: true,
          statusFileType: 'image/bmp, image/jpg, image/jpeg, image/png, image/gif',
          instantEdit: false,
          service: this.slimService,
          didInit: this.slimInit,
          didLoad: this.slimLoad,
          didRemove: this.slimRemove
        },

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

        currentName: null,
        currentWidth: null,
        currentHeight: null,

        aspectRatio: this.width / this.height,
        fileA: null,
        resultImage: null,
        typePaths: [
          {type: 'avatar', path: 'images/avatars', ratio: '1:1'},
          {type: 'banner', path: 'images/banners', ratio: '16:9'},
          {type: 'video'},
          {type: 'other', path: 'images/other', ratio: 'free'},
          {type: 'passport', path: 'images/other', width: 300, height: 400, ratio: 'free'},
          {type: 'file', path: 'resources/documents'},
        ],
        typeObj: null,
        uploads: [],
        page: 1,
        search: '',
        pagination: {current_page: 1},
        uploadData: {
          name: null,
          source: null

        },

        selectedUI: this.uiSelector
      }
    },
    computed: {
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
    },
    watch: {
      uploadId(val, oldVal) {
        if (val !== oldVal) {
          this.getUpload();
        }
      },
      type(val, oldVal) {
        this.typeObj = _.findWhere(this.typePaths, {type: val});
        if (this.typeObj && val !== 'video') {
          this.path = this.typeObj.path;
          if (this.file)
            this.adjustSelectByType();
        }
      },
      tags(val) {
        //
      },
      // Toggle ui states
      selectedUI(val, oldVal) {
        if (val === 1) {
          this.searchUploads();
        }
        if (oldVal === 0 && val === 2) {
          this.loadCropper();
        }
      },
      // Pagination Functionality
      orderByField(val, oldVal) {
        this.searchUploads();
      },
      direction(val, oldVal) {
        this.searchUploads();
      },
      page(val, oldVal) {
        this.searchUploads();
      },
      perPage(val, oldVal) {
        this.searchUploads();
      }
    },
    methods: {
      // Slim Methods
      slimInit(data, slim) {
        // slim instance reference
        // console.log(slim)

        // current slim data object and slim reference
        // console.log(data)

        if (this.isUpdate)
          slim.load(this.src, { blockPush:true });
        else {
          if (this.initialImage)
            slim.load(this.initialImage, { blockPush:true });
        }

        this.slimAPI = slim;
      },
      slimService(formdata, progress, success, failure, slim) {
        // slim instance reference
        // console.log(slim)

        // form data to post to server
        // log(formdata)

        // call these methods to handle upload state
        // console.log(progress, success, failure)

        let promise;
        this.$root.$emit('Uploader:BUSY');
        if (this.isUpdate) {
          promise = this.update();
        } else {
          promise = this.submit();
        }
        progress(2, 10);

        promise.then(result => {
          this.$root.$emit('Uploader:DONE');
          progress(10, 10);
          success();
        }).catch(result => {
          this.$root.$emit('Uploader:DONE');
          failure();
        })
      },
      slimLoad(file, image, meta) {
        switch (this.type) {
          case 'avatar':
            this.slimAPI.setRatio('1:1');
            break;
          case 'banner':
            this.slimAPI.setRatio('16:9');
            break;
          case 'other':
            this.slimAPI.setRatio('free');
            break;
        }
        return true;
      },
      slimRemove(data, slim) {

      },

      containedIn(arr, item) {
        return _.contains(arr, item);
      },
      reset() {
        _.extend(this, {
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
          aspectRatio: this.width / this.height,
          fileA: null,
          resultImage: null,
          page: 1,
          search: '',
        });

        if (this.isUpdate) {
          this.$http.get(`uploads/${this.uploadId}`).then((response) => {
            let upload = response.data.data;
            this.currentName = upload.name;
            this.tags = upload.tags;
            this.type = upload.type;
            this.src = upload.source;
          });
        }

        if (this.isChild) {
          this.typeObj = _.findWhere(this.typePaths, {type: this.type});
          if (this.typeObj) {
            this.path = this.typeObj.path;
            if (this.file)
              this.adjustSelectByType();
          }
        }
      },
      isSmall() {
        return (parseInt(this.coords.w / this.imageAspectRatio) < this.scaledWidth && parseInt(this.coords.h / this.imageAspectRatio) < this.scaledHeight);
      },
      adjustSelectByType() {
        if (this.slimAPI && _.contains(['banner', 'avatar'], this.typeObj.type)) {
          // update dimensions
          this.scaledWidth = this.typeObj.width;
          this.scaledHeight = this.typeObj.height;
          this.currentWidth = this.scaledWidth * this.imageAspectRatio;
          this.currentHeight = this.scaledHeight * this.imageAspectRatio;
          // update slim editor ratio
          if (this.slimAPI)
            this.slimAPI.ratio = `${this.typeObj.width}:${this.typeObj.height}`;
        }
      },
      adjustSelect() {
        this.currentWidth = this.scaledWidth * this.imageAspectRatio;
        this.currentHeight = this.scaledHeight * this.imageAspectRatio;

        let w = this.currentWidth;
        let h = this.currentHeight;
        // always go with the width when constrained
        h = this.constrained ? (this.currentHeight = this.currentWidth) : this.currentHeight;

        if (!this.constrained && this.slimAPI) {
          this.slimAPI.ratio = `${w}:${h}`;
        }
        // this.vueCropApi.setSelect([this.coords.x, this.coords.y, w, h]);
      },
      submit() {
        return this.$validator.validateAll().then(result => {
          if (!result) {
            this.$root.$emit('showError', 'Please check form.');
            return false;
          }

          let params;
          if (this.type === 'video') {
            params = {
              name: this.currentName,
              tags: this.tags,
              type: this.type,
              url: this.uploadData.source
            };
          } else {
            params = {
              name: this.name,
              tags: this.tags,
              type: this.type,
              file: (this.slimAPI ? this.slimAPI.dataBase64.output.image : false) || this.file,
              path: this.path,
              width: parseInt(this.coords.w / this.imageAspectRatio),
              height: parseInt(this.coords.h / this.imageAspectRatio),
            };

            if (this.allowName) {
              params.name = this.name + '_' + moment().unix();
            }
          }

          if (this.type === 'passport') {
            params.type = 'other';
          }

          return this.$http.post(`uploads`, params).then((resp) => {
            return this.handleSuccess(resp);
          }, (error) => {
            this.SERVER_ERRORS = error.data.errors;
            console.log(error);
          });
        })
      },
      update() {

        return this.$validator.validateAll().then(result => {
          if (!result) {
            this.$root.$emit('showError', 'Please check form.');
            return false;
          }

          let params = {
            name: this.name,
            tags: this.tags,
            type: this.type,
            path: this.path,
            file: (this.slimAPI ? this.slimAPI.dataBase64.output.image : false) || this.file || undefined,
            width: parseInt(this.coords.w / this.imageAspectRatio) || undefined,
            height: parseInt(this.coords.h / this.imageAspectRatio) || undefined
          };

          if (this.type === 'passport') {
            params.type = 'other';
          }

          return this.$http.put(`uploads/${this.uploadId}`, params).then((resp) => {
            return this.handleSuccess(resp)
          }, (error) => {
            this.SERVER_ERRORS = error.data.errors;
            console.log(error);
          });
        });
      },
      handleSuccess(response) {
        if (this.isChild) {
          // send data to parent componant
          this.$emit('uploads-complete', response.data.data);
          this.uploadsComplete(response.data.data);

        } else {
          window.location.href = '/admin/uploads';
          // window.location.href = '/admin' + response.data.data.links[0].uri;
        }
        return response.data.data;
      },
      handleImage(e) {
        let self = this;
        let reader = new FileReader();
        reader.onload = function (event) {
          let img = new Image();
          img.onload = function () {
            self.imageAspectRatio = Math.min(self.imageMaxWidth / img.width, self.imageMaxHeight / img.height);
            self.imageWidth = img.width * self.imageAspectRatio;
            self.imageHeight = img.height * self.imageAspectRatio;

            // adjust container
            if (self.slimAPI) {
//                            self.vueCropApi.resizeContainer(self.imageWidth, self.imageHeight);
              if (self.typeObj && _.contains(['banner', 'avatar'], self.typeObj.type)) {
                self.adjustSelectByType()
              } else {
                self.slimAPI.ratio = 'free';
              }
            }
          };
          self.file = img.src = event.target.result
        };
        reader.readAsDataURL(e.target.files[0]);
      },
      searchUploads() {
        let params = {
          include: '',
          per_page: this.perPage,
          page: this.pagination.current_page,
          sort: this.orderByField + '|' + (this.direction ? 'asc' : 'desc'),
          type: this.type,
          tags: this.tags
        };

        return this.$http.get('uploads', {params: params}).then((response) => {
          this.uploads = response.data.data;
          this.pagination = response.data.meta.pagination;
          return this.uploads;
        }).catch(this.$root.handleApiError);
      },
      handleStaticUpload(upload) {

      },
      selectExisting(upload) {
          // Assumes this is a child component
        if (this.staticUploads) {
          this.slimAPI.load(upload.source, (error, data) => {

          });
          this.previewModal = false;
        } else {
          if (this.slimAPI)
            this.slimAPI.load(upload.source, {blockPush: true});
          this.$emit('uploads-complete', upload);
          this.uploadsComplete(upload);
        }
      },
      selectExistingPreview() {
        // Assumes this is a child component
        if (this.staticUploads) {
          this.slimAPI.load(this.previewUpload.source, (error, data) => {

          });
          this.previewModal = false;
        } else {
          if (this.slimAPI)
            this.slimAPI.load(this.previewUpload.source, {blockPush: true});
          this.$emit('uploads-complete', this.previewUpload);
          this.uploadsComplete(this.previewUpload);
        }
      },
      preview(upload) {
        this.previewModal = true;
        this.previewUpload = upload;
      },
      loadCropper() {
        let self = this;
        if (_.contains(['avatar', 'banner', 'other', 'passport'], this.type) && this.selectedUI === 2) {
          if (self.typeObj && _.contains(['banner', 'avatar'], self.typeObj.type)) {
            switch (this.type) {
              case 'avatar':
                this.slimOptions.ratio = '1:1';
                break;
              case 'banner':
                this.slimOptions.ratio = '16:9';
                break;

            }
          } else {
            this.slimOptions.ratio = 'free';
          }
          this.loadedCropper = true;
        }
      },
      uploadsComplete(data) {
        switch (data.type) {
          case 'avatar':
            this.selectedAvatar = data;
            this.avatar_upload_id = data.id;
            if (_.isFunction(jQuery.fn.collapse))
              jQuery('#avatarCollapse').collapse('hide');
            break;
          case 'banner':
            this.selectedBanner = data;
            this.banner_upload_id = data.id;
            if (_.isFunction(jQuery.fn.collapse))
              jQuery('#bannerCollapse').collapse('hide');
            break;
          default:
            break;
        }
        this.reset();
      },
      getUpload() {
        this.$http.get(`uploads/${this.uploadId}`).then((response) => {
          let upload = response.data.data;
          this.currentName = upload.name;
          // strictly verify tags
          switch (upload.type) {
            case 'avatar':
              // if upload.tags is not an array or if it is empty
              this.tags = !_.isArray(upload.tags) || !upload.tags.length
                // check if component prop `tags` is set, if not default to User, Group, Campaing tags
                ? !_.isArray(this.tags) ? ['User', 'Campaign', 'Group'] : this.tags
                // else use tags from API data
                : upload.tags;
              break;
            case 'banner':
              this.tags = !_.isArray(upload.tags) || !upload.tags.length
                // check if component prop `tags` is set, if not default to User, Group, Campaing tags
                ? !_.isArray(this.tags) ? ['User', 'Campaign', 'Group'] : this.tags
                // else use tags from API data
                : upload.tags;
              break;
          }
          this.type = upload.type;
          this.src = upload.source;
          this.uploadData = upload;

          this.loadCropper();
        }, (response) => {
          console.log(response);
          return response
        });

      }
    },
    mounted() {
      this.selectedUI = this.uiSelector;
      this.currentName = this.name;
      this.currentWidth = this.width;
      this.currentHeight = this.height;

      let self = this;
      if (this.isUpdate) {
        this.getUpload();
      } else {
        this.loadCropper();
      }

      if (this.isChild) {
        this.typeObj = _.findWhere(this.typePaths, {type: this.type});
        if (this.typeObj) {
          this.path = this.typeObj.path;
          //if (this.file)
          //this.adjustSelectByType();
        }
      }

      if (this.staticUploads) {
        this.uploads = this.staticUploads;
      } else {
        this.searchUploads();
      }

      this.$root.$on(self.submitEvent, () => {
        if (self.isUpdate) {
          self.put();
        } else {
          self.submit();
        }
      });
    }
  }
</script> 