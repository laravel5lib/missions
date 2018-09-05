<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 v-if="isUpdate">Edit Passport</h5>
            <h5 v-else>New Passport</h5>
        </div>
        <div class="panel-body">
        <form id="CreateUpdatePassport" class="form-horizontal" novalidate>
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>

            <template v-if="forAdmin">
                <div class="col-sm-12">
                    <div class="form-group" v-error-handler="{ value: user_id, client: 'manager', server: 'user_id' }">
                        <label for="infoManager">Passport Manager</label>
                        <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name" name="manager" v-validate="'required'"></v-select>
                        <span class="help-block">The user account that manages this passport.</span>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: given_names, client: 'givennames', server: 'given_names', messages: { req: 'Please provide the passport holder\'s given names.'} }">
                        <label for="given_names" class="control-label">Given Names</label>
                        <input type="text" class="form-control" id="given_names" v-model="given_names"
                               placeholder="Given Names" name="givennames" v-validate="'required|min:1|max:100'"
                               maxlength="150" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: surname, handle: 'surname' }">
                        <label for="surname" class="control-label">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Surname" v-validate="'required|min:1|max:100'"
                               maxlength="100" minlength="1" required>

                    </div>
                </div>
            </div><!-- end row -->
            <div class="form-group" v-error-handler="{ value: number, handle: 'number', messages: { req: 'Please provide a valid passport number.'} }">
                <div class="col-sm-12">
                    <label for="number" class="control-label">Passport Number</label>
                    <input type="text" class="form-control" name="number" id="number" v-model="number"
                           placeholder="Passport Number" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="9" required>

                </div>
            </div>

            <div class="row" v-error-handler="{ value: expires_at, client:'expires', server: 'expires_at' }">
                <div class="col-sm-12">
                    <label class="control-label">Expires On</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <date-picker :has-error= "errors.has('expires')" v-model="expires_at" :view-format="['YYYY-MM-DD', false, true]" type="date" name="expires" v-validate="'required'"></date-picker>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" v-error-handler="{ value: birth_country, client: 'birth', server: 'birth_country', messages: { req: 'Please select country of nationality (where you were born).'} }">
                <div class="col-sm-12">
                    <label for="birth" class="control-label">Nationality</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="birth" v-model="birthCountryObj" :options="UTILITIES.countries" label="name" name="birth" v-validate="'required'"></v-select>
                </div>
            </div>
            <div class="form-group" v-error-handler="{ value: citizenship, handle: 'citizenship', messages: { req: 'Please select country of citizenship.'} }">
                <div class="col-sm-12">
                    <label for="citizenship" class="control-label">Citizenship</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="country" v-model="citizenshipObj" :options="UTILITIES.countries" label="name" name="citizenship" v-validate="'required'"></v-select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <accordion :one-at-atime="true">
                        <panel header="Upload Photocopy" :is-open="true">
                            <div class="panel-body">
                                <p>Please upload a full-color photocopy of your passport. Please be sure that both the photo page and signed signiture page are both clearly visible and legible.</p>
                                <div class="media" v-if="selectedAvatar">
                                    <div class="media-left">
                                        <a href="#">
                                            <img class="media-object" :src="selectedAvatar.source + '?w=100&q=50'" width="100" :alt="selectedAvatar.name">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="media-heading">{{selectedAvatar.name}}</h5>
                                    </div>
                                </div>
                                <upload-create-update v-if="userObj || !isUpdate" type="passport" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" :is-update="isUpdate && !!upload_id" :upload-id="upload_id" :name="'passport-'+given_names+'-'+surname" @uploads-complete="uploadsComplete"></upload-create-update>
                            </div>
                        </panel>
                    </accordion>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a @click="forceBack" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="update" class="btn btn-primary">Update</a>
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
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    import errorHandler from'../../error-handler.mixin';
    import utilities from '../../utilities.mixin';
    export default{
        name: 'passport-create-update',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        mixins: [utilities, errorHandler],
        props: {
            isUpdate: {
                type:Boolean,
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
            return{
                given_names: '',
                surname: '',
                number: '',
                expires_at: null,
                upload_id: null,
                usersArr: [],
                userObj: null,

                // logic vars
                birthCountryObj: null,
                citizenshipObj: null,
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow:moment().add(1, 'days').format('YYYY-MM-DD'),
                showSaveAlert: false,
                passportResource: this.$resource('passports{/id}', {include: 'user'})
            }
        },
        computed: {
            birth_country: {
                get() {
                    return _.isObject(this.birthCountryObj) ? this.birthCountryObj.code : null;
                },
                set() {

                }
            },
            citizenship: {
                get() {
                    return _.isObject(this.citizenshipObj) ? this.citizenshipObj.code : null;
                },
                set() {

                }
            },
            user_id(){
                return  _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
            },

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
                if (this.isFormDirty && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }

                if (this.reservationId && this.requirementId) {
                    window.location.href = `/${this.firstUrlSegment}/reservations/${this.reservationId}/requirements/${this.requirementId}`;
                } else {
                    window.location.href = `/${this.firstUrlSegment}/records/passports/${this.id}`;
                }
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.showError = true;
                        return;
                    }
                    this.passportResource.post({}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                        requirement_id: this.requirementId,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Passport created.');
                        let that = this;
                        setTimeout(() =>  {

                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/passports/' + resp.data.data.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create passport.');
                    });
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        return;
                    }

                    this.passportResource.put({id:this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                        requirement_id: this.requirementId,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {

                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/passports/' + that.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                });
            },
            uploadsComplete(data) {
                switch(data.type){
                    case 'other':
                        //save for preview
                        this.selectedAvatar = data;
                        // save for upload reference
                        this.upload_id = data.id;
                        break;
                }
            },
        },
        mounted(){
            this.getCountries().then(() => {
                if (this.isUpdate) {
                    this.passportResource.get({ id: this.id }).then((response) => {
                        let passport = response.data.data;
                        $.extend(this, passport);

                        this.birthCountryObj = _.findWhere(this.UTILITIES.countries, {code: passport.birth_country});
                        this.citizenshipObj = _.findWhere(this.UTILITIES.countries, {code: passport.citizenship});
                        this.userObj = passport.user.data;
                        this.usersArr.push(this.userObj);
                    });
                }
            });


        }

    }
</script>
