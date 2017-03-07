<template>
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="row">
            <p v-if="trips.length < 1" class="text-center text-muted lead">
                This group does not have any trips yet. Please check back soon!
            </p>
            <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="trip in trips">
                <div class="panel panel-default">
                    <div class="panel-heading" :class="'panel-' + trip.type">
                        <h5 class="text-uppercase text-center">{{ trip.type|capitalize }} Trip</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body text-center">
                        <p class="badge">{{ trip.status | capitalize }}</p><br>
                        <img :src="trip.campaign.data.avatar" alt="{{ trip.campaign.data.name }}" class="img-circle img-md">
                        <h4>{{ trip.campaign.data.name }}</h4>
                        <p class="small">{{ trip.country_name }} {{ trip.started_at|moment 'YYYY' }}</p>
                        <label>Travel Date</label>
                        <p>{{ trip.started_at|moment 'MMMM DD' }} - {{ trip.ended_at|moment 'LL' }}</p>
                        <p class="text-left" data-toggle="tooltip" data-placement="top" title="Reservations"><i class="fa fa-user"></i> {{ trip.reservations }}</p>
                        <p><a class="btn btn-primary btn-block" :href="id + trip.links[0].uri">Details</a></p>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
            <div v-if="trips.length" class="col-sm-12 text-center">
                <pagination :pagination.sync="pagination" :callback="searchTrips"></pagination>
            </div>
        </div><!-- end row -->
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'dashboard-group-trips',
        props: ['id'],
        data(){
            return {
                trips:[],
                resource: this.$resource('trips?include=campaign&status=current&onlyPublished=true&groups[]=' + this.id),
                pagination: { current_page: 1 },
                per_page: 3,
            }
        },
        methods:{
            searchTrips(){
                // this.$refs.spinner.show();
                this.resource.query().then(function(response){
                    this.trips = response.body.data;
                    this.pagination = response.body.meta.pagination;
                    // this.$refs.spinner.hide();
                }, function (error) {
                    // this.$refs.spinner.hide();
                    //TODO add error alert
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