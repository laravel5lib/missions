<template >
    <div style="position:relative;">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <!--<button class="btn btn-primary btn-xs" @click="add"><span
					   class="fa fa-plus"></span> Add Existing
			   </button>-->
        <!--<button class="btn btn-primary btn-xs" @click="addNew"><span
                class="fa fa-plus"></span> Create New
        </button>-->

        <!--<hr class="divider sm">-->
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Status</th>
                <th>Cost Name</th>
                <th>Outstanding</th>
                <th>Due</th>
                <th>Grace Period</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="due in dues" :key="due.id">
                <tr>
                    <td class="text-center">
                        <small class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending', }">{{ due.status|capitalize }}</small>
                    </td>
                    <td>{{ due.cost }}</td>
                    <td>{{ due.balance | currency }}</td>
                    <td v-if="due.type === 'static'">Immedately</td>
                    <td v-else>{{ due.due_at | moment('lll') }}</td>
                    <td>{{ due.grace_period }} days</td>
                    <td>
                        <a class="btn btn-default btn-xs" @click="edit(due)"><i class="fa fa-pencil"></i></a>
                        <!--<a class="btn btn-danger btn-xs" @click="remove(due)"><i class="fa fa-times"></i></a>-->
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <!--<modal title="Add Dues" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800" :callback="addDues">
            <div slot="modal-body" class="modal-body">

                    <form class="for" novalidate>
                        <div class="form-group" :class="{ 'has-error': errors.has('dues') }">
                            <label class="control-label">Available Dues</label>
                            <v-select @keydown.enter.prevent=""  class="form-control" id="user" multiple :value="selectedDues" :options="availableDues"
                                      label="name"></v-select>
                            <select hidden="" v-model="user_id" name="dues" v-validate="'required'" multiple>
                                <option :value="due.id" v-for="due in dues">{{due.name}}</option>
                            </select>
                        </div>
                    </form>

            </div>
        </modal>-->

        <modal title="Edit Due" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800" :callback="updateDue">
            <div slot="modal-body" class="modal-body">

                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditDueError('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForEditDueError('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="editedDue.grace_period"
                                               name="grace" v-validate="'required'">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <!--<input id="due_date" type="datetime" class="form-control" v-model="editedDue.due_at">-->
                                    <date-picker :model="editedDue.due_at|moment('YYYY-MM-DD HH:mm:ss')" v-if="editedDue.due_at"></date-picker>
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
        </modal>
    </div>
</template>

<script type="text/javascript">
    import vSelect from 'vue-select';
    export default{
        name: 'admin-reservation-dues',
        props: ['id'],
        components:{ vSelect},
        data(){
            return{
                reservation: null,
                reservationsDues:[],
                dues:[],
                pagination: { current_page: 1 },
                selectedDues: [],
                availableDues: [],
                editedDue: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                newDue: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                resource: this.$resource('reservations/' + this.id + '/dues{/due_id}'),
                showAddModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,
                preppedReservation : {}
            }
        },
        computed:{},
        methods: {
            dateIsBetween(a, b){
                    let start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                let stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            errors.has(field) {
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
            /*add(){
                this.attemptSubmit = false;
                this.showAddModal = true;
            },
            addNew(){
                this.attemptSubmit = false;
                this.showNewModal = true;

            },*/
            edit(due){
                this.editedDue = _.extend({}, due);
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            updateDue(){
                this.resource.put({due_id: this.editedDue.id}, {
                    due_at: this.editedDue.due_at,
                    grace_period: this.editedDue.grace_period,
                }).then((response) => {
                    this.showEditModal = false;
                    this.getDues();
                    this.editedDue = {
                        name: '',
                        date: null,
                        grace_period: 0,
                        enforced: false,
                    };
                });
            },
            /*remove(due){
                let reservation = this.preppedReservation;
                reservation.dues = [];
                _.each(this.reservation.dues.data, (cs) => {
                    if (cs.due_id !== due.due_id) {
                        reservation.dues.push({ id: cs.due_id/!*, locked: cs.locked*!/})
                    }
                });
                console.log(reservation.dues);

                return this.doUpdate(reservation);
            },*/
            /*addDues(){
                // prep current dues
                let currentDueIds = [];
                _.each(this.reservation.dues.data, (due) => {
                    currentDueIds.push({ id: due.id || due.due_id, locked: due.locked })
                });

                // prep added dues
                let selectedDueIds = [];
                _.each(this.selectedDues, (due) => {
                    selectedDueIds.push({ id: due.id })
                });

                // merge arrays
                let newDues = _.union(currentDueIds, selectedDueIds);
                // filter possible duplicates
                newDues = _.uniq(newDues);

                let reservation = this.preppedReservation;
                reservation.dues = newDues;

                return this.doUpdate(reservation);
            },*/
            /*addNew(){
                // get trip object
                let trip = this.reservation.trip.data;

                // get only ids of current dues so we don't change anything
                trip.dues = [];
                _.each(this.reservation.trip.data.dues.data, (dl) => {
                    trip.dues.push({id: dl.id});
                });
                trip.dues.push(this.newDeadline);
                // remove tranformer modified values
                delete trip.difficulty;
                delete trip.rep_id;

                // this.$refs.spinner.show();
                this.$http.put('trips/' + trip.id, trip).then((response) => {
                    let thisTrip = response.data.data;
                    this.selecteddues = new Array(this.newDeadline);
                    // this.$refs.spinner.hide();
                    return this.adddues();

                });
            },*/

            /*getDues(search, loading){
                loading(true);

            },*/
            /*doUpdate(reservation){
                // this.$refs.spinner.show();
                return this.resource.put(reservation).then((response) => {
                    this.setReservationData(response.data.data);
                    this.selectedDues = [];
                    // this.$refs.spinner.hide();
                });
            },*/
            /*setReservationData(reservation){
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
                this.availableCosts = _.filter(reservation.trip.data.costs.data, (cost) => {
                    return !_.findWhere(reservation.costs.data, {cost_id: cost.id, type: 'incremental' || 'optional'})
                });

                // Extend dues data

            }*/
            getDues(){
                this.resource.get({ page: this.pagination.current_page }).then((response) => {
                    this.dues = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        mounted(){
            // this.$refs.spinner.show();
            this.getDues();

            //Listen to Event Bus
            this.$root.$on('AdminReservation:CostsUpdated', function (data) {
                this.getDues();
            });
        }
    }
</script>