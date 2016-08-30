<template>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <!--<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>-->
            <li data-target="#carousel-example-generic" class="{{ $index == 0 ? 'active' : '' }}" :data-slide-to="$index" v-for="fundraiser in featuredFundraisers"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item {{ $index == 0 ? 'active' : '' }}" v-for="fundraiser in featuredFundraisers">
                <img :src="fundraiser.banner">
                <div class="carousel-caption">
                    <h6 class="text-uppercase"><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised</small></h6>
                    <h3>{{fundraiser.name}}</h3>
                    <a :href="'/fundraisers/' + fundraiser.id" class="btn btn-primary btn-sm">More Details</a>
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
        <div class="col-xs-12">
            <h4>Current Fundraisers</h4>
        </div>
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
    <div class="container">
        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers|limitBy fundraisersLimit">
            <div class="panel panel-default">
                <img :src="fundraiser.banner||'images/india-prof-pic.jpg'" alt="India" class="img-responsive">
                <div class="panel-body">
                    <h4>{{ fundraiser.name }}</h4>
                    <h6>Expires: {{ fundraiser.expires_at | moment 'll'  }}</h6>
                    <h3><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised</small></h3>
                    <p><span>{{ fundraiser.raised_percent }}</span>% <small>Funded</small> / <span>{{ fundraiser.donors_count }}</span> <small>Donors</small></p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" :style="{ width: fundraiser.raised_percent + '%'}">
                            <span class="sr-only">{{ fundraiser.raised_percent }}% Complete (success)</span>
                        </div>
                    </div>
                    <p><a class="btn btn-primary btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
        <div class="col-sm-12 text-center">
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
</template>
<script>
    export default{
        name: 'fundraisers',
        props: ['id', 'type', 'authId'],
        data(){
            return{
                featuredFundraisers: [],
                fundraisers: [],
                fundraisersLimit: 6,

                // pagination vars
                search: '',
                page: 1,
                per_page: 6,
                pagination: {},
                pathName: window.location.pathname
            }
        },
        watch: {
            'page': function (val, oldVal) {
                this.searchFundraisers();
            },
            'search': function (val, oldVal) {
                this.searchFundraisers();
            },
        },
        methods:{
            searchFundraisers(){
                this.$http.get('fundraisers', {
                    // include: '',
                    search: this.search,
                    per_page: this.per_page
                }).then(function (response) {
                    this.fundraisers = response.data.data;
                    this.featuredFundraisers = _.first(this.fundraisers, 5);
                });
            },
            seeAll(){
                this.fundraisersLimit = this.fundraisers.length
            }
        },
        ready() {
            this.searchFundraisers();
        }
    }
</script>