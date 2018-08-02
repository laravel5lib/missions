<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">

        <template v-if="horizontal">

            <slot name="label"></slot>

            <div :class="classes">
                <div class="input-group">
                    <label class="radio-inline" v-for="option in options" :key="option.value">
                        <input type="radio" :value="option.value" v-model="picked" style="margin-right: 1em">
                        {{ option.label }}
                    </label>
                </div>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>

        </template>
        <template v-else>
        
        <slot name="label"></slot>

        <div class="input-group">
            <div class="radio" v-for="option in options" :key="option.value">
                <label>
                    <input type="radio" :value="option.value" v-model="picked" style="margin-right: 1em">
                    {{ option.label }}
                </label>
            </div>
        </div>
        <span class="help-block" 
              v-text="$parent.form.errors.get(name)" 
              v-if="($parent.form ? $parent.form.errors.has(name) : false)">
        </span>
        <slot name="help-text" v-else></slot>

        </template>

    </div>
</template>
<script>
export default {
    name: 'input-radio-group',

    props: {
        'name': {
            type: String,
            required: true
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-9'
        },
        'options': {
            type: Array,
            required: true
        },
        'value': {}
    },

    data() {
        return {
            picked: null
        }
    },

    watch: {
        'picked'(value) {
            this.updateValue(value);
        }
    },

    methods: {
        updateValue(value) {
            this.$emit('input', value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    },

    mounted() {
        this.picked = this.value;
    }
}
</script>
