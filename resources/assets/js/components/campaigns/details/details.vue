<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Details</h4>
        </div>
        <table class="table table-hover">
            <tbody>
            <tr>
                <th>Name</th>
                <td v-if="!editMode">{{campaign.name}}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td v-if="!editMode">{{campaign.description}}</td>
            </tr>
            <tr>
                <th>Page Url</th>
                <td>{{campaign.page_url}}</td>
            </tr>
            <tr>
                <th>Page Source</th>
                <td>{{campaign.page_src}}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{campaign.country}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Groups</th>
                <td>{{campaign.groups_count}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Status</th>
                <td>{{campaign.status}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{toDate(campaign.started_at)}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{toDate(campaign.ended_at)}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{toDate(campaign.updated_at, 'LLL')}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Updated At</th>
                <td>{{toDate(campaign.created_at, 'LLL')}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <th>Published At</th>
                <td>{{toDate(campaign.published_at, 'LLL')}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            </tbody>
        </table>
        <!--<div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item" v-for="(key, value) in campaign">
                    {{key}}: {{value}}
                </li>
            </ul>
        </div>-->
    </div>
</template>
<script>
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
