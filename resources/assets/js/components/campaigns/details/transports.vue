<template>
    <div class="panel panel-default">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

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
<script type="text/javascript">
    export default{
        name: 'transports',
        data(){
            return{
                transports:[]
            }
        },
        activate(done){
            // get transport data
            // this.$refs.spinner.show();
            let resource = this.$resource('transports', {'campaign_id': this.$parent.campaignId});
            resource.get().then(function(response) {
                this.transports = response.body.data;
                // this.$refs.spinner.hide();
            });
            done();
        }
    }
</script>
