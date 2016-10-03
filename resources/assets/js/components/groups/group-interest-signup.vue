<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <validator name="Interest">
        <form novalidate id="TripInterestSignupForm">
            <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Awesome!</strong>
                <p>Trip interest sent.</p>
            </alert>
            <spinner v-ref:validationSpinner size="xl" :fixed="false" text="Saving"></spinner>
            <div class="row">
                <div class="col-xs-12" :class="{ 'has-error': checkForError('campaign')}">
                    <label>Campaign of Interest</label>
                    <select v-model="campaign_id" class="form-control" v-validate:campaign="{required: true}">
                        <option v-for="campaign in campaigns" :value="campaign.data.id">
                            {{   campaign.data.name }}
                        </option>
                    </select>
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row" v-if="campaign_id">
                <div class="col-xs-12" :class="{ 'has-error': checkForError('trip')}">
                    <label>Trip Type</label>
                    <select v-model="interest.trip_id" class="form-control" v-validate:trip="{required: true}">
                        <option v-for="trip in trips" :value="trip.id">
                            {{ trip.type | capitalize }} Trip
                        </option>
                    </select>
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row">
                <div class="col-xs-12" :class="{ 'has-error': checkForError('name')}">
                    <label>Name</label>
                    <input type="text" class="form-control" v-model="interest.name" v-validate:name="{required: true}">
                </div>
            </div>
            <hr class="divider inv sm">
            <div class="row">
                <div class="col-xs-12" :class="{ 'has-error': checkForError('email')}">
                    <label>Email</label>
                    <input type="text" class="form-control" v-model="interest.email" v-validate:email="{required: true, email: true}">
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
        </validator>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'group-interest-signup',
        props: ['id'],
        components:{'spinner': VueStrap.spinner, 'alert': VueStrap.alert },
        data(){
            return{
                group: {},
                interest: {
                    communication_preferences: []
                },
                campaign_id: null,
                campaigns: {},
                allTrips: {},
                attemptSubmit: false,
                showSuccess: false
            }
        },
        computed: {
            trips() {
                return _.where(this.allTrips, {campaign_id: this.campaign_id});
            }
        },
        ready() {
            this.$http.get('groups/' + this.id, {include: 'trips.campaign'}).then(function (response) {
                this.group = response.data.data;
                this.allTrips = response.data.data.trips.data;
                var campaigns = _.mapObject(response.data.data.trips.data, 'campaign');
                this.campaigns = this.removeDuplicates(campaigns, 'id');
                console.log(this.campaigns);
            })
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$Interest[field].invalid && this.attemptSubmit;
            },
            removeDuplicates(arr, prop) {
                var new_arr = [];
                var lookup  = {};

                for (var i in arr) {
                    lookup[arr[i]['data'][prop]] = arr[i];
                }

                for (i in lookup) {
                    new_arr.push(lookup[i]);
                }

                return new_arr;
            },
            save() {
                this.attemptSubmit = true;
                if (this.$Interest.valid) {
                    this.$refs.validationspinner.show();
                    this.$http.post('interests', this.interest).then(function (response) {
                        this.$refs.validationspinner.hide();
                        this.showSuccess = true;
                        this.interest = {
                            communication_preferences: []
                        },
                        console.log(response);
                    }).then(function (error) {
                        this.$refs.validationspinner.hide();
                        console.log(error);
                    });
                }
            }
        }
    }
</script>
