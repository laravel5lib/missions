<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">
        <template v-if="horizontal"> 
            <slot name="label"><label>Trip Rep</label></slot>
            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                        class="form-control"
                        :id="name"
                        v-model="repObj"
                        :on-search="getReps"
                        :options="reps"
                        label="email"
                        :name="name">
                        <template slot="option" slot-scope="option">
                                {{ option.email }} ({{ option.name }})
                        </template>
                </v-select>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>
        </template>
        <template v-else>
            <slot name="label"><label>Trip Rep</label></slot>
            <v-select @keydown.enter.prevent="null"
                      class="form-control"
                      :id="name"
                      v-model="repObj"
                      :on-search="getReps"
                      :options="reps"
                      label="email"
                      :name="name">
                       <template slot="option" slot-scope="option">
                            {{ option.email }} ({{ option.name }})
                       </template>
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
    name: 'select-rep',

    components: {vSelect},

    data() {
        return {
            repObj: null,
            reps: []
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
        rep_id() {
            return _.isObject(this.repObj) ? this.repObj.id : null;
        },
    },

    watch: {
        'rep_id'(value) {
            this.updateValue(value);
        }
    },

    methods: {
        getReps(search, loading){
            loading(true);
            this.$http.get('representatives', { params: {search: search} }).then((response) => {
                this.reps = response.data.data;
                loading(false);
            });
        },
        updateValue(value) {
            this.$emit('input', value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        if (this.value) {
            this.$http.get('representatives/' + this.value).then((response) => {
                this.repObj = response.data.data;
            });
        }

        this.$root.$on('form:reset', () => {
            this.repObj = null;
        });
    }
}
</script>
