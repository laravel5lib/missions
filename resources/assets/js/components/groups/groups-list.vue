<template>
<div>
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="group in groups" v-if="groups.length > 0">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h5>{{ group.name }} <small>{{ group.country }}</small></h5>
            </div>
            <div class="panel-body text-center">
                <img :src="group.avatar||group.banner" class="img-circle img-lg">
                <hr class="divider inv sm">
                <h6 class="label label-default text-uppercase">{{ group.type }} Group</h6>
                <!--<h4>{{ group.surname }}, {{ group.given_names }}</h4>-->
                <h5 class="text-capitalize"> </h5>
                <div class="btn-group btn-group-justified">
                    <a class="btn btn-sm btn-info" href="/groups/{{ group.url }}"><i class="fa fa-eye"></i> View</a>
                    <a class="btn btn-sm btn-primary" href="/dashboard/groups/{{ group.id }}"><i class="fa fa-pencil"></i> Manage</a>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info" v-if="groups.length < 1">No groups found</div>
</div>
</template>
<script>
    export default{
        name: 'groups-list',
        props: ['userId', 'type'],
        data(){
            return{
                groups: []
            }
        },
        methods: {
            country(code){
                return code;
            },
            getGroups(){
                this.$http.get('users/me', {
                    include: 'managing',
                    user: new Array(this.userId)
                }).then(function (response) {
                    this.groups = response.data.data.managing.data;
                })
            },
        },
        ready(){
            this.getGroups();
        }
    }
</script>
