<template>
	<div>

		<form novalidate id="TripInterestSignupForm">
			<spinner ref="validationSpinner" size="xl" :fixed="false" text="Sending..."></spinner>
      <template v-if="!preselected">
      <div class="row">
        <div class="col-xs-12" v-error-handler="{ value: campaign_id, handle: 'campaign_id' }">
          <label>Campaign of Interest</label>
          <select v-model="campaign_id" class="form-control" name="campaign_id" v-validate="'required'">
            <option v-for="campaign in campaigns" :value="campaign.id" :key="campaign.id">
              {{ campaign.name }}
            </option>
          </select>
        </div>
      </div>
      <hr class="divider inv sm">
      <div class="row" v-if="campaign_id">
        <div class="col-xs-12" v-error-handler="{ value: interest.trip_id, handle: 'trip_id' }">
          <label>Trip Type</label>
          <select name="trip_id" v-model="interest.trip_id" class="form-control" v-validate="'required'">
            <option v-for="trip in trips" :value="trip.id" :key="trip.id">
              {{ trip.type | capitalize }} Trip <template v-for="tag in trip.tags">[{{ tag.name }}] </template>
            </option>
          </select>
        </div>
      </div>
      <hr class="divider inv sm">
      </template>
			<div class="row">
				<div class="col-xs-12" v-error-handler="{ value: interest.name, handle: 'name' }">
					<label>Name</label>
					<input type="text" class="form-control" v-model="interest.name" name="name" v-validate="'required'">
				</div>
			</div>
			<hr class="divider inv sm">
			<div class="row">
				<div class="col-xs-12" v-error-handler="{ value: interest.email, handle: 'email' }">
					<label>Email</label>
					<input type="text" class="form-control" v-model="interest.email" name="email"
					       v-validate="'required|email'">
				</div>
			</div>
			<hr class="divider inv sm">
			<div class="row">
				<div class="col-xs-12" v-error-handler="{ value: interest.phone, handle: 'phone' }">
					<phone-input name="phone" v-model="interest.phone" label="Phone"
					             v-validate="'required'"></phone-input>
				</div>
			</div>
			<hr class="divider inv sm">
			<div class="row">
				<div class="col-xs-12">
					<label>Communication Preference</label>
					<hr class="divider inv sm">
					<div>
						<label class="checkbox-inline">
							<input type="checkbox" value="email" v-model="interest.communication_preferences"> Email
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="phone" v-model="interest.communication_preferences"> Phone
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="text" v-model="interest.communication_preferences"> Text
						</label>
					</div>
				</div>
			</div>
			<hr class="divider inv md">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<span class="help-block">By submitting this form you agree to our
							<a href="/tos" target="_blank">Terms of Service</a> and
							<a href="/privacy">Privacy Policy</a>. You are also stating your interest in this trip and that you would like a Missions.Me representative to contact you.</span>
						<div class="text-center">
							<a class="btn btn-primary" @click="save()">Submit</a>
						</div>
					</div>
				</div>
			</div>

		</form>

	</div>
</template>
<script type="text/javascript">
  import errorHandler from '../error-handler.mixin';

  export default {
    name: 'group-interest-signup',
    mixins: [errorHandler],
    props: ['id', 'preselected'],
    data() {
      return {
        group: {},
        interest: {
          trip_id: null,
          name: null,
          email: null,
          phone: null,
          communication_preferences: [],
          status: 'undecided'
        },
        campaign_id: null,
        campaigns: {},
        allTrips: {},
//                attemptSubmit: false
        // mixin settings
        validatorHandle: 'Interest',
      }
    },
    computed: {
      trips() {
        return _.where(this.allTrips, {campaign_id: this.campaign_id});
      }
    },
    methods: {
      removeDuplicates(arr, prop) {
        let new_arr = [];
        let lookup = {};

        for (let i in arr) {
          lookup[arr[i][prop]] = arr[i];
        }

        for (let i in lookup) {
          new_arr.push(lookup[i]);
        }

        return new_arr;
      },
      save() {

        this.$validator.validateAll().then(result => {
          if (!result) {
            swal("Oops!", "That doesn't look right. Please check what you entered.", "error", {
              button: true
            });
            return false;
          }

          this.$refs.validationSpinner.show();
          this.$http.post('interests', this.interest).then((response) => {
            this.$refs.validationSpinner.hide();
            swal("Thank you!", "Your interest has been submitted. You'll be hearing from us soon.", "success", {
              button: true,
              timer: 3000
            });

            this.interest = {
              trip_id: null,
              name: null,
              email: null,
              phone: null,
              communication_preferences: [],
              status: 'undecided'
            };
            this.$validator.reset();
            this.campaign_id = '';
            console.log(response);
          }).catch((error) => {
            this.errors = error.data.errors;
            this.$refs.validationSpinner.hide();
            console.log(error);
            swal("Oops!", "Something didn't go right. Can you try again?", "error", {
              button: true,
              timer: 3000
            });
          });
        });
      }
    },
    mounted() {
      if (this.preselected) {
        this.interest.trip_id = this.preselected;
      }

      this.$http.get('trips?filter[group_id]=' + this.id + '&filter[current]=true&include=group,campaign,tags&per_page=100')
      .then((response) => {
        // this.group = response.data.data.group.data;
        this.allTrips = response.data.data;
        let campaigns = _.mapObject(response.data.data, 'campaign');
        this.campaigns = this.removeDuplicates(campaigns, 'id');
        console.log(this.campaigns);
      })
    },
  }
</script>
