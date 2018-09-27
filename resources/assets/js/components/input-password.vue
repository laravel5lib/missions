<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">

        <template v-if="horizontal">

            <slot name="label"></slot>

            <div :class="classes">
                <div :class="{'input-group' : $slots.prefix || $slots.suffix}">
                    <slot name="prefix"></slot>
                    <input class="form-control"
                        type="password"
                        :name="name"
                        :value="value"
                        :readonly="readonly"
                        :placeholder="placeholder"
                        @input="updateValue($event.target.value)">
                    <slot name="suffix"></slot>
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

        <div :class="{'input-group' : $slots.prefix || $slots.suffix}">
            <slot name="prefix"></slot>
            <input class="form-control"
                type="password"
                :name="name"
                :value="value"
                :readonly="readonly"
                :placeholder="placeholder"
                @input="updateValue($event.target.value)">
            <slot name="suffix"></slot>
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
    name: 'input-password',

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
            default: 'col-sm-9'
        },
        'readonly': {
            type: Boolean
        }
    },

    methods: {
        updateValue(value) {
            this.$emit('input', value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    }
}
</script>
