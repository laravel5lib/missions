<template>
<div>

    <div class="panel panel-default">
      <div class="panel-heading">
          <h5>Update Photo</h5>
      </div>
      <div class="panel-body">
        <uploader type="avatar" hide-submit :initial-image="representative.avatar_url" :name="representative.name + '_avatar' || 'rep_avatar'" lock-type ui-locked :ui-selector="2" is-child :tags="['User']" @uploads-complete="uploadComplete"></uploader>
      </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Update Details</h5>
        </div>
        <div class="panel-body">
            <form id="editRepForm" novalidate data-vv-scope="update-rep">
                <div class="form-group"
                     v-error-handler="{ value: representative.name, handle: 'name', scope: 'update-rep' }">

                    <div class="col-xs-12">
                        <label for="name">Name</label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="name"
                               v-model="representative.name"
                               debounce="250"
                               placeholder="John Doe"
                               v-validate="'required|min:1|max:100'"
                               maxlength="100"
                               minlength="1"
                               required>
                    </div>

                </div>
                <div class="form-group"
                     v-error-handler="{ value: representative.email, handle: 'email', scope: 'update-rep' }">

                     <div class="col-xs-12">
                        <label for="email" class="control-label" placeholder="john@missions.me">Email</label>
                        <input type="email"
                               class="form-control"
                               name="email"
                               id="email"
                               v-validate="'required|email'"
                               v-model="representative.email">
                    </div>

                </div>
                <div class="form-group">
                     <div class="col-xs-8"
                          v-error-handler="{ value: representative.phone, handle: 'phone', scope: 'update-rep' }">

                        <label for="phone">Phone</label>
                        <phone-input v-model="representative.phone"
                                     v-validate="'required'"
                                     data-vv-value-path="value"
                                     data-vv-scope="update-rep"
                                     name="phone"
                                     id="phone">
                        </phone-input>
                    </div>
                    <div class="col-xs-4" >
                        <label for="ext" class="control-label">Extension</label>
                        <input type="text"
                               class="form-control"
                               v-validate="'numeric|max:4'"
                               name="ext"
                               id="ext"
                               v-model="representative.ext"
                               placeholder="1011">
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <a :href="baseUrl" class="btn btn-link">Cancel</a>
            <button type="button" class="btn btn-primary" @click="update()">Save Changes</button>
        </div>
    </div>
</div>
</template>

<script type="text/javascript">
  import errorHandler from '../error-handler.mixin';

  import uploader from '../uploads/admin-upload-create-update.vue';

  export default {
    mixins: [errorHandler],

    components: {'uploader': uploader},

    data: function () {
      return {
        app: MissionsMe,
        baseUrl: '/admin/representatives/',
        ui: {}
      };
    },

    props: {
      representative: {
        type: Object,
        required: true
      }
    },

    methods: {
      update() {
        this.$validator.validateAll('update-rep').then(result => {
          if (result) {
            this.$http.put('representatives/' + this.representative.id, this.representative).then((response) => {
                swal("Good job!", "The trip rep was updated.", "success", {
                        button: true,
                        timer: 3000
                    });
            }).catch(this.$root.handleApiError);
          } else {
              this.$root.$emit('showError', 'Please check the form.');
          }
        });
      },
        uploadComplete(upload) {
          this.$http.post(`representatives/${this.representative.id}/avatar`, {
              name: upload.name,
              path: upload.source
          }).then((response) => {
              this.$root.$emit('showSuccess', 'Trip Rep\'s Avatar updated.');
          }).catch(this.$root.handleApiError);
        }
    },

    mounted() {

    }
  };
</script>