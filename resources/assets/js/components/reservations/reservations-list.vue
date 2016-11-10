<template>
<div class="row">
    <div class="col-xs-12 text-right">
        <form class="form-inline">
            <div class="checkbox" v-if="isFacilitator">
                <label>
                    <input type="checkbox" v-model="includeManaging"> Include my group's reservations
                </label>
            </div>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
        </form>
        <hr class="divider sm inv">
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="reservation in reservations" v-if="reservations.length > 0">
        <div class="panel panel-default">
            <div class="panel-heading text-center" :class="'panel-' + reservation.trip.data.type">
                <h5 class="text-capitalize">{{ reservation.trip.data.type }}</h5>
            </div>
            <div class="panel-body text-center">
                <img :src="reservation.avatar" class="img-circle img-md">
                <hr class="divider inv sm">
                <h4>{{ reservation.surname }}, {{ reservation.given_names }}</h4>
                <h6 class="text-capitalize small">{{ reservation.trip.data.group.data.name }}</h6>
                <label style="margin-bottom:2px;">Campaign</label>
                <h6 class="text-capitalize small" style="margin-top:2px;">{{ reservation.trip.data.campaign.data.name }}</h6>
                <label style="margin-bottom:2px;font-size:10px;">Country</label>
                <h6 class="text-capitalize small" style="margin-top:2px;">{{ reservation.country_name }}</h6>
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
<script type="text/javascript">
    export default{
        name: 'reservations-list',
        props: ['userId', 'type'],
        data(){
            return {
                reservations: [],
                trips: [],
                includeManaging: false,
                search: '',
                isFacilitator: false,
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.getReservations();
            },
            'includeManaging': function (val, oldVal) {
                this.getReservations();
            }
        },
        methods: {
            country(code){
                return code;
            },
            getReservations(){
                var params = {
                    include: 'trip.campaign,trip.group',
                    search: this.search
                }
                if (this.includeManaging) {
                    params.trip = this.trips;
                } else {
                    params.user = new Array(this.userId);
                }

                this.$http.get('reservations', params).then(function (response) {

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
                });
            },
        },
        ready(){
            this.$http.get('users/' + this.userId + '?include=facilitating,managing.trips').then(function (response) {
                var user = response.data.data;
                var managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    var facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, function (group) {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }
            });

            this.getReservations();
        }
    }
</script>
