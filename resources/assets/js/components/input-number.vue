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
                    @input="updateInput($event.target.value)"
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

    methods: {
        updateInput(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);
    }
}
</script>
