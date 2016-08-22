<template>
<div>
    <p v-if="trips.length < 1" class="text-center text-muted lead">
        This group does not have any trips yet. Please check back soon!
    </p>
    <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="trip in trips">
        <div class="panel panel-default">
            <img :src="trip.campaign.data.avatar" alt="{{ trip.campaign.data.name }}" class="img-responsive">
            <div class="panel-body">
                <h6><span class="label label-default">{{ trip.campaign.data.name }}</span></h6>
                <h4>{{ trip.country_name }} {{ trip.started_at|moment 'YYYY' }}</h4>
                <h6>{{ trip.type|capitalize }} Trip</h6>
                <h6>{{ trip.started_at|moment 'MMMM DD' }} - {{ trip.ended_at|moment 'LL' }}</h6>
                <ul class="list-inline">
                    <li data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> {{ trip.reservations }}</li>
                    <li data-toggle="tooltip" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                </ul>
                <p><a class="btn btn-primary btn-lg btn-block" :href="'/trips/' + trip.id">Details</a></p>
            </div><!-- end panel-body -->
        </div><!-- end panel -->
    </div><!-- end col -->
</div>
</template>
<script>
    export default{
        name: 'group-profile-trips',
        props: ['id'],
        data(){
            return{
                trips:[],
                resource: this.$resource('trips?include=campaign&onlyPublished=true&groups[]=' + this.id)
            }
        },
        ready(){
            this.resource.query().then(function(trips){
                this.trips = trips.data.data
            }).then(function () {


            });
        }
    }
</script>