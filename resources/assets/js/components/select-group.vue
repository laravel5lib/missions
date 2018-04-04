<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"><label>Group</label></slot>
            <v-select @keydown.enter.prevent="null"
                        class="form-control"
                        :id="name"
                        v-model="groupObj"
                        :options="groups"
                        :on-search="getGroups"
                        label="name"
                        :name="name">
            </v-select>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
        </div>
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
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

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
