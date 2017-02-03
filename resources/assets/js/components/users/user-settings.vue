<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
    <validator name="UserSettings" @touched="onTouched">
        <form id="UserSettingsForm" class="" novalidate style="position:relative;">
            <spinner v-ref:spinner size="sm" text="Loading"></spinner>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Account</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Profile Photo (max file size:2mb)</label>
                                    <h5>
                                        <a href="#">
                                            <img class="av-left img-circle img-md" :src="avatar + '?w=64&h=64'" :alt="name" width="64">
                                        </a>
                                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button>
                                    </h5>
                                </div>
                                <hr class="divider inv visible-xs">
                                <div class="col-sm-6">
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
                                            <upload-create-update type="avatar" :name="id" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['User']"></upload-create-update>
                                        </div>
                                    </div>
                                    <div class="collapse" id="bannerCollapse">
                                        <div class="well">
                                            <upload-create-update type="banner" :name="id" :lock-type="true" :ui-locked="true" :ui-selector="1" :per-page="6" :is-child="true" :tags="['User']"></upload-create-update>
                                        </div>
                                    </div>
                                    <hr class="divider inv" />
                                </div>
                            </div><!-- end row -->

                                <div class="row form-group" :class="{ 'has-error': checkForError('name') }">
                                    <div class="col-sm-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" v-model="name"
                                               placeholder="User Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                                               maxlength="100" minlength="1" required>
                                    </div>
                                </div>
                                <div class="row form-group" :class="{ 'has-error': checkForError('email') || errors.email }">
                                    <div class="col-sm-12">
                                        <label for="name">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" v-model="email"
                                               v-validate:email="{ required: true, minlength:1, maxlength:100 }">
                                        <div v-show="errors.email" class="help-block">{{errors.email}}</div>
                                    </div>
                                </div>
                                <div class="row form-group" :class="{ 'has-error': errors.alt_email }">
                                    <div class="col-sm-12">
                                        <label for="name">Alt. Email</label>
                                        <input type="email" class="form-control" name="alt_email" id="alt_email" v-model="alt_email">
                                        <div v-show="errors.alt_email" class="help-block">{{errors.alt_email}}</div>
                                    </div>
                                </div>

                                <div class="row form-group" :class="{ 'has-error': !!changePassword && (checkForError('password')||checkForError('passwordconfirmation')) }">
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
                                                    <!-- <option value="01">1</option>
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
                                                    <option value="31">31</option> -->
                                                </select>
                                                <h6 class="help-block lightcolor">Day</h6>
                                            </div>
                                            <div class="col-xs-4">
                                                <select class="form-control" name="dob_year" v-model="dobYear">
                                                <option v-for="year in years" :value="year">{{ year }}</option>

                                                    <!-- <option value="1930">1930</option>
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
                                                    <option value="2015">2015</option> -->
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
                                        <span class="input-group-addon">www.missions.me/</span>
                                        <input type="text" id="url" v-model="url" class="form-control" required v-validate:url="{ required: !!public }"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
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
                            <div class="row form-group" :class="{ 'has-error': checkForError('timezone') }">
                                <div class="col-sm-12">
                                    <label for="timezone" class="control-label">Timezone</label>
                                    <v-select class="form-control" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                                    <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate:timezone="{ required: true }">
                                        <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row form-group">
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
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Social Media</h5>
                        </div>
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="facebook">Facebook</label>
                                        <input type="text" class="form-control" v-model="facebook" id="facebook" placeholder="Facebook Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="twitter">Twitter</label>
                                        <input type="text" class="form-control" v-model="twitter" id="twitter" placeholder="Twitter Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="instagram">Instagram</label>
                                        <input type="text" class="form-control" v-model="instagram" id="instagram" placeholder="Instagram Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="linkedIn">LinkedIn</label>
                                        <input type="text" class="form-control" v-model="linkedIn" id="linkedIn" placeholder="LinkedIn Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="pintrest">Pinterest</label>
                                        <input type="text" class="form-control" v-model="pinterest" id="pinterest" placeholder="Printerest Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="google-plus">Google +</label>
                                        <input type="text" class="form-control" v-model="google" id="google" placeholder="Googe + Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="vimeo">Vimeo</label>
                                        <input type="text" class="form-control" v-model="vimeo" id="vimeo" placeholder="Vimeo Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="youtube">YouTube</label>
                                        <input type="text" class="form-control" v-model="youtube" id="youtube" placeholder="YouTube Profile">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label" for="website">Website</label>
                                        <input type="text" class="form-control" v-model="website" id="website" placeholder="Website">
                                    </div>
                                </div>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                    </div><!-- end col -->
                    <div class="col-sm-12 text-center">
                        <hr class="divider inv lg">
                        <a @click="submit()" class="btn btn-primary">Update</a>
                        <a @click="back()" class="btn btn-success">Done</a>
                        <hr class="divider inv xlg">
                    </div><!-- end col -->
                </div><!-- end row -->

                <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                    <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
                </modal>
            </form>
        </validator>
    </div>
