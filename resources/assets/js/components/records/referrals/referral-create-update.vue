<template>
    <validator name="CreateUpdateReferral" @touched="onTouched">
        <form id="CreateUpdateReferral" class="form-horizontal" novalidate style="postition:relative;">
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="row">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('name') }">
                    <label for="author" class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                       placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referralemail') }">
                    <label for="author" class="control-label">Referral Email</label>
                    <input type="text" class="form-control" name="referral_email" id="referral_email" v-model="referral_email"
                           placeholder="Referral Email" v-validate:referralemail="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="150" minlength="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referralname') }">
                    <label for="author" class="control-label">Referral Name</label>
                    <input type="text" class="form-control" name="referral_name" id="referral_name" v-model="referral_name"
                       placeholder="Referral Name" v-validate:referralname="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referralphone') }">
                    <label for="author" class="control-label">Referral Phone</label>
                    <input type="text" class="form-control" name="referral_phone" id="referral_phone" v-model="referral_phone|phone"
                       placeholder="Referral Phone" v-validate:referralphone="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
            </div>

            <div class="row" v-for="QA in response">
                <div class="col-sm-12">
                <label class="control-label" v-text="QA.q"></label>
                <textarea class="form-control" v-model="QA.a"></textarea>
                </div>
            </div>
            <hr class="divider inv">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" href="/dashboard/records/referrals" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-success">Done</a>
                </div>
            </div>
        </form>
        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>Referral updated!</p>
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
        name: 'referral-create-update',
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
                type: 'pastoral',
                referral_email: '',
                referral_name: '',
                referral_phone: '',
                name: '',
                response: [
                    { q: 'How Long have you known the applicant?', a: ''},
                    { q: 'Please list any current roles the applicant serves in at your church:', a: ''},
                    { q: 'To the best of your knowledge, what is the current state of the applicant\'s spiritual walk?', a: ''},
                    { q: 'Do you have any reservations about sending this applicant into a foreign nation where spiritual, physical, and social endurance is tested?', a: ''},
                    { q: 'What are the applicant\'s significant strengths?', a: ''},
                    { q: 'What are the applicant\'s significant weaknesses?', a: ''},
                    { q: 'Would you recommend this applicant for a leadership role with Missions.me? If so, why?', a: ''},
                ],
                user_id: this.userId,

                // logic vars
                resource: this.$resource('referrals{/id}'),
                showSuccess: false,
                showError: false,
                hasChanged: false,
                attemptSubmit: false,
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdateReferral[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard/records/referrals/';
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateReferral.valid) {
                    this.resource.save({
                        name: this.name,
                        referral_email: this.referral_email,
                        referral_name: this.referral_name,
                        referral_phone: this.referral_phone,
                        type: this.type,
                        response: this.response,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.showSuccess = true;
                        // window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/records/referrals';
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
                if (this.$CreateUpdateReferral.valid) {
                    this.resource.update({id: this.id}, {
                        name: this.name,
                        referral_email: this.referral_email,
                        referral_name: this.referral_name,
                        referral_phone: this.referral_phone,
                        type: this.type,
                        response: this.response,
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
            if (this.isUpdate) {
                this.$http('referrals/' + this.id).then(function (response) {

                    let referral = response.data.data;
                    $.extend(this, referral);
                });
            }
        }
    }
</script>