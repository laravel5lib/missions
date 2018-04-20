<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"></slot>
            <select class="form-control" v-model="selection" :value="value">
                <option v-for="(value, key) in options" :value="key" :key="key">{{ value }}</option>
            </select>
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
    name: 'input-select',

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
        'options': {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            selection: null
        }
    },

    watch: {
        'selection'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);
        this.selection = this.value

        this.$root.$on('form:reset', () => {
            this.selection = null;
        });
    }
}
</script>
