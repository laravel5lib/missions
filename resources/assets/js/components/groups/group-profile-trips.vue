<template>
<div>
    <fetch-json url="/trips" :parameters="{
        'filter': {
            'group_id': id,
            'current': true,
            'public': true,
            'published': true
        }
    }" v-cloak>
        <div slot-scope="{ json: trips, loading, pagination, changePage }">
            
        </div>
    </fetch-json>


    <spinner ref="spinner" global size="sm" text="Loading"></spinner>
    <div class="row">
        <p v-if="trips.length < 1" class="text-center text-muted">
            This group does not have any trips yet. Please check back soon!
        </p>
        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="trip in trips" :key="trip.id" style="min-height: 500px;">
            <div class="panel panel-default">
                <div class="panel-heading" :class="'panel-' + trip.type">
                    <h5 class="text-uppercase text-center">{{ trip.type|capitalize }}</h5>
                </div>
                <div class="panel-body text-center">
                    <p class="badge">{{ trip.status|capitalize }}</p><br>
                    <!-- <h4>{{ trip.campaign.data.name }}</h4> -->
                    <p class="small">{{ trip.country_name }}</p>
                    <label>Travel Dates</label>
                    <p class="small">{{ trip.started_at|moment('MMMM DD', false, true) }} - {{ trip.ended_at|moment('LL', false, true) }}</p>
                    <label>Perfect For</label>
                    <p class="small"><span v-for="(prospect, index) in limitedProspects(trip.prospects, 3)">
                                {{ prospect|capitalize }}<span v-show="index + 1 != trip.prospects.length">, </span>
                    </span><span v-show="trip.prospects.length > 3">...</span></p>
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
<script type="text/javascript">
    import $ from 'jquery';
    export default{
        name: 'group-profile-trips',
        props: ['id'],
        data(){
            return{
                trips:[],
                resource: this.$resource('trips?status=current&onlyPublished=true&onlyPublic=true&groups[]=' + this.id)
            }
        },
        methods: {
            limitedProspects(propects, limit) {
                return propects.slice(0, limit);
            }
        },
        mounted(){
            this.resource.query().then((trips) =>{
                this.trips = trips.data.data
            }, this.$root.handleApiError).then(() => {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }
    }
</script>