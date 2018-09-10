<template>
<div class="panel panel-default">
    <div class="panel-heading">
       <h5>Transfer Reservation</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning"><i class="fa fa-info-circle"></i> <strong>USE CAUTION!</strong> Transferring a reservation will reset it's travel requirements (custom requirements will not be affected), change pricing (custom prices will not be affected), and remove it from any squads it may already be assigned to. <strong>This action cannot be reversed.</strong></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Current Campaign</label>
                            <p class="form-control-static">{{ current.campaign }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-select name="campaign_id" v-model="campaign_id" :options="campaigns">
                                <label slot="label">New Campaign</label>
                                <span class="help-block" slot="help-text">
                                    Changing the campaign will also remove any custom pricing.
                                </span>
                            </input-select>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Current Group</label>
                            <p class="form-control-static">{{ current.group }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-select name="group_id" v-model="group_id" :options="groups">
                                <label slot="label">New Group</label>
                                <span class="help-block" slot="help-text">
                                    Available groups might be different.
                                </span>
                            </input-select>
                        </div>
                    </div>
                    
                    <hr class="divider">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Current Trip</label>
                            <p class="form-control-static">{{ current.trip | capitalize }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-select name="trip_id" v-model="form.trip_id" :options="trips">
                                <label slot="label">New Trip</label>
                                <span class="help-block" slot="help-text">
                                    Available trips might be different.
                                </span>
                            </input-select>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Current Role</label>
                            <p class="form-control-static">{{ current.role }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-select name="desired_role" v-model="form.desired_role" :options="roles">
                                <label slot="label">New Role</label>
                                <span class="help-block" slot="help-text">
                                    Available roles may be different.
                                </span>
                            </input-select>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Current Room</label>
                            <p class="form-control-static">{{ current.room }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-select name="room_price_id" v-model="form.room_price_id" :options="prices">
                                <label slot="label">New Room</label>
                                <span class="help-block" slot="help-text">
                                    Available rooms may be different.
                                </span>
                            </input-select>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="row">
                        <div class="col-sm-4">
                            <label>Missionary's Full Name</label>
                            <p class="form-control-static">{{ current.name }}</p>
                        </div>
                        <div class="col-sm-8">
                            <input-text name="name" v-model="name">
                                <label slot="label">Enter Name to Confirm</label>
                                <span class="help-block" slot="help-text">
                                    Please enter the missionary's full name to confirm the transer.
                                </span>
                            </input-text>
                        </div>
                    </div>
                    
                    <hr class="divider">

                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <a :href="'/admin/reservations/' + current.id" class="btn btn-md btn-link">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary" :disabled="!ready">Start Transfer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script type="text/javascript">
    export default {
        name: 'transfer-reservation',
        props: {
            current: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                campaign_id: null,
                group_id: null,
                form: new Form({
                    trip_id: null,
                    desired_role: null,
                    room_price_id: null
                }),
                name: null,
                campaigns: {},
                groups: {},
                trips: {},
                allRoles: {},
                roles: {},
                prices: {},
                method: 'post',
                action: '/reservations/'+this.current.id+'/transfer',
            }
        },
        computed: {
            ready() {
                return this.form.trip_id 
                    && this.form.desired_role 
                    && this.form.room_price_id 
                    && this.name === this.current.name;
            }
        },
        watch: {
            'campaign_id'() {
                this.groups = {};
                this.group_id = null;
                this.getGroups();
            },
            'group_id'() {
                this.trips = {};
                this.form.trip_id = null;
                this.getTrips();
            },
            'form.trip_id'(value) {
                this.prices = {};
                this.form.room_price_id = null;
                this.roles = {'': 'Select a role'};

                if (value) {
                    this.getPrices();
                
                    this.$http.get('trips/'+value).then((response) => {
                        _.each(response.data.data.team_roles, (code) => {
                            this.roles[code] = this.allRoles[code];
                        });
                    });
                }
            }
        },
        methods: {
            getCampaigns() {
                this.$http.get('campaigns?current=true')
                    .then((response) => {
                        this.campaigns = _.extend({'': 'Select a campaign'}, _.mapObject(_.indexBy(response.data.data, 'id'), function(data) {
                            return data.name
                        }));
                    }, (error) =>  {
                        this.$root.$emit('showError', 'Unable to find campaigns');
                    });
            },
            getGroups() {
                this.$http.get('campaigns/'+this.campaign_id+'?include=groups')
                    .then((response) => {
                        this.groups = _.extend({'': 'Select a group'}, _.mapObject(_.indexBy(response.data.data.groups.data, 'id'), function(data) {
                            return data.name
                        }));
                    }, (error) =>  {
                        this.$root.$emit('showError', 'Unable to find groups');
                    });
            },
            getTrips() {
                this.$http.get('trips?filter[campaign_id]='+this.campaign_id+'&filter[group_id]='+this.group_id)
                    .then((response) => {

                        this.trips = _.extend(
                            {'': 'Select a trip'}, 
                            _.mapObject(_.indexBy(response.data.data, 'id'), function(data) {
                                return data.type
                            })
                        );
                        
                    }, (error) =>  {
                        this.$root.$emit('showError', 'Unable to find trips');
                    });
            },
            getRoles() {
                this.$http.get('utilities/team-roles').then((response) => {
                    this.allRoles = response.data.roles;
                });
            },
            getPrices() {
                this.$http.get('trips/'+this.form.trip_id+'/prices?type=optional')
                    .then((response) => {
                        this.prices = _.extend({'': 'Select a room'}, _.mapObject(_.indexBy(response.data.data, 'id'), function(data) {
                            return data.cost.name
                        }));
                    }, (error) =>  {
                        this.$root.$emit('showError', 'Unable to find rooms');
                    });
            },
            onSubmit(){
                this.form.submit(this.method, this.action)
                    .then(data => {

                        if (this.method == 'post') {
                            this.form.reset();
                            this.$root.$emit('form:reset');
                            this.$forceUpdate();
                        }
                        
                        this.$root.$emit('form:success', data);
                        this.$emit('form:success', data);

                    })
                    .catch(error => {
                        
                        this.$root.$emit('form:error', error);

                    });
            }
        },
        mounted() {
            this.getCampaigns();
            this.getRoles();
        }
    }
</script>
