<template>
	<div>
		<template v-if="previewMode">
			<img class="img-responsive" style="margin: auto;"
			     :src="localContent && localContent.source ? `${localContent.source}?w=650` : 'http://lorempixel.com/800/350/people/Example%20image'"
			     alt="">
			<hr class="sm divider inv">
			<p class="text-center text-muted"><em>{{ localCaption }}</em></p>
		</template>
		<template v-else>
			<div class="row bg-gray">
				<div class="col-xs-8">
					<label>Photo</label>
					<span class="help-block">A great photo can increase donations.</span>
				</div>
				<div class="col-xs-4 text-right">
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-link dropdown-toggle" data-toggle="dropdown"
						        aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a v-if="!first" @click="moveUp">Move Up</a></li>
							<li v-if="!last"><a @click="moveDown">Move Down</a></li>
							<li role="separator" class="divider"></li>
							<li><a @click="remove">Remove Image</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="row bg-hollow">
				<div class="col-sm-8 col-sm-offset-2 text-center">
					<slim-cropper :options="slimOptions"/>
					<div v-if="imageUploads.length">
						<hr class="divider sm inv">
						<p class="text-center">
							<em class="text-muted">Or</em>
						</p>
						<hr class="divider sm inv">
						<button class="btn btn-sm btn-default-hollow" @click="previewModal = true">Choose an Image
						</button>
					</div>
					<div class="form-group">
						<hr class="divider inv">
						<label>Caption (optional)</label>
						<input type="text"
						       class="form-control"
						       placeholder="figure A1"
						       v-model="caption">
					</div>

				</div>

				<modal title="Select an Image" :value="previewModal" @closed="previewModal=false" effect="zoom"
				       width="400" ok-text="Select" :callback="selectExistingPreview">
					<div slot="modal-body" class="modal-body">
						<div class="flex-container" style="justify-content: space-around;">
							<div class="" v-for="upload in imageUploads" style="width:100px;height:155px;">
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

	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore';
  import Slim from '../../../vendors/slim.vue';

  export default {
    name: 'image',
    components: {'slim-cropper': Slim},
    props: {
      id: { type: String, default: _.uniqueId('image_') },
      previewMode: {
        type: Boolean,
        required: true
      },
      content: {
        type: [Object, Array, null],
        required: true
      },
      caption: {
        //type: String,
        required: true
      },
      drag: {
        type: Boolean,
        required: false
      },
      last: {
        type: Boolean,
        required: false
      },
      first: {
        type: Boolean,
        required: false
      },
    },
    data() {
      return {
        localContent: null,
        localCaption: null,
        localUpload: null,

        // slim vars
        slimOptions: {
          ratio: 'free',
          push: true,
          // initialImage: _.isObject(this.content) && this.content.hasOwnProperty('source') ? this.content.source : undefined,
          statusFileType: 'image/bmp, image/jpg, image/jpeg, image/png, image/gif',
          service: this.slimService,
          didInit: this.slimInit,
          didRemove: this.slimRemove
        },
        slimAPI: null,

        // select vars
        previewModal: false,
        previewUpload: null,
        page: 1,
        search: '',
        pagination: {current_page: 1},
      }
    },
    watch: {
      localContent(val, oldVal) {
        this.$emit('update:content', val)
      },
      localCaption(val, oldVal) {
        this.$emit('update:caption', val)
      },
      previewMode(val) {
        if (!val) {
          this.$nextTick(function () {

          });
        }

      },
    },
    computed: {
      imageUploads() {
        return _.where(this.$parent.uploads, {type: 'other'});
      }
    },
    methods: {
      // Move Component Methods
      moveUp() {
        this.$emit('move-up')
      },
      moveDown() {
        this.$emit('move-down');
      },
      remove() {
        this.$emit('remove');
      },

      // Slim Functions
      slimInit(data, slim) {
        // slim instance reference
        console.log(slim);

        // current slim data object and slim reference
        console.log(data);

        if (_.isObject(this.content) && this.content.hasOwnProperty('source'))
          slim.load(this.content.source, { blockPush:true });

        this.slimAPI = slim;
      },
      slimRemove(data, slim) {
        if (this.localContent.id) {
          this.$root.$emit('Save:DISABLED');
          this.$http.delete(`uploads/${this.localContent.id}`);
          this.$parent.uploadsIds = _.without(this.$parent.uploadsIds, upload.id);
          this.$parent.uploadsIds = _.uniq(this.$parent.uploadsIds);

          this.$http.put('fundraisers/' + this.$parent.fundraiser.id + '?include=uploads', {upload_ids: this.$parent.uploadsIds}).then((response) => {
            this.localContent = {};
            this.$root.$emit('Save:ENABLED');
          });
        }
      },
      slimService(formdata, progress, success, failure, slim) {
        let promise;
        // form data to post to server
        let data = {
          name: this.localUpload ? this.localUpload.name : this.id,
          file: this.slimAPI.dataBase64.output.image,
          path: `images/fundraiser_photos/${this.$parent.fundraiser.id}`,
          tags: ['Fundraiser'],
          type: 'other',
        };
        progress(3, 10);
        this.$root.$emit('Save:DISABLED');
        promise = this.$http.post(`uploads`, data).then((response) => {
          progress(7, 10);
          this.$parent.uploads.push(response.data.data);
          return this.localUpload = response.data.data;
        }, (error) => {
          let status;
          switch (error.response.data.message) {
            default:
              status = error.response.data.message;
          }
          failure(status);
          this.$root.$emit('Save:ENABLED');
        });

        promise.then((upload) => {
          if (upload) {

            this.$parent.uploadsIds.push(upload.id);
            this.$parent.uploadsIds = _.uniq(this.$parent.uploadsIds);
            // TODO: even though this is acceptable, probably find a better way to update parent object
            this.$parent.fundraiser.upload_ids = this.$parent.uploadsIds;

            this.$http.put('fundraisers/' + this.$parent.fundraiser.id + '?include=uploads', {upload_ids: this.$parent.uploadsIds}).then((response) => {
              // Now we want to update the content object
              this.localContent = {id: upload.id, source: upload.source};
              progress(10, 10);
              this.$root.$emit('Save:ENABLED');
              success();
            }, (error) => {
              let status;
              switch (error.response.data.message) {
                default:
                  status = error.response.data.message;
              }
              failure(status);
              this.$root.$emit('Save:ENABLED');
            }).catch(this.$root.handleApiError);
          }
        })

        // call these methods to handle upload state
        // console.log(progress, success, failure)
      },

      searchUploads() {
        let params = {
          include: '',
          per_page: 5,
          page: this.pagination.current_page,
          // sort: this.orderByField + '|' + (this.direction?'asc':'desc'),
          type: 'banner',
          tags: ['Fundraiser', 'User']
        };

        return this.$http.get('fundraisers/' + this.$parent.fundraiser.id + '?include=uploads', {params: params}).then((response) => {
          this.uploads = response.data.data;
          this.pagination = response.data.meta.pagination;
          return this.uploads;
        }).catch(this.$root.handleApiError);
      },
      selectExisting(upload) {
        this.localUpload = upload;
        this.slimAPI.load(upload.source, function (error, data) {
          // `this` is Slim Object
          this.upload();
        });
        this.previewModal = false;
      },
      selectExistingPreview() {
        this.localUpload = this.previewUpload;
        this.slimAPI.load(this.previewUpload.source, function (error, data) {
          // `this` is Slim Object
          this.upload();
        });
        this.previewModal = false;
      },

    },
    mounted() {
      if (this.content && this.content.id) {
        this.$http.get(`uploads/${this.content.id}`).then((response) => {
          this.localUpload = response.data.data;
        });
      }

      this.localContent = _.isObject(this.content) && _.isArray(this.content) && this.content.length === 0 ? {} : this.content;
      this.localCaption = this.caption;
    }
  }
</script>