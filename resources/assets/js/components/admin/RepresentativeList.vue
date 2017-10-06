<style scoped>
    .tools {
        display: none;
    }
    .toolbar:hover .tools {
        display: inline-block;
    }
    table>tbody>tr>td {
        vertical-align: middle;
    }
</style>

<template>
<div>
    <div class="panel panel-default">
        <div class="panel-heading text-right">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#repForm" v-if="app.user.can.create_representatives">Add New</button>
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-md-4">Name</th>
                    <th class="col-md-3">Email</th>
                    <th class="col-md-2">Phone</th>
                    <th class="col-md-1">Ext.</th>
                    <th class="col-md-2"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="toolbar" v-for="(representative, index) in representatives" :key="representative.id">
                    <td class="col-md-4">
                        <img class="img-circle img-sm"
                             :src="representative.avatar_url"
                             width="50"
                             height="50"
                             style="margin-right: 1em">
                        {{ representative.name }}
                    </td>
                    <td class="col-md-3">{{ representative.email }}</td>
                    <td class="col-md-2">{{ representative.phone }}</td>
                    <td class="col-md-1">{{ representative.ext }}</td>
                    <td class="col-md-2 text-right">
                        <span class="tools">
                            <a href="#" data-toggle="modal" data-target="#repForm" @click="load(representative, index)" v-if="app.user.can.update_representatives">Edit</a>
                            <span v-if="app.user.can.delete_representatives"> |
                                <a href="#" data-toggle="modal" data-target="#deleteRep" @click="load(representative, index)">Remove</a>
                            </span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="repForm">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{ ui.modal.title }}</h4>
          </div>
          <div class="modal-body clearfix">
            <form id="createRepForm" novalidate>
                <div class="form-group" v-error-handler="{ value: representative.name, handle: 'name' }">
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
                <div class="form-group" v-error-handler="{ value: representative.email, handle: 'email' }">
                     <div class="col-xs-12">
                        <label for="email" class="control-label" placeholder="john@missions.me">Email</label>
                        <input type="email" class="form-control" name="email" id="email" v-model="representative.email">
                    </div>
                </div>
                <div class="form-group" v-error-handler="{ value: representative.phone, handle: 'phone' }">
                     <div class="col-xs-8">
                        <label for="phone">Phone</label>
                        <phone-input v-model="representative.phone" name="phone" id="phone"></phone-input>
                    </div>
                    <div class="col-xs-4">
                        <label for="ext" class="control-label">Extension</label>
                        <input type="text" class="form-control" name="ext" id="ext" v-model="representative.ext" placeholder="1011">
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-link" @click="cancel()">Cancel</button>
            <button type="button" class="btn btn-primary" @click="add()" v-if="!ui.modal.edit">Create</button>
            <button type="button" class="btn btn-primary" @click="update()" v-if="ui.modal.edit">Save Changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteRep" id="deleteRep">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this trip rep?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Keep</button>
                <button type="button" class="btn btn-primary" @click="remove()">Delete</button>
            </div>
        </div>
      </div>
    </div>

    <div class="text-center">
        <pagination :pagination="pagination" pagination-key="pagination" :callback="getRepresentatives"></pagination>
    </div>

</div>
</template>

<script>
export default {

  data () {
    return {
        app: MissionsMe,
        ui: {
            modal: {
                title: 'Add New Trip Rep',
                edit: false
            }
        },
        representatives: [],
        representative: {
            name: '',
            email: '',
            phone: '',
            ext: ''
        },
        currentIndex: null,
        pagination: {current_page: 1}
    };
  },

  methods: {
    getRepresentatives() {
        this.$http.get('representatives').then((response) => {
            this.representatives = response.data.data;
            this.pagination = response.data.meta.pagination;
        });
    },
    load(representative, index) {
        this.ui.modal.title = "Update Trip Rep";
        this.ui.modal.edit = true;

        this.representative = representative;
        this.currentIndex = index;
    },
    add() {
        this.$http.post('representatives', this.representative).then((response) => {
            this.representatives.push(response.data.data);
            this.cancel();
            this.$root.$emit('showSuccess', 'New Trip Rep added.');
        });
    },
    remove() {
        this.$http.delete('representatives/' + this.representative.id).then((response) => {
            this.representatives.splice(this.currentIndex, 1);
            $('#deleteRep').modal('hide');
            this.$root.$emit('showSuccess', 'Trip Rep deleted.');
            this.cancel();
        });
    },
    update() {
        this.$http.put('representatives/' + this.representative.id, this.representative).then((response) => {
            this.cancel();
            this.$root.$emit('showSuccess', 'Trip Rep updated.');
        });

        this.cancel();
    },
    cancel() {
        $('#repForm').modal('hide');

        this.ui.modal.title = "Add New Representative";
        this.ui.modal.edit = false;

        this.currentIndex = null;

        this.representative = {
            name: '',
            email: '',
            phone: '',
            ext: ''
        }
    }
  },

  mounted() {
    this.getRepresentatives();
  }
};
</script>