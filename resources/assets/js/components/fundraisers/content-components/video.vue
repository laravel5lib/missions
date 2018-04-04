<template>
	<div>
		<template v-if="previewMode">
			<div class="vjs-player-container">
				<video v-if="localContent.source && localContent.type" :id="id"
				       class="video-js vjs-default-skin vjs-big-play-centered" width="620">
					<p class="vjs-no-js">
						To view this video please enable JavaScript, and consider upgrading to a web browser that
						<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
					</p>
				</video>
				<hr class="divider sm inv">
				<p class="text-center text-muted caption-text"><em>{{ localCaption }}</em></p>
			</div>

		</template>
		<template v-else>
			<div>
				<div class="row bg-gray">
					<div class="col-xs-8">
						<label>Video</label>
						<span class="help-block">Add a video to grab your reader's attention and make your story more interesting.</span>
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
								<li><a @click="remove">Remove Video</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row bg-hollow">
					<div class="col-sm-12 text-center">
						<template v-if="!localContent.id">
							<uploader type="video" hide-submit lock-type ui-locked is-child selector-locked
							          :is-update="!!localContent.id" :upload-id="localContent.id"
							          :button-text="localContent.id ? 'Change' : 'Attach'" :name="randName"
							          :ui-selector="2" :tags="['Fundraiser']"
							          @uploads-complete="uploadsComplete"></uploader>
							<div v-if="videoUploads.length">
								<hr class="divider sm inv">
								<p class="text-center">
									<em class="text-muted">Or</em>
								</p>
								<hr class="divider sm inv">
								<button class="btn btn-sm btn-default-hollow" @click="previewModal = true">
									Choose an Video
								</button>
							</div>
						</template>
						<template v-else>
							<div class="form-group text-right">
								<button type="button" class="btn btn-link" @click="clearVideo">
									<i class="fa fa-cog"></i> Change Video
								</button>
							</div>
						</template>
						<div class="form-group">
							<hr class="divider sm inv">
							<div class="vjs-player-container">
							<video v-show="localContent && localContent.source" :id="id"
							       class="video-js vjs-default-skin vjs-big-play-centered" width="620">
								<p class="vjs-no-js">
									To view this video please enable JavaScript, and consider upgrading to a web browser that
									<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
								</p>
							</video>
							</div>
							<div class="caption-text">
								<hr class="divider inv">
								<label>Caption (optional)</label>
								<input type="text"
								       class="form-control"
								       placeholder="figure A1"
								       v-model="localCaption">
							</div>

						</div>
					</div>
				</div>
			</div>
		</template>
		<modal title="Select an Video" :value="previewModal" @closed="previewModal=false" effect="zoom" width="400"
		       ok-text="Select" :callback="selectExistingPreview">
			<div slot="modal-body" class="modal-body">
				<div class="flex-container" style="justify-content: space-around;">
					<div class="" v-for="upload in videoUploads" style="width:100px;height:155px;">
						<div class="panel panel-default">
							<a @click="preview(upload)" role="button" data-toggle="tooltip" data-placement="top"
							   title="Preview">
								<!--<tooltip effect="scale" placement="top" content="Preview">-->
								<img :src="getThumbnailUrl(upload.source) || 'https://placehold.it/620x349?text=Video'"
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
					<!--<div class="col-xs-12 text-center">
						<pagination :pagination="pagination" pagination-key="pagination" :callback="searchUploads"></pagination>
					</div>-->
				</div>
				<hr class="divider">
				<template v-if="previewUpload">
					<h5 class="text-center">{{previewUpload.name}}</h5>
					<div class="vjs-player-container">
					<video id="previewUpload" class="video-js vjs-default-skin vjs-big-play-centered" width="650">
						<p class="vjs-no-js">
							To view this video please enable JavaScript, and consider upgrading to a web browser that
							<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
						</p>
					</video>
					</div>
				</template>
				<template v-else>
					<p class="well text-center text-muted">
						<em>Select a Video to Preview</em>
					</p>
				</template>
			</div>
		</modal>
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore';
  import uploader from '../../uploads/admin-upload-create-update.vue';

  export default {
    name: 'video',
    components: {uploader},
    props: {
      id: { type: String, default: _.uniqueId('video_') },
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
        localContent: {},
        localCaption: null,
        videoJsPreviewAPI: null,
        videoJsAPI: null,

        // select vars
        previewModal: false,
        previewUpload: null,
        page: 1,
        search: '',
        pagination: {current_page: 1},

        // state params
        isMoving: false,
        isInit: true,
      }
    },
    watch: {
      localContent(val, oldVal) {
        this.$emit('update:content', val);
        if (val.id) {
          this.initVideoPlayers();
        }
      },
      localCaption(val, oldVal) {
        this.$emit('update:caption', val)
      },
      previewMode(val, oldVal) {
        this.initVideoPlayers();
      }
    },
    computed: {
      randName() {
        return this.$parent.fundraiser ? `${this.$parent.fundraiser.url}_${this.id}` : null;
      },
      videoUploads() {
        return _.where(this.$parent.uploads, {type: 'video'});
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
        this.clearVideo();
        this.$emit('remove');
      },

      clearVideo() {
        // Clear video player and API
        if (this.videoJsAPI) {
          this.videoJsAPI.dispose();
          this.videoJsAPI = null;
        }

        // If localContent has an ID and it is present in videoUploads, delete it
        if (this.localContent.id && _.contains(this.videoUploads, this.localContent.id)) {
          this.$http.delete(`uploads/${this.localContent.id}`).then(response => {
            this.$parent.uploads = _.reject(this.$parent.uploads, upload => upload.id === this.localContent.id);
            this.$parent.uploadsIds = _.without(this.$parent.uploadsIds, this.localContent.id);
            // Sync Uploads with server
            this.updateUploads();
          });
        }
        this.localContent = {};
      },
      preview(upload) {
        this.previewUpload = null;
        this.$nextTick(function () {
          this.previewUpload = upload;
          this.$nextTick(function () {
            this.initVideoPlayers(true);
          });
        });
      },
      selectExisting(video) {
        this.localContent = video;
        // Clean up
        this.previewModal = false;
        this.previewUpload = null;

        this.initVideoPlayers();
      },
      selectExistingPreview() {
        this.localContent = _.extend({}, this.previewUpload);
        // Clean up
        this.previewModal = false;
        this.previewUpload = null;

        this.initVideoPlayers();
      },

      updateUploads() {
        this.$parent.uploadsIds = _.uniq(this.$parent.uploadsIds);
        // TODO: even though this is acceptable, probably find a better way to update parent object
        this.$parent.fundraiser.upload_ids = this.$parent.uploadsIds;
        this.$http
          .put('fundraisers/' + this.$parent.fundraiser.id + '?include=uploads', {upload_ids: this.$parent.uploadsIds})
          .then((response) => {
            this.$root.$emit('Save:ENABLED')
          }).catch(this.$root.handleApiError);
      },
      uploadsComplete(data) {
        this.$root.$emit('Save:DISABLED');
        switch (data.type) {
          case 'video':
            // Update Uploads and uploadsIds arrays
            this.$parent.uploads.push(data);
            this.$parent.uploadsIds.push(data.id);
            this.localContent = data;
            // Sync Uploads with server
            this.updateUploads();
            break;
        }
        this.initVideoPlayers();
      },
      getThumbnailUrl(url) {
        if (url) {
          if (url.indexOf('youtu') != -1) {
            let video_id = url.indexOf('v=') != -1 ? url.split('v=')[1].split('&')[0] : url.split('be/')[1];
            return 'https://img.youtube.com/vi/' + video_id + '/hqdefault.jpg';
          } else if (url.indexOf('vimeo') != -1) {
            let video_id = url.split('.com/')[1];
            $.get('https://vimeo.com/api/v2/video/' + video_id + '.json')
              .then(res => {
                return res[0].thumbnail_large;
              });
          }
        }
      },
      initVideoPlayers(skipDestroy = true) {
        let self = this;
        let player;
        let settings;

        if (this.videoJsAPI) {
          this.videoJsAPI.dispose();
          this.videoJsAPI = null;
        }

        // Handling Videos
        // We must use $nexTick to allow the DOM to fully load first
        // else videojs won't find the element
        this.$nextTick(() => {
            // check if player (video element) exists
            player = document.getElementById(this.id);
            // if not, create it
            if (!player) {
              player = document.createElement('video');
//              let captionText = document.getElementsByClassName('caption-text')[0];
              player.setAttribute('id', this.id);
              player.setAttribute('class', 'video-js vjs-default-skin vjs-big-play-centered');
              player.setAttribute('width', 620);
              // insert player into template
              $(this.$el).find('.vjs-player-container').html(player);
            }

            if (this.localContent.id && this.localContent.source && this.localContent.type) {
              settings = this.videoConfiguration(this.localContent);
              videojs(player, settings.options, function () {
                // Player (this) is initialized and ready.
                self.videoJsAPI = this;
              });
            }
        });
      },
      videoConfiguration(video) {
        let vjsId = video.id;
        let vjsOptions = {
          controls: true,
          autoplay: false,
          preload: 'metadata',
          fluid: true,
          techOrder: ['youtube', 'vimeo']
        };

        // Add conditional options based on video format/website
        switch (video.meta.format) {
          case 'youtube':
            vjsOptions.sources = [{type: 'video/youtube', src: video.source}];
            break;
          case 'vimeo':
            vjsOptions.sources = [{type: 'video/vimeo', src: video.source}];
            break;
        }

        return {id: vjsId, options: vjsOptions};
      },

    },
    mounted() {
      this.localContent = _.isObject(this.content) && _.isArray(this.content) && this.content.length === 0 ? {} : this.content;
      this.localCaption = this.caption;

      this.$nextTick(() => {
        this.isInit = true;
        this.initVideoPlayers();
      });

      this.$root.$on('Uploader:BUSY', () => {
        this.$root.$emit('Save:DISABLED');
      });
      this.$root.$on('Uploader:DONE', () => {
        this.$root.$emit('Save:ENABLED');
      });
      this.$root.$on('Components:REMOVED', () => {
        this.initVideoPlayers();
      });
      this.$root.$on('Components:MOVING', () => {
        this.isMoving = true;
        if (this.videoJsAPI) {
          this.videoJsAPI.dispose();
          this.videoJsAPI = null;
        }
      });
      this.$root.$on('Components:MOVED', () => {
        this.isMoving = false;
        this.$nextTick(() => {
          this.localContent = _.isObject(this.content) && _.isArray(this.content) && this.content.length === 0 ? {} : this.content;
          this.localCaption = this.caption;
          if (!this.videoJsAPI) this.initVideoPlayers();
        });
      });
    }
  }
</script>