</template>
<style>
    .alert.top, .alert.top-right {
        top: 80px;
    }
</style>
<script type="text/javascript">
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    export default{
        name: 'user-settings',
        components: {vSelect, 'upload-create-update': uploadCreateUpdate},
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
                address_one: '',
                address_two: '',
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
                resource: this.$resource('users/' + this.$root.user.id + '?include=links'),
                errors: {},
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
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            },
            birthday() {
                return this.dobYear && this.dobMonth && this.dobDay
                        ? moment(this.dobYear+'-'+this.dobMonth+'-'+this.dobDay).format('YYYY-MM-DD 00:00:00')
                        : null;
            },
            years() {
                var currentYear = new moment().subtract(8, 'years').year(), years = [];
                startYear = new moment().subtract(100, 'years').year();

                while ( startYear <= currentYear ) {
                        years.push(startYear++);
                } 

                return years;
            },
            days() {
                var day = 1, days = [];
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
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UserSettings[field].invalid && this.attemptSubmit;
            },
            onTouched(){
                this.hasChanged = true;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$UserSettings.valid) {
                    // this.$refs.spinner.show();
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
                        address_one: this.address_one,
                        address_two: this.address_two,
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
                }).then(function (response) {
                        this.setUserData(response.data.data);
                        this.$dispatch('showSuccess', 'Settings updated successfully.');
                        this.hasChanged = false;
                    }, function (error) {
                        console.log(error);
                        this.errors = error.data.errors;
                        this.$dispatch('showError', 'Please check the form.');
                    });
                } else {
                    this.showError = true;
                }
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
                this.countryCodeObj = _.findWhere(this.countries, {code: user.country_code});
                console.log(this.countryCodeObj);
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
                this.avatar_upload_id = user.avatar_upload_id;
                this.banner_upload_id = user.banner_upload_id;
                this.facebook = _.findWhere(user.links.data, {name: "facebook"}) ? _.findWhere(user.links.data, {name: "facebook"}).url : null;
                this.twitter = _.findWhere(user.links.data, {name: "twitter"}) ? _.findWhere(user.links.data, {name: "twitter"}).url : null;
                this.instagram = _.findWhere(user.links.data, {name: "instagram"}) ? _.findWhere(user.links.data, {name: "instagram"}).url : null;
                this.linkedIn = _.findWhere(user.links.data, {name: "linkedin"}) ? _.findWhere(user.links.data, {name: "linkedin"}).url : null;
                this.website = _.findWhere(user.links.data, {name: "website"}) ? _.findWhere(user.links.data, {name: "website"}).url : null;
                this.dobDay = moment(user.birthday).format('DD');
                this.dobMonth = moment(user.birthday).format('MM');
                this.dobYear = moment(user.birthday).format('YYYY');
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
                this.setUserData(response.data.data)
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