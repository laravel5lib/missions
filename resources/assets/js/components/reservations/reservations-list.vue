<template>
<div>
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="reservation in reservations" v-if="reservations.length > 0">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h5>{{ reservation.trip.data.campaign.data.name }} <small>{{ reservation.country }}</small></h5>
            </div>
            <div class="panel-body text-center">
                <img src="http://lorempixel.com/300/300/" class="img-circle img-lg">
                <hr class="divider inv sm">
                <h6 class="label label-default text-uppercase">{{ reservation.trip.data.type }} Missionary</h6>
                <h4>{{ reservation.surname }}, {{ reservation.given_names }}</h4>
                <h5 class="text-capitalize">{{ reservation.trip.data.group.data.name }}</h5>
                <a class="btn btn-sm btn-primary" href="/dashboard/reservations/{{ reservation.id }}">View Reservation</a>
            </div>
        </div>
    </div>
    <div class="alert alert-info" v-if="reservations.length < 1">No reservations found</div>
</div>
</template>
<script>
    export default{
        name: 'reservations-list',
        props: ['userId', 'type'],
        data(){
            return{
                reservations: []
            }
        },
        methods: {
            country(code){
                return code;
            },
            getReservations(){
                this.$http.get('reservations', {
                    include: 'trip.campaign,trip.group',
                    user: new Array(this.userId)
                }).then(function (response) {

                    switch (this.type) {
                        case 'active':
                            this.reservations = _.filter(response.data.data, function (reservation) {
                                return moment().isBefore(reservation.trip.data.ended_at);
                            });
                            break;
                        case 'archive':
                            this.reservations = _.filter(response.data.data, function (reservation) {
                                return moment().isAfter(reservation.trip.data.ended_at);
                            });
                            break;
                    }
                })
            },
        },
        ready(){
            this.getReservations();
        }
    }
</script>
