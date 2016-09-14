<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <button class="btn btn-primary btn-xs" @click="add"><span
                class="fa fa-plus"></span> Add Existing
        </button>
        <button class="btn btn-primary btn-xs" @click="new"><span
                class="fa fa-plus"></span> Create New
        </button>

        <hr class="divider sm">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody v-if="reservation">
            <tr v-for="cost in reservation.costs.data">
                <td>
                    <i class="fa {{ isPast(cost.due_at) ? 'fa-times text-danger' : 'fa-exclamation text-warning' }}"></i>&nbsp;
                    {{ cost.name ? cost.name : !cost.cost_name ? cost.cost_name : cost.item  + ' Submission' }}
                </td>
                <td>{{ cost.due_at| moment 'll' }}</td>
                <td>{{ cost.amount| currency }}</td>
                <td>
                    <a class="btn btn-default btn-xs" @click="edit(cost)"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-danger btn-xs" @click="remove(cost)"><i class="fa fa-times"></i></a>
                </td>
            </tr>
            </tbody>
        </table>

        <modal title="Add Costs" :show.sync="showAddModal" effect="fade" width="800" :callback="addCosts">
            <div slot="modal-body" class="modal-body">
                <validator name="AddCost">
                    <form class="form-horizontal" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('costs') }"><label
                                class="col-sm-2 control-label">Available Costs</label>
                            <div class="col-sm-10">
                                <v-select class="form-control" id="user" multiple :value.sync="selectedCosts" :options="availableCosts"
                                          label="name"></v-select>
                                <select hidden="" v-model="user_id" v-validate:costs="{ required: true }" multiple>
                                    <option :value="cost.id" v-for="cost in costs">{{cost.name}}</option>
                                </select></div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal title="Edit Cost" :show.sync="showEditModal" effect="fade" width="800" :callback="update(editedCost)">
            <div slot="modal-body" class="modal-body">
                <validator name="EditCost">
                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditCostError('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForEditCostError('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="editedCost.grace_period"
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

        <modal title="New Cost" :show.sync="showNewModal" effect="fade" width="800" :callback="update(editedCost)">
            <div slot="modal-body" class="modal-body">
                <validator name="NewCost">
                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForNewCostError('name')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="newCost.name" v-validate:name="{require:true}" class="form-control input-sm">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForNewCostError('grace') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': checkForNewCostError('grace') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="newCost.grace_period"
                                                       v-validate:grace="{required: true, min:0}">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForNewCostError('due')}">
                                            <label for="due_at">Due</label>
                                            <input type="date" id="due_at" class="form-control input-sm"
                                                   v-model="newCost.due_at" v-validate:due="{required: true}">
                                        </div>

                                    </div>
                                </div>

                                <br>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="newCost.enforced">
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
        name: 'admin-reservation-costs',
        props: ['id'],
        components:{ vSelect, 'modal': VueStrap.modal},
        data(){
            return{
                reservation: null,
                reservationsCosts:[],
                costs:[],
                selectedCosts: [],
                availableCosts: [],
                editedCost: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                newCost: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                resource: this.$resource('reservations/' + this.id, { include: 'costs,trip.costs' }),
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
                return this.$AddCost[field].invalid && this.attemptSubmit;
            },
            checkForEditCostError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditCost[field].invalid && this.attemptSubmit;
            },
            checkForNewCostError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$NewCost[field].invalid && this.attemptSubmit;
            },
            resetCost(){
                this.newCost = {
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
            edit(cost){
                this.editedCost = cost;
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            update(cost){
//                this.resource.delete()
            },
            remove(cost){
                var reservation = this.preppedReservation;
                reservation.costs = [];
                _.each(this.reservation.costs.data, function (cs) {
                    if (cs.cost_id !== cost.cost_id) {
                        reservation.costs.push({ id: cs.cost_id/*, locked: cs.locked*/})
                    }
                });
                console.log(reservation.costs);

                return this.doUpdate(reservation);
            },
            addCosts(){
                // prep current costs
                var currentCostIds = [];
                _.each(this.costs, function (cost) {
                    currentCostIds.push({ id: cost.id })
                });

                // prep added costs
                var selectedCostIds = [];
                _.each(this.selectedCosts, function (cost) {
                    selectedCostIds.push({ id: cost.id })
                });

                // merge arrays
                var newCosts = _.union(currentCostIds, selectedCostIds);
                // filter possible duplicates
                newCosts = _.uniq(newCosts);

                var reservation = this.preppedReservation;
                reservation.costs = newCosts;

                return this.doUpdate(reservation);
            },
            getCosts(search, loading){
                loading(true);

            },
            doUpdate(reservation){
                return this.resource.update(reservation).then(function (response) {
                    this.setReservationData(response.data.data);
                    this.selectedCosts = [];
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
                    return !_.findWhere(reservation.costs.data, {cost_id: cost.id})
                });
                console.log(this.availableCosts);
            }
        },
        ready(){
            this.resource.get().then(function (response) {
                this.setReservationData(response.data.data)
            });

        }
    }
</script>