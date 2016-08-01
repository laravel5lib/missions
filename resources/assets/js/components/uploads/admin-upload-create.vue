<template xmlns:v-validate="http://www.w3.org/1999/xhtml" xmlns:v-crop="http://www.w3.org/1999/xhtml">
    <validator name="CreateUpload">
        <form id="CreateUploadForm" class="form-horizontal" novalidate @submit="prevent">
            <div class="form-group" :class="{ 'has-error': checkForError('name') }">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                <label for="type" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-10">
                    <select class="form-control" id="type" v-model="type" v-validate:type="{ required: true }">
                        <option value="">-- select type --</option>
                        <option value="avatar">Avatar</option>
                        <option value="banner">Banner</option>
                        <option value="file">File</option>
                        <option value="photo">Photo</option>
                        <option value="thumbnail">Thumbnail</option>
                    </select>
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="width">Width (px)</label>
                        <input type="number" number class="form-control" v-model="width" id="width" placeholder="300">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="height">Height (px)</label>
                        <input type="number" number class="form-control" v-model="height" id="height" placeholder="300">
                    </div>
                </div>
            </div>

			<div class="form-group">
				<label for="file" class="col-sm-2 control-label">File</label>
				<div class="col-sm-10">
					<input type="file" id="file" v-model="fileA" @change="handleImage" class="form-control">
					 <!--<h5>Coords: {{coords|json}}</h5>-->
				</div>
			</div>

			<div class="form-group">
				<label for="file" class="col-sm-2 control-label">Crop Image</label>
				<div id="crop-wrapper" class="col-sm-10">
					<img v-if="file" :src="file" :width="imageWidth" :height="imageHeight"
							 v-crop:create="test" v-crop:start="test" v-crop:move="test" v-crop:end="test"/>
				</div>
			</div>

			<br>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a href="/admin/uploads" class="btn btn-default">Cancel</a>
					<a @click="submit()" class="btn btn-primary">Create</a>
				</div>
			</div>

		</form>
    </validator>
</template>
<script>
	export default{
        name: 'upload-create',
        data(){
            return {
                name: '',
                type: '',
                path: '',
                file: null,
                x_axis: null,
                y_axis: null,
                width: null,
                height: null,

				// logic variables
				attemptSubmit: false,
				coords: 'Try to move/resize the selection',
				vueCropApi: null,
				imageWidth: 600,
				imageHeight: null,
				aspectRatio: null,
				fileA: null,
			}
        },
		events:{
			'vueCrop-api':function (api) {
				// make api available on scope
				this.vueCropApi = api;
			}
		},
		watch:{
			'width':function (val) {
				this.width = val;
//				this.vueCropApi.setSelect([(this.coords.x/2)-50, (this.coords.y/2)-50, val, val]);

			},
			'height':function (val) {
				this.height = val;
//				this.vueCropApi.setSelect([(this.coords.x/2)-50, (this.coords.y/2)-50, val, val]);
			},
		},
        methods: {
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
                    var resource = this.$resource('uploads');
                    resource.save(null, {
                        name: this.name,
                        type: this.type,
                        path: this.name + '_' + moment().valueOf(),
                        //path: this.path,
                        file: this.file,
                        x_axis: this.x_axis,
                        y_axis: this.y_axis,
                        width: this.width,
                        height: this.height,
                    }).then(function (resp) {
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                    }, function (error) {
                        console.log(error);
                    });
                }
            },
			handleImage(e){
            	/*if (!this.canvas) {
					this.canvas = document.getElementById('imageCanvas');
					this.ctx = this.canvas.getContext('2d');
				}*/
				var self = this;
				var reader = new FileReader();
				reader.onload = function(event){
					var img = new Image();
					img.onload = function(){
						self.aspectRatio = img.height/img.width;
						self.imageHeight = self.aspectRatio * self.imageWidth;

						// adjust container
						self.vueCropApi.resizeContainer(self.imageWidth, self.imageHeight);
						self.vueCropApi.setSelect([(self.imageWidth/2)-50, (self.imageHeight/2)-50, self.width, self.height]);

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
		ready(){

        }
    }
</script> 