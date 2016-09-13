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
              <img :src="campaign.banner">
              <div class="carousel-caption">
                <h6 class="text-uppercase">{{campaign.country}}</h6>
                <h3>{{campaign.name}}</h3>
                <p>{{campaign.description}}</p>
                <a :href="'/campaigns/' + campaign.id" class="btn btn-primary btn-sm">More Details</a>
                <hr class="divider inv" />
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
            <h4>Current Campaigns</h4>
        </div>
        <div class="col-xs-6 text-right">
            <a v-if="campaigns.length > 3" @click="seeAll" class="btn btn-primary btn-sm">See All</a>
        </div>
    </div>
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <div class="col-sm-6 col-md-3" v-for="campaign in campaigns|limitBy campaignsLimit" style="display:flex">
                <div class="panel panel-default">
                    <a :href="'/campaigns/' + campaign.page_url" role="button">
                        <img :src="campaign.avatar" :alt="campaign.name" class="img-responsive">
                    </a>
                        <div style="min-height:220px;" class="panel-body">
                            <h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;"><i class="fa fa-map-marker"></i> {{campaign.country}}</h6>
                            <a :href="'/campaigns/' + campaign.page_url" role="button">
                                <h5 style="text-transform:capitalize;" class="text-primary">{{campaign.name}}</h5>
                            </a>
                            <h6 style="font-size:12px;">{{campaign.started_at | moment 'll'}} - {{campaign.ended_at | moment 'll'}}</h6>
                            <hr class="divider lg" />
                            <p style="font-size:12px;" class="small">{{campaign.description}}</p>
                        </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
    </div>
    <hr class="divider inv xlg">
    <div class="white-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
            <h2 class="text-primary">Missions.Me plans trips around you. We create experiences that will maximize your specific abilities and desires.</h2>
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end white-bg -->
    <div class="white-bg">
      <div class="row row-no-margin">
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage1.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage3.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage4.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage5.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage8.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage9.jpg" alt="">
        </div>
      </div>
    </div><!-- end white-bg -->
    <div class="white-bg">
      <div class="row row-no-margin">
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage10.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage12.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage13.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage14.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage15.jpg" alt="">
        </div>
        <div class="col-sm-2 col-xs-4 col-no-padding">
          <img class="img-responsive" src="images/why-mm/collage/collage18.jpg" alt="">
        </div>
      </div>
    </div><!-- end white-bg -->
    <div class="gray-lighter-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1">
            <div class="row">
              <div class="col-xs-10 col-xs-offset-1">
                <h6 class="text-uppercase text-center">A Trip For Everyone</h6>
                <h1 class="text-center">Choose A Role</h1>
                <hr class="divider red-small lg">
                <hr class="divider inv lg">
              </div><!-- end col -->
            </div><!-- end row -->
            <div class="row">
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="missionary.html"><img class="img-responsive" src="images/why-mm/missionary.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="missionary.html"><h4 class="text-primary">Missionary</h4></a>
                    <p class="small">Anyone age 13+ can be an M.M missionary. Set out on the journey with your friend or family to bring a message of love and hope along with an unforgettable cultural experience.</p>
                  </div>
                </div>
              </div><!-- end col -->
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="medical-missionary.html"><img class="img-responsive" src="images/why-mm/medical-missionary.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="medical-missionary.html"><h4>Medical Missionary</h4></a>
                    <p class="small">Treat, diagnose, prescribe, and share the love of Jesus. Anyone in a medical field can serve, including students currently enrolled in school. Physicians, Dentists, Nurses, and all assistants needed.</p>
                  </div>
                </div>
              </div><!-- end col -->
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="group-leader.html"><img class="img-responsive" src="images/why-mm/group-leader.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="group-leader.html"><h4>Group Leader</h4></a>
                    <p class="small">M.M specializes in organizing and creating turn-key group missions experiences for youth groups, college groups, and business groups. You bring passion; we take care of the rest.</p>
                  </div>
                </div>
              </div><!-- end col -->
            </div><!-- end row -->
            <div class="row">
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="pastor-speaker.html"><img class="img-responsive" src="images/why-mm/speaker.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="pastor-speaker.html"><h4>Pastor/Speaker</h4></a>
                    <p class="small">Share your gift of leadership with the team by leading a morning devo then impart spiritual wisdom into local church leadership. Let us create a custom schedule that makes the most of your valuable time.</p>
                  </div><!-- end panel-body -->
                </div><!-- end panel -->
              </div><!-- end col -->
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="business-leader.html"><img class="img-responsive" src="images/why-mm/business-person.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="business-leader.html"><h4>Business Leader</h4></a>
                    <p class="small">A shortened “Business Class” trip is available on select trips. You will experience conferences, stadium outreaches, street ministry, and connect with God in a whole new way. All trips will have you back on Sunday night, ready for Monday.</p>
                  </div>
                </div>
              </div><!-- end col -->
              <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <div class="panel">
                  <a href="media-missionary.html"><img class="img-responsive" src="images/why-mm/media-missionary.jpg" alt=""></a>
                  <div class="panel-body">
                    <a href="media-missionary.html"><h4>Media Missionary</h4></a>
                    <p class="small">Tell the story of life change! M.M needs you to film, shoot, interview, edit, export, and be creative! Experience a whole new world through the lens of a camera while capturing the most exciting moments of the trip.</p>
                  </div>
                </div>
              </div><!-- end col -->
            </div><!-- end row -->
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end gray-lighter-bg -->
    <div class="white-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <h6 class="text-uppercase text-primary">It's So Easy</h6>
            <h1 class="text-primary dash-trailing">We've Got You</h1>
            <p class="large-type">Logistics, we got it covered. Missions.Me makes missions simple by taking care of all of your transportation, hotel, food, training, translators and ministry schedule needs.</p>
            <hr class="divider inv">
            <a class="btn btn-primary" href="#">Speak to a rep</a>
          </div><!-- end col -->
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-responsive" src="images/why-mm/staff.jpg" />
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section-->
      </div><!-- end container -->
    </div><!-- end red-bg -->
    <div class="white-bg">
      <div class="row row-no-margin">
        <div class="col-sm-6 col-no-padding">
          <img class="img-responsive" src="images/why-mm/miami-lawn.jpg" alt="">
        </div>
        <div class="col-sm-6 col-no-padding">
          <img class="img-responsive" src="images/why-mm/miami-conf.jpg" alt="">
        </div>
      </div>
    </div><!-- end white-bg -->
    <div class="bg-primary">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <h1 class="text-primary-darker dash-trailing-dark">Level Of Impact</h1>
            <p class="large-type">We are interested in changing nations, communities, and individuals. Every outreach we organize deems to do just that. In just one week's time you and your team will be face to face with entire schools, neighborhoods, and churches. We believe a short-term team must serve a long-term and sustainable goal. That’s why when your team leaves our international partners continue to serve the communities you impacted.</p>
          </div><!-- end col -->
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-responsive" src="images/why-mm/stadium-impact.jpg" />
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
    <div class="gray-lighter-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-responsive" src="images/why-mm/group-security.jpg" />
          </div><!-- end col -->
          <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <h1 class="text-primary dash-trailing">Safety and Security</h1>
            <!-- <p>Missions.Me's first priority is safety. Our partners have successfully hosted thousands of American missionaries for over 25 years. Our leadership provides in depth training to create a safe experience for every missionary. Our projects are managed in cooperation with the local police and hired security so that each ministry context is safe. Multiple leaders care for each missionary and guidelines are faceted so that no person is ever alone. We also do background checks on all potential leaders. Missions.Me has a proven track record of safety and security that keeps our teams coming back.</p> -->
            <p>Missions.Me's first priority is safety. Our partners have successfully hosted thousands of American missionaries for over 25 years. Our projects are managed in cooperation with the local police and hired security so that each ministry context is safe.</p>
            <blockquote>"I have traveled with Missions.Me in many countries and have witnessed first-hand their organization and safety measures. I am completely confident sending my teens on a Missions.Me missions trip."
              <footer>Sue, Mother from Lancaster, PA</footer></blockquote>
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
    <div class="gray-light-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <h1 class="text-center">Trip Difficulty Ratings</h1>
            <hr class="divider red-small lg">
            <hr class="divider inv lg">
          </div><!-- end col -->
        </div><!-- end row -->
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-lg" src="images/why-mm/level1.png" alt="">
            <hr class="divider inv">
            <p>These trips are great for those just getting started in the world-changing business. First timers of all ages area welcome.</p>
          </div>
          <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-lg" src="images/why-mm/level2.png" alt="">
            <hr class="divider inv">
            <p>If Level 1 proved to be a piece of cake, you’re ready for a Level 2 adventure. A little tougher, a little sweatier, but much more sweeter.</p>
          </div>
          <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
            <img class="img-lg" src="images/why-mm/level3.png" alt="">
            <hr class="divider inv">
            <p>Chuck Norris. Mr. T. Annie Oakley. Yep, they’re all level 3 missionaries. It may not be physically tough, but it’ll be a challenge.</p>
          </div>
        </div>
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
</template>

<script>
    export default{
		name: 'campaigns',
        data(){
            return{
                campaigns:[],
                campaignsLimit: 3,
                resource: this.$resource('campaigns?published=true')
            }
        },
        methods:{
            seeAll(){
                this.campaignsLimit = this.campaigns.length
            }
        },
        ready(){
            this.resource.query().then(function(campaigns){
                this.campaigns = campaigns.data.data
            }).then(function () {

            });
        }
    }
</script>
