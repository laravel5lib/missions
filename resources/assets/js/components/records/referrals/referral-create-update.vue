<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 v-if="isUpdate">Edit Referral</h5>
            <h5 v-else>New Referral</h5>
        </div>
        <div class="panel-body">
        <form id="CreateUpdateReferral" class="form-horizontal" novalidate style="postition:relative;">
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>

            <template v-if="forAdmin">
                <div class="col-sm-12">
                    <div class="form-group" :class="{ 'has-error': errors.has('manager') }">
                        <label for="infoManager">Record Manager</label>
                        <v-select @keydown.enter.prevent="" class="form-control" name="manager" id="infoManager" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name" v-validate="'required'"></v-select>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-sm-12" v-error-handler="{ value: applicant_name, handle: 'name' }">
                    <label for="author" class="control-label">Applicant Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="applicant_name"
                           placeholder="Name" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: attention_to, client: 'attention_to', server: 'attention_to' }">
                    <label for="author" class="control-label">Attention to</label>
                    <input type="text" class="form-control" name="attention_to" id="attention_to" v-model="attention_to"
                           placeholder="Attention to" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: recipient_email, client: 'recipient_email', server: 'recipient_email' }">
                    <label for="author" class="control-label">Recipient Email</label>
                    <input type="text" class="form-control" name="recipient_email" id="recipient_email" v-model="recipient_email"
                           placeholder="Recipient Email" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: referrer.title, client: 'referral_title', server: 'referral_title' }">
                    <label for="author" class="control-label">Referral Title/Position</label>
                    <input type="text" class="form-control" name="referral_title" id="referral_title" v-model="referrer.title"
                           placeholder="Referral title or position" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: referrer.name, client: 'referral_name', server: 'referral_name' }">
                    <label for="author" class="control-label">Referral Name</label>
                    <input type="text" class="form-control" name="referral_name" id="referrer.name" v-model="referrer.name"
                           placeholder="Referral Name" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: referrer.organization, client: 'referral_organization', server: 'referral_organization' }">
                    <label for="author" class="control-label">Referral Organization</label>
                    <input type="text" class="form-control" name="referral_organization" id="referral_organization" v-model="referrer.organization"
                           placeholder="Referral Name" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: referrer.phone, client: 'referral_phone', server: 'referral_phone' }">
                    <label for="author" class="control-label">Referral Phone</label>
                    <phone-input v-model="referrer.phone" v-validate="'required|min:1|max:100'" name="referral_phone" id="referral_phone"></phone-input>
                </div>
            </div>

            <hr class="divider inv">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <template v-if="!isUpdate">
                        <a @click="back" class="btn btn-default">Cancel</a>
                        <a @click="submit" class="btn btn-primary">Create</a>
                    </template>
                    <template v-else>
                        <a @click="back" class="btn btn-default">Cancel</a>
                        <a @click="update" class="btn btn-primary">Update</a>
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
    import $ from 'jquery';
    import _ from 'underscore';
    import vSelect from "vue-select";
    import errorHandler from'../../error-handler.mixin';
    export default{
        name: 'referral-create-update',
        mixins: [errorHandler],
        components: {vSelect},
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
                usersArr: [],
                userObj: null,

                // logic vars
                resource: this.$resource('referrals{/id}'),
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
                    window.location.href = `/${this.firstUrlSegment}/records/referrals/${this.id}`;
                }
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        this.showError = true;
                        return false;
                    }
                    this.resource.post({
                        applicant_name: this.applicant_name,
                        recipient_email: this.recipient_email,
                        referrer: this.referrer,
                        type: this.type,
                        response: this.response,
                        user_id: this.user_id,
                        attention_to: this.attention_to,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Referral created and sent.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/referrals/' + resp.data.data.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create referral.');
                    });
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

                    this.resource.put({id: this.id}, {
                        attention_to: this.attention_to,
                        applicant_name: this.applicant_name,
                        recipient_email: this.recipient_email,
                        referrer: this.referrer,
                        type: this.type,
                        response: this.response,
                        user_id: this.user_id,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/referrals/' + that.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                });
            },
        },
        mounted(){
            if (this.isUpdate) {
                this.$http.get('referrals/' + this.id).then((response) => {

                    let referral = response.data.data;
                    $.extend(this, referral);
                    this.userObj = referral.user;
                    this.usersArr.push(this.userObj);
                });
            }
        }
    }
</script>