<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="CreateUpdateVisa" @touched="onTouched">
        <form id="CreateUpdateVisa" class="form-horizontal" novalidate>
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="row">
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('givennames') }">
                        <label for="given_names" class="control-label">Given Names</label>
                        <input type="text" class="form-control" name="given_names" id="given_names" v-model="given_names"
                               placeholder="Given Names" v-validate:givennames="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="150" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('surname') }">
                        <label for="surname" class="control-label">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Surname" v-validate:surname="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div>
            <div class="form-group" :class="{ 'has-error': checkForError('number') }">
                <div class="col-sm-12">
                    <label for="number" class="control-label">Visa Number</label>
                    <input type="text" class="form-control" name="number" id="number" v-model="number"
                           placeholder="Visa Number" v-validate:number="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="9" required>
                </div>
            </div>

            <div class="row" :class="{ 'has-error': (checkForError('issued') || checkForError('expires')) }">
                <div class="col-sm-12">
                    <label for="issued_at" class="control-label">Dates</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group input-group-sms"
                                 :class="{ 'has-error': checkForError('issued') }">
                                <span class="input-group-addon">Issued</span>
                                <date-picker class="form-control input-sms" :time.sync="issued_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                <input type="datetime" class="form-control hidden" v-model="issued_at" id="issued_at" :max="today"
                                       v-validate:issued="{ required: true }" required>
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group input-group-sms"
                                 :class="{ 'has-error': checkForError('expires') }">
                                <span class="input-group-addon">Expires</span>
                                <date-picker class="form-control input-sms" :time.sync="expires_at|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                <input type="datetime" class="form-control hidden" v-model="expires_at" id="expires_at" :min="tomorrow"
                                       v-validate:expires="{ required: true }" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group" :class="{ 'has-error': checkForError('country') }">
                <div class="col-sm-12">
                    <label for="country" class="control-label">Country</label>
                    <v-select class="form-control" id="countryObj" :value.sync="countryObj" :options="countries" label="name"></v-select>
                    <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }">
                        <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                    </select>

                </div>
            </div>

            <accordion :one-at-atime="true">
                <panel header="Avatar" :is-open.sync="true">
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
                        <upload-create-update type="other" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" :name="'visa-'+given_names+'-'+surname"></upload-create-update>
                    </div><!-- end panel-body -->
                </panel>
            </accordion>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" href="/dashboard/records/visas" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-default">Cancel</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>
        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>Profile updated</p>
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
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    export default{
        name: 'visa-create-update',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        props: {
            isUpdate: {
                type:Boolean,
                default: false
            },
            id: {
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
                country_code: null,
                upload_id: null,
                user_id: null,

                // logic vars
                countries: [],
                countryObj: null,
                attemptSubmit: false,
                showSuccess: false,
                showError: false,
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow:moment().add(1, 'days').format('YYYY-MM-DD'),
                visasResource: this.$resource('visas{/id}')
            }
        },
        computed: {
            country_code(){
                return _.isObject(this.countryObj) ? this.countryObj.code : null;
            },
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdateVisa[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard/records/visas/';
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateVisa.valid) {
                    // this.$refs.spinner.show();
                    this.visasResource.save(null, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        country_code: this.country_code,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
                        this.showSuccess = true;
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/records/visas';
                    }, function (error) {
                        this.showError = true;
                        // this.$refs.spinner.hide();
                        debugger;
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                this.attemptSubmit = true;
                if (this.$CreateUpdateVisa.valid) {
                    // this.$refs.spinner.show();
                    this.visasResource.update({id:this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        country_code: this.country_code,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        // window.location.href = '/dashboard/visas';
                        // this.$refs.spinner.hide();
                    }, function (error) {
                        // this.$refs.spinner.hide();
                        debugger;
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
                this.countries = response.data.countries;
            });

            this.user_id = this.$root.user.id;

            if (this.isUpdate) {
                this.visasResource.get({ id: this.id }).then(function (response) {
                    let visa = response.data.data;
                    $.extend(this, visa);
                    this.countryObj = _.findWhere(this.countries, {code: visa.country_code});
                });
            }
        }

    }
</script>
