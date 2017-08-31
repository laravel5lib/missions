<template>
	<div class="panel panel-default">
		<div class="panel-body">
			<h6 class="text-uppercase text-center">Welcome To Missions.Me</h6>
			<hr class="divider inv">
			<template v-if="currentState==='login'">
				<form class="form-horizontal" method="post" action="/login" name="LoginForm" novalidate>
					<input type="hidden" name="_token" :value="user._token">
						<div id="alerts" v-if="messages.length > 0">
							<div v-for="message in messages" :class="'alert alert-' + message.type + ' alert-dismissible'"
							     role="alert">
								{{ message.message }}
							</div>
						</div><!-- end alert -->
						<div class="form-group" style="margin-bottom:0;"
						     :class="{ 'has-error': errors.has('email') }">
							<div class="col-xs-10 col-xs-offset-1">
								<label class="control-label">E-Mail Address</label>
								<input type="email" class="form-control" v-model="user.email" name="email"
								       v-validate="'required|email'"/>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" style="margin-bottom:0;" :class="{ 'has-error': errors.has('password') }">
							<div class="col-xs-10 col-xs-offset-1">
								<label class="control-label">Password</label>
								<input type="password" class="form-control" v-model="user.password" name="password"
								       v-validate="'required'"/>
								<span class="help-block"><a href="/password/email">Forgot password?</a></span>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group">
							<div class="col-xs-10 col-xs-offset-1">
								<button type="submit" class="btn btn-primary btn-block">Login</button>
							</div><!-- end col -->
						</div><!-- end form-group -->
					</form><!-- end form -->
			</template>
			<template v-else-if="currentState==='reset'">
				<form class="form-horizontal" role="form" @submit.prevent="requestReset">
					<div id="alerts" v-if="messages.length > 0">
						<div v-for="message in messages" :class="'alert alert-' + message.type + ' alert-dismissible'"
						     role="alert">
							{{ message.message }}
						</div>
					</div><!-- end alert -->
					<div class="form-group">
						<div class="col-xs-10  col-xs-offset-1">
							<label class="control-label">E-Mail Address</label>
							<input type="email" class="form-control" v-model="user.email"/>
						</div><!-- end col -->
					</div><!-- end form-group -->
					<div class="form-group">
						<div class="col-xs-10  col-xs-offset-1">
							<label class="control-label">Password</label>
							<input type="password" class="form-control" v-model="user.password"/>
						</div><!-- end col -->
					</div><!-- end form-group -->
					<div class="form-group">
						<div class="col-xs-10  col-xs-offset-1">
							<button type="submit" class="btn btn-primary btn-block">
								Request Password Reset
							</button>
						</div><!-- end col -->
					</div><!-- end form-group -->
				</form><!-- end form -->
			</template>
			<template v-else-if="currentState==='create'">
				<form name="RegisterForm" class="form-horizontal" role="form" @submit.prevent="registerUser" novalidate>
						<div id="alerts" v-if="messages.length > 0">
							<div v-for="message in messages" :class="'alert alert-' + message.type + ' alert-dismissible'"
							     role="alert">
								{{ message.message }}
							</div>
						</div><!-- end alert -->
						<div class="form-group" v-error-handler="{ value: newUser.name, handle: 'name'}"
						     :class="{ 'has-error': registerErrors.name || errors.has('name') }">
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" v-model="newUser.name" placeholder="John Doe"
								       required name="name" v-validate="'required|alpha_spaces'"
								       maxlength="100"/>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" :class="{ 'has-error': registerErrors.email || errors.has('email') }">
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-label">E-Mail Address</label>
								<input type="email" class="form-control" v-model="newUser.email"
								       placeholder="example@gmail.com"
								       required name="email" v-validate="'required|email'"/>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" :class="{ 'has-error': registerErrors.password || errors.has('password') }">
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-label">Password</label>
								<input type="password" class="form-control" v-model="newUser.password" required
								       minlength="8" name="password" v-validate="'required'"/>
								<div class="help-block">Password must be at least 8 characters long</div>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" :class="{ 'has-error': registerErrors.password || errors.has('password_confirmation') }">
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-label">Password Again</label>
								<input type="password" class="form-control" v-model="newUser.password_confirmation"
								       required v-validate="'required'" name="password_confirmation"
								       minlength="8"/>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" :class="{ 'has-error': registerErrors.birthday || (errors.has('dob_month') || errors.has('dob_day') || errors.has('dob_year')) }">
							<!-- registerErrors.birthday || (newUser.dobMonth === null || newUser.dobDay === null) -->
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-label">Date of Birth</label>
								<div class="row">
									<div class="col-xs-5">
										<select class="form-control" name="dob_month" v-model="newUser.dobMonth" v-validate="'required'"
										        required>
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
										<select class="form-control" name="dob_day" v-model="newUser.dobDay" v-validate="'required'" required>
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
										<select class="form-control" name="dob_year" v-model="newUser.dobYear" v-validate="'required'" >
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

						<div class="form-group" :class="{ 'has-error': registerErrors.gender || errors.has('gender') }">
							<div class="col-xs-10  col-xs-offset-1">
								<label class="control-labal">Gender</label><br>
								<label class="radio-inline lightcolor">
									<input name="gender" type="radio" value="Male" id="gender" v-model="newUser.gender"
									       required v-validate="'required'">
									Male</label>
								<label class="radio-inline lightcolor">
									<input name="gender" type="radio" value="Female" id="gender"
									       v-model="newUser.gender" v-validate="''"> Female</label>
							</div><!-- end col -->
						</div><!-- end form-group -->
						<div class="form-group" :class="{ 'has-error': registerErrors.country_code }">
							<div class="col-xs-10 col-xs-offset-1">
								<label for="country" class="control-label">Country</label>
								<v-select @keydown.enter.prevent="" class="form-control" id="country"
								          v-model="countryCodeObj" name="country"
								          :options="UTILITIES.countries" label="name"></v-select>
							</div>
						</div>
						<div class="form-group" :class="{ 'has-error': registerErrors.timezone }">
							<div class="col-xs-10 col-xs-offset-1">
								<label for="timezone" class="control-label">Timezone</label>
								<v-select @keydown.enter.prevent="" class="form-control" id="timezone"
								          v-model="newUser.timezone" name="timezone"
								          :options="UTILITIES.timezones"></v-select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-xs-10  col-xs-offset-1">
								<button type="submit" class="btn btn-primary btn-block">
									Create Account
								</button>
							</div><!-- end col -->
						</div><!-- end form-group -->
					</form><!-- end form -->
			</template>
			<!--<a v-if="currentState === 'login' || currentState === 'create'" @click="currentState='reset'">Forgot Your Password?</a>-->
			<p class="text-center">
				<a v-if="currentState === 'reset' || currentState === 'create'"
				   @click="currentState='login'">I Have An Account</a>
			</p>
			<p class="text-center">
				<a v-if="currentState === 'login' || currentState === 'reset'" @click="currentState='create'">
					Create A New Account
				</a>
			</p>
		</div>
	</div><!-- end panel-body -->
