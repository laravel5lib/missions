<template>
    <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="cancel()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Transfer Reservation?</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning"><strong>Important:</strong> Transferring a reservation will reset it's travel requirements, applied costs, and remove it from any teams or rooms it may already be assigned to. This action cannot be reversed.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Transfer to Group:</label>
                            <select class="form-control" v-model="selectedGroupId">
                                <option v-for="group in groups" v-bind:value="group.id">
                                        {{ group.name ? group.name[0].toUpperCase() + group.name.slice(1) : '' }}
                                </option>
                            </select>
                            <span class="help-block">Only group's with active trips participating in the same campaign are available.</span>
                            
                            <div v-if="selectedGroupId">
                                <label>Register for Trip Type:</label>
                                <select class="form-control" v-model="selectedTrip">
                                    <option v-for="trip in trips" v-bind:value="trip">
                                        {{ trip.type ? trip.type[0].toUpperCase() + trip.type.slice(1) : '' }}
                                    </option>
                                </select>
                                <span class="help-block">Available trips may have changed. Please select a new trip.</span>
                            </div>
                            
                            <div v-if="selectedTrip">
                                <label>Assign Role:</label>
                                <select class="form-control" v-model="selectedRole">
                                    <option v-for="role in roles" v-bind:value="role.value">
                                        {{ role.name }}
                                    </option>
                                </select>
                                <span class="help-block">
                                    Available role types may have changed. Please select a new role.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-6"><a class="btn btn-md btn-block btn-default" @click="cancel()">Cancel</a></div>
                        <div class="col-xs-6">
                            <a @click="transfer()" class="btn btn-md btn-block btn-primary" v-html="button" :disabled="! selectedRole"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'transfer-reservation',
        props: {
            'id': {
                type: String,
                required: true
            },
            'campaignId': {
                type: String,
                required: true
            }
        },
        data(){
            return {
                groups: [],
                trips: [],
                roles: [],
                selectedGroupId: null,
                selectedTrip: null,
                selectedRole: null,
                button: 'Transfer'
            }
        },
        watch: {
            'selectedGroupId': function() {
                this.selectedTrip = null;
                this.selectedRole = null;
                this.getTrips();
            },
            'selectedTrip': function(val) {
                if (! val) return;

                this.$http.get('utilities/team-roles').then(function (response) {
                    _.each(response.body.roles, function (name, key) {
                        if (_.contains(val.team_roles, key))
                            this.roles.push({ value: key, name: name});
                    }.bind(this));
                });
            }
        },
        methods: {
            getGroups() {
                this.$http.get('campaigns/'+this.campaignId+'?include=groups')
                .then(function (response) {
                    this.groups = response.data.data.groups.data;
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to find groups');
                });
            },
            getTrips() {
                this.$http.get('trips?status=active&campaign='+this.campaignId+'&groups[]='+this.selectedGroupId)
                .then(function (response) {
                    this.trips = response.data.data;
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to find trips');
                });
            },
            transfer() {
                this.button = '<i class="fa fa-circle-o-notch fa-spin"></i> Transferring...'
                this.$http.post('reservations/' + this.id + '/transfer', {
                    trip_id: this.selectedTrip.id, desired_role: this.selectedRole
                })
                .then(function (response) {
                    this.selectedGroupId = null;
                    this.selectedTrip = null;
                    this.selectedRole = null;
                    this.button = 'Transfer';
                    $('#transferModal').modal('hide');
                    this.$root.$emit('showSuccess', 'Reservation transferred and registrant notified');
                    setTimeout(location.reload.bind(location), 300);
                }, function (error) {
                    this.button = 'Transfer';
                    this.$root.$emit('showError', 'Unable to transfer');
                });
            },
            cancel() {
                this.selectedGroupId = null;
                this.selectedTrip = null;
                this.selectedRole = null;
                $('#transferModal').modal('hide');
            }
        },
        mounted() {
            this.getGroups();
        }
    }
</script>
