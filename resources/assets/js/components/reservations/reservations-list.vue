<template>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="reservation in reservations" v-if="reservations.length > 0">
        <div class="panel panel-default">
            <div class="panel-heading panel-ministry text-center">
                <h5 class="text-capitalize">{{ reservation.trip.data.type }} Missionary</h5>
            </div>
            <div class="panel-body text-center">
                <img :src="reservation.avatar" class="img-circle img-md">
                <hr class="divider inv sm">
                <h4>{{ reservation.surname }}, {{ reservation.given_names }}</h4>
                <h6 class="text-capitalize small">{{ reservation.trip.data.group.data.name }}</h6>
                <label style="margin-bottom:2px;font-size:10px;">Campaign</label>
                <h6 class="text-capitalize small" style="margin-top:2px;">{{ reservation.trip.data.campaign.data.name }}</h6>
                <label style="margin-bottom:2px;font-size:10px;">Country</label>
                <h6 class="text-capitalize small" style="margin-top:2px;">{{ reservation.country }}</h6>
                <hr class="divider inv sm">
                <a class="btn btn-sm btn-primary" href="/dashboard/reservations/{{ reservation.id }}">View Reservation</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="alert alert-info" v-if="reservations.length < 1">No reservations found</div>
    </div>
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
                    user: new Array(this.userId),
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
