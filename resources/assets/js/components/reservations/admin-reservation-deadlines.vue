<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <button class="btn btn-primary btn-xs" @click="showAddModal=!showAddModal"><span
                class="fa fa-plus"></span> Add Existing
        </button>
        <button class="btn btn-primary btn-xs" @click="showAddModal=!showAddModal"><span
                class="fa fa-plus"></span> Create New
        </button>

        <hr class="divider sm">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Name</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody v-if="reservation">
            <tr v-for="deadline in reservation.deadlines.data">
                <td>
                    <i class="fa {{ isPast(deadline.due_at) ? 'fa-times text-danger' : 'fa-exclamation text-warning' }}"></i>&nbsp;
                    {{ deadline.name ? deadline.name : !deadline.cost_name ? deadline.cost_name : deadline.item  + ' Submission' }}
                </td>
                <td>{{ deadline.due_at| moment 'll' }}</td>
                <td>
                    <a class="btn btn-default btn-xs" @click="edit(deadline)"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-danger btn-xs" @click="remove(deadline)"><i class="fa fa-times"></i></a>
                </td>
            </tr>
            </tbody>
        </table>

        <modal title="Add Deadlines" :show.sync="showAddModal" effect="fade" width="800" :callback="addDeadlines">
            <div slot="modal-body" class="modal-body">
                <validator name="AddManager">
                    <form class="form-horizontal" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('deadlines') }"><label
                                class="col-sm-2 control-label">Available Deadlines</label>
                            <div class="col-sm-10">
                                <v-select class="form-control" id="user" multiple :value.sync="selectedDeadlines" :options="availableDeadlines"
                                          label="name"></v-select>
                                <select hidden="" v-model="user_id" v-validate:deadlines="{ required: true }" multiple>
                                    <option :value="deadline.id" v-for="deadline in deadlines">{{deadline.name}}</option>
                                </select></div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal title="Edit Deadline" :show.sync="showEditModal" effect="fade" width="800" :callback="update(editedDeadline)">
            <div slot="modal-body" class="modal-body">
                <validator name="EditManager">
                    <form class="form-horizontal" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('deadlines') }"><label
                                class="col-sm-2 control-label">Available Deadlines</label>
                            <div class="col-sm-10">
                                <v-select class="form-control" id="user" multiple :value.sync="selectedDeadlines" :options="availableDeadlines"
                                          label="name"></v-select>
                                <select hidden="" v-model="user_id" v-validate:deadlines="{ required: true }" multiple>
                                    <option :value="deadline.id" v-for="deadline in deadlines">{{deadline.name}}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal title="New Deadline" :show.sync="showNewModal" effect="fade" width="800" :callback="update(editedDeadline)">
            <div slot="modal-body" class="modal-body">
                <validator name="EditManager">
                    <form class="form-horizontal" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('deadlines') }"><label
                                class="col-sm-2 control-label">Available Deadlines</label>
                            <div class="col-sm-10">
                                <v-select class="form-control" id="user" multiple :value.sync="selectedDeadlines" :options="availableDeadlines"
                                          label="name"></v-select>
                                <select hidden="" v-model="user_id" v-validate:deadlines="{ required: true }" multiple>
                                    <option :value="deadline.id" v-for="deadline in deadlines">{{deadline.name}}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>
    </div>
</template>

<script>
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
        name: 'admin-reservation-deadlines',
        props: ['id'],
        components:{ vSelect, 'modal': VueStrap.modal},
        data(){
            return{
                reservation: null,
                reservationsDeadlines:[],
                deadlines:[],
                selectedDeadlines: [],
                availableDeadlines: [],
                editedDeadline: null,

                resource: this.$resource('reservations/' + this.id, { include: 'deadlines,trip.deadlines' }),
                showAddModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,

                preppedReservation : {}
            }
        },
        computed:{},
        methods: {
            checkForError: function checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddManager[field].invalid && this.attemptSubmit;
            },

            isPast(date){
                return moment().isAfter(date);
            },
            edit(deadline){
                this.editedDeadline = deadline;
                this.showEditModal = true;
            },
            update(deadline){
//                this.resource.delete()
            },
            remove(deadline){
                var reservation = this.preppedReservation;
                reservation.deadlines = _.reject(this.reservation.deadlines.data, function (dl) {
                    return dl.id === deadline.id
                });

                return this.doUpdate(reservation);
            },
            addDeadlines(){
                var currentDeadlineIds = _.isArray(this.deadlines) ? _.pluck(this.deadlines, 'id') : [];
                var selectedDeadlineIds = _.pluck(this.selectedDeadlines, 'id') || [];
                var newDeadlines = _.union(currentDeadlineIds, selectedDeadlineIds);
                newDeadlines = _.uniq(newDeadlines);

                var reservation = this.preppedReservation;
                reservation.deadlines = newDeadlines;

                return this.doUpdate(reservation);
            },
            doUpdate(reservation){
                return this.resource.update(reservation).then(function (response) {
                    debugger;
                    // console.log(response);
                    this.selectedDeadlines = [];
                });
            },
            getDeadlines(search, loading){
                loading(true);

            }
        },
        ready(){
            this.resource.get().then(function (response) {
                this.reservation = response.data.data;
                this.preppedReservation = {
                    given_names: this.reservation.given_names,
                    surname: this.reservation.surname,
                    gender: this.reservation.gender,
                    status: this.reservation.status,
                    shirt_size: this.reservation.shirt_size,
                    birthday: this.reservation.birthday,
                    user_id: this.reservation.user_id,
                    trip_id: this.reservation.trip_id,
                };

                // get available deadlines intersect with current
                this.availableDeadlines = _.intersection(response.data.data.trip.data.deadlines.data, response.data.data.deadlines.data);
            });

        }
    }
</script>