<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="UpdateReservation" @touched="onTouched">
        <form id="UpdateReservation" novalidate class="form-horizontal">
            <div class="row">
                <div class="col-sm-12">
                    <h5>
                        <img class="av-left img-circle img-md" :src="selectedAvatar ? (selectedAvatar.source + '?w=100&q=50') : ''" width="100" :alt="selectedAvatar ? selectedAvatar.name : ''">
                        {{selectedAvatar ? selectedAvatar.name : ''}}
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse">
                            <i class="fa fa-camera icon-left"></i> Set Avatar
                        </button>
                    </h5>
                    <div class="collapse" id="avatarCollapse">
                        <div class="well">
                            <upload-create-update type="avatar" :lock-type="true" :is-child="true" :tags="['campaign']"></upload-create-update>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('givennames') }">
                        <label for="given_names">Given Names</label>
                        <input type=    "text" class="form-control" name="given_names" id="given_names" v-model="given_names"
                               placeholder="Given Names" v-validate:givennames="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('surname')  }">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                           placeholder="Surname" v-validate:surname="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>Date of Birth</label>
                    <date-picker v-if="loaded" class="form-control" :time.sync="birthday|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                </div>
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('size') }">
                        <label for="infoShirtSize">Shirt Sizes</label>
                        <select class="form-control" v-model="shirt_size" v-validate:size="{ required: true }" :classes="{ invalid: 'has-error' }"
                                id="infoShirtSize">
                            <option value="S">S (Small)</option>
                            <option value="M">M (Medium)</option>
                            <option value="L">L (Large)</option>
                            <option value="XL">XL (Extra Large)</option>
                            <option value="XXL">XXL (2 Extra Large)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>Gender</label>
                    <div class="radios" :class="{ 'has-error': checkForError('gender') }">
                        <label>
                            <input type="radio" v-model="gender" v-validate:gender="{ required: { rule: true} }"
                                   value="male"> Male
                        </label>
                    </div>
                    <div class="radios" :class="{ 'has-error': checkForError('gender') }">
                        <label>
                            <input type="radio" v-model="gender" v-validate:gender value="female"> Female
                        </label>
                    </div>
                    <span class="help-block" v-show="checkForError('gender')">Select a gender</span>
                </div>
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('status') }">
                        <label for="infoRelStatus">Relationship Status</label>
                        <select class="form-control" v-model="status"
                                v-validate:status="{ required: true }" :classes="{ invalid: 'has-error' }" id="infoRelStatus">
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="infoPhone">Phone 1</label>
                    <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                </div>
                <div class="col-sm-6">
                    <label for="infoMobile">Phone 2</label>
                    <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('email') }">
                    <label for="infoEmail">Email</label>
                    <input type="email" class="form-control" v-model="email" id="infoEmail" v-validate:email="['email']" placeholder="Email˚">
                </div>
                <div class="col-sm-6">
                    <label for="infoAddress">Address 1</label>
                    <input type="text" class="form-control" v-model="address" id="infoAddress" v-validate:address="{}" placeholder="Street Address 1">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="infoCity">City</label>
                    <input type="text" class="form-control" v-model="city" id="infoCity" v-validate:city="{}" placeholder="City">
                </div>
                <div class="col-sm-6">
                    <label for="infoState">State/Prov.</label>
                    <input type="text" class="form-control" v-model="state" id="infoState" v-validate:state="{}" placeholder="State/Province">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="infoZip">ZIP/Postal Code</label>
                    <input type="text" class="form-control" v-model="zip" id="infoZip" v-validate:zip="{}" placeholder="12345">
                </div>
                <div class="col-sm-8">
                    <div>
                        <label for="country">Country</label>
                        <v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="" v-model="country_code">
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('user') }">
                        <label for="manager">Managing User</label>
                        <v-select class="form-control" :value.sync="userObj" :options="users" :debounce="250"
                                    :on-search="searchUsers" label="name"></v-select>
                        <select id="manager" hidden class="form-control hidden" v-model="user_id" v-validate:user="{require:true}">
                            <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div :class="{ 'has-error': checkForError('companions') }">
                        <label for="companions">Companions</label>
                        <input type="number" number class="form-control" v-validate:companions="{ require: true }" v-model="companion_limit" id="companions">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="form-group" :class="{ 'has-error': checkForError('desiredrole') }">
                        <label for="desiredRole">Desired Team Role</label>
                        <select class="form-control input-sm" id="desiredRole" v-model="desired_role" v-validate:desiredrole="{ required: true }">
                            <option v-for="role in roles" :value="role.value">{{role.name}}</option>
                        </select>
                    </div><!-- end form-group -->
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <br>
                    <a @click="update" class="btn btn-primary">Save</a>
                    <a @click="back()" class="btn btn-default">Done</a>
                </div>
            </div>

        </form>
        <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>
    </validator>
