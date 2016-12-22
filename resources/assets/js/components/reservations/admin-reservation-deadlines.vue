<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

        <button class="btn btn-primary btn-xs" @click="add">
            <span class="fa fa-plus"></span> Add Existing
        </button>
        <!--<button class="btn btn-primary btn-xs" @click="new">
            <span class="fa fa-plus"></span> Create New
        </button>-->

        <hr class="divider sm">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Due Date</th>
                <th>Grace Period</th>
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
                <td>{{ deadline.grace_period }} {{ deadline.grace_period | pluralize 'day' }}</td>
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

        <modal title="Edit Deadline" :show.sync="showEditModal" effect="fade" width="800" :callback="update">
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

        <modal title="New Deadline" :show.sync="showNewModal" effect="fade" width="800" :callback="addNew">
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
                                            <date-picker class="form-control input-sm" :time.sync="newDeadline.date|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                            <input type="datetime" id="due_at" class="form-control input-sm hidden"
                                                   v-model="newDeadline.date" v-validate:due="{required: true}">
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

<script type="text/javascript">
    import vSelect from 'vue-select';
    export default{
        name: 'admin-reservation-deadlines',
        props: ['id'],
        components:{ vSelect },
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
            add(){
                this.attemptSubmit = false;
                this.showAddModal = true;
            },
            new(){
                this.attemptSubmit = false;
                this.showNewModal = true;

            },
            edit(deadline){
                this.editedDeadline = deadline;
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            update(){
                // prep current deadlines
                let currentDeadlineIds = [];
                _.some(this.reservation.deadlines.data, function (dl) {
                    if (dl.id === this.editedDeadline.id) {
                        return dl = this.editedDeadline;
                    }
                }.bind(this));

                let reservation = this.preppedReservation;
                reservation.deadlines = this.reservation.deadlines.data;

                return this.doUpdate(reservation);
            },
            remove(deadline){
                let reservation = this.preppedReservation;
                reservation.deadlines = _.reject(this.reservation.deadlines.data, function (dl) {
                    return dl.id === deadline.id
                });

                return this.doUpdate(reservation);
            },
            addDeadlines(){
                // prep current deadlines
                let currentDeadlineIds = [];
                _.each(this.reservation.deadlines.data, function (dl) {
                    currentDeadlineIds.push({ id: dl.id, grace_period: dl.grace_period })
                });
                // prep added deadlines
                let selectedDeadlineIds = [];
                _.each(this.selectedDeadlines, function (dl) {
                    selectedDeadlineIds.push({ id: dl.id, grace_period: dl.grace_period })
                });

                // merge arrays
                let newDeadlines = _.union(currentDeadlineIds, selectedDeadlineIds);
                // filter possible duplicates
                newDeadlines = _.uniq(newDeadlines);

                let reservation = this.preppedReservation;
                reservation.deadlines = newDeadlines;

                return this.doUpdate(reservation);
            },
            addNew(){
                // get trip object
                let trip = this.reservation.trip.data;

                // get only ids of current deadlines so we don't change anything
                trip.deadlines = [];
                _.each(this.reservation.trip.data.deadlines.data, function (dl) {
                    trip.deadlines.push({id: dl.id});
                });
                trip.deadlines.push(this.newDeadline);
                // remove tranformer modified values
                delete trip.difficulty;
                delete trip.rep_id;

                // this.$refs.spinner.show();
                this.$http.put('trips/' + trip.id + '?include=deadlines', trip).then(function (response) {
                    let thisTrip = response.data.data;
                    this.selectedDeadlines = new Array(_.findWhere(response.data.data.deadlines.data, { name: this.newDeadline.name }));

                    return this.addDeadlines();

                });
            },
            doUpdate(reservation){
                // this.$refs.spinner.show();
                return this.resource.update(reservation).then(function (response) {
                    this.setReservationData(response.data.data);
                    this.selectedDeadlines = [];

                    // close modals
                    this.showAddModal = false;
                    this.showEditModal = false;
                    this.showNewModal = false;
                    // this.$refs.spinner.hide();
                });
            },
            getDeadlines(search, loading){
                loading(true);

            },
            setReservationData(reservation){
                this.reservation = reservation;
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
                this.availableDeadlines = _.filter(reservation.trip.data.deadlines.data, function (dl) {
                    return !_.findWhere(reservation.deadlines.data, {id: dl.id})

                });
            }
        },
        ready(){
            // this.$refs.spinner.show();
            this.resource.get().then(function (response) {
                this.setReservationData(response.data.data)
                // this.$refs.spinner.hide();
            });

        }
    }
</script>