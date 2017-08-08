<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="CreateUser" :groups="['passwordGroup']">
        <form id="CreateUserForm" class="form-horizontal" novalidate style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
                <div class="col-sm-12">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name" debounce="250"
                           placeholder="User Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div v-error-handler="{ value: email, handle: 'email' }">
                        <label for="name" class="control-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" v-model="email"
                           v-validate:email="{ required: true, minlength:1, maxlength:100 }">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="name" class="control-label">Alt. Email</label>
                    <input type="email" class="form-control" name="alt_email" id="alt_email" v-model="alt_email">
                </div>
            </div>

            <div class="row" :class="{ 'has-error': checkForError('password')||checkForError('passwordconfirmation') }">
                <div class="col-sm-12">
                    <label for="name" class="control-label">Password</label>
                    <div class="row" v-error-handler="{ value: password, handle: 'password' }">
                        <div class="col-sm-6">
                            <div class="input-group" :class="{ 'has-error': checkForError('password') }">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="password"
                                       v-validate:password="{ required: true, minlength:8 }" placeholder="Enter password"
                                       group="passwordGroup">
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
                                       v-validate:passwordconfirmation="{ required: true, minlength:8 }" placeholder="Enter password again"
                                       group="passwordGroup">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" @click="showPassword=!showPassword">
                                        <i class="fa fa-eye" v-if="!showPassword"></i>
                                        <i class="fa fa-eye-slash" v-if="showPassword"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="help-block" v-if="$CreateUser.passwordGroup.invalid && attemptSubmit">Passwords do not match!</div>
                    <div class="help-block">Password must be at least 8 characters long</div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label">Date of Birth</label>
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
            <div class="row">
                <div v-error-handler="{ value: gender, handle: 'gender' }">
                    <div class="col-sm-6">
                        <label for="gender" class="control-label">Gender</label><br>
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="gender" value="Male" v-model="gender" v-validate:gender="{required: {rule: true}}"> Male
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender2" id="gender2" value="Female" v-model="gender" v-validate:gender> Female
                        </label>
                    </div>
                </div>
                <div v-error-handler="{ value: status, handle: 'status' }">
                    <div class="col-sm-6">
                        <label for="status" class="control-label">Status</label><br>
                        <label class="radio-inline">
                            <input type="radio" name="status" id="status" value="Single" v-model="status" v-validate:status="{required: {rule: true}}"> Single
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="status2" id="status2" value="Married" v-model="status" v-validate:status> Married
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="bio">Bio</label>
                    <textarea class="form-control" v-model="bio" id="bio" placeholder="User Bio" maxlength="120"></textarea>
                    <span class="help-block">Characters left: {{120 - (bio.length||0)}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="infoAddress">Address</label>
                    <input type="text" class="form-control" v-model="address" id="infoAddress" placeholder="Street Address">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div>
                        <label for="infoCity">City</label>
                        <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div>
                        <label for="infoState">State/Prov.</label>
                        <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div>
                        <label for="infoZip">ZIP/Postal Code</label>
                        <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                        <label class="control-label" for="country" style="padding-top:0;margin-bottom: 5px;">Country</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }" >
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div  v-error-handler="{ value: timezone, handle: 'timezone' }">
                        <label for="timezone" class="control-label">Timezone</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                        <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate:timezone="{ required: true }">
                            <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div>
                        <label for="infoPhone">Phone 1</label>
                        <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div>
                        <label for="infoMobile">Phone 2</label>
                        <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="public" class="control-label">Public</label><br>
                    <label class="radio-inline">
                        <input type="radio" name="public" id="public" :value="true" v-model="public"> Public
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="public2" id="public2" :value="false" v-model="public"> Private
                    </label>
                </div>
            </div>
            <div class="row" v-if="!!public">
                <div class="col-sm-12" v-error-handler="{ value: url, handle: 'url' }">
                    <label for="url" class="control-label">Url Slug</label>
                    <div class="input-group">
                        <span class="input-group-addon">www.missions.me/</span>
                        <input type="text" id="url" v-model="url" class="form-control" required v-validate:url="{ required: !!public }"/>
                    </div>
                </div>
            </div>
            <hr class="divider inv">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="/admin/users" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Create</a>
                </div>
            </div>
            <alert :show.sync="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
                <span class="icon-info-circled alert-icon-float-left"></span>
                <strong>Oh No!</strong>
                <p>There are errors on the form.</p>
            </alert>
        </form>
    </validator>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'admin-user-create',
        components: {vSelect},
        mixins: [errorHandler],
        data(){
            return {
                name: '',
                email: '',
                alt_email: '',
                password: '',
                password_confirmation: '',
                bio: '',
                status: '',
                birthday: null,
                country_code: null,
                timezone: null,
                phone_one: '',
                phone_two: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                url: '',
                gender: false,
                admin: false,

                // logic variables
//                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
                attemptSubmit: false,
                showError: false,
                countries: [],
                countryCodeObj: null,
                timezones: [],
                showPassword: false,
                timezoneObj: null,
                dobMonth: null,
                dobDay: null,
                dobYear: null,

                // mixin settings
                validatorHandle: 'CreateUser',
            }
        },
        watch: {
            'name': function (val) {
                if (typeof val === 'string') {
                    // pre-populate slug
                    this.$http.get('utilities/make-slug/' + val, { params: { hideLoader: true } }).then(function (response) {
                        this.url = response.body.slug;
                    });
                }
            }
        },
        computed: {
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            },
            birthday() {
                return this.dobYear && this.dobMonth && this.dobDay
                        ? moment(this.dobMonth + '-' + this.dobDay + '-' + this.dobYear, 'MM-DD-YYYY').format('YYYY-MM-DD')
                        : null;
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateUser[field].invalid && this.attemptSubmit;
            },
            submit(){
                this.resetErrors();
                if (this.$CreateUser.valid) {
                    let resource = this.$resource('users');

                    resource.save(null, {
                        name: this.name,
                        email: this.email,
                        alt_email: this.alt_email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        bio: this.bio,
                        birthday: this.birthday,
                        type: this.type,
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
                    }).then(function (resp) {
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                    }, function (error) {
                        this.errors = error.data.errors
                        this.showError = true;
                        console.log(error);
                    });
                } else {
                    this.showError = true;
                }
            }
        },
        mounted(){
            let countriesPromise = this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.body.countries;
            });

            let timezonesPromise = this.$http.get('utilities/timezones').then(function (response) {
                this.timezones = response.body.timezones;
            });
        }
    }
</script> 