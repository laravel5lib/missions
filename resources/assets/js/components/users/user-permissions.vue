<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-header">Permissions</h5>
        </div>
        <div class="panel-body">
            <h5>Role(s)</h5>

            <div class="row" v-for="role in availableRoles">
                <div class="col-xs-12">
                    {{ role.name | capitalize }}
                    <button class="btn btn-xs btn-default-hollow pull-right"
                            v-if="!hasRole(role)"
                            @click="assign(role)">Assign</button>
                    <button class="btn btn-xs btn-default pull-right"
                            v-if="hasRole(role)"
                            @click="revoke(role)">Revoke</button>
                    <hr class="divider" />
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 text-center text-muted small">
                    <a @click="customize()"><i class="fa fa-cog"></i> Customize User Abilities</a>
                </div>
            </div>
        </div>
        <div class="panel-heading" v-if="showAbilities">
            <h5 class="panel-header">Abilities</h5>
        </div>
        <div class="panel-body" v-if="showAbilities">
            <div class="row">
                <div class="col-xs-12" v-for="ability in abilitiesList">
                    <h5>{{ $key | capitalize }}</h5>
                    <p v-for="item in ability">
                        {{ item.name | capitalize }}
                        <button class="btn btn-xs btn-default-hollow pull-right"
                                v-if="! hasAbility(item)"
                                @click="allow(item)">Allow</button>
                        <button class="btn btn-xs btn-default pull-right"
                                v-if="hasAbility(item)"
                                @click="deny(item)">Deny&nbsp;</button>
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
            }
        },
        data(){
            return {
                availableRoles: [],
                selectedRoles: [],
                availableAbilities: [],
                selectedAbilities: [],
                showAbilities: false
            }
        },
        computed: {
            roles() {
                return _.intersection(
                    _.pluck(this.availableRoles, 'id'),
                    _.pluck(this.selectedRoles, 'id')
                );
            },
            abilities() {
                return _.intersection(
                    _.pluck(this.availableAbilities, 'id'),
                    _.pluck(this.selectedAbilities, 'id')
                );
            },
            abilitiesList() {
                return _.groupBy(this.availableAbilities, function(ability){
                            return ability.entity_type ? ability.entity_type : 'application';
                        });
            }
        },
        methods: {
            hasRole(role)
            {
                return _.contains(this.roles, role.id);
            },
            hasAbility(ability)
            {
                return _.contains(this.abilities, ability.id);
            },
            customize() {
                this.showAbilities = !this.showAbilities;

                if(this.showAbilities) {
                    this.getAbilities();
                }
            },
            getAbilities() {
                this.$http.get('permissions/abilities').then(function (response) {
                    this.availableAbilities = response.data.data;
                });
            },
            fetch() {
                this.$http.get('permissions/roles').then(function (response) {
                    this.availableRoles = response.data.data;
                });
                this.$http.get('users/' + this.user_id + '?include=roles,abilities').then(function (response) {
                    this.selectedRoles = response.data.data.roles.data;
                    this.selectedAbilities = response.data.data.abilities.data;
                });
            },
            assign(role) {
                this.$http.post('users/' + this.user_id + '/roles', {
                    name: role.name
                }).then(function (response) {
                    this.selectedRoles.push(role);
                    console.log(response.data.message)
                });
            },
            revoke(role) {
                this.$http.delete('users/' + this.user_id + '/roles', {
                    name: role.name
                }).then(function (response) {
                    this.selectedRoles.$remove(role);
                    console.log(response.data.message)
                });
            },
            allow(ability) {
                this.$http.post('users/' + this.user_id + '/abilities', {
                    name: ability.name,
                    entity_type: ability.entity_type
                }).then(function (response) {
                    this.selectedAbilities.push(ability);
                    console.log(response.data.message)
                });
            },
            deny(ability) {
                this.$http.delete('users/' + this.user_id + '/abilities', {
                    name: ability.name,
                    entity_type: ability.entity_type
                }).then(function (response) {
                    this.selectedAbilities.$remove(ability);
                    // this.selectedAbilities = _.reject(this.selectedAbilities, function(a) {
                        // return a == ability;
                    // });
                    console.log(response.data.message)
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>