<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-header">Permissions</h5>
        </div>
        <div class="panel-body">
            <h5>Role(s)</h5>

            <div class="row" v-for="role in roles">
                <div class="col-xs-12">
                    {{ role.name | capitalize }}
                    <button class="btn btn-xs btn-default-hollow pull-right">Assign</button>
                    <hr />
                    <!--<button class="btn btn-xs btn-default ptn-pull-right">Revoke</button>-->
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 text-center text-muted small">
                    <a @click="customize()"><i class="fa fa-cog"></i> Customize User Abilities</a>
                </div>
            </div>

            <div class="row" v-if="showAbilities">
                <div class="col-xs-12"><h5>Abilities</h5></div>
                <hr class="divider inv">
                <div class="col-xs-12" v-for="ability in abilities">
                    <h5>{{ $key }}</h5>
                    <p v-for="item in ability">
                        {{ item.name | capitalize }}
                        <button class="btn btn-xs btn-default-hollow pull-right">Allow</button>
                        <!--<button class="btn btn-xs btn-default ptn-pull-right">Deny</button>-->
                        <hr class="divider" />
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    export default {
        name: 'user-permissions',
        components: {
            'vSelect': vSelect
        },
        props: {
            'user_id': {
                type: String
            },
            'userRoles': {
                type: Array
            }
        },
        data(){
            return {
                roles: [],
                selectedRoles: [],
                abilities: [],
                selectedAbilities: [],
                permissions: [],
                showAbilities: false
            }
        },
        methods: {
            customize() {
                this.showAbilities = !this.showAbilities;

                if(this.showAbilities) {
                    this.getAbilities();
                }
            },
            getAbilities() {
                this.$http.get('permissions/abilities').then(function (response) {
                    this.abilities = response.data.data;
                });
            },
            fetch() {
                this.$http.get('permissions/roles').then(function (response) {
                    this.roles = response.data.data;
                });
                this.$http.get('users/' + this.user_id + '/permissions').then(function (response) {
                    this.permissions = response.data.data;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>