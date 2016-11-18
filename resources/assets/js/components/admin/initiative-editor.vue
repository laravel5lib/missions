<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5 v-if="edit">Details</h5>
                    <h5 v-else>Create an Initiative</h5>
                </div>
                <div class="col-xs-6 text-right" v-if="!editMode">
                    <button class="btn btn-xs btn-default-hollow" @click="editMode = true">
                        <i class="fa fa-pencil"></i> Edit
                    </button>
                </div>
                <div class="col-xs-6 text-right" v-else>
                    <button class="btn btn-xs btn-default" @click="cancel">
                        Cancel
                    </button>
                    <button class="btn btn-xs btn-primary" @click="save" v-if="edit">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button class="btn btn-xs btn-primary" @click="create" v-else>
                        <i class="fa fa-plus"></i> Create
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <label>Type</label>
                    <input class="form-control" v-model="initiative.type" v-if="editMode" />
                    <p v-else>{{ initiative.type }}</p>
                    <label>Country</label>
                    <v-select class="form-control"
                              id="country"
                              :value.sync="initiative.country.code"
                              :options="countries"
                              label="name"
                              v-if="editMode">
                    </v-select>
                    <p v-else>
                        {{ initiative.country.name }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <label>Start Date</label>
                    <input type="date" class="form-control" v-model="initiative.started_at" v-if="editMode">
                    <p v-else>{{ initiative.started_at | moment 'll' }}</p>
                    <label>End Date</label>
                    <input type="date" class="form-control" v-model="initiative.ended_at" v-if="editMode">
                    <p v-else>{{ initiative.ended_at | moment 'll' }}</p>
                </div>
            </div>

            <label>Short Description</label>
            <textarea class="form-control" v-model="initiative.short_desc" v-if="editMode" rows="10"></textarea>
            <p v-else>{{ initiative.short_desc }}</p>

        </div>

        <alert :show.sync="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="400px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{ message }}</p>
        </alert>

        <alert :show.sync="showError"
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
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import vSelect from 'vue-select';
    export default {
        name: 'initiative-editor',
        components: {
            'alert': VueStrap.alert,
            'v-select': vSelect
        },
        data() {
            return{
                initiative: {},
                countries: [],
                editMode: true,
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
            },
            'edit': {
                type: Boolean,
                required: false,
                default: false
            }
        },
        methods: {
            fetch () {
                this.$http.get('initiatives/' + this.id).then(function (response) {
                    this.initiative = response.data.data;
                });
            },
            save() {
                this.$http.put('initiatives/' + this.id, this.initiative).then(function (response) {
                    this.initiative = response.data.data;
                    this.editMode = false;
                    this.message = 'Your changes were saved successfully.';
                    this.showSuccess = true;
                }).error(function () {
                    this.message = 'Your changes could not be saved.';
                    this.showError = true;
                });
            },
            create() {
                this.$http.post('initiatives', this.initiative).then(function (response) {
                    this.initiative = {};
                    window.location.reload();
                }).error(function () {
                    this.message = 'The initiative could not be created.';
                    this.showError = true;
                });
            },
            cancel() {
                if (this.edit) {
                    this.fetch();
                    this.editMode = false;
                } else {
                    $('#initiativeEditor').modal('hide');
                }
            }
        },
        ready () {
            if (this.edit) {
                this.fetch();
                this.editMode = false;
            }

            <!--this.selectedCost.active_at = moment(cost.active_at).format('YYYY-MM-DD')-->

            this.$http.get('utilities/countries').then(function (response) {
				this.countries = response.data.countries;
			});
        }
    }
</script>