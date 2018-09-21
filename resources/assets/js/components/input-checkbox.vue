<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        
        <template v-if="horizontal">

            <slot name="label"></slot>
            
            <div :class="classes">
                <input type="checkbox"
                    :name="name"
                    :value="value"
                    :checked="value"
                    @change="updateValue"
                    style="margin-top: 1em;">
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="$parent.form.errors.has(name)">
                </span>
                <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
            </div>

        </template>
        <template v-else>
            
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

        </template>
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
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-9'
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
