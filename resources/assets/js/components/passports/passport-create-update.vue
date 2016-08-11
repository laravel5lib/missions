<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="CreateUpdatePassport">
        <form id="CreateUpdatePassport" class="form-horizontal" novalidate>
            <div class="form-group" :class="{ 'has-error': checkForError('givennames') }">
                <label for="given_names" class="col-sm-2 control-label">Given Names</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="given_names" id="given_names" v-model="given_names"
                           placeholder="Given Names" v-validate:givennames="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="150" minlength="1" required>
                </div>
            </div>
            <div class="form-group" :class="{ 'has-error': checkForError('surname') }">
                <label for="surname" class="col-sm-2 control-label">Surname</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                           placeholder="Surname" v-validate:surname="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group" :class="{ 'has-error': checkForError('number') }">
                <label for="number" class="col-sm-2 control-label">Passport Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="number" id="number" v-model="number"
                           placeholder="Passport Number" v-validate:number="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="9" required>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': (checkForError('issued') || checkForError('expires')) }">
                <label for="issued_at" class="col-sm-2 control-label">Dates</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group input-group-sms"
                                 :class="{ 'has-error': checkForError('issued') }">
                                <span class="input-group-addon">Issued</span>
                                <input type="date" class="form-control" v-model="issued_at" id="issued_at"
                                       v-validate:issued="{ required: true }" required>
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group input-group-sms"
                                 :class="{ 'has-error': checkForError('expires') }">
                                <span class="input-group-addon">Expires</span>
                                <input type="date" class="form-control" v-model="expires_at" id="expires_at"
                                       v-validate:expires="{ required: true }" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group" :class="{ 'has-error': checkForError('birth') }">
                <label for="birth" class="col-sm-2 control-label">Birth Country</label>
                <div class="col-sm-10">
                    <v-select class="form-control" id="birth" :value.sync="birthCountryObj" :options="countries" label="name"></v-select>
                    <select hidden name="birth" id="birth" class="hidden" v-model="birth_country" v-validate:birth="{ required: true }">
                        <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                    </select>

                </div>
            </div>
            <div class="form-group" :class="{ 'has-error': checkForError('citizenship') }">
                <label for="citizenship" class="col-sm-2 control-label">Citizenship</label>
                <div class="col-sm-10">
                    <v-select class="form-control" id="country" :value.sync="citizenshipObj" :options="countries" label="name"></v-select>
                    <select hidden name="citizenship" id="citizenship" class="hidden" v-model="citizenship" v-validate:citizenship="{ required: true }">
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
                    <upload-create-update type="avatar" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']"></upload-create-update>
                </panel>
            </accordion>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="/dashboard/passports" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>
    </validator>
</template>
<script>
    import vSelect from "vue-select";
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import adminUploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    export default{
        name: 'passport-create-update',
        components: {vSelect, 'upload-create-update': adminUploadCreateUpdate, 'accordion': VueStrap.accordion, 'panel': VueStrap.panel},
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
                birth_country: null,
                citizenship: null,
                upload_id: null,
                user_id: null,

                // logic vars
                countries: [],
                birthCountryObj: null,
                citizenshipObj: null,
                attemptSubmit: false,
                selectedAvatar: null
            }
        },
        computed: {
            birth_country(){
                return _.isObject(this.birthCountryObj) ? this.birthCountryObj.code : null;
            },
            citizenship(){
                return _.isObject(this.citizenshipObj) ? this.citizenshipObj.code : null;
            },
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUpdatePassport[field].invalid && this.attemptSubmit;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateUpdatePassport.valid) {
                    var resource = this.$resource('passports{/id}');
                    resource.save(null, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/passports';
                    }, function (error) {
                        debugger;
                    });
                }
            },
            update(){
                this.attemptSubmit = true;
                if (this.$CreateUpdatePassport.valid) {
                    var resource = this.$resource('passports{/id}');
                    resource.update({id:this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        number: this.number,
                        issued_at: this.issued_at,
                        expires_at: this.expires_at,
                        birth_country: this.birth_country,
                        citizenship: this.citizenship,
                        upload_id: this.upload_id,
                        user_id: this.user_id,
                    }).then(function (resp) {
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        window.location.href = '/dashboard/passports';
                    }, function (error) {
                        debugger;
                    });
                }
            },

        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'avatar':
                        this.selectedAvatar = data;
                        this.upload_id = data.id;
                        break;
                }
            }
        },
        ready(){
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            var fetchURL = this.isUpdate ? 'users/me?include=passports' : 'users/me';
            this.$http(fetchURL).then(function (response) {
                // this.user = response.data.data;
                this.user_id = response.data.data.id;

                if (this.isUpdate) {
                    var passport = _.findWhere(response.data.data.passports.data, {id: this.id});
                    $.extend(this, passport);

                    this.birthCountryObj = _.findWhere(this.countries, {code: passport.birth_country})
                    this.citizenshipObj = _.findWhere(this.countries, {code: passport.citizenship})
                }
            });
        }

    }
</script>
