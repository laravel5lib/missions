<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"></slot>
            <div :class="{'input-group' : $slots.prefix || $slots.suffix}">
                <slot name="prefix"></slot>
                <input class="form-control" 
                    :name="name"
                    :value="value" 
                    :placeholder="placeholder"
                    @input="updateValue($event.target.value)"
                    type="number">
                <slot name="suffix"></slot>
            </div>
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
    name: 'input-number',

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Number,
            default: 0
        },
        'placeholder': {
            type: Number,
            default: null
        }
    },

    data() {
        return {
            number: 0
        }
    },

    methods: {
        updateValue(value) {
            this.$emit('input', parseInt(value));
            this.$emit('update:form', [this.name, parseInt(value)]);
        }
    }
}
</script>
