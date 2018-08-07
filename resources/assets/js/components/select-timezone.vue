<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">
        <template v-if="horizontal">
            
            <slot name="label"><label>Timezone</label></slot>

            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                            class="form-control"
                            :name="name"
                            :id="name"
                            v-model="timezone"
                            :options="UTILITIES.timezones"
                            label="name">
                </v-select>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>

        </template>
        <template v-else>
            
            <slot name="label"><label>Timezone</label></slot>
                <v-select @keydown.enter.prevent="null"
                            class="form-control"
                            :name="name"
                            :id="name"
                            v-model="timezone"
                            :options="UTILITIES.timezones"
                            label="name">
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
    name: 'select-timezone',

    components: {vSelect},

    mixins: [utilities],

    data() {
        return {
            timezone: null,
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

    watch: {
        'timezone'(value) {
            this.$emit('input', value);
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        let promise = this.getTimezones();

        promise.then((values) => {
            if (this.value) {
                this.timezone = this.value;
            }
        });

        this.$root.$on('form:reset', () => {
            this.timezone = null;
        });
    }
}
</script>
