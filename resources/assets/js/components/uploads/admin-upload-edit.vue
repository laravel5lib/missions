<template  xmlns:v-crop="http://www.w3.org/1999/xhtml">

        <form id="CreateUploadForm" class="form-horizontal" novalidate @submit="prevent" style="position:relative">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Name" name="name" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group" v-error-handler="{ value: tags, handle: 'tags' }">
                <label for="tags" class="col-sm-2 control-label">Tags</label>
                <div class="col-sm-10">
                    <v-select @keydown.enter.prevent=""  id="tags" class="form-control" name="tags" v-validate="'required'" multiple v-model="tags" :options="tagOptions"></v-select>
                </div>
            </div>
            <div class="form-group" v-error-handler="{ value: type, handle: 'type' }">
                <label for="type" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-10">
                    <select class="form-control" id="type" v-model="type" name="type" v-validate="'required'" disabled>
                        <option value="">-- select type --</option>
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
                    <a @click="submit" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>

</template>
<script type="text/javascript">
    import vSelect from 'vue-select'
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'upload-edit',
        props:['uploadId'],
        mixins: [errorHandler],
        components: {vSelect},
        data(){
            return {
//				showRight: false,

                name: '',
                type: null,
                path: '',
                file: null,
                x_axis: null,
                y_axis: null,
                width: 100,
                height: 100,
                tags: [],

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
                aspectRatio: this.width / this.height,
                fileA: null,
                resultImage: null,
                typeObj: null,
                typePaths: [
                    {type: 'avatar', path: 'images/avatars', width: 1280, height: 1280},
                    {type: 'banner', path: 'images/banners', width: 1300, height: 500},
                    {type: 'other', path: 'images/other'},
                    {type: 'file', path: 'resources/documents'},
                ],
                resource: this.$resource('uploads{/id}'),
                tagOptions: ['Campaign', 'User', 'Group', 'Fundraiser'],
                // mixin settings
                validatorHandle: 'EditUser',

            }
        },
        watch: {
            'type'(val, oldVal) {
                this.typeObj = _.findWhere(this.typePaths, {type: val});
                this.path = this.typeObj.path;
                if (this.file)
                    this.adjustSelectByType();
            },
            'tags'(val, oldVal) {

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
            submit(){
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.resource.put({id: this.uploadId}, {
                            name: this.name,
                            tags: this.tags,
                            type: this.type,
                            path: this.path,
                            file: this.file || undefined,
                            x_axis: parseInt(this.x_axis / this.imageAspectRatio) || undefined,
                            y_axis: parseInt(this.y_axis / this.imageAspectRatio) || undefined,
                            width: parseInt(this.coords.w / this.imageAspectRatio) || undefined,
                            height: parseInt(this.coords.h / this.imageAspectRatio) || undefined,
                        }).then((resp) => {
                            console.log(resp);
//                    	this.resultImage = resp.data;
                            window.location.href = '/admin/uploads';
//                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                        }, (error) => {
                            console.log(error);
                        });
                    }
                })
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
            }
        },
        mounted(){
            this.$root.$on('vueCrop-api', (api) => {
                // make api available on scope
                window.vueCropApi = this.vueCropApi = api;
            })

            this.resource.get({id:this.uploadId}).then((response) => {
                var upload = response.data.data;
                this.name = upload.name;
                this.tags = upload.tags;
                this.type = upload.type;
            })
        }
    }
</script> 