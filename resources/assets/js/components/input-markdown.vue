<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <slot name="label"></slot>
        <textarea class="form-control"
            v-autosize="content" 
            @input="updateValue($event.target.value)"
            :name="name"
            :value="value"
            :placeholder="placeholder"
            :rows="rows">
        </textarea>
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
    </div>
</template>
<script>
let marked = require('marked');
export default {
    name: 'admin-trip-description',

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
        'rows': {
            type: Number,
            default: 10
        }
    },

    methods: {
        marked: marked,
        updateValue(value) {
            this.$emit('input', value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    }
}
</script>