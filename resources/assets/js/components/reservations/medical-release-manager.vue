<template>
    <div class="row" v-if="loaded" style="position:relative">
        <spinner ref="spinner" global size="sm" text="Loading"></spinner>
        <div class="col-sm-12">
            <div class="text-center">
                <form novalidate>
                    <a class="btn btn-default-hollow btn-sm" @click="toggleChangeState()"><i class="fa fa-pencil icon-left"></i> Change Medical Release</a>
                    <a class="btn btn-primary-hollow btn-sm" href="/dashboard/records/medical-releases/create"><i class="fa fa-plus"></i> Add New Medical Release</a>
                </form>
            </div>
            <hr class="divider inv">
            <div class="panel panel-default" v-if="medicalRelease">
                <div style="min-height:220px;" class="panel-body">
                    <a role="button" :href="'/dashboard/records' + medicalRelease.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{medicalRelease.name}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>Insurance Provider:</b> {{medicalRelease.ins_provider}}
                        <br>
                        <b>Insurance Policy #:</b> {{medicalRelease.ins_policy_no}}
                        <br>
                        <b>Emergency Contact:</b><br>
                        {{medicalRelease.emergency_contact.name}} - {{medicalRelease.emergency_contact.relationship}}<br>
                        {{medicalRelease.emergency_contact.email}}<br>
                        {{medicalRelease.emergency_contact.phone | phone}}<br>
                        <!--<br>-->
                        <!--<b>EXPIRES ON:</b> {{medicalRelease.expires_at|moment('ll')}}-->
                    </p>
                </div>
            </div>
            <div v-if="!medicalRelease" role="alert"><p class="text-center text-muted"><em>This reservation has no Medical Release(s) assigned to it. Please select one or add one.</em></p></div>
        </div>

        <div class="col-sm-12" v-if="changeState">
            <div class="col-sm-12" v-if="loaded && !medicalReleases.length">
                <div class="alert alert-info" role="alert">No Medical Releases found. Please create add one.</div>
            </div>
            <div class="row" v-if="loaded && medicalReleases.length">
                <div class="col-sm-6" v-for="medicalRelease in paginatedMedicalReleases">
                    <div class="panel panel-default">
                        <div style="min-height:220px;" class="panel-body">
                            <a role="button" :href="'/dashboard/records' + medicalRelease.links[0].uri">
                                <h5 style="text-transform:capitalize;" class="text-primary">
                                    {{medicalRelease.name}}
                                </h5>
                            </a>
                            <hr class="divider lg">
                            <p class="small">
                                <b>Insurance Provider:</b> {{medicalRelease.ins_provider}}
                                <br>
                                <b>Insurance Policy #:</b> {{medicalRelease.ins_policy_no}}
                                <br>
                                <b>Emergency Contact:</b><br>
                                {{medicalRelease.emergency_contact.name}} - {{medicalRelease.emergency_contact.relationship}}<br>
                                {{medicalRelease.emergency_contact.email}}<br>
                                {{medicalRelease.emergency_contact.phone | phone}}<br>
                                <!--<br>-->
                                <!--<b>EXPIRES ON:</b> {{medicalRelease.expires_at|moment('ll')}}-->
                            </p>
                        </div><!-- end panel-body -->
                        <div class="panel-footer" style="padding: 0;">
                            <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                                <a class="btn btn-danger" @click="setMedicalRelease(medicalRelease)">
                                    Select Medical Release
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
            <medicalRelease-create-update></medicalRelease-create-update>
        </div>-->

    </div>
</template>
<script type="text/javascript">
    import medicalCreateUpdate from '../records/medicals/medical-create-update.vue';
    export default{
        name: 'medical-release-manager',
        components:{medicalCreateUpdate},
        props:['reservationId', 'medicalReleaseId', 'requirementId'],
        data(){
            return{
                medicalRelease:null,
                medicalReleases: [],

                //logic vars
                medicalReleaseResource: this.$resource('medical/releases{/id}'),
                reservationResource: this.$resource('reservations{/id}'),
                loaded: false,
                newState: false,
                changeState: false,

                // pagination vars
                paginatedMedicalReleases: [],
                selectedMedicalRelease: null,
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
            'medicalReleases':(val) =>  {
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
                    if (this.medicalReleases[index])
                        array.push(this.medicalReleases[index]);
                }, this);
                this.paginatedMedicalReleases = array;
            },
            setMedicalRelease(medicalRelease){
                if(medicalRelease) {
                    this.medicalRelease = medicalRelease;
                    this.reservationResource.put({id: this.reservationId}, {
                        medical_release_id: medicalRelease.id
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
            this.$http.get('users/me?include=medical_releases').then((response) => {
                this.medicalReleases = response.data.data.medical_releases.data;
                this.pagination.total_pages = Math.ceil(this.medicalReleases.length / this.per_page);
                this.medicalRelease = _.findWhere(this.medicalReleases, {id: this.medicalReleaseId});
                this.loaded = true;
            });

        }
    }
</script>
