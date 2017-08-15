<template>
    <div>
        <form id="UserSettingsForm" class="" novalidate style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Account</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-sm-6 tour-step-avatar">
                                    <label>Profile Photo (max file size:2mb)</label>
                                    <h5>
                                        <a href="#">
                                            <img class="av-left img-circle img-md" :src="avatar + '?w=64&h=64'" :alt="name" width="64">
                                        </a>
                                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button>
                                    </h5>
                                </div>
                                <hr class="divider inv visible-xs">
                                <div class="col-sm-6 tour-step-cover">
                                    <label>Cover Photo (max file size:2mb)</label>
                                    <h5>
                                        <a href="#">
                                            <img class="av-left img-rounded img-md"
                                                :src="banner + '?w=64&h=64&fit=crop-center'"
                                                :alt="name" width="64">
                                        </a>
                                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse"><i class="fa fa-camera"></i> Cover</button>
                                    </h5>
                                </div><!-- end col -->
                                <div class="col-sm-12">
                                    <div class="collapse" id="avatarCollapse">
                                        <div class="well">
                                            <upload-create-update v-if="!startUp" type="avatar" :name="id || 'avatar'" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :is-update="!!avatar_upload_id" :upload-id="avatar_upload_id" :tags="['User']"></upload-create-update>
                                        </div>
                                    </div>
                                    <div class="collapse" id="bannerCollapse">
                                        <div class="well">
                                            <upload-create-update v-if="!startUp" type="banner" :name="id || 'banner'" :lock-type="true" :ui-locked="true" :ui-selector="1" :per-page="6" :is-child="true" :tags="['User']"></upload-create-update>
                                        </div>
                                    </div>
                                    <hr class="divider inv" />
                                </div>
                            </div><!-- end row -->

                                <div class="row form-group" v-error-handler="{ value: name, handle: 'name' }">
                                    <div class="col-sm-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" v-model="name"
                                               placeholder="User Name" v-validate="'required|min:1|max:100'"
                                               maxlength="100" minlength="1" required>
                                    </div>
                                </div>
                                <div class="row form-group" v-error-handler="{ value: email, handle: 'email' }">
                                    <div class="col-sm-12">
                                        <label for="name">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" v-model="email"
                                               v-validate="'required|min:1|max:100'">
                                        <div v-show="errors.has('email')" class="help-block">{{errors.has('email')}}</div>
                                    </div>
                                </div>
                                <div class="row form-group" v-error-handler="{ value: alt_email, client: 'altemail', server: 'alt_email' }">
                                    <div class="col-sm-12">
                                        <label for="name">Alt. Email</label>
                                        <input type="email" class="form-control" name="alt_email" id="alt_email" v-model="alt_email">
                                        <div v-show="errors.has('altemail')" class="help-block">{{errors.has('altemail')}}</div>
                                    </div>
                                </div>

                                <div class="row form-group" :class="{ 'has-error': !!changePassword && (errors.has('password')|| errors.has('passwordconfirmation') || SERVER_ERRORS.password) }">
                                    <div class="col-sm-12">
                                        <label for="name">Password</label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="changePassword">
                                                Change Password
                                            </label>
                                        </div>
                                        <div v-if="changePassword" class="row" v-error-handler="{ value: password, handle: 'password' }">
                                            <div class="col-sm-6">
                                                <div class="input-group" :class="{ 'has-error': errors.has('password') }">
                                                    <input v-if="showPassword === 'text'" type="text" class="form-control" v-model="password"
                                                           name="password" placeholder="Enter password" v-validate="'required|min:8'">
                                                    <input v-else type="password" class="form-control" v-model="password"
                                                           name="password" placeholder="Enter password" v-validate="'required|min:8'">
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" @click="showPassword=!showPassword">
                                                        <i class="fa fa-eye" v-if="!showPassword"></i>
                                                        <i class="fa fa-eye-slash" v-if="showPassword"></i>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group" :class="{ 'has-error': errors.has('passwordconfirmation') }">
                                                    <input v-if="showPassword === 'text'" type="text" class="form-control" v-model="password_confirmation"
                                                           name="passwordconfirmation" placeholder="Enter password again" v-validate="'required|min:8'">
                                                    <input v-else type="password" class="form-control" v-model="password_confirmation"
                                                           name="passwordconfirmation" placeholder="Enter password again" v-validate="'required|min:8'">
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" @click="showPassword=!showPassword">
                                                        <i class="fa fa-eye" v-if="!showPassword"></i>
                                                        <i class="fa fa-eye-slash" v-if="showPassword"></i>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="changePassword" class="help-block">Password must be at least 8 characters long</div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label>Date of Birth</label>
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <select class="form-control" name="dob_month" v-model="dobMonth" required>
                                                    <option :value="1">January</option>
                                                    <option :value="2">February</option>
                                                    <option :value="3">March</option>
                                                    <option :value="4">April</option>
                                                    <option :value="5">May</option>
                                                    <option :value="6">June</option>
                                                    <option :value="7">July</option>
                                                    <option :value="8">August</option>
                                                    <option :value="9">September</option>
                                                    <option :value="10">October</option>
                                                    <option :value="11">November</option>
                                                    <option :value="12">December</option>
                                                </select>
                                                <h6 class="help-block lightcolor">Month</h6>
                                            </div>
                                            <div class="col-xs-3">
                                                <select class="form-control" name="dob_day" v-model="dobDay" required>
                                                    <option v-for="day in days" :value="day">{{ day }}</option>
                                                </select>
                                                <h6 class="help-block lightcolor">Day</h6>
                                            </div>
                                            <div class="col-xs-4">
                                                <select class="form-control" name="dob_year" v-model="dobYear">
                                                <option v-for="year in years" :value="year">{{ year }}</option>
                                                </select>
                                                <h6 class="help-block lightcolor">Year</h6>
                                            </div>
                                        </div>
                                    </div><!-- end col -->
                                </div><!-- end form-group -->

                                <div class="row form-group" v-error-handler="{ value: gender, handle: 'gender' }">
                                    <label for="gender" class="col-sm-3">Gender</label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline">
                                            <input type="radio" name="gender" id="gender" value="male" v-model="gender" v-validate="'required|in:male,female'"> Male
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="gender" id="gender2" value="female" v-model="gender"> Female
                                        </label>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="status" class="col-sm-3">Relationship Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control input-sm" v-model="status" id="status">
                                            <option value="single">Single</option>
                                            <option value="engaged">Engaged</option>
                                            <option value="married">Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="public" class="col-sm-3">Account Visibility</label>
                                    <div class="col-sm-9 tour-step-privacy">
                                        <label class="radio-inline">
                                            <input type="radio" name="public" id="public" :value="true" v-model="public"> Public
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="public2" id="public2" :value="false" v-model="public"> Private
                                        </label>
                                    </div>
                                </div>

                            <div class="form-group" v-error-handler="{ value: url, handle: 'url' }" v-if="!!public">
                                <div class="col-sm-12 tour-step-url">
                                    <label for="url" class="control-label">Url Slug</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">www.missions.me/</span>
                                        <input type="text" id="url" v-model="url" class="form-control" required name="url" v-validate="'required'"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 tour-step-contact">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Contact Info</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="bio">Bio</label>
                                    <textarea class="form-control" v-model="bio" id="bio" placeholder="User Bio" maxlength="120"></textarea>
                                    <span class="help-block">Characters left: {{120 - (bio ? bio.length : 0||0)}}</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="infoAddress">Address</label>
                                    <input type="text" class="form-control" v-model="address" id="infoAddress" placeholder="Street Address">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="infoCity">City</label>
                                    <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                                </div>
                                <div class="col-sm-6">
                                    <label for="infoState">State/Prov.</label>
                                    <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-4">
                                    <label for="infoZip">ZIP/Postal Code</label>
                                    <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                                </div>
                                <div class="col-sm-8">
                                    <div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                                        <label class="control-label" for="country" style="padding-top:0;margin-bottom: 5px;">Country</label>
                                        <v-select @keydown.enter.prevent="" v-validate="'required'" data-vv-name="country" data-vv-value-path="value" class="form-control" id="country" :value="countryCodeObj" :options="UTILITIES.countries" label="name"></v-select>
                                        <!--<select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate="'required'">
                                            <option :value="country.code" v-for="country in UTILITIES.countries">{{country.name}}</option>
                                        </select>-->
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group" v-error-handler="{ value: timezone, handle: 'timezone' }">
                                <div class="col-sm-12">
                                    <label for="timezone" class="control-label">Timezone</label>
                                    <v-select @keydown.enter.prevent="" class="form-control" id="timezone" v-validate="'required'" data-vv-name="timezone" data-vv-value-path="value" :value="timezone" :options="UTILITIES.timezones"></v-select>
                                    <!--<select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate="'required'">
                                        <option :value="timezone" v-for="timezone in UTILITIES.timezones">{{ timezone }}</option>
                                    </select>-->
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <phone-input v-model="phone_one" label="Phone 1"></phone-input>
                                    <!--<label for="infoPhone">Phone 1</label>-->
                                </div>
                                <div class="col-sm-6">
                                    <phone-input v-model="phone_two" label="Phone 2"></phone-input>
                                    <!--<label for="infoMobile">Phone 2</label>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 tour-step-social">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Social Media</h5>
                        </div>
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="facebook">Facebook</label>
                                        <input type="text" class="form-control" v-model="facebook" id="facebook" placeholder="facebook.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="twitter">Twitter</label>
                                        <input type="text" class="form-control" v-model="twitter" id="twitter" placeholder="twitter.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="instagram">Instagram</label>
                                        <input type="text" class="form-control" v-model="instagram" id="instagram" placeholder="instagram.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="linkedIn">LinkedIn</label>
                                        <input type="text" class="form-control" v-model="linkedIn" id="linkedIn" placeholder="linkedin.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="pintrest">Pinterest</label>
                                        <input type="text" class="form-control" v-model="pinterest" id="pinterest" placeholder="pinterest.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="google-plus">Google +</label>
                                        <input type="text" class="form-control" v-model="google" id="google" placeholder="plus.google.com/+/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="vimeo">Vimeo</label>
                                        <input type="text" class="form-control" v-model="vimeo" id="vimeo" placeholder="vimeo.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="youtube">YouTube</label>
                                        <input type="text" class="form-control" v-model="youtube" id="youtube" placeholder="youtube.com/username">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="website">Website</label>
                                        <input type="text" class="form-control" v-model="website" id="website" placeholder="www.my-website.com">
                                    </div>
                                </div>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                    </div><!-- end col -->
                    <div class="col-sm-12 text-center tour-step-save">
                        <hr class="divider inv lg">
                            <a @click="back()" class="btn btn-default">Cancel</a>
                        <a @click="submit()" class="btn btn-primary">Update</a>
                        <hr class="divider inv xlg">
                    </div><!-- end col -->
                </div><!-- end row -->

                <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                    <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
                </modal>
            </form>
    </div>
