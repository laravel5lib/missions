<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-header">Assign Roles</h5>
        </div>
        <div class="panel-body">
            <div class="panel-group" id="rolesAccordion" role="tablist" aria-multiselectable="true">
                <div class="panel" v-for="(role, index) in availableRoles">
                    <div class="panel-heading" role="tab" :id="'heading' + index">
                        <h4 class="panel-title">
                            <input type="checkbox" v-model="role.value" @change="handleRoleChange(role)">
                            &nbsp;
                            <a class="" role="button" data-toggle="collapse" data-parent="#rolesAccordion" :href="'#roleItem' + index" aria-expanded="true" aria-controls="collapseOne">
                                {{ role.name|underscoreToSpace|capitalize }}
                            </a>
                        </h4>
                    </div>
                    <div :id="'roleItem' + index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <h5>This Role has Permission to:</h5>
                            <ul class="list-unstyled small" v-if="role.permissions.data.length > 0">
                                <li v-for="permission in role.permissions.data">{{ permission.name|underscoreToSpace|capitalize }}</li>
                            </ul>
	                        <p class="small text-muted" v-else>No Permissions Given</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
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

                availablePermissions: [],
                selectedPermissions: [],

                showPermissions: false,
                ready: false,
            }
        },
        computed: {
            permissions() {
                return _.intersection(
                    _.pluck(this.availablePermissions, 'id'),
                    _.pluck(this.selectedPermissions, 'id')
                );
            },
            permissionsList() {
                return _.groupBy(this.availablePermissions, function(ability){
                    return ability.entity_type ? ability.entity_type : 'application';
                });
            }
        },
        methods: {
            hasAbility(ability)
            {
                return _.contains(this.permissions, ability.id);
            },
            hasAbilityByRole(ability)
            {
                return _.some(this.selectedRoles, function(role) {
                    return _.contains(_.pluck(role.permissions.data, 'id'), ability.id);
                });
            },
            customize() {
                this.showPermissions = !this.showPermissions;

                if(this.showPermissions) {
                    this.getPermissions();
                }
            },
            getPermissions() {
                this.$http.get('permissions/permissions').then((response) => {
                    this.availablePermissions = response.data.data;
                });
            },
            fetch() {
                this.$http.get('permissions/roles?include=permissions').then((response) => {
                    let roles = response.data.data;

                    this.$http.get('users/' + this.user_id + '?include=roles').then((response) => {
                        let user = response.data.data;
                        let currentRoleIds = _.pluck(user.roles.data, 'id');

                        // We will alter the availableRoles array so we can watch for changes when (de)selecting a role
                        _.each(roles, role => {
                            role.value = _.contains(currentRoleIds, role.id)
                        });

                        // availableRoles is now active
                        this.availableRoles = roles;
                        this.$nextTick(() => {
                            // The component is now ready to watch for changes to availableRoles
                            this.ready = true;
                        });
                    });
                });

            },
            assign(role) {
                this.$http.post('users/' + this.user_id + '/roles', {
                    name: role.name
                }).then((response) => {
                    this.$root.$emit('showSuccess', 'User permissions updated.')
                });
            },
            revoke(role) {
                this.$http.delete('users/' + this.user_id + '/roles/' + role.name).then((response) => {
                    this.$root.$emit('showSuccess', 'User permissions updated.')
                });
            },
            allow(ability) {
                this.$http.post('users/' + this.user_id + '/permissions', {
                    name: ability.name,
                    entity_type: ability.entity_type
                }).then((response) => {
                    this.selectedPermissions.push(ability);
                    this.$root.$emit('showSuccess', 'User permissions updated.')
                });
            },
            deny(ability) {
                this.$http.delete('users/' + this.user_id + '/permissions', {
                    name: ability.name,
                    entity_type: ability.entity_type
                }).then((response) => {
                    //this.selectedPermissions.$remove(ability);
                    var index = this.selectedPermissions.indexOf(_.findWhere(this.selectedPermissions, {id: ability.id}));
                    if (index !== -1) {
                      this.selectedPermissions.splice(index, 1);
                    }
                    this.$root.$emit('showSuccess', 'User permissions updated.')
                });
            },
            handleRoleChange(role) {
                if (this.ready) {
                    if (role.value) {
                        this.assign(role);
                    } else {
                        this.revoke(role);
                    }
                }
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>