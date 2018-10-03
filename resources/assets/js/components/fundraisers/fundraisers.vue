<template>
    <div>
        <div class="content-page-header">
            <img class="img-responsive" src="images/fundraising/fundraisers-header.jpg" alt="Fundraisers">
            <div class="c-page-header-text">
                <h1 class="text-uppercase dash-trailing">Fundraisers</h1>
            </div><!-- end c-page-header-content -->
        </div><!-- end c-page-header -->
        <hr class="divider inv lg">
        <div class="container">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                <label>Find a Missionary to Support</label>
                <div class="form-group form-group-md">
                    <input type="text" class="form-control" placeholder="Search by fundraiser name..." v-model="search" @keyup="debouncedSearch">
                </div><!-- /input-group -->
            </div>
        </div>
        <hr class="divider inv lg">
        <div class="container" style="display:flex; flex-wrap: wrap;">
            <spinner ref="spinner" global size="sm" text="Loading"></spinner>

            <template v-if="fundraisers.length">
                <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in limitBy(fundraisers, fundraisersLimit)" style="display:flex; flex-direction:column;">
                    <div class="panel panel-default">
                        <img :src="fundraiser.featured_image ? fundraiser.featured_image : 'images/placeholders/fundraiser-placeholder.jpg'" :alt="fundraiser.name" class="img-responsive">
                        <div class="panel-body">
                            <a :href="'/' + fundraiser.url"><h6>{{ fundraiser.name }}</h6></a>
                            <div class="row">
                                <div class="col-xs-6 col-sm-12 col-md-6">
                                    <label style="margin-bottom:0;">Raised</label>
                                    <p class="text-success small" style="margin-bottom:0;">{{ currency(fundraiser.raised_amount) }}</p>
                                </div>
                                <div class="col-xs-6 col-sm-12 col-md-6">
                                    <label style="margin-bottom:0;">Expires</label>
                                    <p class="small" style="margin-bottom:0;">{{ fundraiser.ended_at | moment('ll') }}</p>
                                </div>
                            </div><!-- end row -->
                            <label><span>{{ fundraiser.raised_percent.toFixed(2) }}</span>% <small>Funded</small> / <span>{{ fundraiser.donors_count }}</span> <small>Donors</small></label>
                            <div class="progress" style="height:9px;margin-bottom:15px;">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" :style="{ width: fundraiser.raised_percent + '%'}">
                                    <span class="sr-only">{{ fundraiser.raised_percent.toFixed(2) }}% Complete (success)</span>
                                </div>
                            </div>
                            <p class="text-center" style="margin-bottom:0;"><a class="btn btn-primary-hollow btn-sm" :href="'/' + fundraiser.url">Details</a></p>
                        </div><!-- end panel-body -->
                    </div><!-- end panel -->
                </div><!-- end col -->
                <div class="col-xs-12 text-center">
                    <pagination :pagination="pagination" pagination-key="pagination" :callback="searchFundraisers" :hide-total="true"></pagination>
                </div>
            </template>
            <template v-else>
                <div class="col-xs-12 text-center">
                    <p class="text-muted"><em>We didn't find fundraisers matching your search. Please modify your search terms.</em></p>
                    <hr class="divider inv">
                    <hr class="divider inv">
                </div>
            </template>
        </div>
    </div>

</template>
<script type="text/javascript">
	import _ from 'underscore';
    export default{
        name: 'fundraisers',
        props: ['id', 'type'],
        data(){
            return{
                featuredFundraisers: [],
                fundraisers: [],
                fundraisersLimit: 9,

                // pagination vars
                search: '',
                pagination: { current_page: 1 },
            }
        },
        computed: {
        	per_page() {
				return this.fundraisersLimit;
			}
        },
        methods:{
            calcPath(fundraiser){
                return fundraiser.sponsor.data.url + '/' + fundraiser.url;
            },
            debouncedSearch: _.debounce(function() { this.searchFundraisers(); }, 250),
            searchFundraisers(){
                this.$http.get('fundraisers', { params: {
                    active: true,
                    include: 'sponsor',
                    search: this.search,
                    page: this.pagination.current_page,
                    per_page: this.per_page,
					isPublic: true,
				}}).then((response) => {
                    this.fundraisers = response.data.data;
                    this.featuredFundraisers = _.first(this.fundraisers, 5);
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }, this.$root.handleApiError);
            },
            seeAll(){
                this.fundraisersLimit = this.fundraisers.length
            }
        },
        mounted() {
            this.searchFundraisers();
        }
    }
</script>