<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5 v-if="id">Details</h5>
                    <h5 v-else>Create a Cause</h5>
                </div>
                <div class="col-xs-6 text-right" v-if="id && !editMode">
                    <button class="btn btn-xs btn-default-hollow" @click="editMode = true">
                            <i class="fa fa-pencil"></i> Edit
                    </button>
                </div>
                <div class="col-xs-6 text-right" v-else>
                    <button class="btn btn-xs btn-default" @click="cancel">
                        Cancel
                    </button>
                    <button class="btn btn-xs btn-primary" @click="save" v-if="editMode && id">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button class="btn btn-xs btn-primary" @click="create" v-else>
                        <i class="fa fa-plus"></i> Create
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <label>Name</label>
            <input class="form-control" name="name" v-model="cause.name" v-if="editMode" />
            <p v-else>{{ cause.name }}</p>
            <label>Countries</label>
            <v-select @keydown.enter.prevent=""  class="form-control"
                      multiple name="countries"
                      id="country"
                      :value="cause.countries"
                      :options="UTILITIES.countries"
                      v-model="cause.countries"
                      label="name"
                      v-if="editMode">
            </v-select>
            <p v-else>
                <span class="label label-default" style="margin-right: 1em" v-for="country in cause.countries">
                    {{ country.name }}
                </span>
            </p>
            <label>Description</label>
            <textarea class="form-control" v-model="cause.short_desc" v-if="editMode" rows="10"></textarea>
            <p v-else>{{ cause.short_desc }}</p>
        </div>

        <alert v-model="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="400px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{ message }}</p>
        </alert>

        <alert v-model="showError"
               placement="top-right"
               :duration="6000"
               type="danger"
               width="400px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>{{ message }}</p>
        </alert>

    </div>
</template>
<script>
    import utilities from '../utilities.mixin'
    import vSelect from 'vue-select';
    export default {
        name: 'cause-editor',
        mixins: [utilities],
        components: {vSelect},
        data() {
            return{
                cause: {},
                editMode: false,
                showSuccess: false,
                showError: false,
                message: ''
            }
        },
        props: {
            'id': {
                type: String,
                required: false,
                default: null
            }
        },
        methods: {
            fetch () {
                this.$http.get('causes/' + this.id).then((response) => {
                    this.cause = response.data.data;
                });
            },
            save() {
                this.$http.put('causes/' + this.id, this.cause).then((response) => {
                    this.cause = response.data.data;
                    this.editMode = false;
                    this.message = 'Your changes were saved successfully.';
                    this.showSuccess = true;
                },() =>  {
                    this.message = 'Your changes could not be saved.';
                    this.showError = true;
                });
            },
            create() {
                this.$http.post('causes', this.cause).then((response) => {
                    this.cause = {};
                    window.location.reload();
                },() =>  {
                    this.message = 'The cause could not be created.';
                    this.showError = true;
                });
            },
            cancel() {
                if (this.id) {
                    this.fetch();
                    this.editMode = false;
                } else {
                    $('#causeEditor').modal('hide');
                }
            }
        },
        mounted() {
            if (this.id) {
                this.fetch();
            } else {
                this.editMode = true;
            }
            this.getCountries();
        }
    }
</script>