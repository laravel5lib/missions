<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}">

        <template v-if="horizontal">

            <slot name="label"></slot>

            <div :class="classes">
                <textarea class="form-control"
                    :name="name"
                    :value="value"
                    :placeholder="placeholder"
                    :rows="rows"
                    @input="updateInput($event.target.value)">
                </textarea>

                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>

        </template>
        <template v-else>

            <slot name="label"></slot>
        
            <textarea class="form-control"
                :name="name"
                :value="value"
                :placeholder="placeholder"
                :rows="rows"
                @input="updateInput($event.target.value)">
            </textarea>

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
    name: 'input-textarea',

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
            default: 5
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
        updateInput(value) {
            this.$emit('input', value);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    }
}
</script>
