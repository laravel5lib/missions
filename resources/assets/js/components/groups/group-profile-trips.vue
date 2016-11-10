<template>
<div>
    <div class="row">
        <p v-if="trips.length < 1" class="text-center text-muted">
            This group does not have any trips yet. Please check back soon!
        </p>
        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="trip in trips">
            <div class="panel panel-default">
                <div class="panel-heading" :class="'panel-' + trip.type">
                    <h5 class="text-uppercase text-center">{{ trip.type | capitalize }}</h5>
                </div>
                <div class="panel-body text-center">
                    <p class="badge">{{ trip.status | capitalize }}</p><br>
                    <h4>{{ trip.campaign.data.name }}</h4>
                    <p class="small">{{ trip.country_name }} {{ trip.started_at|moment 'YYYY' }}</p>
                    <label>Travel Dates</label>
                    <p class="small">{{ trip.started_at|moment 'MMMM DD' }} - {{ trip.ended_at|moment 'LL' }}</p>
                    <label>Perfect For</label>
                    <p class="small"><span v-for="prospect in trip.prospects">
                        {{ prospect | capitalize }}<span v-show="$index + 1 != trip.prospects.length">, </span> 
                    </span></p>
                    <label>Spots Available</label>
                    <p>{{ trip.spots }}</p>
                    <p class="text-left" data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> {{ trip.reservations }}</p>
                    <p><a class="btn btn-primary btn-block" :href="'/trips/' + trip.id">Details</a></p>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
    </div>
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