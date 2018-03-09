<template>
	<div>
		<div class="content-page-header">
			<img class="img-responsive" src="/images/groups/groups-header.jpg" alt="">
			<div class="c-page-header-text">
				<h1 class="text-uppercase dash-trailing">Take Your Church or Organization</h1>
			</div><!-- end c-page-header-content -->
		</div><!-- end c-page-header -->
		<div class="white-bg">
			<div class="container">
				<div class="content-section">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
							<h2 class="text-primary">Team trips are what we do!</h2>
							<p>Missions.Me specializes in taking teams form around the world on life-changing missions experiences.  If you are interested in partnering with one of our missions trips, please fill out the form.  Missions.Me can provide your group with its own profile, URL and customized  trips created especially for your church or organization.</p>
						</div><!-- end col -->
					</div><!-- end row -->
					<hr class="divider inv xlg">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
							<spinner ref="spinner" global size="sm" text="Loading"></spinner>

							<form id="CreateGroupForm" class="form-horizontal" novalidate>
								<div>
									<!--<legend>Interests</legend>-->
									<div class="form-group">
										<div class="col-sm-12" v-error-handler="{ value: campaign, handle: 'campaign' }">
											<label for="campaign">Which Trip are you interested in?</label>
											<select name="campaign" id="campaign" class="form-control" v-model="campaign" v-validate="'required'">
												<option value="">-- please select --</option>
												<option :value="campaign.id" v-for="campaign in campaigns">{{campaign.name}}</option>
											</select>
										</div>
									</div>
								</div>
								<div>
									<legend>Organization Information</legend>
									<div class="form-group">
										<div class="col-sm-12" v-error-handler="{ value: name, handle: 'name' }">
											<label for="name">Name</label>
											<input type="text" class="form-control" name="name" id="name" v-model="name"
											       placeholder="Church/Organization Name" v-validate="'required|min:1|max:100'"
											       maxlength="100" minlength="1">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6">
											<label for="infoAddress">Address 1</label>
											<input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
										</div>
										<div class="col-sm-6">
											<label for="infoAddress2">Address 2</label>
											<input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
										</div>
									</div>
									<div class="row form-group col-sm-offset-2">
										<div class="col-sm-4" v-error-handler="{ value: city, handle: 'city' }">
											<label for="infoCity">City</label>
											<input type="text" class="form-control" v-model="city" name="city" id="infoCity" placeholder="City">
										</div>
										<div class="col-sm-4" v-error-handler="{ value: state, handle: 'state' }">
											<label for="infoState">State/Prov.</label>
											<input type="text" class="form-control" v-model="state" name="state" id="infoState" placeholder="State/Province">
										</div>
										<div class="col-sm-4" v-error-handler="{ value: zip, handle: 'zip' }">
											<label for="infoZip">ZIP/Postal Code</label>
											<input type="text" class="form-control" v-model="zip" name="zip" id="infoZip" placeholder="12345">
										</div>
									</div>
									<div class="row form-group col-sm-offset-2">
										<div class="col-sm-6">
											<div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
												<label for="country">Country</label>
												<v-select @keydown.enter.prevent="" class="form-control" name="country" id="country" v-validate="'required'" v-model="countryCodeObj" :options="UTILITIES.countries" label="name"></v-select>
											</div>
										</div>
										<div class="col-sm-6" v-error-handler="{ value: type, handle: 'type' }">
											<label for="type">Type</label>
											<select name="type" id="type" class="form-control" v-model="type" v-validate="'required'">
												<option value="">-- please select --</option>
												<option :value="option" v-for="option in typeOptions">{{ option|capitalize }}</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6" v-error-handler="{ value: phone_one, client: 'phone', server: 'phone_one' }">
											<label for="infoPhone">Phone 1</label>
											<phone-input v-model="phone_one" name="phone" id="infoPhone"></phone-input>
											<p class="help-block" v-if="errors.has('phone')" v-text="errors.phone"></p>
										</div>
										<div class="col-sm-6">
											<label for="infoMobile">Phone 2</label>
											<phone-input v-model="phone_two" name="phone" id="infoMobile"></phone-input>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12" v-error-handler="{ value: timezone, handle: 'timezone' }">
											<label for="timezone">Timezone</label>
											<v-select @keydown.enter.prevent=""  class="form-control" name="timezone" id="timezone" v-model="timezone" :options="UTILITIES.timezones" v-validate="'required'"></v-select>
										</div>
									</div>
								</div>

								<div>
									<legend>Your Information</legend>
									<div class="form-group">
										<div class="col-sm-6" v-error-handler="{ value: contact, handle: 'contact' }">
											<label for="contact">Your Name</label>
											<input type="text" class="form-control" name="contact" id="contact" v-model="contact"
											       placeholder="John Smith" v-validate="'required|min:1|max:100'"
											       maxlength="100" minlength="1">
										</div>
										<div class="col-sm-6" v-error-handler="{ value: email, handle: 'email' }">
											<label for="email">Email</label>
											<input type="text" class="form-control" name="email" id="email" v-model="email" v-validate="'required|email'">
										</div>
										<div class="col-sm-12" v-error-handler="{ value: position, handle: 'position' }">
											<label for="position">Your Position</label>
											<input type="text" class="form-control" name="position" id="position" v-model="position" v-validate="'required|min:1'">
										</div>
									</div>
									<div class="form-group" v-error-handler="{ value: spoke_to_rep, client: 'spoken', server: 'spoke_to_rep' }">
										<label for="status" class="col-sm-8 control-label">Have you spoken with a Missions.Me representative?</label>
										<div class="col-sm-4">
											<label class="radio-inline">
												<input type="radio" name="spoken" id="status" value="yes" v-model="spoke_to_rep" v-validate="'required'"> Yes
											</label>
											<label class="radio-inline">
												<input type="radio" name="spoken" id="status2" value="no" v-model="spoke_to_rep" v-validate="''"> No
											</label>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12 text-center">
										<a @click="submit" class="btn btn-primary">Send Request</a>
									</div>
								</div>
							</form>
						</div><!-- end col -->
					</div><!-- end row -->
					<alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
						<span class="icon-ok-circled alert-icon-float-left"></span>
						<strong>Done</strong>
						<p>Request sent</p>
					</alert>

				</div><!-- end content-section -->
			</div><!-- end container -->
		</div><!-- end white-bg -->
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore';
  import timezone from 'moment-timezone';
  import vSelect from 'vue-select';
  import errorHandler from'../error-handler.mixin';
  import utilities from'../utilities.mixin';
  export default {
    name: 'group-request-form',
    components: { vSelect },
    mixins: [errorHandler, utilities],
    data() {
      return {
        campaigns:[],
        typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
        countryCodeObj: null,
        showSuccess: false,

        // form vars
        name: '',
        type: '',
        description: '',
        timezone: timezone.tz.guess(),
        phone_one: '',
        phone_two: '',
        address_one: '',
        address_two: '',
        city: '',
        state: '',
        zip: '',
        campaign: '',
        contact: '',
        position: '',
        email: '',
        spoke_to_rep: undefined,
      }
    },
    computed: {
      country_code: {
        get() {
          return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
        },
        set() {}
      },
    },
    methods: {
      resetForm() {
        this.name ='';
        this.type ='';
        this.countryCodeObj = null;
        this.country_code = null;
        this.description ='';
        this.timezone = timezone.tz.guess();
        this.phone_one ='';
        this.phone_two ='';
        this.address_one ='';
        this.address_two ='';
        this.city ='';
        this.state ='';
        this.zip ='';
        this.campaign ='';
        this.contact ='';
        this.position ='';
        this.email ='';
        this.spoke_to_rep = undefined;
        this.attemptSubmit = false;
        this.$validator.reset();
        $('#collapseGroupForm').collapse('hide');

      },
      submit(){
        this.$validator.validateAll().then(result => {
          if (!result) {
            this.$root.$emit('showError', 'Please check the form.');
            return false;
          }

          this.$http.post('groups/submit', {
            name: this.name,
            type: this.type,
            country_code: this.country_code,
            description: this.description,
            timezone: this.timezone,
            phone_one: this.phone_one,
            phone_two: this.phone_two,
            address_one: this.address_one,
            address_two: this.address_two,
            city: this.city,
            state: this.state,
            zip: this.zip,
            campaign: this.campaign,
            contact: this.contact,
            position: this.position,
            email: this.email,
            spoke_to_rep: this.spoke_to_rep,
          }).then((response) => {
            console.log(response);
            this.showSuccess = true;
            //TODO use universal alert
            this.resetForm();
            // this.$refs.spinner.hide();
          }, (error) =>  {
            this.errors = error.data.errors;
            // this.$refs.spinner.hide();
          });
        });
      }

    },
    mounted() {
      this.getCountries();
      this.getTimezones();

      this.$http.get('campaigns').then((response) => {
        this.campaigns = response.data.data;
      });

    }
  }
</script>