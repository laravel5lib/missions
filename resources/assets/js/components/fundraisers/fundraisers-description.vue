<template>
<div>
  <h3>Edit Fundrasier</h3>
  <div class="panel panel-default">
		<div class="panel-body">
      <div class="row">
        <div class="col-sm-4">
          <h5>Featured Photo</h5>
          <p class="text-muted">Adding a photo to your fundraiser will increase donations. A photo is required.</p>
        </div>
        <div class="col-sm-8">
          <div class="form-group">
            <div class="col-xs-12">
              <slim-cropper v-show="showSlim" :options="slimOptions"/>
              <modal title="Featured Images" large v-model="showMediaPicker">
                <spinner ref="spinner" global size="sm" :text="loadingText"></spinner>
                <div class="row">
                  <div class="col-xs-6 col-md-3" v-for="image in fundraiserImageChoices">
                    <a @click.prevent="chooseImage(image)" class="thumbnail">
                      <img :src="image.source" :alt="image.name">
                    </a>
                  </div>
                </div>
                <div slot="modal-footer" class="modal-footer">
                  <button type="button" class="btn btn-default" @click.prevent="showMediaPicker = false">
                    Exit
                  </button>
                </div>
              </modal>
              <div class="row p-2">
                <div class="col-sm-12 text-center" v-if="!showSlim">
                  <button class="btn btn-sm btn-primary" @click.prevent="showSlim=true">Upload my own Photo
                    (recommended)
                  </button>
                </div>
                <div class="col-sm-12 text-center text-muted p-1/2 ">
                  - OR -
                </div>
                <div class="col-sm-12 text-center">
                  <button class="btn btn-sm btn-primary-hollow" @click.prevent="openMediaPicker">Choose a
                    Photo
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
		</div>
  </div>

<form @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
	<div class="panel panel-default">
		<div class="panel-body">
      <template v-if="firstUrlSegment === 'admin'">
        <div class="row">
          <div class="col-sm-4">
            <h5>Organizer</h5>
            <p class="text-muted">Every fundraiser has an organizer. This is the user who may edit the fudraiser and change it's settings.</p>
          </div>
          <div class="col-sm-8">
            <div class="form-group" :class="{'has-error' : form.errors.has('sponsor_id')}">
              <div class="col-xs-12">
                <label for="sponsor">Organizer</label>
                <v-select @keydown.enter.prevent="" 
                          class="form-control" 
                          v-model="userObj" 
                          :options="users"
                          :debounce="250"
                          :on-search="searchUsers" label="name" name="user">
                </v-select>
              </div>
            </div>
          </div>
        </div>
        <hr class="divider">
      </template>

      <div class="row">
				<div class="col-sm-4">
					<h5>Fundraiser Details</h5>
					<p class="text-muted">Update imporant details about your fundraiser.</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" :class="{'has-error' : form.errors.has('name')}">
						<div class="col-xs-12">
							<label for="name">Title</label>
							<span class="help-block">Give your fundraiser a name.</span>
							<input type="text"
							       class="form-control"
							       name="name"
							       id="name"
							       v-model="form.name"
							       debounce="250"
							       placeholder="Fundraiser Name"
							       maxlength="100"
							       minlength="1"
							       required>
              <span class="help-block" v-text="form.errors.get('name')" v-if="form.errors.has('name')"></span>
							<hr class="divider inv">
						</div>
					</div>
					<div class="from-group" :class="{'has-error' : form.errors.has('short_desc')}">
						<div class="col-xs-12">
							<label for="shortdescription">Short Description</label>
							<span class="help-block">Provide a brief summary about your fundraiser in one or two sentences.</span>
							<textarea class="form-control" name="shortdescription"
							          id="shortdescription"
							          v-model="form.short_desc"
							          v-autosize="form.short_desc"
							          style="resize: vertical;"
							          minlength="1"
							          rows="3">
              </textarea>
              <span class="help-block" v-text="form.errors.get('short_desc')" v-if="form.errors.has('short_desc')"></span>
              <hr class="divider inv">
						</div>
					</div>
					<div class="from-group">
						<div class="col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" v-model="form.show_donors"
									       @change="toggleDisplayDonors(form.show_donors)">
									Show the donations list (Recommended)
								</label>
								<span class="help-block">Seeing donation activity could inspire others to donate.</span>
                <hr class="divider inv">
							</div>
						</div>
					</div>
          <div class="form-group">
            <div class="col-xs-12">
              <div class="checkbox">
                <label>
                  <input type="checkbox" v-model="form.public" :disabled="!form.description">
                  Show Fundraiser to the Public (Highly Recommended)
                </label>
                <span class="help-block text-danger" v-if="!form.description">Your fundraiser must have content before you can make it public.</span>
                <span class="help-block" v-else>Private fundraisers can only be seen by you and cannot be shared.</span>
              </div>
            </div>
          </div>
				</div>
			</div>
			<hr class="divider">
			<div class="row">
				<div class="col-sm-12" :class="{'has-error' : form.errors.has('description')}">
					<h5>Fundraiser Description</h5>
					<p class="text-muted">An inspiring story with great photos or even a video is very important to get
						donations.</p>
          <span class="help-block" v-text="form.errors.get('description')" v-if="form.errors.has('description')"></span>
					<redactor-editor v-model.sync="form.description" 
                           name="body"
					                 :uuid="fundraiser.uuid">
          </redactor-editor>
				</div>
			</div>
		</div>

		<div class="panel-footer clearfix">
			<div class="form-group">
				<hr class="divider inv md">
				<div class="col-xs-12 text-right">
					<a :href="'/' + fundraiser.url" class="btn btn-link">Cancel</a>
					<button type="submit" :disabled="disabledSave" class="btn btn-primary">
            Save Changes
					</button>
					<hr class="divider inv md">
				</div>
			</div>
		</div>

	</div>
