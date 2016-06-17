<template>
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
    
        <div class="col-sm-6 col-md-4" v-for="campaign in campaigns" style="display:flex">
            <div class="panel panel-default">
                <img v-bind:src="campaign.thumb_src" v-bind:alt="campaign.name" class="img-responsive">
                <div class="panel-body">
                    <h4>{{campaign.name}}</h4>
                    <h6>{{campaign.country}}</h6>
                    <p>{{campaign.description}}</p>
                </div><!-- end panel-body -->
                <div class="panel-footer">
                    <p>
                        <a v-bind:href="'/campaigns/' + campaign.page_url" class="btn btn-primary btn-block" role="button">Details</a>
                    </p>
                </div>
            </div><!-- end panel -->
        </div><!-- end col -->
    </div>

</template>

<script>
    export default{
		name: 'campaigns',
        data(){
            return{
                campaigns:[]
            }
        },
        ready(){
            var resource = this.$resource('campaigns?published=true');

            resource.query().then(function(campaigns){
                this.campaigns = campaigns.data.data
            })
        }
    }
</script>