</template>
<script type="text/javascript">
    import vSelect from 'vue-select';
    import uploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    export default{
        name: 'admin-reservation-edit',
        props: ['id'],
        components: { vSelect, 'upload-create-update': uploadCreateUpdate },
        data(){
            return{
                given_names: '',
                surname: '',
                gender: '',
                birthday: '',
                shirt_size: '',
                user_id: null,
                desired_role: '',
                status: '',
                companion_limit: 0,

                address: '',
                city: '',
                state: '',
                zip: '',
                country_code : '',
                phone_one: '',
                phone_two: '',
                email: '',

                // logic vars
                attemptSubmit: false,
                loaded: false,
                resource: this.$resource('reservations{/id}', {id: this.id, include: 'user'}),
                users: [],
                selectedAvatar: null,
                avatar_upload_id: null,
                countryCodeObj: null,
                userObj: null,
                errors: [],
                countries: [],
                roles: [],
				showSuccess: false,
				showError: false,
                showSaveAlert: false,
                hasChanged: false
            }
        },
        computed:{
            user_id(){
                return _.isObject(this.userObj) ? this.userObj.id : null;
            },
            country_code() {
                if (_.isObject(this.countryCodeObj)) {
                    return this.countryCodeObj.code;
                }
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UpdateReservation[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            searchUsers(search, loading){
                loading(true);
                this.$http.get('users', { search: search}).then(function (response) {
                    this.users = response.data.data;
                    loading(false);
                });
            },
            update(){
                // Touch fields for proper validation
                if ( _.isFunction(this.$validate) )
                    this.$validate(true);

                this.attemptSubmit = true;
                if (this.$UpdateReservation.valid) {
                    this.resource.update({id: this.id}, {
                        given_names: this.given_names,
                        surname: this.surname,
                        gender: this.gender,
                        birthday: this.birthday,
                        status: this.status,
                        shirt_size: this.shirt_size,
                        address: this.address,
                        email: this.email,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        country_code: this.country_code,
                        companion_limit: this.companion_limit,
                        avatar_upload_id: this.avatar_upload_id,
                        user_id: this.user_id,
                        desired_role: this.desired_role,
                    }).then(function (response) {
                        $.extend(this, response.data.data);
                        this.$root.$emit('showSuccess', 'Reservation updated!');
						this.hasChanged = false;
                        this.desired_role = response.data.data.desired_role.code;
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'There are errors on the form.');
                    });
                } {
                    this.showError = true;
                }
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/admin/reservations/' + this.id;
            },
            forceBack(){
                return this.back(true);
            },

        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'avatar':
                        this.selectedAvatar = data;
                        this.avatar_upload_id = data.id;
                        jQuery('#avatarCollapse').collapse('hide');
                        break;
                }
            }
        },
        ready(){
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/team-roles').then(function (response) {
                _.each(response.data.roles, function (name, key) {
                    this.roles.push({ value: key, name: name});
                }.bind(this));
            });

            this.resource.get().then(function (response) {
                var reservation = response.data.data;
                this.given_names = reservation.given_names;
                this.surname = reservation.surname;
                this.gender = reservation.gender;
                this.birthday = reservation.birthday;
                this.status = reservation.status;
//                this.birthday = moment(reservation.birthday).toDate();
                this.shirt_size = reservation.shirt_size;
                this.companion_limit = reservation.companion_limit;
                this.countryCodeObj = _.findWhere(this.countries, {code: reservation.country_code.toLowerCase()});
                this.country_code = reservation.country_code;
                this.phone_one = reservation.phone_one;
                this.phone_two = reservation.phone_two;
                this.address = reservation.address;
                this.city = reservation.city;
                this.state = reservation.state;
                this.zip = reservation.zip;
                this.email = reservation.email;
                this.userObj = reservation.user.data;
                this.selectedAvatar = {source: reservation.avatar};
                this.desired_role = reservation.desired_role.code;

                this.loaded = true;
            })
        }
    }
</script>
