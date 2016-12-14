<template>
<div>
    <spinner v-ref:spinner size="sm" text="Loading"></spinner>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
            <hr class="divider inv">
            <h6 class="text-center text-uppercase">Choose Your Group</h6>
            <form v-if="selectUi">
                <div class="form-group">
                    <select class="form-control" v-model="currentGroup" @change="rememberSelection()">
                        <option :value="">Select Group</option>
                        <option v-for="group in groups" :value="group.id">{{group.name}}</option>
                    </select>
                </div>
            </form>
            <hr class="divider inv">
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4" v-for="group in groups" v-if="!selectUi && groups.length > 0">
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
                    <a v-if="group.public" class="btn btn-sm btn-info" href="/groups/{{ group.url }}"><i class="fa fa-eye"></i> View</a>
                    <a class="btn btn-sm btn-primary" href="/dashboard/groups/{{ group.id }}"><i class="fa fa-pencil"></i> Manage</a>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info" v-if="!selectUi && groups.length < 1">No groups found</div>
    <!--<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default" v-if="currentGroup != null && currentGroup !== 'undefined'">
            <div class="panel-body">
                <group-edit :group-id="currentGroup" :managing="true"></group-edit>
            </div>
        </div>
    </div>-->
</div>
</template>
<script type="text/javascript">
//    import groupEdit from './admin-group-edit.vue';
    export default{
        name: 'groups-list',
//        components: {'group-edit': groupEdit},
        props: {
            userId: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: ''
            },
            selectUi: {
                type: Boolean,
                default: false
            },
            groupId: {
                type: String,
                default: ''
            }
        },
        data(){
            return{
                groups: [],
                currentGroup: this.groupId !== '' ? this.groupId : (localStorage.currentGroup && localStorage.currentGroup !== 'undefined') ? localStorage.currentGroup : ''
            }
        },
        methods: {
            country(code){
                return code;
            },
            rememberSelection(){
                localStorage.currentGroup = this.currentGroup;
                if (this.groupId !== this.currentGroup) {
                    let group = _.findWhere(this.groups, {id: this.currentGroup});
                    location.pathname = '/' + location.pathname.split('/')[1] + group.links[0].uri;
                }
            },
            getGroups(){
                this.$refs.spinner.show();
                this.$http.get('users/me', {
                    include: 'managing',
                    user: new Array(this.userId)
                }).then(function (response) {
                    this.groups = response.data.data.managing.data;
                    this.$refs.spinner.hide();
                })
            },
        },
        ready(){


            this.getGroups();
        }
    }
</script>
