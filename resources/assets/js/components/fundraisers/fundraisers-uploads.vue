<template>
    <template v-if="isUser()">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h5>Add photos and videos!</h5>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#mediaCollapse" aria-expanded="false" aria-controls="mediaCollapse">Manage Media <i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-12 text-center">
                <h5>Add photos and videos!</h5>
            </div>
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#mediaCollapse" aria-expanded="false" aria-controls="mediaCollapse">Manage Media <i class="fa fa-plus"></i></button>
            </div>
        </div>
        <hr class="divider inv">
        <div class="collapse" id="mediaCollapse">
            <div class="well">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Media</a></li>
                    <li><a href="#tab2" role="tab" data-toggle="tab">Add Image</a></li>
                    <li><a href="#tab3" role="tab" data-toggle="tab">Add Video</a></li>
                </ul>
                <!-- TAB CONTENT -->
                <div class="tab-content">
                    <div class="active tab-pane fade in" id="tab1">
                        <div class="row" v-if="fundraiser.hasOwnProperty('uploads')">
                            <div class="col-sm-4 col-md-3" v-for="upload in fundraiser.uploads.data">
                                <div class="thumbnail">
                                    <img :src="upload.type === 'video' ? 'https://placehold.it/150x150?text=Video' : upload.source" :alt="upload.name">
                                    <div class="caption">
                                        <h6>{{ upload.name }}</h6>
                                        <div class="btn-group btn-group-xs btn-group-justified" role="group" aria-label="...">
                                            <a @click="viewUpload(upload)" class="btn btn-xs btn-info" role="button"><i class="fa fa-eye"></i></a>
                                            <a @click="deleteUpload(upload)" class="btn btn-xs btn-danger" role="button"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <modal title="View Upload" :show.sync="showView">
                            <div slot="modal-body" class="modal-body" v-if="selectedUpload && selectedUpload.hasOwnProperty('type')">
                                <template v-if="selectedUpload.type === 'video'">
                                    <iframe width="560" height="315" :src="selectedUpload.source" frameborder="0" allowfullscreen></iframe>
                                    <!--<iframe width="560" height="315" src="https://www.youtube.com/embed/RwXqcOMw0ng" frameborder="0" allowfullscreen></iframe>  -->
                                </template>
                                <template v-else>
                                    <img :src="selectedUpload.source" class="img-responsive">
                                </template>
                            </div>
                        </modal>

                        <modal title="Delete Upload" small :show.sync="showDelete" ok-text="Delete" cancel-text="Cancel" :callback="doDelete">
                            <div slot="modal-body" class="modal-body">Are you show that you want to delete this upload?</div>
                        </modal>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <upload-create-update type="other" :name="randName" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Fundraiser']"></upload-create-update>
                    </div>
                    <div class="tab-pane fade" id="tab3">
                        <upload-create-update type="video" :name="randName" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Fundraiser']"></upload-create-update>
                    </div>
                </div>

            </div>
        </div>
    </template>

    <div id="uploads-carousel" class="carousel slide" data-ride="carousel" v-if="fundraiser.hasOwnProperty('uploads')">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <!--<li data-target="#uploads-carousel" data-slide-to="0" class="active"></li>-->
            <li data-target="#uploads-carousel" class="{{ $index == 0 ? 'active' : '' }}" :data-slide-to="$index" v-for="upload in fundraiser.uploads.data"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item {{ $index == 0 ? 'active' : '' }}" v-for="upload in fundraiser.uploads.data">
                <template v-if="upload.type === 'video'">
                    <iframe width="560" height="315" :src="upload.source" frameborder="0" allowfullscreen></iframe>
                    <!--<iframe width="560" height="315" src="https://www.youtube.com/embed/RwXqcOMw0ng" frameborder="0" allowfullscreen></iframe>  -->
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
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    export default{
        name: 'fundraisers-uploads',
        props: ['id', 'sponsorId', 'authId'],
        components: {'upload-create-update': uploadCreateUpdate, 'alert': VueStrap.alert, 'modal': VueStrap.modal},
        data(){
            return{
                fundraiser: {},
                showView: false,
                showDelete: false,
                selectedUpload: null
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
                return this.sponsorId && this.authId && this.sponsorId === this.authId;
            },
            viewUpload(upload){
                this.selectedUpload = upload;
                this.showView = true;
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
                this.$http.put('fundraisers/' + this.id + '?include=uploads', this.fundraiser).then(function (response) {
                    this.fundraiser = response.data.data;
                });
            }
        },
        ready(){
            this.$http.get('fundraisers/' + this.id, { include: 'uploads'}).then(function (response) {
                this.fundraiser = response.data.data;
            });
        }
    }
</script>
