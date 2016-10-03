<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
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
            <a class="btn btn-primary btn-lg" role="button" data-toggle="collapse" href="#collapseGroupForm" aria-expanded="false" aria-controls="collapseGroupForm">Take Your Group</a>
          </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
        <div class="row collapse" id="collapseGroupForm">
          <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <validator name="CreateGroup">
              <form id="CreateGroupForm" class="form-horizontal" novalidate>
                  <div class="form-group">
                      <div class="col-sm-6" :class="{ 'has-error': checkForError('name') || errors.name }">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" name="name" id="name" v-model="name"
                                 placeholder="Group Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                                 maxlength="100" minlength="1" required>
                          <p class="help-block" v-if="errors.name" v-text="errors.name"></p>
                      </div>
                      <div class="col-sm-6" :class="{ 'has-error': checkForError('campaign') || errors.campaign }">
                          <label for="campaign">Which Campaign are you interested in?</label>
                          <select name="type" id="campaign" class="form-control" v-model="campaign" v-validate:campaign="{ required: true }" required>
                              <option value="">-- please select --</option>
                              <option :value="campaign.id" v-for="campaign in campaigns">{{campaign.name}}</option>
                          </select>
                          <p class="help-block" v-if="errors.campaign" v-text="errors.campaign"></p>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-6">
                          <label for="infoAddress">Address 1</label>
                          <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
                      </div>
                      <div class="col-sm-6">
                          <label for="infoAddress2">Address 2</label>
                          <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
                      </div>
                  </div>

                  <div class="row form-group col-sm-offset-2">
                      <div class="col-sm-4">
                              <label for="infoCity">City</label>
                              <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                          <p class="help-block" v-if="errors.city" v-text="errors.city"></p>
                      </div>
                      <div class="col-sm-4">
                              <label for="infoState">State/Prov.</label>
                              <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                            <p class="help-block" v-if="errors.state" v-text="errors.state"></p>
                      </div>
                      <div class="col-sm-4">
                              <label for="infoZip">ZIP/Postal Code</label>
                              <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                          <p class="help-block" v-if="errors.zip" v-text="errors.zip"></p>
                      </div>
                  </div>

                  <div class="row form-group col-sm-offset-2">
                      <div class="col-sm-6">
                          <div :class="{ 'has-error': checkForError('country') }">
                              <label for="country">Country</label>
                              <v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                              <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }" >
                                  <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-sm-6" :class="{ 'has-error': checkForError('type') || errors.type }">
                          <label for="type">Type</label>
                          <select name="type" id="type" class="form-control" v-model="type" v-validate:type="{ required: true }" required>
                              <option value="">-- please select --</option>
                              <option :value="option" v-for="option in typeOptions">{{option|capitalize}}</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-sm-4" :class="{ 'has-error': checkForError('timezone') || errors.timezone }">
                          <label for="timezone">Timezone</label>
                          <v-select class="form-control" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                          <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate:timezone="{ required: true }">
                              <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                          </select>
                      </div>
                      <div class="col-sm-4" :class="{ 'has-error': checkForError('phone') || errors.phone_one }">
                          <label for="infoPhone">Phone 1</label>
                          <input type="text" class="form-control" v-model="phone_one | phone" v-validate:phone="{ require: true, minlength:9 }" id="infoPhone" placeholder="123-456-7890">
                          <p class="help-block" v-if="errors.phone_one" v-text="errors.phone_one"></p>
                      </div>
                      <div class="col-sm-4">
                          <label for="infoMobile">Phone 2</label>
                          <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-sm-4" :class="{ 'has-error': checkForError('contact') || errors.contact }">
                          <label for="contact">Your Name</label>
                          <input type="text" class="form-control" name="contact" id="contact" v-model="contact"
                                 placeholder="John Smith" v-validate:contact="{ required: true, minlength:1, maxlength:100 }"
                                 maxlength="100" minlength="1" required>
                          <p class="help-block" v-if="errors.contact" v-text="errors.contact"></p>
                      </div>
                      <div class="col-sm-4" :class="{ 'has-error': checkForError('email') || errors.email }">
                          <label for="email">Email</label>
                          <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate:email="['email','require']">
                          <p class="help-block" v-if="errors.email" v-text="errors.email"></p>
                      </div>
                      <div class="col-sm-4" :class="{ 'has-error': checkForError('position') || errors.position }">
                          <label for="position">Your Position</label>
                          <input type="text" class="form-control" name="position" id="position" v-model="position" v-validate:position="{ require: true, minlength:1 }">
                          <p class="help-block" v-if="errors.position" v-text="errors.position"></p>
                      </div>
                  </div>

                  <div class="form-group" :class="{ 'has-error': checkForError('spoken') || errors.spoke_to_rep }">
                      <label for="status" class="col-sm-8 control-label">Have you spoken with a Missions.Me representative?</label>
                      <div class="col-sm-4">
                          <label class="radio-inline">
                              <input type="radio" name="status" id="status" value="yes" v-model="spoke_to_rep" v-validate:spoken="{ require: { rule: true } }"> Yes
                          </label>
                          <label class="radio-inline">
                              <input type="radio" name="status2" id="status2" value="no" v-model="spoke_to_rep" v-validate:spoken=""> No
                          </label>
                          <!--<p class="help-block" v-if="errors.spoke_to_rep" v-text="errors.spoke_to_rep"></p>-->
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-12 text-center">
                          <a @click="submit" class="btn btn-primary">Send Request</a>
                      </div>
                  </div>
              </form>
          </validator>
          </div><!-- end col -->
        </div><!-- end row -->
      <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
          <span class="icon-ok-circled alert-icon-float-left"></span>
          <strong>Awesome!</strong>
          <p>Group request sent</p>
      </alert>

      </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end white-bg -->
    <hr class="divider inv xlg">
    <div class="container">
        <div class="col-xs-12">
            <h4>Groups Partnered With Us</h4>
        </div>
    </div>
    <div class="container">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group form-group-md">
                <input type="text" class="form-control" placeholder="Start typing a group name or category..." v-model="search" debounce="250">
            </div><!-- /input-group -->
        </div>
    </div>
    <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
        <div class="col-sm-6 col-md-3" v-for="group in groups|limitBy groupsLimit" v-if="groups.length" style="display:flex">
            <div class="panel panel-default">
                <a :href="'/groups/' + group.url" role="button">
                    <img :src="group.avatar" :alt="group.name" class="img-responsive">
                </a>
                    <div style="min-height:120px;" class="panel-body">
                        <!--<h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;"><i class="fa fa-users"></i> {{group.type}} Group</h6>-->
                        <a :href="'/groups/' + group.url" role="button">
                            <h5 style="text-transform:capitalize;" class="text-primary">{{group.name}}</h5>
                        </a>
                        <h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;">{{group.type}} Group</h6>
                    </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
        <div class="col-sm-12" v-if=" ! groups.length">
            <hr class="divider inv">
            <p class="text-muted lead text-center">Hmmmm. We couldn't find any groups matching your search.</p>
            <hr class="divider inv">
        </div>
        <div class="col-xs-12 text-center" v-if="groups.length">
            <nav>
                <ul class="pagination pagination-md">
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
            <div class="col-xs-12">
                <h1 class="dash-trailing-light">Group Leader Or Youth Pastor</h1>
            </div>
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
                    <a href="downloads/SampleSchedule.pdf" target="_blank" class="btn btn-primary btn-sm">Sample Weekly Schedule</a>
                    <hr class="divider inv">
                    <p>Our leaders or "project directors" number one job is to take care of you (the team leader) so that you can properly take care of your group (team). Most importantly, we strive to create a culture of leadership on our trips that creates new leaders and takes your student leaders to the next level.</p>
                    <a href="downloads/SampleFlow.pdf" target="_blank" class="btn btn-primary btn-sm">Sample Leadership Flow Chart</a>
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
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
		name: 'groups',
        components: { vSelect, 'alert': VueStrap.alert },
        data(){
            return{
                // logic vars
                campaigns:[],
                groups:[],
                groupsLimit: 8,
                attemptSubmit: false,
                resource: this.$resource('groups?isPublic=yes'),
                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
                countries: [],
                countryCodeObj: null,
                timezones: [],
                errors: {},
                showSuccess: false,

                // form vars
                name: '',
                type: '',
                country_code: null,
                description: '',
                timezone: null,
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                url: '',
                campaign: '',
                contact: '',
                position: '',
                email: '',
                spoke_to_rep: undefined,

                // pagination vars
                search: '',
                page: 1,
                per_page: 8,
                pagination: {},
            }
        },
        computed: {
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            },
        },
        watch: {
            'page': function (val, oldVal) {
                this.pagination.current_page = val;
                this.searchGroups();
            },
            'search': function (val) {
                this.searchGroups();
            }
        },
        methods:{
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateGroup[field].invalid && this.attemptSubmit;
            },
            /*seeAll(){
					this.groupsLimit = this.groups.length
				},*/
            searchGroups(){
                this.resource.query(null, {
                    search: this.search,
                    page: this.page,
                    per_page: this.per_page
                }).then(function(groups){
                    this.groups = groups.data.data;
                    this.pagination = groups.data.meta.pagination;
                }).then(function () {

                });
            },
            resetForm() {
                this.name ='';
                this.type ='';
                this.countryCodeObj = null;
                this.country_code = null;
                this.description ='';
                this.timezone = null;
                this.phone_one ='';
                this.phone_two ='';
                this.address_one ='';
                this.address_two ='';
                this.city ='';
                this.state ='';
                this.zip ='';
                this.url ='';
                this.campaign ='';
                this.contact ='';
                this.position ='';
                this.email ='';
                this.spoke_to_rep = undefined;
                this.attemptSubmit = false;
                $('#collapseGroupForm').collapse('hide');
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateGroup.valid) {
                    this.$http.post('groups/submit', {
                        name: this.name,
                        type: this.type,
                        country_code: this.country_code,
                        description: this.description,
                        timezone: this.timezone,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        address_one: this.address_one,
                        address_two: this.address_two,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        url: this.url,
                        campaign: this.campaign,
                        contact: this.contact,
                        position: this.position,
                        email: this.email,
                        spoke_to_rep: this.spoke_to_rep,
                    }).then(function (response) {
                        console.log(response);
                        this.showSuccess = true;
                        this.resetForm();
                    }, function (error) {
                        this.errors = error.data.errors
                    });
                }
            }
        },
        ready(){
            this.searchGroups();

            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/timezones').then(function (response) {
                this.timezones = response.data.timezones;
            });

            this.$http.get('campaigns').then(function (response) {
                this.campaigns = response.data.data;
            })
        }
    }
</script>
