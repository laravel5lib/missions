<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">
        <template v-if="horizontal">
            <slot name="label"><label>Team Roles</label></slot>
            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                            multiple 
                            class="form-control" 
                            :id="name" 
                            v-model="rolesObj"
                            :options="UTILITIES.roles" 
                            label="name"
                            :name="name">
                </v-select>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>
        </template>
        <template v-else>
            <slot name="label"><label>Team Roles</label></slot>
            <v-select @keydown.enter.prevent="null"
                        multiple 
                        class="form-control" 
                        :id="name" 
                        v-model="rolesObj"
                        :options="UTILITIES.roles" 
                        label="name"
                        :name="name">
            </v-select>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="($parent.form ? $parent.form.errors.has(name) : false)">
            </span>
            <slot name="help-text" v-else></slot>
        </template>
    </div>
</template>
<script>
import _ from 'underscore';
import vSelect from "vue-select";
import utilities from'./utilities.mixin';

export default {
    name: 'select-roles',

    components: {vSelect},

    mixins: [utilities],

    data() {
        return {
            rolesObj: null,
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Array,
            default: []
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-8'
        }
    },

    computed: {
        team_roles: {
            get() {
                return _.pluck(this.rolesObj, 'value') || [];
            },
            set() {}
        }
    },

    watch: {
        'team_roles'(value) {
            this.$emit('input', value);
            // this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        let promise = this.getRoles();

        promise.then((values) => {
            if (this.value) {
                this.rolesObj = _.filter(this.UTILITIES.roles, (p) => {
                    return _.some(this.value, function (role) {
                        return role.toLowerCase() === p.value.toLowerCase();
                    });
                });
            }
        });

        this.$root.$on('form:reset', () => {
            this.rolesObj = null;
        });
    }
}
</script>
