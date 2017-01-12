<template>
    <spinner v-ref:spinner size="sm" text="Loading"></spinner>
    <template v-if="isUser()">
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
            <div class="panel panel-default"><div class="panel-body">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab1" role="tab" data-toggle="tab">All Media</a></li>
                    <li><a href="#tab2" role="tab" data-toggle="tab">Add Picture</a></li>
                    <li><a href="#tab3" role="tab" data-toggle="tab">Add Video</a></li>
                </ul>
                <!-- TAB CONTENT -->
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
                                    </div><!-- end panel-body -->
                                </div><!-- end panel -->
                            </div>
                        </div>

                        <modal title="View Upload" :show.sync="showView">
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

                        <modal title="Delete Upload" small :show.sync="showDelete" ok-text="Delete" cancel-text="Cancel" :callback="doDelete">
                            <div slot="modal-body" class="modal-body">Are you show that you want to delete this upload?</div>
                        </modal>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <upload-create-update type="other" :name="randName" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Fundraiser']" :width="1920" :height="1080"></upload-create-update>
                    </div>
                    <div class="tab-pane fade" id="tab3">
                        <upload-create-update type="video" :name="randName" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Fundraiser']"></upload-create-update>
                    </div>
                </div>

            </div></div>
        </div>
    </template>

    <div id="uploads-carousel" class="carousel slide" data-ride="carousel" data-interval="false" v-if="fundraiser.hasOwnProperty('uploads')">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <!--<li data-target="#uploads-carousel" data-slide-to="0" class="active"></li>-->
            <li data-target="#uploads-carousel" class="{{ $index == 0 ? 'active' : '' }}" :data-slide-to="$index" v-for="upload in fundraiser.uploads.data"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item {{ $index == 0 ? 'active' : '' }}" v-for="upload in fundraiser.uploads.data">
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
        props: ['id', 'sponsorId'],
        components: {'upload-create-update': uploadCreateUpdate},
        data(){
            return{
                fundraiser: {},
                showView: false,
                showDelete: false,
                selectedUpload: null,
                selectedUploadPlayer: null
            }
        },
        events:{
            'uploads-complete'(data){
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
            }
        },
        computed:{
            randName(){
                return (this.fundraiser.url || this.id) + '_' + moment().unix().toString();
            },
        },
        methods:{
            isUser(){
                return this.sponsorId && this.$root.user.id && this.sponsorId === this.$root.user.id;
            },
            viewUpload(upload){
                this.selectedUpload = upload;
                this.showView = true;

                if (this.selectedUpload.type === 'video') {
                    // load preview player
                    this.$nextTick(function () {
                        if (this.selectedUploadPlayer === null) {
                            var vjsOptions = {
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

                            this.selectedUploadPlayer = videojs('preview', vjsOptions, function () {
                                // Player (this) is initialized and ready.
                            });
                        } else {
                            debugger;
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
                this.$http.delete('uploads/' + this.selectedUpload.id).then(function (response) {
                    console.log(response);
                    this.fundraiser.uploads.data = _.reject(this.fundraiser.uploads.data, function (upload) {
                        return upload.id === this.selectedUpload.id;
                    }.bind(this));
                    this.showDelete = false;
                    this.selectedUpload = null;

                });
            },
            submit(){
                console.log(this);
                // this.$refs.spinner.show();
                this.$http.put('fundraisers/' + this.id + '?include=uploads', this.fundraiser).then(function (response) {
                    this.fundraiser = response.data.data;
                    this.initVideoPlayers();
                    // this.$refs.spinner.hide();
                });
            },
            initVideoPlayers(){
                // Handling Videos
                // We must use $nexTick to allow the DOM to fully load first
                // else videojs won't find the element
                this.$nextTick(function () {
                    // We need to filter the `upload.type: 'video'` objs
                    var videos = _.filter(this.fundraiser.uploads.data, function (upload) {
                        return upload.type === 'video';
                    });

                    // Initiate videojs players for each video
                    _.each(videos, function (video) {
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
        ready(){
            // this.$refs.spinner.show();
            this.$http.get('fundraisers/' + this.id, { include: 'uploads'}).then(function (response) {
                this.fundraiser = response.data.data;
                this.initVideoPlayers();
                // this.$refs.spinner.hide();
            }, function (error) {
                // this.$refs.spinner.hide();
                //TODO add error alert
            });
        }
    }
</script>