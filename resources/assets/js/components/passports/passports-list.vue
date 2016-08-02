<template>
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
</template>
<script>
    export default{
        name: 'passports-list',
        data(){
            return{
                passports: [],

                //logic vars
                loaded: false,
            }
        },
        ready(){
            this.$http('users/me?include=passports').then(function (response) {
                this.passports = response.data.data.passports.data;
                this.loaded = true;
            });
        }
    }
</script>
