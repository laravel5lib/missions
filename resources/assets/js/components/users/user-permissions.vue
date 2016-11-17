<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-header">Permissions <button class="btn btn-xs btn-default-hollow pull-right" style="margin-top:-3px"
                    @click="showAbilities = !showAbilities">
               <i class="fa fa-cog icon-left"></i> Manage 
            </button></h5>

        </div>
        <div class="panel-body">
            <label>User Roles</label>
            <v-select class="form-control"
                      multiple
                      :value.sync="selectedRoles"
                      :options="availableRoles"
                      label="roles">
            </v-select>
        </div>
        <div class="panel-body" v-if="showAbilities">
            <div class="list-gorup-item">
                <label>User Abilities</label>
                <v-select class="form-control"
                          :value.sync="selectedAbility"
                          :options="availableAbilities"
                          placeholder="add an ability"
                          label="abilities">
                </v-select>
            </div>
            <div class="list-group-item ability-item" v-for="ability in abilities">
                <div class="row">
                    <div class="col-xs-11">
                        <small class="text-muted">can</small> {{ ability.display_name }}
                    </div>
                    <div class="col-xs-1 col-sm-1 text-right">
                        <i class="fa fa-times fa-lg text-muted remove-ability">
                        </i>
                    </div>
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
                abilities: {},
                showAbilities: false,
                selectedRoles: ['member'],
                availableRoles: ['member', 'admin', 'intern', 'manager', 'facilitator']
            }
        },
        methods: {
            fetch() {
                this.$http.get('users/' + this.user_id + '/permissions').then(function (response) {
                    this.abilities = response.data.data;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>
<style lang="scss" scoped>
    div.list-group-item {
        cursor: pointer;
    }

    .remove-ability {
        display: none;
    }

    div.ability-item:hover i.remove-ability {
        display: inline;
    }

    i.remove-ability:hover {
        color: #d8262e;
    }

</style>