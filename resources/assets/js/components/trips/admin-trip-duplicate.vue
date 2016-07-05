<template>
        <div class="modal fade" id="duplicationModal" tabindex="-1" role="dialog" aria-labelledby="duplicationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Duplicate Trip</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to duplicate this trip?</p>
                        <hr>
                        <validator name="TripDuplication">
                            <form class="form-horizontal" novalidate>
                                <div class="form-group" :class="{ 'has-error': checkForError('group') }">
                                    <label class="col-sm-2 control-label">Group</label>
                                    <div class="col-sm-10">
                                        <v-select class="form-controls" id="group" :value.sync="groupObj" :options="groups" :on-search="getGroups"
                                                  label="name"></v-select>
                                        <select hidden v-model="group_id" v-validate:group="{ required: true}">
                                            <option :value="group.id" v-for="group in groups">{{group.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                                    <label for="type" class="col-sm-2 control-label">Type</label>
                                    <div class="col-sm-10">
                                        <select id="type" class="form-control input-sm" v-model="type"
                                                v-validate:type="{ required: true }" required>
                                            <option value="">-- select --</option>
                                            <option value="full">Full</option>
                                            <option value="media">Media</option>
                                            <option value="medical">Medical</option>
                                            <option value="short">Short</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </validator>
                        <div class="row">
                            <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
                            <div class="col-xs-6"><a @click="duplicateTrip()" class="btn btn-sm btn-block btn-primary">Yes</a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</template>

<script>
    import vSelect from "vue-select"
    export default{
        name: 'admin-trip-duplicate',
        props: ['tripId'],
        components: {vSelect},
        data(){
            return{
                attemptedContinue: false,
                groups: [],
                groupObj: null,
                group_id: null,
                type: '',
                trip: null
            }
        },
        computed: {
            group_id(){
                return _.isObject(this.groupObj) ? this.groupObj.id : null;
            }
        },
        methods:{
            getGroups(search, loading){
                loading(true);
                this.$http.get('groups', { search: search }).then(function (response) {
                    this.groups = response.data.data;
                    loading(false);
                });
            },
            checkForError(field){
                // if user clicked continue button while the field is invalid trigger error styles
                return this.$TripDuplication[field.toLowerCase()].invalid && this.attemptedContinue;
            },
            duplicateTrip(){
                this.$validate('group', true);
                this.attemptedContinue = true;
                if (this.$TripDuplication.valid) {
                    this.$http.get('trips/' + this.tripId, { include: 'campaign,costs.payments,requirements,notes,deadlines'}).then(function (trip) {
                        this.trip = trip.data.data;
                        $.extend(this.trip, {
                            type: this.type,
                            group_id: this.group_id
                        });
                        this.$http.post('trips', this.trip).then(function (response) {
                            console.log(response);
                        }, function (error) {
                            console.log(error);
                        });
                    });
                }
            }

        }
    }
</script>
