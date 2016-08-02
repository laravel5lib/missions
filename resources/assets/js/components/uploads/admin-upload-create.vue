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
                        <option :value="">-- select type --</option>
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
					<a @click="submit()" class="btn btn-primary">Create</a>
				</div>
			</div>

		</form>
    </validator>
</template>
<script>
//	import VueStrap from 'vue-strap/dist/vue-strap.min';
	export default{
        name: 'upload-create',
//		components: {'alert': VueStrap.alert},
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
				aspectRatio: this.width/this.height,
				fileA: null,
				resultImage: null,
				typeObj: null,
				typePaths: [
					{type: 'avatar', path: 'images/avatars', width: 600, height: 600},
					{type: 'banner', path: 'images/banners', width: 1200, height: 320},
					{type: 'other', path: 'images/other'},
					{type: 'file', path: 'resources/documents'},
				]
			}
        },
		watch:{
        	'type': function (val, oldVal) {
        		this.typeObj = _.findWhere(this.typePaths, {type: val});
				this.path = this.typeObj.path;
				if (this.file)
					this.adjustSelectByType();
			}
		},
		events:{
			'vueCrop-api':function (api) {
				// make api available on scope
				window.vueCropApi = this.vueCropApi = api;
			}
		},
        methods: {
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
                        path: this.path,
						file: this.file,
                        x_axis: parseInt(this.x_axis / this.imageAspectRatio),
                        y_axis: parseInt(this.y_axis / this.imageAspectRatio),
                        width: parseInt(this.scaledWidth),
                        height: parseInt(this.scaledHeight),
                    }).then(function (resp) {
						console.log(resp);
//                    	this.resultImage = resp.data;
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                    }, function (error) {
                        console.log(error);
                    });
                }
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
		ready(){

        }
    }
</script> 