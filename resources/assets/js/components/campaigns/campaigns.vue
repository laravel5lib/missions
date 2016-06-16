<template>
    <div class="container">
        <div class="col-sm-6 col-md-4" v-for="campaign in campaigns">
            <div class="thumbnail">
                <img v-bind:src="'http://lorempixel.com/242/200/city/' + $index" v-bind:alt="campaign.name">
                <div class="caption">
                    <h4>{{campaign.name}}</h4>
                    <h6>{{campaign.countries}}</h6>
                    <p>{{campaign.description}}</p>
                    <p>
						<a v-bind:href="campaign.links[0].uri" class="btn btn-primary btn-block" role="button">Details</a>
					</p>
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
