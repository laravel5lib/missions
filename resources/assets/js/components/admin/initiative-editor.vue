<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12">
                    <h5>Details
                        <span class="pull-right text-muted" v-if="! newOnly && ! readOnly">
                            <tooltip effect="scale" placement="bottom" content="Edit">
                                <i class="fa fa-lg fa-cog" @click="editMode = !editMode"></i>
                            </tooltip>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <spinner ref="spinner" size="md" text="Loading..."></spinner>
            <div class="row">
                <div class="col-sm-6">
                    <label>Type</label>
                    <input class="form-control" v-model="initiative.type" v-if="editMode" />
                    <p v-else>{{ initiative.type }}</p>
                    <label>Country</label>
                    <select class="form-control"
                            v-model="initiative.country.code"
                            v-if="editMode">
                        <option :value="country.code" v-for="country in countries">{{ country.name }}</option>
                    </select>
                    <p v-else>
                        {{ initiative.country.name }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <label>Start Date</label>
                    <input type="text" class="form-control" v-model="initiative.started_at" v-if="editMode">
                    <p v-else>{{ initiative.started_at | moment('ll') }}</p>
                    <label>End Date</label>
                    <input type="text" class="form-control" v-model="initiative.ended_at" v-if="editMode">
                    <p v-else>{{ initiative.ended_at | moment('ll') }}</p>
                </div>
            </div>

            <label>Short Description</label>
            <textarea class="form-control" v-model="initiative.short_desc" v-if="editMode" rows="10"></textarea>
            <p v-else>{{ initiative.short_desc }}</p>

        </div>
        <div class="panel-footer text-right" v-if="editMode">
            <button class="btn btn-default" @click="cancel">Cancel</button>
            <button class="btn btn-primary" @click="save" v-if="! newOnly">Save</button>
            <button class="btn btn-primary" @click="create" v-else>Create</button>
        </div>

    </div>
</template>
<script>
    export default {
        name: 'initiative-editor',
        data() {
            return{
                initiative: {
                    country: {
                        code: null
                    }
                },
                cause: {},
                countries: [],
                editMode: true,
                loading: false
            }
        },
        props: {
            'id': {
                type: String,
                required: false,
                default: null
            },
            'causeId': {
                type: String,
                default: null
            },
            'readOnly': {
                type: Boolean,
                default: false
            },
            'newOnly': {
                type: Boolean,
                default: false
            }
        },
        methods: {
            getCause() {
                // this.$refs.spinner.show();
                this.$http.get('causes/' + this.causeId).then((response) => {
                    this.cause  = response.data.data;
                    this.countries = this.cause.countries;
                    // this.$refs.spinner.hide();
                });
            },
            fetch () {
                // this.$refs.spinner.show();
                this.$http.get('initiatives/' + this.id, { params: {include: 'cause'} }).then((response) => {
                    this.cause = response.data.data.cause.data;
                    this.countries = response.data.data.cause.data.countries;
                    this.initiative = _.omit(response.data.data, 'cause');
                    // this.$refs.spinner.hide();
                });
            },
            save() {
                this.initiative.country_code = this.initiative.country.code;
                this.$http.put('initiatives/' + this.id, this.initiative).then((response) => {
                    this.initiative = response.data.data;
                    this.editMode = false;
                    this.$root.$emit('showSuccess', 'Your changes were saved successfully.');
                },() =>  {
                    this.$root.$emit('showError', 'Your changes could not be saved.');
                });
            },
            create() {
                this.initiative.country_code = this.initiative.country.code;
                this.initiative.project_cause_id = this.causeId;
                this.$http.post('initiatives', this.initiative).then((response) => {
                    this.initiative = {};
                    window.location = '/admin/initiatives/' + response.data.data.id;
                },() =>  {
                    this.$root.$emit('showError', 'The initiative could not be created.');
                });
            },
            cancel() {
                if ( ! this.newOnly) {
                    this.fetch();
                    this.editMode = false;
                } else {
                    window.location = '/admin/causes/' + this.causeId + '/current-initiatives';
                }
            }
        },
        ready () {
            if ( ! this.newOnly) {
                this.fetch();
                this.editMode = false;
            } else {
                this.editMode = true;
                this.getCause();
            }
        }
    }
</script>