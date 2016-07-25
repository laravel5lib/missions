<template>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <!--<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>-->
            <li data-target="#carousel-example-generic" class="{{ $index == 0 ? 'active' : '' }}" :data-slide-to="$index" v-for="campaign in campaigns"></li>
          </ol>
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item {{ $index == 0 ? 'active' : '' }}" v-for="campaign in campaigns">
              <img :src="campaign.thumb_src">
              <div class="carousel-caption">
                <h6 class="text-uppercase">{{campaign.country}}</h6>
                <h3>{{campaign.name}}</h3>
                <p>{{campaign.description}}</p>
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
        <div class="col-xs-6">
            <h4>Recent Campaigns</h4>
        </div>
        <div class="col-xs-6 text-right">
            <a href="#" class="btn btn-primary btn-sm">See All</a>
        </div>
    </div>
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <div class="col-sm-6 col-md-4" v-for="campaign in campaigns" style="display:flex">
                <div class="panel panel-default">
                    <a :href="'/campaigns/' + campaign.page_url" role="button">
                        <img :src="campaign.thumb_src" :alt="campaign.name" class="img-responsive">
                    </a>
                        <div style="min-height:220px;" class="panel-body">
                            <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{campaign.country}}</h6>
                            <a :href="'/campaigns/' + campaign.page_url" role="button">
                                <h5 style="text-transform:capitalize;" class="text-primary">{{campaign.name}}</h5>
                            </a>
                            <hr class="divider lg" />
                            <p class="small">{{campaign.description}}</p>
                        </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
    </div>
    <hr class="divider inv xlg">
    <div class="white-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h2>Why Missions.Me?</h2>
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
            var resource = this.$resource('campaigns?published=true');

            resource.query().then(function(campaigns){
                this.campaigns = campaigns.data.data
            }).then(function () {


            });
        }
    }
</script>
