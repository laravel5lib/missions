<template>   
    <slim-cropper :options="slimOptions"/>
</template>
<script>
import Slim from '../vendors/slim.vue';
export default {
    name: 'avatar-uploader',

    components: {
      'slim-cropper': Slim
    },

    props: {
        uploadUrl: {
            type: String,
            required: true
        },
        loadedImage: {
            type: String,
            default: null
        }
    },

    data() {
        return {
            slimAPI: null,
            slimOptions: {
                ratio: '4:4',
                push: true,
                statusFileType: 'image/bmp, image/jpg, image/jpeg, image/png, image/gif',
                instantEdit: false,
                service: this.slimService,
                didInit: this.slimInit
            },
        }
    },

    methods: {
        slimInit(data, slim) {
            if (this.loadedImage) {
                slim.load(this.loadedImage, { blockPush: true });
            }
            this.slimAPI = slim;
        },
        slimService(formdata, progress, success, failure, slim) {
            progress(2, 10);
            this.uploadImage(this.slimAPI.dataBase64.output.image).then((response) => {
                progress(10, 10);
                success();
                swal("Updated!", "Photo was saved successfully.", "success", {
                    button: true,
                    timer: 3000
                });
            }).catch((error) => {
                failure();
            });
        },
        uploadImage(file) {
            return this.$http.post(this.uploadUrl, {
                file
            }).catch((error) => {
                return error;
            });
        },
    }
}
</script>

