<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="UserSettings">
        <form id="UserSettingsForm" class="" novalidate>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Account</h5>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" :class="{ 'has-error': checkForError('name') }">
                                <div class="col-sm-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                                           placeholder="User Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                                           maxlength="100" minlength="1" required>
                                </div>
                            </div>
                            <div class="form-group" :class="{ 'has-error': checkForError('email') || errors.email }">
                                <div class="col-sm-12">
                                    <label for="name">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" v-model="email"
                                           v-validate:email="{ required: true, minlength:1, maxlength:100 }">
                                    <div v-show="errors.email" class="help-block">{{errors.email}}</div>
                                </div>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.alt_email }">
                                <div class="col-sm-12">
                                    <label for="name">Alt. Email</label>
                                    <input type="email" class="form-control" name="alt_email" id="alt_email" v-model="alt_email">
                                    <div v-show="errors.alt_email" class="help-block">{{errors.alt_email}}</div>
                                </div>
                            </div>

                            <div class="form-group" :class="{ 'has-error': !!changePassword && (checkForError('password')||checkForError('passwordconfirmation')) }">
                                <div class="col-sm-12">
                                    <label for="name">Password</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="changePassword">
                                            Change Password
                                        </label>
                                    </div>
                                    <div v-if="changePassword" class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group" :class="{ 'has-error': checkForError('password') }">
                                                <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="password"
                                                       v-validate:password="{ minlength:8 }" placeholder="Enter password">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" @click="showPassword=!showPassword">
                                                        <i class="fa fa-eye" v-if="!showPassword"></i>
                                                        <i class="fa fa-eye-slash" v-if="showPassword"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group" :class="{ 'has-error': checkForError('passwordconfirmation') }">
                                                <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="password_confirmation"
                                                       v-validate:passwordconfirmation="{ minlength:8 }" placeholder="Enter password again">
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

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Date of Birth</label>
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <select class="form-control" name="dob_month" v-model="dobMonth" required>
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <h6 class="help-block lightcolor">Month</h6>
                                        </div>
                                        <div class="col-xs-3">
                                            <select class="form-control" name="dob_day" v-model="dobDay" required>
                                                <option value="01">1</option>
                                                <option value="02">2</option>
                                                <option value="03">3</option>
                                                <option value="04">4</option>
                                                <option value="05">5</option>
                                                <option value="06">6</option>
                                                <option value="07">7</option>
                                                <option value="08">8</option>
                                                <option value="09">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                            </select>
                                            <h6 class="help-block lightcolor">Day</h6>
                                        </div>
                                        <div class="col-xs-4">
                                            <select class="form-control" name="dob_year" v-model="dobYear">
                                                <option value="1930">1930</option>
                                                <option value="1931">1931</option>
                                                <option value="1932">1932</option>
                                                <option value="1933">1933</option>
                                                <option value="1934">1934</option>
                                                <option value="1935">1935</option>
                                                <option value="1936">1936</option>
                                                <option value="1937">1937</option>
                                                <option value="1938">1938</option>
                                                <option value="1939">1939</option>
                                                <option value="1940">1940</option>
                                                <option value="1941">1941</option>
                                                <option value="1942">1942</option>
                                                <option value="1943">1943</option>
                                                <option value="1944">1944</option>
                                                <option value="1945">1945</option>
                                                <option value="1946">1946</option>
                                                <option value="1947">1947</option>
                                                <option value="1948">1948</option>
                                                <option value="1949">1949</option>
                                                <option value="1950">1950</option>
                                                <option value="1951">1951</option>
                                                <option value="1952">1952</option>
                                                <option value="1953">1953</option>
                                                <option value="1954">1954</option>
                                                <option value="1955">1955</option>
                                                <option value="1956">1956</option>
                                                <option value="1957">1957</option>
                                                <option value="1958">1958</option>
                                                <option value="1959">1959</option>
                                                <option value="1960">1960</option>
                                                <option value="1961">1961</option>
                                                <option value="1962">1962</option>
                                                <option value="1963">1963</option>
                                                <option value="1964">1964</option>
                                                <option value="1965">1965</option>
                                                <option value="1966">1966</option>
                                                <option value="1967">1967</option>
                                                <option value="1968">1968</option>
                                                <option value="1969">1969</option>
                                                <option value="1970">1970</option>
                                                <option value="1971">1971</option>
                                                <option value="1972">1972</option>
                                                <option value="1973">1973</option>
                                                <option value="1974">1974</option>
                                                <option value="1975">1975</option>
                                                <option value="1976">1976</option>
                                                <option value="1977">1977</option>
                                                <option value="1978">1978</option>
                                                <option value="1979">1979</option>
                                                <option value="1980">1980</option>
                                                <option value="1981">1981</option>
                                                <option value="1982">1982</option>
                                                <option value="1983">1983</option>
                                                <option value="1984">1984</option>
                                                <option value="1985">1985</option>
                                                <option value="1986">1986</option>
                                                <option value="1987">1987</option>
                                                <option value="1988">1988</option>
                                                <option value="1989">1989</option>
                                                <option value="1990" selected="selected">1990</option>
                                                <option value="1991">1991</option>
                                                <option value="1992">1992</option>
                                                <option value="1993">1993</option>
                                                <option value="1994">1994</option>
                                                <option value="1995">1995</option>
                                                <option value="1996">1996</option>
                                                <option value="1997">1997</option>
                                                <option value="1998">1998</option>
                                                <option value="1999">1999</option>
                                                <option value="2000">2000</option>
                                                <option value="2001">2001</option>
                                                <option value="2002">2002</option>
                                                <option value="2003">2003</option>
                                                <option value="2004">2004</option>
                                                <option value="2005">2005</option>
                                                <option value="2006">2006</option>
                                                <option value="2007">2007</option>
                                                <option value="2008">2008</option>
                                                <option value="2009">2009</option>
                                                <option value="2010">2010</option>
                                                <option value="2011">2011</option>
                                                <option value="2012">2012</option>
                                                <option value="2013">2013</option>
                                                <option value="2014">2014</option>
                                                <option value="2015">2015</option>
                                            </select>
                                            <h6 class="help-block lightcolor">Year</h6>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end form-group -->

                            <div class="row form-group" :class="{ 'has-error': checkForError('gender') }">
                                <label for="gender" class="col-sm-2">Gender</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" id="gender" value="Male" v-model="gender" v-validate:gender="{required: {rule: true}}"> Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender2" id="gender2" value="Female" v-model="gender" v-validate:gender> Female
                                    </label>
                                </div>
                            </div>

                            <div class="row form-group" :class="{ 'has-error': checkForError('status') }">
                                <label for="status" class="col-sm-2">Status</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="status" value="Single" v-model="status" v-validate:status="{required: {rule: true}}"> Single
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status2" id="status2" value="Married" v-model="status" v-validate:status> Married
                                    </label>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="public" class="col-sm-2">Public</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="public" id="public" :value="true" v-model="public"> Public
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="public2" id="public2" :value="false" v-model="public"> Private
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" :class="{ 'has-error': checkForError('url') || errors.url }" v-if="!!public">
                                <div class="col-sm-12">
                                    <label for="url" class="control-label">Url Slug</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">www.missions.me/users/</span>
                                        <input type="text" id="url" v-model="url" class="form-control" required v-validate:url="{ required: !!public }"/>
                                    </div>
                                    <div v-show="errors.url" class="help-block">{{errors.url}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Contact Info</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="bio">Bio</label>
                                    <textarea class="form-control" v-model="bio" id="bio" placeholder="User Bio" maxlength="120"></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="infoAddress">Address 1</label>
                                    <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="infoAddress2">Address 2</label>
                                    <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
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
                                    <div :class="{ 'has-error': checkForError('country') }">
                                        <label class="control-label" for="country" style="padding-top:0;margin-bottom: 5px;">Country</label>
                                        <v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                                        <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }" >
                                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" :class="{ 'has-error': checkForError('timezone') }">
                                <div class="col-sm-12">
                                    <label for="timezone" class="control-label">Timezone</label>
                                    <v-select class="form-control" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                                    <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate:timezone="{ required: true }">
                                        <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class=" form-group">
                                <div class="col-sm-6">
                                    <label for="infoPhone">Phone 1</label>
                                    <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                                </div>
                                <div class="col-sm-6">
                                    <label for="infoMobile">Phone 2</label>
                                    <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
                <div class="col-sm-12 text-center">
                    <hr class="divider inv lg">
                    <a href="/dashboard" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Update</a>
                    <hr class="divider inv xlg">
                </div><!-- end col -->
            </div><!-- end row -->
            <alert :show.sync="saved" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Well Done!</strong>
                <p>Profile updated successfully</p>
            </alert>
        </form>
    </validator>