</template>
<!--<style>
    .alert.top, .alert.top-right {
        top: 80px;
    }
</style>-->
<script type="text/javascript">
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    import errorHandler from'../error-handler.mixin';
    import utilities from'../utilities.mixin';
    export default{
        name: 'user-settings',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
        mixins: [errorHandler, utilities],
        data(){
            return {
                id: this.$root.user.id,
                avatar: '',
                banner: '',
                name: '',
                email: '',
                alt_email: '',
                password: null,
                password_confirmation: null,
                bio: '',
                status: '',
                timezone: null,
                phone_one: '',
                phone_two: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                url: null,
                gender: false,
                admin: false,
                facebook: '',
                twitter: '',
                instagram: '',
                linkedIn: '',
                pinterest: '',
                google: '',
                vimeo: '',
                youtube: '',
                website: '',

                // logic variables
                startUp: true,
//                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
//                attemptSubmit: false,
                countryCodeObj: null,
                changePassword: false,
                showPassword: false,
                timezoneObj: null,
                dobMonth: null,
                dobDay: null,
                dobYear: null,
                resource: this.$resource('users/' + this.$root.user.id + '?include=links'),
                // errors: {},
                showError: false,
                showSuccess: false,
                showSaveAlert: false,
                hasChanged: false,

                selectedAvatar: null,
                avatar_upload_id: null,
                selectedBanner: null,
                banner_upload_id: null,
            }
        },
        computed: {
            country_code: {
                get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                },
                set() {},
            },
            birthday() {
                return this.dobYear && this.dobMonth && this.dobDay
                        ? moment(this.dobYear+'-'+this.dobMonth+'-'+this.dobDay).format('YYYY-MM-DD 00:00:00')
                        : null;
            },
            years() {
                let currentYear = new moment().subtract(8, 'years').year(), years = [];
                let startYear = new moment().subtract(100, 'years').year();

                while ( startYear <= currentYear ) {
                        years.push(startYear++);
                } 

                return years;
            },
            days() {
                let day = 1, days = [];
                while ( day <= 31 ) {
                        days.push(day++);
                } 

                return days;
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
                    case 'banner':
                        this.selectedBanner = data;
                        this.banner_upload_id = data.id;
                        jQuery('#bannerCollapse').collapse('hide');
                        break;
                }
                this.submit();
            }
        },
        methods: {
            onTouched(){
                this.hasChanged = true;
            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

                    this.resource.update({
                        name: this.name,
                        email: this.email,
                        alt_email: this.alt_email,
                        password: this.changePassword ? this.password : undefined,
                        password_confirmation: this.changePassword ? this.password_confirmation : undefined,
                        bio: this.bio,
                        type: this.type,
                        birthday: this.birthday,
                        country_code: this.country_code,
                        timezone: this.timezone,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        address: this.address,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        status: this.status,
                        gender: this.gender,
                        public: this.public,
                        url: this.public ? this.url : undefined,
                        avatar_upload_id: this.avatar_upload_id,
                        banner_upload_id: this.banner_upload_id,
                        links: [
                            {
                                name: 'facebook',
                                url: this.facebook
                            },
                            {
                                name: 'twitter',
                                url: this.twitter
                            },
                            {
                                name: 'instagram',
                                url: this.instagram
                            },
                            {
                                name: 'linkedin',
                                url: this.linkedIn
                            },
                            {
                                name: 'website',
                                url: this.website
                            }
                        ]
                }).then((response) => {
                        this.setUserData(response.data.data);
                        this.$root.$emit('showSuccess', 'Settings updated successfully.');
                        this.hasChanged = false;

                        // update page profile links
                        if (this.public) {
                            let settingsProfileButton = document.getElementById('settings-profile-link');
                            if (settingsProfileButton)
                                settingsProfileButton.attributes.href.value = '/' + response.data.data.url;

                            let topProfileButton = document.getElementById('top-profile-link');
                            if (topProfileButton)
                                topProfileButton.attributes.href.value = '/' + response.data.data.url;

                            let menuProfileButton = document.getElementById('menu-profile-link');
                            if (menuProfileButton)
                                menuProfileButton.attributes.href.value = '/' + response.data.data.url;
                        }

                    }, (error) =>  {
                        console.log(error);
                        this.SERVER_ERRORS = error.data.errors;
                        this.$root.$emit('showError', 'Please check the form.');
                    });
                })
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/dashboard';
            },
            forceBack(){
                return this.back(true);
            },
            setUserData(user){
                this.id = user.id;
                this.avatar = user.avatar;
                this.banner = user.banner;
                this.name = user.name;
                this.bio = user.bio;
                this.type = user.type;
                this.countryCodeObj = _.findWhere(this.UTILITIES.countries, {code: user.country_code});
                console.log(this.countryCodeObj);
                this.country_code = user.country_code;
                this.timezone = user.timezone;
                this.phone_one = user.phone_one;
                this.phone_two = user.phone_two;
                this.address = user.address;
                this.city = user.city;
                this.state = user.state;
                this.zip = user.zip;
                this.public = user.public;
                this.url = user.url;
                this.email = user.email;
                this.alt_email = user.alt_email;
                this.gender = user.gender;
                this.status = user.status;
                this.alt_email = user.alt_email;
                this.avatar_upload_id = user.avatar_upload_id;
                this.banner_upload_id = user.banner_upload_id;
                this.facebook = _.findWhere(user.links.data, {name: "facebook"}) ? _.findWhere(user.links.data, {name: "facebook"}).url : null;
                this.twitter = _.findWhere(user.links.data, {name: "twitter"}) ? _.findWhere(user.links.data, {name: "twitter"}).url : null;
                this.instagram = _.findWhere(user.links.data, {name: "instagram"}) ? _.findWhere(user.links.data, {name: "instagram"}).url : null;
                this.linkedIn = _.findWhere(user.links.data, {name: "linkedin"}) ? _.findWhere(user.links.data, {name: "linkedin"}).url : null;
                this.website = _.findWhere(user.links.data, {name: "website"}) ? _.findWhere(user.links.data, {name: "website"}).url : null;
                this.dobDay = moment(user.birthday).format('D');
                this.dobMonth = moment(user.birthday).format('M');
                this.dobYear = moment(user.birthday).format('YYYY');
            }
        },
        mounted(){
            let promises = [];
            promises.push(this.getCountries());
            promises.push(this.getTimezones());

            this.$http.all(promises).then((values) => {
                this.resource.get().then((response) => {
                    this.setUserData(response.data.data);
                    this.startUp = false;
                }).catch(this.$root.handleApiError);
            });

            this.$root.$on('save-settings', () =>  {
                this.submit();
            });
        }
    }
</script> 