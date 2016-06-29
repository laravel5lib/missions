<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><i class="fa fa-info-circle"></i> Details</h4>
        </div>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td>Name</td>
                <td v-if="!editMode">{{campaign.name}}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td v-if="!editMode">{{campaign.description}}</td>
            </tr>
            <tr>
                <td>Country</td>
                <td>{{campaign.country}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>Thumbnail</td>
                <td>{{campaign.thumb_src}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>Start Date</td>
                <td>{{toDate(campaign.started_at)}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>End Date</td>
                <td>{{toDate(campaign.ended_at)}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>Created At</td>
                <td>{{toDate(campaign.updated_at, 'LLL')}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>Updated At</td>
                <td>{{toDate(campaign.created_at, 'LLL')}}</td>
                <!--<td><a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>-->
            </tr>
            <tr>
                <td>Published At</td>
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
                //campaign: null
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
