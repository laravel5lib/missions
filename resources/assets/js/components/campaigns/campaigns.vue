<template>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
          </ol>
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item active">
              <img src="http://placehold.it/1500x600">
              <div class="carousel-caption">
                <h6 class="uppercase">Nicaragua</h6>
                <h3>1Nation1Day 2017</h3>
                <p>This is going to be an epic trip!</p>
                <p><a href="#" class="btn btn-primary">View Details</a></p>
              </div>
            </div>
            <div class="item">
              <img src="http://placehold.it/1500x600">
              <div class="carousel-caption">
                <h6 class="uppercase">Peru</h6>
                <h3>1Nation1Day 2019</h3>
                <p>This is going to be an epic trip!</p>
                <p><a href="#" class="btn btn-primary">View Details</a></p>
              </div>
            </div>
          </div>
          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="fa fa-angle-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
    </div><!-- end carousel -->
    <hr class="divider inv xlg">
    <div class="container">
        <div class="col-md-6 col-sm-12">
            <h4>Recent Campaigns</h4>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="#" class="btn btn-primary btn-sm">See All</a>
        </div>
    </div>
    <!-- <div id="code-carousel" class="carousel slide hidden-xs" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active">
                <div class="row">
                    
                </div>
            </div>
        </div>
    </div> -->
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <div class="col-sm-6 col-md-4" v-for="campaign in campaigns" style="display:flex">
                <div class="panel panel-default">
                    <img :src="campaign.thumb_src" :alt="campaign.name" class="img-responsive">
                        <div style="min-height:260px;" class="panel-body">
                            <h4>{{campaign.name}}</h4>
                            <h6>{{campaign.country}}</h6>
                            <p>{{campaign.description}}</p>
                        </div><!-- end panel-body -->
                        <div class="panel-footer">
                            <p><a :href="'/campaigns/' + campaign.page_url" class="btn btn-primary btn-block" role="button">Details</a> </p>
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
