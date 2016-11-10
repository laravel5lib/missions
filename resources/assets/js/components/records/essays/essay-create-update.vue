<template>
    <validator name="CreateUpdateEssay" @touched="onTouched">
        <form id="CreateUpdateEssay" class="form-horizontal" novalidate>
            <div class="form-group" :class="{ 'has-error': checkForError('author') }">
                <label for="author" class="control-label">Author Name</label>
                <input type="text" class="form-control" name="author" id="author" v-model="author_name"
                       placeholder="Author Name" v-validate:author="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('subject') }">
                <label for="subject" class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" v-model="subject"
                       placeholder="Subject" v-validate:subject="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-8">
                            <h5 class="panel-header">Questions & Answers</h5>
                        </div>
                        <div class="col-xs-4 text-right">
                            <button class="btn btn-xs btn-default-hollow" type="button" @click="showQAForm()">
                                <i class="icon-left fa fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
                <div class="list-group">
                    <div class="list-group-item" v-for="QA in content">
                        <template v-if="QA !== editQA">
                            <div class="row">
                                <!--<div class="col-sm-8">-->
                                    <h6 v-text="QA.q"></h6>
                                    <p v-text="QA.a"></p>
                                <!--</div>-->
                                <!--<div class="col-sm-4 text-right">-->
                                    <!--<tooltip content="Edit">-->
                                        <!--<a href="#" class="btn btn-xs btn-default" @click.prevent="editQAMode(QA)"><i class="fa fa-pencil"></i></a>-->
                                    <!--</tooltip>-->
                                    <!--<tooltip content="Delete">-->
                                        <!--<a href="#" class="btn btn-xs btn-danger" @click.prevent="content.$remove(QA)"><i class="fa fa-trash"></i></a>-->
                                    <!--</tooltip>-->
                                <!--</div>-->
                            </div>

                        </template>
                        <template v-else>
                            <validator name="EditContent">
                                <form id="EditContent" novalidate>
                                    <div class="form-group">
                                        <label class="control-label">Question</label>
                                        <input type="text" class="form-control" v-model="editQA.q"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Answer</label>
                                        <input type="text" class="form-control" v-model="editQA.a">
                                    </div>
                                    <hr class="divider inv sm">
                                    <button class="btn btn-sm btn-default" type="button" @click="cancelEditQA(editQA)">Cancel</button>
                                    <button class="btn btn-sm btn-success" type="button" @click="updateEditQA(editQA)">Save</button>
                                </form>
                            </validator>
                        </template>
                    </div>
                    <div class="collapse" id="newQACollapse">
                        <div class="list-group-item">
                            <validator name="NewContent">
                                <form id="NewContent" novalidate>
                                    <div class="form-group">
                                        <label class="control-label">Question</label>
                                        <input type="text" class="form-control" v-model="newQA.q"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Answer</label>
                                        <input type="text" class="form-control" v-model="newQA.a">
                                    </div>
                                    <hr class="divider inv sm">
                                    <button class="btn btn-sm btn-success" type="button" @click="addQA(newQA)">Add Q&A</button>
                                </form>
                            </validator>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" href="/dashboard/records/visas" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-success">Done</a>
                </div>
            </div>
        </form>
        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>Essay updated!</p>
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
    export default{
        name: 'essay-create-update',
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            },
            id: {
                type: String,
                default: null
            }
        },
        data(){
            return {
                author_name: '',
                subject: '',
                content: [],
                user_id: '',

                newQA: {
                    q: '',
                    a: '',
                },

                editQA: {
                    q: '',
                    a: '',
                },
                backupEditQA: null,

                // logic vars
                resource: this.$resource('essays{/id}'),
                showSuccess: false,
                showError: false,
                hasChanged: false,
                attemptSubmit: false,
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdateEssay[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard/records/essays/';
            },
            forceBack(){
                return this.back(true);
            },
            showQAForm(){
                jQuery('#newQACollapse').collapse('show');
                this.editQA = {
                    q: '',
                    a: '',
                };
            },
            addQA(QA){
                this.content.push(QA);
                jQuery('#newQACollapse').collapse('hide');
                this.newQA = {
                    q: '',
                    a: '',
                };
            },
            editQAMode(QA){
                this.editQA = QA;
                this.backupEditQA = jQuery.extend(true, {}, QA);
                jQuery('#newQACollapse').collapse('hide');
            },
            updateEditQA(EditQA){
                this.editQA = null;
                this.backupEditQA = null
            },
            cancelEditQA(EditQA){
                debugger;
                this.editQA = jQuery.extend(true, {}, this.backupEditQA);
                this.backupEditQA = null;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateEssay.valid) {
                    this.resource.save({
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.showSuccess = true;
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/records/essays';
                    }, function (error) {
                        this.showError = true;
                        console.log(error);
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateEssay.valid) {
                    this.resource.update({id: this.id}, {
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.showSuccess = true;
                    }, function (error) {
                        debugger;
                    });
                }
            },
        },
        ready(){
            this.$http.get('users/me').then(function (response) {
                this.user_id = response.data.data.id;
                if (this.update) {
                    this.resource.get({user: this.user_id}).then(function (response) {
                        var essay = _.findWhere(response.data.data, {id: this.id});
                        this.author_name = essay.author_name;
                        this.subject = essay.subject;
                        this.content = essay.content;
                        // this.user_id = essay.user_id;
                    });
                }
            });

        }
    }
</script>