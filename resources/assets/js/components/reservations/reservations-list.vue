<template>
    <div class="panel panel-default" v-for="reservation in reservations">
        <div class="panel-heading">
            <h2 class="panel-title">
                {{ reservation.trip.campaign.name }}
                <small>{{ reservation.country }}</small>
            </h2>
        </div>
        <div class="panel-body">

            <h5>
                {{ reservation.surname }}, {{ reservation.given_names }}
                <small>{{ reservation.trip.group.name }}</small>
            </h5>
            <p>{{ reservation.trip.type }} Missionary</p>
            <a class="btn btn-sm btn-primary" href="/dashboard/reservations/{{ reservation.id }}">View Reservation</a>
            <br>
        </div>
    </div>
</template>
<script>
    export default{
        name: 'reservations-list',
        props: {
            reservations: {
                default: [],
                coerce: function (val) {
                    return JSON.parse(val);
                }
            }
        },
        data(){
            return{

            }
        },
        methods: {
            country(code){
                return code;
            },
            getReservations(){
                this.$http.get('reservations', {
                    include: 'trip.campaign,trip.group'
//                    , user: array(Auth::user()->id)
                }).then(function (response) {
                    this.reservations = response.data.data
                })
            },
        },
        ready(){
            //this.getReservations();
        }
    }
</script>
