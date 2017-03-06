<template>
    <validator name="CreateUpdateReferral" @touched="onTouched">
        <form id="CreateUpdateReferral" class="form-horizontal" novalidate style="postition:relative;">
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: name, handle: 'name' }">
                    <label for="author" class="control-label">Applicant Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="applicant_name"
                       placeholder="Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: attention_to, client: 'attentionto', server: 'attention_to' }">
                    <label for="author" class="control-label">Attention to</label>
                    <input type="text" class="form-control" name="attention_to" id="attention_to" v-model="attention_to"
                           placeholder="Attention to" v-validate:attentionto="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: recipient_email, client: 'recipientemail', server: 'recipient_email' }">
                    <label for="author" class="control-label">Recipient Email</label>
                    <input type="text" class="form-control" name="recipient_email" id="recipient_email" v-model="recipient_email"
                           placeholder="Recipient Email" v-validate:recipientemail="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="150" minlength="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referreraltitle') }">
                    <label for="author" class="control-label">Referral Title/Position</label>
                    <input type="text" class="form-control" name="referral_title" id="referral_title" v-model="referrer.title"
                       placeholder="Referral title or position" v-validate:referraltitle="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referralname') }">
                    <label for="author" class="control-label">Referral Name</label>
                    <input type="text" class="form-control" name="referral_name" id="referrer.name" v-model="referrer.name"
                       placeholder="Referral Name" v-validate:referralname="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('referralorg') }">
                    <label for="author" class="control-label">Referral Organization</label>
                    <input type="text" class="form-control" name="referral_organization" id="referral_organization" v-model="referrer.organization"
                       placeholder="Referral Name" v-validate:referralorg="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-3" :class="{ 'has-error': checkForError('referralphone') }">
                    <label for="author" class="control-label">Referral Phone</label>
                    <input type="text" class="form-control" name="referral_phone" id="referral_phone" v-model="referrer.phone|phone"
                       placeholder="Referral Phone" v-validate:referralphone="{ required: true, minlength:1, maxlength:100 }"
                       maxlength="150" minlength="1" required>
                </div>
            </div>

            <hr class="divider inv">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" href="/dashboard/records/referrals" class="btn btn-default">Cancel</a>
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
</template>
<script type="text/javascript">
    import errorHandler from'../../error-handler.mixin';
    export default{
        name: 'referral-create-update',
        mixins: [errorHandler],
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
                validatorHandle: 'CreateUpdateReferral',

                type: 'pastoral',
                referrer: {
                    title: null,
                    name: null,
                    organization: null,
                    phone: null
                },
                applicant_name: '',
                attention_to: '',
                recipient_email: '',
                response: [
                    { q: 'How Long have you known the applicant?', a: '', type: 'textarea'},
                    { q: 'Does the applicant currently attend your church?', a: '', type: 'radio'},
                    { q: 'Do you have any concerns about this applicant’s ability to honor authority or adhere to instruction?  Please explain.', a: '', type: 'textarea'},
                    { q: 'Do you have any concerns about this applicant\'s spiritual, physical, and social endurance in a foreign nation for 7-14 days? Please explain.', a: '', type: 'textarea'},
                    { q: 'Do you recommend this applicant for missions service with Missions.Me?', a: '', type: 'radio'}
                ],
                user_id: this.userId,

                // logic vars
                resource: this.$resource('referrals{/id}'),
                hasChanged: false,
//                attemptSubmit: false,
            }
        },
        methods: {
            /*checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdateReferral[field].invalid && this.attemptSubmit;
            },*/
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
                this.resetErrors();
                if (this.$CreateUpdateReferral.valid) {
                    this.resource.save({
                        applicant_name: this.applicant_name,
                        recipient_email: this.recipient_email,
                        referrer: this.referrer,
                        type: this.type,
                        response: this.response,
                        user_id: this.user_id,
                        attention_to: this.attention_to
                    }).then(function (resp) {
                        this.showSuccess = true;
                        // window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/records/referrals';
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.showError = true;
                        console.log(error);
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.resetErrors();
                if (this.$CreateUpdateReferral.valid) {
                    this.resource.update({id: this.id}, {
                        attention_to: this.attention_to,
                        applicant_name: this.applicant_name,
                        recipient_email: this.recipient_email,
                        referrer: this.referrer,
                        type: this.type,
                        response: this.response,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.$dispatch('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/dashboard/records/referrals/' + that.id; 
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$dispatch('showErrors', 'Unable to save changes.');
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