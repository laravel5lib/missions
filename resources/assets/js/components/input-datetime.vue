<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <slot name="label"></slot>
        <input class="form-control" 
            v-model="date"
            v-mask="format" 
            :placeholder="placeholder"
            @input="updateInput($event.target.value)">
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
    </div>
</template>
<script>
export default {
    name: 'input-datetime',

    data() {
        return {
            date: null
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
            default: '##/##/#### ##:## aa'
        },
        'placeholder': {
            type: String,
            default: 'mm/dd/yyyy --:-- --'
        }
    },

    watch: {
        'date'(value) {
            this.$parent.form[this.name] = value ? moment(value).format('YYYY-MM-DD HH:mm:ss') : null;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        if (this.value) {
            this.date = moment(this.value).format('MM/DD/YYYY hh:mm a');
        }
    }
}
</script>
