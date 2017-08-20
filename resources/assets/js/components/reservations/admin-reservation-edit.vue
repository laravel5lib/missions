<template >
    <div>
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
                            <upload-create-update type="avatar" :lock-type="true" :is-child="true" :tags="['campaign']" @uploads-complete="uploadsComplete"></upload-create-update>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: given_names, client: 'givennames', server: 'given_names' }">
                        <label for="given_names">Given Names</label>
                        <input type=    "text" class="form-control" id="given_names" v-model="given_names"
                               placeholder="Given Names" name="givennames" v-validate="'required|min:1|max:100'"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: surname, handle: 'surname' }">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Surname" v-validate="'required|min:1|max:100'"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>Date of Birth</label>
                    <date-picker v-if="loaded" :model="birthday|moment('YYYY-MM-DD', false, true)"></date-picker>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: shirt_size, client: 'size', server: 'shirt_size' }">
                        <label for="infoShirtSize">Shirt Sizes</label>
                        <select class="form-control" v-model="shirt_size" name="size" v-validate="'required'"
                                id="infoShirtSize">
                            <option value="XS">XS (Extra Small)</option>
                            <option value="S">S (Small)</option>
                            <option value="M">M (Medium)</option>
                            <option value="L">L (Large)</option>
                            <option value="XL">XL (Extra Large)</option>
                            <option value="XXL">XXL (2 Extra Large)</option>
                            <option value="XXXL">XXXL (3 Extra Large)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>Gender</label>
                    <div class="radios" v-error-handler="{ value: gender, handle: 'gender' }">
                        <label>
                            <input type="radio" v-model="gender" name="gender" v-validate="'required'"
                                   value="male"> Male
                        </label>
                    </div>
                    <div class="radios" v-error-handler="{ value: gender, handle: 'gender' }">
                        <label>
                            <input type="radio" v-model="gender" name="gender" value="female" v-validate=""> Female
                        </label>
                    </div>
                    <span class="help-block" v-show= "errors.has('gender')">Select a gender</span>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: status, handle: 'status' }">
                        <label for="infoRelStatus">Relationship Status</label>
                        <select class="form-control" v-model="status" name="status" id="infoRelStatus" v-validate="'required'">
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
                    <!--<input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_one" name="phone" id="infoPhone"></phone-input>
                </div>
                <div class="col-sm-6">
                    <label for="infoMobile">Phone 2</label>
                    <!--<input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_two" name="mobile" id="infoMobile"></phone-input>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" v-error-handler="{ value: email, handle: 'email' }">
                    <label for="infoEmail">Email</label>
                    <input type="email" class="form-control" v-model="email" id="infoEmail" name="email" placeholder="Email" v-validate="'email'">
                </div>
                <div class="col-sm-6">
                    <label for="infoAddress">Address 1</label>
                    <input type="text" class="form-control" v-model="address" id="infoAddress" name="address" placeholder="Street Address 1" v-validate="">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="infoCity">City</label>
                    <input type="text" class="form-control" v-model="city" id="infoCity" name="city" placeholder="City" v-validate="">
                </div>
                <div class="col-sm-6">
                    <label for="infoState">State/Prov.</label>
                    <input type="text" class="form-control" v-model="state" id="infoState" name="state" placeholder="State/Province" v-validate="">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="infoZip">ZIP/Postal Code</label>
                    <input type="text" class="form-control" v-model="zip" id="infoZip" name="zip" placeholder="12345" v-validate="">
                </div>
                <div class="col-sm-8">
                    <div>
                        <label for="country">Country</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="country" v-model="countryCodeObj" :options="UTILITIES.countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="" v-model="country_code">
                            <option :value="country.code" v-for="country in UTILITIES.countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: user_id, client: 'user', server: 'user_id' }">
                        <label for="manager">Managing User</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" v-model="userObj" :options="users" :debounce="250"
                                  :on-search="searchUsers" label="name"></v-select>
                        <select id="manager" hidden class="form-control hidden" v-model="user_id" name="user" v-validate="'required'">
                            <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div v-error-handler="{ value: companion_limit, client: 'companions', server: 'companion_limit' }">
                        <label for="companions">Companions</label>
                        <input type="number" number class="form-control" name="companions" v-model="companion_limit" id="companions" v-validate="'required'">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="form-group" :class="{ 'has-error': errors.has('desiredrole') }">
                        <label for="desiredRole">Desired Team Role</label>
                        <select class="form-control input-sm" id="desiredRole" v-model="desired_role" name="desiredrole" v-validate="'required'">
                            <option v-for="role in UTILITIES.roles" :value="role.value">{{role.name}}</option>
                        </select>
                    </div><!-- end form-group -->
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <br>
                    <a @click="update" class="btn btn-primary">Save</a>
                    <a @click="back" class="btn btn-default">Done</a>
                </div>
            </div>

        </form>
        <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>
    </div>


</template>
<script type="text/javascript">
    import vSelect from 'vue-select';
    import uploadCreateUpdate from '../../components/uploads/admin-upload-create-update.vue';
    import errorHandler from'../error-handler.mixin';
    import utilities from '../utilities.mixin';
    export default{
        name: 'admin-reservation-edit',
        props: ['id'],
        components: { vSelect, 'upload-create-update': uploadCreateUpdate },
        mixins: [utilities, errorHandler],
        data(){
            return{
                // mixin settings
                validatorHandle: 'UpdateReservation',

                given_names: '',
                surname: '',
                gender: '',
                birthday: '',
                shirt_size: '',
//                user_id: null,
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
//                attemptSubmit: false,
                loaded: false,
                resource: this.$resource('reservations{/id}', {id: this.id, include: 'user'}),
                users: [],
                selectedAvatar: null,
                avatar_upload_id: null,
                countryCodeObj: null,
                userObj: null,
//                errors: [],
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
            onTouched(){
                this.hasChanged = true;
            },
            searchUsers(search, loading){
                loading(true);
                this.$http.get('users', { params: { search: search} }).then((response) => {
                    this.users = response.data.data;
                    loading(false);
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.resource.put({id: this.id}, {
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
                        }).then((response) => {
                            $.extend(this, response.data.data);
                            this.$root.$emit('showSuccess', 'Reservation updated!');
                            this.hasChanged = false;
                            this.desired_role = response.data.data.desired_role.code;
                        }, (error) => {
                            this.errors = error.data.errors;
                            this.$root.$emit('showError', 'There are errors on the form.');
                        });
                    }
                    {
                        this.showError = true;
                    }
                });
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
            uploadsComplete(data){
                switch(data.type){
                    case 'avatar':
                        this.selectedAvatar = data;
                        this.avatar_upload_id = data.id;
                        jQuery('#avatarCollapse').collapse('hide');
                        break;
                }
            },
        },
        mounted(){
            this.getCountries();
            this.getRoles();

            this.resource.get().then((response) => {
                var reservation = response.data.data;
                this.given_names = reservation.given_names;
                this.surname = reservation.surname;
                this.gender = reservation.gender;
                this.birthday = reservation.birthday;
                this.status = reservation.status;
//                this.birthday = moment(reservation.birthday).toDate();
                this.shirt_size = reservation.shirt_size;
                this.companion_limit = reservation.companion_limit;
                this.countryCodeObj = _.findWhere(this.UTILITIES.countries, {code: reservation.country_code.toLowerCase()});
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
