<template ref="BasicInfo">
	<div class="row">
		<div class="col-sm-12">
			<form novalidate name="BasicInfoForm" id="BasicInfoForm">

			<div class="row" v-if="forAdmin">
				<div class="col-sm-12 col-md-8 col-md-offset-4">
					<div class="form-group" :class="{ 'has-error': errors.has('manager') }">
						<label for="infoManager">Reservation Manager</label>
						<v-select @keydown.enter.prevent="" class="form-control" name="manager" id="infoManager" v-validate="'required'" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name"></v-select>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Full Legal Name</h5>
					<p class="text-muted">Please provide your full legal name as it appears on your passport or travel visa.</p>
				</div>
				<div class="col-sm-8">
					<label>Given Names</label>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group" v-error-handler="{value: firstName, handle: 'firstName'}">
								<input type="text" class="form-control" v-model="firstName"
								       name="firstName" placeholder="First & Middle Names" v-validate="'required'"
								       id="infoFirstName">
								<span class="help-block">First name(s), and middle name if applicable</span>
							</div>
						</div>
					</div>
					<label>Surname</label>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group" v-error-handler="{value: lastName, handle: 'lastName'}">
								<input type="text" class="form-control" v-model="lastName"
								       name="lastName" placeholder="Last Name" v-validate="'required'"
								       id="infoLastName">
								<span class="help-block">Last name(s) or family name</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Personal Details</h5>
					<p class="text-muted">Knowing your age, gender and martial status is required to best place you on a team and arrange your accommodations.</p>
				</div>
				<div class="col-sm-8">
					<label>Date of Birth</label>
					<div class="row">
						<div class="col-sm-4" v-error-handler="{value: dobMonth, handle: 'dobMonth'}">
							<div class="form-group" :class="{ 'has-error': errors.has('dobMonth') }">
								<select class="form-control input" v-model="dobMonth"
								        name="dobMonth" id="infoDobMonth" v-validate="'required'">
									<option value="">Month</option>
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
								<span class="help-block">Month</span>
							</div>
						</div>
						<div class="col-sm-4" v-error-handler="{value: dobDay, handle: 'dobDay'}">
							<div class="form-group" :class="{ 'has-error': errors.has('dobDay') }">
								<select class="form-control" v-model="dobDay"
								        name="dobDay" id="infoDobDay" v-validate="'required'">
									<option value="">Day</option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
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
								<span class="help-block">Day</span>
							</div>
						</div>
						<div class="col-sm-4" v-error-handler="{value: dobYearCalc, handle: 'dobYear'}">
							<div class="form-group" :class="{ 'has-error': errors.has('dobYear') }">
								<select class="form-control" v-model="dobYearCalc"
								        name="dobYear" id="infoDobYear" v-validate="'required'">
									<option value="">Year</option>
									<option v-for="year in selectableYears" :value="year">
										{{ year }}
									</option>
								</select>
								<span class="help-block">Year</span>
							</div>
						</div>
						<div class="col-sm-12 errors-block"></div>
					</div>

					<label>Gender</label>
					<div class="row" v-error-handler="{value: gender, handle: 'gender', messages: { req: 'Select a gender' }}">
						<div :class="{ 'has-error': errors.has('gender') }">
							<label :class="{ 'text-danger': errors.has('gender') }">
								<input type="radio" v-model="gender" name="gender" v-validate="'required|in:male,female'"
								       value="male">&nbsp;Male
							</label>
						</div>
						<div :class="{ 'has-error': errors.has('gender') }">
							<label :class="{ 'text-danger': errors.has('gender') }">
								<input type="radio" v-model="gender" name="gender" value="female">&nbsp;Female
							</label>
						</div>
						<div class="col-sm-12 errors-block"></div>
					</div>
					<hr class="divider inv">

					<div class="form-group" v-error-handler="{value: relationshipStatus, handle: 'relationshipStatus'}">
						<label for="infoRelStatus">Relationship Status</label>
						<select class="form-control" v-model="relationshipStatus"
								name="relationshipStatus" id="infoRelStatus" v-validate="'required'">
							<option value="single">Single</option>
							<option value="engaged">Engaged</option>
							<option value="married">Married</option>
							<option value="divorced">Divorced</option>
							<option value="widowed">Widowed</option>
						</select>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Contact Information</h5>
					<p class="text-muted">From time to time a Missions.Me trip represenative will need to contact you about important travel documents and other trip details.</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" v-error-handler="{ value: email, handle: 'email'}">
						<label for="infoEmailAddress">Email Address</label>
						<input type="text" class="form-control" v-model="email" id="infoEmailAddress"
						       name="email" v-validate="'required|email'">
					</div>
					<div class="form-group" v-error-handler="{ value: phone, handle: 'phone' }">
						<label for="infoPhone">Home Phone</label>
						<phone-input v-validate="'required|min:10'" id="infoPhone" name="phone" v-model="phone"></phone-input>
					</div>
					<div class="form-group" v-error-handler="{ value: mobile, handle: 'mobile' }">
						<label for="infoMobile">Cell Phone</label>
						<phone-input v-validate="'required|min:10'" id="infoMobile" name="mobile" v-model="mobile"></phone-input>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Mailing Address</h5>
					<p class="text-muted">We need your home or mailing address so we can send you important materials related to your trip. This information is also required for trip accommodation and transportation arrangements.</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" v-error-handler="{value: address, handle: 'address'}">
						<label for="infoAddress">Address</label>
						<input type="text" class="form-control" v-model="address"
						       name="address" id="infoAddress" v-validate="'required'"
						       placeholder="Mailing Address">
					</div>
					<div class="form-group" v-error-handler="{value: city, handle: 'city'}">
						<label for="infoCity">City</label>
						<input type="text" class="form-control" v-model="city"
								name="city" id="infoCity" placeholder v-validate="'required'">
					</div>
					<div class="form-group" v-error-handler="{value: state, handle: 'state'}">
						<label for="infoState">State/Prov.</label>
						<input type="text" class="form-control" v-model="state"
								name="state" id="infoState" placeholder v-validate="'required'">
					</div>
					<div class="form-group" v-error-handler="{value: zipCode, handle: 'zip'}">
						<label for="infoZip">Zip/Postal Code</label>
						<input type="text" class="form-control" v-model="zipCode"
								name="zip" id="infoZip" placeholder="12345" v-validate="'required'">
					</div>
					<div class="form-group" v-error-handler="{value: country, handle: 'country'}">
						<label for="infoCountry">Country</label>
						<v-select @keydown.enter.prevent="" name="country" v-validate="'required'" class="form-control" id="infoCountry" v-model="countryCodeObj" :options="UTILITIES.countries" label="name"></v-select>
					</div>
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Shirt Size</h5>
					<p class="text-muted">Let us know your shirt size so we can send you an exclusive team shirt!</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" v-error-handler="{value: size, handle: 'size'}">
						<label for="infoShirtSize">Size</label>
						<select class="form-control" v-model="size" name="size" v-validate="'required'"
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

			<hr class="divider">

			<div class="row">
				<div class="col-sm-4">
					<h5>Team Placement</h5>
					<p class="text-muted">Please select your desired team role. Depending on your selection, the role may require additional travel documents.</p>
						
					<p class="text-muted">Missions.Me reserves the right to make all final team placement and role selections. We cannot garantee your selection. Thanks for understanding!</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group" v-error-handler="{value: desired_role, handle: 'role'}">
						<label for="desiredRole">Desired Team Role</label>
						<select class="form-control" id="desiredRole" v-model="desired_role" name="role" v-validate="'required'">
							<option v-for="role in UTILITIES.roles" :value="{value: role.value, name: role.name}">{{role.name}}</option>
						</select>
					</div><!-- end form-group -->
				</div>
			</div>

			<hr class="divider">

			<div class="row">
				<div class="col-sm-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="confirmInfo">
							I have verified that all the above information is completely accurate and up-to-date.
						</label>
					</div>
				</div>
			</div>

			</form>
		</div>
	</div>
