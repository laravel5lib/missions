<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments</h5>
        </div><!-- end panel-heading -->
        <div class="list-group" v-if="reservation">
            <div class="list-group-item" v-for="due in reservation.dues.data">
                <div class="row">
                    <div class="col-md-3">
                        <label>Balance Due</label>
                        <p>{{ due.balance | currency }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-6">
                        <label>Cost</label>
                        <p>{{ due.cost }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-3">
                        <label>{{ due.due_at | moment 'll' }}</label>
                        <p>
                            <span class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending' }">{{due.status|capitalize}}</span>
                        </p>
                        <hr class="divider inv hidden-lg">
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end panel -->



    <!--<div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Status</th>
                <th>Cost Name</th>
                <th>Outstanding Balance</th>
                <th>Grace Period</th>
                <th>Due</th>
                <th></th>
            </tr>
            </thead>
            <tbody v-if="reservation">
            <template v-for="due in reservation.dues.data">
                <tr>
                    <td class="text-center">
                        <small class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending', }">{{due.status|capitalize}}</small>
                    </td>
                    <td>{{ due.cost }}</td>
                    <td>{{ due.balance | currency }}</td>
                    <td>{{ due.grace_period }}</td>
                    <td>{{ due.due_at | moment 'll' }}</td>
                    <td>
                        <a class="btn btn-default btn-xs" @click="edit(due)"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger btn-xs" @click="remove(due)"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>-->
</template>

<script type="text/javascript">
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
        name: 'reservation-dues',
        props: ['id'],
        components:{ vSelect, 'modal': VueStrap.modal},
        data(){
            return{
                reservation: null,
                reservationsDues:[],
                dues:[],
                selectedDues: [],
                availableDues: [],
                resource: this.$resource('reservations/' + this.id, { include: 'dues,costs.payments,trip.costs.payments' }),
                showAddModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,

                preppedReservation : {}
            }
        },
        methods: {
            dateIsBetween(a, b){
                    var start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                var stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddDue[field].invalid && this.attemptSubmit;
            },
            checkForEditDueError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditDue[field].invalid && this.attemptSubmit;
            },
            checkForNewDueError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$NewDue[field].invalid && this.attemptSubmit;
            },
            resetDue(){
                this.newDue = {
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
            addNew(){
                this.attemptSubmit = false;
                this.showNewModal = true;

            },
            edit(due){
                due.due_at = moment(due).format('YYYY-MM-DDTHH:MM:SS');
                this.editedDue = due;
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            updateDue(){
                console.log(this.editedDue.due_at);
                // prep current dues
                var dues = [];
                _.each(this.reservation.dues.data, function (due) {
                    if (due.cost_id === this.editedDue.cost_id) {
                        dues.push({ id: this.editedDue.cost_id, grace_period: this.editedDue.grace_period, due_at: this.editedDue.due_at });
                    } else {
                        dues.push({id: due.cost_id, grace_period: due.grace_period});
                    }
                }.bind(this));

                var reservation = this.preppedReservation;
                reservation.dues = dues;

                return this.doUpdate(reservation);
            },
            remove(due){
                var reservation = this.preppedReservation;
                reservation.dues = [];
                _.each(this.reservation.dues.data, function (cs) {
                    if (cs.due_id !== due.due_id) {
                        reservation.dues.push({ id: cs.due_id/*, locked: cs.locked*/})
                    }
                });
                console.log(reservation.dues);

                return this.doUpdate(reservation);
            },
            addDues(){
                // prep current dues
                var currentDueIds = [];
                _.each(this.reservation.dues.data, function (due) {
                    currentDueIds.push({ id: due.id || due.due_id, locked: due.locked })
                });

                // prep added dues
                var selectedDueIds = [];
                _.each(this.selectedDues, function (due) {
                    selectedDueIds.push({ id: due.id })
                });

                // merge arrays
                var newDues = _.union(currentDueIds, selectedDueIds);
                // filter possible duplicates
                newDues = _.uniq(newDues);

                var reservation = this.preppedReservation;
                reservation.dues = newDues;

                return this.doUpdate(reservation);
            },
            addNew(){
                // get trip object
                var trip = this.reservation.trip.data;

                // get only ids of current dues so we don't change anything
                trip.dues = [];
                _.each(this.reservation.trip.data.dues.data, function (dl) {
                    trip.dues.push({id: dl.id});
                });
                trip.dues.push(this.newDeadline);
                // remove tranformer modified values
                delete trip.difficulty;
                delete trip.rep_id;

                this.$http.put('trips/' + trip.id, trip).then(function (response) {
                    var thisTrip = response.data.data;
                    this.selecteddues = new Array(this.newDeadline);

                    return this.adddues();

                });
            },

            getDues(search, loading){
                loading(true);

            },
            doUpdate(reservation){
                return this.resource.update(reservation).then(function (response) {
                    this.setReservationData(response.data.data);
                    this.selectedDues = [];
                });
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

                // get available costs intersect with current
                this.availableCosts = _.filter(reservation.trip.data.costs.data, function (cost) {
                    return !_.findWhere(reservation.costs.data, {cost_id: cost.id, type: 'incremental' || 'optional'})
                });

                // Extend dues data

            }
        },
        ready(){
            /*this.resource.get().then(function (response) {
                this.setReservationData(response.data.data)
            });*/

            //Listen to Event Bus
            this.$root.$on('Reservation:CostsUpdated', function (data) {
                this.setReservationData(data)
            }.bind(this));

        }
    }
</script>