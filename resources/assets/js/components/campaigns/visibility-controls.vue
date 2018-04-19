<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Settings</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="publish_squads" @change="save"> Publish Squad Assignments
                        </label>
                        <span class="help-block">
                            Display a reservation's squad and group assignment in the user's dashboard.
                        </span>
                        <label>
                            <input type="checkbox" v-model="publish_regions" @change="save"> Publish Region Assignments
                        </label>
                        <span class="help-block">
                            Display a reservation's region assignment in the user's dashboard.
                        </span>
                        <label>
                            <input type="checkbox" v-model="publish_transports" @change="save"> Publish Trip Transportation
                        </label>
                        <span class="help-block">
                            Display a reservation's transportation arrangements in the user's dashboard.
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="publish_rooms" @change="save"> Publish Room Assignments
                        </label>
                        <span class="help-block">
                            Display a reservation's rooms and accommodations in the user's dashboard.
                        </span>
                        <label>
                            <input type="checkbox" v-model="reservations_locked" @change="save"> Lock Reservation Requirements
                        </label>
                        <span class="help-block">
                            Prevent user from making changes to their reservation travel requirements in the dashboard.
                            <strong>Admins can still modify travel requirements.</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        name: 'visibility-controls',
        data(){
            return{
                publish_squads: false,
                publish_rooms: false,
                publish_regions: false,
                publish_transports: false,
                reservations_locked: false
            }
        },
        props: {
            campaignId: {
				type: String,
				required: true
			}
        },
        methods: {
            fetch() {
                let self = this;
                this.$http.get('campaigns/' + this.campaignId).then((response) => {
                    self.publish_squads = response.data.data.publish_squads;
                    self.publish_rooms = response.data.data.publish_rooms;
                    self.publish_regions = response.data.data.publish_regions;
                    self.publish_transports = response.data.data.publish_transports;
                    self.reservations_locked = response.data.data.reservations_locked;
                });
            },
            save() {
               let self = this;
               this.$http.put('campaigns/' + this.campaignId, {
                    publish_squads: self.publish_squads,
                    publish_rooms: self.publish_rooms,
                    publish_regions: self.publish_regions,
                    publish_transports: self.publish_transports,
                    reservations_locked: self.reservations_locked
               }).then((response) => {
                    this.$root.$emit('showSuccess', 'Assignment(s) Visibility Updated.');
                    self.publish_squads = response.data.data.publish_squads;
                    self.publish_rooms = response.data.data.publish_rooms;
                    self.publish_regions = response.data.data.publish_regions;
                    self.publish_transports = response.data.data.publish_transports;
                    self.reservations_locked = response.data.data.reservations_locked;
               });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>
<style>
</style>