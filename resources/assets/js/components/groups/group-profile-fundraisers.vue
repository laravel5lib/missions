<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <template v-if="fundraisers.length">
            <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers">
                <div class="panel panel-default">
                    <!--<img :src="fundraiser.banner||'images/india-prof-pic.jpg'" alt="India" class="img-responsive">-->
                    <div class="panel-body">
                        <h5>{{ fundraiser.name }}</h5>
                        <h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;">Expires: {{ fundraiser.ended_at | moment 'll'  }}</h6>
                        <h3><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised</small></h3>
                        <p><span>{{ fundraiser.raised_percent|number }}</span>% <small>Funded</small> / <span>{{ fundraiser.donors_count }}</span> <small>Donors</small></p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" :style="{ width: fundraiser.raised_percent + '%'}">
                                <span class="sr-only">{{ fundraiser.raised_percent|number }}% Complete (success)</span>
                            </div>
                        </div>
                        <p><a class="btn btn-primary btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
            <div class="col-xs-12 text-center">
                <pagination :pagination.sync="pagination" :callback="getFundraisers"></pagination>
            </div>
        </template>
        <template v-else>
            <div class="col-sm-12">
                <div class="alert alert-info" role="alert">No records found</div>
            </div>
        </template>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'group-profile-fundraisers',
        props: ['id', 'groupUrl'],
        data(){
            return{
                fundraisers: [],
                pagination: { current_page: 1 },
                pathName: window.location.pathname
            }
        },
        methods: {
            getFundraisers(){
                this.$refs.spinner.show();
                this.$http.get('fundraisers', {
                    sponsor: 'groups/' + this.groupUrl,
                    page: this.pagination.current_page
                }).then(function (response) {
                    this.fundraisers = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.$refs.spinner.hide();
                }, function (error) {
                    this.$refs.spinner.hide();
                    //TODO add error alert
                });
            }
        },
        ready(){
            this.getFundraisers();
        }

    }
</script>
