<template>
    <div class="row" v-if="loaded">
        <div class="col-sm-6">
            <div class="panel panel-default" v-if="essay">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-file"></i> {{essay.subject}}</h6>
                    <a role="button" :href="'/dashboard/records/essays/' + essay.id">
                        <h4 style="text-transform:capitalize;" class="text-primary">
                            {{essay.author_name}}
                        </h4>
                    </a>
                    <hr class="divider lg">
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
                <div class="col-sm-4" v-for="essay in essays">
                    <div class="panel panel-default">
                        <div style="min-height:220px;" class="panel-body">
                            <h6 class="text-uppercase"><i class="fa fa-file"></i> {{essay.subject}}</h6>
                            <a role="button" :href="'/dashboard/records/essays/' + essay.id">
                                <h4 style="text-transform:capitalize;" class="text-primary">
                                    {{essay.author_name}}
                                </h4>
                            </a>
                            <hr class="divider lg">
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
                    <pagination :pagination.sync="pagination" :callback="searchEssays"></pagination>
                </div>
            </div>
        </div>

        <!--<div class="col-sm-12" v-if="newState">
            <essay-create-update></essay-create-update>
        </div>-->

    </div>
</template>
<script type="text/javascript">
    // import essayCreateUpdate from '../records/essays/essay-create-update.vue';
    export default{
        name: 'reservations-essays-manager',
        // components:{essayCreateUpdate},
        props:['reservationId', 'essayId', 'userId'],
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
                selectedEssay: null,
                per_page: 3,
                pagination: {
                    current_page:1
                },

            }
        },
        methods:{

            setEssay(essay){
                if(essay) {
                    this.essay = essay;
                    this.reservationResource.update({id: this.reservationId}, {
                        testimony_id: essay.id
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
            searchEssays(){
                this.$http.get('essays/?user=' + this.userId, { page: this.pagination.current_page }).then(function (response) {
                    this.essays = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.essay = _.findWhere(response.data.data, {id: this.essayId});
                    this.loaded = true;
                });
            }
        },
        ready(){
            this.searchEssays();
        }
    }
</script>
