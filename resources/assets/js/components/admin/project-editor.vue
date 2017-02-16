<template>
    <div class="panel panel-default">
        <spinner v-ref:loader size="xl" :fixed="false" text="Loading..."></spinner>
        <div class="panel-heading">
            <h5>
                Details
                <span class="pull-right text-muted" v-if="! newOnly">
                    <tooltip effect="scale" placement="bottom" content="Edit">
                        <i class="fa fa-lg fa-cog" @click="editMode = !editMode"></i>
                    </tooltip>
                </span>
            </h5>
        </div>
        <div class="panel-body">
            <div :class="{ 'col-md-7': !editMode, 'col-md-12' : editMode}">
                <div class="row" v-if="! newOnly">
                    <div class="col-md-12">
                        <label>Project ID</label>
                        <p>{{ project.id }}</p>
                    </div>
                    <hr class="divider">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Project Name</label>
                        <input type="text" v-model="project.name" class="form-control" v-if="editMode">
                        <p v-else>{{ project.name }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-12">
                        <label>Cause</label>
                        <p>{{ cause.name }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Country</label>
                        <select class="form-control" v-model="initiative.country.code" v-if="editMode">
                            <option v-for="country in cause.countries" :value="country.code">{{ country.name }}</option>
                        </select>
                        <p v-else>{{ initiative.country.name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Type</label>
                        <select class="form-control" v-model="project.project_initiative_id" v-if="editMode">
                            <option v-for="initiative in availableInitiatives" :value="initiative.id">{{ initiative.type }}</option>
                        </select>
                        <p v-else>{{ initiative.type }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Sponsor Name</label>
                        <v-select class="form-control"
                                  id="sponsor"
                                  :value.sync="selectedSponsor"
                                  :options="sponsors"
                                  :on-search="getSponsors"
                                  label="name"
                                  v-if="editMode">
                        </v-select>
                        <p v-else>{{ sponsor.name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Sponsor Type</label>
                        <select class="form-control" v-model="project.sponsor_type" v-if="editMode" @change="selectedSponsor = null">
                            <option value="users">Individual</option>
                            <option value="groups">Group</option>
                        </select>
                        <p v-else>{{ project.sponsor_type == 'users' ? 'Individual' : 'Group' }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row" v-if="editMode">
                    <div class="col-md-6">
                        <label>Plaque Prefix</label>
                        <select class="form-control" v-model="project.plaque_prefix">
                            <option>in honor of</option>
                            <option>in memory of</option>
                            <option>sponsored by</option>
                            <option>on behalf of</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Plaque Message</label>
                        <input class="form-control" type="text" v-model="project.plaque_message">
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-md-12">
                        <label>Plaque Message</label>
                        <p>{{ plaque }}</p>
                    </div>
                </div>
                <div class="row" v-if="! newOnly">
                    <hr class="divider">
                    <div class="col-md-6">
                        <label>Started At</label>
                        <p>{{ project.created_at | moment 'll' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Funded At</label>
                        <p>{{ funded }}</p>
                    </div>
                </div>
                <div class="row" v-if="! newOnly">
                    <hr class="divider">
                    <div class="col-md-6">
                        <label>Last Updated</label>
                        <p>{{ project.updated_at | moment 'll' true }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 panel panel-default panel-body text-center" v-if="!editMode">
                <label>Email</label>
                <p>{{ sponsor.email }}</p>
                <label>Home Phone</label>
                <p>{{ sponsor.phone_one }}</p>
                <label>Mobile Phone</label>
                <p>{{ sponsor.phone_two }}</p>
                <label>Address</label>
                <p>{{ sponsor.address }}</p>
                <label>City</label>
                <p>{{ sponsor.city }}</p>
                <label>State/Providence</label>
                <p>{{ sponsor.state }}</p>
                <label>Zip/Postal Code</label>
                <p>{{ sponsor.zip }}</p>
                <label>Country</label>
                <p>{{ sponsor.country_name }}</p>
            </div>
        </div>
        <div class="panel-footer text-right" v-if="editMode">
            <button class="btn btn-default" @click="cancel">Cancel</button>
            <button class="btn btn-primary" @click="save" v-if="! newOnly">Save</button>
            <button class="btn btn-primary" @click="create" v-else>Create</button>
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

    </div><!-- end panel -->
</template>
<script>
    import vSelect from 'vue-select';
    export default{
        name: 'project-editor',
        components:{ vSelect },
        props: {
            'id': {
                type: String,
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
        watch: {
            'initiative.country.code'(val, oldVal) {
                this.getInitiatives();
            },
            'sponsor'(val, oldVal) {
                this.selectedSponsor = val;
            },
            'selectedSponsor'(val, oldVal) {
               val ? this.project.sponsor_id = val.id : this.project.sponsor_id = oldVal.id;
            }
        },
        data(){
            return{
                project: {
                    sponsor_type: 'users',
                    costs: []
                },
                sponsor: {},
                initiative: {
                    country: {
                        code: null
                    }
                },
                availableInitiatives: [],
                cause: {},
                editMode: false,
                sponsors: [],
                selectedSponsor: null
            }
        },
        computed: {
            'plaque': function() {
                return this.project.plaque_prefix + ' ' + this.project.plaque_message
            },
            'funded': function() {
                return this.project.funded_at ? this.project.funded_at.moment('ll') : 'In progress.'
            }
        },
        methods: {
            getInitiatives() {
                this.$http.get('causes/' + this.cause.id + '/initiatives?include=costs.payments', {
                    country: this.initiative.country.code
                }).then(function (response) {
                    this.availableInitiatives = response.data.data;
                });
            },
            getSponsors(search, loading) {
                loading(true);
                this.$http.get(this.project.sponsor_type, {search: search}).then(function (response) {
                    this.sponsors = response.data.data;
                    loading(false);
                });
            },
            getCause() {
                this.$refs.loader.show();
                this.$http.get('causes/' + this.causeId).then(function (response) {
                    this.cause  = response.data.data;
                    this.initiative.country = _.first(this.cause.countries);
                    this.$refs.loader.hide();
                });
            },
            fetch() {
                this.$refs.loader.show();
                this.$http.get('projects/' + this.id, {include: 'initiative.cause,sponsor'}).then(function (response) {
                    var arr = response.data.data;
                    this.cause = _.omit(arr.initiative.data.cause.data, 'initiatives');
                    this.initiative = arr.initiative.data;
                    this.sponsor = arr.sponsor.data;
                    this.project = _.omit(arr, ['initiative', 'sponsor']);
                    this.$refs.loader.hide();
                });
            },
            save() {
                this.$refs.loader.show();
                this.$http.put('projects/' + this.id, this.project).then(function (response) {
                    this.editMode = false;
                    this.$refs.loader.hide();
                    this.$dispatch('showSuccess', 'Your changes were saved successfully.');
                }).error(function() {
                    this.$refs.loader.hide();
                    this.$dispatch('showError', 'There are problems with the form.');
                });
            },
            create() {
                this.$refs.loader.show();
                this.$http.post('projects', this.project).then(function (response) {
                    this.$refs.loader.hide();
                    window.location = '/admin/projects/' + response.data.data.id;
                }).error(function() {
                    this.$refs.loader.hide();
                    this.$dispatch('showError', 'There are problems with the form.');
                });
            },
            cancel() {
                if ( ! this.newOnly) {
                    this.editMode = false;
                    this.fetch();
                } else {
                     window.location = '/admin/causes/' + this.causeId + '/current-projects';
                }
            }
        },
        ready() {
            if( ! this.newOnly) {
                this.fetch();
            }

            if(this.newOnly) {
                this.getCause();
                this.editMode = true;
            }
        }
    }
</script>