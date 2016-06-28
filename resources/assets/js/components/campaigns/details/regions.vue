<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Campaign Regions</h4>
        </div>
        <div class="panel-body">
            ...
        </div>
        <table class="table table-hover">
            <tbody>
            <tr v-for="region in regions">
                <td>{{region.name|capitalize}}</td>
                <td>{{region.country_name}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        name: 'regions',
        data(){
            return{
                regions: []
            }
        },
        activate(done){
            // get transport data
            var resource = this.$resource('regions{/id}', {'campaign_id': this.$parent.campaignId});
            resource.get().then(function(response) {
                this.regions = response.data.data;
            });

            done();
        }
    }
</script>