</template>
<style>
    .alert.top, .alert.top-right {
        top: 80px;
    }
</style>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import vSelect from "vue-select";
    export default{
        name: 'user-settings',
        components: {vSelect, 'alert': VueStrap.alert},
        data(){
            return {
                name: '',
                email: '',
                alt_email: '',
                password: null,
                password_confirmation: null,
                bio: '',
                status: '',
                birthday: null,
                country_code: null,
                timezone: null,
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                url: null,
                gender: false,
                admin: false,

                // logic variables
//                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
                attemptSubmit: false,
                countries: [],
                countryCodeObj: null,
                timezones: [],
                changePassword: false,
                showPassword: false,
                timezoneObj: null,
                dobMonth: null,
                dobDay: null,
                dobYear: null,
                resource: this.$resource('users/me'),
                errors: {},
                saved: false,
            }
        },
        computed: {
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            },
            birthday() {
                return this.dobYear && this.dobMonth && this.dobDay
                        ? moment().set({year: this.dobYear, month: this.dobMonth, day:this.dobDay}).format('LL')
                        : null;
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UserSettings[field].invalid && this.attemptSubmit;
            },
            onModified(){
                this.saved = false;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$UserSettings.valid) {
                    this.resource.update({id: this.userId}, {
                        name: this.name,
                        email: this.email,
                        alt_email: this.alt_email,
                        password: this.changePassword ? this.password : undefined,
                        password_confirmation: this.changePassword ? this.password_confirmation : undefined,
                        bio: this.bio,
                        type: this.type,
                        country_code: this.country_code,
                        timezone: this.timezone,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        address_one: this.address_one,
                        address_two: this.address_two,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        status: this.status,
                        gender: this.gender,
                        public: this.public,
                        url: this.public ? this.url : undefined,
                    }).then(function (resp) {
//                        window.location.href = '/dashboard' + resp.data.data.links[0].uri;
                        this.saved = true;
                        setTimeout(function () {
                            this.saved = false;
                        }.bind(this), 3000);
                    }, function (error) {
                        console.log(error);
                        this.errors = error.data.errors;
                    });
                }
            }
        },
        ready(){
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/timezones').then(function (response) {
                this.timezones = response.data.timezones;
            });

            this.resource.get().then(function (response) {
                var user = response.data.data;
                this.name = user.name;
                this.bio = user.bio;
                this.type = user.type;
                this.countryCodeObj = _.findWhere(this.countries, {code: user.country_code});
                this.country_code = user.country_code;
                this.timezone = user.timezone;
                this.phone_one = user.phone_one;
                this.phone_two = user.phone_two;
                this.address_one = user.address_one;
                this.address_two = user.address_two;
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
            }, function (response) {
                console.log('Update Failed! :(');
                console.log(response);
            });

            this.$root.$on('save-settings', function () {
                this.submit();
            }.bind(this));
        }
    }
</script> 