</template>

<script type="text/javascript">
    import $ from 'jquery';
    import _ from 'underscore';
    import moment from 'moment';
    import timezone from 'moment-timezone';
    import vSelect from "vue-select";
    import utilities from './utilities.mixin';
    import errorHandler from'./error-handler.mixin';
    import qs from 'qs';
    export default {
        name: 'login',
	    mixins: [utilities, errorHandler],
        components: {vSelect},
        data() {
            return {
                attemptedLogin: false,
                currentState: 'login',
                user: {
                    email: null,
                    password: null,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                newUser: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    dobMonth: null,
                    dobDay: null,
                    dobYear: null,
                    birthday: null,
                    gender: null,
                    country_code: null,
                    timezone: timezone.tz.guess(),
                    status: null,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                reset: {
                    email: null
                },
                messages: [],

                // component vars
                isChildComponent: false,
                userData: null,
                timezones: [],
                countries: [],
                countryCodeObj: null,
                // attemptRegister: false,
                registerErrors: []
            }
        },

        watch: {
            countryCodeObj(val) {
                this.newUser.country_code = _.isObject(val) ? val.code : null;
            },
            currentState(val) {
                this.messages = [];
            }
        },
        computed: {},
        methods: {
            attempt(e) {
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.messages = [{type: 'warning', message: 'Please check the form'}];
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }
                    this.$http.post('/oauth/token', qs.stringify({
                        grant_type: 'password',
                        client_id: 1,
                        client_secret: 'x8NFHoYH0z5FP5P6TW9mBrOf8FbX2iekZIa0VAbs',
                        username: this.user.email,
                        password: this.user.password,
                        scope: '*',
                    }), { baseURL: '' }).then((response) => {
                        // set cookie - name, token
                        this.$cookie.set('api_token', response.data.access_token);

                        if (this.isChildComponent) {
                            this.getUserData();
                        }
                        if (response.data.redirect_to)
                            this.getUserData('/dashboard', response.data.ignore_redirect || false);
                    })
                    .catch((response) => {
	                        debugger;
                        this.messages = [];
                        if (response.status) {
                            switch (response.status) {
	                            case 401:
                                    this.messages.push({
                                        type: 'danger',
                                        message: 'An account with this email and password could not be found.'
                                    });
                                    this.$root.$emit('showError', 'Please check the form.');
                                    break;
	                            case 422:
                                    this.messages = [{
                                        type: 'danger',
                                        message: 'Please enter a valid email and password.'
                                    }];
                                    this.$root.$emit('showError', 'Please check the form.');
                                    break;
	                            default:
                                    console.log(response);
                                    this.$root.$emit('showError', 'Oops! Something went wrong!');
	                                break;
                            }
                        } else {
                            console.log(response);
                            this.$root.$emit('showError', 'Oops! Something went wrong!');
                        }
                    })
                });
            },

            getUserData(redirectTo, ignoreRedirect) {
                return this.$http.get('users/me?include=roles,permissions')
                    .then((response) => {
                            this.$root.$emit('userHasLoggedIn', response.data.data);

                            if (this.isChildComponent || ignoreRedirect) {
                                this.userData = response.data.data;
                                return response.data.data;
                            } else {
                                location.href = redirectTo;
                            }

                        })
	                .catch((response) => {
	                    console.log(response);
	                    return response.message;
	                });
            },

            registerUser(e) {
                e.preventDefault();
                $.extend(this.newUser, {
                    birthday: moment(this.newUser.dobYear + '-' + this.newUser.dobMonth + '-' + this.newUser.dobDay).format('YYYY-MM-DD')
                });

                // this.attemptRegister = true;
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

                    this.$http.post('/register', this.newUser, { baseURL: '' }).then((response) => {
                            // console.log(response.data.token);
                            // set cookie - name, token
                            this.$cookie.set('api_token', response.data.token);
                            // reload to set cookie
								/*if (this.isChildComponent) {
								 window.location.reload();
								 }*/
                            this.getUserData(response.data.redirect_to);
                        }, (response) => {
                            // console.log(response);
                            let messages = [];
                            this.registerErrors = response.data;
                            _.each(this.registerErrors, (error) =>  {
                                    messages.push({
                                        type: 'danger',
                                        message: _.values(error)[0]
                                    })
                                });
                            if (!_.isString(this.gender)) {
                                messages.push({
                                    type: 'danger',
                                    message: 'The gender field is required.'
                                })
                            }
                            this.messages = messages;
                            this.$root.$emit('showError', 'Please check the form.');
                            // this.attemptRegister = false;
                        });
                });
            },

            requestReset(e) {

            }
        },
        activated() {
            // Enable child component behavior
            if (this.$parent != this.$root) {
                this.isChildComponent = true;
                this.getUserData(false);
            }
        },
        mounted() {
            if (_.contains(location.search.substr(1).split('='), 'signup')) {
                this.currentState = 'create';
            }

            this.getCountries();
            this.getTimezones();

            if (this.isChildComponent) {
                // After reload from login / registration
                // Check if user is logged in
                if (this.authenticated && !this.$root.user)
                    this.getUserData(false);
            }

        }
    }
</script>
