<template>
    <div class="row">
        <h3>Passports <a href="/dashboard/passports" class="btn btn-xs btn-default-hollow">View All</a></h3>
        <div class="row">
            <div class="col-sm-12" v-if="loaded && !passports.length">
                <div class="alert alert-info" role="alert">No records found</div>
            </div>

            <div class="col-sm-4" v-for="passport in passports">
                <div class="panel panel-default">
                    <div style="min-height:220px;" class="panel-body">
                        <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{passport.citizenship_name}}</h6>
                        <a role="button" :href="'/dashboard' + passport.links[0].uri">
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
            </div>
        </div>

        <hr class="divider">
        <h3>Visas <a href="/dashboard/visas" class="btn btn-xs btn-default-hollow">View All</a></h3>
        <div class="row">
            <div class="col-sm-12" v-if="loaded && !visas.length">
                <div class="alert alert-info" role="alert">No records found</div>
            </div>

            <div class="col-sm-4" v-for="visa in visas">
                <div class="panel panel-default">
                    <div style="min-height:220px;" class="panel-body">
                        <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{visa.country_name}}</h6>
                        <a role="button" :href="'/dashboard' + visa.links[0].uri">
                            <h5 style="text-transform:capitalize;" class="text-primary">
                                {{visa.given_names}} {{visa.surname}}
                            </h5>
                        </a>
                        <hr class="divider lg">
                        <p class="small">
                            <b>ID:</b> {{visa.number}}
                            <br>
                            <b>ISSUED ON:</b> {{visa.issued_at|moment 'll'}}
                            <br>
                            <b>EXPIRES ON:</b> {{visa.expires_at|moment 'll'}}
                        </p>
                    </div><!-- end panel-body -->
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
</template>
<script>
    export default{
        name: 'records-list',
        data(){
            return{
                visas: [],
                passports: [],

                //logic vars
                loaded: false,
            }
        },
        ready(){
            this.$http('users/me?include=passports,visas').then(function (response) {
                this.visas = response.data.data.visas.data;
                this.passports = response.data.data.passports.data;
                this.loaded = true;
            });
        }
    }
</script>
