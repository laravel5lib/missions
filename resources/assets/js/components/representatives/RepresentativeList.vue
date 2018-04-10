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
            <button class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#repForm"
                    v-if="app.user.can.create_representatives">
                Add New
            </button>
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
                <tr class="toolbar"
                    v-for="(representative, index) in representatives"
                    :key="representative.id">

                    <td class="col-md-4">
                        <img class="img-circle img-sm"
                             :src="representative.avatar_url"
                             width="50"
                             height="50"
                             style="margin-right: 1em">
                        {{ representative.name }}
                    </td>
                    <td class="col-md-3">{{ representative.email }}</td>
                    <td class="col-md-2">{{ representative.phone | phone }}</td>
                    <td class="col-md-1">{{ representative.ext }}</td>
                    <td class="col-md-2 text-right">
                        <span class="tools">
                            <a :href="baseUrl + representative.id"
                                v-if="app.user.can.update_representatives">
                                Edit
                            </a>
                            <span v-if="app.user.can.delete_representatives"> |
                                <a href="#"
                                   data-toggle="modal"
                                   data-target="#deleteRep"
                                   @click="load(representative, index)">
                                   Remove
                               </a>
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

            <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <h4 class="modal-title">Add New Trip Rep</h4>

          </div>
          <div class="modal-body clearfix">

            <form id="createRepForm" novalidate data-vv-scope="create-rep">
                <div class="form-group"
                     v-error-handler="{ value: representative.name, handle: 'name', scope: 'create-rep' }">

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
                     v-error-handler="{ value: representative.email, handle: 'email', scope: 'create-rep' }">

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
                          v-error-handler="{ value: representative.phone, handle: 'phone', scope: 'create-rep' }">

                        <label for="phone">Phone</label>
                        <phone-input v-model="representative.phone"
                                     v-validate="'required'"
                                     data-vv-value-path="value"
                                     data-vv-scope="create-rep"
                                     name="phone"
                                     id="phone">
                        </phone-input>
                    </div>
                    <div class="col-xs-4" v-error-handler="{ value: representative.ext, handle: 'ext', scope: 'create-rep' }">
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
          <div class="modal-footer">
            <button type="button" class="btn btn-link" @click="cancel()">Cancel</button>
            <button type="button" class="btn btn-primary" @click="add()">Add</button>
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

<script type="text/javascript">
    import errorHandler from '../error-handler.mixin';

    export default {
        mixins: [errorHandler],

        data: function () {
            return {
                app: MissionsMe,
                baseUrl: '/admin/representatives/',
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
            this.$http.get('representatives?page=' + this.pagination.current_page).then((response) => {
                this.representatives = response.data.data;
                this.pagination = response.data.meta.pagination;
            });
        },
        load(representative, index) {
            this.representative = representative;
            this.currentIndex = index;
        },
        add() {
            this.$validator.validateAll('create-rep').then(result => {
                if (result) {
                    this.$http.post('representatives', this.representative).then((response) => {
                        this.representatives.push(response.data.data);
                        this.pagination.total = this.pagination.total + 1;
                        this.cancel();
                        this.$root.$emit('showSuccess', 'New Trip Rep added.');
                    }).catch(this.$root.handleApiError);
                } else {
                    this.$root.$emit('showError', 'Please check the form.');
                }
            });

        },
        remove() {
            this.$http.delete('representatives/' + this.representative.id).then((response) => {
                this.representatives.splice(this.currentIndex, 1);
                this.pagination.total = this.pagination.total - 1;
                $('#deleteRep').modal('hide');
                this.$root.$emit('showSuccess', 'Trip Rep deleted.');
                this.cancel();
            }).catch(this.$root.handleApiError);
        },
        cancel() {
            $('#repForm').modal('hide');

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