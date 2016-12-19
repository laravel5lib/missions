<template>
    <div class="row" v-if="loaded" style="position:relative">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-sm-12">
            <div class="text-center">
                <form novalidate>
                    <a class="btn btn-default-hollow btn-sm" @click="toggleChangeState()"><i class="fa fa-pencil icon-left"></i> Change Passport</a>
                    <a class="btn btn-primary-hollow btn-sm" href="/dashboard/records/passports/create"><i class="fa fa-plus icon-left"></i> Add New Passport</a>
                </form>
            </div>
            <hr class="divider inv">
            <div class="panel panel-default" v-if="passport">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{passport.citizenship_name}}</h6>
                    <a role="button" :href="'/dashboard/records' + passport.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{passport.given_names}} {{passport.surname}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>ID:</b> {{passport.number}}
                        <br>
                        <b>BIRTH COUNTRY:</b> {{passport.citizenship_name}}
                        <br>
                        <b>ISSUED ON:</b> {{passport.issued_at|moment 'll'}}
                        <br>
                        <b>EXPIRES ON:</b> {{passport.expires_at|moment 'll'}}
                    </p>
                </div><!-- end panel-body -->
            </div>
            <div v-if="!passport" role="alert"><p class="text-muted text-center"><em>This reservation has no passport(s) assigned to it. Please select one or add one.</em></p></div>
        </div>

        <div class="col-sm-12" v-if="changeState">
            <div class="row">
                <div class="col-sm-4" v-for="passport in passports">
                    <div class="panel panel-default">
                        <div style="min-height:220px;" class="panel-body">
                            <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{passport.citizenship_name}}</h6>
                            <a role="button" :href="'/dashboard/records' + passport.links[0].uri">
                                <h5 style="text-transform:capitalize;" class="text-primary">
                                    {{passport.given_names}} {{passport.surname}}
                                </h5>
                            </a>
                            <hr class="divider lg">
                            <p class="small">
                                <b>ID:</b> {{passport.number}}
                                <br>
                                <b>BIRTH COUNTRY:</b> {{passport.citizenship_name}}
                                <br>
                                <b>ISSUED ON:</b> {{passport.issued_at|moment 'll'}}
                                <br>
                                <b>EXPIRES ON:</b> {{passport.expires_at|moment 'll'}}
                            </p>
                        </div><!-- end panel-body -->
                        <div class="panel-footer" style="padding: 0;">
                            <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                                <a class="btn btn-danger" @click="setPassport(passport)">
                                    Select Passport
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 text-center">
                <pagination :pagination.sync="pagination"
                            :callback="fetch"
                            size="small">
                </pagination>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        name: 'passport-manager',
        props:{
            'reservationId': {
                type: String,
                required: true
            },
            'requirementId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                passport: null,
                passports: [],
                requirement: {},

                //logic vars
                requirementResource: this.$resource('reservations/{reservationId}/requirements/{requirementId}'),
                loaded: false,
                newState: false,
                changeState: false,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',

            }
        },
        watch:{
            'search': function (val, oldVal) {
                this.page = 1;
                this.fetch();
            },
            'page': function (val, oldVal) {
                this.fetch();
            },
            'per_page': function (val, oldVal) {
                this.fetch();
            }
        },
        methods:{
            setPassport(passport){
                if(passport) {
                    this.passport = passport;
                    this.requirementResource.update({reservationId: this.reservationId, requirementId: this.requirementId}, {
                        document_id: passport.id,
                        status: 'reviewing'
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
            fetch() {
                console.log(this.$root.user.id);
                var params = {
                    user: this.$root.user.id,
                    manager: this.$root.user.id,
                    include: 'document'
                };
                this.requirementResource.get({
                    reservationId: this.reservationId,
                    requirementId: this.requirementId
                }).then(function (response) {
                    this.requirement = response.data.data;
                });
                this.$http.get('passports', params).then(function (response) {
                    this.passports = response.data.data;
                    this.passport = _.findWhere(response.data.data, {id: this.requirement.document_id});
                    this.loaded = true;
                });
            }
        },
        ready(){
            this.fetch();
        }
    }
</script>
