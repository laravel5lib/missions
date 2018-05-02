<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"></slot>
            <select class="form-control" :value="value" @change="updateValue($event.target.value)">
                <option v-for="(value, key) in options" :value="key" :key="key">{{ value }}</option>
            </select>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
        </div>
    </div>
</template>
<script>
export default {
    name: 'input-select',

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {},
        'placeholder': {
            type: String,
            default: null
        },
        'options': {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            selection: null
        }
    },

    methods: {
        updateValue(value) {
            this.$emit('input', value);
        }
    },

    mounted() {
        this.$root.$on('form:reset', () => {
            this.selection = null;
        });
    }
}
</script>