</template>
<script type="text/javascript">
	import vSelect from "vue-select";
	import utilities from '../../utilities.mixin';
	import errorHandler from '../../error-handler.mixin';
	export default{
		name: 'basic-info',
		mixins: [utilities, errorHandler],
		props: {
			forAdmin: {
				type: Boolean,
				default: false
			}
		},
		components: {vSelect},
		data(){
			return {
			    handle: 'BasicInfo',
				title: 'Basic Traveler Information',
				currentYear: new Date().getFullYear(),
				dobYearCalc: '',
				attemptedContinue: false,
				usersArr: [],
				countryCodeObj: null,
				userObj: null,
//				user_id: null,
				onBehalf: false,

				// basic info data
				desired_role: null,
				address: null,
				city: null,
				state: null,
				zipCode: null,
				phone: '',
				mobile: '',
				firstName: null,
				lastName: null,
				email: null,
				dobDay: '',
				dobMonth: '',
//				dobYear: null,
				gender: null,
				relationshipStatus: 'single',
				size: null,
//				height: null,
				heightA: null,
				heightB: null,
				weight: null,
				avatar_upload_id: null,
//				userInfo: {}
			    confirmInfo: false,
			}
		},
		computed: {
		    selectableYears() {
		        let i = 100;
		        let arr = [];
		        for(i;i >= 0;i--) {
					arr.push(this.currentYear - i);
		        }
		        return arr;
		    },
			heightUnitA() {
				if (this.country === 'us')
					return 'ft';

				return 'm';
			},
			heightUnitB() {
				if (this.country === 'us')
					return 'in';

				return 'cm';
			},
			weightUnit() {
				if (this.country === 'us')
					return 'lbs';

				return 'kg';
			},
			country: {
		        get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                }, set() {}
			},
			dobYear: {
                get() {
                    return this.dobYearCalc;
                }, set() {}
            },
			height(){
				return this.heightA + ' ft. ' + this.heightB + ' in.';
			},
			userInfo(){
				return {
					desired_role: this.desired_role,
					address: this.address,
					city: this.city,
					state: this.state,
					zipCode: this.zipCode,
					country: this.country,
					country_name: this.countryCodeObj.name,
					phone: this.phone,
					mobile: this.mobile,
					firstName: this.firstName,
					lastName: this.lastName,
					email: this.email,
					dobDay: this.dobDay,
					dobMonth: this.dobMonth,
					dobYear: this.dobYear,
					dob: moment(this.dobMonth + '-' + this.dobDay + '-' + this.dobYear, 'MM-DD-YYYY').format('LL'),
					gender: this.gender,
					relationshipStatus: this.relationshipStatus,
					size: this.size,
					height: this.height,
					heightUnitA: this.heightUnitA,
					heightUnitB: this.heightUnitB,
					heightA: this.heightA,
					heightB: this.heightB,
					weight: this.weight,
					weightUnit: this.weightUnit,
					avatar_upload_id: this.avatar_upload_id
				}
			}
		},
		watch: {
		  confirmInfo(val) {
		    if (val) {
              this.$validator.validateAll().then(result => {
                if (result) this.$emit('step-completion', val);
              });
            }
		  },
		    // fail safe for poor loading
		    '$parent.trip'(val){
		        this.getRoles(val.team_roles);
		    },
			userObj: {
		      deep: true,
              handler(val) {
                if (this.forAdmin) {
                  if (val && val.id) {
                    this.setLocalUserData();
                    if (this.$parent.hasOwnProperty('userData') && _.isObject(this.userObj)) {
                      this.$parent.userData = this.userObj;
                    }
                  } else {
                    this.toggleUserData()
                  }
                }
              }
            },
            /*isFormDirty(){
			    this.$validator.validateAll().then(result => {
			        if (result)
                        this.$parent.userInfo = this.userInfo;
                });
            }*/
		},
		methods: {
			getUsers(search, loading){
				loading ? loading(true) : void 0;
				this.$http.get('users', { params: { search: search} }).then((response) => {
					this.usersArr = response.data.data;
					loading ? loading(false) : void 0;
				})
			},
			toggleUserData(){
				if (this.onBehalf) {
                    this.firstName = null;
                    this.lastName = null;
                    this.dobDay = '';
                    this.dobMonth = '';
                    this.dobYearCalc = '';
                    this.dobYear = '';
                    this.email = null;
                    this.gender = null;
                    this.address = null;
                    this.relationshipStatus = 'single';
                    this.phone = '';
                    this.mobile = '';
                    this.address = null;
                    this.state = null;
                    this.zipCode = null;
                    this.countryCodeObj = _.findWhere(this.UTILITIES.countries, {code: "us"});
                    this.avatar_upload_id = null;
                } else {
				    this.setLocalUserData();
                }
			},
			setLocalUserData(){
			  let user = this.forAdmin ? this.userObj : this.$parent.userData;
              let birthdays = moment(user.birthday, 'YYYY-MM-DD');
              let names = user.name.split(' ');
                this.firstName = _.first(names);
                this.lastName = names.length > 1 ? _.last(names) : null;

                this.dobDay = birthdays.format('DD');
                this.dobMonth = birthdays.format('MM');
                this.dobYearCalc = birthdays.format('YYYY');

                this.email = user.email;
                this.gender = user.gender ? user.gender.toLowerCase() : null;
                this.address = user.address;
                this.relationshipStatus = user.status ? user.status.toLowerCase() : 'single';
                this.phone = user.phone_one;
                this.mobile = user.phone_two;
                this.address = user.address;
                this.city = user.city;
                this.state = user.state;
                this.zipCode = user.zip;
                this.size = user.shirt_size;
                this.countryCodeObj = _.findWhere(this.UTILITIES.countries, {code: user.country_code});
                this.avatar_upload_id = user.avatar_upload_id;
			}
		},
		activated(){
          let promises = [];
          if (this.forAdmin) {
			this.onBehalf = true;
          }

		  promises.push(this.getCountries());
		  promises.push(this.getRoles(this.$parent.trip.team_roles));
          this.$http.all(promises).then((vals) => {
            if (_.contains(this.$parent.trip.team_roles, 'MISS'))
              this.desired_role = { value: 'MISS', name: 'Missionary' };
            // if (!this.onBehalf)
            //   this.setLocalUserData();
          });

		  $('html, body').animate({scrollTop: 0}, 300);
		}
	}
</script>
