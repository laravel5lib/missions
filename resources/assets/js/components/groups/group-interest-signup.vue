<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>

        <form novalidate id="TripInterestSignupForm">
            <spinner ref="validationSpinner" size="xl" :fixed="false" text="Saving"></spinner>
            <div class="row">
                <div class="col-xs-12" v-error-handler="{ value: campaign, handle: 'campaign' }">
                    <label>Campaign of Interest</label>
                    <select v-model="campaign_id" class="form-control" name="campaign" v-validate="'required'">
                        <option v-for="campaign in campaigns" :value="campaign.data.id">
                            {{   campaign.data.name }}
                        </option>
                    </select>
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row" v-if="campaign_id">
                <div class="col-xs-12" v-error-handler="{ value: trip, handle: 'trip' }">
                    <label>Trip Type</label>
                    <select name="campaign" v-model="interest.trip_id" class="form-control" v-validate="'required'">
                        <option v-for="trip in trips" :value="trip.id">
                            {{ trip.type ? trip.type[0].toUpperCase() + trip.type.slice(1) : '' }} Trip
                        </option>
                    </select>
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row">
                <div class="col-xs-12" v-error-handler="{ value: name, handle: 'name' }">
                    <label>Name</label>
                    <input type="text" class="form-control" v-model="interest.name" name="name" v-validate="'required'">
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row">
                <div class="col-xs-12" v-error-handler="{ value: email, handle: 'email' }">
                    <label>Email</label>
                    <input type="text" class="form-control" v-model="interest.email" name="email" v-validate="{required: true, email: true}">
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row">
                <div class="col-xs-12">
                    <label>Phone</label>
                    <input type="text" class="form-control" v-model="interest.phone | phone">
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
                <div class="col-sm-12 text-center">
                    <div class="form-group">
                        <a class="btn btn-primary" @click="save()">Submit</a>
                    </div>
                </div>
            </div>

        </form>

    </div>
</template>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'group-interest-signup',
        mixins: [errorHandler],
        props: ['id'],
        data(){
            return{
                group: {},
                interest: {
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
            /*checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$Interest[field].invalid && this.attemptSubmit;
            },*/
            removeDuplicates(arr, prop) {
                let new_arr = [];
                let lookup  = {};

                for (let i in arr) {
                    lookup[arr[i]['data'][prop]] = arr[i];
                }

                for (let i in lookup) {
                    new_arr.push(lookup[i]);
                }

                return new_arr;
            },
            save() {
                this.resetErrors();
                if (this.$Interest.valid) {
                    this.$refs.validationspinner.show();
                    this.$http.post('interests', this.interest).then(function (response) {
                        this.$refs.validationspinner.hide();
                        this.showSuccess = true;
                        this.attemptSubmit = false;
                        this.interest = {
                            communication_preferences: [],
                            status: 'undecided'
                        };
                        this.campaign_id = '';
                        console.log(response);
                    }).then(function (error) {
                        this.errors = error.data.errors;
                        this.$refs.validationspinner.hide();
                        console.log(error);
                        this.$root.$emit('showSuccess', 'Request sent')
                    });
                } else {
                    this.$root.$emit('showError', 'Please check the form for errors.')
                }
            }
        },
        mounted() {
            this.$http.get('trips?groups[]=' + this.id, { params: {status: 'current', include: 'group,campaign'} }).then(function (response) {
                // this.group = response.body.data.group.data;
                this.allTrips = response.body.data;
                let campaigns = _.mapObject(response.body.data, 'campaign');
                this.campaigns = this.removeDuplicates(campaigns, 'id');
                console.log(this.campaigns);
            })
        },
    }
</script>
