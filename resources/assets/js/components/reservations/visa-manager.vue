<template>
    <div class="row" v-if="loaded" style="position:relative">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="col-sm-12">
            <div class="text-center">
                <form novalidate>
                    <a class="btn btn-default-hollow btn-sm" @click="toggleChangeState()"><i class="fa fa-pencil icon-left"></i> Change Visa</a>
                    <a class="btn btn-primary-hollow btn-sm" href="/dashboard/records/visas/create"><i class="fa fa-plus icon-left"></i> Add New Visa</a>
                </form>
            </div>
            <hr class="divider inv">
            <div class="panel panel-default" v-if="visa">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{visa.citizenship_name}}</h6>
                    <a role="button" :href="'/dashboard/records' + visa.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{visa.given_names}} {{visa.surname}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>ID:</b> {{visa.number}}
                        <br>
                        <b>BIRTH COUNTRY:</b> {{visa.citizenship_name}}
                        <br>
                        <b>ISSUED ON:</b> {{visa.issued_at|moment('ll')}}
                        <br>
                        <b>EXPIRES ON:</b> {{visa.expires_at|moment('ll')}}
                    </p>
                </div>
            </div>
            <div v-if="!visa" role="alert"><p class="text-muted text-center"><em>This reservation has no visa(s) assigned to it. Please select one or add one.</em></p></div>
        </div>

        <div class="col-sm-12" v-if="changeState">
            <div class="col-sm-12" v-if="loaded && !visas.length">
                <div class="alert alert-info" role="alert">No visas found. Please create add one.</div>
            </div>
            <div class="row" v-if="loaded && visas.length">
                <div class="col-sm-4" v-for="visa in paginatedVisas">
                    <div class="panel panel-default">
                        <div style="min-height:220px;" class="panel-body">
                            <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{visa.citizenship_name}}</h6>
                            <a role="button" :href="'/dashboard/records' + visa.links[0].uri">
                                <h5 style="text-transform:capitalize;" class="text-primary">
                                    {{visa.given_names}} {{visa.surname}}
                                </h5>
                            </a>
                            <hr class="divider lg">
                            <p class="small">
                                <b>ID:</b> {{visa.number}}
                                <br>
                                <b>BIRTH COUNTRY:</b> {{visa.citizenship_name}}
                                <br>
                                <b>ISSUED ON:</b> {{visa.issued_at|moment('ll')}}
                                <br>
                                <b>EXPIRES ON:</b> {{visa.expires_at|moment('ll')}}
                            </p>
                        </div><!-- end panel-body -->
                        <div class="panel-footer" style="padding: 0;">
                            <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                                <a class="btn btn-danger" @click="setVisa(visa)">
                                    Select Visa
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
            <visa-create-update></visa-create-update>
        </div>-->

    </div>
</template>
<script>
    import visaCreateUpdate from '../records/visas/visa-create-update.vue';
    export default{
        name: 'visa-manager',
        components:{visaCreateUpdate},
        props:['reservationId', 'visaId', 'requirementId'],
        data(){
            return{
                visa:null,
                visas: [],

                //logic vars
                visaResource: this.$resource('visas{/id}'),
                reservationResource: this.$resource('reservations{/id}'),
                loaded: false,
                newState: false,
                changeState: false,

                // pagination vars
                paginatedVisas: [],
                selectedVisa: null,
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                    total_pages: 0
                },

            }
        },
        watch:{
            'page'(val, oldVal) {
                this.pagination.current_page = val;
                this.paginate();
            },
            'visas':(val) =>  {
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
                _.each(range, (index) => {
                    if (this.visas[index])
                        array.push(this.visas[index]);
                }, this);
                this.paginatedVisas = array;
            },
            setVisa(visa){
                if(visa) {
                    this.visa = visa;
                    this.reservationResource.put({id: this.reservationId}, {
                        visa_id: visa.id
                    }).then((response) => {
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
        mounted(){
            this.$http.get('users/me?include=visas').then((response) => {
                this.visas = response.data.data.visas.data;
                this.pagination.total_pages = Math.ceil(this.visas.length / this.per_page);
                this.visa = _.findWhere(response.data.data.visas.data, {id: this.visaId});
                this.loaded = true;
            });
        }
    }
</script>
