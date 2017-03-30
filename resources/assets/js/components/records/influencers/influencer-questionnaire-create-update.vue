<template>
    <div>
        <validator name="CreateUpdateInfluencer" @touched="onTouched">
            <form id="CreateUpdateInfluencer" class="" novalidate>
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
                <div class="form-group" v-error-handler="{ value: author_name, handle: 'author' }">
                    <label for="author" class="control-label">Author Name</label>
                    <input type="text" class="form-control" name="author" id="author" v-model="author_name"
                           placeholder="Author Name" v-validate:author="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="150" minlength="1" required>
                </div>

                <div class="form-group" v-for="QA in content">
                    <template v-if="QA.type">
                        <template v-if="QA.type === 'radio'">
                            <label class="control-labal">{{QA.q}}</label><br>
                            <label class="radio-inline" v-for="choice in QA.options">
                                <input type="radio" :value="choice.value" v-model="QA.a"> {{ choice.name }}
                        </label>
                        </template>
                        <template v-if="QA.type === 'checkbox'">
                            <label class="control-labal">{{QA.q}}</label><br>
                            <label class="radio-inline" v-for="choice in QA.options">
                                <input type="checkbox" :value="choice.value" v-model="QA.a"> {{ choice.name }}
                        </label>
                        </template>
                        <template v-if="QA.type === 'textarea'">
                            <label class="control-label" v-text="QA.q"></label>
                            <textarea class="form-control" v-model="QA.a" rows="10"></textarea>
                        </template>
                        <template v-if="QA.type === 'file'">
                            <div>
                                <label class="control-label" v-text="QA.q"></label>
                                <ul class="list-group" v-if="uploads.length">
                                    <li class="list-group-item" v-for="upload in uploads">
                                        <i class="fa fa-file-pdf-o"></i> {{upload.name}}
                                    <a class="badge" @click="confirmUploadRemoval(upload)"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <upload-create-update type="file" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="true" :name="'influencer-questionnaire-'+ today + '-' + uploadCounter"></upload-create-update>
                            </div>
                             </template>
                    </template>
                    <template v-else>
                        <label class="control-label" v-text="QA.q"></label>
                        <textarea class="form-control" v-model="QA.a" ></textarea>
                    </template>
                </div>
                <hr class="divider inv">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a v-if="!isUpdate" href="/dashboard/records/influencers" class="btn btn-default">Cancel</a>
                        <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                        <a v-if="isUpdate" @click="back()" class="btn btn-default">Cancel</a>
                        <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                    </div>
                </div>
            </form>
            <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
            </modal>

        </validator>
    </div>
</template>
<script type="text/javascript">
    import errorHandler from'../../error-handler.mixin';
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    export default{
        name: 'influencer-questionnaire-create-update',
        mixins: [errorHandler],
        components: {'upload-create-update': uploadCreateUpdate},
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            },
            id: {
                type: String,
                default: null
            },
            userId: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'CreateUpdateInfluencer',

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
                user_id: this.userId,

                // logic vars
                resource: this.$resource('essays{/id}'),
                today: moment().format('YYYY-MM-DD'),
                hasChanged: false,
            }
        },
        methods: {
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard/records/influencers/';
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.resetErrors();
                if (this.$CreateUpdateInfluencer.valid) {
                    // this.$refs.spinner.show();
                    this.resource.save({
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                        upload_ids: this.upload_ids,
                    }).then(function (resp) {
                        this.$dispatch('showSuccess', 'Influencer created.');
                        setTimeout(function () {
                            window.location.href = '/dashboard/records/influencers/' + resp.body.data.id;
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$dispatch('showError', 'Unable to create influencer questionnaire.')
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.resetErrors();
                if (this.$CreateUpdateInfluencer.valid) {
                    // this.$refs.spinner.show();
                    this.resource.update({id: this.id}, {
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                        upload_ids: this.upload_ids,
                    }).then(function (resp) {
                        this.$dispatch('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/dashboard/records/influencers/' + that.id; 
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$dispatch('showError', 'Unable to save changes.');
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
                        this.content[7].a = _.uniq(this.upload_ids);
                        this.uploadCounter++;
                        break;
                }
            }
        },
        ready(){
            if (this.isUpdate) {
                // this.$refs.spinner.show();
                this.resource.get({ id: this.id }).then(function (response) {
                    let influencer = response.body.data;
                    this.author_name = influencer.author_name;
                    this.subject = influencer.subject;
                    this.content = influencer.content;

                    if (this.content[7].a.length) {
                        this.uploadCounter += this.content[7].a.length;
                        _.each(this.content[7].a, function (id) {
                            this.$http.get('uploads/' + id).then(function (resA) {
                                this.uploads.push(resA.body.data);
                                this.upload_ids = this.content[7].a;
                            });
                        }.bind(this));

                    }
                });
            }
        }
    }
</script>