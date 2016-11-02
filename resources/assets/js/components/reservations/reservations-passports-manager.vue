<template>
    <div class="row" v-if="loaded">
        <div class="col-sm-6">
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
            <div v-if="!passport" class="alert alert-info" role="alert">This reservation has no passport(s) assigned to it. Please select one or add one.</div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div style="min-height:220px;" class="panel-body">
                    <form novalidate>
                        <label>Actions</label>
                        <a class="btn btn-block btn-info btn-sm" @click="toggleChangeState()">
                            <i class="fa fa-pencil"></i> Change Passport
                        </a>
                        <a class="btn btn-block btn-primary btn-sm" href="/dashboard/records/passports/create">
                            <i class="fa fa-plus"></i> Add New Passport
                        </a>
                    </form>

                </div><!-- end panel-body -->
            </div>
        </div>

        <div class="col-sm-12" v-if="changeState">
            <div class="row">
                <div class="col-sm-4" v-for="passport in paginatedPassports">
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

        <!--<div class="col-sm-12" v-if="newState">
            <passport-create-update></passport-create-update>
        </div>-->

    </div>
</template>
<script>
    import passportCreateUpdate from '../records/passports/passport-create-update.vue';
    export default{
        name: 'reservations-passports-manager',
        components:{passportCreateUpdate},
        props:['reservationId', 'passportId'],
        data(){
            return{
                passport:null,
                passports: [],

                //logic vars
                passportResource: this.$resource('passports{/id}'),
                reservationResource: this.$resource('reservations{/id}'),
                loaded: false,
                newState: false,
                changeState: false,

                // pagination vars
                paginatedPassports: [],
                selectedPassport: null,
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
            'passports':function (val) {
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
                    if (this.passports[index])
                        array.push(this.passports[index]);
                }, this);
                this.paginatedPassports = array;
            },
            setPassport(passport){
                if(passport) {
                    this.passport = passport;
                    this.reservationResource.update({id: this.reservationId}, {
                        passport_id: passport.id
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
            this.$http('users/me?include=passports').then(function (response) {
                this.passports = response.data.data.passports.data;
                this.pagination.total_pages = Math.ceil(this.passports.length / this.per_page);
                this.passport = _.findWhere(response.data.data.passports.data, {id: this.passportId});
                this.loaded = true;
            });
        }
    }
</script>
