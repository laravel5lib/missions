<template>
        <div class="modal fade" id="duplicationModal" tabindex="-1" role="dialog" aria-labelledby="duplicationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Duplicate Trip</h4>
                    </div>
                    <div class="modal-body">
                        <p>Duplicate this trip?</p>
                        <hr>

                            <form class="form-horizontal" novalidate>
                                <div class="form-group" :class="{ 'has-error': errors.has('group') }">
                                    <label class="col-sm-2 control-label">Group</label>
                                    <div class="col-sm-10">
                                        <v-select @keydown.enter.prevent=""  class="form-control" id="group" :value="groupObj" :options="groups" :on-search="getGroups"
                                                  label="name"></v-select>
                                        <select hidden v-model="group_id" name="group" v-validate="'required'">
                                            <option :value="group.id" v-for="group in groups">{{group.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" :class="{ 'has-error': errors.has('type') }">
                                    <label for="type" class="col-sm-2 control-label">Type</label>
                                    <div class="col-sm-10">
                                        <select id="type" class="form-control input-sm" v-model="type"
                                                name="type" v-validate="'required'" required>
                                            <option value="">-- select --</option>
                                            <option value="ministry">Ministry</option>
                                            <option value="family">Family</option>
                                            <option value="media">Media</option>
                                            <option value="medical">Medical</option>
                                            <option value="international">International</option>
                                            <option value="leader">Leader</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                        <div class="row">
                            <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
                            <div class="col-xs-6"><a @click="duplicateTrip()" class="btn btn-sm btn-block btn-primary">Yes</a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</template>

<script type="text/javascript">
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
                this.$http.get('groups', { params: { search: search } }).then((response) => {
                    this.groups = response.data.data;
                    loading(false);
                });
            },
            errors.has(field){
                // if user clicked continue button while the field is invalid trigger error styles
                return this.$TripDuplication[field.toLowerCase()].invalid && this.attemptedContinue;
            },
            duplicateTrip(){
                this.$validate('group', true);
                this.attemptedContinue = true;
                if (this.$TripDuplication.valid) {
                    this.$http.get('trips/' + this.tripId, { params: { include: 'campaign,costs.payments,requirements,notes,deadlines'} }).then((trip) => {
                        let payments = {};
                        this.trip = trip.data.data;
                        $.extend(this.trip, {
                            type: this.type,
                            group_id: this.group_id,
                            difficulty: this.trip.difficulty.split(' ')[0] + '_' + this.trip.difficulty.split(' ')[1]
                        });
                        // trim costs
                        this.trip.costs = this.trip.costs.data;
                        _.each(this.trip.costs, function (cost) {
                            // use name as reference to new duplicated costs
                            payments[cost.name] = cost.payments.data;

                            delete cost.id;
                            delete cost.payments;
                            delete cost.links;
                        });
                        // trim deadlines
                        this.trip.deadlines = this.trip.deadlines.data;
                        _.each(this.trip.deadlines, function (deadline) {
                            delete deadline.links;
                        });
                        // trim requirements
                        this.trip.requirements = this.trip.requirements.data;
                        _.each(this.trip.requirements, function (requirement) {
                            delete requirement.links;
                        });
                        // trim notes
                        this.trip.notes = this.trip.hasOwnProperty('notes') ? this.trip.notes.data : undefined;

                        // for now remove rep_id and links
                        //delete this.trip.rep_id;
                        delete this.trip.links;
                        delete this.trip.created_at;
                        delete this.trip.updated_at;

                        this.$http.post('trips/duplicate', this.trip, { params: { include: 'costs.payments'}}).then((response) => {
                            let costPromises = [];
                            _.each(this.trip.costs, function (cost) {
                                // assign cost to trip
                                cost.cost_assignable_type = 'trips';
                                cost.cost_assignable_id = response.data.data.id;

                                // add payments array from costs based on name
                                cost.payments = payments[cost.name];
                                // duplicate cost
                                costPromises.push(this.$http.post('costs', cost).then((res) => {
                                }, function (error) {
                                    console.log(error);
                                }));
                            }.bind(this));

                            Promise.all(costPromises).then((newCosts) => {
                                window.location.href = '/admin' + response.data.data.links[0].uri;
                            }.bind(this));
                        }, function (error) {
                            console.log(error);
                        });
                    });
                }
            }
        }
    }
</script>
