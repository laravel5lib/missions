<template>
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <template v-if="isUser">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-body">
                        Add photos and videos!
                        <button class="btn btn-default-hollow btn-xs pull-right" data-toggle="collapse" data-target="#mediaCollapse" aria-expanded="false" aria-controls="mediaCollapse">
                            <i class="fa fa-picture-o icon-left"></i> Manage Media
                        </button>
                    </div>
                </div>
            </div>
            <div class="collapse" id="mediaCollapse">
                <div class="panel panel-default">
	                <div class="panel-body">
		                <div class="row">
			                <div class="col-xs-12">
				                <h3>Feature Picture</h3>
				                <hr class="sm divider">
				                <div class="btn-group">
					                <button @click.prevent="toggleUploader('feature', 1)" class="btn btn-default-hollow btn-sm">Choose</button>
					                <button @click.prevent="toggleUploader('feature', 2)" class="btn btn-default btn-sm">Upload</button>
				                </div>
				                <upload-create-update v-if="toggleUpload.feature.show" type="banner" :name="randName" lock-type ui-locked :ui-selector="toggleUpload.feature.UI" is-child :tags="['Fundraiser']" :width="1920" :height="1080"  @uploads-complete="uploadsComplete"></upload-create-update>
			                </div>
		                </div>
		                <div class="row">
			                <div class="col-xs-12">
				                <h3>Secondary Picture</h3>
				                <hr class="sm divider">
				                <div class="btn-group">
					                <button @click.prevent="toggleUploader('secondary', 1)" class="btn btn-default-hollow btn-sm">Choose</button>
					                <button @click.prevent="toggleUploader('secondary', 2)" class="btn btn-default btn-sm">Upload</button>
				                </div>
				                <upload-create-update v-if="toggleUpload.secondary.show" type="banner" :name="randName" lock-type ui-locked :ui-selector="toggleUpload.secondary.UI" is-child :tags="['Fundraiser']" :width="1920" :height="1080"  @uploads-complete="uploadsComplete"></upload-create-update>
			                </div>
		                </div>
		                <div class="row">
			                <div class="col-xs-12">
				                <h3>Video(optional)</h3>
				                <hr class="sm divider">
				                <div class="btn-group">
					                <button @click.prevent="toggleUploader('video', 1)" class="btn btn-default-hollow btn-sm">Choose</button>
					                <button @click.prevent="toggleUploader('video', 2)" class="btn btn-default btn-sm">Upload</button>
				                </div>
				                <upload-create-update v-if="toggleUpload.video.show" type="video" :name="randName" lock-type ui-locked :ui-selector="toggleUpload.video.UI" is-child :tags="['Fundraiser']"  @uploads-complete="uploadsComplete"></upload-create-update>
			                </div>
		                </div>
	                </div>
	                <!--<div class="panel-body">
                    &lt;!&ndash; TAB NAVIGATION &ndash;&gt;
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#tab1" role="tab" data-toggle="tab">All Media</a></li>
                        <li><a href="#tab2" role="tab" data-toggle="tab">Add Picture</a></li>
                        <li><a href="#tab3" role="tab" data-toggle="tab">Add Video</a></li>
                    </ul>
                    &lt;!&ndash; TAB CONTENT &ndash;&gt;
                    <div class="tab-content">
                        <div class="active tab-pane fade in" id="tab1">
                            <div class="row" v-if="fundraiser.hasOwnProperty('uploads')">
                                <p class="text-center text-muted" v-if="fundraiser.uploads.data.length < 1"><em>
                                    No media found. Add a video or picture.</em>
                                </p>
                                <div class="col-sm-4 col-md-3" v-for="upload in fundraiser.uploads.data">
                                    <div class="panel panel-default">
                                        <img :src="upload.type === 'video' ? 'https://placehold.it/155x87?text=Video' : upload.source" :alt="upload.name" class="img-responsive">
                                        <div class="panel-body">
                                            <p style="font-size:8px;" class="text-center text-muted">{{ upload.name }}</p>
                                            <div class="btn-group btn-group-xs btn-group-justified" role="group" aria-label="...">
                                                <a @click="viewUpload(upload)" class="btn btn-xs btn-primary-hollow" role="button"><i class="fa fa-eye"></i></a>
                                                <a @click="deleteUpload(upload)" class="btn btn-xs btn-default-hollow" role="button"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>&lt;!&ndash; end panel-body &ndash;&gt;
                                    </div>&lt;!&ndash; end panel &ndash;&gt;
                                </div>
                            </div>

                            <modal title="View Upload" :value="showView" @closed="showView=false">
                                <div slot="modal-body" class="modal-body" v-if="selectedUpload && selectedUpload.hasOwnProperty('type')">
                                    <template v-if="selectedUpload.type === 'video'">
                                        <video id="preview" class="video-js vjs-default-skin vjs-big-play-centered" width="620">
                                            <p class="vjs-no-js">
                                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                            </p>
                                        </video>
                                    </template>
                                    <template v-else>
                                        <img :src="selectedUpload.source" class="img-responsive">
                                    </template>
                                </div>
                                <div slot="modal-footer" class="modal-footer">
                                    <button type="button" class="btn btn-default" @click="closeView">Close</button>
                                </div>
                            </modal>

                            <modal title="Delete Upload" small :value="showDelete" @closed="showDelete=false" ok-text="Delete" cancel-text="Keep" :callback="doDelete">
                                <div slot="modal-body" class="modal-body">Delete this upload?</div>
                            </modal>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <upload-create-update type="other" :name="randName" lock-type ui-locked :ui-selector="2" is-child :tags="['Fundraiser']" :width="1920" :height="1080"  @uploads-complete="uploadsComplete"></upload-create-update>
                        </div>
                        <div class="tab-pane fade" id="tab3">
                            <upload-create-update type="video" :name="randName" lock-type ui-locked :ui-selector="2" is-child :tags="['Fundraiser']"  @uploads-complete="uploadsComplete"></upload-create-update>
                        </div>
                    </div>

                </div>-->
                </div>
            </div>
        </template>

        <div id="uploads-carousel" class="carousel slide" data-ride="carousel" data-interval="false" v-if="fundraiser.hasOwnProperty('uploads')">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <!--<li data-target="#uploads-carousel" data-slide-to="0" class="active"></li>-->
                <li data-target="#uploads-carousel" v-for="(upload, index) in fundraiser.uploads.data" :data-slide-to="index" :class="{'active' : index == 0}"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item" :class="{'active' : index == 0}" v-for="(upload, index) in fundraiser.uploads.data">
                    <template v-if="upload.type === 'video'">
                        <video :id="upload.id" class="video-js vjs-default-skin vjs-big-play-centered" width="620">
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                            </p>
                        </video>
                    </template>
                    <template v-else>
                        <img :src="upload.source">
                    </template>
                </div>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#uploads-carousel" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#uploads-carousel" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div><!-- end carousel -->
    </div>
