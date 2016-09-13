<template>
    <div class="content-page-header">
      <img class="img-responsive" src="images/groups/groups-header.jpg" alt="">
      <div class="c-page-header-text">
        <h1 class="text-uppercase dash-trailing">Groups</h1>
      </div><!-- end c-page-header-content -->
    </div><!-- end c-page-header -->
    <div class="white-bg">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
            <h2 class="text-primary">Group trips is what we do!</h2>
            <p>Missions.Me specializes in taking groups around the world on life-changing missions experiences.  If you are interested in partnering with one of our missions campaigns or trips, please fill out the form.  Missions.Me can provide your group with its own profile, URL and custom missions trips created especially for your group.</p>
            <hr class="divider inv">
            <a href="#" class="btn btn-primary btn-lg">Take Your Group</a>
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end white-bg -->
    <hr class="divider inv xlg">
    <div class="container">
        <div class="col-xs-6">
            <h4>Groups Partnered With Us</h4>
        </div>
        <div class="col-xs-6 text-right">
            <a v-if="groups.length > 3" @click="seeAll" class="btn btn-primary btn-sm">See All</a>
        </div>
    </div>
    <div class="container">
        <div class="col-xs-6">
            <div class="form-group form-group-sm">
                <input type="text" class="form-control" placeholder="Search for..." v-model="search" debounce="250">
                <!--<span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                </span>-->
            </div><!-- /input-group -->
        </div>
        <div class="col-xs-6 text-right">
            <!--<a v-if="fundraisers.length > 6 && fundraisersLimit == 6" @click="seeAll" class="btn btn-primary btn-sm">See All</a>-->
        </div>
    </div>
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
        <div class="col-sm-6 col-md-3" v-for="group in groups|limitBy groupsLimit" v-if="groups.length" style="display:flex">
            <div class="panel panel-default">
                <a :href="'/groups/' + group.url" role="button">
                    <img :src="group.avatar" :alt="group.name" class="img-responsive">
                </a>
                    <div style="min-height:220px;" class="panel-body">
                        <!--<h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;"><i class="fa fa-users"></i> {{group.type}} Group</h6>-->
                        <a :href="'/groups/' + group.url" role="button">
                            <h5 style="text-transform:capitalize;" class="text-primary">{{group.name}}</h5>
                        </a>
                        <h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;">{{group.type}} Group</h6>
                        <hr class="divider lg" />
                        <p class="small">{{group.description}}</p>
                    </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
        <div class="col-sm-12 text-center" v-if="groups.length">
            <nav>
                <ul class="pagination pagination-sm">
                    <li :class="{ 'disabled': pagination.current_page == 1 }">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="dark-bg-primary">
      <div class="container">
      <div class="content-section">
        <div class="row">
          <h1 class="dash-trailing-light">Group Leader Or Youth Pastor</h1>
          <div class="col-sm-6">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h6 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Is this going to be a logistical nightmare?
                    </a>
                  </h6>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                    <p>Missions.Me specializes in bringing youth groups, college groups, business groups, or any other types of groups you've created. We take care of all of your transportation, hotel, food and ministry schedule. As a leader, fund-raising and team building is all you'll need to focus on.</p>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      International travel with teens?!
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                    <p>Don't freak out.</p>
                    <p>Missions.Me will provide you with the flight itineraries, prepay baggage fees and be there waiting with pizza in hand when your group arrives. We dare you to find an easier missions experience.</p>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Will my team be eating bugs and sleeping in the jungle?
                    </a>
                  </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                  <div class="panel-body">
                    <p>It is the vision of Missions.Me to always give our best to the people we're ministering to. To achieve that, our missionaries need to be well rested and well fed. The conditions will vary from state to state, but keep in mind that the farther your team travels from a major city, the less available "higher standard" accommodations become.</p>

                    <p>HOTEL: Every hotel we stay in is inspected by a staff member prior to the trip. We guarantee that your hotel will be equipped with air conditioning and high speed internet.</p>

                    <p>FOOD: All restaurants are tested and approved by a staff member. You will eat at widely-known American restaurants (i.e. McDonald's, Wendy's, Pizza Hut) whenever possible. You will also be given the opportunity to partake in the local cuisine.</p>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFour">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      How will my leadership fit with Missions.Me?
                    </a>
                  </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                  <div class="panel-body">
                    <p>If you've ever been on a trip before, you know nothing gets done without organization and good leadership.From scheduling ministry sites, pick-ups, drop-offs, meals, translators and communicating with in-country contacts, our leaders handle everything and relay to you and your leadership the necessary information.</p>
                    <a href="#" class="btn btn-primary btn-sm">Sample Weekly Schedule</a>
                    <hr class="divider inv">
                    <p>Our leaders or "project directors" number one job is to take care of you (the team leader) so that you can properly take care of your group (team). Most importantly, we strive to create a culture of leadership on our trips that creates new leaders and takes your student leaders to the next level.</p>
                    <a href="#" class="btn btn-primary btn-sm">Sample Leadership Flow Chart</a>
                  </div>
                </div>
              </div><!-- end panel -->
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFive">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                      How will I recruit?
                    </a>
                  </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                  <div class="panel-body">
                    <p>Missions.Me specializes in not only bringing a message of hope to nations around the world but also a missional message to the American church. Every year, Missions.Me holds several Missions Nights in churches and generational services around the U.S. The service is packed with video, a powerful message from one of our Missions.Me Project Directors and a call to the mission field. The Missions.Me team comes prepared with applications, training instructions and fundraising ideas to make your church groups trip preparation process so easy. That night following the powerful missions service, those who responded will meet and together, we will launch your group with everything they will need to change the world.</p>
                  </div>
                </div>
              </div><!-- end panel -->
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingSix">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                      How will I train my group?
                    </a>
                  </h4>
                </div>
                <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                  <div class="panel-body">
                    <p>Missions.Me gives you everything you need to hold successful training meetings. You set your own dates and times for training, we do the rest.</p>
                  </div>
                </div>
              </div><!-- end panel -->
            </div>
          </div>
          <div class="col-sm-6">
            <div class="video-outer">
              <div class="video-inner">
                <iframe src="https://player.vimeo.com/video/109034302" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
</template>

<script>
    export default{
		name: 'groups',
        data(){
            return{
                groups:[],
                groupsLimit: 3,
                resource: this.$resource('groups?isPublic=yes'),

                // pagination vars
                search: '',
                page: 1,
                per_page: 6,
                pagination: {},
            }
        },
        watch: {
            'search': function (val) {
                this.searchGroups();
            }
        },
        methods:{
            seeAll(){
                this.groupsLimit = this.groups.length
            },
            searchGroups(){
                this.resource.query({}, {
                    search: this.search,
                    page: this.page,
                    per_page: this.per_page
                }).then(function(groups){
                    this.groups = groups.data.data;
                    this.pagination = groups.data.meta.pagination;
                }).then(function () {

                });
            }
        },
        ready(){
            this.searchGroups();
        }
    }
</script>
