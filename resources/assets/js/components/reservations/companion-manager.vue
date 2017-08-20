<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>
                        <form class="form-inline">
                        <a class="text-muted" @click="editMode=!editMode" v-if="firstUrlSegment === 'admin'">
                            <i class="fa fa-cog"></i>
                        </a>
                        Companions {{ companions.length }} of 
                        <span v-if="editMode">
                            <span class="input-group input-group-sm col-lg-3 col-md-4 col-sm-8 col-xs-12">
                                <input type="number" class="form-control" v-model="limit">
                                <span class="input-group-btn">
                                    <button class="btn btn-default-hollow" 
                                            type="button" style="margin-top:0"
                                            @click.prevent="update()">
                                        Save
                                    </button>
                                </span>
                            </span>
                        </span>
                        <span v-else>{{ limit }}</span>
                        </form>
                    </h5>
                </div>
                <div class="col-xs-4 text-right">
                    <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#LeaveCompanionsModal" v-show="companions.length > 0">
                        <span class="fa fa-remove"></span> Leave
                    </button>
                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#JoinCompanionsModal">
                        <span class="fa fa-plus"></span> Add
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <p class="text-center text-muted" v-show="companions.length < 1">No companions found.</p>
            <div class="col-xs-12 panel panel-default" v-for="companion in companions" :key="companion.id">
                <h5>
                    <a :href="`/admin/reservations/${ companion.id }`">
                        <img :src="companion.avatar + '?w=50&h=50'" class="img-circle av-left" width="50" height="50" :alt=" companion.given_names ">
                        {{ companion.given_names }} {{ companion.surname }}
                        <small> &middot; <em>{{ companion.relationship|capitalize }}</em></small>
                    </a>
                </h5>
            </div>
        </div>
        <div class="modal fade" id="JoinCompanionsModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Join Companions</h4></div>
                    <div class="modal-body">

                            <form novalidate>
                                <div class="form-group">
                                    <label class="control-label">Relationship</label>
                                    <select class="form-control" v-model="relationship">
                                        <option value="family">Family</option>
                                        <option value="friend">Friend</option>
                                        <option value="spouse">Spouse</option>
                                        <option value="guardianship">Guardianship</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group" :class="{ 'has-error': errors.has('reservation') }">
                                    <label class="control-label">Reservation</label>
                                    <span class="help-block" v-show="reservations.length < 1"><i class="fa fa-warning"></i> If your search yields no options it could mean (1) the reservation does not exist or (2) the reservation belongs to another group or campaign.</span>
                                    <v-select @keydown.enter.prevent=""  class="form-control" id="Reservation" v-model="reservationObj" :options="reservations" :on-search="getReservations" label="label"></v-select>
                                    <!-- <select hidden="" v-model="newCompanion.companion_reservation_id" name="reservation" v-validate="'required'">
                                        <option :value="reservation.id" v-for="reservation in reservations">{{reservation.name}}</option>
                                    </select> -->
                                </div>
                            </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" @click="join">Join</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="LeaveCompanionsModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Leave Companions</h4></div>
                    <div class="modal-body">
                        <p class="text-center">Leave your companions? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" @click="leave">Leave</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>

<script>
import vSelect from "vue-select";
export default {
  name: 'companion-manager',
  components: {vSelect},
  props: {
    'reservationId': {
        type: String,
        required: true
    }
  },
  data () {
    return {
        editMode: false,
        reservations: [],
        reservationObj: null,
        limit: 0,
        companions: [],
        relationship: 'family',
        attemptSubmit: false,
        resource: this.$resource('reservations{/reservation}/companions{/companion}')
    };
  },
  computed: {
        companion_reservation_id: function() {
            return _.isObject(this.reservationObj) ? this.reservationObj.id : null;
        }
    },
  methods: {
    getReservations(search, loading) {
        loading(true);
        var that = this;
        this.$http.get('reservations?campaign='+this.campaignId+'&groups[]='+this.groupId+'&search='+search+'&per_page=5&ignore[]=' + this.reservationId).then((response) => {
            this.reservations = _.chain(response.data.data).reject(function(reservation) {
                return _.chain(that.companions).pluck('id').contains(reservation.id).value();
            }).map(function(reservation) {
                return {
                    id: reservation.id,
                    label: reservation.given_names + ' ' + reservation.surname + ' - ' + reservation.email
                }
            }).value();
            loading(false);
        }, (response) =>  {
            this.$root.$emit('showError', 'Could not retreive reservations.');
        });
    },
    getReservation() {
        this.$http.get('reservations/'+this.reservationId+'?include=trip').then((response) => {
            this.limit = response.data.data.companion_limit;
            this.campaignId = response.data.data.trip.data.campaign_id;
            this.groupId = response.data.data.trip.data.group_id;
        }, (response) =>  {
            this.$root.$emit('showError', 'Could not retreive reservation.');
        });
    },
    fetch() {
        this.resource.get({reservation: this.reservationId}).then((response) => {
            this.companions = response.data.data;
        }, (response) =>  {
            this.$root.$emit('showError', 'Could not retreive companions.');
        });
    },
    join() {
        this.resource.post({reservation: this.reservationId}, {
            relationship: this.relationship,
            companion_reservation_id: this.companion_reservation_id
        }).then((response) => {
            this.$root.$emit('showSuccess', 'Joined new companions.');
            $('#JoinCompanionsModal').modal('hide');
            this.reservationObj = null;
            this.reservations = [];
            this.fetch();
        }, (response) =>  {
            console.log(_.first(_.toArray(response.data.errors)));
            this.$root.$emit('showError', _.first(_.toArray(response.data.errors)));
        });
    },
    leave() {
        this.resource.delete({reservation: this.reservationId}).then((response) => {
            this.$root.$emit('showSuccess', 'Successfully left companions.');
            $('#LeaveCompanionsModal').modal('hide');
            this.fetch();
        }, (response) =>  {
            this.$root.$emit('showError', 'Could not leave companions.');
        });
    },
    update() {
        this.$http.put('reservations/' + this.reservationId, {companion_limit: this.limit})
            .then((response) => {
                this.$root.$emit('showSuccess', 'Successfully updated companion limit.');
                this.editMode = false;
            }, (response) =>  {
                this.$root.$emit('showError', 'Could not update companion limit.');
            });
    }
  },
  mounted() {
    this.fetch();
    this.getReservation();
  }
}
</script>