<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <button class="btn btn-primary btn-xs" @click="showAddModal=!showAddModal"><span
                class="fa fa-plus"></span> Add Existing
        </button>
        <button class="btn btn-primary btn-xs" @click="showNewModal=!showNewModal"><span
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
                <validator name="AddDeadline">
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
                <validator name="EditDeadline">
                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditDeadlineError('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForEditDeadlineError('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="editedDeadline.grace_period"
                                               v-validate:grace="{required: true, min:0}">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal title="New Deadline" :show.sync="showNewModal" effect="fade" width="800" :callback="update(editedDeadline)">
            <div slot="modal-body" class="modal-body">
                <validator name="NewDeadline">
                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForNewDeadlineError('name')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="newDeadline.name" v-validate:name="{require:true}" class="form-control input-sm">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForNewDeadlineError('grace') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': checkForNewDeadlineError('grace') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="newDeadline.grace_period"
                                                       v-validate:grace="{required: true, min:0}">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForNewDeadlineError('due')}">
                                            <label for="due_at">Due</label>
                                            <input type="date" id="due_at" class="form-control input-sm"
                                                   v-model="newDeadline.due_at" v-validate:due="{required: true}">
                                        </div>

                                    </div>
                                </div>

                                <br>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="newDeadline.enforced">
                                        Enforced?
                                    </label>
                                </div>
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
                editedDeadline: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                newDeadline: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
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
            checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddDeadline[field].invalid && this.attemptSubmit;
            },
            checkForEditDeadlineError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditDeadline[field].invalid && this.attemptSubmit;
            },
            checkForNewDeadlineError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$NewDeadline[field].invalid && this.attemptSubmit;
            },
            resetDeadline(){
                this.newDeadline = {
                    item: '',
                    item_type: '',
                    due_at: null,
                    grace_period: 0,
                    enforced: false,
                };
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