<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="CreateUpdateMedicalRelease" @touched="onTouched">
        <form id="CreateUpdateMedicalRelease" class="form-horizontal" novalidate>
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-8">
                            <h5 class="panel-header">Basic Information</h5>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': checkForError('name') }">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" class="form-control" name="name" id="name" v-model="name"
                                       placeholder="Name" v-validate:name="{ required: true, minlength:1 }"
                                       minlength="1" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': checkForError('provider') }">
                                <label for="ins_provider" class="control-label">Insurance Provider</label>
                                <input type="text" class="form-control" name="ins_provider" id="ins_provider" v-model="ins_provider"
                                       placeholder="Insurance Provider" v-validate:provider="{ required: true, minlength:1, maxlength:100 }"
                                       maxlength="100" minlength="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div :class="{ 'has-error': checkForError('policy') }">
                                <label for="ins_policy_no" class=control-label">Insurance Policy Number</label>
                                <input type="text" class="form-control" name="ins_policy_no" id="ins_policy_no" v-model="ins_policy_no"
                                       placeholder="Insurance Policy Number" v-validate:policy="{ required: true, minlength:1 }"
                                       maxlength="100" minlength="1" required>
                            </div>
                        </div>
                    </div>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-8">
                                    <h5 class="panel-header">Conditions</h5>
                                </div>
                                <div class="col-xs-4 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="list-group">
                            <div class="list-group-item" v-for="condition in conditionsList|orderBy 'name'">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="condition.selected"> {{condition.name}}
                                    </label>
                                </div>
                                <div class="row" v-if="condition.selected">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="condition.medication"> Have Medication?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="condition.diagnosed"> Medically Diagnosed by Doctor?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item" v-for="condition in additionalConditionsList">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="condition.selected"> {{condition.name}}
                                    </label>
                                </div>
                                <div class="row" v-if="condition.selected">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="condition.medication"> Have Medication?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="condition.diagnosed"> Medically Diagnosed by Doctor?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item text-center">
                                <button class="btn btn-xs btn-default-hollow" type="button" data-toggle="collapse"
                                        data-target="#newCondition" aria-expanded="false" aria-controls="newCondition">
                                    <i class="icon-left fa fa-plus"></i> Add Unlisted Condition
                                </button>
                            </div>
                            <div class="collapse" id="newCondition">
                                <div class="list-group-item">
                                    <validator name="NewCondition">
                                        <form name="NewCondition">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="newCondition.name" required>
                                            <hr class="divider inv sm">
                                            <button class="btn btn-sm btn-success" type="button" @click="addCondition(newCondition)">Add Condition</button>
                                        </form>
                                    </validator>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-8">
                                    <h5 class="panel-header">Allergies</h5>
                                </div>
                                <div class="col-xs-4 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="list-group">
                            <div class="list-group-item" v-for="allergy in allergiesList|orderBy 'name'">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="allergy.selected"> {{allergy.name}}
                                    </label>
                                </div>
                                <div class="row" v-if="allergy.selected">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="allergy.medication"> Have Medication?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="allergy.diagnosed"> Medically Diagnosed by Doctor?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item" v-for="allergy in additionalAllergiesList">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="allergy.selected"> {{allergy.name}}
                                    </label>
                                </div>
                                <div class="row" v-if="allergy.selected">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="allergy.medication"> Have Medication?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="allergy.diagnosed"> Medically Diagnosed by Doctor?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item text-center">
                                <button class="btn btn-xs btn-default-hollow" type="button" data-toggle="collapse"
                                        data-target="#newAllergy" aria-expanded="false" aria-controls="newAllergy">
                                    <i class="icon-left fa fa-plus"></i> Add Unlisted Allergy
                                </button>
                            </div>
                            <div class="collapse" id="newAllergy">
                                <div class="list-group-item">
                                    <validator name="newAllergy">
                                        <form name="newAllergy">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="newAllergy.name">
                                            <hr class="divider inv sm">
                                            <button class="btn btn-sm btn-success" type="button" @click="addAllergy(newAllergy)">Add Allergy</button>
                                        </form>
                                    </validator>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-header">Doctor's Permission</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>Start Here</h5>
                            <p>You have indicated that you have one or more medical conditions or allergies. In order to be cleared for travel, you must download the form below and have it complete by your doctor.  You will need to supply <strong>one form per condition or allergy</strong> as certain conditions may be treated by different doctors.</p>
                            <p>
                                <button class="btn btn-primary btn-md">
                                    <i class="fa fa-file-pdf-o icon-left"></i> Download Permission Form
                                </button>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <h5>Completed Forms</h5>
                            <p>Once you have completed the form(s) please attach them to your medical release by uploading them here in PDF format.</p>
                            <ul class="list-group" v-if="uploads.length">
                                <li class="list-group-item" v-for="upload in uploads">
                                    <i class="fa fa-file-pdf-o"></i> {{upload.name}}
                                    <a class="badge" @click="confirmUploadRemoval(upload)"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <upload-create-update type="file" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" :name="'medical-release-'+today + '-' + uploadCounter"></upload-create-update>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-header">Emergency Contact</h5>
                </div>
                <div class="panel-body">
                    <form name="newContact">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="emergency_contact.name">
                            </div>
                            <div class="col-sm-6">
                                <label>Email</label>
                                <input type="email" class="form-control" v-model="emergency_contact.email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Phone</label>
                                <input type="tel" class="form-control" v-model="emergency_contact.phone|phone">
                            </div>
                            <div class="col-sm-6">
                                <label>Relationship</label>
                                <select type="tel" class="form-control" v-model="emergency_contact.relationship">
                                    <option value="friend">Friend</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="family">Family</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="form-group text-center">
                <div class="col-xs-12">
                    <a v-if="!isUpdate" href="/dashboard/records/medical-releases" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-success">Done</a>
                </div>
            </div>
        </form>

        <modal class="text-center" :show.sync="deleteModal" title="Delete Cost" small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedItem">Delete {{ selectedItem.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Delete</button>
            </div>
        </modal>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>Medical Release updated</p>
        </alert>
        <alert :show.sync="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>There are errors on the form.</p>
        </alert>
        <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>

    </validator>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    export default{
        name: 'medical-create-update',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        props: {
            isUpdate: {
                type:Boolean,
                default: false
            },
            id: {
                type: String,
                default: null
            }
        },
        data(){
            return{
                user_id: null,
                name:'',
                ins_provider:'',
                ins_policy_no:'',
                is_risk:true,
                conditions: [],
                allergies: [],
                uploads: [],
                upload_ids: [],
                uploadCounter: 1,
                emergency_contact: {
                    name: '',
                    email: '',
                    phone: '',
                    relationship: '',
                },

                newCondition: {
                    name: '',
                    medication: false,
                    diagnosed: false,
                    selected: true
                },
                newAllergy: {
                    name: '',
                    medication: false,
                    diagnosed: false,
                    selected: true
                },

                // logic vars
                conditionsList: [],
                additionalConditionsList: [],
                allergiesList: [],
                additionalAllergiesList: [],
                countries: [],
                countryObj: null,
                attemptSubmit: false,
                showSuccess: false,
                showError: false,
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow:moment().add(1, 'days').format('YYYY-MM-DD'),
                resource: this.$resource('medical/releases{/id}')
            }
        },
        computed: {
            country_code(){
                return _.isObject(this.countryObj) ? this.countryObj.code : null;
            },
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdateMedicalRelease[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard/records/medical-releases/';
            },
            forceBack(){
                return this.back(true);
            },
            addCondition(condition){
                // condition.selected = true;
                this.additionalConditionsList.push(condition);
                this.reset('condition');
            },
            addAllergy(allergy){
                // allergy.selected = true;
                this.additionalAllergiesList.push(allergy);
                this.reset('allergy');
            },
            reset(type){
                switch (type) {
                    case 'condition':
                        this.newCondition = {
                            name: '',
                            medication: false,
                            diagnosed: false,
                            selected: true
                        };
                        jQuery('#newCondition').collapse();
                        break;
                    case 'allergy':
                        this.newAllergy = {
                            name: '',
                            medication: false,
                            diagnosed: false,
                            selected: true
                        };
                        jQuery('#newAllergy').collapse();
                        break;
                }
            },
            prepArrays(){
                this.conditions = _.where(_.union(this.conditionsList, this.additionalConditionsList), { selected: true });
                this.allergies = _.where(_.union(this.allergiesList, this.additionalAllergiesList), { selected: true });
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateMedicalRelease.valid) {
                    this.prepArrays();
                    this.resource.save(null, {
                        name: this.name,
                        ins_provider: this.ins_provider,
                        ins_policy_no: this.ins_policy_no,
                        conditions: this.conditions,
                        allergies: this.allergies,
                        emergency_contact: this.emergency_contact,
                        is_risk: this.is_risk,
                        user_id: this.user_id,
                        upload_ids: _.uniq(this.upload_ids),
                    }).then(function (resp) {
                        window.location.href = '/dashboard/records/medical-releases';
                    }, function (error) {
                        this.showError = true;
                        // this.$refs.spinner.hide();
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateMedicalRelease.valid) {
                    this.prepArrays();
                    this.resource.update({id:this.id, include: 'uploads'}, {
                        name: this.name,
                        ins_provider: this.ins_provider,
                        ins_policy_no: this.ins_policy_no,
                        conditions: this.conditions,
                        allergies: this.allergies,
                        emergency_contact: this.emergency_contact,
                        is_risk: this.is_risk,
                        user_id: this.user_id,
                        upload_ids: _.uniq(this.upload_ids),
                    }).then(function (resp) {
                        this.showSuccess = true;
                        // this.$refs.spinner.hide();
                    }, function (error) {
                        // this.$refs.spinner.hide();
                        console.log(error);
                    });
                }
            },
            confirmUploadRemoval(upload){
                this.uploads.$remove(upload);
                this.upload_ids.$remove(upload.id);
            },

        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'file':
                        this.uploads.push(data);
                        this.uploads = _.uniq(this.uploads);
                        this.upload_ids.push(data.id);
                        this.upload_ids = _.uniq(this.upload_ids);
                        this.uploadCounter++;
                        break;
                }
            }
        },
        ready(){
            // set user data
            this.user_id = this.$root.user.id;
            if (this.isUpdate) {
                this.$http.get('medical/releases/' + this.id, { include: 'conditions,allergies,uploads'}).then(function (response) {
                    // this.user = response.data.data;
                    this.user_id = response.data.data.id;
                    let medical_release = response.data.data;
                    medical_release.uploads = medical_release.uploads.data;
                    this.upload_ids = _.pluck(medical_release.uploads, 'id');
                    this.uploadCounter = medical_release.uploads.length + 1;
                    $.extend(this, medical_release);

                    this.$http('medical/conditions').then(function (response) {
                        // prepare conditions for UI
                        let med_conditions = medical_release.conditions.data;
                        _.each(response.data.data, function (condition) {
                            let obj = { name: condition, medication: false, diagnosed: false, selected: false };
                            let match = _.find(med_conditions, function (c, i) {
                                med_conditions[i].selected = true;
                                return c.name === condition;
                            });
                            if (match) {
                                med_conditions = _.reject(med_conditions, function (mc) {
                                    return mc.name === match.name;
                                });
                                _.extend(obj, match, { selected: true });
                            }
                            this.conditionsList.push(obj);
                        }.bind(this));
                        this.additionalConditionsList = med_conditions;
                    });
                    this.$http('medical/allergies').then(function (response) {
                    // prepare conditions for UI
                    let med_allergies = medical_release.allergies.data;
                    _.each(response.data.data, function (allergy) {
                        let obj = { name: allergy, medication: false, diagnosed: false, selected: false };
                        let match = _.find(med_allergies, function (a, i) {
                            med_allergies[i].selected = true;
                            return a.name === allergy;
                        });
                        if (match) {
                            med_allergies = _.reject(med_allergies, function (ma) {
                                return ma.name === match.name;
                            });
                            _.extend(obj, match, { selected: true });
                        }
                        this.allergiesList.push(obj);
                    }.bind(this));
                    this.additionalAllergiesList = med_allergies;
                });
                });
            } else {
                this.$http('medical/conditions').then(function (response) {
                    _.each(response.data.data, function (condition) {
                        this.conditionsList.push({ name: condition, medication: false, diagnosed: false, selected: false });
                    }.bind(this));
                });
                this.$http('medical/allergies').then(function (response) {
                    _.each(response.data.data, function (allergy) {
                        this.allergiesList.push({ name: allergy, medication: false, diagnosed: false, selected: false });
                    }.bind(this));
                });
            }
        }
    }
</script>