</form>
</div>
</template>
<style>
	.drag-handle {
		cursor: move;
	}
</style>
<script type="text/javascript">
  import vSelect from 'vue-select';
  import _ from 'underscore';
  import Slim from '../../vendors/slim.vue';
  import errorHandler from '../error-handler.mixin';
  import redactorEditor from '../redactor-editor.vue';

  export default {
    name: 'fundraisers-description',
    mixins: [errorHandler],
    components: {
      vSelect,
      redactorEditor,
      'slim-cropper': Slim
    },
    props: {
      fundraiser: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        form: new Form({
          name: '',
          short_desc: '',
          description: '',
          show_donors: true,
          sponsor_id: null,
          public: false
        }),
        users: [],
        userObj: null,
        disabledSave: false,
        fundraiserImageChoices: [],
        showSlim: false,
        showMediaPicker: false,
        slimAPI: null,
        slimOptions: {
          ratio: '16:9',
          push: true,
          statusFileType: 'image/bmp, image/jpg, image/jpeg, image/png, image/gif',
          instantEdit: false,
          service: this.slimService,
          didInit: this.slimInit
        },
        loadingText: 'Loading...'
      }
    },
    computed: {
      sponsor_id: {
        get() {
          return _.isObject(this.userObj) ? this.userObj.id : null;
        },
        set() {}
      },
    },
    watch: {
      sponsor_id(val) {
        this.form.sponsor_id = val;
      }
    },
    methods: {
      // Slim Methods
      slimInit(data, slim) {
        if (this.fundraiser.featured_image) {
          slim.load(this.fundraiser.featured_image, { blockPush: true });
				  this.showSlim = true;
        }
        this.slimAPI = slim;
      },
      slimService(formdata, progress, success, failure, slim) {
        progress(2, 10);
        this.$root.$emit('Save:DISABLED');
        this.uploadFeaturedImage(this.slimAPI.dataBase64.output.image).then((response) => {
          this.featured_image = response.data.url;
          progress(10, 10);
          this.$root.$emit('Save:ENABLED');
          success();
          swal("Good job!", "The featured photo has been updated.", "success", {
              button: true,
              timer: 3000
            });
        }).catch((error) => {
          this.$root.$emit('Save:ENABLED');
          failure();
        });
      },
      chooseImage(image) {
        this.loadingText = 'Saving... this might take a few minutes.';
        this.$root.$emit('Save:DISABLED');
        this.$http.post(`/fundraisers/${this.fundraiser.uuid}/media`, {
          featured: true,
          url: image.source,
        }).then((response) => {
          this.slimAPI.load(response.data.url, { blockPush: true });
          this.showSlim = true;
          this.showMediaPicker = false;
          this.$root.$emit('Save:ENABLED');
          this.loadText = 'Loading...'
          swal("Good job!", "The featured photo has been updated.", "success", {
              button: true,
              timer: 3000
            });
        });
      },
      uploadFeaturedImage(file) {
        return this.$http.post(`/fundraisers/${this.fundraiser.uuid}/media`, {
          featured: true,
          file
        }).catch((error) => {
          this.$root.$emit('Save:ENABLED');
          return error;
        });
      },
      searchUsers(search, loading) {
        loading(true);
        this.$http.get('users', {params: {search: search}}).then((response) => {
          this.users = response.data.data;
          loading(false);
        });
      },
      openMediaPicker() {
        this.$http.get();
        this.showMediaPicker = true;
      },
      onSubmit() {
        if ( !(this.featured_image || this.fundraiser.featured_image) ) {
          swal('Wait!', 'Please upload or choose a featured photo', 'error', {
            button: true,
            timer: 3000
          });
          return false;
        }

        if ( !this.form.public) {
          swal('WAIT!', 'Your fundraiser is currently set to private and not visible to the public. Do you want to make it public?', 'warning', {
                closeOnClickOutside: false,
                buttons: {
                    cancel: {
                        text: "No, Keep it Private",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, Make it Public",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.form.public = true;
                    this.save();
                    return false;
                } else {
                    this.save();
                    return false;
                }
            })
            return false;
        }

        this.save();
      },
      save() {
        this.$root.$emit('Save:DISABLED');

        this.form.submit('put', '/fundraisers/'+this.fundraiser.uuid)
          .then(data => {
            this.$emit('delete:removedFiles');
            swal("Good job!", "Your fundraiser has been updated.", "success", {
              button: true,
              timer: 3000
            });
            this.$root.$emit('Save:ENABLED');
          }).catch(error => {
            this.$root.$emit('Save:ENABLED');
            swal("Oops!", "Unable to save your changes. Please check the form.", "error", {
              button: true,
              timer: 3000
            });
          });
      },
      uploadsComplete(upload) {
        this.featured_image = upload;
        this.featured_image_id = upload.id;
        this.submit();
      },
    },
    mounted() {
      this.form.name = this.fundraiser.name;
      this.form.short_desc = this.fundraiser.short_desc;
      this.form.description = this.fundraiser.description;
      this.form.show_donors = this.fundraiser.show_donors;
      this.form.sponsor_id = this.fundraiser.sponsor_id;
      this.form.public = this.fundraiser.public;

      this.$http.get('users/' + this.fundraiser.sponsor_id).then((response) => {
        this.userObj = response.data.data;
      });

      for (let i = 1; i <= 8; i++) {
        this.fundraiserImageChoices.push({
          id: _.uniqueId(),
          meta: null,
          name: `fundraiser-image-${i}`,
          source: `/images/fundraising/photos/fundraiser-${i}.jpg`,
          tags: ['Fundraiser'],
          type: 'banner'
        });
      }

      this.$root.$on('Save:DISABLED', () => {
        this.disabledSave = true;
      });

      this.$root.$on('Save:ENABLED', () => {
        this.disabledSave = false;
      });
    }
  }
</script>