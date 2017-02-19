<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 v-if="!editMode">{{campaign.name}}</h5>
        </div>
        <div class="panel-body">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Description</label>
                        <p v-if="!editMode">{{campaign.description}}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Country</label>
                        <p>{{campaign.country}}</p>
                        <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Page Source</label>
                        <p>{{campaign.page_src}}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Groups</label>
                        <p>{{campaign.groups_count}}</p>
                        <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Status</label>
                        <p>{{campaign.status}}</p>
                        <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    </div><!-- end col -->
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="well">
                            <label>Page Url</label>
                            <h5>
                                <a :href="'/' + campaign.page_url">
                                    <span class="text-muted">https://missions.me/</span>{{campaign.page_url}}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->
            <div class="col-sm-4 panel panel-default text-center">
                <div class="panel-body">
                    <label>Start Date</label>
                    <p>{{toDate(campaign.started_at)}}</p>
                    <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    <hr class="divider">
                    <label>End Date</label>
                    <p>{{toDate(campaign.ended_at)}}</p>
                    <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    <hr class="divider">
                    <label>Created At</label>
                    <p>{{toDate(campaign.updated_at, 'LLL')}}</p>
                    <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    <hr class="divider">
                    <label>Updated At</label>
                    <p>{{toDate(campaign.created_at, 'LLL')}}</p>
                    <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                    <hr class="divider">
                    <label>Published At</label>
                    <p>{{toDate(campaign.published_at, 'LLL')}}</p>
                    <!--<p><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></p>-->
                </div><!-- end panel-body -->
            </div><!-- end col -->
        </div><!-- end panel-body -->
        <!--<div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item" v-for="(key, value) in campaign">
                    {{key}}: {{value}}
                </li>
            </ul>
        </div>-->
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'details',
        data(){
            return{
                campaignId: this.$parent.campaignId,
                editMode: false
            }
        },
        computed:{
            campaign(){
                return this.$parent.campaign;
            }
        },
        methods:{
            toDate(date, format){
                return moment(date).format(format||'LL');
            }

        },
        activate(done){
            done();
        }
    }
</script>
