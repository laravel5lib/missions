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
                        <li data-toggle="tooltip" data-placement="top" title="Reservations"><i class="fa fa-user"></i> {{ trip.reservations }}</li>
                        <li data-toggle="tooltip" data-placement="top" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                    </ul>
                    <p><a class="btn btn-primary btn-lg btn-block" :href="id + trip.links[0].uri">Details</a></p>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
        <div v-if="trips.length" class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchTrips"></pagination>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'dashboard-group-trips',
        props: ['id'],
        data(){
            return {
                trips:[],
                resource: this.$resource('trips?include=campaign&onlyPublished=true&groups[]=' + this.id),
                pagination: { current_page: 1 },
                per_page: 3,
            }
        },
        methods:{
            searchTrips(){
                this.resource.query().then(function(response){
                    this.trips = response.data.data;
                    this.pagination = response.data.meta.pagination;
                }).then(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
        },
        ready(){
            this.searchTrips();
        }
    }
</script>