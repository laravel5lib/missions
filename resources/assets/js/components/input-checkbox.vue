<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <input type="checkbox"
                :name="name"
                v-model="checked">
            <slot name="label"></slot>
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
            type: Number,
            default: null
        }
    },

    watch: {
        'checked'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        if (this.value) {
            this.checked = this.value;
        }
    }
}
</script>
