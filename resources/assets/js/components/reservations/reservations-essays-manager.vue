<template>
    <div class="row" v-if="loaded">
        <div class="col-sm-6">
            <div class="panel panel-default" v-if="essay">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{essay.citizenship_name}}</h6>
                    <a role="button" :href="'/dashboard/records' + essay.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{essay.given_names}} {{essay.surname}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>ID:</b> {{essay.number}}
                        <br>
                        <b>BIRTH COUNTRY:</b> {{essay.citizenship_name}}
                        <br>
                        <b>ISSUED ON:</b> {{essay.issued_at|moment 'll'}}
                        <br>
                        <b>EXPIRES ON:</b> {{essay.expires_at|moment 'll'}}
                    </p>
                </div>
            </div>
            <div v-if="!essay" class="alert alert-info" role="alert">This reservation has no essay(s) assigned to it. Please select one or add one.</div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div style="min-height:220px;" class="panel-body">
                    <form novalidate>
                        <label>Actions</label>
                        <a class="btn btn-block btn-info btn-sm" @click="toggleChangeState()">
                            <i class="fa fa-pencil"></i> Change Essay
                        </a>
                        <a class="btn btn-block btn-primary btn-sm" href="/dashboard/records/essays/create">
                            <i class="fa fa-plus"></i> Add New Essay
                        </a>
                    </form>

                </div><!-- end panel-body -->
            </div>
        </div>

        <div class="col-sm-12" v-if="changeState">
            <div class="col-sm-12" v-if="loaded && !essays.length">
                <div class="alert alert-info" role="alert">No essays found. Please create add one.</div>
            </div>
            <div class="row" v-if="loaded && essays.length">
                <div class="col-sm-4" v-for="essay in paginatedEssays">
                    <div class="panel panel-default">
                        <div style="min-height:220px;" class="panel-body">
                            <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{essay.citizenship_name}}</h6>
                            <a role="button" :href="'/dashboard/records' + essay.links[0].uri">
                                <h5 style="text-transform:capitalize;" class="text-primary">
                                    {{essay.given_names}} {{essay.surname}}
                                </h5>
                            </a>
                            <hr class="divider lg">
                            <p class="small">
                                <b>ID:</b> {{essay.number}}
                                <br>
                                <b>BIRTH COUNTRY:</b> {{essay.citizenship_name}}
                                <br>
                                <b>ISSUED ON:</b> {{essay.issued_at|moment 'll'}}
                                <br>
                                <b>EXPIRES ON:</b> {{essay.expires_at|moment 'll'}}
                            </p>
                        </div><!-- end panel-body -->
                        <div class="panel-footer" style="padding: 0;">
                            <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                                <a class="btn btn-danger" @click="setEssay(essay)">
                                    Select Essay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <li :class="{ 'disabled': pagination.current_page == 1 }">
                                <a aria-label="Previous" @click="page=pagination.current_page-1">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                            <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                                <a aria-label="Next" @click="page=pagination.current_page+1">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!--<div class="col-sm-12" v-if="newState">
            <essay-create-update></essay-create-update>
        </div>-->

    </div>
</template>
<script>
    import essayCreateUpdate from '../records/essays/essay-create-update.vue';
    export default{
        name: 'reservations-essays-manager',
        components:{essayCreateUpdate},
        props:['reservationId', 'essayId'],
        data(){
            return{
                essay:null,
                essays: [],

                //logic vars
                essayResource: this.$resource('essays{/id}'),
                reservationResource: this.$resource('reservations{/id}'),
                loaded: false,
                newState: false,
                changeState: false,

                // pagination vars
                paginatedEssays: [],
                selectedEssay: null,
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                    total_pages: 0
                },

            }
        },
        watch:{
            'page': function (val, oldVal) {
                this.pagination.current_page = val;
                this.paginate();
            },
            'essays':function (val) {
                if(val.length) {
                    this.paginate();
                }
            }
        },
        methods:{
            // emulate pagination
            paginate(){
                var array = [];
                var start = (this.pagination.current_page - 1) * this.per_page;
                var end   = start + this.per_page;
                var range = _.range(start, end);
                _.each(range, function (index) {
                    if (this.essays[index])
                        array.push(this.essays[index]);
                }, this);
                this.paginatedEssays = array;
            },
            setEssay(essay){
                if(essay) {
                    this.essay = essay;
                    this.reservationResource.update({id: this.reservationId}, {
                        essay_id: essay.id
                    }).then(function (response) {
                        this.toggleChangeState();
                    });
                }
            },
            toggleChangeState(){
                this.changeState=!this.changeState;
                this.newState = false;
            },
            toggleNewState(){
                this.newState=!this.newState;
                this.changeState = false;

            },
        },
        ready(){
            this.$http('users/me?include=essays').then(function (response) {
                this.essays = response.data.data.essays.data;
                this.pagination.total_pages = Math.ceil(this.essays.length / this.per_page);
                this.essay = _.findWhere(response.data.data.essays.data, {id: this.essayId});
                this.loaded = true;
            });
        }
    }
</script>
