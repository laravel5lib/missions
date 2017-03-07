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
                <h5 class="text-uppercase">{{ group.type }} Group</h5>
            </div>
            <div class="panel-body text-center">
                <img :src="group.avatar||group.banner" class="img-circle img-md">
                <h4>{{ group.name }}</h4>
                <p class="small">{{ group.country }}</p>
                <hr class="divider inv sm">
                <a v-if="group.public" class="btn btn-sm btn-primary" href="/{{ group.url }}">View Group</a><br>
                <label><a href="/dashboard/groups/{{ group.id }}"><i class="fa fa-pencil"></i> Manage</a></label>
            </div>
        </div>
    </div>
    <div class="alert alert-info" v-if="!selectUi && groups.length < 1">No groups found</div>
</div>
</template>
<script type="text/javascript">
    export default{
        name: 'groups-list',
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
                this.$http.get('users/' + this.$root.user.id, { params: {
                    include: 'managing',
                    user: new Array(this.$root.user.id)
                }}).then(function (response) {
                    this.groups = response.body.data.managing.data;
                })
            },
        },
        ready(){


            this.getGroups();
        }
    }
</script>
