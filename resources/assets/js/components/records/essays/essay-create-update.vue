<template>
    <validator name="CreateUpdateEssay" @touched="onTouched">
        <form id="CreateUpdateEssay" class="form-horizontal" novalidate>
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="form-group" :class="{ 'has-error': checkForError('author') }">
                <label for="author" class="control-label">Author Name</label>
                <input type="text" class="form-control" name="author" id="author" v-model="author_name"
                       placeholder="Author Name" v-validate:author="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
            </div>

            <!--<div class="form-group" :class="{ 'has-error': checkForError('subject') }">
                <label for="subject" class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" v-model="subject"
                       placeholder="Subject" v-validate:subject="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
            </div>-->

            <div class="form-group" v-for="QA in content">
                <label class="control-label" v-text="QA.q"></label>
                <textarea class="form-control" v-model="QA.a"></textarea>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" href="/dashboard/records/essays" class="btn btn-default">Cancel</a>
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
            },
            userId: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                author_name: '',
                subject: 'Testimony',
                content: [
                    { q: 'Describe how you decided to follow Christ', a: ''},
                    { q: 'Describe your church involvement', a: ''},
                    { q: 'Describe your current walk with God', a: ''},
                    { q: 'Have you been on a missions trip before? If so, please tell us about your experience', a: ''},
                ],
                user_id: this.userId,

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
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateEssay.valid) {
                    // this.$refs.spinner.show();
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
                        // this.$refs.spinner.hide();
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateEssay.valid) {
                    // this.$refs.spinner.show();
                    this.resource.update({id: this.id}, {
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.showSuccess = true;
                        // this.$refs.spinner.hide();
                    }, function (error) {
                        // this.$refs.spinner.hide();
                        debugger;
                    });
                }
            },
        },
        ready(){
            if (this.isUpdate) {
                // this.$refs.spinner.show();
                this.$http('essays/' + this.id).then(function (response) {
                // this.user = response.data.data;

                    let essay = response.data.data;
                    this.author_name = essay.author_name;
                    this.subject = essay.subject;
                    this.content = essay.content;
                    // this.user_id = essay.user_id;
                    // this.$refs.spinner.hide();
                });
            }
        }
    }
</script>