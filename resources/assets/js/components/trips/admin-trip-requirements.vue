<template>
    <spinner v-ref:spinner size="md" text="Loading"></spinner>
    <div class="panel-body" v-for="requirement in requirements">
        <div class="row">
            <div class="col-xs-12 text-right hidden-xs">
                <a class="btn btn-xs btn-default-hollow small" @click="editRequirement(requirement)"><i class="fa fa-pencil"></i> Edit</a>
                <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(requirement)"><i class="fa fa-trash"></i> Delete</a>
            </div>
            <div class="col-xs-12 text-center visible-xs">
                <a class="btn btn-xs btn-default-hollow small" @click="editRequirement(requirement)"><i class="fa fa-pencil"></i> Edit</a>
                <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(requirement)"><i class="fa fa-trash"></i> Delete</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <h5>{{ requirement.name|capitalize }}</h5>
                <h6><small>Type: {{ requirement.document_type }}</small></h6>
                <p>{{ requirement.short_desc }}</p>
            </div>
            <div class="col-xs-4 text-right">
                <h5><i class="fa fa-calendar"></i> {{ requirement.due_at|moment 'lll' }} </h5>
                <h6><small>Grace Period: {{ requirement.grace_period }} {{ requirement.grace_period > 1 ? 'days' : 'day' }}</small></h6>
            </div>
        </div><!-- end row -->
        <hr class="divider">
    </div>
    <modal class="text-center" :show.sync="showAddModal" title="Add Requirement">
        <div slot="modal-body" class="modal-body">
            <validator name="TripRequirementsCreate">
                <form class="form" novalidate>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForAddError('name')}">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control input-sm" v-model="newRequirement.name" v-validate:name="{ required: true }">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="type">Document Type</label>
                                        <select id="type" class="form-control input-sm" v-model="newRequirement.document_type">
                                            <option value="">-- select --</option>
                                            <option :value="type.value" v-for="type in documentTypes">{{type.text}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForAddError('grace') }">
                                        <label for="grace_period">Grace Period</label>
                                        <div class="input-group input-group-sm" :class="{'has-error': checkForAddError('grace') }">
                                            <input id="grace_period" type="number" class="form-control" number v-model="newRequirement.grace_period"
                                                   v-validate:grace="{required: true, min:0}">
                                            <span class="input-group-addon">Days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForAddError('due')}">
                                        <label for="due_at">Due</label>
                                        <date-picker :input-sm="true" :model.sync="newRequirement.due_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                        <input type="datetime" id="due_at" class="form-control input-sm hidden"
                                               v-model="newRequirement.due_at" v-validate:due="{required: true}">
                                    </div>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="shortDesc">Short Description</label>
                                        <textarea id="shortDesc" class="form-control" v-model="newRequirement.short_desc" maxlength="120"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </validator>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetRequirement()'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='addRequirement'>Add</button>
        </div>
    </modal>
    <modal class="text-center" :show.sync="showEditModal" title="Edit Requirement">
        <div slot="modal-body" class="modal-body">
            <validator name="TripRequirementsEdit">
                <form class="form" novalidate v-if="selectedRequirement">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForEditError('name')}">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control input-sm" v-model="selectedRequirement.name" v-validate:name="{ required: true }">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="type">Document Type</label>
                                        <select id="type" class="form-control input-sm" v-model="selectedRequirement.document_type">
                                            <option value="">-- select --</option>
                                            <option :value="type.value" v-for="type in documentTypes">{{type.text}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForEditError('grace') }">
                                        <label for="grace_period">Grace Period</label>
                                        <div class="input-group input-group-sm" :class="{'has-error': checkForEditError('grace') }">
                                            <input id="grace_period" type="number" class="form-control" number v-model="selectedRequirement.grace_period"
                                                   v-validate:grace="{required: true, min:0}">
                                            <span class="input-group-addon">Days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForEditError('due')}">
                                        <label for="due_at">Due</label>
                                        <date-picker :input-sm="true" :model.sync="selectedRequirement.due_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                        <input type="datetime" id="due_at" class="form-control input-sm hidden"
                                               v-model="selectedRequirement.due_at" v-validate:due="{required: true}">
                                    </div>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="shortDesc">Short Description</label>
                                        <textarea id="shortDesc" class="form-control" v-model="selectedRequirement.short_desc"maxlength="120"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </validator>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, resetRequirement()'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='updateRequirement'>Update</button>
        </div>
    </modal>
    <modal class="text-center" :show.sync="showDeleteModal" title="Delete Requirement" small="true">
        <div slot="modal-body" class="modal-body text-center" v-if="selectedRequirement">Delete {{ selectedRequirement.name }}?</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='showDeleteModal = false'>Keep</button>
            <button type="button" class="btn btn-primary btn-sm" @click='showDeleteModal = false,remove(selectedRequirement)'>Delete</button>
        </div>
    </modal>

