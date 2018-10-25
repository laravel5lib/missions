<template>
    <div>
        <div class="content-page-header">
            <img class="img-responsive" src="/images/groups/groups-header.jpg" alt="">
            <div class="c-page-header-text">
                <h1 class="text-uppercase dash-trailing">Teams</h1>
            </div><!-- end c-page-header-content -->
        </div><!-- end c-page-header -->
        <div class="white-bg">
            <div class="container">
                <div class="content-section">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
                            <h2 class="text-primary">Team trips are what we do!</h2>
                            <p>Missions.Me specializes in taking teams from around the world on life-changing missions experiences.  If you are interested in partnering with one of our missions trips, please fill out the form.  Missions.Me can provide your group with its own profile, URL and customized  trips created especially for your church or organization.</p>
                            <hr class="divider inv">
                            <a class="btn btn-primary btn-lg" href="/teams/request">Take Your Church or Organization</a>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <!--<hr class="divider inv xlg">-->
                    <!--<div class="row collapse" id="collapseGroupForm">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">

                            <form id="CreateGroupForm" class="form-horizontal" novalidate>
                                <div class="form-group">
                                    <div class="col-sm-6" v-error-handler="{ value: name, handle: 'name' }">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" v-model="name"
                                               placeholder="Church/Organization Name" v-validate="'required|min:1|max:100'"
                                               maxlength="100" minlength="1">
                                    </div>
                                    <div class="col-sm-6" v-error-handler="{ value: campaign, handle: 'campaign' }">
                                        <label for="campaign">Which Trip are you interested in?</label>
                                        <select name="campaign" id="campaign" class="form-control" v-model="campaign" v-validate="'required'">
                                            <option value="">&#45;&#45; please select &#45;&#45;</option>
                                            <option :value="campaign.id" v-for="campaign in campaigns">{{campaign.name}}</option>
                                        </select>
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
                                    <div class="col-sm-4" v-error-handler="{ value: city, handle: 'city' }">
                                        <label for="infoCity">City</label>
                                        <input type="text" class="form-control" v-model="city" name="city" id="infoCity" placeholder="City">
                                    </div>
                                    <div class="col-sm-4" v-error-handler="{ value: state, handle: 'state' }">
                                        <label for="infoState">State/Prov.</label>
                                        <input type="text" class="form-control" v-model="state" name="state" id="infoState" placeholder="State/Province">
                                    </div>
                                    <div class="col-sm-4" v-error-handler="{ value: zip, handle: 'zip' }">
                                        <label for="infoZip">ZIP/Postal Code</label>
                                        <input type="text" class="form-control" v-model="zip" name="zip" id="infoZip" placeholder="12345">
                                    </div>
                                </div>

                                <div class="row form-group col-sm-offset-2">
                                    <div class="col-sm-6">
                                        <div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                                            <label for="country">Country</label>
                                            <v-select @keydown.enter.prevent="" class="form-control" name="country" id="country" v-validate="'required'" v-model="countryCodeObj" :options="UTILITIES.countries" label="name"></v-select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" v-error-handler="{ value: type, handle: 'type' }">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control" v-model="type" v-validate="'required'">
                                            <option value="">&#45;&#45; please select &#45;&#45;</option>
                                            <option :value="option" v-for="option in typeOptions">{{ option|capitalize }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4" v-error-handler="{ value: timezone, handle: 'timezone' }">
                                        <label for="timezone">Timezone</label>
                                        <v-select @keydown.enter.prevent=""  class="form-control" name="timezone" id="timezone" v-model="timezone" :options="UTILITIES.timezones" v-validate="'required'"></v-select>

                                    </div>
                                    <div class="col-sm-4" v-error-handler="{ value: phone_one, client: 'phone', server: 'phone_one' }">
                                        <label for="infoPhone">Phone 1</label>
                                        <phone-input v-model="phone_one" name="phone" id="infoPhone"></phone-input>
                                        <p class="help-block" v-if="errors.has('phone')" v-text="errors.phone"></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="infoMobile">Phone 2</label>
                                        <phone-input v-model="phone_two" name="phone" id="infoMobile"></phone-input>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4" v-error-handler="{ value: contact, handle: 'contact' }">
                                        <label for="contact">Your Name</label>
                                        <input type="text" class="form-control" name="contact" id="contact" v-model="contact"
                                               placeholder="John Smith" v-validate="'required|min:1|max:100'"
                                               maxlength="100" minlength="1">
                                    </div>
                                    <div class="col-sm-4" v-error-handler="{ value: email, handle: 'email' }">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate="'required|email'">
                                    </div>
                                    <div class="col-sm-4" v-error-handler="{ value: position, handle: 'position' }">
                                        <label for="position">Your Position</label>
                                        <input type="text" class="form-control" name="position" id="position" v-model="position" v-validate="'required|min:1'">
                                    </div>
                                </div>

                                <div class="form-group" v-error-handler="{ value: spoke_to_rep, client: 'spoken', server: 'spoke_to_rep' }">
                                    <label for="status" class="col-sm-8 control-label">Have you spoken with a Missions.Me representative?</label>
                                    <div class="col-sm-4">
                                        <label class="radio-inline">
                                            <input type="radio" name="spoken" id="status" value="yes" v-model="spoke_to_rep" v-validate="'required'"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="spoken" id="status2" value="no" v-model="spoke_to_rep" v-validate="''"> No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <a @click="submit" class="btn btn-primary">Send Request</a>
                                    </div>
                                </div>
                            </form>

                        </div>&lt;!&ndash; end col &ndash;&gt;
                    </div>&lt;!&ndash; end row &ndash;&gt;
                    <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                        <span class="icon-ok-circled alert-icon-float-left"></span>
                        <strong>Done</strong>
                        <p>Request sent</p>
                    </alert>-->

                </div><!-- end content-section -->
            </div><!-- end container -->
        </div><!-- end white-bg -->
        <hr class="divider inv xlg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <h4 class="hidden-xs">Organizations Partnered With Us</h4>
                    <h4 class="visible-xs text-center">Organizations Partnered With Us</h4>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name or type" v-model="search" @keyup="debouncedSearch">
                        <span class="input-group-addon" id="basic-addon2"><i class="fa fa-search"></i></span>
                    </div><!-- /input-group -->
                </div>
            </div>
            <hr class="divider">
        </div>
        <div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>
            <template v-if="groups.length">
                <div class="col-xs-6 col-sm-3 col-md-2" v-for="group in limitBy(groups, groupsLimit)" style="display:flex">
                    <div class="panel panel-default">
                        <a :href="group.url" role="button">
                            <img :src="group.avatar" :alt="group.name" class="img-responsive">
                        </a>
                        <div class="panel-body text-center">
                            <a :href="group.url" role="button">
                                <h5 style="margin-bottom:0;" class="text-primary text-capitalize">{{group.name}}</h5>
                            </a>
                            <label>{{group.type}}</label>
                        </div><!-- end panel-body -->
                    </div><!-- end panel -->
                </div><!-- end col -->
                <div class="col-xs-12 text-center" v-if="groups.length">
                    <pagination :pagination="pagination" pagination-key="pagination" :callback="searchGroups" :hide-total="true"></pagination>
                </div>
            </template>
            <template v-else>
                <div class="col-xs-12">
                    <hr class="divider inv">
                    <p class="text-muted text-center">
                        <em>We didn't find any organizations matching your search.</em>
                        <br />
                        Are you a church or organization?
                        <a class="text-primary small" href="/groups">
                            <i class="fa fa-arrow-circle-o-right"></i>  Partner with us.
                        </a>
                    </p>
                    <hr class="divider inv">
                </div>

            </template>
        </div>
        <hr class="divider hidden-xs" style="margin-top:0;margin-bottom:0;">
        <div id="carousel-fundraising-platform" class="carousel slide hidden-sm hidden-xs" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carousel-fundraising-platform" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-fundraising-platform"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img class="img-responsive" src="/images/groups/groups-slide-1.jpg">
                    <div class="carousel-caption">
                        <h3 class="dash-trailing">You're In Control</h3>
                        <p>Control who has administrator access to your team, assign facilitators to manage your trips, and customize your organization's public profile. Use the Stories feature to encourage and inform your supporters or make important announcements.</p>
                    </div>
                </div><!-- end item -->
                <div class="item">
                    <img class="img-responsive" src="/images/groups/groups-slide-2.jpg">
                    <div class="carousel-caption">
                        <h3 class="dash-trailing">Monitor & Promote Your Trips</h3>
                        <p>Easily capture new signups or interests as you walk visitors through the registration process. See important details about your customized Missions.Me trip and get notified of account activity.</p>
                    </div>
                </div><!-- end item -->
                <div class="item">
                    <img class="img-responsive" src="/images/groups/groups-slide-3.jpg">
                    <div class="carousel-caption">
                        <h3 class="dash-trailing">Trip Management Made Easy</h3>
                        <p>See who's coming and track his or her fundraising progress. Monitor or directly assist in completing a missionary's trip requirements - even contact them directly.</p>
                    </div>
                </div><!-- end item -->
            </div>
            <a class="left carousel-control" href="#carousel-fundraising-platform" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-fundraising-platform" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div><!-- end carousel -->
        <hr class="divider hidden-xs hidden-sm" style="margin-top:0;margin-bottom:0;">
        <div class="white-bg visible-sm visible-xs">
            <div class="container">
                <div class="content-section">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <img class="img-responsive" src="/images/groups/mobile/groups-slide-1.png">
                        </div><!-- end col -->
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
                            <h3 class="dash-trailing">You're In Control</h3>
                            <p>Control who has administrator access to your team, assign facilitators to manage your trips, and customize your organization's public profile. Use the Stories feature to encourage and inform your supporters or make important announcements.</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end content-section -->
            </div><!-- end container -->
            <hr class="divider" style="margin-top:0;margin-bottom:0;">
        </div><!-- end gray-lighter-bg -->
        <div class="gray-lighter-bg visible-sm visible-xs">
            <div class="container">
                <div class="content-section">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <img class="img-responsive" src="/images/groups/mobile/groups-slide-2.png">
                        </div><!-- end col -->
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
                            <h3 class="dash-trailing">Monitor & Promote Your Trips</h3>
                            <p>Easily capture new signups or interests as you walk visitors through the registration process. See important details about your customized Missions.Me trip and get notified of account activity.</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end content-section -->
            </div><!-- end container -->
            <hr class="divider" style="margin-top:0;margin-bottom:0;">
        </div><!-- end gray-lighter-bg -->
        <div class="white-bg visible-sm visible-xs">
            <div class="container">
                <div class="content-section">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <img class="img-responsive" src="/images/groups/mobile/groups-slide-3.png">
                        </div><!-- end col -->
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
                            <h3 class="dash-trailing">Trip Management Made Easy</h3>
                            <p>See who's coming and track his or her fundraising progress. Monitor or directly assist in completing a missionary's trip requirements - even contact them directly.</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end content-section -->
            </div><!-- end container -->
            <hr class="divider" style="margin-top:0;margin-bottom:0;">
        </div><!-- end gray-lighter-bg -->
        <div id="testimonial-carousel" class="white-bg">
            <div class="container">
                <div class="content-section" style="padding:40px 0 25px;">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="carousel slide" id="fade-quote-carousel" data-ride="carousel" data-interval="3000">
                                <ol class="carousel-indicators">
                                    <li data-target="#fade-quote-carousel" data-slide-to="0" class="active"></li>
                                    <li data-target="#fade-quote-carousel" data-slide-to="1"></li>
                                    <li data-target="#fade-quote-carousel" data-slide-to="2"></li>
                                    <li data-target="#fade-quote-carousel" data-slide-to="3"></li>
                                </ol>
                                <!-- Carousel items -->
                                <div class="carousel-inner">
                                    <div class="active item">
                                        <img class="img-responsive img-circle profile-circle" src="/images/testimonies/cameron-testimony.jpg">
                                        <blockquote>
                                            <p>On the mission field, I found God’s heart for these people in my own heart.</p>
                                            <cite title="Cameron C">Cameron C</cite>
                                        </blockquote>
                                    </div>
                                    <div class="item">
                                        <img class="img-responsive img-circle profile-circle" src="/images/testimonies/anthony-testimony.jpg">
                                        <blockquote>
                                            <p>I’ve learned true happiness comes through serving those around us.</p>
                                            <cite title="Anthony M">Anthony M</cite>
                                        </blockquote>
                                    </div>
                                    <div class="item">
                                        <img class="img-responsive img-circle profile-circle" src="/images/testimonies/danielle-testimony.jpg">
                                        <blockquote>
                                            <p>I will never forget bringing people hope and showing them the love of Jesus.</p>
                                            <cite title="Danielle O">Danielle O</cite>
                                        </blockquote>
                                    </div>
                                    <div class="item">
                                        <img class="img-responsive img-circle profile-circle" src="/images/testimonies/ruth-testimony.jpg">
                                        <blockquote>
                                            <p>God has transformed my heart and I will never be the same.</p>
                                            <cite title="Ruth P">Ruth P</cite>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end content-section -->
            </div><!-- end container -->
        </div><!-- end white-bg -->
        <div class="dark-bg-primary">
            <div class="container">
                <div class="content-section">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h1>Leader Or Pastor</h1>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h5>
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Is this going to be a logistical nightmare?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <p>Missions.Me specializes in bringing churches, youth groups, college groups, business groups, or any other kind of team of all sizes. We take care of all of your transportation, hotel, food and ministry schedule. As a leader, fund-raising and team building is all you'll need to focus on.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h5>
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Will my team be eating bugs and sleeping in the jungle?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            <p>It is the vision of Missions.Me to give our best to the people we're ministering to. To achieve that, our missionaries need to be well rested and well fed. The conditions will vary from state to state, but keep in mind that the farther your team travels from a major city, the less available "higher standard" accommodations become.</p>

                                            <p>HOTEL: Every hotel we stay in is inspected by a staff member prior to the trip. We guarantee that your hotel will be equipped with air conditioning and high speed internet.</p>

                                            <p>FOOD: All restaurants are tested and approved by a staff member. You will eat at widely-known American restaurants (i.e. McDonald's, Wendy's, Pizza Hut) whenever possible. You will also be given the opportunity to partake in the local cuisine.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h5>
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                How will my leadership fit with Missions.Me?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                        <div class="panel-body">
                                            <p>If you've ever been on a trip before, you know nothing gets done without organization and good leadership.From scheduling ministry sites, pick-ups, drop-offs, meals, translators and communicating with in-country contacts, our leaders handle everything and relay to you and your leadership the necessary information.</p>
                                            <a href="downloads/SampleSchedule.pdf" target="_blank" class="btn btn-primary btn-sm">Sample Weekly Schedule</a>
                                            <hr class="divider inv">
                                            <p>Our leaders or "project directors" number one job is to take care of you (the team leader) so that you can properly take care of your team. Most importantly, we strive to create a culture of leadership on our trips that creates new leaders and takes your student leaders to the next level.</p>
                                            <a href="downloads/SampleFlow.pdf" target="_blank" class="btn btn-primary btn-sm">Sample Leadership Flow Chart</a>
                                        </div>
                                    </div>
                                </div><!-- end panel -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingFive">
                                        <h5>
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                How will I recruit?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                        <div class="panel-body">
                                            <p>Missions.Me specializes in not only bringing a message of hope to nations around the world but also a missional message to the American church. Every year, Missions.Me holds several Missions Nights in churches and generational services around the U.S. The service is packed with video, a powerful message from one of our Missions.Me Project Directors and a call to the mission field. The Missions.Me team comes prepared with applications, training instructions and fundraising ideas to make your church groups trip preparation process so easy. That night following the powerful missions service, those who responded will meet and together, we will launch your group with everything they will need to change the world.</p>
                                        </div>
                                    </div>
                                </div><!-- end panel -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingSix">
                                        <h5>
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                How will I train my team?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                                        <div class="panel-body">
                                            <p>Missions.Me gives you everything you need to hold successful training meetings. You set your own dates and times for training, we do the rest.</p>
                                        </div>
                                    </div>
                                </div><!-- end panel -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="video-outer">
                                <div class="video-inner">
                                    <iframe src="https://player.vimeo.com/video/203848987?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/javascript">
	import _ from 'underscore';
    import timezone from 'moment-timezone';
    import vSelect from 'vue-select';
    import errorHandler from'../error-handler.mixin';
    import utilities from'../utilities.mixin';
    export default{
		name: 'groups',
        components: { vSelect },
        mixins: [errorHandler, utilities],
        data(){
            return{
                // logic vars
                campaigns:[],
                groups:[],
                groupsLimit: 12,
                resource: this.$resource('groups', { isPublic: 'yes' }),
                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
                countryCodeObj: null,
                showSuccess: false,

                // form vars
                name: '',
                type: '',
//                country_code: null,
                description: '',
                timezone: timezone.tz.guess(),
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                campaign: '',
                contact: '',
                position: '',
                email: '',
                spoke_to_rep: undefined,

                // pagination vars
                search: '',
                per_page: 12,
                pagination: { current_page: 1 },
            }
        },
        computed: {
            country_code: {
                get() {
                    return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
                },
                set() {}
            },
        },
        methods:{
            /*seeAll(){
					this.groupsLimit = this.groups.length
				},*/
            debouncedSearch: _.debounce(function() { this.searchGroups(); }, 250),
            searchGroups(){
                // this.$refs.spinner.show();
                this.resource.query({
                    search: this.search,
                    page: this.pagination.current_page,
                    per_page: this.per_page
                }).then((groups) =>{
                    this.groups = groups.data.data;
                    this.pagination = groups.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }).catch(this.$root.handleApiError);
            },
            resetForm() {
                this.name ='';
                this.type ='';
                this.countryCodeObj = null;
                this.country_code = null;
                this.description ='';
                this.timezone = timezone.tz.guess();
                this.phone_one ='';
                this.phone_two ='';
                this.address_one ='';
                this.address_two ='';
                this.city ='';
                this.state ='';
                this.zip ='';
                this.campaign ='';
                this.contact ='';
                this.position ='';
                this.email ='';
                this.spoke_to_rep = undefined;
                this.attemptSubmit = false;
              this.$validator.reset();
              $('#collapseGroupForm').collapse('hide');

            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

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
                        campaign: this.campaign,
                        contact: this.contact,
                        position: this.position,
                        email: this.email,
                        spoke_to_rep: this.spoke_to_rep,
                    }).then((response) => {
                        console.log(response);
                        this.showSuccess = true;
                        //TODO use universal alert
                        this.resetForm();
                        // this.$refs.spinner.hide();
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        // this.$refs.spinner.hide();
                    });
                });
            }
        },
        mounted(){
            this.searchGroups();
        }
    }
</script>