</template>
<style>
    video {
        width: 100%;
        height: auto;
    }

    .carousel { overflow: hidden; }
    .carousel-indicators li { visibility: hidden; }

    .carousel-inner .item {
        font-size:10px;
        color:#0404B4
    }

    .carousel-control {
        z-index: 10;
        width: 30px;
        height: 50px;
        top: 40%;
    }
    .carousel-control.left,
    .carousel-control.right { background: none }

    .carousel-control.left > span.fa {  }
    .carousel-control.right > span.fa {  }

    .carousel-control > span.fa {
        background-color: #EB0A18;
        border-radius: 3px;
    }
</style>
<script type="text/javascript">
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    export default{
        name: 'fundraisers-uploads',
        props: ['id', 'sponsorId', 'editable'],
        components: {'upload-create-update': uploadCreateUpdate},
        data(){
            return{
                fundraiser: {},
                showView: false,
                showDelete: false,
                selectedUpload: null,
                selectedUploadPlayer: null,

                toggleUpload: {
                    feature: {
                        show: false,
	                    UI: 2
                    },
	                secondary: {
                        show: false,
                        UI: 2
                    },
	                video: {
                        show: false,
                        UI: 2
                    }
                },
            }
        },
        computed:{
            randName(){
                return (this.fundraiser.url || this.id) + '_' + moment().unix().toString();
            },
            isUser(){
                if (this.editable === 1) return true;

                return this.sponsorId && this.$root.user && this.sponsorId === this.$root.user.id;
            },
        },
        methods:{
            toggleUploader(type, UI) {
                this.toggleUpload[type].UI = UI;
                this.toggleUpload[type].show = true;

            },
            uploadsComplete(data){
                switch(data.type){
                    case 'video':
                    case 'other':
                        var arr = _.pluck(this.fundraiser.uploads.data, 'id');
                        arr.push(data.id);
                        arr = _.uniq(arr);
                        this.fundraiser.upload_ids = arr;
                        jQuery('#mediaCollapse').collapse('hide');
                        break;
                }
                this.submit();
            },
            viewUpload(upload){
                let vjsOptions;

                this.selectedUpload = upload;
                this.showView = true;

                if (this.selectedUpload.type === 'video') {
                    // load preview player
                    this.$nextTick(() =>  {
                        if (this.selectedUploadPlayer === null) {
                            vjsOptions = {
                                controls: true,
                                autoplay: false,
                                preload: 'metadata',
                                fluid: true,
                                techOrder: ['youtube', 'vimeo']
                            };

                            // Add conditional options based on video format/website
                            switch (this.selectedUpload.meta.format) {
                                case 'youtube':
                                    //vjsOptions.techOrder = ['youtube'];
                                    vjsOptions.sources = [{type: 'video/youtube', src: this.selectedUpload.source}];
                                    break;
                                case 'vimeo':
                                    //vjsOptions.techOrder = ['vimeo'];
                                    vjsOptions.sources = [{type: 'video/vimeo', src: this.selectedUpload.source}];
                                    break;
                            }

                            this.selectedUploadPlayer = videojs('preview', vjsOptions, () =>  {
                                // Player (this) is initialized and ready.
                            });
                        } else {
                            switch (this.selectedUpload.meta.format) {
                                case 'youtube':
                                    //vjsOptions.techOrder = ['youtube'];
                                    this.selectedUploadPlayer.src({type: 'video/youtube', src: this.selectedUpload.source});
                                    break;
                                case 'vimeo':
                                    //vjsOptions.techOrder = ['vimeo'];
                                    this.selectedUploadPlayer.src({type: 'video/vimeo', src: this.selectedUpload.source});
                                    break;
                            }
                        }
                    });
                }
            },
            closeView(){
                if (this.selectedUpload.type === 'video') {
                    // do something with player
                }
                this.showView = false;
            },
            deleteUpload(upload){
                this.selectedUpload = upload;
                this.showDelete = true;
            },
            doDelete(){
                this.$http.delete('uploads/' + this.selectedUpload.id).then((response) => {
                    console.log(response);
                    this.fundraiser.uploads.data = _.reject(this.fundraiser.uploads.data, (upload) => {
                        return upload.id === this.selectedUpload.id;
                    });
                    this.showDelete = false;
                    this.selectedUpload = null;

                }).catch(this.$root.handleApiError);
            },
            submit(){
                console.log(this);
                // this.$refs.spinner.show();
                this.$http.put('fundraisers/' + this.id + '?include=uploads', {upload_ids: this.fundraiser.upload_ids}).then((response) => {
                    this.fundraiser = response.data.data;
                    this.initVideoPlayers();
                    // this.$refs.spinner.hide();
                }).catch(this.$root.handleApiError);
            },
            initVideoPlayers(){
                // Handling Videos
                // We must use $nexTick to allow the DOM to fully load first
                // else videojs won't find the element
                this.$nextTick(() =>  {
                    // We need to filter the `upload.type: 'video'` objs
                    let videos = _.filter(this.fundraiser.uploads.data, (upload) => {
                        return upload.type === 'video';
                    });

                    // Initiate videojs players for each video
                    _.each(videos, (video) => {
                        var vjsId = video.id;
                        var vjsOptions = {
                            controls: true,
                            autoplay: false,
                            preload: 'metadata',
                            fluid: true
                        };

                        // Add conditional options based on video format/website
                        switch (video.meta.format) {
                            case 'youtube':
                                vjsOptions.techOrder = ['youtube'];
                                vjsOptions.sources = [{ type: 'video/youtube', src: video.source}];
                                break;
                            case 'vimeo':
                                vjsOptions.techOrder = ['vimeo'];
                                vjsOptions.sources = [{ type: 'video/vimeo', src: video.source}];
                                break;
                        }
                        videojs(vjsId, vjsOptions, function(){
                            // Player (this) is initialized and ready.
                        });
                    });
                });
            }
        },
        mounted(){
            // this.$refs.spinner.show();
            this.$http.get('fundraisers/' + this.id, { params: { include: 'uploads'} }).then((response) => {
                this.fundraiser = response.data.data;
                this.initVideoPlayers();
                // this.$refs.spinner.hide();
            }).catch(this.$root.handleApiError);
        }
    }
</script>