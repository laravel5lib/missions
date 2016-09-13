<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <button class="btn btn-primary btn-xs" @click="showAddModal=!showAddModal"><span
                class="fa fa-plus"></span> New
        </button>

        <hr class="divider inv sm">
        <ul class="list-group">
            <li class="list-group-item" v-for="deadline in reservation.deadlines.data">
                <h4 class="list-group-item-heading">
                    <i class="fa {{ isPast(deadline.due_at) ? 'fa-times text-danger' : 'fa-exclamation text-warning' }}"></i>&nbsp;
                    {{ deadline.name ? deadline.name : !deadline.cost_name ? deadline.cost_name : deadline.item  + ' Submission' }}
                    <small class="text-muted"><i class="fa fa-clock-o"></i> {{ deadline.due_at| moment 'll' }}</small>
                    <a class="pull-right btn btn-xs" @click="remove(deadline)"><i class="fa fa-times"></i></a>
                </h4>
            </li>
        </ul>

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

                resource: this.$resource('reservations/' + this.id, { include: 'deadlines,trip.deadlines' }),
                showAddModal: false,
                attemptSubmit: false
            }
        },
        methods: {
            checkForError: function checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddManager[field].invalid && this.attemptSubmit;
            },

            isPast(date){
                return moment().isAfter(date);
            },
            remove(deadline){
//                this.resource.delete()
            },
            addDeadlines(){
                var currentDeadlineIds = _.isArray(this.deadlines) ? _.pluck(this.deadlines, 'id') : [];
                var selectedDeadlineIds = _.pluck(this.selectedDeadlines, 'id') || [];
                var newDeadlines = _.union(currentDeadlineIds, selectedDeadlineIds);
                newDeadlines = _.uniq(newDeadlines);

                var reservation = {
                    given_names: this.reservation.given_names,
                    surname: this.reservation.surname,
                    gender: this.reservation.gender,
                    status: this.reservation.status,
                    shirt_size: this.reservation.shirt_size,
                    birthday: this.reservation.birthday,
                    user_id: this.reservation.user_id,
                    trip_id: this.reservation.trip_id,
                    deadlines: newDeadlines,
                };
                this.resource.update(reservation).then(function (response) {
                    console.log(response);
                    this.selectedDeadlines = [];
                    // get current deadlines
                    this.deadlines = response.data.data.deadlines.data;

                })
            },
            getDeadlines(search, loading){
                loading(true);

            }
        },
        ready(){
            this.resource.get().then(function (response) {
                this.reservation = response.data.data;

                // get current deadlines
                this.deadlines = response.data.data.deadlines.data;

                // get available deadlines
                this.availableDeadlines = response.data.data.trip.data.deadlines.data;
            });

        }
    }
</script>
