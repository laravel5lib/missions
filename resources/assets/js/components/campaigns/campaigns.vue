<template>
    <div class="container">
        <div class="col-sm-6 col-md-4" v-for="campaign in campaigns">
            <div class="thumbnail">
                <img src="http://lorempixel.com/242/200/" v-bind:alt="campaign.name">
                <div class="caption">
                    <h3>{{campaign.name}}</h3>
                    <h6>{{campaign.countries}}</h6>
                    <p>{{campaign.description}}</p>
                    <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
            </div>
        </div>
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
            var resource = this.$resource('campaigns');

            resource.query().then(function(campaigns){
                this.campaigns = campaigns.data.data
            })
        }
    }
</script>
