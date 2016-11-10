<template>
    <div>
        <div class="col-sm-12" v-if="!fundraisers.length">
            <div class="alert alert-info" role="alert">No records found</div>
        </div>
        <div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12" v-for="fundraiser in fundraisers" v-if="fundraisers.length">
            <div class="panel panel-default">
                <img :src="fundraiser.banner||'images/india-prof-pic.jpg'" alt="India" class="img-responsive">
                <div class="panel-body">
                    <h4>{{ fundraiser.name }}</h4>
                    <h6>
                        {{ fundraiser.description || 'No Description'}}
                        <br>
                        Expires: {{ fundraiser.expires_at | moment 'll'  }}
                    </h6>
                    <h3><span class="text-success">{{ fundraiser.raised_amount | currency }}</span> <small>Raised</small></h3>
                    <p><span>{{ fundraiser.goal_amount > 0 ? (fundraiser.raised_amount / fundraiser.goal_amount * 100) : 0 }}</span>% <small>Funded</small> / <span>2</span> <small>Donors</small></p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" :style="{ width: (fundraiser.goal_amount > 0 ? (fundraiser.raised_amount / fundraiser.goal_amount * 100) : 0 ) + '%'}">
                            <span class="sr-only">{{ fundraiser.goal_amount > 0 ? (fundraiser.raised_amount / fundraiser.goal_amount * 100) : 0 }}% Complete (success)</span>
                        </div>
                    </div>
                    <p><a class="btn btn-primary btn-block" :href=" pathName + '/' + fundraiser.links[0].uri">Details</a></p>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col -->
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'group-profile-fundraisers',
        props: ['id', 'groupUrl'],
        data(){
            return{
                fundraisers: [],

                pathName: window.location.pathname
            }
        },
        ready(){
            this.$http.get('fundraisers', {
                sponsor: this.groupUrl,
            }).then(function (response) {
                this.fundraisers = response.data.data;
            })
        }

    }
</script>
