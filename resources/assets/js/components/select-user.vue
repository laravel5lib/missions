<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        
        <template v-if="horizontal">
            <slot name="label">
                <label class="col-sm-4 control-label">Select a User</label>
            </slot>
            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                          class="form-control"
                          :id="name"
                          v-model="userObj"
                          :options="users"
                          :on-search="getUsers"
                          :placeholder="placeholder"
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
            <slot name="label">
                <label class="control-label">Select a User</label>
            </slot>
            <v-select @keydown.enter.prevent="null"
                      class="form-control"
                      :id="name"
                      v-model="userObj"
                      :options="users"
                      :on-search="getUsers"
                      :placeholder="placeholder"
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

export default {
    name: 'select-user',

    components: {vSelect},

    data() {
        return {
            userObj: null,
            users: []
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: String,
            default: null
        },
        'placeholder': {
            type: String,
            default: null
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
        user_id() {
            return _.isObject(this.userObj) ? this.userObj.id : null;
        }
    },

    watch: {
        'user_id'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    methods: {
        getUsers(search, loading){
            loading(true);
            this.$http.get('users', { params: {search: search} }).then((response) => {
                this.users = response.data.data;
                loading(false);
            });
        },
    },

    mounted() {
        this.$parent.form[this.name] = this.user_id;
        this.$parent.form.set(this.name, this.user_id);

        if (this.value) {
            this.$http.get('users/' + this.value).then((response) => {
                this.userObj = response.data.data;
            });
        }

        this.$root.$on('form:reset', () => {
            this.userObj = null;
        });
    }
}
</script>
