<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        
        <template v-if="horizontal">
            <slot name="label">
                <label class="col-sm-4 control-label">Select a Group</label>
            </slot>
            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                          class="form-control"
                          :id="name"
                          v-model="groupObj"
                          :options="groups"
                          :on-search="getGroups"
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
                <label class="control-label">Select a Group</label>
            </slot>
            <v-select @keydown.enter.prevent="null"
                      class="form-control"
                      :id="name"
                      v-model="groupObj"
                      :options="groups"
                      :on-search="getGroups"
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
    name: 'select-group',

    components: {vSelect},

    data() {
        return {
            groupObj: null,
            groups: []
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
        group_id() {
            return _.isObject(this.groupObj) ? this.groupObj.id : null;
        }
    },

    watch: {
        'group_id'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    methods: {
        getGroups(search, loading){
            loading(true);
            this.$http.get('groups', { params: {search: search} }).then((response) => {
                this.groups = response.data.data;
                loading(false);
            });
        },
    },

    mounted() {
        this.$parent.form[this.name] = this.group_id;
        this.$parent.form.set(this.name, this.group_id);

        if (this.value) {
            this.$http.get('groups/' + this.value).then((response) => {
                this.groupObj = response.data.data;
            });
        }

        this.$root.$on('form:reset', () => {
            this.groupObj = null;
        });
    }
}
</script>
