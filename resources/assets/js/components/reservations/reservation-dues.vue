<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments Due</h5>
        </div><!-- end panel-heading -->
        <div class="list-group" v-if="reservation">
            <div class="list-group-item tour-step-payment-details" v-for="due in reservation.dues.data" :class="{'list-group-item-default': due.unsaved}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Balance Due</label>
                        <p>{{ '$' + due.balance.toFixed(2)}}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-6">
                        <label>Applied to</label>
                        <p>{{ due.percent }}% of {{ due.cost }}<br />
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-3">
                        <label v-if="due.type === 'static'">Immediately</label>
                        <label v-else>Due {{ due.due_at | moment('ll', true) }}</label>
                        <p>
                            <span class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending' }">{{due.status ? due.status[0].toUpperCase() + due.status.slice(1) : ''}}</span>
                        </p>
                        <hr class="divider inv hidden-lg">
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end panel -->
</template>

<script type="text/javascript">
    // inmport vSelect from 'vue-select';
    export default{
        name: 'reservation-dues',
        props: ['id'],
        // components:{ vSelect },
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
            isPast(date){
                return moment().isAfter(date);
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

                // Extend dues data

            }
        },
        mounted(){
            /*this.resource.get().then((response) => {
                this.setReservationData(response.data.data)
            });*/

            //Listen to Event Bus
            this.$root.$on('Reservation:CostsUpdated', function (data) {
                this.setReservationData(data)
            }.bind(this));

            this.$root.$on('Reservation:CostsReverted', function (data) {
                this.setReservationData(data)
            }.bind(this));

        }
    }
</script>