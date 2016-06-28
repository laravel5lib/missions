<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Campaign Trips</h4>
        </div>
        <div class="panel-body">
            ...
        </div>
        <table class="table table-hover">
            <tbody>
            <tr v-for="trip in trips">
                <td>{{trip.type|capitalize}} Trip</td>
                <td>{{trip.reservations}} reservations</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        name: 'trips',
        data(){
            return{
                trips: [],
            }
        },
        computed:{
            trips(){
                return this.$parent.campaign.trips.data;
            }
        },
        activate(done){
            done();
        }
    }
</script>
