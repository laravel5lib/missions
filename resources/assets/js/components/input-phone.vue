<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        <slot name="label"></slot>

        <template v-if="horizontal">
        
        <div :class="classes">
            <the-mask class="form-control" 
                    v-model="phone" 
                    :mask="format" />
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="($parent.form ? $parent.form.errors.has(name) : false)">
            </span>
            <slot name="help-text" v-else></slot>
        </div>

        </template>
        <template v-else>

            <the-mask class="form-control" 
                    v-model="phone" 
                    :mask="format" />
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
    name: 'input-phone',

    data() {
        return {
            phone: null
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: String,
            default: null
        },
        'format': {
            type: String,
            default: '(###) ###-####'
        },
        'placeholder': {
            type: String,
            default: '(000) 000-0000'
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

    watch: {
        'phone'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);
        this.phone = this.value;

        this.$root.$on('form:reset', () => {
            this.phone = null;
        });
    }
}
</script>