</template>
<script type="text/javascript">
    export default{
        name: 'admin-trip-requirements',
        props: ['id', 'requester'],
        data(){
            return{
                requirements:[],
                selectedRequirement: null,
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                attemptedAddRequirement: false,
                attemptedEditRequirement: false,
                newRequirement: {
                    requester_id: this.id,
                    requester_type: this.requester,
                    name: '',
                    document_type: '',
                    due_at: null,
                    grace_period: 0,
                    short_desc: null,
                },
                resource: this.$resource('requirements{/id}'),
                documentTypes: [
                    {value: 'airport_preferences', text: 'Airport Preference'},
                    {value: 'arrival_designations', text: 'Arrival Designation'},
                    {value: 'influencer_applications', text: 'Influencer Application'},
                    {value: 'media_credentials', text: 'Media Credentials'},
                    {value: 'medical_credentials', text: 'Medical Credentials'},
                    {value: 'medical_releases', text: 'Medical Release'},
                    {value: 'minor_releases', text: 'Minor Release'},
                    {value: 'passports', text: 'Passport'},
                    {value: 'referrals', text: 'Referral'},
                    {value: 'essays', text: 'Testimony'},
                    {value: 'visas', text: 'Visa'}
                ],
                sort: 'due_at',
                direction: 'asc'
            }
        },
        methods:{
            checkForAddError(field){
                return this.$TripRequirementsCreate[field.toLowerCase()].invalid && this.attemptedAddRequirement;
            },
            checkForEditError(field){
                return this.$TripRequirementsEdit[field.toLowerCase()].invalid && this.attemptedEditRequirement;
            },
            resetRequirement(){
                this.newRequirement = {
                    requester_id: this.id,
                    requester_type: 'trips',
                    name: '',
                    document_type: '',
                    due_at: null,
                    grace_period: 0,
                    short_desc: null,
                };
            },
            addRequirement(){
                this.attemptedAddRequirement = true;
                if(this.$TripRequirementsCreate.valid) {
                    // this.$refs.spinner.show();
                    this.resource.save({}, this.newRequirement).then(function (response) {
                        this.requirements.push(response.body.data);
                        this.resetRequirement();
                        this.attemptedAddRequirement = false;
                        this.showAddModal = false;
                        this.searchRequirements();
                    })
                }
            },
            updateRequirement(){
                this.attemptedEditRequirement = true;
                if(this.$TripRequirementsEdit.valid) {
                    // this.$refs.spinner.show();
                    this.resource.update({ id: this.selectedRequirement.id}, this.selectedRequirement).then(function (response) {
                        this.attemptedEditRequirement = false;
                        this.showEditModal = false;
                        this.searchRequirements();
                    })
                }
            },
            editRequirement(requirement){
                this.selectedRequirement = requirement;
                this.selectedRequirement.due_at = moment(requirement.due_at).format('YYYY-MM-DD');
                this.showEditModal = true;
            },
            confirmRemove(requirement) {
                this.selectedRequirement = requirement;
                this.showDeleteModal = true;
            },
            remove(requirement){
                // this.$refs.spinner.show();
                this.resource.delete({ id: requirement.id }).then(function (response) {
                    this.requirements.$remove(requirement);
                    this.selectedRequirement = null;
                    this.searchRequirements();
                });
            },
            searchRequirements(){
                // this.$refs.spinner.show();
                this.resource.get({
                    requester: this.requester + '|' + this.id,
                    search: this.search,
                    sort: this.sort + '|' + this.direction,
                }).then(function (response) {
                    this.requirements = response.body.data;
                    this.$refs.spinner.hide()
                });
            },

        },
        ready(){
            this.searchRequirements();
            var self = this;
            this.$root.$on('NewRequirement', function () {
                self.showAddModal = true;
            })
        }
    }
</script>