<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 v-if="isUpdate">Edit Influencer Application</h5>
            <h5 v-else>New Influencer Application</h5>
        </div>
        <div class="panel-body">
            <form id="CreateUpdateInfluencer" class="" novalidate>
                <spinner ref="spinner" global size="sm" text="Loading"></spinner>
                
                <template v-if="forAdmin">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" v-error-handler="{ value: user_id, client: 'manager', server: 'user_id' }">
                            <label for="infoManager">Record Manager</label>
                            <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" name="manager" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name" v-validate="'required'"></v-select>
                        </div>
                    </div>
                </div>
                </template>

                <div class="form-group" v-error-handler="{ value: author_name, handle: 'author' }">
                    <label for="author" class="control-label">Author Name</label>
                    <input type="text" class="form-control" name="author" id="author" v-model="author_name"
                           placeholder="Author Name" v-validate="'required|min:1|max:100'"
                           maxlength="150">
                </div>

                <template class="form-group" v-for="(QA, indexQA) in content">
                    <template v-if="QA.type">
                        <div class="form-group" v-if="QA.type === 'radio'" v-error-handler="{ value: QA.a, client: 'radio' + indexQA, messages: { req: 'Please select an option.'} }">
                            <label class="control-labal">{{QA.q}}</label><br>
                            <label class="radio-inline" v-for="(choice, choiceIndex) in QA.options">
                                <input type="radio" :value="choice.value" v-model="QA.a" :name="'radio' + indexQA" v-validate="choiceIndex === 0 ? 'required' : ''"> {{ choice.name }}
                            </label>
                        </div>
                        <div class="form-group" v-if="QA.type === 'checkbox'" v-error-handler="{ value: QA.a, client: 'chex' + indexQA }">
                            <label class="control-labal">{{QA.q}}</label><br>
                            <label class="radio-inline" v-for="choice in QA.options">
                                <input type="checkbox" :value="choice.value" v-model="QA.a" :name="'chex' + indexQA" v-validate="''"> {{ choice.name }}
                            </label>
                        </div>
                        <div class="form-group" v-if="QA.type === 'textarea'" v-error-handler="{ value: QA.a, client: 'textarea' + indexQA, messages: { req: 'Please provide an answer.'} }">
                            <label class="control-label" v-text="QA.q"></label>
                            <textarea class="form-control" v-model="QA.a" rows="10" :name="'textarea' + indexQA" v-validate="'required'"></textarea>
                        </div>
                        <template v-if="QA.type === 'file'">
                            <div class="form-group" >
                                <label class="control-label" v-text="QA.q"></label>
                                <ul class="list-group" v-if="uploads.length">
                                    <li class="list-group-item" v-for="upload in uploads">
                                        <i class="fa fa-file-pdf-o"></i> {{upload.name}}
                                    <a class="badge" @click="confirmUploadRemoval(upload)"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <upload-create-update type="file" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" allow-name :name="'influencer-questionnaire-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
                            </div>
                             </template>
                    </template>
                    <template v-else>
                        <label class="control-label" v-text="QA.q"></label>
                        <textarea class="form-control" v-model="QA.a" ></textarea>
                    </template>
                </template>
                <hr class="divider inv">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <template v-if="!isUpdate">
                            <a @click="back()" class="btn btn-default">Cancel</a>
                            <a @click="submit()" class="btn btn-primary">Create</a>
                        </template>
                        <template v-else>
                            <a @click="back()" class="btn btn-default">Cancel</a>
                            <a @click="update()" class="btn btn-primary">Update</a>
                        </template>
                    </div>
                </div>
            </form>
            <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
            </modal>

        </div>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from "vue-select";
    import errorHandler from'../../error-handler.mixin';
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    export default{
        name: 'influencer-questionnaire-create-update',
        mixins: [errorHandler],
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            },
            id: {
                type: String,
                default: null
            },
            forAdmin: {
                type: Boolean,
                default: false
            },
            reservationId: {
                type: String,
                default: null
            },
            requirementId: {
                type: String,
                default: null
            }
        },
        data(){
            return {
                uploads: [],
                upload_ids: [],
                uploadCounter: 1,

                author_name: '',
                subject: 'Influencer',
                content: [
                    { q: 'Are you comfortable speaking in both a faith-based and non-faith based environments? Please explain.', a: '', type: 'textarea' },
                    { q: 'Do you have prior experience speaking to a large audience? Please explain.', a: '', type: 'textarea'},
                    { q: 'Have you ever spoken with a translator?', a: '', type: 'radio', options: [{name: 'Yes', value:'yes'}, {name: 'No', value:'no'}]},
                    { q: 'Please list any degrees or certifications you hold and the issuing university.', a: '', type: 'textarea'},
                    { q: 'Please tell us about your professional history including career highlights and current or past roles.', a: '', type: 'textarea'},
                    { q: 'What areas would you consider yourself an expert?', a: '', type: 'textarea'},
                    { q: 'Please list 3-4 topics that you would be most comfortable speaking on to college students? (i.e. technology, education, leadership, business, politics, etc.)', a: '', type: 'textarea'},
                    { q: 'Please upload a bio or resume for review.', a: [], type: 'file'},
                ],
                usersArr: [],
                userObj: null,

                // logic vars
                resource: this.$resource('influencer-applications{/id}'),
                today: moment().format('YYYY-MM-DD'),
                showSaveAlert: false,
            }
        },
        computed: {
            user_id(){
                return  _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
            }
        },
        methods: {
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('users', { params: { search: search} }).then((response) => {
                    this.usersArr = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            back(force){
                if (this.isFormDirty && !force) {
                    this.showSaveAlert = true;
                    return false;
                }

                if (this.reservationId && this.requirementId) {
                    window.location.href = `/${this.firstUrlSegment}/reservations/${this.reservationId}/requirements/${this.requirementId}`;
                } else {
                    window.location.href = `/${this.firstUrlSegment}/records/influencer-applications/${this.id}`;
                }
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.showError = true;
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

                    // this.$refs.spinner.show();
                    this.resource.post({
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                        upload_ids: this.upload_ids,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Influencer created.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/influencer-applications/' + resp.data.data.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create influencer questionnaire.')
                    });
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.showError = true;
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }
                    // this.$refs.spinner.show();
                    this.resource.put({id: this.id}, {
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                        upload_ids: this.upload_ids,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/influencer-applications/' + that.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                })
            },
            confirmUploadRemoval(upload){
                this.uploads.$remove(upload);
                this.upload_ids.$remove(upload.id);
            },
            uploadsComplete(data){
                switch(data.type){
                    case 'file':
                        this.uploads.push(data);
                        this.uploads = _.uniq(this.uploads);
                        this.upload_ids.push(data.id);
                        this.content[7].a = _.uniq(this.upload_ids);
                        this.uploadCounter++;
                        break;
                }
            }
        },
        mounted(){
            if (this.isUpdate) {
                // this.$refs.spinner.show();
                this.resource.get({ id: this.id, include: 'user' }).then((response) => {
                    let influencer = response.data.data;
                    this.author_name = influencer.author_name;
                    this.subject = influencer.subject;
                    this.content = influencer.content;
                    this.userObj = influencer.user;
                    this.usersArr.push(this.userObj);

                    // TODO Find better reference to this data
                    if (this.content[7].a.length) {
                        this.uploadCounter += this.content[7].a.length;
                        _.each(this.content[7].a, function (id) {
                            this.$http.get('uploads/' + id).then((resA) => {
                                this.uploads.push(resA.data.data);
                                this.upload_ids = this.content[7].a;
                            });
                        });

                    }
                });
            }
        }
    }
</script>