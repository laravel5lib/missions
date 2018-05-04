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
                    @input="updateValue($event.target.value)">
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
    name: 'input-text',

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
        }
    },

    methods: {
        updateValue(value) {
            this.$emit('input', value);
        }
    },

    mounted() {
        // this.$parent.form[this.name] = this.value;
        // this.$parent.form.set(this.name, this.value);
        
        // this.$root.$on('form:reset', () => {
        //     this.text = null;
        // });
    }
}
</script>
