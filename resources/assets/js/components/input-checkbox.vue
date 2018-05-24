<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <input type="checkbox"
            :name="name"
            :value="value"
            :checked="value"
            @change="updateValue">
        <slot name="label"></slot>
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
    </div>
</template>
<script>
export default {
    name: 'input-text',

    data() {
        return {
            checked: false,
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Boolean,
            default: false
        }
    },

     methods: {
        updateValue() {
            this.$emit('input', !this.value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    }
}
</script>
