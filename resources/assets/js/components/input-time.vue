<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 

        <slot name="label"></slot>

        <template v-if="horizontal">

            <div :class="classes">
                <input class="form-control" 
                    v-model="time"
                    v-mask="format" 
                    :readonly="readonly"
                    :name="name"
                    :placeholder="placeholder">
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="$parent.form.errors.has(name)">
                </span>
                <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
            </div>

        </template>
        <template v-else>

            <input class="form-control" 
                v-model="time"
                v-mask="format" 
                :readonly="readonly"
                :name="name"
                :placeholder="placeholder">
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>

        </template>
    </div>
</template>
<script>
export default {
    name: 'input-time',

    data() {
        return {
            time: null
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
        'format': {
            type: String,
            default: '##:##'
        },
        'placeholder': {
            type: String,
            default: 'hh:mm'
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-9'
        },
        'readonly': {
            type: Boolean
        }
    },

    watch: {
        'time'(value) {
            this.$emit('input', value);
        }
    },

    mounted() {
        this.$nextTick(() =>  {
            if (this.value) {
                this.time = this.value;
            }
        });

        this.$root.$on('form:reset', () => {
            this.time = null;
        });
    }
}
</script>
