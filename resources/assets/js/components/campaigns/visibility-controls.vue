<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Assignment(s) Visibility</h5>
        </div>
        <div class="panel-body">
            <div class="checkbox">
                <label>
                    <input type="checkbox" v-model="publish_squads"> Publish Squad Assignments
                </label>
                <span class="help-block">
                    Display a reservation's squad and group assignment in the user's dashboard.
                </span>
                <label>
                    <input type="checkbox" v-model="publish_regions"> Publish Region Assignments
                </label>
                <span class="help-block">
                    Display a reservation's region assignment in the user's dashboard.
                </span>
                <label>
                    <input type="checkbox" v-model="publish_transports"> Publish Trip Transportation
                </label>
                <span class="help-block">
                    Display a reservation's transportation arrangements in the user's dashboard.
                </span>
                <label>
                    <input type="checkbox" v-model="publish_rooms"> Publish Room Assignments
                </label>
                <span class="help-block">
                    Display a reservation's rooms and accommodations in the user's dashboard.
                </span>
            </div>
        </div>
        <div class="panel-heading">
            <h5>Restrictions</h5>
        </div>
        <div class="panel-body">
            <div class="checkbox">
                <label>
                    <input type="checkbox" v-model="reservations_locked"> Lock Reservation Requirements
                </label>
                <span class="help-block">
                    Prevent user from making changes to their reservation travel requirements in the dashboard.
                    <strong>Admins can still modify travel requirements.</strong>
                </span>
            </div>
        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-primary" @click="save()">Save</button>
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
                this.$http.get('campaigns/' + this.campaignId).then(function (response) {
                    self.publish_squads = response.body.data.publish_squads;
                    self.publish_rooms = response.body.data.publish_rooms;
                    self.publish_regions = response.body.data.publish_regions;
                    self.publish_transports = response.body.data.publish_transports;
                    self.reservations_locked = response.body.data.reservations_locked;
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
               }).then(function (response) {
                    this.$root.$emit('showSuccess', 'Assignment(s) Visibility Updated.');
                    self.publish_squads = response.body.data.publish_squads;
                    self.publish_rooms = response.body.data.publish_rooms;
                    self.publish_regions = response.body.data.publish_regions;
                    self.publish_transports = response.body.data.publish_transports;
                    self.reservations_locked = response.body.data.reservations_locked;
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