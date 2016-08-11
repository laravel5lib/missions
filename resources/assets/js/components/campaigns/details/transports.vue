<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Campaign Transports</h4>
        </div>
        <div class="panel-body">
            ...
        </div>
        <table class="table table-hover">
            <tbody>
            <tr v-for="transport in transports">
                <th>{{transport.name}}</th>
                <td>{{transport.type|capitalize}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        name: 'transports',
        data(){
            return{
                transports:[]
            }
        },
        activate(done){
            // get transport data
            var resource = this.$resource('transports', {'campaign_id': this.$parent.campaignId});
            resource.get().then(function(response) {
                this.transports = response.data.data;
            });
            done();
        }
    }
</script>
