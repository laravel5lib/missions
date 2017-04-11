<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div><validator name="CreateUpdatePassport" @touched="onTouched">
        <form id="CreateUpdatePassport" class="form-horizontal" novalidate>
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>

            <template v-if="forAdmin">
                <div class="col-sm-12">
                    <div class="form-group" v-error-handler="{ value: user_id, client: 'manager', server: 'user_id' }">
                        <label for="infoManager">Record Manager</label>
                        <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" :value.sync="userObj" :options="usersArr" :on-search="getUsers" label="name"></v-select>
                        <select hidden name="manager" id="infoManager" class="hidden" v-model="user_id" v-validate:manager="{ required: true }">
                            <option :value="user.id" v-for="user in usersArr">{{user.name}}</option>
                        </select>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: given_names, client: 'givennames', server: 'given_names' }">
                        <label for="given_names" class="control-label">Given Names</label>
                        <input type="text" class="form-control" name="given_names" id="given_names" v-model="given_names"
                               placeholder="Given Names" v-validate:givennames="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="150" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: surname, handle: 'surname' }">
                        <label for="surname" class="control-label">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Surname" v-validate:surname="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div><!-- end row -->
            <div class="form-group" v-error-handler="{ value: number, handle: 'number' }">
                <div class="col-sm-12">
                    <label for="number" class="control-label">Passport Number</label>
                    <input type="text" class="form-control" name="number" id="number" v-model="number"
                           placeholder="Passport Number" v-validate:number="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="9" required>
                </div>
            </div>

            <div class="row" v-error-handler="{ value: expires_at, client:'expires', server: 'expires_at' }">
                <div class="col-sm-12">
                    <label class="control-label">Expires On</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <date-picker :has-error="checkForError('expires')" :model.sync="expires_at|moment 'YYYY-MM-DD'" :input-sm="false"></date-picker>
                            <input type="datetime" class="form-control hidden" v-model="expires_at" id="expires_at" :min="tomorrow"
                                   v-validate:expires="{ required: true }" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" v-error-handler="{ value: birth_country, client: 'birth', server: 'birth_country' }">
                <div class="col-sm-12">
                    <label for="birth" class="control-label">Nationality</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="birth" :value.sync="birthCountryObj" :options="countries" label="name"></v-select>
                    <select hidden name="birth" id="birth" class="hidden" v-model="birth_country" v-validate:birth="{ required: true }">
                        <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group" v-error-handler="{ value: citizenship, handle: 'citizenship' }">
                <div class="col-sm-12">
                    <label for="citizenship" class="control-label">Citizenship</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="country" :value.sync="citizenshipObj" :options="countries" label="name"></v-select>
                    <select hidden name="citizenship" id="citizenship" class="hidden" v-model="citizenship" v-validate:citizenship="{ required: true }">
                        <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <accordion :one-at-atime="true">
                        <panel header="Upload Photocopy" :is-open.sync="true">
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
                                <upload-create-update v-if="isUpdate && userObj || !isUpdate" type="passport" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" :is-update="isUpdate" :upload-id="upload_id" :name="'passport-'+given_names+'-'+surname"></upload-create-update>
                            </div>
                        </panel>
                    </accordion>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" :href="'/' + firstUrlSegment + '/records/passports'" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-default">Cancel</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>

        <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>

    </validator></div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    import errorHandler from'../../error-handler.mixin';
    export default{
        name: 'passport-create-update',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        mixins: [errorHandler],
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
            }
        },
        data(){
            return{
                // mixin settings
                validatorHandle: 'CreateUpdatePassport',

                given_names: '',
                surname: '',
                number: '',
                expires_at: null,
                birth_country: null,
                citizenship: null,
                upload_id: null,
                usersArr: [],
                userObj: null,

                // logic vars
                countries: [],
                birthCountryObj: null,
                citizenshipObj: null,
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow:moment().add(1, 'days').format('YYYY-MM-DD'),
                showSaveAlert: false,
                hasChanged: false,
                passportResource: this.$resource('passports{/id}', {include: 'user'})
            }
        },
        computed: {
            birth_country(){
                return _.isObject(this.birthCountryObj) ? this.birthCountryObj.code : null;
            },
            citizenship(){
                return _.isObject(this.citizenshipObj) ? this.citizenshipObj.code : null;
            },
            user_id(){
                return  _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
            }
        },
        methods: {
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('users', { params: { search: search} }).then(function (response) {
                    this.usersArr = response.body.data;
                    loading ? loading(false) : void 0;
                })
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/' + this.firstUrlSegment + '/records/passports/' + this.id;
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.resetErrors();
                if (this.$CreateUpdatePassport.valid) {
                    this.passportResource.save(null, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.$dispatch('showSuccess', 'Passport created.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/' + that.firstUrlSegment + '/records/passports/' + resp.data.data.id;
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$dispatch('showError', 'Unable to create passport.');
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                if ( _.isFunction(this.$validate) )
                    this.$validate(true);

                this.resetErrors();
                if (this.$CreateUpdatePassport.valid) {
                    this.passportResource.update({id:this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.$dispatch('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/' + that.firstUrlSegment + '/records/passports/' + that.id;
                        }, 1000);
                        this.hasChanged = false;
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$dispatch('showError', 'Unable to save changes.');
                    });
                }
            },

        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'other':
                        //save for preview
                        this.selectedAvatar = data;
                        // save for upload reference
                        this.upload_id = data.id;
                        break;
                }
            }
        },
        ready(){
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.body.countries;
            });

            if (this.isUpdate) {
                this.passportResource.get({ id: this.id }).then(function (response) {
                    let passport = response.body.data;
                    $.extend(this, passport);

                    this.birthCountryObj = _.findWhere(this.countries, {code: passport.birth_country});
                    this.citizenshipObj = _.findWhere(this.countries, {code: passport.citizenship});
                    this.userObj = passport.user.data;
                    this.usersArr.push(this.userObj);
                });
            }
        }

    }
</script>
