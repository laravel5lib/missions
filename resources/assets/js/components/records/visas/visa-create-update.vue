<template >
    <div>
        <form id="CreateUpdateVisa" class="form-horizontal" novalidate>
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>

            <template v-if="forAdmin">
                <div class="col-sm-12">
                    <div class="form-group" :class="{ 'has-error': errors.has('manager') }">
                        <label for="infoManager">Record Manager</label>
                        <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name" name="manager" v-validate="'required'"></v-select>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: given_names, client: 'givennames', server: 'given_names' }">
                        <label for="given_names" class="control-label">Given Names</label>
                        <input type="text" class="form-control" id="given_names" v-model="given_names"
                               placeholder="Given Names" name="givennames" v-validate="'required|alpha_spaces|min:1|max:100'"
                               maxlength="150" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: surname, handle: 'surname' }">
                        <label for="surname" class="control-label">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Surname" v-validate="'required|alpha|min:1|max:100'"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div>
            <div class="form-group" v-error-handler="{ value: number, handle: 'number' }">
                <div class="col-sm-12">
                    <label for="number" class="control-label">Visa Number</label>
                    <input type="text" class="form-control" name="number" id="number" v-model="number"
                           placeholder="Visa Number" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="9" required>
                </div>
            </div>

            <div class="row" :class="{ 'has-error': (errors.has('issued') || errors.has('expires')) }">
                <div class="col-sm-12">
                    <label for="issued_at" class="control-label">Dates</label>
                    <div class="row">
                        <div class="col-lg-6" v-error-handler="{ value: issued_at, client:'issued', server: 'issued_at' }">
                            <date-picker addon="Issued" v-model="issued_at" :view-format="['YYYY-MM-DD']" type="date" name="issued" v-validate="'required'"></date-picker>
                            <br>
                        </div>
                        <div class="col-lg-6" v-error-handler="{ value: expires_at, client:'expires', server: 'expires_at' }">
                            <date-picker addon="Expires" v-model="expires_at" :view-format="['YYYY-MM-DD', false, true]" type="date" name="expires" v-validate="'required'"></date-picker>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                <div class="col-sm-12">
                    <label for="country" class="control-label">Country</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="countryObj" v-model="countryObj" :options="UTILITIES.countries" label="name" name="country" v-validate="'required'"></v-select>
                </div>
            </div>

            <accordion one-at-atime>
                <panel header="Upload Photocopy" is-open>
                    <div class="media" v-if="selectedAvatar">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" :src="selectedAvatar.source + '?w=100&q=50'" width="100" :alt="selectedAvatar.name">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">{{selectedAvatar.name}}</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <upload-create-update v-if="userObj || !isUpdate" type="passport" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" :is-update="isUpdate && !!upload_id" :upload-id="upload_id" :name="'passport-'+given_names+'-'+surname"  @uploads-complete="uploadsComplete"></upload-create-update>
                    </div><!-- end panel-body -->
                </panel>
            </accordion>

            <div class="form-group">
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
        <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>Profile updated</p>
        </alert>
        <alert v-model="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>There are errors on the form.</p>
        </alert>
        <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>
    </div>



</template>
<script type="text/javascript">
    import $ from 'jquery';
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    import errorHandler from'../../error-handler.mixin';
    import utilities from'../../utilities.mixin';
    export default{
        name: 'visa-create-update',
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
                issued_at: null,
                expires_at: null,
                showSaveAlert: false,
                upload_id: null,
                usersArr: [],
                userObj: null,

                // logic vars
                countryObj: null,
                showSuccess: false,
                showError: false,
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow:moment().add(1, 'days').format('YYYY-MM-DD'),
                visasResource: this.$resource('visas{/id}', {include: 'user'})
            }
        },
        computed: {
            country_code:{
                get() {
                    return _.isObject(this.countryObj) ? this.countryObj.code : null;
                },
                set() {}
            },
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
                if (this.isFormDirty && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }

                if (this.reservationId && this.requirementId) {
                    window.location.href = `/${this.firstUrlSegment}/reservations/${this.reservationId}/requirements/${this.requirementId}`;
                } else {
                    window.location.href = `/${this.firstUrlSegment}/records/visas/${this.id}`;
                }

            },
            forceBack(){
                return this.back(true);
            },
            submit() {
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.showError = true;
                        return;
                    }

                    this.visasResource.post({}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        country_code: this.country_code,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Visa created.');
                        let that = this;
                        setTimeout(() => {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/visas/' + resp.data.data.id;
                            }

                        }, 1000);
                    }, (error) => {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create visa.');
                    });
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        return;
                    }

                    this.visasResource.put({id:this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        country_code: this.country_code,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/visas/' + that.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                });
            },
            uploadsComplete(data){
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
                    this.visasResource.get({ id: this.id }).then((response) => {
                        let visa = response.data.data;
                        $.extend(this, visa);
                        this.countryObj = _.findWhere(this.UTILITIES.countries, {code: visa.country_code});
                        this.userObj = visa.user;
                        this.usersArr.push(this.userObj);
                    });
                }
            });


        }

    }
</script>
