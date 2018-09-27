<template>
    <div class="row form-group">
        <div class="col-sm-6 tour-step-avatar">
            <label>Profile Photo (max file size:2mb)</label>
            <h5>
                <a href="#">
                    <img class="av-left img-circle img-md" :src="'/api/' + selectedAvatar.source + '?w=64&h=64'" width="64">
                </a>
                <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button>
            </h5>
        </div>
        <hr class="divider inv visible-xs">
        <div class="col-sm-6 tour-step-cover">
            <label>Cover Photo (max file size:2mb)</label>
            <h5>
                <a href="#">
                    <img class="av-left img-rounded img-md"
                        :src="'/api/' + selectedBanner.source + '?w=64&h=64&fit=crop-center'"
                        width="64">
                </a>
                <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse"><i class="fa fa-camera"></i> Cover</button>
            </h5>
        </div>
        <div class="col-sm-12">
            <div class="collapse" id="avatarCollapse">
                <div class="well">
                    <upload-create-update 
                        type="avatar" 
                        :name="id || 'avatar'" 
                        lock-type ui-locked 
                        :ui-selector="2" 
                        is-child 
                        :is-update="!!avatar_upload_id" 
                        :upload-id="avatar_upload_id" 
                        :tags="['User']" 
                        @uploads-complete="uploadsComplete"
                    ></upload-create-update>
                </div>
            </div>
            <div class="collapse" id="bannerCollapse">
                <div class="well">
                    <upload-create-update 
                        type="banner" 
                        :name="id || 'banner'" 
                        lock-type 
                        ui-locked 
                        :ui-selector="1" 
                        :per-page="6" 
                        is-child 
                        :tags="['User']" 
                        @uploads-complete="uploadsComplete"
                    ></upload-create-update>
                </div>
            </div>
            <hr class="divider inv" />
        </div>
    </div>
</template>

<script>
import uploadCreateUpdate from './uploads/admin-upload-create-update.vue';

export default {

    name: 'UserUpload',

    components: {'upload-create-update': uploadCreateUpdate},

    props: {
        user: {
            type: Object,
            required: true
        },
        avatar: {
            type: Object,
            required: true
        },
        banner: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            id: null,
            selectedAvatar: null,
            avatar_upload_id: null,
            selectedBanner: null,
            banner_upload_id: null,
        }
    },

    methods: {
        uploadsComplete(data){
            switch(data.type){
                case 'avatar':
                    this.selectedAvatar = data;
                    this.avatar_upload_id = data.id;
                    $('#avatarCollapse').collapse('hide');
                    break;
                case 'banner':
                    this.selectedBanner = data;
                    this.banner_upload_id = data.id;
                    $('#bannerCollapse').collapse('hide');
                    break;
            }

            this.$http.put(`users/${this.user.id}`, {
                avatar_upload_id: this.avatar_upload_id,
                banner_upload_id: this.banner_upload_id
            })
            .then((response) => {
                swal('Nice work!', 'The photo was updated.', 'success');
            })
            .catch((error) => {
                swal('Oops! Something went wrong', error, 'error');
            });
        },
    },

    mounted() {
        this.selectedBanner = this.banner;
        this.selectedAvatar = this.avatar;
        this.id = this.user.id;
        this.avatar_upload_id = this.user.avatar_upload_id;
        this.banner_upload_id = this.user.banner_upload_id;
    }
}
</script>