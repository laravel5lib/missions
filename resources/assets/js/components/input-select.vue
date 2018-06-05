<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        
        <template v-if="horizontal">
            <slot name="label">
                <label class="col-sm-4 control-label">Choose an Option</label>
            </slot>
            <div :class="classes">
                <select class="form-control" :value="value" @change="updateValue($event.target.value)">
                    <option v-for="(value, key) in options" :value="key" :key="key">{{ value }}</option>
                </select>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>
        </template>
        <template v-else>
            <slot name="label">
                <label class="control-label">Choose an Option</label>
            </slot>
            <select class="form-control" :value="value" @change="updateValue($event.target.value)">
                <option v-for="(value, key) in options" :value="key" :key="key">{{ value }}</option>
            </select>
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

    methods: {
        updateValue(value) {
            this.$emit('input', value);
            this.$parent.form.errors ? this.$parent.form.errors.clear(this.name) : null;
        }
    }
}
</script>
