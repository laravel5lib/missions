<template>
    <div>
        <div class="row">
            <p class="text-muted text-center" v-if="fundrasiers.length < 1">No fundraisers found.</p>
            <div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>{{ fundraiser.name }}</h4>
                        <h6>
                            Expires: {{ fundraiser.ended_at | moment 'll'  }}
                            <span class="label label-default" v-if="!fundraiser.public">Private</span>
                        </h6>
                        <h3><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised</small></h3>
                        <p><span>{{ (fundraiser.raised_amount/fundraiser.goal_amount * 100)|number 1 }}</span>% <small>Funded</small> / <span>{{ fundraiser.donors_count }}</span> <small>Donors</small></p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" :style="{ width: (fundraiser.raised_amount/fundraiser.goal_amount * 100) + '%'}">
                                <span class="sr-only">{{ (fundraiser.raised_amount/fundraiser.goal_amount * 100) }}% Complete (success)</span>
                            </div>
                        </div>
                        <p><a class="btn btn-primary btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div>
        <div class="row" v-if="oldFundraisers.length > 0">
            <div class="col-xs-12">
                <hr />
                <h5 class="text-muted text-center">Previous Fundraisers</h5>
                <hr />
            </div>
        </div>
        <div class="row" v-if="oldFundraisers.length > 0">
            <div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in oldFundraisers">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>{{ fundraiser.name }}</h4>
                        <h6>
                            Closed: {{ fundraiser.ended_at | moment 'll'  }}
                            <span class="label label-default" v-if="!fundraiser.public">Private</span>
                        </h6>
                        <h3><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised / {{ fundraiser.donors_count }} Donors</small></h3>
                        <p><a class="btn btn-default btn-block" :href="pathName + '/' + fundraiser.url">Details</a></p>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div>
    </div>
</template>
<script>
    export default{
        name: 'user-profile-fundraisers',
        props: ['id', 'userUrl', 'authId'],
        data(){
            return{
                fundraisers: [],
                oldFundraisers: [],

                pathName: window.location.pathname
            }
        },
        ready(){
            this.$http.get('fundraisers?active=true', {
                sponsor: this.userUrl,
                per_page: 100
            }).then(function (response) {
                this.fundraisers = response.data.data;
            });

            this.$http.get('fundraisers?archived=true', {
                sponsor: this.userUrl,
                per_page: 100
            }).then(function (response) {
                this.oldFundraisers = response.data.data;
            })
        }

    }
</script>
