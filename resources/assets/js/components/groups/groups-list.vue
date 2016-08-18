<template>
<div>
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="group in groups" v-if="groups.length > 0">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h5>{{ group.trip.data.campaign.data.name }} <small>{{ group.country }}</small></h5>
            </div>
            <div class="panel-body text-center">
                <img :src="group.avatar" class="img-circle img-lg">
                <hr class="divider inv sm">
                <h6 class="label label-default text-uppercase">{{ group.trip.data.type }} Missionary</h6>
                <h4>{{ group.surname }}, {{ group.given_names }}</h4>
                <h5 class="text-capitalize">{{ group.trip.data.group.data.name }}</h5>
                <a class="btn btn-sm btn-primary" href="/dashboard/groups/{{ group.id }}">View Group</a>
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
                this.$http.get('groups', {
                    include: 'trip.campaign,trip.group',
                    user: new Array(this.userId)
                }).then(function (response) {

                    switch (this.type) {
                        case 'active':
                            this.groups = _.filter(response.data.data, function (group) {
                                return moment().isBefore(group.trip.data.ended_at);
                            });
                            break;
                        case 'archive':
                            this.groups = _.filter(response.data.data, function (group) {
                                return moment().isAfter(group.trip.data.ended_at);
                            });
                            break;
                    }
                })
            },
        },
        ready(){
            this.getGroups();
        }
    }
</script>
